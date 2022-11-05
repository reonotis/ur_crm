<?php

namespace App\Models;

use App\Consts\DatabaseConst;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    public function shop()
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
        try {
            return self::select('shops.*')
                ->join('shops', 'shops.id', '=', 'user_shop_authorizations.shop_id')
                ->where('user_shop_authorizations.user_id', $userId)
                ->get();
        } catch (Exception $e) {
            dd($e->getMessage(), 'getShopByUserId');
            return false;
        }
    }

}