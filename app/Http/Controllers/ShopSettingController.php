<?php

namespace App\Http\Controllers;

use App\Consts\SessionConst;
use App\Consts\ShopSettingConst;
use App\Http\Requests\BusinessHourRequest;
use App\Http\Requests\BusinessHourTemporaryRequest;
use App\Models\ShopBusinessHour;
use App\Models\ShopBusinessHourTemporary;
use App\Services\ShopBusinessHourService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use ReflectionException;

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
     * 店舗設定画面を表示
     * @return View
     * @throws ReflectionException
     */
    public function index(): View
    {
        // 閉店日を取得
        $closeDay = $this->shopBusinessHourService->getCloseDay($this->shopId);

        // 曜日始まりの設定を取得
        $weekList = $this->shopBusinessHourService->getWeekArray($this->shopId);

        // 登録されている営業時間設定のデータを取得
        $shopBusinessHours = $this->shopBusinessHourService->getMyShopBusinessHourList($this->shopId);

        // 現在適用されている営業時間設定を抽出
        $organizeBusinessHours = $this->shopBusinessHourService->getBusinessCalendarOnlyWeek($weekList, $shopBusinessHours);

        // 臨時営業・臨時定休を取得
        $temporaryBusinessHours = $this->shopBusinessHourService->getTemporaryBusinessHour($this->shopId);

        // カレンダー表示用の配列を作成
        $businessHourCalendars = $this->shopBusinessHourService->getBusinessCalendars($shopBusinessHours, $temporaryBusinessHours);

        return View('shop_setting.index')->with([
            'closeDay' => $closeDay,
            'weekList' => $weekList,
            'shopBusinessHours' => $shopBusinessHours,
            'organizeBusinessHours' => $organizeBusinessHours,
            'businessHourCalendars' => $businessHourCalendars,
        ]);
    }

    /**
     * 営業時間編集画面を表示
     * @return View
     * @throws ReflectionException
     */
    public function businessHourEdit(): View
    {
        // 閉店日が設定されているか取得
        $closeDay = $this->shopBusinessHourService->getCloseDay($this->shopId);

        // 曜日始まりの設定を取得
        $weekList = $this->shopBusinessHourService->getWeekArray($this->shopId);

        // 登録されている営業時間設定のデータを取得
        $shopBusinessHours = $this->shopBusinessHourService->getMyShopBusinessHourList($this->shopId);

        // 現在適用されている営業時間設定を抽出
        $organizeBusinessHours = $this->shopBusinessHourService->getBusinessCalendarOnlyWeek($weekList, $shopBusinessHours);

        // まだ適用される前の営業時間を抽出
        $beforeApplyBusinessHours = $shopBusinessHours->where('setting_start_date', '>', Carbon::today())->sortBy('setting_start_date')->values();

        // 臨時営業・臨時定休を取得
        $temporaryBusinessHours = $this->shopBusinessHourService->getTemporaryBusinessHour($this->shopId);

        // カレンダー表示用の配列を作成
        $businessHourCalendars = $this->shopBusinessHourService->getBusinessCalendars($shopBusinessHours, $temporaryBusinessHours);

        // 曜日選択用のセレクトボックスを作成
        $weekSelectOptions = [];
        foreach (ShopSettingConst::WEEK_LABEL_LIST as $value => $label) {
            $weekSelectOptions[$value]['value'] = $value;
            $weekSelectOptions[$value]['label'] = $label;
        }

        return View('shop_setting.business_hour_edit')->with([
            'closeDay' => $closeDay,
            'weekList' => $weekList,
            'shopBusinessHours' => $beforeApplyBusinessHours,
            'futureReserve' => false, // TODO 予約機能が出来たら未来の予約があるか取得しておく
            'organizeBusinessHours' => $organizeBusinessHours,
            'businessHourCalendars' => $businessHourCalendars,
            'weekSelectOptions' => $weekSelectOptions,
        ]);
    }

    /**
     * 営業時間設定の新しいレコードを追加
     * @param BusinessHourRequest $request
     * @return RedirectResponse
     */
    public function businessHourRegister(BusinessHourRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            // 登録更新処理
            $this->shopBusinessHourService->registerBusinessHour($request, $this->shopId, $this->loginUser->id);

            DB::commit();
            return redirect()->route('shop_setting.business_hour_edit')->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['新しい営業時間の設定を登録しました。']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['新しい営業時間の設定に失敗しました'])->withInput();
        }
    }

    /**
     * 営業時間の設定を削除する
     * @param ShopBusinessHour $shopBusinessHour
     * @return RedirectResponse
     */
    public function businessHourDelete(ShopBusinessHour $shopBusinessHour): RedirectResponse
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
            $this->shopBusinessHourService->resetSettingEndDate($this->shopId, $shopBusinessHour->week_no);

            DB::commit();
            return redirect()->route('shop_setting.business_hour_edit')->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['営業時間の設定を削除しました。']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['新しい営業時間の設定に失敗しました'])->withInput();
        }
    }

    /**
     * 閉店日更新画面を表示
     * @return View
     */
    public function closeDayEdit(): View
    {
        $closeDay = $this->shopBusinessHourService->getCloseDay($this->shopId);

        return View('shop_setting.close_day_edit')->with([
            'closeDay' => $closeDay,
            'futureReserve' => false, // TODO 予約機能が出来たら未来の予約があるか取得しておく
        ]);
    }

    /**
     * 店舗閉店日を更新する
     * @param Request $request
     * @return RedirectResponse
     */
    public function closeDayUpdate(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            // 閉店日を更新
            $this->shopBusinessHourService->updateCloseDay($this->shopId, $request->shop_close_day);

            // 営業時間の適用終了日を更新
            foreach (ShopSettingConst::WEEK_LABEL_LIST as $weekKey => $label) {
                $this->shopBusinessHourService->resetSettingEndDate($this->shopId, $weekKey);
            }

            DB::commit();
            return redirect()->route('shop_setting.index')->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['閉店日の設定を更新しました。']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['閉店日の更新に失敗しました'])->withInput();
        }
    }

    /**
     * 臨時定休/臨時営業画面を表示する
     * @return View
     * @throws ReflectionException
     */
    public function temporaryBusinessHourEdit(): View
    {
        // 閉店日が設定されているか取得
        $closeDay = $this->shopBusinessHourService->getCloseDay($this->shopId);

        // 曜日始まりの設定を取得
        $weekList = $this->shopBusinessHourService->getWeekArray($this->shopId);

        // 登録されている営業時間設定のデータを取得
        $shopBusinessHours = $this->shopBusinessHourService->getMyShopBusinessHourList($this->shopId);

        // 臨時定休/臨時営業の設定を取得
        $temporaryBusinessHours = $this->shopBusinessHourService->getTemporaryBusinessHour($this->shopId);

        // カレンダー表示用の配列を作成
        $businessHourCalendars = $this->shopBusinessHourService->getBusinessCalendars($shopBusinessHours, $temporaryBusinessHours);

        // 臨時定休/臨時営業の設定を 設定中以降のデータで抽出
        $featureTemporaryBusinessHours = $temporaryBusinessHours->where('target_date', '>=', new Carbon());

        return View('shop_setting.temporary_business_hour_edit')->with([
            'closeDay' => $closeDay,
            'weekList' => $weekList,
            'featureTemporaryBusinessHours' => $featureTemporaryBusinessHours,
            'businessHourCalendars' => $businessHourCalendars,
            'futureReserve' => false, // TODO 予約機能が出来たら未来の予約があるか取得しておく
        ]);
    }

    /**
     * 臨時定休/臨時営業画面を登録する
     * @param BusinessHourTemporaryRequest $request
     * @return RedirectResponse
     */
    public function temporaryBusinessHourRegister(BusinessHourTemporaryRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            // 登録処理
            $this->shopBusinessHourService->registerTemporaryBusinessHour($request, $this->shopId, $this->loginUser->id);

            DB::commit();
            return redirect()->route('shop_setting.temporary_business_hour_edit')->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['臨時定休/臨時営業を登録しました。']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['臨時定休/臨時営業の設定に失敗しました'])->withInput();
        }
    }

    /**
     * 臨時定休/臨時営業の設定を削除する
     * @param BusinessHourTemporaryRequest $request
     * @return RedirectResponse
     */
    public function temporaryBusinessHourDelete(ShopBusinessHourTemporary $shopBusinessHourTemporary): RedirectResponse
    {
        if ($shopBusinessHourTemporary->shop_id != $this->shopId) {
            dd('不正な削除をしようとしています');
        }

        $tomorrow = Carbon::today();
        if ($shopBusinessHourTemporary->target_date <= $tomorrow) {
            dd('適用中、もしくは適用後のデータは削除できません');
        }

        try {
            DB::beginTransaction();
            // 削除処理
            $this->shopBusinessHourService->deleteShopBusinessHourTemporary($shopBusinessHourTemporary);

            DB::commit();
            return redirect()->route('shop_setting.temporary_business_hour_edit')->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['臨時定休/臨時営業の設定を削除しました。']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['臨時定休/臨時営業の削除に失敗しました'])->withInput();
        }
    }

    /**
     * 閉店日更新画面を表示
     * @return View
     */
    public function startWeekEdit(): View
    {

        $weekList = $this->shopBusinessHourService->getStartWeek($this->shopId);

        $startWeekSelectOptions[ShopSettingConst::START_WEEK_SUNDAY]['value'] =ShopSettingConst::START_WEEK_SUNDAY;
        $startWeekSelectOptions[ShopSettingConst::START_WEEK_SUNDAY]['label'] = '日曜';
        $startWeekSelectOptions[ShopSettingConst::START_WEEK_MONDAY]['value'] =ShopSettingConst::START_WEEK_MONDAY;
        $startWeekSelectOptions[ShopSettingConst::START_WEEK_MONDAY]['label'] = '月曜';

        return View('shop_setting.start_week_edit')->with([
            'startWeekSelectOptions' => $startWeekSelectOptions,
            'selected' => $weekList->value,
        ]);
    }

    /**
     * 閉店日を更新
     * @param Request $request
     * @return RedirectResponse
     */
    public function startWeekUpdate(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            if(!in_array($request->start_week, [
                ShopSettingConst::START_WEEK_SUNDAY,
                ShopSettingConst::START_WEEK_MONDAY,
            ])){
                throw new Exception('不正なリクエストです');
            }

            $this->shopBusinessHourService->updateStartWeek($this->shopId, $request->start_week);

            DB::commit();
            return redirect()->route('shop_setting.index')->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['週始まりの設定を更新しました。']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['週始まりの設定更新に失敗しました'])->withInput();
        }
    }
}

