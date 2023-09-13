<?php

namespace App\Repositories;

use App\Models\{ShopConfig};

class ShopConfigRepository implements ShopConfigRepositoryInterface
{
    /**
     * 各keyのデフォルト値を登録する
     */
    public const DEFAULT = [
        'business_hour_type' => 1,
        'close_day' => null,
    ];

    /**
     * @param int $shopId
     * @return ShopConfig
     */
    public function getByKey(int $shopId, string $key): ShopConfig
    {
        $shopConfig = ShopConfig::where('shop_id', $shopId)->where('key', $key)->first();

        // レコードが無ければ作成しておく
        if (empty($shopConfig)) {
            // 作成
            ShopConfig::insert([
                'shop_id' => $shopId,
                'key' => $key,
                'value' => self::DEFAULT[$key],
            ]);
        }

        return ShopConfig::where('shop_id', $shopId)->where('key', $key)->first();
    }
}
