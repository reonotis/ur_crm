<?php

namespace App\Repositories;

use App\Models\{VisitReserve};
use Carbon\Carbon;

class ReserveRepository implements ReserveRepositoryInterface
{
    /**
     * 本日の予約を取得
     * @param Carbon $date
     * @param int $shopId
     * @return object
     * @see ReserveRepositoryInterface::getReserveByDayAndShopId
     */
    public function getReserveByDayAndShopId(Carbon $date, int $shopId): object
    {
        return VisitReserve::getByDayAndShopId($date, $shopId)->get();
    }
}
