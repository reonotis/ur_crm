<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $shop_id
 * @property int $staff_id
 * @property string $function_name
 */
class FunctionAuth extends Model
{
    use SoftDeletes; // 論理削除を有効化

    protected $table = 'function_auth';
}
