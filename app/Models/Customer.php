<?php

namespace App\Models;

use App\Consts\DatabaseConst;
use App\Consts\SessionConst;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 */
class Customer extends Model
{
    use SoftDeletes; // 論理削除を有効化

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_no',
        'f_name',
        'l_name',
        'f_read',
        'l_read',
        'sex',
        'tel',
        'email',
        'birthday_year',
        'birthday_month',
        'birthday_day',
        'zip21',
        'zip22',
        'pref21',
        'address21',
        'street21',
        'shop_id',
        'staff_id',
        'question1',
        'comment',
        'memo',
    ];

    /**
     * @return hasOne
     */
    public function shop(): hasOne
    {
        return $this->hasOne(Shop::class, 'id', 'shop_id');
    }

    /**
     * @return hasOne
     */
    public function user(): hasOne
    {
        return $this->hasOne(User::class, 'id', 'staff_id');
    }


    /**
     * @param array $condition
     * @return object
     */
    public static function getCustomers(array $condition): object
    {
        $query = self::select('customers.*', 'users.name', 'shops.shop_name');
        $query = self::setCondition($query, $condition);

        $query->leftJoin('users', function ($join) {
            $join->on('users.id', '=', 'customers.staff_id')
                ->whereNull('users.deleted_at');
        });
        $query->leftJoin('shops', function ($join) {
            $join->on('shops.id', '=', 'customers.shop_id')
                ->whereNull('shops.deleted_at');
        });
        return $query;
    }

    /**
     * @param object $query
     * @param array $condition
     * @return object
     */
    public static function setCondition(object $query, array $condition): object
    {
        if (!empty($condition['customer_no'])){
            $query = self::setWhereLike($query, 'customer_no', $condition['customer_no']);
        }
        if (!empty($condition['f_name'])){
            $query = self::setWhereLike($query, 'f_name', $condition['f_name']);
        }
        if (!empty($condition['l_name'])){
            $query = self::setWhereLike($query, 'l_name', $condition['l_name']);
        }
        if (!empty($condition['f_read'])){
            $query = self::setWhereLike($query, 'f_read', $condition['f_read']);
        }
        if (!empty($condition['l_read'])){
            $query = self::setWhereLike($query, 'l_read', $condition['l_read']);
        }
        if (!empty($condition['shop_id'])){
            $query = $query->where('shop_id', $condition['shop_id']);
        }
        if (!empty($condition['staff_id'])){
            $query = $query->where('staff_id', $condition['staff_id']);
        }
        if (!empty($condition['user'])){
            $query = $query->where('staff_id', $condition['user']);
        }

        if (!empty($condition['birthday_year'])){
            $query = $query->where('birthday_year', $condition['birthday_year']);
        }
        if (!empty($condition['birthday_month'])){
            $query = $query->where('birthday_month', $condition['birthday_month']);
        }
        if (!empty($condition['birthday_day'])){
            $query = $query->where('birthday_day', $condition['birthday_day']);
        }
        if (!empty($condition['tel'])){
            $query = self::setWhereLike($query, 'tel', $condition['tel']);
        }
        if (!empty($condition['email'])){
            $query = self::setWhereLike($query, 'email', $condition['email']);
        }

        if (!empty($condition['zip21'])){
            $query = self::setWhereLike($query, 'zip21', $condition['zip21']);
        }
        if (!empty($condition['zip22'])){
            $query = self::setWhereLike($query, 'zip22', $condition['zip22']);
        }
        if (!empty($condition['pref21'])){
            $query = self::setWhereLike($query, 'pref21', $condition['pref21']);
        }
        if (!empty($condition['address21'])){
            $query = self::setWhereLike($query, 'address21', $condition['address21']);
        }
        if (!empty($condition['street21'])){
            $query = self::setWhereLike($query, 'street21', $condition['street21']);
        }

        if (empty($condition['hidden_flag'])){
            $query = $query->where('hidden_flag', DatabaseConst::FLAG_OFF);
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
            $query = $query->where('customers.' . $columName, 'LIKE', '%' . $value . '%');
        }
        return $query;
    }

    /**
     * @return object
     */
    public static function getTodayCustomers(int $shopId): object
    {
        $today = new Carbon('today');

        $query = self::select('customers.*', 'users.name')
            ->where('customers.shop_id', $shopId)
            ->where('customers.created_at', '>=', $today->format('Y-m-d'))
        ;
        $query->leftJoin('users', function ($join) {
            $join->on('users.id', '=', 'customers.staff_id');
        });

        return $query;
    }

}
