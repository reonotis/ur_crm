<?php

namespace App\Models;

use App\Consts\{Common, DatabaseConst};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property int shop_id
 * @property int user_read
 * @property int user_create
 * @property int user_edit
 * @property int user_delete
 * @property int customer_read
 * @property int customer_read_none_mask
 * @property int customer_create
 * @property int customer_edit
 * @property int customer_delete
 * @property int reserve_read
 * @property int reserve_create
 * @property int reserve_edit
 * @property int reserve_delete
 */
class UserShopAuthorization extends Model
{
    use SoftDeletes; // 論理削除を有効化

    /**
     * 複数代入可能な属性
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'user_id',
        'user_read',
        'user_create',
        'user_edit',
        'user_delete',
        'customer_read',
        'customer_read_mask_none',
        'customer_create',
        'customer_edit',
        'customer_delete',
        'reserve_read',
        'reserve_create',
        'reserve_edit',
        'reserve_delete',
    ];

    /**
     * @return hasOne
     */
    public function shop(): hasOne
    {
        return $this->hasOne(Shop::class, 'id', 'shop_id');
    }

    /**
     * スタッフに紐づくショップを取得する
     * @param int $userId
     * @return object
     */
    public static function getShopByUserId(int $userId): object
    {
        return self::select('shops.*')
            ->join('shops', 'shops.id', '=', 'user_shop_authorizations.shop_id')
            ->where('user_shop_authorizations.user_id', $userId);
    }

    /**
     * 選択可能なユーザーを取得する
     * @param int|null $shopId
     * @return object
     */
    public static function getSelectableUsers(int $shopId = NULL): object
    {
        $query = self::select('users.id', 'users.name', 'user_shop_authorizations.shop_id')
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'user_shop_authorizations.user_id')
                    ->where('users.display_flag', DatabaseConst::FLAG_ON)
                    ->whereNull('users.deleted_at');
            });
        if($shopId){
            $query->where('user_shop_authorizations.shop_id', $shopId);
        }

        $query->orderByRaw("
            authority_level = " . Common::AUTHORITY_ENROLLED . " DESC,
            authority_level ASC,
            id ASC
        ");

        return $query;
    }

}
