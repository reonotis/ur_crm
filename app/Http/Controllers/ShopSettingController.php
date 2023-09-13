<?php

namespace App\Http\Controllers;

use App\Consts\SessionConst;
use App\Http\Requests\BusinessHourRequest;
use App\Models\ShopBusinessHour;
use App\Services\ShopBusinessHourService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class ShopSettingController extends UserAppController
{
    /**
     * @var ShopBusinessHourService $shopBusinessHourService
     */
    public $shopBusinessHourService;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
        $this->shopBusinessHourService = app(ShopBusinessHourService::class);
    }

    /**
     */
    public function index()
    {
        $closeDay = $this->shopBusinessHourService->getCloseDay($this->shopId);
        $businessHourType = $this->shopBusinessHourService->getBusinessHourType($this->shopId);
        $shopBusinessHours = $this->shopBusinessHourService->getMyShopBusinessHourList($this->shopId, $businessHourType);

        return view('shop_setting.index')->with([
            'closeDay' => $closeDay,
            'businessHourType' => $businessHourType,
            'shopBusinessHours' => $shopBusinessHours,
        ]);
    }

    /**
     * 編集画面
     */
    public function businessHourEdit()
    {
        // 閉店日
        $closeDay = $this->shopBusinessHourService->getCloseDay($this->shopId);

        // 現在登録されている営業時間のデータを取得
        $businessHourType = $this->shopBusinessHourService->getBusinessHourType($this->shopId);
        $shopBusinessHours = $this->shopBusinessHourService->getMyShopBusinessHourList($this->shopId, $businessHourType);

        $futureReserve = false; // TODO 予約機能が出来たら未来の予約があるか取得しておく

        return view('shop_setting.business_hour_edit')->with([
            'closeDay' => $closeDay,
            'businessHourType' => $businessHourType,
            'shopBusinessHours' => $shopBusinessHours,
            'futureReserve' => $futureReserve,
        ]);
    }

    /**
     * 毎日同時間として設定 する時に新しいレコードを追加する場合
     * @param BusinessHourRequest $request
     * @return RedirectResponse
     */
    public function businessHourRegisterWithEveryday(BusinessHourRequest $request)
    {
        try {
            DB::beginTransaction();
            // 登録更新処理
            $this->shopBusinessHourService->registerWithEveryday($request, $this->shopId, $this->loginUser->id);

            DB::commit();
            return redirect()->route('shop_setting.index')->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['新しい営業時間の設定を登録しました。']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error( ' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['新しい営業時間の設定に失敗しました'])->withInput();
        }
    }

    /**
     * @param BusinessHourRequest $request
     * @param ShopBusinessHour $shopBusinessHour
     * @return RedirectResponse
     */
    public function businessHourEditWithEveryday(BusinessHourRequest $request, ShopBusinessHour $shopBusinessHour)
    {
        try {
            DB::beginTransaction();
            // 更新処理
            $updateParam = [
                'business_open_time' => $request->{"business_open_time_$shopBusinessHour->id"},
                'business_close_time' => $request->{"business_close_time_$shopBusinessHour->id"},
                'last_reception_time' => $request->{"last_reception_time_$shopBusinessHour->id"},
                'setting_start_date' => $request->{"setting_start_date_$shopBusinessHour->id"},
            ];
            $this->shopBusinessHourService->updateShopBusinessHourById($shopBusinessHour->id, $updateParam);

            // 適用日が変わる可能性があるので、各レコードの適用終了日を修正する
            $this->shopBusinessHourService->resetSettingEndDate($this->shopId);

            DB::commit();
            return redirect()->route('shop_setting.index')->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['営業時間の設定を更新しました。']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['新しい営業時間の設定に失敗しました'])->withInput();
        }
    }

    /**
     * @param ShopBusinessHour $shopBusinessHour
     * @return RedirectResponse
     */
    public function businessHourDeleteWithEveryday(ShopBusinessHour $shopBusinessHour)
    {

        if ($shopBusinessHour->shop_id != $this->shopId) {
            dd('不正な削除をしようとしています');
        }

        $tomorrow = Carbon::today();
        if ($shopBusinessHour->setting_start_date <= $tomorrow) {
            dd('適用中、もしくは適用後のデータは削除できません');
        }

        try {
            DB::beginTransaction();
            // 削除処理
            $this->shopBusinessHourService->deleteShopBusinessHour($shopBusinessHour);

            // 歯抜けになるレコードが存在する可能性があるので、各レコードの適用終了日を修正する
            $this->shopBusinessHourService->resetSettingEndDate($this->shopId);

            DB::commit();
            return redirect()->route('shop_setting.index')->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['営業時間の設定を削除しました。']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['新しい営業時間の設定に失敗しました'])->withInput();
        }
    }
}

