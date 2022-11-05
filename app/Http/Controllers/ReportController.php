<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\VisitHistory;
use App\UserOld;
use App\Services\CheckData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    private $_user;                 //Auth::user()
    private $_auth_id ;             //Auth::user()->id;
    private $_auth_authority_id ;   //権限

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
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // レポートデータの取得
        list($reportData , $visTypeData) = VisitHistory::get_todayReport($this->_user->shop_id);

        // 来店者情報を取得
        $visit_histories = VisitHistory::get_todayVisitHistory($this->_user->shop_id);

        // 本日来店時に登録された顧客を取得
        $customers = Customer::get_todayRegisterCustomer($this->_user->shop_id);
        $customers = CheckData::set_sex_names($customers);

        return view('report.index',compact('visit_histories', 'customers', 'reportData', 'visTypeData'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function set_stylist($id)
    {
        try {
            $customer = Customer::find($id);
            if($customer->staff_id) throw new \Exception("このお客様には既にスタイリストが設定されています。");
            $users = UserOld::where('shop_id', $customer->shop_id)
            ->where('authority_id', '>=', 3 )->where('authority_id', '<=', 7 )->get();

            return view('report.set_stylist',compact('customer', 'users' ));
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function setting_stylist(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // 担当スタイリストを設定する
            $customer = Customer::find($id);
            if(!empty($customer->staff_id)){
                throw new \Exception("既にスタイリストが登録されています。");
            }
            $customer->staff_id = $request->staff_id;
            $customer->save();

            // 来店履歴を登録する
            if( $request->stylistAndVisitHistory){
                // 既に本日の来店履歴が登録されていないか確認する
                $todayRecord = VisitHistory::checkTodayHistory($id);
                if($todayRecord->isEmpty()){
                    VisitHistory::insert([[
                        'vis_date'    => date('Y-m-d'),
                        'vis_time'    => date('H:i'),
                        'customer_id' => $id,
                        'shop_id'     => $customer->shop_id,
                        'staff_id'    => $request->staff_id,
                        ]
                    ]);
                }
            }

            // throw new \Exception("強制終了");
            DB::commit();
            session()->flash('msg_success', 'スタイリストを設定しました。');
            return redirect()->action('ReportController@index');
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->action('ReportController@index');
        }
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
