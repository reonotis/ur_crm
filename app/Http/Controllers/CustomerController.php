<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\CheckCustomerData;

use App\Http\Controllers\Controller,
    Session;



class CustomerController extends Controller
{

    private $_user;                 //Auth::user()
    private $_auth_id ;             //Auth::user()->id;
    private $_auth_authority_id ;   //権限

    public function __construct(){
        $this->middleware(function ($request, $next) {
            $this->_user = \Auth::user();
            $this->_auth_id = $this->_user->id;
            $this->_auth_authority_id = $this->_user->authority_id;
            if($this->_auth_authority_id >= 8){
                dd("権限がありません。");
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index(){
        return view('customer.index');
    }

    /**
     * 顧客の登録画面に遷移します。
     *
     */
    public function create(){
        return view('customer.create');
    }

    /**
     * 検索画面を表示する
     */
    public function search(){
        // アーティサンコマンドでWordpressからの申し込みファイルをインポートする
        Artisan::call('command:courseApplicationImport');
        return view('customer.search');
    }

    /**
     * 条件に基づき検索した結果を表示する
     */
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

        // ユーザーの権限がエージェント以下だったら自分の顧客だけ選択する
        if($this->_auth_authority_id >= 7) $query -> where('customers.instructor' ,'=', $this->_auth_id);

        $query -> select('customers.*','users.name as instName');
        $query -> orderby('customers.id','asc');
        $customers = $query -> paginate(20);

        return view('customer.list', ['customers' => $customers]);
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
     * 顧客の詳細画面を表示する
     */
    public function display($customer_id){
        // 渡されたIDの顧客情報を取得する
        $customer = CheckCustomerData::getCustomer($customer_id);

        // 顧客情報が無ければ前の画面に戻る
        if(empty($customer))return redirect()->back();

        // 権限がエージェントだったら住所にmaskをかける
        if($this->_auth_authority_id >= 7) $customer = $this->maskCustomerData($customer);

        // 顧客のスケジュールを取得する
        $CSQuery = DB::table('customer_schedules');
        $CSQuery   -> select('customer_schedules.*', 'users.name as intrName', 'courses.course_name' );
            $CSQuery -> leftJoin('users', 'users.id', '=', 'customer_schedules.instructor_id');
            $CSQuery -> leftJoin('course_schedules', 'course_schedules.id', '=', 'customer_schedules.course_schedules_id');
            $CSQuery -> leftJoin('courses', 'courses.id', '=', 'course_schedules.course_id');
        $CSQuery -> where('customer_schedules.customer_id','=',$customer_id);
        $CSQuery -> orderByRaw('customer_schedules.date DESC , customer_schedules.time DESC , customer_schedules.howMany DESC ');
        $CustomerSchedules = $CSQuery -> get();
        $CustomerSchedules =  CheckCustomerData::attendanceStatus($CustomerSchedules);
        
        // 購入コース明細を取得する
        $CPDQuery = DB::table('customer_course_mapping');
        $CPDQuery -> leftJoin('course_schedules', 'course_schedules.id', '=', 'customer_course_mapping.course_schedule_id');
        $CPDQuery -> leftJoin('courses', 'courses.id', '=', 'course_schedules.course_id');
        $CPDQuery -> select('customer_course_mapping.*', 'courses.course_name' );
        $CPDQuery -> where('customer_course_mapping.customer_id','=',$customer_id);
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
        $query -> where ('enrolled_id', '<', '5');
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
        return back()->withInput();
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


        return redirect()->action('CustomerController@display', ['id' => $id]);
    }

    /**
     * 郵便番号3桁のバリデーションチェックを行います。
     * input $date
     */
    public function checkValidationZip1($date){
        $this->_zip21 = NULL;
        if(strlen($date) <> 3 ){
            dd("郵便番号3桁が不正な値です。");
            return false;
        }else{
            $this->_zip21 = sprintf('%03d', $date);
        }
    }

    /**
     * 顧客情報にmaskをかけます
     */
    public function maskCustomerData($customer){
        $customer->strt21 = str_repeat("*",  mb_strlen($customer->strt21));
        return $customer;
    }

}
