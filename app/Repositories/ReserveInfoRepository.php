<?php

namespace App\Repositories;

use App\Consts\DatabaseConst;
use Illuminate\Support\Facades\DB;
use App\Models\{ReserveInfo};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ReserveInfoRepository implements ReserveInfoRepositoryInterface
{
    /**
     * 本日の予約を取得
     * @param int $shop_id
     * @return mixed
     * @see ReserveInfoRepositoryInterface::getTodayVisitHistory
     */
    public function getTodayVisitHistory(int $shop_id): Collection
    {
        $date = Carbon::now()->format('Y-m-d');
        $select = [
            'reserve_info.*',
            'customers.id AS customer_id',
            'customers.customer_no',
            'customers.f_name',
            'customers.l_name',
            'customers.sex',
            'users.name',
            'menus.menu_name',
            'visit_types.type_name',
        ];
        return ReserveInfo::select($select)
            ->where('reserve_info.shop_id', $shop_id)
            ->where('vis_date', $date)
            ->whereNotIn('status', [ReserveInfo::STATUS['CANCEL']])
            ->join('customers', function ($join) {
                $join->on('reserve_info.customer_id', '=', 'customers.id')
                    ->whereNull('customers.deleted_at');
            })
            ->leftJoin('users', 'users.id', '=', 'reserve_info.user_id')
            ->leftJoin('menus', 'menus.id', '=', 'reserve_info.menu_id')
            ->leftJoin('visit_types', 'visit_types.id', '=', 'reserve_info.visit_type_id')
            ->get();
    }

    /**
     * @param int $shop_id
     * @return mixed
     * @see ReserveInfoRepositoryInterface::getTodayOpeMemberByShopId
     */
    public function getTodayOpeMemberByShopId(int $shop_id): Collection
    {
        $date = Carbon::now()->format('Y-m-d');
        return ReserveInfo::select(DB::raw('
                users.name,
                COUNT(user_id) AS count
            '))
            ->join('customers', function ($join) {
                $join->on('reserve_info.customer_id', '=', 'customers.id')
                    ->whereNull('customers.deleted_at');
            })
            ->leftJoin('users', 'users.id', 'reserve_info.user_id')
            ->where('reserve_info.shop_id', $shop_id)
            ->where('reserve_info.vis_date', $date)
            ->whereNotIn('reserve_info.status', [ReserveInfo::STATUS['CANCEL']])
            ->groupBy('reserve_info.user_id')
            ->get();
    }

    /**
     * 対象顧客の本日の来店者情報を取得
     * @param int $customerId
     * @return ReserveInfo|null
     * @see ReserveInfoRepositoryInterface::getTodayVisitHistoryByCustomerId
     */
    public function getTodayVisitHistoryByCustomerId(int $customerId): ?ReserveInfo
    {
        $date = Carbon::now()->format('Y-m-d');
        return ReserveInfo::select('*')
            ->where('customer_id', $customerId)
            ->where('vis_date', $date)
            ->first();
    }

    /**
     * @see ReserveInfoRepositoryInterface::createRecord
     */
    public function createRecord(array $condition): ReserveInfo
    {
        return ReserveInfo::create($condition);
    }

    /**
     * @see ReserveInfoRepositoryInterface::getByCustomerId
     */
    public function getByCustomerId(int $customerId): Collection
    {
        $select = [
            'reserve_info.*',
            'users.name',
            'menus.menu_name',
            'shops.shop_name',
        ];
        return ReserveInfo::select($select)
            ->where('customer_id', $customerId)
            ->leftJoin('users', 'users.id', 'reserve_info.user_id')
            ->leftJoin('menus', 'menus.id', 'reserve_info.menu_id')
            ->leftJoin('shops', 'shops.id', 'reserve_info.shop_id')
            ->orderBy('reserve_info.vis_date', 'desc')
            ->orderBy('reserve_info.vis_time', 'desc')
            ->get();
    }

    /**
     * 来店済みの受付を取得
     * @see ReserveInfoRepositoryInterface::getByTargetPeriod
     */
    public function getByTargetPeriod(Carbon $fromDate,Carbon $endDate, int $shopId): Collection
    {
        $select = [
            'reserve_info.*',
            'customers.f_name',
            'customers.l_name',
            'customers.sex',
            'users.name',
            'menus.menu_name',
            'shops.shop_name',
        ];
        return ReserveInfo::select($select)
            ->where('reserve_info.vis_date', '>=', $fromDate)
            ->where('reserve_info.vis_date', '<=', $endDate)
            ->where('reserve_info.shop_id', $shopId)
            ->where('reserve_info.status', ReserveInfo::STATUS['GUIDED'])
            ->join('customers', function ($join) {
                $join->on('reserve_info.customer_id', '=', 'customers.id')
                    ->whereNull('customers.deleted_at');
            })
            ->leftJoin('users', 'users.id', 'reserve_info.user_id')
            ->leftJoin('menus', 'menus.id', 'reserve_info.menu_id')
            ->leftJoin('shops', 'shops.id', 'reserve_info.shop_id')
            ->orderBy('reserve_info.vis_date', 'ASC')
            ->orderBy('reserve_info.vis_time', 'ASC')
            ->get();

    }

    /**
     * 該当日のキャンセル以外の受付を取得
     * @param int $shopId
     * @param Carbon $date
     * @return Collection
     * @see ReserveInfoRepositoryInterface::getByDayAndShopId
     */
    public function getByDayAndShopId(int $shopId, Carbon $date): Collection
    {
        $select = [
            'reserve_info.*',
            'customers.f_name',
            'customers.l_name',
            'users.name',
        ];

        return ReserveInfo::select($select)
            ->where('reserve_info.vis_date',$date)
            ->where('reserve_info.shop_id', $shopId)
            ->whereNotIn('reserve_info.status', [ReserveInfo::STATUS['CANCEL']])
            ->join('customers', function ($join) {
                $join->on('reserve_info.customer_id', '=', 'customers.id')
                    ->whereNull('customers.deleted_at');
            })
            ->leftJoin('users', 'users.id', 'reserve_info.user_id')
            ->leftJoin('menus', 'menus.id', 'reserve_info.menu_id')
            ->leftJoin('shops', 'shops.id', 'reserve_info.shop_id')
            ->orderBy('reserve_info.vis_date', 'ASC')
            ->orderBy('reserve_info.vis_time', 'ASC')
            ->get();
    }

}
