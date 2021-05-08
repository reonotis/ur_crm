<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Authority;
use App\Models\Claim;
use App\Models\Payment;
use App\Models\HistorySendEmailsInstructor;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\CheckUsers;


class UserController extends Controller
{
    public function index(){
        $auth = Auth::user();

        // ユーザーの権限がエージェント以下だったら
        if($auth->authority_id >= 7){
            return redirect()->back();    // 前の画面へ戻る
        }
        return view('user.index');
    }

    public function list(){
        $auth = Auth::user();

        // ユーザーの権限がエージェント以下だったら
        if($auth->authority_id >= 7){
            return redirect()->back();    // 前の画面へ戻る
        }
        $users = CheckUsers::getUser();
        return view('user.list', compact('users'));
    }

    public function searching(Request $request){
        $query = DB::table('users')
                    ->select('users.*', 'users_info.intr_No')
                    ->leftJoin('users_info', 'users_info.id', '=', 'users.id');

        // 顧客番号の条件を設定する
        $this->setQueryLike($query, $request->input('menberNumber'), 'users_info.intr_No');

        $users = $query -> paginate(20);

        $users = CheckUsers::checkAuthoritys($users);
        return view('user.list', compact('users'));
    }

    /**
     * 渡されたqueryにwhere句を追加する
     */
    public function setQueryLike($query, $data, $name){
        if($data !== null){
            $data_split = mb_convert_kana($data, 's');    //全角スペースを半角にする
            $data_split2 = preg_split('/[\s]+/', $data_split, -1, PREG_SPLIT_NO_EMPTY);    //半角スペースで区切る
            foreach($data_split2 as $value){
                $query -> where($name ,'like','%'.$value.'%');
            }
        }
    }

    public function create(){
        // 権限のリストを取得
        $query = DB::table('authoritys')
                    -> select('id', 'authority_name')
                    -> where('delete_flag','=', '0')
                    -> orderBy('id', 'DESC');
        $authoritys = $query -> get();
        return view('user.create', compact('authoritys'));
    }

    public function store(Request $request){
        $User = new User;
        $User -> name = $request->input('name');
        $User -> email = $request->input('email');
        // 12文字のランダムなパスワードを生成
        $password = base_convert(mt_rand(pow(36, 12 - 1), pow(36, 12) - 1), 10, 36);
        $User -> password = Hash::make($password);
        $User -> authority = $request->input('authority');
        $User -> enrollment = $request->input('enrollment');
        $User -> save();
        return redirect('user/index');
    }

    public function display($id){
        $query = DB::table('users')
                    ->select('users.*', 'UI.intr_No', 'UI.tel', 'UI.birthdayYear', 'UI.birthdayMonth', 'UI.birthdayDay', 'UI.zip21', 'UI.zip22', 'UI.pref21', 'UI.addr21', 'UI.strt21')
                    ->leftJoin('users_info AS UI', 'UI.id', 'users.id')
                    ->where('users.id','=',$id);
        $user = $query -> first();
        // dd($user);

        $claims = Claim::select(('claims.*'))
        ->where('user_type', '2')
        ->where('user_id', $id)
        ->get();
        $claims = $this->setBillingStatuses($claims);

        $HSEIs = HistorySendEmailsInstructor::select('history_send_emails_instructors.*', 'users.name')
        ->join('users', 'users.id', 'history_send_emails_instructors.user_id')
        ->where('instructor_id', $id)
        ->orderBy('send_time','desc')
        ->get();
        $HSEIs = $this->changeMailTime($HSEIs);

        return view('user.display', compact('user','claims','HSEIs'));
    }

    /**
     * 渡された請求情報を表示しやすい形式に変更する
     */
    public function setBillingStatuses($claims){
        foreach($claims as $claim){
            $claim = $this->setBillingStatus($claim);
        }
        return $claims;
    }

    /**
     * 渡された請求情報に日本語の説明を付与する
     */
    public function setBillingStatus($claim){

        if($claim->status == 0){
            $claim->billing_Status = "未請求";
            $claim->payment_complete_date = "未払い";
        }else if($claim->status == 1){
            $claim->billing_Status = "請求中";
            $claim->payment_complete_date = "未払い";
        }else if($claim->status == 3){
            $claim->billing_Status = "cancel";
            $claim->payment_complete_date = "未払い";
        }else if($claim->status == 5){
            $claim->billing_Status = "支払い済み";
            $claim->payment_complete_date = "計上日をセット";
        }

        if($claim->claim_date <> '0000-00-00'){
            $claim->claim_date = date('Y年 m月 d日' , strtotime($claim->claim_date));
        }else{
            $claim->claim_date = '-';
        }
        
        return $claim;
    }

    /**
     * メールの時間の表示形式を調整する
     */
    public function changeMailTime($HSEIs){
        foreach($HSEIs as $HSEI){
            // 本日だったら
            if($HSEI->send_time->format('Y-m-d') == date('Y-m-d')){
                $HSEI->sendtime = $HSEI->send_time->format('H:i');
            }elseif($HSEI->send_time->format('Y-m-d') == date('Y-m-d',strtotime('-1 day')) ){  // 昨日だったら
                $HSEI->sendtime = "昨日 " . $HSEI->send_time->format('H:i');
            }elseif($HSEI->send_time->format('Y-m-d') == date('Y-m-d',strtotime('-2 day')) ){  // おととい
                $HSEI->sendtime = "おととい " . $HSEI->send_time->format('H:i');
            }elseif($HSEI->send_time->format('Y-m-d') > date('Y-m-d',strtotime('-3 day')) ){   // 3日前
                $HSEI->sendtime = "3日前 " . $HSEI->send_time->format('H:i');
            }elseif($HSEI->send_time->format('Y') == date('Y') ){                              //今年だったら
                $HSEI->sendtime = $HSEI->send_time->format('m月d日');
            }elseif(1==1){  //それ以外
                $HSEI->sendtime = $HSEI->send_time->format('Y年m月d日');
            }
            // dd($HSEI->send_time);
        }

        return $HSEIs;
    }

    /**
     * 
     */
    public function claimDisplay($id){
        $claim = Claim::select('claims.*', 'users.name')
        ->join('users', 'users.id', 'claims.user_id')
        ->find($id);
        $claim = $this->setBillingStatus($claim);
        return view('user.claimDisplay', compact('claim'));
    }

    /**
     * 
     */
    public function claimComplete(Request $request, $id){
        try {
            DB::beginTransaction();
            $claim = Claim::find($id);
            $claim->complete_date = $request->date;
            $claim->status = 2;
            $claim->save();

            $Payment = new Payment;
            $Payment->claim_id      = $id ;
            $Payment->sold_type     = 1 ;
            $Payment->sold_id       = 0 ;
            $Payment->instructor_id = $claim->user_id;
            $Payment->amount        = $claim->price;
            $Payment->item_name     = $claim->title;
            $Payment->accounting_date = $request->date;
            $Payment ->save();

            DB::commit();
            session()->flash('msg_success', '入金済みに更新し、売り上げ情報に登録しました。');
            return redirect()->action('UserController@display', ['id' => $claim->user_id]);
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

}
