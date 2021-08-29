<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    /**
     * 削除されていないショップリストを取得する
     * @return void
     */
    public static function get_shopList(){
        $result = self::where('delete_flag', 0)->get();
        return $result ;
    }
}
