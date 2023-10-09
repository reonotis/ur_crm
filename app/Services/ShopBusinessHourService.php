<?php

namespace App\Services;

use App\Consts\ShopSettingConst;
use App\Http\Requests\BusinessHourRequest;
use App\Http\Requests\BusinessHourTemporaryRequest;
use App\Models\ShopBusinessHour;
use App\Models\ShopBusinessHourTemporary;
use App\Models\ShopConfig;
use App\Repositories\ShopBusinessHourRepository;
use App\Repositories\ShopBusinessHourTemporaryRepository;
use App\Repositories\ShopConfigRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use ReflectionException;
use Yasumi\Yasumi;

/**
 * 営業時間に関するサービスクラス
 *
 */
class ShopBusinessHourService
{
    /** @var ShopBusinessHourRepository $shopBusinessHourRepository */
    private $shopBusinessHourRepository;

    /** @var ShopBusinessHourTemporaryRepository $shopBusinessHourTemporaryRepository */
    private $shopBusinessHourTemporaryRepository;

    /** @var ShopConfigRepository $shopConfigRepository */
    private $shopConfigRepository;

    public $closeDay;
    public $holidays;

    /**
     * コンストラクタ
     * @throws ReflectionException
     */
    public function __construct()
    {
        $this->shopBusinessHourRepository = app(ShopBusinessHourRepository::class);
        $this->shopBusinessHourTemporaryRepository = app(ShopBusinessHourTemporaryRepository::class);
        $this->shopConfigRepository = app(ShopConfigRepository::class);

        $this->holidays = Yasumi::create('Japan', (new Carbon())->format('Y'), 'ja_JP');
    }

    /**
     * 対象店舗が閉店する場合の閉店日を返却する
     * 閉店日が設定されていなければnullを返却
     * @param int $shopId
     * @return Carbon|null
     */
    public function getCloseDay(int $shopId): ?Carbon
    {
        $shopConfig = $this->shopConfigRepository->getByKey($shopId, 'close_day');
        $this->closeDay = null;
        if ($shopConfig->value) {
            $this->closeDay = new Carbon($shopConfig->value);
        }

        return $this->closeDay;
    }

    /**
     * 対象店舗の閉店日を更新する
     * @param int $shopId
     * @param string|null $shopCloseDay
     * @return void
     */
    public function updateCloseDay(int $shopId, string $shopCloseDay = null)
    {
        $shopConfig = $this->shopConfigRepository->getByKey($shopId, 'close_day');
        $this->shopConfigRepository->updateByID($shopConfig->id, $shopCloseDay);
    }

    /**
     * @param int $shopId
     * @param int $startWeek
     * @return void
     */
    public function updateStartWeek(int $shopId, int $startWeek)
    {
        $shopConfig = $this->shopConfigRepository->getByKey($shopId, 'start_week');
        $this->shopConfigRepository->updateByID($shopConfig->id, $startWeek);
    }

    /**
     * @param int $shopId
     * @return ShopConfig
     */
    public function getStartWeek(int $shopId): ShopConfig
    {
        return $this->shopConfigRepository->getByKey($shopId, 'start_week');
    }

    /**
     * 対象店舗の開始曜日を取得して、曜日リストの配列を返却する
     * @param int $shopId
     * @return array
     */
    public function getWeekArray(int $shopId): array
    {
        $shopConfig = $this->shopConfigRepository->getByKey($shopId, 'start_week');
        switch ($shopConfig->value) {
            case(ShopSettingConst::START_WEEK_SUNDAY):
                // 日曜始まりの場合
                return ShopSettingConst::START_WEEK_SUNDAY_LIST;
            case(ShopSettingConst::START_WEEK_MONDAY):
                // 月曜始まりの場合
                return ShopSettingConst::START_WEEK_MONDAY_LIST;
            default;
                return [];
        }
    }

    /**
     * 対象店舗の営業時間設定リストを返却する
     * @param int $shopId
     * @return Collection
     */
    public function getMyShopBusinessHourList(int $shopId): Collection
    {
        return $this->shopBusinessHourRepository->getMyShopBusinessHours($shopId);
    }

    /**
     * 対象店舗の臨時営業/定休を返却する
     * @param int $shopId
     * @return Collection
     */
    public function getTemporaryBusinessHour(int $shopId): Collection
    {
        return $this->shopBusinessHourTemporaryRepository->getTemporaryBusinessHour($shopId);
    }

    /**
     * 今月から6ヵ月分の営業時間のcalendarを返却する
     * @param Collection $shopBusinessHours
     * @param Collection $temporaryBusinessHours
     * @return array
     * @throws ReflectionException
     */
    public function getBusinessCalendars(Collection $shopBusinessHours, Collection $temporaryBusinessHours): array
    {
        $today = Carbon::today();

        $calendars = [];
        for ($num = 0; $num < 6; $num++) {
            $calendars[] = $this->makeBusinessCalender($today, $shopBusinessHours, $temporaryBusinessHours);
        }

        return $calendars;
    }

    /**
     * 現在適用されている営業時間を返却
     * @param array $weekList // ShopSettingConst::START_WEEK_SUNDAY_LIST or ShopSettingConst::START_WEEK_MONDAY_LIST
     * @param Collection $shopBusinessHours
     * @return array
     */
    public function getBusinessCalendarOnlyWeek(array $weekList, Collection $shopBusinessHours): array
    {
        $today = new Carbon();

        // 曜日をループ
        $thisWeekBusinessHours = [];
        foreach ($weekList as $week) {
            // 抽出対象の曜日の設定に絞る
            $shopBusinessHoursOnlyDayOfWeek = $shopBusinessHours->where('week_no', $week);
            // 現在適用されている営業時間をセット
            $thisWeekBusinessHours[$week] = $this->getBusinessHourByTargetData($today, $shopBusinessHoursOnlyDayOfWeek);
        }

        // 祝日の設定をセット
        $holiday = ShopSettingConst::HOLIDAY;
        $shopBusinessHoursOnlyDayOfWeek = $shopBusinessHours->where('week_no', $holiday);
        $thisWeekBusinessHours[$holiday] = $this->getBusinessHourByTargetData($today, $shopBusinessHoursOnlyDayOfWeek);

        // 祝前日の設定をセット
        $beforeHoliday = ShopSettingConst::BEFORE_HOLIDAY;
        $shopBusinessHoursOnlyDayOfWeek = $shopBusinessHours->where('week_no', $beforeHoliday);
        $thisWeekBusinessHours[$beforeHoliday] = $this->getBusinessHourByTargetData($today, $shopBusinessHoursOnlyDayOfWeek);

        return $thisWeekBusinessHours;
    }

    /**
     * 毎日同時間として、新しく登録するデータの適用開始日が設定して良い日付か確認する
     * @param int $shopId
     * @param Carbon $checkSettingStartDate
     * @param int $weekNo
     * @return string エラーメッセージ
     */
    public function confirmWhetherRegisterDate(int $shopId, Carbon $checkSettingStartDate, int $weekNo): string
    {
        if ($checkSettingStartDate->isPast()) {
            return '未来日を指定してください';
        }

        // 登録されているデータを取得
        $shopBusinessHours = $this->getMyShopBusinessHourList($shopId);
        $shopBusinessHours = $shopBusinessHours->where('week_no', $weekNo);

        // 閉店日が設定されている場合は閉店日以降の適用はさせない
        $closeDay = $this->getCloseDay($shopId);
        if ($closeDay && $checkSettingStartDate > $closeDay) {
            return '閉店日以降の設定は出来ません';
        }

        // 同日に適用するデータが既に存在している場合はエラーにする
        foreach ($shopBusinessHours as $businessHour) {
            $targetDate = new Carbon($businessHour->setting_start_date);
            if ($targetDate == $checkSettingStartDate) {
                return '同じ日に適用されるデータが既に存在しています';
            }
        }

        return '';
    }

    /**
     * 新しいレコードを作成し、1つ前に適用させるレコードの適用終了日を更新する
     * @param BusinessHourRequest $request
     * @param int $shopId
     * @param int $userId
     * @return void
     */
    public function registerBusinessHour(BusinessHourRequest $request, int $shopId, int $userId)
    {
        // 登録する適用日のデータ
        $settingStartDate = new Carbon($request->setting_start_date);

        // 既存で登録されているデータを取得
        $shopBusinessHours = $this->getMyShopBusinessHourList($shopId);

        // 対象曜日の設定を抽出する
        $shopBusinessHours = $shopBusinessHours->where('week_no', $request->day_of_week);

        // 適用させる前後のデータを取得
        list($beforeShopBusinessHour, $afterShopBusinessHour) = $this->getBeforeAndAfterData($settingStartDate, $shopBusinessHours);

        if (empty($afterShopBusinessHour)) {
            // 一番未来に適用されるデータよりも、更に未来に適用させる場合
            // 閉店日が設定されていれば閉店日、設定されて無ければnull
            $settingEndDate = $this->getCloseDay($shopId);
        } else {
            // 一つ未来の適用日の前日を設定する
            $settingEndDate = $afterShopBusinessHour->setting_start_date->subDays(1);
        }

        // 登録するパラメータの作成
        $param = [
            'shop_id' => $shopId,
            'week_no' => $request->day_of_week,
            'setting_start_date' => $settingStartDate,
            'setting_end_date' => $settingEndDate,
            'created_by' => $userId,
        ];

        if ($request->regular_holiday) {
            $param['regular_holiday'] = 1;
        } else {
            $param['business_open_time'] = $request->business_open_time;
            $param['business_close_time'] = $request->business_close_time;
            $param['last_reception_time'] = $request->last_reception_time;
        }

        $this->shopBusinessHourRepository->insert($param);

        // 適用させるデータよりも過去のデータがある場合
        if ($beforeShopBusinessHour) {
            // 適用終了日を、登録しようとしている適用開始日の前日に更新
            $updateParam = [
                'setting_end_date' => $settingStartDate->subDays(),
            ];
            $this->shopBusinessHourRepository->updateById($beforeShopBusinessHour->id, $updateParam);
        }
    }

    /**
     * @param BusinessHourTemporaryRequest $request
     * @param int $shopId
     * @param int $userId
     * @return void
     */
    public function registerTemporaryBusinessHour(BusinessHourTemporaryRequest $request, int $shopId, int $userId)
    {
        // 登録するパラメータの作成
        $param = [
            'shop_id' => $shopId,
            'created_by' => $userId,
            'target_date' => $request->target_date,
        ];

        if ($request->regular_holiday == 1) {
            $param['holiday'] = $request->regular_holiday;
        } else {
            $param['business_open_time'] = $request->business_open_time;
            $param['last_reception_time'] = $request->last_reception_time;
            $param['business_close_time'] = $request->business_close_time;
        }

        $this->shopBusinessHourTemporaryRepository->insert($param);
    }

    /**
     * 論理削除を行う
     * @param ShopBusinessHour $shopBusinessHour
     * @return void
     * @throws Exception
     */
    public function deleteShopBusinessHour(ShopBusinessHour $shopBusinessHour)
    {
        $this->shopBusinessHourRepository->delete($shopBusinessHour);
    }

    /**
     * 論理削除を行う
     * @param ShopBusinessHourTemporary $shopBusinessHourTemporary
     * @return void
     * @throws Exception
     */
    public function deleteShopBusinessHourTemporary(ShopBusinessHourTemporary $shopBusinessHourTemporary)
    {
        $this->shopBusinessHourTemporaryRepository->delete($shopBusinessHourTemporary);
    }

    /**
     * 適用中もしくは適用前のデータを、再設定するする
     * @param int $shopId
     * @param int $weekNo
     * @return void
     * @throws Exception
     */
    public function resetSettingEndDate(int $shopId, int $weekNo): void
    {
        // 適用中以降のデータを取得
        $shopBusinessHours = $this->shopBusinessHourRepository->getApplyingOrLater($shopId);
        $shopBusinessHours = $shopBusinessHours->where('week_no', $weekNo);

        $settingEndDate = $this->getCloseDay($shopId); // 閉店日が設定されて無ければnullになります

        // 閉店日よりも未来に適用しようとしていたレコードは削除する
        $featureShopBusinessHours = $shopBusinessHours->where('setting_start_date', '>', $settingEndDate);
        if ($featureShopBusinessHours) {
            foreach ($featureShopBusinessHours as $shopBusinessHour) {
                $this->shopBusinessHourRepository->delete($shopBusinessHour);
            }
            $shopBusinessHours = $this->shopBusinessHourRepository->getApplyingOrLater($shopId);
            $shopBusinessHours = $shopBusinessHours->where('week_no', $weekNo);
        }

        foreach ($shopBusinessHours as $shopBusinessHour) {
            // 最初だったら
            if ($shopBusinessHour === $shopBusinessHours->first()) {
                $this->shopBusinessHourRepository->updateById($shopBusinessHour->id, ['setting_end_date' => $settingEndDate]);

                // 適用開始日の前日をセットしておく
                $settingEndDate = $shopBusinessHour->setting_start_date->subDays();
                continue;
            }

            // 1つ前のデータの適用開始日の前日を適用終了日にする
            $this->shopBusinessHourRepository->updateById($shopBusinessHour->id, ['setting_end_date' => $settingEndDate]);
            $settingEndDate = $shopBusinessHour->setting_start_date->subDays();
        }
    }

    /**
     * @param int $shopId
     * @param Carbon $targetDate
     * @return ShopBusinessHourTemporary|null
     */
    public function getShopBusinessHourTemporaryByTargetDate(int $shopId, Carbon $targetDate): ?ShopBusinessHourTemporary
    {
        return $this->shopBusinessHourTemporaryRepository->getShopBusinessHourTemporaryByTargetDate($shopId, $targetDate);
    }

    /**
     * 適用させる日付の、前後の適用データを返却する
     * @param Carbon $farthestFutureDate
     * @param Collection $shopBusinessHours
     * @return array [Carbon, Carbon|null]
     */
    private function getBeforeAndAfterData(Carbon $farthestFutureDate, Collection $shopBusinessHours): array
    {
        foreach ($shopBusinessHours as $key => $shopBusinessHour) {

            // 設定する適用開始日よりも古い適用開始日のデータだったら
            if ($shopBusinessHour->setting_start_date < $farthestFutureDate) {
                // 1つ手前で適用させるデータ
                $beforeShopBusinessHour = $shopBusinessHour;

                // 1つ後で適用させるデータ
                $afterShopBusinessHour = null;
                if (isset($shopBusinessHours[$key - 1])) {
                    $afterShopBusinessHour = $shopBusinessHours[$key - 1];
                }
                return [$beforeShopBusinessHour, $afterShopBusinessHour];
            }
        }

        // 一番古いデータの為、それ以前のデータは無い
        return [null, $shopBusinessHours->last()];
    }

    /**
     * 渡された日付の月の営業時間calendarを作成
     * @param Carbon $targetDay
     * @param Collection $shopBusinessHours
     * @return array
     * @throws ReflectionException
     */
    private function makeBusinessCalender(Carbon $targetDay, Collection $shopBusinessHours, Collection $temporaryBusinessHours): array
    {
        $targetDay = $targetDay->setDay(1);// 対象日の月初
        $endOfMonth = $targetDay->copy()->endOfMonth();// 月末

        // 対象月の配列を作成する
        $monthData = [];
        for ($i = 0; true; $i++) {
            $monthData[$i] = $this->makeBusinessCalenderByDate($targetDay, $shopBusinessHours, $temporaryBusinessHours);
            $targetDay->addDay();
            if ($targetDay > $endOfMonth) {
                break;
            }
        }
        return $monthData;
    }

    /**
     * @param Carbon $targetDay
     * @param Collection $shopBusinessHours
     * @return array
     * @throws ReflectionException
     */
    private function makeBusinessCalenderByDate(Carbon $targetDay, Collection $shopBusinessHours, Collection $temporaryBusinessHours): array
    {
        $businessData['date'] = new Carbon($targetDay->format('Y-m-d')); // カレンダーに入れる日付
        $holidayNumber = $this->checkHoliday($targetDay); // 祝日、祝前日であるかを判定
        $businessData['holiday'] = $holidayNumber;
        $businessData['temporary'] = $this->checkTemporaryDay($targetDay, $temporaryBusinessHours); // 臨時定休/臨時営業ではない場合はNULL
        $shopBusinessHour = $this->getBusinessHourByDate($targetDay, $shopBusinessHours, $holidayNumber); // 対象日の営業時間を取得
        $businessData['business_hour'] = $shopBusinessHour;
        $businessData['regular_holiday'] = $this->checkRegularHoliday($shopBusinessHour);
        $businessData['close'] = $this->checkShopClose($targetDay);

        return $businessData;
    }

    /**
     * 店舗が閉店していなるかを判定
     * @param Carbon $targetDay
     * @return bool
     */
    private function checkShopClose(Carbon $targetDay): bool
    {
        if (is_null($this->closeDay)) {
            return false;
        }

        if ($targetDay > $this->closeDay) {
            return true;
        }

        return false;
    }

    /**
     * 祝日、祝前日を判定
     * 通常 = 0
     * 祝日 = ShopSettingConst::BEFORE_HOLIDAY (9)
     * 祝前日 = ShopSettingConst::HOLIDAY (8)
     * @param Carbon $targetDay
     * @return int
     * @throws ReflectionException
     */
    private function checkHoliday(Carbon $targetDay): int
    {
        $this->makeHolidayInstance($targetDay);

        // 祝日の場合
        if ($this->holidays->isHoliday($targetDay)) {
            return ShopSettingConst::HOLIDAY;
        }

        // 祝前日の確認の為、翌日を取得しておく
        $nextDay = $targetDay->copy()->addDay();
        $this->makeHolidayInstance($nextDay);

        // 祝前日の場合
        if ($this->holidays->isHoliday($nextDay)) {
            return ShopSettingConst::BEFORE_HOLIDAY;
        }

        // 祝日でも祝前日でもない
        return 0;
    }

    /**
     * @param Carbon $targetDay
     * @param Collection $temporaryBusinessHours
     * @return ShopBusinessHourTemporary|null
     */
    private function checkTemporaryDay(Carbon $targetDay, Collection $temporaryBusinessHours): ?ShopBusinessHourTemporary
    {
        $filtered = $temporaryBusinessHours->where('target_date', $targetDay->format('Y-m-d'));
        if($filtered->count()) {
            return $filtered->first();
        }
        return null;
    }

    /**
     * @param Carbon|null $targetDay
     * @return void
     * @throws ReflectionException
     */
    private function makeHolidayInstance(Carbon $targetDay = null)
    {
        if (is_null($targetDay)) {
            $targetDay = new Carbon();
        }

        $alreadyInstanceYear = $this->holidays->getYear(); // 既に作成されているインスタンス年
        $holidayYear = (int)$targetDay->format('Y'); //

        // 対象年が別ならインスタンスを作り直す
        if ($holidayYear <> $alreadyInstanceYear) {
            $this->holidays = Yasumi::create('Japan', $holidayYear, 'ja_JP');
        }
    }

    /**
     * 定休日か判定
     * @param ShopBusinessHour|null $shopBusinessHour
     * @return bool
     */
    private function checkRegularHoliday(?ShopBusinessHour $shopBusinessHour): bool
    {
        if (is_null($shopBusinessHour)) {
            return false;
        }
        if ($shopBusinessHour->regular_holiday) {
            return true;
        }
        return false;
    }

    /**
     * 引数で渡られた日付の曜日や祝日なのか等をを判断して、適用されている営業時間を返却する
     * 祝日機能が出来たら改修
     * @param Carbon $targetDay
     * @param Collection $shopBusinessHours
     * @param int $holidayNumber
     * @return ShopBusinessHour|null
     */
    private function getBusinessHourByDate(Carbon $targetDay, Collection $shopBusinessHours, int $holidayNumber = 0): ?ShopBusinessHour
    {
        // 祝日か判定して、営業時間を取得してreturnする処理
        if ($holidayNumber == ShopSettingConst::HOLIDAY) {
            // 祝日に絞り込んだ営業時間の設定
            $shopBusinessHoursOnlyHoliday = $shopBusinessHours->where('week_no', ShopSettingConst::HOLIDAY);
            // 祝日が設定されていない場合は、その曜日の設定を抽出する
            if ($shopBusinessHoursOnlyHoliday->isEmpty()) {
                $shopBusinessHoursOnlyHoliday = $shopBusinessHours->where('week_no', $targetDay->dayOfWeekIso);
            }
            return $this->getBusinessHourByTargetData($targetDay, $shopBusinessHoursOnlyHoliday);
        }

        // 祝前日か判定して、営業時間を取得してreturnする処理
        if ($holidayNumber == ShopSettingConst::BEFORE_HOLIDAY) {
            // 祝前日に絞り込んだ営業時間の設定
            $shopBusinessHoursOnlyBeforeHoliday = $shopBusinessHours->where('week_no', ShopSettingConst::BEFORE_HOLIDAY);

            // 祝前日が設定されていない場合は、その曜日の設定を抽出する
            if ($shopBusinessHoursOnlyBeforeHoliday->isEmpty()) {
                $shopBusinessHoursOnlyBeforeHoliday = $shopBusinessHours->where('week_no', $targetDay->dayOfWeekIso);
            }
            return $this->getBusinessHourByTargetData($targetDay, $shopBusinessHoursOnlyBeforeHoliday);
        }

        // 対象曜日に絞り込んだ営業時間の設定
        $targetDayOfWeek = $targetDay->dayOfWeekIso; // 対象日の曜日を取得
        $shopBusinessHoursOnlyDayOfWeek = $shopBusinessHours->where('week_no', $targetDayOfWeek);
        return $this->getBusinessHourByTargetData($targetDay, $shopBusinessHoursOnlyDayOfWeek);
    }

    /**
     * 引数で渡された日付に適用されている営業時間を返却する
     * @param Carbon $targetDay
     * @param Collection $shopBusinessHoursOnlyDayOfWeek 特定の曜日に絞られたレコード
     * @return ShopBusinessHour|null
     */
    private function getBusinessHourByTargetData(Carbon $targetDay, Collection $shopBusinessHoursOnlyDayOfWeek): ?ShopBusinessHour
    {
        $shopBusinessHours = $shopBusinessHoursOnlyDayOfWeek->sortByDesc('setting_start_date');

        foreach ($shopBusinessHours as $shopBusinessHour) {
            if ($targetDay < $shopBusinessHour->setting_start_date) {
                continue; // 適用前
            }
            if (!is_null($shopBusinessHour->setting_end_date)
                && $targetDay > $shopBusinessHour->setting_end_date) {
                continue; // 適用終了
            }
            return $shopBusinessHour; // 適用中
        }
        return null; // まだ設定されている営業時間はない
    }

}
