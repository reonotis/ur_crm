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

class NewClaimController extends Controller
{
    private $_user;                 //Auth::user()
    private $_auth_id ;             //Auth::user()->id;
    private $_auth_authority_id ;   //権限
    private $_toInfo ;
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
            $this->_toAkemi = config('mail.toAkemi');
            $this->_toInfo = config('mail.toInfo');
            $this->_toReon = config('mail.toReon');
            return $next($request);
        });
    }


    //
    public function index(Request $request, $id){
        // try {
        //     $user = User::find($id);

        //     return view('email_forms.newClaim', compact('user') );

        // } catch (\Throwable $e) {
        //     session()->flash('msg_danger',$e->getMessage() );
        //     return redirect()->back();    // 前の画面へ戻る
        // }
    }


    //
    public function sendMailNewClaim(Request $request, $id){
        try {
            DB::beginTransaction();
            $user = User::find($id);
            $this->_toInstructor = $user->email;
            $this->_mailTitle= $request->title;

            $text = $request->text;
            $text = str_replace("###item_name###", $request->item_name, $text);
            $text = str_replace("###price###", number_format($request->price), $text);
            // 依頼メールの送信
            $data = [
                "text"  => $text,
            ];
            Mail::send('emails.mailtext', $data, function($message){
                $message->to($this->_toInstructor)
                ->bcc($this->_toReon)
                ->subject($this->_mailTitle);
            });

            // 請求テーブル登録
            $Claim = new Claim;
            $Claim->claim_date  = date('Y-m-d');
            $Claim->user_type = 2;
            $Claim->user_id   = $id;
            $Claim->title     = $request->item_name;
            $Claim->price     = $request->price;
            $Claim->status    = 0;
            $Claim->save();


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