<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['f_name', 'l_name', 'f_read', 'l_read', 'shop_id']; //保存したいカラム名が複数の場合

    public static function get_customer($id)
    {
        $customer = Customer::select('customers.*', 'shops.shop_name', 'users.name AS staff_name')
        ->join('shops', 'shops.id', 'shop_id')
        ->leftJoin('users', 'customers.staff_id', '=', 'users.id')
        ->where('customers.id', $id)
        ->where('customers.delete_flag', '0')
        ->first();

        if(!$customer) throw new \Exception("データがありません");
        return($customer);
    }

    /**
     * 本日来店時に登録した顧客を取得する
     * @param [type] $shop_id
     * @return void
     */
    public static function get_todayRegisterCustomer($shop_id)
    {
        $result = self::select('customers.*', 'users.name as user_name', 'visit_histories.id as visit_history_id')
        ->whereDate('customers.created_at', date('Y-m-d'))
        ->where('customers.register_flow', 1)
        ->where('customers.delete_flag', 0)
        ->where('customers.shop_id', $shop_id)
        ->leftJoin('visit_histories', function ($join) {
            $join->on('customers.id', '=', 'visit_histories.customer_id')
                ->where('visit_histories.delete_flag', '0');
        })
        ->leftJoin('users', 'users.id', '=', 'customers.staff_id')
        ->get();
        return($result);
    }

}
