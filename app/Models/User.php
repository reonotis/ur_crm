<?php

namespace App\Models;

use App\Consts\DatabaseConst;
use App\Consts\SessionConst;
use App\Consts\Common;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes; // 論理削除を有効化

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'authority_level',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * 日付に変更する必要がある属性。
     * The attributes that should be mutated to date.
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return hasOne
     */
    public function userShopAuthorization(): hasOne
    {
        $shopId = NULL;
        if (!empty(session()->get(SessionConst::SELECTED_SHOP))){
            $shopId = session()->get(SessionConst::SELECTED_SHOP)->id;
        }

        return $this->hasOne(UserShopAuthorization::class)
            ->where('shop_id', $shopId);
    }

    /**
     * @return HasMany
     */
    public function userShopAuthorizations()
    {
        return $this->hasMany(userShopAuthorization::class);
    }

    /**
     * @param array $condition
     * @return mixed
     */
    public static function getUsersByShopId(array $condition)
    {
        $query = self::select('users.*');

        if (!empty($condition['shopId'])){
            $query = $query->join('user_shop_authorizations', function ($join) {
                $join->on('users.id', '=', 'user_shop_authorizations.user_id')
                    ->whereNull('user_shop_authorizations.deleted_at');
            });
            $query = $query->where('user_shop_authorizations.shop_id', $condition['shopId']);
        }

        if (!empty($condition['name'])){
            $query = self::setWhereLike($query, 'name', $condition['name']);
        }

        if (!empty($condition['email'])){
            $query = self::setWhereLike($query, 'email', $condition['email']);
        }

        if (empty($condition['authority_level'])){
            $query = $query->where('authority_level', Common::AUTHORITY_ENROLLED);
        }

        return $query;
    }

    /**
     * @param object $query
     * @param string $columName
     * @param string $values
     * @return object
     */
    public static function setWhereLike(object $query, string $columName, string $values): object
    {
        // 全角スペースを半角スペースに変換
        $HANKAKUValues = mb_convert_kana($values, 's');

        // 半角スペース区切りで配列にする
        $valueArray = explode (' ' , $HANKAKUValues);

        foreach($valueArray AS $value){
            $query = $query->where($columName, 'LIKE', '%' . $value . '%');
        }
        return $query;
    }

}
