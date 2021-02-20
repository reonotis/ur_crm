<?php

namespace App\Services;


class CheckUsers
{

    /**
    * インストラクターの在籍状況を日本語にして返す
    */
    public static function checkEnrolled($users){
        foreach($users as $user){
            if($user->enrolled === 1){
                $user->enrolled = "在籍" ;
            }
            if($user->enrolled === 2){
                $user->enrolled = "" ;
            }
            if($user->enrolled === 3){
                $user->enrolled = "" ;
            }
            if($user->enrolled === 4){
                $user->enrolled = "" ;
            }
            if($user->enrolled === 5){
                $user->enrolled = "長期休暇" ;
            }
            if($user->enrolled === 6){
                $user->enrolled = "---" ;
            }
            if($user->enrolled === 7){
                $user->enrolled = "---" ;
            }
            if($user->enrolled === 8){
                $user->enrolled = "---" ;
            }
            if($user->enrolled === 9){
                $user->enrolled = "退職" ;
            }
        }
        
        return $users;
    }

    /**
    * 顧客の表示ステータスを判断して返す
    */
    public static function hiddenStatus($customer){
        if($customer->hidden_flag === 0){
            $customer->hiddenStatus =  "表示" ;
        }
        if($customer->hidden_flag === 1){
            $customer->hiddenStatus = "非表示" ;
        }
        return $customer;
    }
    
}