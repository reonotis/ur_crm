<?php

namespace App\Http\Controllers;

use App\Models\CustomerCourseMapping;
use App\Models\CustomerSchedule;
use App\Models\Customer;
use App\Models\Claim;
use App\Models\InstructorCourseSchedule;
use App\User;
use App\Services\CheckClaims;
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
    private $_toInfo ;
    private $_toReon ;
    private $_toCustomer ;
    private $_toInstructor ;

    public function __construct(){
        $this->middleware(function ($request, $next) {
            $this->_user = Auth::user();
            $this->_auth_id = $this->_user->id;
            $this->_auth_authority_id = $this->_user->authority_id;
            if($this->_auth_authority_id >= 5){
                session()->flash('msg_danger', '権限がありません');
                Auth::logout();
                return redirect()->intended('/');
            }
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
    public function customer_complete_course()
    {
        $CCMs = CustomerCourseMapping::select('customer_course_mapping.*', 'customers.name')
        ->join('customers', 'customers.id', 'customer_id')
        ->join('instructor_courses', 'instructor_courses.id', 'customer_course_mapping.instructor_courses_id')
        ->where('course_id', 6)
        ->where('status', '>=',5)
        ->where('status', '<=',6)
        ->get();

        return view('admin.customer_complete_course', ['CCMs' => $CCMs, 'a' => 1]);
    }

    /**
     * 未入金者の一覧を表示する
     */
    public function unPayd()
    {
        $claims = Claim::select('claims.*','customers.name', 'CCM.id as CCM_id', 'CCM.instructor_courses_id', 'CCM.date', 'courses.course_name')
        ->where('claims.user_type',1)
        ->where('claims.status',1)
        ->join('customers', 'customers.id', 'claims.user_id' )
        ->join('customer_course_mapping as CCM', 'CCM.claim_id', 'claims.id' )
        ->join('instructor_courses', 'instructor_courses.id', 'CCM.instructor_courses_id' )
        ->join('courses', 'courses.id', 'instructor_courses.course_id' )
        ->get();

        $instructorClaims = Claim::select('claims.*','users.name')
        ->where('claims.user_type',2)
        ->where('claims.status',1)
        ->join('users', 'users.id', 'claims.user_id' )
        ->get();

        return view('admin.unpaid_customer', ['claims' => $claims, 'instructorClaims' => $instructorClaims]);
    }

    /**
     * 新しく申し込みがあった情報を表示する
     */
    public function newApply()
    {
        $subQuery = InstructorCourseSchedule::whereIn('date', function($query) {
            $query->select(DB::raw('min(date) As date'))->from('instructor_course_schedules')->groupByRaw('instructor_courses_id');
        });

        $CCMs = CustomerCourseMapping::select('customer_course_mapping.*', 'customers.name', 'courses.course_name','instructor_course_schedules.date as first_date')
        ->join('customers', 'customers.id', 'customer_course_mapping.customer_id')
        ->join('instructor_courses', 'instructor_courses.id', 'customer_course_mapping.instructor_courses_id')
        ->join('courses', 'courses.id', 'instructor_courses.course_id')
        ->joinSub($subQuery, 'instructor_course_schedules', function ($join) {
            $join->on('instructor_course_schedules.instructor_courses_id', '=', 'instructor_courses.id');
        })
        ->where('status', 0)
        ->get();

        return view('admin.newApply', ['CCMs' => $CCMs, 'a' => 1]);
    }

    /**
     * インストラクター規約に同意した顧客をインストラクターに登録する
     */
    public function completeContract($id)
    {
        try {
            DB::beginTransaction();
            $CCM = CustomerCourseMapping::find($id);
            $CCM->status = 7;
            $CCM->save();

            $customer = Customer::find($CCM->customer_id);
            // ログイン時のパスワードを誕生日に設定
            $password = $customer->birthdayYear . $customer->birthdayMonth . $customer->birthdayDay;
            // パスワードが8桁にならなかったらエラー
            if(strlen($password) <> 8 ) throw new \Exception("パスワードを設定する事ができませんでした。");

            // ユーザー登録
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

            // 顧客にインストラクター登録完了メールを送信する
            $this->_toCustomer = $customer->email;
            $data = [
                "customer_name"  => $customer->name,
                "mail"  => $customer->email,
                "password"  => $password,
                "url"         => url('').'/home'
            ];
            // お客様へインストラクター登録のメールを送る
            Mail::send('emails.registerInstructor_forCustomer', $data, function($message){
                $message->to($this->_toCustomer)
                ->cc($this->_toInfo)
                ->bcc($this->_toReon)
                ->subject('インストラクターへの登録が完了しました');
            });

            // TODO メール送信履歴を登録したい
            // history_send_emails_instructors

            // TODO 初回請求内容を作成

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

    // public function confirmedPaymentCourseFee($id)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $CCM = CustomerCourseMapping::find($id);
    //         $CCM->pay_confirm = 1;
    //         $CCM->status = 3;
    //         $CCM->save();

    //         // 顧客とインストラクターのmail_addresを取得
    //         $customer = Customer::find($CCM->customer_id);
    //         $this->_toCustomer = $customer->email;

    //         $user = User::find($CCM->instructor_id);
    //         $this->_toInstructor = $user->email;
    //         $instructor_name = $user->name;
    //         $customer_name = $customer->name ;

    //         $data = [
    //             "instructor_name"  => $instructor_name,
    //             "customer_name"  => $customer_name,
    //         ];
    //         // お客様へ入金確認のメールを送る
    //         Mail::send('emails.confirmedPaymentCourseFee_forCustomer', $data, function($message){
    //             $message->to($this->_toCustomer)
    //             ->cc($this->_toInfo)
    //             ->bcc($this->_toReon)
    //             ->subject('ご入金を確認いたしました');
    //         });

    //         // インストラクターへ入金確認のメールを送る
    //         Mail::send('emails.confirmedPaymentCourseFee_forInstructor', $data, function($message){
    //             $message->to($this->_toInstructor)
    //             ->cc($this->_toInfo)
    //             ->bcc($this->_toReon)
    //             ->subject('お申込者のご入金確認通知');
    //         });
    //         // throw new \Exception("強制修了");

    //         DB::commit();
    //         session()->flash('msg_success', '入金確認を完了にしました。');
    //         return redirect()->action('CustomerController@display', ['id' => $CCM->customer_id, 'a' => 1]);
    //     } catch (\Throwable $e) {
    //         DB::rollback();
    //         session()->flash('msg_danger',$e->getMessage() );
    //         return redirect()->back();    // 前の画面へ戻る
    //     }
    // }

    public function cancelCourseMapping($id)
    {
        DB::beginTransaction();
        try {
            // CustomerCourseMappingのキャンセル処理
            $CCM = CustomerCourseMapping::find($id);
            $CCM->status = 2 ;
            $CCM->save();
            $CCM_ID = $CCM->instructor_courses_id;

            // customer_schedulesのキャンセル処理
            $ICSs = InstructorCourseSchedule::where('instructor_courses_id', $CCM_ID)->get();
            foreach($ICSs as $ICS){
                $ICS_IDs[] = $ICS->id ;
            }
            $idLists =  explode(",", implode(",", $ICS_IDs));
            $CS = CustomerSchedule::whereIn('course_schedules_id',$idLists)->where('customer_id', $CCM->customer_id )->update(['delete_flag' => 1]);

            // Claimのキャンセル処理
            $claim_ID = $CCM->claim_id;
            $result = Claim::where('id',$claim_ID)->update(['status' => 3]);
            if(!$result) throw new \Exception("キャンセル処理に失敗しました。");

            DB::commit();
            session()->flash('msg_success', 'キャンセル処理を行いました');
            return redirect()->action('CustomerController@display', ['id' => $CCM->customer_id, 'a' => 1]);
        } catch (\Throwable $e) {
            DB::rollback();
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
    public function courseMappingShow($id)
    {
        $CCMs = CustomerCourseMapping::select('customer_course_mapping.*', 'customers.name', 'courses.course_name', 'users.name AS intr_name')
        ->join('customers', 'customers.id', 'customer_course_mapping.customer_id')
        ->join('instructor_courses', 'instructor_courses.id', 'customer_course_mapping.instructor_courses_id')
        ->join('courses', 'courses.id', 'instructor_courses.course_id')
        ->join('users', 'users.id', 'customer_course_mapping.instructor_id')
        ->find($id);
        $ICS = InstructorCourseSchedule::where('instructor_courses_id',$CCMs->instructor_courses_id )->orderBy('date')->first();
        $firstDate = $ICS->date;

        $claim_ID = $CCMs->claim_id;
        // claimsのデータがなければ作成する
        if(!$claim_ID){
            $claim = new Claim;
            $claim->user_type = 1 ;
            $claim->user_id = $CCMs->customer_id;
            $claim->price = $CCMs->price;
            $claim->save();
            $claim_ID = $claim->id;
            CustomerCourseMapping::where('id', $id)->update(['claim_id' => $claim_ID]);
        }
        $claim = Claim::find($claim_ID);
        $claim = CheckClaims::setStatus($claim);
        return view('admin.courseMappingShow', compact('CCMs','claim','firstDate') );
        //
    }

    /**
     * コース代金の入金を確認した状態に更新する
     */
    public function completeCourseFee(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            if(!$request->complete_date) throw new \Exception("売上計上日を入力してください");

            // CustomerCourseMappingの更新
            $CCM = CustomerCourseMapping::find($id);
            $CCM->status = 3;
            $CCM->save();

            // Claimの更新
            $claim = Claim::find($CCM->claim_id);
            $claim->status = 5;
            $claim->complete_date = $request->complete_date;
            $claim->save();

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
                ->cc($this->_toInfo)
                ->bcc($this->_toReon)
                ->subject('ご入金を確認いたしました');
            });
            // インストラクターへ入金確認のメールを送る
            Mail::send('emails.confirmedPaymentCourseFee_forInstructor', $data, function($message){
                $message->to($this->_toInstructor)
                ->cc($this->_toInfo)
                ->bcc($this->_toReon)
                ->subject('お申込者のご入金確認通知');
            });

            DB::commit();
            session()->flash('msg_success', '入金確認済みに更新しました');
            // return redirect()->action('CustomerController@display', ['id' => $CCM->customer_id, 'a' => 1]);
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
        }
        return redirect()->back();    // 前の画面へ戻る

    }


    public function sales()
    {
        $month = date('Y-m', strtotime(date('Y-m-1').' -1 month'));//先月
        if(!empty($_GET['month'])) $month = $_GET['month'];

        $claim= Claim::where('claim_date', 'like', $month.'%')
        ->where('status',5)->get();



        // dd($claim);
        return view('admin.sales', compact('claim'));

    }




}
