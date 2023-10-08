<?php

namespace App\Rules;

use App\Consts\SessionConst;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use App\Services\{ShopBusinessHourService};

class ShopBusinessHourTemporaryRule implements Rule
{
    /** @var ShopBusinessHourService $shopBusinessHourService */
    private $shopBusinessHourService;

    private $message;

    private const ALREADY_EXISTS = '既に同じ日に臨時定休/臨時営業が設定されています';

    /**
     * Create a new rule instance.
     *
     */
    public function __construct()
    {
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

        $shop = session()->get(SessionConst::SELECTED_SHOP);
        if ($this->shopBusinessHourService->getShopBusinessHourTemporaryByTargetDate($shop->id, $date)) {
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
        return self::ALREADY_EXISTS;
    }
}
