<?php

namespace App\Rules;

use App\Consts\SessionConst;
use Illuminate\Contracts\Validation\Rule;
use App\Services\{ShopBusinessHourService};

class SettingStartDateRule implements Rule
{

    private ?int $shopBusinessHourId;  // null:新規登録時、int:既存レコード更新時

    private ShopBusinessHourService $shopBusinessHourService;
    private string $message;

    /**
     * Create a new rule instance.
     *
     * @param int|null $shopBusinessHourId // 既存レコード更新時に渡される
     */
    public function __construct(int $shopBusinessHourId = null)
    {
        $this->shopBusinessHourId = $shopBusinessHourId;
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
        $shop = session()->get(SessionConst::SELECTED_SHOP);
        $errorMessage = $this->shopBusinessHourService->confirmWhetherRegisterDate($shop->id, $value, $this->shopBusinessHourId);
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
