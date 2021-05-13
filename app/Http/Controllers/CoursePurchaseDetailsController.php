<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerSchedule;
use App\Models\CoursePurchaseDetails;
use App\Models\CoursePurchaseDetailTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\CheckCustomerData;

use App\Http\Controllers\Controller,
    Session;


class CoursePurchaseDetailsController extends Controller
{

    private $_user;                 //Auth::user()
    private $_auth_id ;             //Auth::user()->id;
    private $_auth_authority_id ;   //権限
    private $_toInfo ;
    private $_toReon ;

    public function __construct(){
        $this->middleware(function ($request, $next) {
            $this->_user = \Auth::user();
            $this->_auth_id = $this->_user->id;
            $this->_auth_authority_id = $this->_user->authority_id;
            if($this->_auth_authority_id >= 8){
                dd("権限がありません。");
            }
            $this->_toInfo = config('mail.toInfo');
            $this->_toReon = config('mail.toReon');
            return $next($request);
        });
    }

    /**
     * 新しいコースに申し込む
     *
     */
    public function apply($customer_id){
        // 渡されたIDの顧客情報を取得する
        
        $customer = CheckCustomerData::getCustomer($customer_id);

        // コース一覧を取得する
        $coursesQuery = DB::table('courses');
        $coursesQuery -> where('courses.delete_flag','=','0');
        $courses = $coursesQuery -> get();

        // 購入済みコース明細を取得する
        $CPDQuery = DB::table('course_purchase_details');
        $CPDQuery -> leftJoin('courses', 'courses.id', '=', 'course_purchase_details.purchase_id');
        $CPDQuery -> select('course_purchase_details.*', 'courses.course_name' );
        $CPDQuery -> where('course_purchase_details.customer_id','=',$customer_id);
        $CoursePurchaseDetails = $CPDQuery -> get();


        return view('customer.appryCourse', compact('customer', 'courses', 'CoursePurchaseDetails'));
    }

    /**
     * 申し込むコースをトランザクションに入れる
     *
     */
    public function applySecond(Request $request){
        $course_id   = $request->input('children');
        $customer_id = $request->input('customer_id');

        // キャンセルをした場合は戻る
        if($request->input('cancel')){
            return redirect()->action('CustomerController@display', ['id' => $customer_id]);
        }
        // POSTでデータが渡ってきているか確認する
        if(!$course_id || !$customer_id  ){
            return redirect()->back();    // 前の画面へ戻る
        }

        // 一度、対象顧客のトランザクションテーブルを物理削除する
        DB::table('course_purchase_detail_transactions')->where('customer_id', '=',$customer_id )->delete();

        // POSTで渡されたコースIDのレコードを取得
        $coursesQuery = DB::table('courses') -> where('courses.id', '=', $course_id);
        $course = $coursesQuery -> first();

        // 使用していいコースかを判定
        if($course->delete_flag === 1){  //削除されたコースじゃないか
            dd($course);
            return redirect()->back();
            echo "不正なリクエストですというページに遷移したい";
        }

        // トランザクションに登録
        $Tran = new CoursePurchaseDetailTransactions;
        $Tran -> purchase_id = $course_id;
        $Tran -> customer_id = $customer_id;
        $Tran -> save();

        // 渡されたIDの顧客情報を取得する
        $customer = CheckCustomerData::getCustomer($customer_id);

        return view('customer.appryCourseSecond', compact('customer', 'course'));
    }

    /**
     * 申し込みを完了する。
     *
     */
    public function courseApply(Request $request){
        // エラーチェックをしたい
        $purchase_id    = $request->input('course_id');
        $how_many_times = $request->input('how_many_times');
        $price          = $request->input('price');
        $pay_confirm    = ($request->input('pay_confirm') == NULL)? '0' : '1' ;
        $customer_id    = $request->input('customer_id');
        $today          = date("Y-m-d");

        // キャンセルをした場合は戻る
        if($request->input('cancel')){
            return redirect()->action('CustomerController@display', ['id' => $customer_id]);
        }

        $auths = Auth::user();

        // 購入したコースの明細を登録
        $Tran = new CoursePurchaseDetails;
        $Tran -> customer_id = $customer_id;
        $Tran -> date        = $today ;
        $Tran -> purchase_id = $purchase_id;
        $Tran -> price       = $price;
        $Tran -> pay_confirm = $pay_confirm;
        $Tran -> save();

        $date = date("Y-m-d",strtotime("+7 day"));

        // 回数分のスケジュールを登録
        for($i = 1; $i < $how_many_times + 1 ; $i++){
            $cSch = new CustomerSchedule;
            $cSch -> customer_id = $customer_id;
            $cSch -> course_id = $purchase_id;
            $cSch -> date = $date;
            $cSch -> howMany = $i;
            $cSch -> instructor_id = $auths->id;
            $cSch -> save();
        }

        return redirect()->action('CustomerController@display', ['id' => $customer_id]);
    }

    public function scheduleEdit($id){
        // 渡されたIDの顧客情報を取得する
        $query = CustomerSchedule::select('customer_schedules.*', 'users.name')
            ->join('users', 'users.id', '=', 'customer_schedules.instructor_id')
            -> where('customer_schedules.id','=',$id);
        $customerSchedule = $query -> first();

        $customer_id = $customerSchedule -> customer_id;
        $customer = CheckCustomerData::getCustomer($customer_id);

        return view('customer.editSchedule',compact('customer', 'customerSchedule'));
    }

    public function scheduleUpdate(Request $request, $id){
        try{
            throw new \Exception("機能さくせいちゅです。");
            $id         = $request->input('id');
            $date       = $request->input('date');
            $time       = $request->input('time');
            $status     = $request->input('status');
            $comment    = $request->input('comment');
            $memo       = $request->input('memo');

            $CS = CustomerSchedule::find($id);
            if($this->_auth_authority_id >= 5){
                dd( $this->_auth_authority_id  , $CS->instructor_id);
            }
            $CS->date    = $date ;
            $CS->time    = $time ;
            $CS->status  = $status ;
            $CS->comment = $comment ;
            $CS->memo    = $memo ;
            $CS->save();

            $customer_id = $CS->customer_id;

            return redirect()->action('CustomerController@display', ['id' => $customer_id]);
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

}

