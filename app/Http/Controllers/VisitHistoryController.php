<?php

namespace App\Http\Controllers;

use App\Consts\{Common,
    ErrorCode,
    SessionConst
};
use App\Exceptions\ExclusionException;
use App\Models\{Customer,
    UserShopAuthorization,
    VisitHistory,
    VisitHistoryImage,
    Menu
};
use App\Services\ImageService;
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

class VisitHistoryController extends UserAppController
{
    /**
     * @var ImageService $ImageService 画像処理をするためのインスタンス
     */
    private $ImageService;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
        $this->ImageService = new ImageService();
    }

    /**
     * 本日の来店履歴を登録する
     * @param Customer $customer
     * @return RedirectResponse
     */
    public function register(Customer $customer): RedirectResponse
    {
        if (count(VisitHistory::getTodayVisitHistoryByCustomerId($customer->id)->get())) {
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['既に本日の来店履歴が登録されています']);
        }

        try {
            DB::beginTransaction();
            // 来店履歴を登録する
            VisitHistory::insert([[
                'vis_date' => Carbon::now()->format('Y-m-d'),
                'vis_time' => Carbon::now()->format('H:i:s'),
                'customer_id' => $customer->id,
                'shop_id' => $this->shopId,
                'user_id' => $this->loginUser->id,
            ]]);
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
     * @param VisitHistory $visitHistory
     * @return View
     * @throws ExclusionException
     */
    public function edit(VisitHistory $visitHistory): View
    {
        // 編集して良いかチェック
        $this->_checkEditing($visitHistory);

        $menus = Menu::where('hidden_flag', 0)->orderBy('rank')->get();
        $users = UserShopAuthorization::getSelectableUsers($this->shopId)->get();
        $images = VisitHistoryImage::where('visit_history_id', $visitHistory->id)->get()->toArray();

        return View('visitHistory.edit', compact('visitHistory', 'users', 'menus', 'images'));
    }

    /**
     * 来店履歴を更新する
     * @param Request $request
     * @param VisitHistory $visitHistory
     * @return RedirectResponse
     * @throws ExclusionException
     */
    public function update(Request $request, VisitHistory $visitHistory): RedirectResponse
    {
        // 編集して良いかチェック
        $this->_checkEditing($visitHistory);

        try {
            // ここからDB更新
            DB::beginTransaction();

            // ファイルを保存してDBに登録する
            foreach (Common::ANGLE_LIST as $angleKey => $angleName) {
                // 削除ボタンがチェックされていたら
                if (!empty($request->imgDelete[$angleKey])) {
                    // 論理削除
                    $this->deleteVisitHistoryImage($visitHistory->id, $angleKey);
                } else {
                    // 対象のアングル画像が送信されていたら登録する
                    if (!empty($request->image[$angleKey])) {
                        $this->_fileUpload($request->image[$angleKey], $visitHistory->customer_id, $visitHistory->id, $angleKey);
                    }
                }
            }

            $visitHistory->vis_time = $request->vis_time;
            $visitHistory->user_id = $request->user_id;
            $visitHistory->menu_id = $request->menu_id;
            $visitHistory->memo = $request->memo;
            $visitHistory->save();

            DB::commit();
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['来店履歴を更新しました']);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['来店履歴の更新に失敗しました', $e->getMessage()])->withInput();
        }
    }

    /**
     * @param VisitHistory $visitHistory
     * @return RedirectResponse
     */
    public function destroy(visitHistory $visitHistory): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $visitHistory->delete();
            DB::commit();
            return redirect()->route('report.index')->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['来店履歴を削除しました']);
        } catch (Exception $e) {
            Log::error(' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['来店履歴の削除に失敗しました'])->withInput();
        }
    }

    /**
     * 編集して良いかチェックする
     * @param VisitHistory $visitHistory
     * @return void
     * @throws ExclusionException
     */
    private function _checkEditing(VisitHistory $visitHistory): void
    {
        // 本日の履歴ではない場合
        if (!Carbon::parse($visitHistory->vis_date)->isToday()) {
            $this->goToExclusionErrorPage(ErrorCode::CL_040004, [
                $visitHistory->id,
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
                'visit_history_id' => $visitHistoryId,
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
        VisitHistoryImage::where('visit_history_id', $visitHistoryId)->where('angle', $angle)->delete();
    }
}
