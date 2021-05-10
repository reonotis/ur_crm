<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WPMyScheduleIntrCourse extends Model
{
    
    protected $connection = 'mysql_2';
    protected $table = 'my_schedule_intr_courses';
    protected $primaryKey = 'id';
    protected $dates = ['date1','date2','date3','date4','date5','date6','date7','date8','date9','date10'];

}
