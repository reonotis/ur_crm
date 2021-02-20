<?php

namespace App\Services;



use Illuminate\Support\Facades\DB;


class CheckCustomerData
{
    /**
    * 顧客の基本情報を取得する
    */
    public static function getCustomer($id){
        if(empty($id)){
            return false;
        }
        // 渡されたIDの顧客情報を取得する
        $query = DB::table('customers');
        $query -> leftJoin('users', 'users.id', '=', 'customers.instructor');
        $query -> select('customers.*', 'users.name as intrName');
        $query -> where('customers.id','=',$id);
        $customer = $query -> first();
        $customer = self::checkSex($customer);
        $customer = self::hiddenStatus($customer);
        return $customer ;
    }



    /**
    * 顧客の性別を判断して返す
    * @input $customer
    */
    public static function checkSex($customer){
        if($customer->sex === 1){
            $customer->sexName =  "男性" ;
        }
        if($customer->sex === 2){
            $customer->sexName = "女性" ;
        }
        if($customer->sex === 3){
            $customer->sexName = "その他" ;
        }
        return $customer;
    }

    /**
    * 顧客の表示ステータスを判断して返す
    */
    public static function hiddenStatus($customer){
        if($customer->hidden_flag === 0){
            $customer->hiddenStatus =  "表示中" ;
        }
        if($customer->hidden_flag === 1){
            $customer->hiddenStatus = "非表示" ;
        }
        return $customer;
    }

    /**
    * 顧客のスケジュールの受講状態を判断して返す
    */
    public static function attendanceStatus($CustomerSchedules){
        foreach ($CustomerSchedules as $CustomerSchedule){
            if($CustomerSchedule->status === 0){
                $CustomerSchedule->status =  "未受講" ;
            }
            if($CustomerSchedule->status === 1){
                $CustomerSchedule->status = "受講済み" ;
            }
        }
        return $CustomerSchedules;
    }




}