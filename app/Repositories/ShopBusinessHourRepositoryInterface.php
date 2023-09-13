<?php

namespace App\Repositories;

use App\Models\ShopConfig;
use Illuminate\Database\Eloquent\Collection;

interface ShopBusinessHourRepositoryInterface
{
    /**
     * @param int $shopId
     * @param int $business_hour_type
     * @return Collection
     */
    public function getMyShopBusinessHours(int $shopId, int $business_hour_type): Collection;

    /**
     * @param array $param
     * @return void
     */
    public function insert(array $param): void;

    /**
     * @param int $id
     * @param array $param
     * @return void
     */
    public function updateById(int $id, array $param): void;
}
