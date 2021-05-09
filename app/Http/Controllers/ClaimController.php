<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Claim;
use App\Models\ClaimsTransactions;
use App\Models\ClaimsDetail;
use App\Models\ClaimsDetailsTransactions;
use App\Models\Payment;
use App\Services\CheckClaims;
use Illuminate\Http\Request;
use Carbon\Carbon;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;

class ClaimController extends Controller
{

    private $_user;
    protected $_auth_id ;
    protected $_auth_authority_id ;
    public $_backslash = '\\';
    private $_toInfo ;
    private $_toReon ;
    private $_toIntr ;
    private $_subject ;

    public function __construct(){
        $this->middleware(function ($request, $next) {
            $this->_user = \Auth::user();
            $this->_auth_id = $this->_user->id;
            $this->_auth_authority_id = $this->_user->authority_id;
            if($this->_auth_authority_id >= 7){
                dd("権限がありません。");
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
    public function index($id){
        //
    }

    /**
     * Show the form for creating a new resource.
     * 既に請求トランテーブルにデータがあれば引き継いで請求情報を作成する画面を表示する。
     * 
     * @return \Illuminate\Http\Response
     */
    public function create($id){
        $user = User::find($id);
        $ClaimTrn = ClaimsTransactions::
        where('user_type', 2)
        ->where('user_id', $id)
        ->first();

        if($ClaimTrn){
            $CTrn_id = $ClaimTrn->id;
            $CDTrns = ClaimsDetailsTransactions::where('claim_trn_id', $CTrn_id)->orderBy('rank')->get();
        }else{
            $CDTrns = "" ;
        }

        return view('claim.create',compact('user', 'ClaimTrn', 'CDTrns'));
        //
    }

    public function updateOrInsert($id){
        try {
            DB::beginTransaction();
            DB::table('claims_transactions')->updateOrInsert(
                [
                    'user_type' => $_GET['user_type'],
                    'user_id' =>  $id,
                ],[
                    'title' => $_GET['claim_name'],
                    'limit_date' => $_GET['limit_date'],
                ]);
            DB::commit();
            return response()->json($id);
        } catch (\Throwable $e) {
            DB::rollback();
        }
    }

    public function addClaimDetail($id){
        DB::beginTransaction();
        try {
            $user_type = $_GET['user_type'];
            $CTrn = ClaimsTransactions::
            where('user_type', $user_type)
            ->where('user_id', $id)
            ->first();
            $CTrn_id = $CTrn->id;

            $CDTrn = new ClaimsDetailsTransactions;
            $CDTrn->claim_trn_id = $CTrn_id ;
            $CDTrn->save();
            $CDTrn->rank  = $CDTrn->id  ;
            $CDTrn->save();

            $CDTrns = ClaimsDetailsTransactions::where('claim_trn_id', $CTrn_id)->orderBy('rank')->get();

            DB::commit();
            return response()->json($CDTrns);
        } catch (\Throwable $e) {
            DB::rollback();
        }
    }

    public function deleteClaimDetail($id){
        DB::beginTransaction();
        try {
            ClaimsDetailsTransactions::where('id', $id)->delete();

            $user_type = $_GET['user_type'];
            $user_id = $_GET['user_id'];

            $CTrn = ClaimsTransactions::
            where('user_type', $user_type)
            ->where('user_id', $user_id)
            ->first();
            $CTrn_id = $CTrn->id;

            $CDTrns = ClaimsDetailsTransactions::where('claim_trn_id', $CTrn_id)->orderBy('rank')->get();

            DB::commit();
            return response()->json($CDTrns);
        } catch (\Throwable $e) {
            DB::rollback();
        }
    }

    public function updateOrInsert_claimDetail($id){
        DB::beginTransaction();
        try {
            $CDTrn = ClaimsDetailsTransactions::find($id);
            $CDTrn->item_name  = $_GET['item_name'];
            $CDTrn->unit_price = $_GET['unit_price'];
            $CDTrn->quantity   = $_GET['quantity'];
            $CDTrn->unit       = $_GET['unit'];
            $CDTrn->price      = $_GET['price'];
            $CDTrn->save();

            DB::commit();
            return response()->json(true);
        } catch (\Throwable $e) {
            DB::rollback();
        }
    }

    public function deleteTran($id){
        DB::beginTransaction();
        try {
            $ClaimTrn = ClaimsTransactions::where('user_type',2)->where('user_id', $id)->first();
            $ClaimTrn_id = $ClaimTrn->id;
            $ClaimTrn->delete();
            ClaimsDetailsTransactions::where('claim_trn_id', $ClaimTrn_id)->delete();

            DB::commit();
            session()->flash('msg_success', '請求情報をリセットしました');
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
        }
        return redirect()->back();    // 前の画面へ戻る
    }

    public function rankDuwn($id){
        DB::beginTransaction();
        try {
            $thisId = $id;
            $CDTrns = ClaimsDetailsTransactions::find($thisId);
            $thisRank = $CDTrns->rank;
            $next_CDTrn = ClaimsDetailsTransactions::
                where('claim_trn_id', $CDTrns->claim_trn_id )
                ->where('rank','>', $thisRank)->orderBy('rank')->first();

            if($next_CDTrn <> NULL) {
                $nextRank = $next_CDTrn->rank;
                $nextId = $next_CDTrn->id;
                ClaimsDetailsTransactions::where('id', $thisId)->update(['rank' => $nextRank]);
                ClaimsDetailsTransactions::where('id', $nextId)->update(['rank' => $thisRank]);

                // return response()->json( $next_CDTrn);
            }

            DB::commit();
            $CDTrns = ClaimsDetailsTransactions::where('claim_trn_id', $CDTrns->claim_trn_id)->orderBy('rank')->get();
            return response()->json( $CDTrns);
        } catch (\Throwable $e) {
            DB::rollback();
        }
    }

    public function confilmAddClaim($id){
        try {

            $user = User::find($id);
            $ClaimTrn = ClaimsTransactions::
                        where('user_type', 2)
                        ->where('user_id', $id)
                        ->first();

            $CTrn_id = $ClaimTrn->id;
            $CDTrns = ClaimsDetailsTransactions::where('claim_trn_id', $CTrn_id)->orderBy('rank')->get();

            foreach($CDTrns as $CDTrn){
                $grossAmount[] =$CDTrn->price;
            }
            $grossAmount = collect($grossAmount)->sum();
            return view('claim.comfilmAddClaim', compact('user', 'ClaimTrn', 'CDTrns', 'grossAmount'));
        } catch (\Throwable $e) {
            session()->flash('msg_danger', '不正なアクセスです。' );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    public function storeClaim($id){
        DB::beginTransaction();
        try {
            $user_id = $id;
            $user_type = 2;

            // トランザクションデータの呼び出し
            $ClaimTrn = ClaimsTransactions::
                        where('user_type', $user_type)
                        ->where('user_id', $id)
                        ->first();
            $CTrn_id = $ClaimTrn->id;
            $ClaimDetailTrans = ClaimsDetailsTransactions::where('claim_trn_id', $CTrn_id)->orderBy('rank')->get();

            // 請求データを本登録する
            $this->create_claims($ClaimTrn, $ClaimDetailTrans);

            // トランザクションの削除
            ClaimsDetailsTransactions::where('claim_trn_id', $CTrn_id)->delete();
            ClaimsTransactions::destroy($CTrn_id);

            DB::commit();
            session()->flash('msg_success', '請求データを作成しました。');
            return redirect()->action('UserController@display', ['id' => $user_id]);
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    public function create_claims($ClaimTrn, $ClaimDetailTrans){
        // 請求データを登録
        $Claim = new Claim();
        $Claim->user_type =  $ClaimTrn->user_type;
        $Claim->user_id =  $ClaimTrn->user_id;
        $Claim->title =  $ClaimTrn->title;
        $Claim->limit_date =  $ClaimTrn->limit_date;
        $Claim->save();
        $last_claim_id = $Claim->id;

        // 請求明細を登録
        foreach($ClaimDetailTrans as $ClaimDetailTran){
            ClaimsDetail::insert([
                'claim_id' =>  $last_claim_id,
                'item_name' =>  $ClaimDetailTran->item_name,
                'unit_price' =>  $ClaimDetailTran->unit_price,
                'quantity' =>  $ClaimDetailTran->quantity,
                'unit' =>  $ClaimDetailTran->unit,
                'price' =>  $ClaimDetailTran->price,
                'rank' =>  $ClaimDetailTran->rank,
            ]);
            $grossAmount[] =$ClaimDetailTran->price;
        }

        // 請求データに合計金額を登録
        $grossAmount = collect($grossAmount)->sum();

        $Claim->price =$grossAmount;
        $Claim->save();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        try {
            $user_type = Claim::select('user_type')->find($id)->user_type;

            if($user_type == 1){
                $claim = Claim::where('')->find($id);
            }else if($user_type == 2){
                $claim = Claim::select('claims.*', 'users.name')
                ->join('users', "users.id", "claims.user_id")
                ->find($id);
            }
            $claim = CheckClaims::setStatus($claim);

            $claimsDetails = ClaimsDetail::where('claim_id', $id)->get();
            return view('user.claimShow',compact('claim', 'claimsDetails'));
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        //
    }

    public function sendRequestClaimMail(Request $request, $id){
        try {
            DB::beginTransaction();

            // 請求データを取得
            list($claim, $claimsDetails) = $this->getClaimsData($id);
            $this->_toIntr =  $claim->email ;

            // バリデーションチェック
            if($claim->status == 5 ) throw new \Exception("入金済みデータのため請求できません");

            // 本文を修正
            $text = $request->text;
            $limit_date = $claim->limit_date->format('　Y年 m月 d日') ;
            $claim_data = "　振込金額 : " . number_format($claim->price) . "円\r\n";
            $claim_data .= "　内訳\r\n";
            foreach($claimsDetails as $data){
                $claim_data .= "　　・".$data->item_name." : ".number_format($data->unit_price) ." × ".$data->quantity.$data->unit."　　".number_format($data->price)."円\r\n" ;
            }
            $text = str_replace("###customerName###", $claim->name, $text);
            $text = str_replace("###limit_date###", $limit_date, $text);
            $text = str_replace("###claim_detail###", $claim_data, $text);// TODO 

            // 依頼メールの送信
            $this->_subject = $claim->title . "のご請求につきまして" ;
            $data = [
                "text"  => $text,
            ];
            Mail::send('emails.mailtext', $data, function($message){
                $message->to($this->_toIntr)
                ->cc($this->_toInfo)
                ->bcc($this->_toReon)
                ->subject( $this->_subject );
            });

            // メール送信履歴登録
            DB::table('history_send_emails_instructors')->insert([[
                'instructor_id' => $claim->user_id,
                'user_id'       => $this->_auth_id,
                'title'         => $this->_subject,
                'text'          => $text
            ]]);

            // 請求済みに更新
            $claim = Claim::find($id);
            $claim->status = 1;
            if($claim->claim_date == '0000-00-00' ){
                $claim->claim_date = date('Y-m-d') ;
            }
            $claim->save();

            session()->flash('msg_success', '請求依頼メールを送信しました。');
            DB::commit();
            return redirect()->action('UserController@display',['id'=>$claim->user_id]);
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * 渡された請求IDから請求情報と明細を返す
     */
    public function getClaimsData($id){
        $user_type = Claim::select('user_type')->find($id)->user_type;
        if($user_type == 1){
            $claim = Claim::where('')->find($id);
        }else if($user_type == 2){
            $claim = Claim::select('claims.*', 'users.name', 'users.email')
            ->join('users', "users.id", "claims.user_id")
            ->find($id);
        }
        $claimsDetails = ClaimsDetail::where('claim_id', $id)->get();
        return [$claim, $claimsDetails];
    }

    /**
     * 渡された請求IDからステータスを確認してキャンセル状態に更新する
     */
    public function cancelClaims($id){
        DB::beginTransaction();
        try {
            // 請求データを取得
            list($claim, $claimsDetails) = $this->getClaimsData($id);

            if($claim->status == 0 ) throw new \Exception("まだ請求を行っていないためキャンセルできません");
            if($claim->status == 5 ) throw new \Exception("支払い完了しているためキャンセルできません");

            $claim = Claim::find($id);
            $claim->status  = 3 ;
            $claim->save();

            DB::commit();
            session()->flash('msg_success', '請求データのステータスをキャンセルにしました。');
            return redirect()->action('UserController@display',['id'=>$claim->user_id]);
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * 渡された請求IDからステータスを確認して入金済み状態に更新する
     */
    public function completePaidClaim(Request $request, $id){
        DB::beginTransaction();
        try {
            // 請求データを取得
            if($request->complete_date == NULL)throw new \Exception("計上日を入力してください");

            if(! $complete_date = Carbon::createFromFormat('Y-m-d', $request->complete_date))throw new \Exception("日付の値が不正です");

            list($claim, $claimsDetails) = $this->getClaimsData($id);

            if($claim->status == 5 ) throw new \Exception("既に入金済みです");

            $claim = Claim::find($id);
            $claim->status  = 5 ;
            $claim->complete_date = $complete_date ;
            $claim->save();


            // TODO 売り上げ情報に登録
            $payment = new Payment;
            $payment->claim_id  = $claim->id ;
            $payment->sold_type  = 1 ;
            $payment->sold_id  = 0 ;
            $payment->customer_id  = NULL ;
            $payment->instructor_id = $claim->user_id ;
            $payment->amount  = $claim->price ;
            $payment->item_name  = $claim->title ;
            $payment->accounting_date  = $complete_date ;
            $payment->save();

            // throw new \Exception("売上情報に登録していません");
            DB::commit();
            session()->flash('msg_success', '請求データのステータスを入金済みにしました。');
            return redirect()->action('UserController@display',['id'=>$claim->user_id]);
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * 渡された請求IDからステータスを確認して削除する
     */
    public function deleteClaims($id){
        DB::beginTransaction();
        try {
            // 請求データを取得
            list($claim, $claimsDetails) = $this->getClaimsData($id);

            if($claim->status <> 0 ) throw new \Exception("一度請求したデータは削除できません");

            $claim = Claim::find($id);
            $claim->delete_flag = 1 ;
            $claim->save();

            DB::commit();
            session()->flash('msg_success', '請求データを削除しました');
            return redirect()->action('UserController@display',['id'=>$claim->user_id]);
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }



}
