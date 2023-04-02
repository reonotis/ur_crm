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


}
