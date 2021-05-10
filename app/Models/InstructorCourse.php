<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorCourse extends Model
{
    //
    protected $dates = [
        'date',
        'open_start_day',
        'open_finish_day'
    ];
}
