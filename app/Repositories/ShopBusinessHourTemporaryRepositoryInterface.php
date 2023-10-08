<?php

namespace App\Repositories;

use App\Models\ShopBusinessHourTemporary;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface ShopBusinessHourTemporaryRepositoryInterface
{
    /**
     * @param int $shopId
     * @return Collection
     */
    public function getTemporaryBusinessHour(int $shopId): Collection;

    /**
     * @param int $shopId
     * @param Carbon $targetDate
     * @return ShopBusinessHourTemporary|null
     */
    public function getShopBusinessHourTemporaryByTargetDate(int $shopId, Carbon $targetDate): ?ShopBusinessHourTemporary;

    /**
     * @param array $param
     * @return void
     */
    public function insert(array $param): void;

}
