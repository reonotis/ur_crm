<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer id
 * @property integer shop_id
 * @property string key
 * @property string value
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class ShopConfig extends Model
{
    use SoftDeletes; // 論理削除を有効化

    protected $table = 'shop_config';

    protected $fillable = [
        'shop_id',
    ];

    protected $dates = [
        'created_at',
    ];

}
