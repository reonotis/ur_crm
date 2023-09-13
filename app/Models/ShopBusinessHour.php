<?php

namespace App\Models;

use Carbon\Carbon;
use Facade\FlareClient\Time\Time;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer id
 * @property integer shop_id
 * @property integer business_hour_type
 * @property integer week_no
 * @property Time business_open_time
 * @property Time business_close_time
 * @property Time last_reception_time
 * @property Carbon setting_start_date
 * @property Carbon setting_end_date
 * @property integer created_by
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class ShopBusinessHour extends Model
{
    use SoftDeletes; // 論理削除を有効化

    protected $table = 'shop_business_hour';

    protected $fillable = [
        'shop_id',
        'business_hour_type',
        'week_no',
        'business_open_time',
        'business_close_time',
        'last_reception_time',
        'setting_start_date',
        'setting_end_date',
        'created_by',
    ];

    protected $dates = [
        'setting_start_date',
        'setting_end_date',
        'created_at',
    ];

}
