<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerCourseMapping extends Model
{
    //
    protected $table = 'customer_course_mapping';

    protected $dates = [
        'limit_day',
    ];


}
