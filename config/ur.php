<?php

return [

    // 認定料金
    // 'certificationFee' => 12100,

    // // 年会費
    // 'annualFee' => [
    //     'oneMonth' => 2200,            // 一か月単位
    //     'secondTimeOnwards' => 26400,  // 二回目以降の年会費
    // ],

    // 請求内訳に使用する項目のリスト
    'authorityList' =>[
        [
            'authorityId' => 1,
            'authorityName' => 'システム作成者',
            'display_flg' => false,
        ],[
            'authorityId' => 2,
            'authorityName' => '管理者',
            'display_flg' => true,
        ],[
            'authorityId' => 3,
            'authorityName' => 'マネージャー',
            'display_flg' => false,
        ],[
            'authorityId' => 4,
            'authorityName' => '店長',
            'display_flg' => true,
        ],[
            'authorityId' => 5,
            'authorityName' => 'トップスタイリスト',
            'display_flg' => false,
        ],[
            'authorityId' => 6,
            'authorityName' => 'ミドルスタイリスト',
            'display_flg' => false,
        ],[
            'authorityId' => 7,
            'authorityName' => 'スタイリスト',
            'display_flg' => true,
        ],[
            'authorityId' => 8,
            'authorityName' => 'レセプション',
            'display_flg' => true,
        ],[
            'authorityId' => 9,
            'authorityName' => 'なし',
            'display_flg' => true,
        ]
    ],

];

