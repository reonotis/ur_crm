<?php

namespace App\Consts;

class ShopSettingConst
{
    public const BUSINESS_HOUR_EVERYDAY = 1;
    public const BUSINESS_HOUR_WEEKDAY = 2;
    public const BUSINESS_HOUR_TYPE_LIST = [
        self::BUSINESS_HOUR_EVERYDAY => '毎日同時間として設定',
        self::BUSINESS_HOUR_WEEKDAY => '曜日毎に設定',
    ];

    public const APPLY_BEFORE = 1;
    public const APPLYING = 2;
    public const APPLY_AFTER = 3;
    public const APPLY_LIST = [
        self::APPLY_BEFORE => '適用前',
        self::APPLYING => '適用中',
        self::APPLY_AFTER => '適用終了',
    ];

}
