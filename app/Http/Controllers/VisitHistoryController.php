<?php

namespace App\Http\Controllers;

use App\Consts\{Common,
    ErrorCode,
    SessionConst
};
use App\Exceptions\ExclusionException;
use App\Http\Requests\ReserveInfoRequest;
use App\Models\{Customer,
    UserShopAuthorization,
    ReserveInfo,
    VisitHistoryImage,
    Menu
};
use App\Services\ImageService;
use App\Services\ReserveInfoService;
use App\Services\ReserveService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\{RedirectResponse,
    Request,
    UploadedFile
};
use Illuminate\Support\Facades\{DB,
    Log
};

/**
 *
 */
class VisitHistoryController extends UserAppController
{
    /** @var ImageService $ImageService 画像処理をするためのインスタンス */
    private $ImageService;

    /** @var ReserveInfoService $reserveInfoService */
    private $reserveInfoService;

    /** @var ReserveService $reserveService */
    private $reserveService;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
        $this->ImageService = new ImageService();
        $this->reserveInfoService = new ReserveInfoService();
        $this->reserveService = app(ReserveService::class);
    }

    /**
     * 本日の来店履歴を登録する
     * @param Customer $customer
     * @return RedirectResponse
     */
    public function register(Customer $customer): RedirectResponse
    {
        if ($this->reserveInfoService->getTodayVisitHistoryByCustomerId($customer->id)) {
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['既に本日の来店履歴が登録されています']);
        }

        try {
            DB::beginTransaction();
            // 来店履歴を登録する
            $condition = [
                'customer_id' => $customer->id,
                'shop_id' => $this->shopId,
                'user_id' => $this->loginUser->id,
            ];
            $this->reserveInfoService->createRecord($condition);
            DB::commit();
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['本日の来店履歴を登録しました']);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['来店履歴の登録に失敗しました']);
        }
    }

    /**
     * 編集画面を表示する
     * @param ReserveInfo $reserve_info
     * @return View
     * @throws ExclusionException
     */
    public function edit(ReserveInfo $reserveInfo): View
    {
        // 編集して良いかチェック
        $this->_checkEditing($reserveInfo);

        $menus = Menu::where('hidden_flag', 0)->orderBy('rank')->get();
        $users = UserShopAuthorization::getSelectableUsers($this->shopId)->get();
        $images = VisitHistoryImage::where('reserve_info_id', $reserveInfo->id)->get()->toArray();
        $sections = $this->reserveService->makeReserveSection(10, 30, 2);

        $sectionSelected = $this->reserveInfoService->getSectionValue($reserveInfo, $sections);

        return View('visitHistory.edit')->with([
            'reserve_info' => $reserveInfo,
            'users' => $users,
            'menus' => $menus,
            'images' => $images,
            'sections' => $sections,
            'selected' => $sectionSelected,
        ]);
    }

    /**
     * 来店履歴を更新する
     * @param Request $request
     * @param ReserveInfo $reserve_info
     * @return RedirectResponse
     * @throws ExclusionException
     */
    public function update(ReserveInfoRequest $request, ReserveInfo $reserve_info): RedirectResponse
    {
        // 編集して良いかチェック
        $this->_checkEditing($reserve_info);

        try {
            // ここからDB更新
            DB::beginTransaction();

            // ファイルを保存してDBに登録する
            foreach (Common::ANGLE_LIST as $angleKey => $angleName) {
                // 削除ボタンがチェックされていたら
                if (!empty($request->imgDelete[$angleKey])) {
                    // 論理削除
                    $this->deleteVisitHistoryImage($reserve_info->id, $angleKey);
                } else {
                    // 対象のアングル画像が送信されていたら登録する
                    if (!empty($request->image[$angleKey])) {
                        $this->_fileUpload($request->image[$angleKey], $reserve_info->customer_id, $reserve_info->id, $angleKey);
                    }
                }
            }
            $reserve_info->vis_time = $request->vis_time;
            $reserve_info->vis_end_time = $this->reserveInfoService->createEndTimeFromSection($reserve_info->vis_date, $request->vis_time, $request->section);
            $reserve_info->user_id = $request->user_id;
            $reserve_info->menu_id = $request->menu_id;
            $reserve_info->memo = $request->memo;
            $reserve_info->save();

            DB::commit();
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['来店履歴を更新しました']);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['来店履歴の更新に失敗しました', $e->getMessage()])->withInput();
        }
    }

    /**
     * @param ReserveInfo $reserve_info
     * @return RedirectResponse
     */
    public function destroy(ReserveInfo $reserve_info): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $reserve_info->delete();
            DB::commit();
            return redirect()->route('report.index')->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['来店履歴を削除しました']);
        } catch (Exception $e) {
            Log::error(' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['来店履歴の削除に失敗しました'])->withInput();
        }
    }

    /**
     * 編集して良いかチェックする
     * @param ReserveInfo $reserve_info
     * @return void
     * @throws ExclusionException
     */
    private function _checkEditing(ReserveInfo $reserve_info): void
    {
        // 本日の履歴ではない場合
        if (!Carbon::parse($reserve_info->vis_date)->isToday()) {
            $this->goToExclusionErrorPage(ErrorCode::CL_040004, [
                $reserve_info->id,
                $this->loginUser->id,
            ]);
        }

        // TODO 対象の店舗でない場合
    }

    /**
     * 画像をアップロードし、DBをアングル毎に更新すう
     * @param UploadedFile $file
     * @param int $customer_id
     * @param int $visitHistoryId
     * @param int $angle
     * @throws Exception
     */
    private function _fileUpload(UploadedFile $file, int $customer_id, int $visitHistoryId, int $angle)
    {
        // 画像を保存する
        $fileName = $this->ImageService->customerImgStore($file, $visitHistoryId, $this->loginUser->id);
        if ($fileName === '') {
            $errMsg = $this->ImageService->getErrorMsg();
            throw new Exception($errMsg);
        }

        // VisitHistoryImage
        VisitHistoryImage::updateOrInsert(
            [
                'customer_id' => $customer_id,
                'reserve_info_id' => $visitHistoryId,
                'angle' => $angle,
                'deleted_at' => null,
            ], [
                'img_pass' => $fileName,
            ]
        );
    }

    /**
     * 対象アングルの画像を削除する
     * @param int $visitHistoryId
     * @param int $angle
     * @return void
     */
    private function deleteVisitHistoryImage(int $visitHistoryId, int $angle)
    {
        VisitHistoryImage::where('reserve_info_id', $visitHistoryId)->where('angle', $angle)->delete();
    }
}
