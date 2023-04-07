<?php

namespace App\Models;

use App\Consts\SessionConst;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Shop extends Model
{

    use SoftDeletes; // 論理削除を有効化

    /**
     * @return hasOne
     */
    public function userShopAuthorization(): hasOne
    {
        $userId = Auth::user()->id;
        return $this->hasOne(UserShopAuthorization::class, 'shop_id', 'id')
            ->where('user_id', $userId);
    }

    /**
     * @param int $loginUserId
     * @return object
     */
    public static function getMyShop(int $loginUserId)
    {
        return self::select('shops.*')
            ->join('user_shop_authorizations', 'shops.id', '=', 'user_shop_authorizations.shop_id')
            ->where('user_shop_authorizations.user_id', $loginUserId)
            ->whereNull('user_shop_authorizations.deleted_at')
            ->get();

    }

}
