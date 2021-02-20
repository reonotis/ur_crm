<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\CheckCustomerData;

use App\Http\Controllers\Controller,
    Session;



class CustomerController extends Controller
{

    private $_zip21;


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

        return view('customer.list', ['customers' => $customers]);
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
        $customer = CheckCustomerData::checkSex($customer);
        $customer = CheckCustomerData::hiddenStatus($customer);

        // 顧客のスケジュールを取得する
        $CSQuery = DB::table('customer_schedules');
            $CSQuery -> leftJoin('users', 'users.id', '=', 'customer_schedules.instructor_id');
            $CSQuery -> leftJoin('courses', 'courses.id', '=', 'customer_schedules.course_id');
        $CSQuery   -> select('customer_schedules.*', 'users.name as intrName', 'courses.course_name' );
        $CSQuery -> where('customer_schedules.customer_id','=',$client_id);
        $CSQuery -> orderByRaw('customer_schedules.date DESC , customer_schedules.time DESC , customer_schedules.howMany DESC ');
        $CustomerSchedules = $CSQuery -> get();
        $CustomerSchedules =  CheckCustomerData::attendanceStatus($CustomerSchedules);


        // 購入コース明細を取得する　course_purchase_details
        $CPDQuery = DB::table('course_purchase_details');
        $CPDQuery -> leftJoin('courses', 'courses.id', '=', 'course_purchase_details.purchase_id');
        $CPDQuery -> select('course_purchase_details.*', 'courses.course_name' );
        $CPDQuery -> where('course_purchase_details.customer_id','=',$client_id);
        $CoursePurchaseDetails = $CPDQuery -> get();

        return view('customer.display', compact('customer', 'CustomerSchedules', 'CoursePurchaseDetails'));
    }

    /**
    * 顧客情報を編集します
    */
    public function edit($client_id){
        // 渡されたIDの顧客情報を取得する
        $query = DB::table('customers');
            $query -> leftJoin('users', 'users.id', '=', 'customers.instructor');
        $query -> select('customers.*', 'users.name as intrName');
        $query -> where('customers.id','=',$client_id);
        $customer = $query -> first();

        // 選択できるインストラクターを取得する
        $query = DB::table('users');
        $query -> where ('enrolled', '<', '5');
        $query -> orWhere ('id', '=', $customer->instructor);
        $users = $query -> get();

        return view('customer.edit', compact('customer', 'users'));
    }







    /**
    * 顧客情報を更新します。
    */
    public function update(Request $request, $id){
        $birthdayYear  = $request->input('birthdayYear');
        $birthdayMonth = $request->input('birthdayMonth');
        $birthdayDay   = $request->input('birthdayDay');
        $tel           = $request->input('tel');
        $email         = $request->input('email');
        $zip21         = $request->input('zip21');
        $zip22         = $request->input('zip22');
        $pref21        = $request->input('pref21');
        $addr21        = $request->input('addr21');
        $strt21        = $request->input('strt21');
        $instructor    = $request->input('instructor');
        $memo          = $request->input('memo');
        $hidden_flag   = ($request->input('hidden_flag')) ?  "1": "0" ;

        // return redirect()->back();    // 前の画面へ戻る

        $customer = Customer::find($id);
        $customer->birthdayYear = $birthdayYear ;
        $customer->birthdayMonth = $birthdayMonth ;
        $customer->birthdayDay  = $birthdayDay ;
        $customer->tel          = $tel ;
        $customer->email        = $email ;
        $customer->zip21        = $zip21 ;
        $customer->zip22        = $zip22 ;
        $customer->pref21       = $pref21 ;
        $customer->addr21       = $addr21 ;
        $customer->strt21       = $strt21 ;
        $customer->instructor   = $instructor ;
        $customer->memo         = $memo ;
        $customer->hidden_flag  = $hidden_flag ;
        $customer->save();


        return redirect()->action('customerController@display', ['id' => $id]);
    }





    /**
    * 郵便番号3桁のバリデーションチェックを行います。
    * input $date
    */
    function checkValidationZip1($date){
        $this->_zip21 = NULL;
        if(strlen($date) <> 3 ){
            dd("郵便番号3桁が不正な値です。");
            return false;
        }else{
            $this->_zip21 = sprintf('%03d', $date);
        }
    }

    /**
    * 電話番号のバリデーションチェックを行います。
    */
    function zip2($date){}
















}
