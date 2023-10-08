<?php

namespace App\Repositories;

use App\Models\ShopBusinessHourTemporary;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;

/**
 * 営業時間テーブルに対するレポジトリクラスです
 */
class ShopBusinessHourTemporaryRepository implements ShopBusinessHourTemporaryRepositoryInterface
{
    /**
     * @param int $shopId
     * @return Collection
     * @see ShopBusinessHourTemporaryRepositoryInterface::getTemporaryBusinessHour
     */
    public function getTemporaryBusinessHour(int $shopId): Collection
    {
        $shopBusinessHours = ShopBusinessHourTemporary::where('shop_id', $shopId)->get();
        return $shopBusinessHours->sortBy('target_date');
    }

    /**
     * @param int $shopId
     * @return ShopBusinessHourTemporary|null
     * @see ShopBusinessHourTemporaryRepositoryInterface::getShopBusinessHourTemporaryByTargetDate
     */
    public function getShopBusinessHourTemporaryByTargetDate(int $shopId, Carbon $targetDate): ?ShopBusinessHourTemporary
    {
        return ShopBusinessHourTemporary::where('shop_id', $shopId)->where('target_date', $targetDate)->first();
    }

    /**
     * @param array $param
     * @return void
     * @see ShopBusinessHourTemporaryRepositoryInterface::insert
     */
    public function insert(array $param): void
    {
        ShopBusinessHourTemporary::create($param);
    }

    /**
     * @param ShopBusinessHourTemporary $shopBusinessHourTemporary
     * @return void
     * @throws Exception
     */
    public function delete(ShopBusinessHourTemporary $shopBusinessHourTemporary): void
    {
        $shopBusinessHourTemporary->delete();
    }
}
