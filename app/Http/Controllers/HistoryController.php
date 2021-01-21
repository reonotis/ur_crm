<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        if(isset($_GET['month'])) {
            $year = substr($_GET['month'], 0, 4);
            $month = substr($_GET['month'], 5, 2);
        }else{
            $year = date('Y');
            $month = date('m');
        }

        $start = '2010-11-01';
        $end = '2020-11-30';
        $auths = Auth::user();
        $auths_id = $auths->id;

        // 手段別のテーブルを作成
        $MeansQuery = DB::table('contacts')
                        -> where('staff','=',$auths_id)
                        // -> whereBetween('history_datetime', [$start , $end])
                        -> whereYear('history_datetime', $year)
                        -> whereMonth('history_datetime', $month)
                        -> select(DB::raw("DATE_FORMAT(history_datetime, '%Y-%m-%d') as date"), 'means_id', DB::raw('count(means_id) as meanscount'))
                        -> groupBy(DB::raw('means_id'), DB::raw("DATE_FORMAT(history_datetime, '%Y-%m-%d')"));
        $Means = $MeansQuery -> get();

        // 手段別のテーブルを作成
        $ResultQuery = DB::table('contacts')
                    -> where('staff','=',$auths_id)
                    // -> whereBetween('history_datetime', [$start , $end])
                    -> whereYear('history_datetime', $year)
                    -> whereMonth('history_datetime', $month)
                    -> select(DB::raw("DATE_FORMAT(history_datetime, '%Y-%m-%d') as date"), 'result_id', DB::raw('count(result_id) as resultcount'))
                    -> groupBy(DB::raw('result_id'), DB::raw("DATE_FORMAT(history_datetime, '%Y-%m-%d')"))
                    -> orderBy('date', 'asc');
        $Result = $ResultQuery -> get();
// dd($Result);




        $MailSoushin='1';  // 架電のIDを渡す
        $MailJushin='2';  // 架電のIDを渡す
        $KADEN='3';  // 架電のIDを渡す
        $JUDEN='4';  // 受電のIDを渡す
        $SYOHOU='5';  // 初訪のIDを渡す
        $HOUMON='6';  // 訪問のIDを渡す
        $RAISHA='7';  // 来社のIDを渡す
        $APO='3';  // アポ獲得のIDを渡す
        $JYUTYUU='4';  // アポ獲得のIDを渡す

        // 日付毎のテーブルを作成
        $ContactQuery = DB::table('contacts')
                        -> where('staff','=',$auths_id)
                        -> whereYear('history_datetime', $year)
                        -> whereMonth('history_datetime', $month)
                        ->leftJoinSub($MeansQuery, 'MailSoushin',  function ($join) use ($MailSoushin){
                            $join->on(DB::raw("DATE_FORMAT(contacts.history_datetime, '%Y-%m-%d')"), '=', 'MailSoushin.date')
                            ->where('MailSoushin.means_id', $MailSoushin);
                        })
                        ->leftJoinSub($MeansQuery, 'MailJushin',  function ($join) use ($MailJushin){
                            $join->on(DB::raw("DATE_FORMAT(contacts.history_datetime, '%Y-%m-%d')"), '=', 'MailJushin.date')
                            ->where('MailJushin.means_id', $MailJushin);
                        })
                        ->leftJoinSub($MeansQuery, 'KADEN',  function ($join) use ($KADEN){
                            $join->on(DB::raw("DATE_FORMAT(contacts.history_datetime, '%Y-%m-%d')"), '=', 'KADEN.date')
                            ->where('KADEN.means_id', $KADEN);
                        })
                        ->leftJoinSub($MeansQuery, 'JUDEN',  function ($join) use ($JUDEN){
                            $join->on(DB::raw("DATE_FORMAT(contacts.history_datetime, '%Y-%m-%d')"), '=', 'JUDEN.date')
                            ->where('JUDEN.means_id', $JUDEN);
                        })
                        ->leftJoinSub($MeansQuery, 'SYOHOU',  function ($join) use ($SYOHOU){
                            $join->on(DB::raw("DATE_FORMAT(contacts.history_datetime, '%Y-%m-%d')"), '=', 'SYOHOU.date')
                            ->where('SYOHOU.means_id', $SYOHOU);
                        })
                        ->leftJoinSub($MeansQuery, 'HOUMON',  function ($join) use ($HOUMON){
                            $join->on(DB::raw("DATE_FORMAT(contacts.history_datetime, '%Y-%m-%d')"), '=', 'HOUMON.date')
                            ->where('HOUMON.means_id', $HOUMON);
                        })
                        ->leftJoinSub($MeansQuery, 'RAISHA',  function ($join) use ($RAISHA){
                            $join->on(DB::raw("DATE_FORMAT(contacts.history_datetime, '%Y-%m-%d')"), '=', 'RAISHA.date')
                            ->where('RAISHA.means_id', $RAISHA);
                        })
                        ->leftJoinSub($ResultQuery, 'APO',  function ($join) use ($APO){
                            $join->on(DB::raw("DATE_FORMAT(contacts.history_datetime, '%Y-%m-%d')"), '=', 'APO.date')
                            ->where('APO.result_id', $APO);
                        })
                        ->leftJoinSub($ResultQuery, 'JYUTYUU',  function ($join) use ($JYUTYUU){
                            $join->on(DB::raw("DATE_FORMAT(contacts.history_datetime, '%Y-%m-%d')"), '=', 'JYUTYUU.date')
                            ->where('JYUTYUU.result_id', $JYUTYUU);
                        })
                        -> select(
                                DB::raw("DATE_FORMAT(history_datetime, '%Y-%m-%d') as date"),
                                DB::raw('count(history_datetime) as count'),
                                'MailSoushin.meanscount as SoushinCount',
                                'MailJushin.meanscount as JushinCount',
                                'KADEN.meanscount as kadenCount',
                                'JUDEN.meanscount as judenCount',
                                'SYOHOU.meanscount as shohouCount',
                                'HOUMON.meanscount as houmonCount',
                                'RAISHA.meanscount as raishaCount',
                                'APO.resultcount as apoCount',
                                'JYUTYUU.resultcount as jyutyuuCount'
                            )
                        -> groupBy(DB::raw("DATE_FORMAT(history_datetime, '%Y-%m-%d')"));
        $Contacts = $ContactQuery -> get();
        $NENGETSU = $year."-".$month;

        // dd($Means, $Contacts );
        return view('history.index', compact('Contacts','NENGETSU'));
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
