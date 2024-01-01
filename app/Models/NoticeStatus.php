<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer id
 * @property integer notice_id
 * @property integer user_id
 * @property integer notice_status
 * @property Carbon read_at
 * @property integer del_user_id
 * @property Carbon del_at
 * @property integer hidden_flag
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 */
class NoticeStatus extends Model
{
    use SoftDeletes; // 論理削除を有効化

    protected $table = 'notice_statuses';

    protected $fillable = [
        'id',
        'notice_id',
        'user_id',
        'notice_status',
        'read_at',
    ];

    protected $dates = [
        'read_at',
        'del_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     */
    public function notice()
    {
        return $this->hasOne(Notice::class, 'id', 'notice_id');
    }


    /**
     * 既読ステータス
     * 0: 未読
     * 1: 既読
     * 9: 削除
     */
    public const NOTICE_STATUS = [
        'UNREAD' => 0,
        'ALREADY_READ' => 1,
        'DELETE' => 9,
    ];
}
