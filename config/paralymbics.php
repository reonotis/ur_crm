<?php

return [

    // 認定料金
    'certificationFee' => 12100,

    // 年会費
    'annualFee' => [
        'oneMonth' => 2200,            // 一か月単位
        'secondTimeOnwards' => 26400,  // 二回目以降の年会費
    ],

    // 請求内訳に使用する項目のリスト
    'claimDetailList' =>[
        [
            'item_name' => '認定料金',
            'unit_price' => '12100',
            'unit' => '回',
        ],[
            'item_name' => '年会費',
            'unit_price' => '2200',
            'unit' => 'ヵ月分',
        ],[
            'item_name' => '物販',
            'unit_price' => '2200',
            'unit' => '個',
        ],[
            'item_name' => 'その他',
            'unit_price' => 0,
            'unit' => '',
        ],
    ],

];