<?php

namespace App\Repositories;

use Carbon\Carbon;

interface ReserveRepositoryInterface
{
    /**
     * @param Carbon $date
     * @param int $shopId
     * @return object
     */
    public function getReserveByDayAndShopId(Carbon $date, int $shopId): object;
}
