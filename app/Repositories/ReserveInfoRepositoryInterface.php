<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\{ReserveInfo};
use Illuminate\Database\Eloquent\Collection;

interface ReserveInfoRepositoryInterface
{
    /**
     * 本日の予約を取得
     * @param int $shop_id
     * @return mixed
     */
    public function getTodayVisitHistory(int $shop_id): Collection;
    /**
     * 本日の予約を取得
     * @param int $shop_id
     * @return mixed
     */
    public function getTodayOpeMemberByShopId(int $shop_id): Collection;

    /**
     * 本日の予約を取得
     * @param int $shop_id
     * @return ReserveInfo|null
     */
    public function getTodayVisitHistoryByCustomerId(int $customerId): ?ReserveInfo;

    /**
     * @param int $customerId
     * @return Collection
     */
    public function getByCustomerId(int $customerId): Collection;

    /**
     * @param Carbon $fromDate
     * @param Carbon $endDate
     * @param int $shopId
     * @return Collection
     */
    public function getByTargetPeriod(Carbon $fromDate,Carbon $endDate, int $shopId);

    /**
     * 該当日のキャンセル以外の受付を取得
     * @param int $shopId
     * @param Carbon $date
     * @return Collection
     */
    public function getByDayAndShopId(int $shopId, Carbon $date): Collection;

}
