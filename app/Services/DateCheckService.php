<?php

namespace App\Services;

use App\Consts\ErrorCode;
use App\Exceptions\ExclusionException;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class DateCheckService
{
    /**
     * YMDの文字列から日付のCarbonインスタンスを返却する
     * @param string $ymd 20200101
     * @return Carbon
     * @throws ExclusionException
     */
    public function makeDateInstanceByYMD(string $ymd = ''): Carbon
    {
        // 値が設定されていない場合は本日のInstanceを返却する
        if (empty($ymd)) {
            return new Carbon('today');
        }

        // 値が指定されているので正しいかチェックして返却する
        return $this->validateYMD($ymd);
    }

    /**
     * 文字列が正しい日付か判定し、Carbonインスタンスを返却する
     * @param string $ymd
     * @return Carbon
     * @throws ExclusionException
     */
    public function validateYMD(string $ymd): Carbon
    {
        $date = $this->convertStringToDate($ymd);
        if ($date && $date->format('Ymd') == $ymd) {
            return $date;
        }

        $errLogFmt = ErrorCode::ERROR_LOG_LIST[ErrorCode::INVALID_DATE];
        Log::error(vsprintf($errLogFmt, [$ymd]));
        throw new ExclusionException(ErrorCode::INVALID_DATE);
    }

    /**
     *
     * @param string $ymd
     * @return Carbon|false
     */
    public function convertStringToDate(string $ymd)
    {
        try {
            $date = Carbon::createFromFormat('Ymd', $ymd);
            $date->setTime(0, 0, 0);
        } catch (Exception $e) {
            return false;
        }
        return $date;
    }

    /**
     * @param Carbon $date
     * @return bool
     */
    public function checkLess(Carbon $date)
    {
        $second = Carbon::today();
        if ($date <= $second) {
            return true;
        }

        return false;
    }

    /**
     * 過去日かどうか確認し、過去日の場合はエラーを吐き出す
     * @param Carbon $date
     * @return bool
     * @throws ExclusionException
     */
    public function checkPastAndError(Carbon $date)
    {
        $second = Carbon::today();
        if ($date < $second) {
            $errLogFmt = ErrorCode::ERROR_LOG_LIST[ErrorCode::INVALID_DATE_PAST];
            Log::error(vsprintf($errLogFmt, [$date->format('Y-m-d')]));
            throw new ExclusionException(ErrorCode::INVALID_DATE_PAST);
        }
    }
}
