<?php

namespace App\Rules;

use App\Consts\SessionConst;
use App\Consts\ShopSettingConst;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use App\Services\{ShopBusinessHourService};

class SettingStartDateRule implements Rule
{

    private $dayOfWeek; // 曜日の番号

    /** @var ShopBusinessHourService $shopBusinessHourService */
    private $shopBusinessHourService;

    private $message;

    private const DAY_OF_WEEK_MISS_MUCH_MSG = '適用開始日と曜日が一致しません';

    /**
     * Create a new rule instance.
     *
     */
    public function __construct(int $day_of_week)
    {
        $this->dayOfWeek = $day_of_week;
        $this->shopBusinessHourService = app(ShopBusinessHourService::class);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $date = new Carbon($value);

        // 1週間の曜日か、祝日かを判定
        $dayOfWeek = true;
        if($this->dayOfWeek == ShopSettingConst::HOLIDAY || $this->dayOfWeek == ShopSettingConst::BEFORE_HOLIDAY ){
            // 祝日か祝前日だったらfalse
            $dayOfWeek = false;
        }

        // 祝日もしくは祝前日ではない場合は、曜日と日付が一致する事、
        if ( $dayOfWeek && $this->dayOfWeek <> $date->dayOfWeekIso) {
            $this->message = self::DAY_OF_WEEK_MISS_MUCH_MSG;
            return false;
        }

        $shop = session()->get(SessionConst::SELECTED_SHOP);
        $errorMessage = $this->shopBusinessHourService->confirmWhetherRegisterDate($shop->id, $date, $this->dayOfWeek);
        if ($errorMessage) {
            $this->message = $errorMessage;
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }
}
