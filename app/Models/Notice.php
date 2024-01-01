<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $comment
 * @property integer $hidden_flag
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
class Notice extends Model
{
    use SoftDeletes; // 論理削除を有効化

    protected $table = 'notices';

    protected $fillable = [
        'title',
        'comment',
    ];
}
