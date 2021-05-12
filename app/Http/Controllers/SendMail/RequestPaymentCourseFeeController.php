<?php

namespace App\Http\Controllers\SendMail;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerCourseMapping;
use App\Models\InstructorCourseSchedule;
use App\Models\InstructorCourse;
use App\Models\Claim;
use App\Models\HistorySendingEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;

class RequestPaymentCourseFeeController extends Controller
{
    private $_user;                 //Auth::user()
    private $_auth_id ;             //Auth::user()->id;
    private $_auth_authority_id ;   //権限
    private $_toInfo ;
    private $_toReon ;
    private $_toCustomer ;
    private $_subject ;
    private $_textCount = 5000 ;

    public function __construct(){
        $this->middleware(function ($request, $next) {
            $this->_user = \Auth::user();
            $this->_auth_id = $this->_user->id;
            $this->_auth_authority_id = $this->_user->authority_id;
            if($this->_auth_authority_id >= 8){
                dd("権限がありません。");
            }
            $this->_toAkemi = config('mail.toAkemi');
            $this->_toInfo = config('mail.toInfo');
            $this->_toReon = config('mail.toReon');
            return $next($request);
        });
    }

    /**
     *
     */
    public function index($id){
        $CCM = CustomerCourseMapping::select('customer_course_mapping.*', 'customers.name')
        ->join('customers','customers.id','customer_course_mapping.customer_id')
        ->find($id);
        $instructor_courses_id =$CCM->instructor_courses_id;

        $ICS = InstructorCourseSchedule::
        where('instructor_courses_id', $instructor_courses_id )
        ->orderBy('date', 'asc')
        ->first();

        // A 購入日から1週間後を調べる
        $oneWeekLater = date('Y-m-d', strtotime('1 week', strtotime($CCM->date)));

        // B コースの初回日時から3日前を調べる
        $threeDaysAGo = date('Y-m-d', strtotime('-3 day', strtotime($ICS->date)));

        // A or B の日付が近い方を期限日にする
        $dayLimit = ($oneWeekLater > $threeDaysAGo) ? $threeDaysAGo : $oneWeekLater;

        // 元々期限が入っていたらその期限にする
        if($CCM->limit_day <> NULL) $dayLimit = $CCM->limit_day;
        $dayLimit = $oneWeekLater;

        return view('email_forms.requestPaymentCourseFee', ['CCM' => $CCM, 'dayLimit'=> $dayLimit]);
    }

    /**
     *
     */
    public function sendmailPaymentCourseFee(Request $request, $id){
        DB::beginTransaction();
        try {
            $CCM = CustomerCourseMapping::find($id);
            $customer_id = $CCM->customer_id;
            $customer = Customer::find($customer_id);
            $IC_id = $CCM->instructor_courses_id;
            $InstructorCourse = InstructorCourse::join('courses', 'courses.id', 'instructor_courses.course_id')->find($IC_id);
            $course_name = $InstructorCourse->course_name . " ";
            if($InstructorCourse->course_title) $course_name .= ": " . $InstructorCourse->course_title. " ";
            $this->_toCustomer = $customer->email;
            $this->_subject = $InstructorCourse->course_name.'受講料のご請求につきまして';

            $dayLimit = $request->dayLimit;
            $str_dayLimit = date('Y年m月d日', strtotime($dayLimit));
            $text = $request->text;
            $text = str_replace("###limitDay###", $str_dayLimit, $text);
            $text = str_replace("###price###", number_format($request->price), $text);
            $text = str_replace("###customerName###", $customer->name, $text);
            $text = str_replace("###course_name###", $course_name, $text);

            if($this->_textCount < mb_strlen($text)) throw new \Exception("文字数が多すぎます");
            if(!$request->dayLimit) throw new \Exception("振込期日を入力してください");

            // 依頼メールの送信
            $data = [
                "text"  => $text,
            ];
            Mail::send('emails.mailtext', $data, function($message){
                $message->to($this->_toCustomer)
                ->cc($this->_toInfo)
                ->bcc($this->_toReon)
                ->subject($this->_subject);
            });
            // メール送信履歴登録
            DB::table('history_send_emails')->insert([[
                'customer_id'=>$customer->id,
                'user_id'    =>$this->_auth_id,
                'title'      =>$this->_subject,
                'text'       =>$text
            ]]);

            // 請求データの上書き
            $Claim = Claim::find($CCM->claim_id);
            $Claim->claim_date = date('Y-m-d');
            $Claim->user_id =  $CCM->customer_id;
            $Claim->title =  $this->_subject;
            $Claim->price =  $request->price;
            $Claim->limit_date =  $dayLimit;
            $Claim->status = 1;
            $Claim->save();
            $last_claim_id = $Claim->id;

            // DB更新
            $CCM->price     = $request->price ;
            $CCM->status    = 1 ;
            $CCM->save();

            DB::commit();
            session()->flash('msg_success', 'メールを送信しました。');
        return redirect()->action('AdminController@unPayd');
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }


}