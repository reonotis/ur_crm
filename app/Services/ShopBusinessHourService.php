<?php

namespace App\Services;

use App\Consts\ShopSettingConst;
use App\Http\Requests\BusinessHourRequest;
use App\Models\ShopBusinessHour;
use App\Repositories\ShopBusinessHourRepository;
use App\Repositories\ShopConfigRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;

/**
 *
 */
class ShopBusinessHourService
{
    private $shopBusinessHourRepository;
    private $shopConfigRepository;
    public $closeDay;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->shopBusinessHourRepository = new ShopBusinessHourRepository();
        $this->shopConfigRepository = new ShopConfigRepository();
    }

    /**
     * 対象店舗が閉店する場合の閉店日を返却する
     * @param int $shopId
     * @return Carbon|null
     */
    public function getCloseDay(int $shopId)
    {
        $shopConfig = $this->shopConfigRepository->getByKey($shopId, 'close_day');
        $this->closeDay = null;
        if ($shopConfig->value) {
            $this->closeDay = new Carbon($shopConfig->value);
        }

        return $this->closeDay;
    }

    /**
     * 1:毎日同じ時間に設定しているか、2:曜日ごとに設定しているか
     * @param int $shopId
     * @return int
     */
    public function getBusinessHourType(int $shopId)
    {
        $shopConfig = $this->shopConfigRepository->getByKey($shopId, 'business_hour_type');
        return $shopConfig->value;
    }

    /**
     * 対象店舗の営業時間設定リストを返却する
     * @param int $shopId
     * @param int $business_hour_type
     * @return Collection
     */
    public function getMyShopBusinessHourList(int $shopId, int $business_hour_type = 1)
    {
        $shopBusinessHours = $this->shopBusinessHourRepository->getMyShopBusinessHours($shopId, $business_hour_type);

        // 適用中かどうか判定値を付与して返却
        return $this->addDetermineApplicable($shopBusinessHours);
    }

    /**
     * 適用中かどうか判定値を付与して返却
     * @param Collection $shopBusinessHours
     * @return Collection
     */
    private function addDetermineApplicable(Collection $shopBusinessHours)
    {
        if ($shopBusinessHours->isEmpty()) {
            return $shopBusinessHours;
        }

        // 適用開始日順に並び替える
        $sortedBusinessHours = $shopBusinessHours->sortByDesc('setting_start_date');
        $today = new Carbon();
        $apply_after_flg = false; // ループ時に利用するフラグ

        // 適用中かどうかの判断値をセットする
        // ※ 矛盾しているデータは、登録更新時にチェックしているので、ここでは値をセットするだけです
        foreach ($sortedBusinessHours as $business) {
            $targetDate = new Carbon($business->setting_start_date);

            // 適用前の場合、「1」をセットする
            if ($today < $targetDate) {
                $business->applyType = ShopSettingConst::APPLY_BEFORE;
                continue;
            }

            // 適用中の判断
            if (!$apply_after_flg) {
                $business->applyType = ShopSettingConst::APPLYING;
                $apply_after_flg = true;
                continue;
            }

            // 適用後と判断する
            $business->applyType = ShopSettingConst::APPLY_AFTER;
        }

        return $sortedBusinessHours->values();
    }

    /**
     * 毎日同時間として、新しく登録するデータの適用開始日が設定して良い日付か確認する
     *
     * @param int $shopId
     * @param string $value 'Y-m-d'
     * @param int|null $shopBusinessHourId
     * @return string エラーメッセージ
     */
    public function confirmWhetherRegisterDate(int $shopId, string $value, int $shopBusinessHourId = null)
    {
        $checkSettingStartDate = new Carbon($value);
        if ($checkSettingStartDate->isPast()) {
            return '未来日を指定してください';
        }

        // 登録されているデータを取得
        $shopBusinessHours = $this->getMyShopBusinessHourList($shopId);

        // 既に設定されているデータよりも、更に未来日に適用させようとする場合
        if ($checkSettingStartDate > $shopBusinessHours->first()->setting_start_date) {
            // 閉店日が設定されている場合は閉店日以降の適用はさせない
            $closeDay = $this->getCloseDay($shopId);
            if ($closeDay && $checkSettingStartDate >= $closeDay) {
                return '閉店日以降の設定は出来ません';
            }
            return '';
        }

        // 同日に適用するデータが既に存在して入れエラーにする
        foreach ($shopBusinessHours as $businessHour) {
            //　編集時は適用日を変更しない可能性があるのでチェックしない
            if ($shopBusinessHourId && $businessHour->id === $shopBusinessHourId) {
                continue;
            }

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
    public function registerWithEveryday(BusinessHourRequest $request, int $shopId, int $userId)
    {
        // 既存で登録されているデータを取得
        $shopBusinessHours = $this->getMyShopBusinessHourList($shopId);

        // 登録する適用日のデータを作成
        $registerStartData = new Carbon($request->setting_start_date);

        // 適用させる前後のデータを取得
        list($beforeShopBusinessHour, $afterShopBusinessHour) = $this->getBeforeAndAfterData($registerStartData, $shopBusinessHours);
        if (empty($afterShopBusinessHour)) {
            // 一番未来に適用されるデータよりも、更に未来に適用させる場合
            $settingEndDate = $this->getCloseDay($shopId); // 閉店日が設定されて無ければnullになります
        } else {
            // 一つ未来の適用日の前日を設定する
            $settingEndDate = $afterShopBusinessHour->setting_start_date->subDays(1);
        }
        $param = [
            'shop_id' => $shopId,
            'business_hour_type' => ShopSettingConst::BUSINESS_HOUR_EVERYDAY,
            'business_open_time' => $request->business_open_time,
            'business_close_time' => $request->business_close_time,
            'last_reception_time' => $request->last_reception_time,
            'setting_start_date' => $registerStartData,
            'setting_end_date' => $settingEndDate,
            'created_by' => $userId,
        ];

        $this->shopBusinessHourRepository->insert($param);

        // 適用終了日を、登録しようとしている適用開始日の前日に更新
        $updateParam = [
            'setting_end_date' => $registerStartData->subDays(),
        ];
        $this->shopBusinessHourRepository->updateById($beforeShopBusinessHour->id, $updateParam);
    }

    /**
     * @param int $shopBusinessHourId
     * @param array $updateParam
     * @return void
     */
    public function updateShopBusinessHourById(int $shopBusinessHourId, array $updateParam)
    {
        $this->shopBusinessHourRepository->updateById($shopBusinessHourId, $updateParam);
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
     * 適用中もしくは適用前のデータを、再設定するする
     * @param int $shopId
     * @return void
     */
    public function resetSettingEndDate(int $shopId)
    {
        // 適用中以降のデータを取得
        $businessHourType = $this->getBusinessHourType($shopId);
        $records = $this->shopBusinessHourRepository->getApplyingOrLater($shopId, $businessHourType);

        $settingEndDate = $this->getCloseDay($shopId); // 閉店日が設定されて無ければnullになります
        foreach ($records as $record) {
            // 最初だったら
            if ($record === $records->first()) {
                $this->shopBusinessHourRepository->updateById($record->id, ['setting_end_date' => $settingEndDate]);

                // 適用開始日の前日をセットしておく
                $settingEndDate = $record->setting_start_date->subDays();
                continue;
            }

            // 1つ前のデータの適用開始日の前日を適用終了日にする
            $this->shopBusinessHourRepository->updateById($record->id, ['setting_end_date' => $settingEndDate]);
            $settingEndDate = $record->setting_start_date->subDays();
        }
    }

    /**
     * 適用させる日付の、前後の適用データを返却する
     * @param Carbon $farthestFutureDate
     * @param Collection $shopBusinessHours
     * @return array [Carbon, Carbon|null]
     */
    private function getBeforeAndAfterData(Carbon $farthestFutureDate, Collection $shopBusinessHours)
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
}
