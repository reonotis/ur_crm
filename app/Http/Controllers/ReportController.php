<?php

namespace App\Http\Controllers;

use App\Consts\{ErrorCode, SessionConst};
use App\Exceptions\ExclusionException;
use App\Models\{Customer, UserShopAuthorization};
use App\Services\ReserveInfoService;
use App\Services\ShopBusinessHourService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportController extends UserAppController
{
    /**
     * @var ReserveInfoService $reserveInfoService
     */
    public $reserveInfoService;

    /** @var ShopBusinessHourService $shopBusinessHourService */
    public $shopBusinessHourService;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
        $this->reserveInfoService = new ReserveInfoService();
        $this->shopBusinessHourService = app(ShopBusinessHourService::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     * @throws \ReflectionException
     */
    public function index(): View
    {
        $shopId = session()->get(SessionConst::SELECTED_SHOP)->id;
        $shopAuthorizationFlg = $this->userShopAuthorization;

        // 営業時間を取得
        $businessTime = $this->shopBusinessHourService->getBusinessTimeByDate($this->shopId, new Carbon());

        // 来店者情報を取得
        $visitHistories = $this->reserveInfoService->getTodayVisitHistory($shopId);

        // 本日来店時に登録された顧客を取得
        $todayCustomers = Customer::getTodayCustomers($shopId)->get();

        // 基本レポートを作成
        $basicReport = $this->makeBasicReport(count($visitHistories), $shopId);

        return view('report.index', compact(
            'businessTime',
            'todayCustomers',
            'visitHistories',
            'basicReport',
            'shopAuthorizationFlg'
        ));
    }

    /**
     * @param Customer $customer
     * @return View
     * @throws ExclusionException
     */
    public function setStylist(Customer $customer): View
    {
        // 閲覧して良いかチェック
        $shopId = session()->get(SessionConst::SELECTED_SHOP)->id;
        if ($customer->shop_id <> $shopId) {
            $this->goToExclusionErrorPage(ErrorCode::CL_030011, [
                $customer->shop_id,
                $customer->id,
                $this->loginUser->id,
            ]);
        }
        if ($customer->staff_id) {
            $this->goToExclusionErrorPage(ErrorCode::CL_030012, [
                $customer->shop_id,
                $customer->id,
                $this->loginUser->id,
            ]);
        }

        // 既に本日の来店履歴が登録されているか確認する
        $todayHistory = $this->reserveInfoService->getTodayVisitHistoryByCustomerId($customer->id);
        $historyFlg = (bool)$todayHistory;  // レコードが取得できていたら true

        // 選択可能なスタイリストを取得
        $users = UserShopAuthorization::getSelectableUsers($shopId)->get();

        return view('report.setStylist', compact('customer', 'users', 'historyFlg'));
    }

    /**
     * @param Request $request
     * @param Customer $customer
     * @return RedirectResponse
     * @throws ExclusionException
     */
    public function settingStylist(Request $request, Customer $customer): RedirectResponse
    {
        $request->session()->regenerateToken(); // 二重クリック防止
        $shopId = session()->get(SessionConst::SELECTED_SHOP)->id;

        // 更新して良いかチェックしていく
        // 他の店舗の顧客の場合はエラー
        if ($customer->shop_id <> $shopId) {
            $this->goToExclusionErrorPage(ErrorCode::CL_030013, [
                $customer->shop_id,
                $customer->id,
                $this->loginUser->id,
            ]);
        }

        // 既にスタイリストが紐づいている場合はエラー
        if ($customer->staff_id) {
            $this->goToExclusionErrorPage(ErrorCode::CL_030014, [
                $customer->shop_id,
                $customer->id,
                $this->loginUser->id,
            ]);
        }

        // 既に本日の来店履歴が登録されているのに、新しく登録しようとした場合のエラー
        if ($request->register_reserve_info) {
            $todayHistory = $this->reserveInfoService->getTodayVisitHistoryByCustomerId($customer->id);
            if ($todayHistory) {
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

            // 来店履歴を登録する場合の処理
            if ($request->register_reserve_info) {
                $condition = [
                    'customer_id' => $customer->id,
                    'shop_id' => $shopId,
                    'user_id' => $request->staff_id,
                ];
                $this->reserveInfoService->createRecord($condition);
            }

            DB::commit();
            return redirect()->route('report.index')->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['スタイリストを設定しました']);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['スタイリストの設定に失敗しました'])->withInput();
        }
    }

    /**
     * 基本レポート用の配列を生成して返却
     * @param int $todayCount
     * @param int $shopId
     * @return array
     */
    private function makeBasicReport(int $todayCount, int $shopId): array
    {
        $basicReport = [];
        $basicReport['todayCount'] = $todayCount;
        $basicReport['opeMembers'] = $this->reserveInfoService->getTodayOpeMemberByShopId($shopId);
        return $basicReport;
    }

}
