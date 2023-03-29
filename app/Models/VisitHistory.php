<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @property int id
 * @property string vis_date
 * @property string vis_time
 * @property int user_id
 * @property int menu_id
 * @property string memo
 */
class VisitHistory extends Model
{
    use SoftDeletes; // 論理削除を有効化

    /**
     * 日付に変更する必要がある属性。
     * The attributes that should be mutated to date.
     * @var array
     */
    protected $dates = [
        'vis_date',
    ];

    /**
     * @return HasMany
     */
    public function VisitHistoryImages()
    {
        return $this->hasMany(VisitHistoryImage::class);
    }

    /**
     * @return hasOne
     */
    public function customer(): hasOne
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    /**
     * 本日の来店者情報を取得
     * @param int $shop_id
     * @return object
     */
    public static function getTodayVisitHistory(int $shop_id): object
    {
        $date = Carbon::now()->format('Y-m-d');
        $select = [
            'visit_histories.*',
            'customers.id AS customer_id',
            'customers.customer_no',
            'customers.f_name',
            'customers.l_name',
            'customers.sex',
            'users.name',
            'menus.menu_name',
            'visit_types.type_name',
        ];
        return self::select($select)
            ->where('visit_histories.shop_id', $shop_id)
            ->where('vis_date', $date)
            ->join('customers', function ($join) {
                $join->on('visit_histories.customer_id', '=', 'customers.id')
                    ->whereNull('customers.deleted_at');
            })
            ->leftJoin('users', 'users.id', '=', 'visit_histories.user_id')
            ->leftJoin('menus', 'menus.id', '=', 'visit_histories.menu_id')
            ->leftJoin('visit_types', 'visit_types.id', '=', 'visit_histories.visit_type_id');
    }

    /**
     * 対象顧客の本日の来店者情報を取得
     * @param int $shop_id
     * @return object
     */
    public static function getTodayVisitHistoryByCustomerId(int $customerId): object
    {
        $date = Carbon::now()->format('Y-m-d');
        return self::select('*')
            ->where('customer_id', $customerId)
            ->where('vis_date', $date);
    }

    /**
     * 本日の来店者をスタイリスト別にカウントする
     * @param int $shop_id
     * @return object
     */
    public static function getTodayOpeMemberByShopId(int $shop_id): object
    {
        $date = Carbon::now()->format('Y-m-d');
        return self::select(DB::raw('
                users.name,
                COUNT(user_id) AS count
            '))
            ->join('customers', function ($join) {
                $join->on('visit_histories.customer_id', '=', 'customers.id')
                    ->whereNull('customers.deleted_at');
            })
            ->leftJoin('users', 'users.id', 'visit_histories.user_id')
            ->where('visit_histories.shop_id', $shop_id)
            ->where('visit_histories.vis_date', $date)
            ->groupBy('visit_histories.user_id');
    }


    /**
     * @param [type] $shop_id
     * @return void
     */
    public static function get_monthAndMenu($targetMonth, $shop_id){
        $fromMonth = $targetMonth;
        $toMonth = date("Y-m-20", strtotime($targetMonth . "+1 month"));

        $result = self::select(DB::raw('visit_histories.*, count(*) as numberOfVisitors, menus.menu_name'))
        ->where('visit_histories.shop_id', $shop_id)
        ->where('visit_histories.vis_date', '>=', $fromMonth)
        ->where('visit_histories.vis_date', '<=', $toMonth)
        ->where('visit_histories.delete_flag', 0)
        ->leftJoin('menus', 'menus.id', '=', 'visit_histories.menu_id')
        ->groupBy('menu_id')
        ->get();
        return [$result, $fromMonth, $toMonth] ;
    }

    /**
     * 指定ショップと指定日から1ヵ月間の来店履歴をスタイリスト別に取得
     * @param [type] $shop_id
     * @return void
     */
    public static function get_monthAndStylist($targetMonth, $shop_id){
        // 指定期間を取得
        $fromMonth = $targetMonth;
        $toMonth = date("Y-m-20", strtotime($targetMonth . "+1 month"));

        // サブクエリを作成 店舗・スタッフ・来店タイプでグループ化する
        $VHTQuery = self::select(DB::raw(
            'visit_histories.staff_id,
            visit_histories.visit_type_id,
            visit_types.type_name,
            count(*) as numberOfVisitors'
        ))
        ->leftJoin('visit_types', 'visit_types.id', '=', 'visit_histories.visit_type_id'  )
        ->where('visit_histories.vis_date', '>=', $fromMonth)
        ->where('visit_histories.vis_date', '<=', $toMonth)
        ->where('visit_histories.delete_flag', 0)
        ->groupBy('shop_id')
        ->groupBy('staff_id')
        ->groupBy('visit_type_id');

        // サブクエリを結合してレコードを取得する
        $result = self::select(DB::raw('
            visit_histories.*,
            count(visit_histories.staff_id) as total_NINNZUU,
            VHT1.numberOfVisitors as VHT1_NINNZUU,
            VHT1.type_name as VHT1_type_name,
            VHT2.numberOfVisitors as VHT2_NINNZUU,
            VHT2.type_name as VHT2_type_name,
            VHT3.numberOfVisitors as VHT3_NINNZUU,
            VHT3.type_name as VHT3_type_name,
            VHT4.numberOfVisitors as VHT4_NINNZUU,
            VHT4.type_name as VHT4_type_name,
            VHT5.numberOfVisitors as VHT5_NINNZUU,
            VHT5.type_name as VHT5_type_name,
            users.name'
        ))
        ->where('visit_histories.shop_id', $shop_id)
        ->where('visit_histories.vis_date', '>=', $fromMonth)
        ->where('visit_histories.vis_date', '<=', $toMonth)
        ->where('visit_histories.delete_flag', 0)
        ->leftJoin('users', 'users.id', '=', 'visit_histories.staff_id')
        ->leftJoinSub($VHTQuery, 'VHT1', function ($join) {
            $join->on('VHT1.staff_id', '=', 'visit_histories.staff_id')
            ->where('VHT1.visit_type_id', '=', 1);
        })
        ->leftJoinSub($VHTQuery, 'VHT2', function ($join) {
            $join->on('VHT2.staff_id', '=', 'visit_histories.staff_id')
            ->where('VHT2.visit_type_id', '=', 2);
        })
        ->leftJoinSub($VHTQuery, 'VHT3', function ($join) {
            $join->on('VHT3.staff_id', '=', 'visit_histories.staff_id')
            ->where('VHT3.visit_type_id', '=', 3);
        })
        ->leftJoinSub($VHTQuery, 'VHT4', function ($join) {
            $join->on('VHT4.staff_id', '=', 'visit_histories.staff_id')
            ->where('VHT4.visit_type_id', '=', 4);
        })
        ->leftJoinSub($VHTQuery, 'VHT5', function ($join) {
            $join->on('VHT5.staff_id', '=', 'visit_histories.staff_id')
            ->where('VHT5.visit_type_id', '=', 5);
        })
        ->groupBy('visit_histories.staff_id')
        ->get();
        return [$result, $fromMonth, $toMonth] ;

    }

    /**
     * 対象顧客の本日の来店履歴を取得
     * @param int $customer_id
     * @return void
     */
    public static function checkTodayHistory($customer_id){
        $result = self::select()
        ->where('customer_id', $customer_id)
        ->where('vis_date', date('Y-m-d'))
        ->get();
        return $result;
    }

    /**
     * 対象顧客の本日の来店履歴を取得
     * @param int $customerId
     * @return object
     */
    public static function getByCustomerId(int $customerId): object
    {
        $select = [
            'visit_histories.*',
            'users.name',
            'menus.menu_name',
            'shops.shop_name',
        ];
        $result = self::select($select)
        ->where('customer_id', $customerId)
        ->leftJoin('users', 'users.id', 'visit_histories.user_id')
        ->leftJoin('menus', 'menus.id', 'visit_histories.menu_id')
        ->leftJoin('shops', 'shops.id', 'visit_histories.shop_id')
        ->orderBy('visit_histories.vis_date', 'desc')
        ->orderBy('visit_histories.vis_time', 'desc')
        ;
        return $result;
    }

}
