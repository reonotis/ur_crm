<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitType extends Model
{
    //
    public static function get_visitList(){
        $result = self::get();
        return $result ;
    }
}
