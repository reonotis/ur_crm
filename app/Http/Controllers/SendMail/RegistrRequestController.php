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
    private $_toInfo ;
    private $_toReon ;

    public function __construct(){
        $this->middleware(function ($request, $next) {
            $this->_user = \Auth::user();
            $this->_auth_id = $this->_user->id;
            $this->_auth_authority_id = $this->_user->authority_id;
            if($this->_auth_authority_id >= 8){
                session()->flash('msg_danger', '権限がありません');
                Auth::logout();
                return redirect()->intended('/');
            }
            $this->_toInfo = config('mail.toInfo');
            $this->_toReon = config('mail.toReon');
            return $next($request);
        });
    }

    //
    public function instructorRegistrRequest($id){
        $oneMonth = config('paralymbics.annualFee.oneMonth');
        $certificationFee = config('paralymbics.certificationFee');

        $customer = CustomerCourseMapping::select('customer_course_mapping.*', 'customers.name')
        ->join('customers','customers.id','customer_course_mapping.customer_id')
        ->find($id);
        // 翌月を求める
        // echo date("Y-m", strtotime( date("Y-m-d") . " + months"));
        $KOTOSHIno3 = date("Y-03-01");
        $KOTOSHIno3 = date('Y-m-d', strtotime('last day of ' . $KOTOSHIno3));

        $claims['licenseStartDay'] = date("Y-m-01",strtotime(date("Y-m-d") . "+1 month"));
        // $YOKUGETU = "2021-02-01";  // テスト用

        // 今年中に年度末がくる場合
        if($KOTOSHIno3 > $claims['licenseStartDay'] ){
            $claims['licenseFinishDay'] = $KONNENDOMATU ;  //
            $claims['months'] = 3 - (date('m',strtotime($claims['licenseStartDay'])) - 1);
        }else{  // 年度末が来年になる場合
            $KONNENDOMATU = date('Y-m-d' ,strtotime('+1 year ' . $KOTOSHIno3));
            $claims['licenseFinishDay'] = date('Y-m-d', strtotime('last day of ' . $KONNENDOMATU));
            $claims['months'] = 15 - (date('m',strtotime($claims['licenseStartDay'])) - 1);
        }

        $claims['licenseFee'] = $claims['months'] * $oneMonth ;  //
        $claims['certificationFee'] = $certificationFee ; // 認定料金

        return view('email_forms.registrRequest', ['customer'=>$customer, 'claims'=>$claims] );
    }

    //
    public function sendMailRegistrRequest(Request $request, $id){
        try {

            // 依頼メールの送信
            $data = [
                "text"  => $request->text,
            ];
            Mail::send('emails.mailText', $data, function($message){
                $message->to($this->_toInfo)
                ->bcc($this->_toReon)
                ->subject('インストラクター規約同意依頼メール');
            });

            // DB更新
            $CCM = CustomerCourseMapping::find($id);
            $CCM->status = 6;
            $CCM->save();

            // メール送信履歴登録
            DB::table('history_send_emails')->insert([[
                'customer_id'=>$CCM->customer_id,
                'user_id'=>$this->_auth_id,
                'title'=>$request->title,
                'text'=>$request->text
            ]]);

            session()->flash('msg_success', 'メールを送信しました。');
            return redirect()->action('AdminController@customer_complete_course');
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }
}