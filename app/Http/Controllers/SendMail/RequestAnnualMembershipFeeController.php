<?php

namespace App\Http\Controllers\SendMail;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerCourseMapping;
use App\Models\InstructorCourseSchedule;
use App\Models\HistorySendingEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;

class RequestAnnualMembershipFeeController extends Controller
{
    private $_user;                 //Auth::user()
    private $_auth_id ;             //Auth::user()->id;
    private $_auth_authority_id ;   //権限
    private $_toAkemi ;
    private $_toInfo ;
    private $_toReon ;
    private $_toCustomer ;

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
        $annualFee     = config('paralymbics.annualFee.firstTime');
        $CCM = CustomerCourseMapping::select('customer_course_mapping.*', 'customers.name')
        ->join('customers','customers.id','customer_course_mapping.customer_id')
        ->find($id);

        return view('email_forms.requestAnnualMembershipFee', ['CCM'=>$CCM, 'annualFee'=>$annualFee]);
    }

    /**
     *
     */
    public function sendRequestAnnualMembershipFee(Request $request, $id){
        try {
            DB::beginTransaction();

            $text = $request->text;
            $text = str_replace("###price###", number_format($request->price), $text);

            $CCM = CustomerCourseMapping::find($id);
            $customer = Customer::find($CCM->customer_id);
            $this->_toCustomer = $customer->email;

            // 依頼メールの送信
            $data = [
                "text"  => $text,
            ];
            Mail::send('emails.mailtext', $data, function($message){
                $message->to($this->_toCustomer, 'Test')
                ->cc($this->_toAkemi)
                ->bcc($this->_toReon)
                ->subject('入金依頼メール');
            });

            // メール送信履歴登録
            DB::table('history_send_emails')->insert([[
                'customer_id'=>$customer->id,
                'user_id'    =>$this->_auth_id,
                'title'      =>$request->title,
                'text'       =>$request->text
            ]]);

            // DB更新
            $CCM->status    = 8;
            $CCM->save();

            session()->flash('msg_success', 'メールを送信しました。');
            DB::commit();
            
            return redirect()->action('AdminController@customer_complet_course');
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }


}