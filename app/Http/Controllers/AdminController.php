<?php

namespace App\Http\Controllers;

use App\Models\CustomerCourseMapping;
use App\Models\Customer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;

class AdminController extends Controller
{
    private $_user;
    protected $_auth_id ;
    protected $_auth_authority_id ;
    public $_backslash = '\\';
    private $_toAkemi ;
    private $_toInfo ;
    private $_toReon ;
    private $_toCustomer ;
    private $_toInstructor ;

    public function __construct(){
        $this->middleware(function ($request, $next) {
            $this->_user = \Auth::user();
            $this->_auth_id = $this->_user->id;
            $this->_auth_authority_id = $this->_user->authority_id;
            if($this->_auth_authority_id >= 5){
                dd("権限がありません。");
            }
            $this->_toAkemi = config('mail.toAkemi');
            $this->_toInfo = config('mail.toInfo');
            $this->_toReon = config('mail.toReon');
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.index');
    }

    /**
     * 
     */
    public function customer_complet_course()
    {
        $CCMs = CustomerCourseMapping::select('customer_course_mapping.*', 'customers.name')
        ->join('customers', 'customers.id', 'customer_id')
        ->join('instructor_courses', 'instructor_courses.id', 'customer_course_mapping.instructor_courses_id')
        ->where('course_id', 6)
        ->where('status', '>=',5)
        ->where('status', '<=',6)
        ->get();

        return view('admin.customer_complet_course', ['CCMs' => $CCMs, 'a' => 1]);
    }



    /**
     * 
     */
    public function unPayd()
    {
        $CCMs = CustomerCourseMapping::select('customer_course_mapping.*', 'customers.name', 'courses.course_name')
        ->join('customers', 'customers.id', 'customer_course_mapping.customer_id')
        ->join('instructor_courses', 'instructor_courses.id', 'customer_course_mapping.instructor_courses_id')
        ->join('courses', 'courses.id', 'instructor_courses.course_id')
        ->where('pay_confirm', 0)
        ->get();

        return view('admin.unpaid_customer', ['CCMs' => $CCMs, 'a' => 1]);
    }


    /**
     * 
     */
    public function completeContract($id)
    {
        try {
            DB::beginTransaction();
            $CCM = CustomerCourseMapping::find($id);
            $CCM->status = 7;
            $CCM->save();
            // throw new \Exception("強制修了");
            $customer = Customer::find($CCM->customer_id);
            $password = $customer->birthdayYear . $customer->birthdayMonth . $customer->birthdayDay;

            $User = new User;
            $User->customer_id = $customer->id ;    //    顧客だった時のID
            $User->name        = $customer->name ;
            $User->read        = $customer->read ;
            $User->email       = $customer->email ;
            $User->password    = Hash::make($password);
            $User->authority_id= 9 ;
            $User->enrolled_id= 9 ;
            $User->save();


            DB::table('users_info')
            ->updateOrInsert(
                [   'tel' => $customer->tel ,
                    'intr_No'  => $customer->menberNumber ,
                    'birthdayYear'  => $customer->birthdayYear ,
                    'birthdayMonth'  => $customer->birthdayMonth ,
                    'birthdayDay'  =>  $customer->birthdayDay ,
                    'zip21'  => $customer->zip21 ,
                    'zip22'  => $customer->zip22 ,
                    'pref21'  => $customer->pref21 ,
                    'addr21'  => $customer->addr21 ,
                    'strt21'  => $customer->strt21
                ],
                ['id' => $User->id ]
            );

            // dd($User);

            DB::commit();
            session()->flash('msg_success', '契約完了にし、インストラクターの登録を行いました。');
            return redirect()->action('UserController@display', ['id' => $User->id]);
            // return redirect()->back();    // 前の画面へ戻る
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }

    }

    public function confirmedPaymentCourseFee($id)
    {
        try {
            DB::beginTransaction();
            $CCM = CustomerCourseMapping::find($id);
            $CCM->pay_confirm = 1;
            $CCM->status = 3;
            $CCM->save();

            // 顧客とインストラクターのmail_addresを取得
            $customer = Customer::find($CCM->customer_id);
            $this->_toCustomer = $customer->email;

            $user = User::find($CCM->instructor_id);
            $this->_toInstructor = $user->email;
            $instructor_name = $user->name;
            $customer_name = $customer->name ;

            $data = [
                "instructor_name"  => $instructor_name,
                "customer_name"  => $customer_name,
            ];
            // お客様へ入金確認のメールを送る
            Mail::send('emails.confirmedPaymentCourseFee_forCustomer', $data, function($message){
                $message->to($this->_toCustomer)
                ->cc($this->_toAkemi)
                ->bcc($this->_toReon)
                ->subject('ご入金を確認いたしました');
            });

            // インストラクターへ入金確認のメールを送る
            Mail::send('emails.confirmedPaymentCourseFee_forInstructor', $data, function($message){
                $message->to($this->_toInstructor)
                ->cc($this->_toAkemi)
                ->bcc($this->_toReon)
                ->subject('お申込者のご入金確認通知');
            });
            // throw new \Exception("強制修了");

            DB::commit();
            session()->flash('msg_success', '入金確認を完了にしました。');
            return redirect()->action('CustomerController@display', ['id' => $CCM->customer_id, 'a' => 1]);
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }
}
