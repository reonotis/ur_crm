<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\VisitHistory;
use App\User;
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

        $visit_histories = VisitHistory::select('visit_histories.*', 'customers.f_name', 'customers.l_name' , 'users.name', 'menus.menu_name' )
        ->where('visit_histories.shop_id', $this->_user->shop_id)
        ->where('vis_date', date('Y-m-d'))
        ->where('visit_histories.delete_flag', 0)
        ->join('customers', 'customers.id', '=', 'visit_histories.customer_id' )
        ->leftJoin('users', 'users.id', '=', 'visit_histories.staff_id' )
        ->leftJoin('menus', 'menus.id', '=', 'visit_histories.menu_id' )
        ->get();

        $customers = Customer::select('customers.*', 'users.name as user_name', 'visit_histories.id as visit_history_id')
        ->whereDate('customers.created_at', date('Y-m-d'))
        ->where('customers.register_flow', 1)
        ->where('customers.delete_flag', 0)
        ->where('customers.shop_id', $this->_user->shop_id)
        // ->leftJoin('visit_histories', 'visit_histories.customer_id', '=', 'customers.id')
        // 来店履歴を作成していたら削除できないという判定をするためにJOIN

        ->leftJoin('visit_histories', function ($join) {
            $join->on('customers.id', '=', 'visit_histories.customer_id')
                ->where('visit_histories.delete_flag', '0');
        })

        ->leftJoin('users', 'users.id', '=', 'customers.staff_id')
        ->get();
        $customers = CheckData::set_sex_names($customers);


        return view('report.index',compact('visit_histories', 'customers'));
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
            $users = User::where('shop_id', $customer->shop_id)
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

            $customer = Customer::find($id);
            $customer->staff_id = $request->staff_id;
            $customer->save();

            // 来店履歴を登録する
            if( $request->stylistAndVisitHistory){
                VisitHistory::insert([[
                    'vis_date'    => date('Y-m-d'),
                    'vis_time'    => date('H:i'),
                    'customer_id' => $id,
                    'shop_id'     => $customer->shop_id,
                    'staff_id'    => $request->staff_id,
                    ]
                ]);
            }

            // throw new \Exception("強制終了");
            DB::commit();
            session()->flash('msg_success', 'スタイリストを設定しました。');
            return redirect()->action('ReportController@index');
            return view('report.set_stylist',compact('customer', 'users' ));
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
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
