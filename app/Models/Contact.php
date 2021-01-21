<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Contact extends Model
{


    //
    protected $dates = [
        'history_datetime',
        'created_at',
        'updated_at'
    ];

}
