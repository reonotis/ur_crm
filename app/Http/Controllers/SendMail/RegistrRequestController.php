<?php

namespace App\Http\Controllers\SendMail;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerCourseMapping;
use App\Models\HistorySendingEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;

class RegistrRequestController extends Controller
{
    private $_user;                 //Auth::user()
    private $_auth_id ;             //Auth::user()->id;
    private $_auth_authority_id ;   //権限
    private $_toAkemi ;
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
            $this->_toAkemi = config('mail.toAkemi');
            $this->_toInfo = config('mail.toInfo');
            $this->_toReon = config('mail.toReon');
            return $next($request);
        });
    }

    //
    public function instructorRegistrRequest($id){
        $customer = CustomerCourseMapping::select('customer_course_mapping.*', 'customers.name')
        ->join('customers','customers.id','customer_course_mapping.customer_id')
        ->find($id);
        return view('email_forms.registrRequest', ['customer' => $customer]);
    }

    //
    public function sendmailRegistrRequest(Request $request, $id){
        try {

            // 依頼メールの送信
            $data = [
                "text"  => $request->text,
            ];
            Mail::send('emails.mailtext', $data, function($message){
                $message->to($this->_toInfo, 'Test')
                ->cc($this->_toAkemi)
                ->bcc($this->_toReon)
                ->subject('インストラクター登録依頼');
            });


            // DB更新
            $CCM = CustomerCourseMapping::find($id);
            $CCM->status = 6;
            $CCM->save();

            // メール送信履歴登録
            DB::table('history_sending_emails')->insert([
                ['customer_id'=>$CCM->customer_id, 'title'=>$request->title, 'text'=>$request->text]
            ]);

            session()->flash('msg_success', 'メールを送信しました。');
        return redirect()->action('AdminController@customer_complet_course');
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }
}