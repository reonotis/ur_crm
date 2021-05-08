<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Claim;
use App\Models\ClaimsTransactions;
use App\Models\ClaimsDetail;
use App\Models\ClaimsDetailsTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClaimController extends Controller
{
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

            $claimsDetail = ClaimsDetail::where('claim_id', $id)->get();
            return view('user.claimShow',compact('claim', 'claimsDetail'));
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




}
