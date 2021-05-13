<?php

namespace App\Http\Controllers\SendMail;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Claim;
use App\User;
use App\Models\CustomerCourseMapping;
use App\Models\HistorySendingEmail;
use App\Models\HistorySendEmailsInstructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;

class sendEmailController extends Controller
{
    private $_user;                 //Auth::user()
    private $_auth_id ;             //Auth::user()->id;
    private $_auth_authority_id ;   //権限
    private $_toReon ;
    private $_toInstructor ;
    private $_mailTitle ;

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


    //
    public function index(Request $request, $id){
        try {
            $user = User::find($id);

            return view('email_forms.sendEmail', compact('user') );

        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }


    //
    public function sendMail(Request $request, $id){
        try {
            DB::beginTransaction();
            $user = User::find($id);
            $this->_toInstructor = $user->email;
            $this->_mailTitle= $request->title;

            $text = $request->text;
            // 依頼メールの送信
            $data = [
                "text"  => $text,
            ];
            Mail::send('emails.mailtext', $data, function($message){
                $message->to($this->_toInstructor)
                ->bcc($this->_toReon)
                ->subject($this->_mailTitle);
            });

            // メール送信履歴登録
            DB::table('history_send_emails_instructors')->insert([[
                'instructor_id' => $id,
                'user_id'       => $this->_auth_id,
                'title'         => $this->_mailTitle,
                'text'          => $text
            ]]);

            DB::commit();
            session()->flash('msg_success', 'メールを送信しました。');
            return redirect()->action('UserController@display',['id'=>$id]);
            // return redirect()->action('AdminController@customer_complet_course');
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }
}