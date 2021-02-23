<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CheckUsers
{

    /**
    * インストラクターを取得します。
    */
    public static function getUser($id = NULL){
        $query = DB::table('users');

        // idが渡されれば渡されていれば
        if( $id ){ // そのUserを取得
            $query -> where('id','=', $id);
            $user = $query -> first();
            $user = self::checkEnrolled($user);
            $user = self::checkAuthority($user);
            return $user ;
        }

        // idが渡されていなければ
        if(empty($id)){ // 全てのUsersを取得
            $users = $query -> get();
            $users = self::checkEnrollese($users);
            $users = self::checkAuthoritys($users);
            return $users ;
        }
    }



    /**
    * 配列で渡されたインストラクターの在籍状況確認する
    */
    public static function checkEnrollese($users){
        foreach($users as $user){
            $user = self::checkEnrolled($user);
        }
        return $users;
    }

    /**
    * インストラクターの在籍状況を日本語にして返す
    */
    public static function checkEnrolled($user){
        if($user->enrolled_id === 1){
            $user->enrolled = "在籍" ;
        }
        if($user->enrolled_id === 2){
            $user->enrolled = "" ;
        }
        if($user->enrolled_id === 3){
            $user->enrolled = "" ;
        }
        if($user->enrolled_id === 4){
            $user->enrolled = "" ;
        }
        if($user->enrolled_id === 5){
            $user->enrolled = "長期休暇" ;
        }
        if($user->enrolled_id === 6){
            $user->enrolled = "---" ;
        }
        if($user->enrolled_id === 7){
            $user->enrolled = "---" ;
        }
        if($user->enrolled_id === 8){
            $user->enrolled = "---" ;
        }
        if($user->enrolled_id === 9){
            $user->enrolled = "退職" ;
        }
        return $user;
    }

    /**
    * 配列で渡されたインストラクターの権限情報を確認する
    */
    public static function checkAuthoritys($users){
        foreach($users as $user){
            $user = self::checkAuthority($user);
        }
        return $users;
    }

    /**
    * インストラクターの権限情報を日本語にして返す
    */
    public static function checkAuthority($user){
        if($user->authority_id === 1){
            $user->authority = "オーナー" ;
        }
        if($user->authority_id === 2){
            $user->authority = "社長" ;
        }
        if($user->authority_id === 3){
            $user->authority = "部長" ;
        }
        if($user->authority_id === 4){
            $user->authority = "課長" ;
        }
        if($user->authority_id === 5){
            $user->authority = "社員" ;
        }
        if($user->authority_id === 6){
            $user->authority = "経理" ;
        }
        if($user->authority_id === 7){
            $user->authority = "---" ;
        }
        if($user->authority_id === 8){
            $user->authority = "---" ;
        }
        if($user->authority_id === 9){
            $user->authority = "なし" ;
        }
        return $user;
    }

}