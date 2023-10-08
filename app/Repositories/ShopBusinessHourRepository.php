<?php

namespace App\Repositories;

use App\Models\ShopBusinessHour;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;

/**
 * 営業時間テーブルに対するレポジトリクラスです
 */
class ShopBusinessHourRepository implements ShopBusinessHourRepositoryInterface
{
    /**
     * @param int $shopId
     * @return Collection
     * @see ShopBusinessHourRepositoryInterface::getMyShopBusinessHours
     */
    public function getMyShopBusinessHours(int $shopId): Collection
    {
        $shopBusinessHours = ShopBusinessHour::where('shop_id', $shopId)->get();

        return $shopBusinessHours->sortByDesc('setting_start_date');
    }

    /**
     * @param int $shopId
     * @return Collection [ShopBusinessHour]
     * @see ShopBusinessHourRepositoryInterface::getApplyingOrLater
     */
    public function getApplyingOrLater(int $shopId): Collection
    {
        $today = Carbon::today();

        $shopBusinessHours = ShopBusinessHour::where('shop_id', $shopId)
            ->where(function ($query) use ($today) {
                $query->whereNull('setting_end_date')
                    ->orWhere('setting_end_date', '>=', $today);
            })
            ->get();

        return $shopBusinessHours->sortByDesc('setting_start_date')->values();
    }

    /**
     * @param array $param
     * @return void
     * @see ShopBusinessHourRepositoryInterface::insert
     */
    public function insert(array $param): void
    {
        ShopBusinessHour::create($param);
    }

    /**
     * @param int $id
     * @param array $param
     * @return void
     * @see ShopBusinessHourRepositoryInterface::updateById
     */
    public function updateById(int $id, array $param): void
    {
        $record = ShopBusinessHour::find($id);

        if (array_key_exists('setting_end_date', $param)) $record->setting_end_date = $param['setting_end_date'];
        if (array_key_exists('business_open_time', $param)) $record->business_open_time = $param['business_open_time'];
        if (array_key_exists('business_close_time', $param)) $record->business_close_time = $param['business_close_time'];
        if (array_key_exists('last_reception_time', $param)) $record->last_reception_time = $param['last_reception_time'];
        if (array_key_exists('setting_start_date', $param)) $record->setting_start_date = $param['setting_start_date'];

        $record->save();
    }

    /**
     * 論理削除を行う
     * @param ShopBusinessHour $shopBusinessHour
     * @return void
     * @throws Exception
     */
    public function delete(ShopBusinessHour $shopBusinessHour): void
    {
        $shopBusinessHour->delete();
    }
}
