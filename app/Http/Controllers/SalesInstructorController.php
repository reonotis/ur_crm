<?php

namespace App\Http\Controllers;

use App\Models\CustomerCourseMapping;
use App\Models\Claim;
use App\Models\InstructorCourseSchedule;
use App\Services\CheckClaims;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class SalesInstructorController extends Controller
{
    private $_user;                 //Auth::user()
    private $_auth_id ;             //Auth::user()->id;
    private $_auth_authority_id ;   //権限

    public function __construct(){
        $this->middleware(function ($request, $next) {
            $this->_user = Auth::user();
            $this->_auth_id = $this->_user->id;
            $this->_auth_authority_id = $this->_user->authority_id;
            if($this->_auth_authority_id >= 8){
                session()->flash('msg_danger', '権限がありません');
                Auth::logout();
                return redirect()->intended('/');
            }
            return $next($request);
        });
    }


    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sales.index');
    }

    public function list()
    {
        // 過去12か月分のデータを作成
        $arryNo = 0;
        for($i = -1; $i >= -12; $i--){
            $months[$arryNo]['month'] = date("Y-m",strtotime($i ." month"));
            $months[$arryNo]['displayName'] = date("Y年 m月分",strtotime($i ." month"));
            $arryNo ++ ;
        }
        return view('sales.index', compact('months') );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($month)
    {
        try{
            // 正しくYYYY-MMが渡されているかチェック
            if(strlen($month) <> 7)throw new \Exception("不正な画面遷移を検知しました");
            if(! $monthDate = Carbon::createFromFormat('Y-m', $month))throw new \Exception("日付の値が不正です");
            $date['month']= date('Y年m月', strtotime($monthDate));

            //今月以降のデータを表示しようとしていないかチェック
            $targetMonth = date("Y-m-01", strtotime($month));
            $thisMonth = date('Y-m-01');
            // if($targetMonth >= $thisMonth ) throw new \Exception("確認できるデータは先月分までです");

            $sales = Claim::select('claims.*', 'customers.name')
            ->join('customer_course_mapping AS CCM','CCM.claim_id','claims.id')
            ->join('customers','customers.id','CCM.customer_id')
            ->where('claims.user_type', 1)
            ->where('claims.status', 5)
            ->where('claims.claim_date', 'like', $month.'%')
            ->where('CCM.instructor_id',$this->_auth_id)
            ->get();
            $date['sales'] = $sales;

            // 合計金額を取得
            $date['gross_amount'] = 0;
            foreach ($sales as $sale) {
                $date['gross_amount'] += $sale->price;
            }


            return view('sales.show', compact('date') );
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        // }finally {
        }
    }
}
