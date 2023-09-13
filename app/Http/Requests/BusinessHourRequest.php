<?php

namespace App\Http\Requests;

use App\Rules\SettingStartDateRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 *
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
        if($this->shop_business_hour_id) {
            $id = '_' . $this->shop_business_hour_id;
        }else {
            $id = null;
        }
        return [
            "business_open_time$id" => 'required',
            "last_reception_time$id" => "required|date_format:H:i|after_or_equal:business_open_time$id",
            "business_close_time$id" => "required|date_format:H:i|after_or_equal:last_reception_time$id",
            "setting_start_date$id" => ['required', new SettingStartDateRule($this->shop_business_hour_id)],
        ];
    }

    /**
     * バリデーションエラーのカスタム属性の取得
     *
     * @return array
     */
    public function attributes()
    {
        if($this->shop_business_hour_id) {
            $id = '_' . $this->shop_business_hour_id;
        }else{
            $id = null;
        }
        return [
            "business_open_time$id" => '営業開始時間',
            "last_reception_time$id" => '最終受付時間',
            "business_close_time$id" => '営業終了時間',
            "setting_start_date$id" => '適用開始日',
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'required' => ':attributeは必須です',
            'after_or_equal' => ':date以降の時間を指定してください',
        ];
    }
}
