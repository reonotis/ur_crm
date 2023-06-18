<?php

namespace App\Consts;

class DataAnalyze
{
    // データ分析タイプ
    public const ANALYZE_TYPE_VISIT_HISTORY = 1;
    public const ANALYZE_TYPE_STYLIST = 2;
    public const ANALYZE_TYPE_MENU = 3;
    public const ANALYZE_TYPE_LIST = [
        self::ANALYZE_TYPE_VISIT_HISTORY => '来店履歴別',
        self::ANALYZE_TYPE_STYLIST => 'スタイリスト別',
        // self::ANALYZE_TYPE_MENU => 'メニュー別',
    ];

}
