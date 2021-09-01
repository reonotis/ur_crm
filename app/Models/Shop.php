<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    /**
     * 非表示や削除されていないショップリストを取得する
     * IDが渡されたときはそのショップに限定する
     * @return void
     */
    public static function get_shopList($id = NULL){
        $result = self::where('delete_flag', 0)->where('hidden_flag','0');
        if($id){
            $result = $result->where('id', $id);
        }
        $result = $result->get();
        return $result ;
    }
}
