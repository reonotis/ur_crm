<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Claim;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\HistorySendEmailsInstructor;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\CheckUsers;
use App\Services\CheckClaims;


class UserController extends Controller
{
    private $_user;
    protected $_auth_id ;
    protected $_auth_authority_id ;

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
            return $next($request);
        });
    }

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
        $query = DB::table('users')->select('users.*', 'users_info.intr_No')
                    ->leftJoin('users_info', 'users_info.id', '=', 'users.id');

        // 顧客番号の条件を設定する
        $this->setQueryLike($query, $request->input('menberNumber'), 'users_info.intr_No');

        // 名前の条件を設定する
        $this->setQueryLike($query, $request->input('name'), 'users.name');

        // ヨミの条件を設定する
        $this->setQueryLike($query, $request->input('read'), 'users.read');

        // 権限の条件を設定する
        if($request->input('authority')) $query -> where('users.authority_id','<=','7');

        // 電話番号の条件を設定する
        $this->setQueryLike($query, $request->input('tel'), 'users_info.tel');

        // メールアドレスの条件を設定する
        $this->setQueryLike($query, $request->input('email'), 'users.email');

        $users = $query -> paginate(30);
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
        ->where('delete_flag', '0')
        ->get();
        $claims = CheckClaims::setStatuses($claims);

        $customers = Customer::where('instructor', $id)->get();

        $HSEIs = HistorySendEmailsInstructor::select('history_send_emails_instructors.*', 'users.name')
        ->join('users', 'users.id', 'history_send_emails_instructors.user_id')
        ->where('instructor_id', $id)
        ->orderBy('send_time','desc')
        ->get();
        $HSEIs = $this->changeMailTime($HSEIs);

        return view('user.display', compact('user','claims','customers','HSEIs'));
    }

    /**
     * 編集画面を表示する
     *
     */
    public function edit($id){
        $query = DB::table('users')
                    ->select('users.*', 'UI.intr_No', 'UI.tel', 'UI.birthdayYear', 'UI.birthdayMonth', 'UI.birthdayDay', 'UI.zip21', 'UI.zip22', 'UI.pref21', 'UI.addr21', 'UI.strt21')
                    ->leftJoin('users_info AS UI', 'UI.id', 'users.id')
                    ->where('users.id','=',$id);
        $user = $query -> first();

        return view('user.edit', compact('user'));
    }

    /**
     * user の更新処理
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function update(Request $request, $id){
        try {
            DB::beginTransaction();

            $User = User::find($id);

            // 更新対象が自分だったら
            if( $id == $this->_auth_id ){
                if($request->authority_id > 5) throw new \Exception("自分の権限を下げることはできません");
            }
            $User->name = $request->name;
            $User->email = $request->email;
            $User->authority_id = $request->authority_id;
            $User->save();

            DB::commit();
            session()->flash('msg_success', '更新しました。');
            return redirect()->action('UserController@display', ['id' => $User->id]);
            // return redirect()->back();    // 前の画面へ戻る
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
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
