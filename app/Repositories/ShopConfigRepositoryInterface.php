<?php

namespace App\Repositories;

use App\Models\{ShopConfig};

interface ShopConfigRepositoryInterface
{

    /**
     * @param int $shopId
     * @param string $key
     * @return ShopConfig
     */
    public function getByKey(int $shopId, string $key): ShopConfig;

}
