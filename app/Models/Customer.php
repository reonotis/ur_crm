<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['f_name', 'l_name', 'f_read', 'l_read', 'shop_id']; //保存したいカラム名が複数の場合

    public function get_customer($id)
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
}
