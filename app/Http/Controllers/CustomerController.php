<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Http\Controllers\Controller,
    Session;



class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $auths = Auth::user();
        // dd($auths->id);
        // if($auths->authority === 1){
            return view('customer.index');
        // }
    }

    public function search(){
        return view('customer.search');
    }

    public function searching(Request $request){

        $query = DB::table('customers')
                    ->leftJoin('users', 'customers.instructor', '=', 'users.id');

        // 顧客番号の条件を設定する
        $this->setQueryLike($query, $request->input('menberNumber'), 'menberNumber');

        // 名前の条件を設定する
        $this->setQueryLike($query, $request->input('name'), 'name');

        // ヨミの条件を設定する
        $this->setQueryLike($query, $request->input('read'), 'read');

        // 電話番号の条件を設定する
        $this->setQueryLike($query, $request->input('tel'), 'tel');

        // emailの条件を設定する
        $this->setQueryLike($query, $request->input('email'), 'email');

        // 生年月日の条件を設定する
        $this->setQueryLike($query, $request->input('birthdayYear'), 'birthdayYear');
        $this->setQueryLike($query, $request->input('birthdayMonth'), 'birthdayMonth');
        $this->setQueryLike($query, $request->input('birthdayDay'), 'birthdayDay');

        // 住所の条件を設定する
        $this->setQueryLike($query, $request->input('zip21'), 'zip21');
        $this->setQueryLike($query, $request->input('zip22'), 'zip22');
        $this->setQueryLike($query, $request->input('pref21'), 'pref21');
        $this->setQueryLike($query, $request->input('addr21'), 'addr21');
        $this->setQueryLike($query, $request->input('strt21'), 'strt21');





        // 非表示の顧客を表示するか設定する
        if( !$request->input('hidden_flag')) $query -> where('customers.hidden_flag','=','0');


        // ユーザーのステータスが__以下だったら自分の顧客だけ選択する



        $query -> select('customers.*','users.name as instName');
        $query -> orderby('customers.id','asc');
        // dd($query);
        $customers = $query -> paginate(20);
        // Session::put('search_client_id', $customers);

        // $client_id = $customers[0]->id;

        return view('customer.list', ['customers' => $customers]);
        // return redirect()->action('ClientController@display', ['id' => $client_id]);
    }

    public function setQueryLike($query, $date, $name){

        if($date !== null){
            $date_split = mb_convert_kana($date, 's');    //全角スペースを半角にする
            $date_split2 = preg_split('/[\s]+/', $date_split, -1, PREG_SPLIT_NO_EMPTY);    //半角スペースで区切る
            foreach($date_split2 as $value){
                $query -> where('customers.'.$name ,'like','%'.$value.'%');
            }
        }
    }

    public function display($client_id){
        // 渡されたIDの顧客情報を取得する
        $query = DB::table('customers');
        $query -> leftJoin('users', 'users.id', '=', 'customers.instructor');
        $query -> select('customers.*', 'users.name as intrName');
        $query -> where('customers.id','=',$client_id);
        $customer = $query -> first();

        return view('customer.display', compact('customer'));
    }

}
