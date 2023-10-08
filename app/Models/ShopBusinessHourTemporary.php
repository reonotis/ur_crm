<?php

namespace App\Models;

use Carbon\Carbon;
use Facade\FlareClient\Time\Time;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer id
 * @property integer shop_id
 * @property integer holiday
 * @property integer target_date
 * @property Time business_open_time
 * @property Time business_close_time
 * @property Time last_reception_time
 * @property integer created_by
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class ShopBusinessHourTemporary extends Model
{
    use SoftDeletes; // 論理削除を有効化

    protected $table = 'shop_business_hour_temporary';

    protected $fillable = [
        'shop_id',
        'holiday',
        'target_date',
        'business_open_time',
        'business_close_time',
        'last_reception_time',
        'created_by',
    ];

    protected $dates = [
        'created_at',
    ];

}
