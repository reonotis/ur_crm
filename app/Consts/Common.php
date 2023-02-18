<?php

namespace App\Consts;

class Common
{
    // 画像ディレクトリ
    public const CUSTOMER_IMG_DIR = 'storage/images/customer';
    public const CUSTOMER_IMG_RESIZE_DIR = 'storage/images/customer/resize';

    // 顧客関係
    public const CUSTOMER_NO_LENGTH = 6;

    // 性別
    public const SEX_MEN = 1;
    public const SEX_WOMAN = 2;
    public const SEX_OTHER = 3;
    public const SEX_LIST = [
        self::SEX_MEN => '男性',
        self::SEX_WOMAN => '女性',
        self::SEX_OTHER => 'etc',
    ];
    public const SEX_SYMBOL = [
        self::SEX_MEN => '♂',
        self::SEX_WOMAN => '♀',
        self::SEX_OTHER => '-',
    ];

    // 曜日
    public const WEEK_LIST = [
        0 => '日',
        1 => '月',
        2 => '火',
        3 => '水',
        4 => '木',
        5 => '金',
        6 => '土',
    ];

    // 在籍権限
    public const AUTHORITY_PLANING_JOIN = 1;
    public const AUTHORITY_ENROLLED = 2;
    public const AUTHORITY_REST = 5;
    public const AUTHORITY_RETIREMENT = 9;
    public const AUTHORITY_LIST = [
        self::AUTHORITY_PLANING_JOIN => '入社予定',
        self::AUTHORITY_ENROLLED => '在籍',
        self::AUTHORITY_REST => '長期休暇',
        self::AUTHORITY_RETIREMENT => '退職',
    ];

    // 作業権限
    public const AUTHORIZATION_NOT_AVAILABLE = 0;
    public const AUTHORIZATION_AVAILABLE = 1;
    public const AUTHORIZATION_LIST = [
        self::AUTHORIZATION_NOT_AVAILABLE => '権限なし',
        self::AUTHORIZATION_AVAILABLE => '権限あり',
    ];

    // 在籍権限
    public const DO_MASK = 0;
    public const NONE_MASK = 1;
    public const MASK_CHECK = [
        self::DO_MASK => 'する',
        self::NONE_MASK => 'しない',
    ];

    // 画像
    public const ANGLE_1 = 1;
    public const ANGLE_2 = 2;
    public const ANGLE_3 = 3;
    public const ANGLE_LIST = [
        self::ANGLE_1 => '正面',
        self::ANGLE_2 => '側面',
        self::ANGLE_3 => '背面',
    ];

    // アンケート
    public const Q1_MESSAGE = "お店を知ったきっかけを教えてください。";
    public const Q1_A1 = 1;
    public const Q1_A2 = 2;
    public const Q1_A3 = 3;
    public const Q1_A4 = 4;
    public const Q1_A5 = 5;
    public const Q1_A6 = 6;
    public const Q1_A7 = 7;
    public const Q1_A8 = 8;
    public const QUESTION_1_LIST = [
        self::Q1_A1 => 'ホームページ',
        self::Q1_A2 => 'ホットペッパー',
        self::Q1_A4 => '知人の紹介',
        self::Q1_A5 => 'TV雑誌',
        self::Q1_A6 => '以前URを利用したことがある',
        self::Q1_A7 => 'SNS',
        self::Q1_A8 => 'その他',
    ];

    // バリデーション関連
    public const VALIDATE_TEL = '/^0[0-9]{1,4}-[0-9]{1,4}-[0-9]{3,4}\z/';
    public const VALIDATE_EMAIL = '/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/';

    // ルートに対して必要な権限名
    public const ROUTE_AUTH_LIST = [
        'user.index' => 'user_read',
        'user.show' => 'user_read',
        'user.create' => 'user_create',
        'user.store' => 'user_create',
        'user.update' => 'user_edit',
        'user.edit' => 'user_edit',
        'user.belongSelect' => 'user_edit',
        'user.belongSelected' => 'user_edit',
        'user.deleteBelongShop' => 'user_edit',
        'user.destroy' => 'user_delete',
        'customer.index' => 'customer_read',
        'customer.show' => 'customer_read',
        'customer.create' => 'customer_create',
        'customer.store' => 'customer_create',
        'customer.edit' => 'customer_edit',
        'customer.update' => 'customer_edit',
    ];

}
