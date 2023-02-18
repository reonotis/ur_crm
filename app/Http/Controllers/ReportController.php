<?php

namespace App\Http\Controllers;

use App\Consts\ErrorCode;
use App\Consts\SessionConst;
use App\Exceptions\ExclusionException;
use App\Models\Customer;
use App\Models\UserShopAuthorization;
use App\Models\VisitHistory;
use App\Services\CheckData;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportController extends UserAppController
{
    public $errMsg = [];

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $shopId = session()->get(SessionConst::SELECTED_SHOP)->id;

        // 来店者情報を取得
        $visitHistories = VisitHistory::getTodayVisitHistory($shopId)->get();

        // 本日来店時に登録された顧客を取得
        $todayCustomers = Customer::getTodayCustomers($shopId)->get();

        // 基本レポートを作成
        $basicReport = $this->_makeBasicReport(count($visitHistories), $shopId);

        return view('report.index', compact('todayCustomers', 'visitHistories', 'basicReport'));
    }

    /**
     * @return View
     * @throws ExclusionException
     */
    public function setStylist(Customer $customer): View
    {
        // 閲覧して良いかチェック
        $shopId = session()->get(SessionConst::SELECTED_SHOP)->id;
        if ($customer->shop_id <> $shopId){
            $this->goToExclusionErrorPage(ErrorCode::CL_030011, [
                $customer->shop_id,
                $customer->id,
                $this->loginUser->id,
            ]);
        }
        if($customer->staff_id){
            $this->goToExclusionErrorPage(ErrorCode::CL_030012, [
                $customer->shop_id,
                $customer->id,
                $this->loginUser->id,
            ]);
        }

        // 既に本日の来店履歴が登録されているか確認する
        $his = VisitHistory::getTodayVisitHistoryByCustomerId($customer->id)->get();
        $historyFlg = count($his)? true: false;

        // 選択可能なスタイリストを取得
        $users = UserShopAuthorization::getSelectableUsers($shopId)->get();

        return view('report.setStylist',compact('customer', 'users', 'historyFlg' ));
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Customer $customer
     * @return RedirectResponse
     * @throws ExclusionException
     */
    public function settingStylist(Request $request, Customer $customer): RedirectResponse
    {
        $request->session()->regenerateToken(); // 二重クリック防止
        // 更新して良いかチェック
        $shopId = session()->get(SessionConst::SELECTED_SHOP)->id;
        if ($customer->shop_id <> $shopId){
            $this->goToExclusionErrorPage(ErrorCode::CL_030013, [
                $customer->shop_id,
                $customer->id,
                $this->loginUser->id,
            ]);
        }
        if($customer->staff_id){
            $this->goToExclusionErrorPage(ErrorCode::CL_030014, [
                $customer->shop_id,
                $customer->id,
                $this->loginUser->id,
            ]);
        }

        // 既に本日の来店履歴が登録されているのに、新しく登録しようとした場合のエラー
        if ($request->vis_history) {
            $todayHistory = VisitHistory::getTodayVisitHistoryByCustomerId($customer->id)->get();
            if (count($todayHistory)) {
                $this->goToExclusionErrorPage(ErrorCode::CL_030015, [
                    $customer->shop_id,
                    $customer->id,
                ]);
            }
        }

        try {
            DB::beginTransaction();

            // 担当スタイリストを設定する
            $customer->staff_id = $request->staff_id;
            $customer->save();

            // 来店履歴を登録する
            if ($request->vis_history) {
                VisitHistory::insert([[
                    'vis_date' => Carbon::now()->format('Y-m-d'),
                    'vis_time' => Carbon::now()->format('H:i'),
                    'customer_id' => $customer->id,
                    'shop_id' => $shopId,
                    'user_id' => $request->staff_id,
                ]]);
            }

            DB::commit();
            return redirect()->route('report.index')->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['スタイリストを設定しました']);
        } catch (Exception $e) {
            DB::rollback();
            Log::error( ' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['スタイリストの設定に失敗しました'])->withInput();
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

    /**
     * 基本レポート用の配列を生成して返却
     * @param int $todayCount
     * @param int $shopId
     * @return array
     */
    private function _makeBasicReport(int $todayCount, int $shopId): array
    {
        $basicReport = [];
        $basicReport['todayCount'] = $todayCount;
        $basicReport['opeMembers'] = VisitHistory::getTodayOpeMemberByShopId($shopId)->get();
        return $basicReport;
    }

}
