<?php

namespace App\Repositories;

use App\Models\ShopBusinessHour;
use App\Models\ShopConfig;
use Illuminate\Database\Eloquent\Collection;

interface ShopBusinessHourRepositoryInterface
{
    /**
     * @param int $shopId
     * @return Collection
     */
    public function getMyShopBusinessHours(int $shopId): Collection;

    /**
     * @param int $shopId
     * @return Collection
     */
    public function getApplyingOrLater(int $shopId): Collection;

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

    /**
     * @param ShopBusinessHour $shopBusinessHour
     * @return void
     */
    public function delete(ShopBusinessHour $shopBusinessHour): void;

}
