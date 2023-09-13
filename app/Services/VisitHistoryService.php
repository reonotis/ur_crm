<?php

namespace App\Services;

use App\Models\VisitHistory;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class VisitHistoryService
{

    /**
     * 対象店舗の特定期間の来店履歴を取得する
     * @param string $fromDate
     * @param string $endDate
     * @param int $shopId
     * @return mixed
     */
    public function getByTargetPeriod($fromDate, $endDate, $shopId)
    {
        return VisitHistory::getByTargetPeriod($fromDate, $endDate, $shopId)->get();
    }


    /**
     * 来店履歴のリストをスタイリスト毎の配列に変換する
     * @param object $list
     * @return array
     */
    public function convert(object $list): array
    {
        if (empty($list)) {
            return [];
        }

        $data = [];
        foreach ($list as $row) {
            $data[$row['user_id']][] = $row;
        }

        return $data;
    }


}
