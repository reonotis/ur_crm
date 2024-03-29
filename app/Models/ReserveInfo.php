<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property Carbon $vis_date
 * @property string $vis_time
 * @property string $vis_end_time
 * @property int $customer_id
 * @property int $shop_id
 * @property int $user_id
 * @property int $menu_id
 * @property int $visit_type_id // 不要なので削除したい
 * @property int $visit_reserve_id // 不要なので削除したい
 * @property int $status
 * @property int $reserve_type
 * @property string $memo
 */
class ReserveInfo extends Model
{
    /**
     * 論理削除を有効化
     */
    use SoftDeletes;

    protected $table = 'reserve_info';

    /**
     * 日付に変更する必要がある属性。
     * The attributes that should be mutated to date.
     * @var array
     */
    protected $dates = [
        'vis_date',
    ];

    /**
     * 複数代入可能な属性
     * @var array
     */
    protected $fillable = [
        'vis_date',
        'vis_time',
        'vis_end_time',
        'customer_id',
        'shop_id',
        'user_id',
        'status',
        'reserve_type',
        'memo',
    ];

    /**
     * 取得しないカラム
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * @return HasMany
     */
    public function VisitHistoryImages()
    {
        return $this->hasMany(VisitHistoryImage::class);
    }

    /**
     * @return hasOne
     */
    public function customer(): hasOne
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    /**
     * 案内状態
     * 0: 未案内
     * 1: キャンセル
     * 5: 案内済み
     */
    public const STATUS = [
        'UNGUIDE' => 0,
        'CANCEL' => 1,
        'GUIDED' => 5,
    ];

    /**
     * 予約タイプ
     * 0: 不明
     * 1: 来店時
     * 5: 予約
     */
    const RESERVE_TYPE = [
        'UNKNOWN' => 0,
        'CAME_SHOP' => 1,
        'RESERVATION' => 5,
    ];

    /**
     * 施術時間のデフォルト値
     */
    const DEFAULT_TREATMENT_TIME = 60;
}
