<?php

namespace App\Services;

use App\Models\ReserveInfo;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class ReserveService
{
    /**
     * @param array $users
     * @param Collection $reserve_group_users
     * @return array
     */
    public function makeReserveByUser(array $users, Collection $reserve_group_users): array
    {
        $reserve_list = [];
        foreach ($users as $user) {
            $reserve_list[$user['id']]['user_name'] = $user['name'];
            $reserve_list[$user['id']]['lines'] = $this->makeReserveLine($reserve_group_users[$user['id']]);
        }
        return $reserve_list;
    }

    /**
     * @param Collection $staffReserve
     * @return array
     */
    private function makeReserveLine(Collection $staffReserve): array
    {
        // 開始時間で並び替えをしておく
        $staffReserve = $staffReserve->sortBy('vis_time');

        $lineList = [];
        foreach ($staffReserve as $reserve) {
            // 滞在時間と終了時間を計算する
            $this->setCalculationTreatmentTime($reserve);

            $reserveStoreFlg = false;
            // 最初の予約だけは $lineList がまだ出来てないので1本目に格納する
            if (empty($lineList)) {
                $lineList[][] = $reserve;
                continue;
            }

            // 2つ目以降の予約の格納処理
            foreach ($lineList as $lineNo => $line) {
                // 該当予約の開始時間が、最後の予約の終了時間よりも後なら
                if ($reserve['vis_time'] >= $line[array_key_last($line)]['vis_end_time']) {
                    // 該当の予約をこのラインに追加
                    $lineList[$lineNo][] = $reserve;
                    $reserveStoreFlg = true;
                    break;
                }
            }

            // 上記のループ処理で格納出来ていたら抜ける
            if ($reserveStoreFlg) {
                continue;
            }

            // 新しいラインを作成して格納する
            $lineList[][] = $reserve;
        }
        return $lineList;
    }

    /**
     * 予約枠を設定するセクションを作成
     * @param int $time_interval 何分おきか
     * @param int $min_minutes 何分から作成するか
     * @param int $max_hour 何時間分作成するか
     * @return array
     */
    public function makeReserveSection(int $time_interval, int $min_minutes, int $max_hour): array
    {
        $first_time = new Carbon();
        $first_time->setTime(0, 0);

        $last_time = $first_time->copy()->addHours($max_hour);

        $time_array = CarbonPeriod::create($first_time, $last_time)->minute($time_interval)->toArray();

        $section = [];
        foreach ($time_array as $timeInstance) {
            $diffMinutes = $first_time->diffInMinutes($timeInstance); // 差分
            // 最小値を超えるまで処理しない
            if ($diffMinutes < $min_minutes) {
                continue;
            }

            $section[] = [
                'label' => $timeInstance->format('G時間i分'),
                'value' => $diffMinutes,
            ];
        }

        return $section;
    }

    /**
     * @param $reserve
     * @return void
     */
    private function setCalculationTreatmentTime(&$reserve): void
    {
        $vis_time = new Carbon($reserve['vis_time']);

        // 終了時間がセットされていなければ、デフォルトの滞在時間を基に終了時間をセットする
        if (empty($reserve['vis_end_time'])) {
            $treatment_time = ReserveInfo::DEFAULT_TREATMENT_TIME;
            $reserve['treatment_time'] = $treatment_time;
            $reserve['vis_end_time'] = $vis_time->addMinutes($treatment_time)->format('H:i:s');
        } else {
            // 終了時間がセットされているので滞在時間を計算する
            $vis_end_time = new Carbon($reserve['vis_end_time']);
            $reserve['treatment_time'] =  $vis_time->diffInMinutes($vis_end_time); // 差分を求める
        }
    }
}
