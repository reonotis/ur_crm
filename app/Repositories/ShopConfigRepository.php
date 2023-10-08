<?php

namespace App\Repositories;

use App\Models\ShopConfig;
use App\Consts\ShopSettingConst;

/**
 * 店舗設定に関するレポジトリクラスです
 */
class ShopConfigRepository implements ShopConfigRepositoryInterface
{
    /**
     * 各keyのデフォルト値を登録する
     */
    public const DEFAULT = [
        'close_day' => null,
        'start_week' => ShopSettingConst::START_WEEK_MONDAY,
    ];

    /**
     * @param int $shopId
     * @param string $key
     * @return ShopConfig
     */
    public function getByKey(int $shopId, string $key): ShopConfig
    {
        $shopConfig = ShopConfig::where('shop_id', $shopId)->where('key', $key)->first();

        // データがあれば返却
        if ($shopConfig) {
            return $shopConfig;
        }

        // データが無いので初期データを作成して返却
        return $this->insertDefaultData($shopId, $key);
    }

    /**
     */
    public function updateByID($shopConfigId, $shopCloseDay)
    {
        $shopConfig = ShopConfig::find($shopConfigId);
        $shopConfig->value = $shopCloseDay;
        $shopConfig->save();
    }

    /**
     * 対象キーの初期データを登録する
     * @param int $shopId
     * @param string $key
     * @return ShopConfig
     */
    private function insertDefaultData(int $shopId, string $key): ShopConfig
    {
        $shopConfig = new ShopConfig();
        $shopConfig->shop_id = $shopId;
        $shopConfig->key = $key;
        $shopConfig->value = self::DEFAULT[$key];
        $shopConfig->save();

        return $shopConfig;
    }

}
