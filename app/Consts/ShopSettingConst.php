<?php

namespace App\Consts;

class ShopSettingConst
{
    // 営業時間の設定タイプ
    public const BUSINESS_HOUR_EVERYDAY = 1;
    public const BUSINESS_HOUR_WEEKDAY = 2;
    public const BUSINESS_HOUR_TYPE_LIST = [
        self::BUSINESS_HOUR_EVERYDAY => '毎日同時間として設定',
        self::BUSINESS_HOUR_WEEKDAY => '曜日毎に設定',
    ];

    // 営業時間の設定が適用されているか
    public const APPLY_BEFORE = 1;
    public const APPLYING = 2;
    public const APPLY_AFTER = 3;
    public const APPLY_LIST = [
        self::APPLY_BEFORE => '適用前',
        self::APPLYING => '適用中',
        self::APPLY_AFTER => '適用終了',
    ];

    // 曜日の設定 ※ISO-8601形式
    public const MONDAY = 1;
    public const TUESDAY = 2;
    public const WEDNESDAY = 3;
    public const THURSDAY = 4;
    public const FRIDAY = 5;
    public const SATURDAY = 6;
    public const SUNDAY = 7;
    public const HOLIDAY = 8;
    public const BEFORE_HOLIDAY = 9;

    // 曜日のラベル
    public const WEEK_LABEL_LIST = [
        self::MONDAY => '月曜日',
        self::TUESDAY => '火曜日',
        self::WEDNESDAY => '水曜日',
        self::THURSDAY => '木曜日',
        self::FRIDAY => '金曜日',
        self::SATURDAY => '土曜日',
        self::SUNDAY => '日曜日',
        self::HOLIDAY => '祝日',
        self::BEFORE_HOLIDAY => '祝前日',
    ];

    // 週の始まりが日曜か月曜か
    public const START_WEEK_SUNDAY = 1;
    public const START_WEEK_MONDAY = 2;

    // 週の始まりが日曜始まりの場合の曜日リスト
    public const START_WEEK_SUNDAY_LIST = [
        self::SUNDAY,
        self::MONDAY,
        self::TUESDAY,
        self::WEDNESDAY,
        self::THURSDAY,
        self::FRIDAY,
        self::SATURDAY,
    ];

    // 週の始まりが月曜始まりの場合の曜日リスト
    public const START_WEEK_MONDAY_LIST = [
        self::MONDAY,
        self::TUESDAY,
        self::WEDNESDAY,
        self::THURSDAY,
        self::FRIDAY,
        self::SATURDAY,
        self::SUNDAY,
    ];

}
