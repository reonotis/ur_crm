<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WPMySchedule extends Model
{
    protected $connection = 'mysql_2';
    protected $table = 'my_schedule';
    protected $primaryKey = 'id';
    protected $dates = ['date'];

}
