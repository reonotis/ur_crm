<?php

namespace App\Http\Requests;

use App\Rules\ShopBusinessHourTemporaryRule;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $shop_id
 * @property string $regular_holiday
 * @property string $target_date
 * @property string $business_open_time
 * @property string $business_close_time
 * @property Carbon $last_reception_time
 */
class BusinessHourTemporaryRequest extends FormRequest
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
        return [
            'target_date' => ['required', new ShopBusinessHourTemporaryRule()],
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
            'target_date'=> '対象日',
            'regular_holiday' => '定休日',
            'business_open_time' => '営業開始時間',
            'last_reception_time'=> '最終受付時間',
            'business_close_time' => '営業終了時間',
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'required_unless' => ':otherにしない場合 :attributeは必須です',
            'required' => ':attributeは必須です',
            'after_or_equal' => ':date以降の時間を指定してください',
        ];
    }
}
