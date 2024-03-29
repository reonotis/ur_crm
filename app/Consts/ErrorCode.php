<?php

namespace App\Consts;

/**
 * エラーコード
 * 10000番台 : admin でのエラー
 * 20000番台 : shop でのエラー
 * 30000番台 : user でのエラー
 *  1000番台 : Model でのエラー
 *  2000番台 : Controller でのエラー
 */
class ErrorCode
{
    // 共通系
    public const DEFAULT = 'CL-9900001';
    public const IRREGULAR_PROCESS = 'CL-9900002';
    public const INVALID_DATE = 'CL-9900003';

    // ユーザー系
    public const CL_010001 = 'CL-010001';
    public const CL_010002 = 'CL-010002';
    public const CL_010003 = 'CL-010003';
    public const CL_010004 = 'CL-010004';
    public const CL_010005 = 'CL-010005';
    public const CL_010006 = 'CL-010006';

    // 店舗選択系
    public const CL_020001 = 'CL-020001';

    // 顧客系
    public const CL_030002 = 'CL-030002';
    public const CL_030003 = 'CL-030003';
    public const CL_030004 = 'CL-030004';
    public const CL_030005 = 'CL-030005';
    public const CL_030011 = 'CL-030011';
    public const CL_030012 = 'CL-030012';
    public const CL_030013 = 'CL-030013';
    public const CL_030014 = 'CL-030014';
    public const CL_030015 = 'CL-030015';

    // 来店履歴関係
    public const CL_040004 = 'CL-040004';

    public const ERROR_MSG_DEFAULT = 'エラーが発生しました。管理者にお問い合わせください';

    public const ERROR_MESSAGE_LIST = [
        self::DEFAULT => self::ERROR_MSG_DEFAULT,
        self::IRREGULAR_PROCESS => "不正な操作が行われました\n一度ログアウトした後、ログイン可能なユーザーでログインしてください",
        self::CL_010001 => "退職している為操作出来ません\n一度ログアウトした後、ログイン可能なユーザーでログインしてください",
        self::CL_010002 => '対象のスタッフは紐づいていない為閲覧できません',
        self::CL_010003 => '対象のスタッフは紐づいていない為編集できません',
        self::CL_010004 => '対象のスタッフは紐づいていない為更新できません',
        self::CL_010005 => '対象のスタッフは紐づいていない為削除できません',
        self::CL_010006 => '対象のスタッフは紐づいていない為、紐づけ解除できません',
        self::CL_030002 => "対象顧客のショップに対して、閲覧権限を持っていない閲覧できません\n管理者にお問い合わせください",
        self::CL_030003 => "対象顧客のショップに対して、閲覧権限を持っていない為編集できません\n管理者にお問い合わせください",
        self::CL_030004 => "対象顧客のショップに対して、編集権限を持っていない為更新できません\n管理者にお問い合わせください",
        self::CL_030005 => "対象顧客のショップに対して、編集権限を持っていない為削除できません\n管理者にお問い合わせください",
        self::CL_030011 => "対象顧客は別の店舗に所属している為、スタイリストを設定できません\n管理者にお問い合わせください",
        self::CL_030012 => "対象顧客は既にスタイリストが設定されている為、新たにスタイリストを設定できません\n管理者にお問い合わせください",
        self::CL_030013 => "対象顧客は別の店舗に所属している為、スタイリストを設定できません\n管理者にお問い合わせください",
        self::CL_030014 => "対象顧客は既にスタイリストが設定されている為、新たにスタイリストを設定できません\n管理者にお問い合わせください",
        self::CL_030015 => '対象顧客は既に本日の来店履歴が登録されている為、新たに来店履歴を登録することは出来ません',
        self::CL_040004 => '対象の来店履歴は本日の履歴ではないため編集できません',
        self::INVALID_DATE => '不正な日付が選択されました',
    ];

    public const ERROR_LOG_LIST = [
        self::DEFAULT => 'default error',
        self::CL_010002 => '店舗(shop_id:%d)に紐づいていないユーザー(user_id:%d)を、ログインユーザー(login_user_id:%d)が閲覧しようとしました',
        self::CL_010003 => '店舗(shop_id:%d)に紐づいていないユーザー(user_id:%d)を、ログインユーザー(login_user_id:%d)が編集しようとしました',
        self::CL_010004 => '店舗(shop_id:%d)に紐づいていないユーザー(user_id:%d)を、ログインユーザー(login_user_id:%d)が更新しようとしました',
        self::CL_010005 => '店舗(shop_id:%d)に紐づいていないユーザー(user_id:%d)を、ログインユーザー(login_user_id:%d)が削除しようとしました',
        self::CL_010006 => '店舗(shop_id:%d)に紐づいていないユーザー(user_id:%d)を、ログインユーザー(login_user_id:%d)が紐づけ解除しようとしました',
        self::CL_030002 => '店舗(shop_id:%d)の顧客(customer_id:%d)をログインユーザー(login_user_id:%d)が閲覧しようとしました',
        self::CL_030003 => '店舗(shop_id:%d)の顧客(customer_id:%d)をログインユーザー(login_user_id:%d)が編集しようとしました',
        self::CL_030004 => '店舗(shop_id:%d)の顧客(customer_id:%d)をログインユーザー(login_user_id:%d)が更新しようとしました',
        self::CL_030005 => '店舗(shop_id:%d)の顧客(customer_id:%d)をログインユーザー(login_user_id:%d)が削除しようとしました',
        self::CL_030011 => '別店舗(shop_id:%d)の顧客(customer_id:%d)に対して、ログインユーザー(login_user_id:%d)がスタイリスト選択画面を閲覧しようとしました',
        self::CL_030012 => '店舗(shop_id:%d)の顧客(customer_id:%d)に対して、既にスタイリストが設定されていますが、ログインユーザー(login_user_id:%d)が再度スタイリストを選択しようとしました',
        self::CL_030013 => '別店舗(shop_id:%d)の顧客(customer_id:%d)に対して、ログインユーザー(login_user_id:%d)がスタイリストを更新しようとしました',
        self::CL_030014 => '店舗(shop_id:%d)の顧客(customer_id:%d)に対して、既にスタイリストが設定されていますが、ログインユーザー(login_user_id:%d)が再度スタイリストを更新しようとしました',
        self::CL_030015 => '店舗(shop_id:%d)の顧客(customer_id:%d)に対してスタイリストを紐づける際に、既に本日の来店履歴が登録されているにも関わらず、新しく本日の来店履歴を登録しようとしました。',
        self::CL_040004 => '来店履歴(visit_history_id:%d)に対して、ログインユーザー(login_user_id:%d)が編集をしようとしました',
        self::INVALID_DATE => '不正な日付(%d)が選択されました。 ',
    ];

    // ルーティングに対して不正なアクセスをしたときのエラーメッセージ
    public const ROUTE_AUTH_ERROR_MSG = [
        'default' => "不正な画面遷移を検知しました。\n操作を最初からやり直してください",
        'user_read' => 'スタイリスト閲覧権限がありません',
        'user_create' => 'スタイリスト作成権限がありません',
        'user_edit' => 'スタイリスト編集権限がありません',
        'user_delete' => 'スタイリスト削除権限がありません',
        'customer_read' => "顧客閲覧権限がありません\n他店舗の顧客情報を閲覧する場合は操作店舗を切り替えてください",
        'customer_create' => "顧客登録の権限がありません\n他店舗の顧客を登録する場合は操作店舗を切り替えてください",
        'customer_edit' => "顧客編集の権限がありません\n他店舗の顧客を編集する場合は操作店舗を切り替えてください",
        'customer_delete' => "顧客削除の権限がありません\n他店舗の顧客を削除する場合は操作店舗を切り替えてください",
    ];


}
