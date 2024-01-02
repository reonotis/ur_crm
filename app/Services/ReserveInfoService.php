<?php

namespace App\Services;

use App\Models\{ReserveInfo};
use App\Repositories\ReserveInfoRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 *
 */
class ReserveInfoService
{
    /** @var ReserveInfoRepository $reserveInfoRepository */
    private $reserveInfoRepository;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->reserveInfoRepository = app(ReserveInfoRepository::class);
    }

    /**
     * 本日の来店者情報を取得
     * @param int $shopId
     * @return Collection
     */
    public function getTodayVisitHistory(int $shopId): Collection
    {
        return $this->reserveInfoRepository->getTodayVisitHistory($shopId);
    }

    /**
     * 該当日のキャンセル以外の受付を取得
     * @param int $shopId
     * @param Carbon $date
     * @return Collection
     */
    public function getByDayAndShopId(int $shopId, Carbon $date): Collection
    {
        return $this->reserveInfoRepository->getByDayAndShopId($shopId, $date);
    }

    /**
     * 本日の来店者をスタイリスト別にカウントする
     * @param int $shop_id
     * @return object
     */
    public function getTodayOpeMemberByShopId(int $shop_id)
    {
        return $this->reserveInfoRepository->getTodayOpeMemberByShopId($shop_id);
    }

    /**
     */
    public function getTodayVisitHistoryByCustomerId(int $shop_id): ?ReserveInfo
    {
        return $this->reserveInfoRepository->getTodayVisitHistoryByCustomerId($shop_id);
    }

    /**
     * reserve_infoテーブルへの登録処理を行う
     * @param array $condition
     * @return ReserveInfo
     */
    public function createRecord(array $condition): ReserveInfo
    {
        // 値が渡されなかった場合のdefault値を設定
        if (!isset($condition['vis_date'])) {
            $condition['vis_date'] = Carbon::now();
        }
        if (!isset($condition['vis_time'])) {
            $condition['vis_time'] = Carbon::now()->format('H:i');
        }
        if (!isset($condition['status'])) {
            $condition['status'] = ReserveInfo::STATUS['GUIDED'];
        }
        if (!isset($condition['reserve_type'])) {
            $condition['reserve_type'] = ReserveInfo::RESERVE_TYPE['CAME_SHOP'];
        }
        if (!isset($condition['vis_end_time'])) {
            $condition['vis_end_time'] = $this->createEndTimeFromSection($condition['vis_date'], $condition['vis_time']);
        }

        return $this->reserveInfoRepository->createRecord($condition);
    }

    /**
     * 対象顧客の本日の来店履歴を取得
     * @param int $customerId
     * @return Collection
     */
    public function getByCustomerId(int $customerId): Collection
    {
        return $this->reserveInfoRepository->getByCustomerId($customerId);

    }

    /**
     * 対象店舗の特定期間の来店履歴を取得する
     * @param Carbon $fromDate
     * @param Carbon $endDate
     * @param int $shopId
     * @return mixed
     */
    public function getByTargetPeriod(Carbon $fromDate, Carbon $endDate, int $shopId)
    {
        return $this->reserveInfoRepository->getByTargetPeriod($fromDate, $endDate, $shopId);
    }

    /**
     * 来店履歴のリストをスタイリスト毎の配列に変換する
     * @param object $list
     * @return array
     */
    public function getByTargetPeriodGroupByUser(Carbon $fromDate, Carbon $endDate, int $shopId): array
    {
        $list = $this->getByTargetPeriod($fromDate, $endDate, $shopId);
        if (empty($list)) {
            return [];
        }

        $data = [];
        foreach ($list as $row) {
            $data[$row['user_id']][] = $row;
        }
        return $data;
    }

    /**
     * 施術終了時間を求める
     * @param Carbon $vis_date
     * @param string $vis_time
     * @param int|null $section
     * @return Carbon
     */
    public function createEndTimeFromSection(Carbon $vis_date, string $vis_time, int $section = null): Carbon
    {
        $startTime = $vis_date->createFromFormat(
            'H:i',
            $vis_time
        );

        if (is_null($section)) {
            $section = ReserveInfo::DEFAULT_TREATMENT_TIME;
        }

        return $startTime->addMinutes($section);
    }

    /**
     * 施術開始時間と施術終了時間の差分からセクションの値を求める
     * @param ReserveInfo $reserveInfo
     * @param array $sections
     * @return int
     */
    public function getSectionValue(ReserveInfo $reserveInfo, array $sections): int
    {
        // 設定されていなければデフォルト値を返却
        if (is_null($reserveInfo->vis_end_time)) {
            return ReserveInfo::DEFAULT_TREATMENT_TIME;
        }

        // 開始時間と終了時間のインスタンスを作成
        $startTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $reserveInfo->vis_date->format('Y-m-d ') . $reserveInfo->vis_time
        );
        $endTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $reserveInfo->vis_date->format('Y-m-d ') . $reserveInfo->vis_end_time
        );

        // [分]単位の差分
        $diff = $startTime->diffInMinutes($endTime);

        // 差分以上の最小値を返却する
        foreach ($sections as $section) {
            if ($section['value'] >= $diff) {
                return $section['value'];
            }
        }

        return 0;
    }
}
