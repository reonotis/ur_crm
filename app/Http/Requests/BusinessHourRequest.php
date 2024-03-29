<?php

namespace App\Http\Requests;

use App\Consts\ShopSettingConst;
use App\Rules\SettingStartDateRule;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string $day_of_week
 * @property string $regular_holiday
 * @property string $business_open_time
 * @property string $business_close_time
 * @property string $last_reception_time
 * @property Carbon $setting_start_date
 */
class BusinessHourRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // 入力可能な曜日
        $possibleDayOfWeekValues = [
            ShopSettingConst::MONDAY,
            ShopSettingConst::TUESDAY,
            ShopSettingConst::WEDNESDAY,
            ShopSettingConst::THURSDAY,
            ShopSettingConst::FRIDAY,
            ShopSettingConst::SATURDAY,
            ShopSettingConst::SUNDAY,
            ShopSettingConst::HOLIDAY,
            ShopSettingConst::BEFORE_HOLIDAY,
        ];

        return [
            'day_of_week' => ['required', Rule::in($possibleDayOfWeekValues)],
            'setting_start_date' => ['required', new SettingStartDateRule((int)$this->day_of_week)],
            'business_open_time' => ['required_unless:regular_holiday, 1',],
            'last_reception_time' => ['required_unless:regular_holiday, 1', 'date_format:H:i', 'after_or_equal:business_open_time'],
            'business_close_time' => ['required_unless:regular_holiday, 1', 'date_format:H:i', 'after_or_equal:last_reception_time',],
        ];
    }

    /**
     * バリデーションエラーのカスタム属性の取得
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'day_of_week' => '曜日',
            'regular_holiday'=> '定休日',
            'business_open_time' => '営業開始時間',
            'last_reception_time'=> '最終受付時間',
            'business_close_time' => '営業終了時間',
            'setting_start_date'=> '適用開始日',
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'required_unless' => ':otherにしない場合 :attributeは必須です',
            'day_of_week.in' => '入力可能な:attributeではありません',
            'required' => ':attributeは必須です',
            'after_or_equal' => ':date以降の時間を指定してください',
        ];
    }
}
