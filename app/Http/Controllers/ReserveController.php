<?php

namespace App\Http\Controllers;

use App\Services\ReserveInfoService;
use App\Services\ReserveService;
use App\Services\ShopBusinessHourService;
use App\Services\UserService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 *
 */
class ReserveController extends UserAppController
{
    /** @var ReserveService $reserveService */
    private $reserveService;

    /** @var ReserveInfoService $reserveInfoService */
    private $reserveInfoService;

    /** @var UserService $userService */
    private $userService;

    /** @var ShopBusinessHourService $shopBusinessHourService */
    private $shopBusinessHourService;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
        $this->reserveService = app(ReserveService::class);
        $this->reserveInfoService = app(ReserveInfoService::class);
        $this->userService = app(UserService::class);
        $this->shopBusinessHourService = app(ShopBusinessHourService::class);

    }

    /**
     * ajax で利用される該当日の受付表を返却する
     * @param Request $request
     * @return JsonResponse
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function getReserveTable(Request $request): JsonResponse
    {
        $date = $request->date;
        $dateDisplay = (bool)$request->date_display;

        // 予約日の確認
        $reserve_date = new Carbon($date);
        // 営業時間を取得
        $businessTime = $this->shopBusinessHourService->getBusinessTimeByDate($this->shopId, $reserve_date);

        // 営業時間を確認
        if ($businessTime['temporary']) {
            $open_time = new Carbon($businessTime['date']->format('Y-m-d') . ' ' . $businessTime['temporary']['business_open_time']);
            $lo_time = new Carbon($businessTime['date']->format('Y-m-d') . ' ' . $businessTime['temporary']['last_reception_time']);
            $close_time = new Carbon($businessTime['date']->format('Y-m-d') . ' ' . $businessTime['temporary']['business_close_time']);
        } else {
            $open_time = new Carbon($businessTime['date']->format('Y-m-d') . ' ' . $businessTime['business_hours']['business_open_time']);
            $lo_time = new Carbon($businessTime['date']->format('Y-m-d') . ' ' . $businessTime['business_hours']['last_reception_time']);
            $close_time = new Carbon($businessTime['date']->format('Y-m-d') . ' ' . $businessTime['business_hours']['business_close_time']);
        }

        // 営業時間の配列を作成
        $start_time = $open_time->copy()->subHour();
        $end_time = $close_time->copy()->addHour();
        $time_array = CarbonPeriod::create($start_time, $end_time)->minute()->toArray();

        // 現時点の受付一覧を取得
        $reserve_list = $this->reserveInfoService->getByDayAndShopId($this->shopId, $reserve_date)->toArray();

        // スタイリスト毎にまとめる
        $reserve_list = $this->convertReserve($reserve_list, []);

        return response()->json(['view' => view('components.reception_table')
            ->with([
                'dateDisplay' => $dateDisplay,
                'time_array' => $time_array,
                'businessTime' => $businessTime,
                'reserve_list' => $reserve_list,
                'open_time' => $open_time,
                'lo_time' => $lo_time,
                'close_time' => $close_time,
            ])->render()
        ]);
    }

    /**
     * @param array $reserve_list
     * @param array $new_reserve_info
     * @return array
     */
    public function convertReserve(array $reserve_list, array $new_reserve_info): array
    {
        // 元々の受付も追加予定の受付も無ければ終了
        if (empty($reserve_list) && empty($new_reserve_info)) {
            return [];
        }

        $reserve_list_collection = collect($reserve_list);
        // 追加予定の受付を結合
        if (!empty($new_reserve_info)) {
            $reserve_list_collection->push($new_reserve_info);
        }

        // 受付をユーザー毎のグループにする
        $reserve_group_users = $reserve_list_collection->groupBy('user_id');

        // 受付に紐づくユーザーを取得 // これだと未設定のものが取得されない
        $user_ids = $reserve_group_users->keys()->toArray();

        $users = $this->userService->getByIds($user_ids)->toArray();
        if (isset($new_reserve_info['name'])) { // 追加した予約のユーザーがまだ存在しない可能性があるので追加しておく
            $new_user = [
                'id' => 0,
                'name' => $new_reserve_info['name'],
            ];
            $users[] = $new_user;
        }

        // 受付画面表示しやすい配列に変換する
        return $this->reserveService->makeReserveByUser($users, $reserve_group_users);
    }
}
