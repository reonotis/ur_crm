<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\VisitHistory;
use Illuminate\Support\Facades\DB;


class OldReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 最後にどの表示方法をしていたかセッションに残っているか確認し
        // 対象のcontrollerにリダイレクトする
        if(session('oldReport_displayType') == 3){
            return redirect()->action('OldReportController@monthly');
        }else if(session('oldReport_displayType') == 2){
            return redirect()->action('OldReportController@weekly');
        }else if(session('oldReport_displayType') == 1){
            return redirect()->action('OldReportController@daily');
        }else{
            // セッションが残っていなければデフォルトで新別の表示をする
            return redirect()->action('OldReportController@daily');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function daily()
    {
        $LOCAL_ENVIRONMENT = env('LOCAL_ENVIRONMENT');

        // 対象日がセッションに残っているか確認
        if(session('oldReport_day_setData')){
            // デフォルトの対象日をセッションの値にする
            $setData = session('oldReport_day_setData');
        }else{
            // デフォルトの対象日を本日にする
            $setData = date('Y-m-d');
        }

        $shops = Shop::get_shopList();
        // ショップを選択していたセッションが残っているか確認
        if(session('oldReport_day_shopChoice')){
            // デフォルトのショップ選択値をセッションの値にする
            $defaultShopId = session('oldReport_day_shopChoice');
        }else{
            // デフォルトのショップ選択値をログインユーザーの所属店にする
            $defaultShopId = \Auth::user()->shop_id;
        }

        // 表示方法を選択していたセッションが残っているか確認
        if(session('oldReport_day_selectChoice')){
            // デフォルトの表示方法選択値をセッションの値にする
            $defaultSelectChoice = session('oldReport_day_selectChoice');
        }else{
            // デフォルトの表示方法選択値を設定する
            $defaultSelectChoice = 1;
        }

        return view('oldReport.daily',compact('setData', 'shops', 'defaultShopId', 'defaultSelectChoice', 'LOCAL_ENVIRONMENT' ));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function monthly()
    {
        $LOCAL_ENVIRONMENT = env('LOCAL_ENVIRONMENT');

        session(['oldReport_displayType' => 3]);   //月別表示

        // session(['oldReport_month_selectChoice' => $selectChoice]);   //月別表示時の表示方法

        if(session('oldReport_displayMonth')){
            $months = session('oldReport_displayMonth');
        }elseif(date('Y-m-d') >= date('Y-m-21')){
            $months = date('Y-m-21',strtotime("+1 month"));
        }else{
            $months = date('Y-m-21');
        }
        session(['oldReport_displayMonth' => $months]);

        $shops = Shop::get_shopList();
        // ショップを選択していたセッションが残っているか確認
        if(session('oldReport_month_shopChoice')){
            // デフォルトのショップ選択値をセッションの値にする
            $defaultShopId = session('oldReport_month_shopChoice');
        }else{
            // デフォルトのショップ選択値をログインユーザーの所属店にする
            $defaultShopId = \Auth::user()->shop_id;
        }

        // 表示方法を選択していたセッションが残っているか確認
        if(session('oldReport_month_selectChoice')){
            // デフォルトの表示方法選択値をセッションの値にする
            $defaultSelectChoice = session('oldReport_month_selectChoice');
        }else{
            // デフォルトの表示方法選択値を設定する
            $defaultSelectChoice = 2;
        }


        return view('oldReport.monthly',compact('shops', 'defaultShopId', 'defaultSelectChoice', 'months', 'LOCAL_ENVIRONMENT' ));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDayRecord()
    {
        try {
            $setDate = $_GET['setDate'];
            $shopChoice = $_GET['shopChoice'];
            $selectChoice = $_GET['selectChoice'];
            session(['oldReport_day_setData' => $setDate]);
            session(['oldReport_day_shopChoice' => $shopChoice]);   //月別表示時のショップ選択
            session(['oldReport_day_selectChoice' => $selectChoice]);   //月別表示時の表示方法

            if($selectChoice == 1){
                $visitHistory = VisitHistory::get_dayAndPayment($setDate, $shopChoice);
                $visitHistory = $this->change_formatTime($visitHistory);
                $return = $visitHistory;
            }elseif($selectChoice == 2){
                $return = "作成中";
            }elseif($selectChoice == 3){
                $visitHistory = VisitHistory::get_dayAndStylist($setDate, $shopChoice);
                $return = $visitHistory;
            }

            // throw new \Exception("強制終了");
            return response()->json($return);

        } catch (\Throwable $e) {
            $return = [
                'fail',
                response()->json($e->getMessage()),
            ];
            return response()->json($return);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMonthRecord()
    {
        try {
            $targetMonth = $_GET['targetMonth'];
            $shopChoice = $_GET['shopChoice'];
            $selectChoice = $_GET['selectChoice'];
            session(['oldReport_displayMonth' => $targetMonth]);
            session(['oldReport_month_shopChoice' => $shopChoice]);   //月別表示時のショップ選択
            session(['oldReport_month_selectChoice' => $selectChoice]);   //月別表示時の表示方法

            if($selectChoice == 1){
                $return = "作成中1";
            }elseif($selectChoice == 2){
                list($visitHistory, $fromMonth, $toMonth) = VisitHistory::get_monthAndMenu($targetMonth, $shopChoice);
                // $visitHistory = $this->change_formatTime($visitHistory);
                $return['$visitHistory'] = $visitHistory;
                $return['$fromMonth'] = $fromMonth;
                $return['$toMonth'] = $toMonth;
            }elseif($selectChoice == 3){
                list($visitHistory, $fromMonth, $toMonth) = VisitHistory::get_monthAndStylist($targetMonth, $shopChoice);
                $return['$visitHistory'] = $visitHistory;
                $return['$fromMonth'] = $fromMonth;
                $return['$toMonth'] = $toMonth;
            }

            // throw new \Exception("強制終了");
            return response()->json($return);

        } catch (\Throwable $e) {
            $return = [
                'fail',
                response()->json($e->getMessage()),
            ];
            return response()->json($return);
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $data
     * @return void
     */
    public function change_formatTime($data){
        foreach($data as $value){
            $value->vis_time = date('H:i' ,strtotime($value->vis_time));
        }
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
