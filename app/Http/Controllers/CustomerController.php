<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Shop;
use App\User;
use App\Services\CheckData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    private $_customerOBJ;
    private $_user;                 //Auth::user()
    private $_auth_authority_id ;   //権限

    public function __construct(){
        $this->_customerOBJ = new Customer;
        $this->middleware(function ($request, $next) {
            $this->_user = \Auth::user();
            $this->_auth_authority_id = $this->_user->authority_id;
            // if($this->_auth_authority_id >= 8){
            //     session()->flash('msg_danger', '権限がありません');
            //     Auth::logout();
            //     return redirect()->intended('/');
            // }
            return $next($request);
        });
    }

    /**
     * Display the search screen.
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        $shops = Shop::select()
        ->where('hidden_flag','0')
        ->where('delete_flag','0');
        if($this->_auth_authority_id >= 5){
            $shops = $shops->where('id', $this->_user->shop_id );
        }
        $shops = $shops->get();

        $user = User::where('authority_id','<=', 7)
        ->where('authority_id','>=', 3)
        ->get();

        return view('customer.search',compact('shops', 'user'));
    }

    public function searching(Request $request){

        $query = Customer::select('customers.*', 'shops.shop_name', 'users.name')
        ->join('shops', 'shops.id', 'shop_id')
        ->leftJoin('users', 'customers.staff_id', '=', 'users.id');

        // 顧客番号の条件を設定する
        $this->setQueryLike($query, $request->input('member_number'), 'member_number');
        // 苗字の条件を設定する
        $this->setQueryLike($query, $request->input('f_name'), 'f_name');
        // 名前の条件を設定する
        $this->setQueryLike($query, $request->input('l_name'), 'l_name');
        // ミョウジの条件を設定する
        $this->setQueryLike($query, $request->input('l_read'), 'l_read');
        // ナマエの条件を設定する
        $this->setQueryLike($query, $request->input('l_read'), 'l_read');
        // 店舗の条件を設定する
        if($_GET['shop_id']){
            $query->where('customers.shop_id', $_GET['shop_id']);
        }
        // 担当者の条件を設定する
        if($_GET['staff_id']){
            $query->where('customers.staff_id', $_GET['staff_id']);
        }
        // 電話番号の条件を設定する
        $this->setQueryLike($query, $request->input('tel'), 'tel');
        // emailの条件を設定する
        $this->setQueryLike($query, $request->input('email'), 'email');
        // 生年月日の条件を設定する
        $this->setQueryLike($query, $request->input('birthday_year'), 'birthday_year');
        $this->setQueryLike($query, $request->input('birthday_month'), 'birthday_month');
        $this->setQueryLike($query, $request->input('birthday_day'), 'birthday_day');
        // 住所の条件を設定する
        $this->setQueryLike($query, $request->input('zip21'), 'zip21');
        $this->setQueryLike($query, $request->input('zip22'), 'zip22');
        $this->setQueryLike($query, $request->input('pref21'), 'pref21');
        $this->setQueryLike($query, $request->input('addr21'), 'addr21');
        $this->setQueryLike($query, $request->input('strt21'), 'strt21');
        // 非表示の顧客を表示するか設定する
        if( !$request->input('hidden_flag')) $query -> where('customers.hidden_flag','=','0');


        $query -> orderby('customers.id','asc');
        $customers = $query -> paginate(20);
        return view('customer.list', compact('customers'));
    }

    /**
     * 渡されたqueryにwhere句を追加する
     */
    public function setQueryLike($query, $data, $name){
        if($data !== null){
            $data_split = mb_convert_kana($data, 's');    //全角スペースを半角にする
            $data_split2 = preg_split('/[\s]+/', $data_split, -1, PREG_SPLIT_NO_EMPTY);    //半角スペースで区切る
            foreach($data_split2 as $value){
                $query -> where('customers.'.$name ,'like','%'.$value.'%');
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shops = Shop::select()
        ->where('hidden_flag','0')
        ->where('delete_flag','0');
        if($this->_auth_authority_id >= 5){
            $shops = $shops->where('id', $this->_user->shop_id );
        }
        $shops = $shops->get();

        $users = User::
        // where('hidden_flag','0')
        // where('delete_flag','0')
        get();
// dd($user);
        return view('customer.create',compact('shops', 'users' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $customer = $this->_customerOBJ->get_customer($id);
            $customer = CheckData::set_sex_name($customer);
            $customer = CheckData::set_birthday($customer);
            if($this->_auth_authority_id >= 5){
                $customer = CheckData::mask_tel($customer);
                $customer = CheckData::mask_address($customer);
            }
            return view('customer.show', compact('customer'));
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function visit_history($id)
    {
        try {
            // throw new \Exception("処理作成中");
            $customer = $this->_customerOBJ->get_customer($id);
            $customer = CheckData::set_sex_name($customer);
            return view('customer.visit_history', compact('customer'));
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            if($this->_auth_authority_id >= 5) throw new \Exception("編集権限がありません");

            $customer = $this->_customerOBJ->get_customer($id);
            $shops = Shop::select()
            ->where('hidden_flag','0')
            ->where('delete_flag','0')
            ->get();

            return view('customer.edit', compact('customer','shops'));
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $customer = Customer::find($id);
            $customer->member_number = $request->member_number;
            $customer->f_name = $request->f_name;
            $customer->l_name = $request->l_name;
            $customer->f_read = $request->f_read;
            $customer->l_read = $request->l_read;
            $customer->birthday_year = $request->birthday_year;
            $customer->birthday_month = $request->birthday_month;
            $customer->birthday_day = $request->birthday_day;
            $customer->tel = $request->tel;
            $customer->email = $request->email;
            $customer->zip21 = $request->zip21;
            $customer->zip22 = $request->zip22;
            $customer->pref21 = $request->pref21;
            $customer->addr21 = $request->addr21;
            $customer->strt21 = $request->strt21;
            $customer->memo = $request->memo;
            $customer->save();

            // throw new \Exception("強制終了");

            DB::commit();
            session()->flash('msg_success', '更新完了しました');
            return redirect()->action('CustomerController@show', ['id' => $id]);
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
