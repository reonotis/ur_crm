<?php

namespace App\Services;

use App\Models\{ReserveInfo};
use App\Repositories\ReserveInfoRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 *
 */
class ReserveInfoService
{
    /** @var ReserveInfoRepository $reserveInfoRepository */
    private $reserveInfoRepository;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->reserveInfoRepository = app(ReserveInfoRepository::class);
    }

    /**
     * 本日の来店者情報を取得
     * @param int $shopId
     * @return Collection
     */
    public function getTodayVisitHistory(int $shopId): Collection
    {
        return $this->reserveInfoRepository->getTodayVisitHistory($shopId);
    }

    /**
     * 該当日のキャンセル以外の受付を取得
     * @param int $shopId
     * @param Carbon $date
     * @return Collection
     */
    public function getByDayAndShopId(int $shopId, Carbon $date): Collection
    {
        return $this->reserveInfoRepository->getByDayAndShopId($shopId, $date);
    }

    /**
     * 本日の来店者をスタイリスト別にカウントする
     * @param int $shop_id
     * @return object
     */
    public function getTodayOpeMemberByShopId(int $shop_id)
    {
        return $this->reserveInfoRepository->getTodayOpeMemberByShopId($shop_id);
    }

    /**
     */
    public function getTodayVisitHistoryByCustomerId(int $shop_id): ?ReserveInfo
    {
        return $this->reserveInfoRepository->getTodayVisitHistoryByCustomerId($shop_id);
    }

    /**
     */
    public function createRecord(array $condition)
    {
        // 値が渡されなかった場合のdefault値を設定
        if(!isset($condition['vis_date'])) {
            $condition['vis_date']=  Carbon::now()->format('Y-m-d');
        }
        if(!isset($condition['vis_time'])) {
            $condition['vis_time']= Carbon::now()->format('H:i');
        }
        if(!isset($condition['status'])) {
            $condition['status']= ReserveInfo::STATUS['GUIDED'];
        }
        if(!isset($condition['reserve_type'])) {
            $condition['reserve_type']= ReserveInfo::RESERVE_TYPE['CAME_SHOP'];
        }
        return $this->reserveInfoRepository->createRecord($condition);
    }

    /**
     * 対象顧客の本日の来店履歴を取得
     * @param int $customerId
     * @return Collection
     */
    public function getByCustomerId(int $customerId): Collection
    {
        return $this->reserveInfoRepository->getByCustomerId($customerId);

    }

    /**
     * 対象店舗の特定期間の来店履歴を取得する
     * @param Carbon $fromDate
     * @param Carbon $endDate
     * @param int $shopId
     * @return mixed
     */
    public function getByTargetPeriod(Carbon $fromDate,Carbon $endDate, int $shopId)
    {
        return $this->reserveInfoRepository->getByTargetPeriod($fromDate, $endDate, $shopId);
    }

    /**
     * 来店履歴のリストをスタイリスト毎の配列に変換する
     * @param object $list
     * @return array
     */
    public function getByTargetPeriodGroupByUser(Carbon $fromDate,Carbon $endDate, int $shopId): array
    {
        $list = $this->getByTargetPeriod($fromDate, $endDate, $shopId);
        if (empty($list)) {
            return [];
        }

        $data = [];
        foreach ($list as $row) {
            $data[$row['user_id']][] = $row;
        }
        return $data;
    }
}
