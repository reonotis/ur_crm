<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * 選択可能なユーザーリストを取得する
     * @return void
     */
    public static function get_userList($shop_id = NULL){
        $result = self::where('authority_id','<=', 7)->where('authority_id','>=', 3);
        if($shop_id){
            $result = $result->where('shop_id', $shop_id);
        }
        $result = $result->get();
        return $result ;
    }
}
