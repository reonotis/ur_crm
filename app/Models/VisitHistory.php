<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VisitHistory extends Model
{
    //
    protected $dates = [
        'vis_date',
    ];

    /**
     * 本日の集計データを取得する
     * @param [type] $shop_id
     * @return void
     */
    public static function get_todayReport($shop_id){
        $result = self::select(DB::raw('vis_date, staff_id, count(*) as numberOfVisitors, ifnull(users.name,"未設定") as name '))
        ->where('vis_date', date('Y-m-d'))
        ->where('visit_histories.shop_id', $shop_id)
        ->where('visit_histories.delete_flag', 0)
        ->leftJoin('users', 'visit_histories.staff_id', '=', 'users.id')
        ->groupBy('staff_id')
        ->orderByRaw('users.id IS NULL asc')
        ->orderByRaw('users.id asc')
        ->get()->toArray();


        $visTypeResult = self::select(DB::raw('ifnull(visit_types.type_name,"未設定") as type_name , visit_type_id, count(COALESCE(customer_id,"")) as numberOfVisitors'))
        ->where('vis_date', date('Y-m-d'))
        ->where('visit_histories.delete_flag', 0)
        ->groupBy('visit_type_id')
        ->leftJoin('visit_types', 'visit_types.id', '=', 'visit_histories.visit_type_id')
        ->orderByRaw('visit_types.id IS NULL asc')
        ->orderByRaw('visit_types.id asc')
        ->get()->toArray();

        return [$result, $visTypeResult] ;
    }

    /**
     * 本日の来店者情報を取得
     * @param [type] $shop_id
     * @return void
     */
    public static function get_todayVisitHistory($shop_id){
        $result = self::select('visit_histories.*', 'customers.f_name', 'customers.l_name' , 'users.name', 'menus.menu_name', 'visit_types.type_name' )
        ->where('visit_histories.shop_id', $shop_id)
        ->where('vis_date', date('Y-m-d'))
        ->where('visit_histories.delete_flag', 0)
        ->join('customers', 'customers.id', '=', 'visit_histories.customer_id' )
        ->leftJoin('users', 'users.id', '=', 'visit_histories.staff_id' )
        ->leftJoin('menus', 'menus.id', '=', 'visit_histories.menu_id' )
        ->leftJoin('visit_types', 'visit_types.id', '=', 'visit_histories.visit_type_id' )
        ->get();
        return $result ;
    }

    /**
     * 指定日と指定ショップの来店履歴を会計別に取得
     * @param [type] $shop_id
     * @return void
     */
    public static function get_dayAndPayment($date, $shop_id){
        $result = self::select(DB::raw(
            'visit_histories.*,
            ifnull(users.name,"未設定") as name,
            customers.f_name,
            customers.l_name,
            menus.menu_name,
            visit_types.type_name'
        ))
        ->where('visit_histories.shop_id', $shop_id)
        ->where('visit_histories.vis_date', $date)
        ->where('visit_histories.delete_flag', 0)
        ->leftJoin('users', 'users.id', '=', 'visit_histories.staff_id')
        ->leftJoin('customers', 'customers.id', '=', 'visit_histories.customer_id')
        ->leftJoin('menus', 'menus.id', '=', 'visit_histories.menu_id')
        ->leftJoin('visit_types', 'visit_types.id', '=', 'visit_histories.visit_type_id')
        ->get();
        return $result ;
    }

    /**
     * 指定日と指定ショップの来店履歴をスタイリスト別に取得
     * @param [type] $shop_id
     * @return void
     */
    public static function get_dayAndStylist($date, $shop_id){

        // サブクエリを作成 店舗・スタッフ・来店タイプでグループ化する
        $VHTQuery = self::select(DB::raw(
            'visit_histories.staff_id,
            visit_histories.visit_type_id,
            visit_types.type_name,
            count(*) as numberOfVisitors',
        ))
        ->leftJoin('visit_types', 'visit_types.id', '=', 'visit_histories.visit_type_id'  )
        ->where('visit_histories.vis_date', $date)
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
            ifnull(users.name,"未設定") as name'
        ))
        ->where('visit_histories.shop_id', $shop_id)
        ->where('visit_histories.vis_date', $date)
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
        // dd($result);
        return $result ;

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
            count(*) as numberOfVisitors',
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
        // dd($result);
        return [$result, $fromMonth, $toMonth] ;

    }





}