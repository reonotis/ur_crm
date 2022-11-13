<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerNoCounter extends Model
{
    //
    /**
     * 複数代入可能な属性
     * @var array
     */
    protected $fillable = [
        'id',
    ];
}
