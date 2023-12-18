<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property string vis_date
 * @property string vis_time
 * @property int customer_id
 * @property int shop_id
 * @property int user_id
 * @property int menu_id
 * @property string memo
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
        'customer_id',
        'shop_id',
        'user_id',
        'memo',
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
}
