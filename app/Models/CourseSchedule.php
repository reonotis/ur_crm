<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSchedule extends Model
{
    //
    protected $dates = [
        'date',
        'open_start_day',
        'open_finish_day'
    ];
}
