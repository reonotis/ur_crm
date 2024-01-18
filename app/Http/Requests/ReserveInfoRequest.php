<?php

namespace App\Http\Requests;

use App\Services\ReserveService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property int $vis_time
 * @property int $section
 * @property int $menu_id
 * @property int $user_id
 * @property int $memo
 */
class ReserveInfoRequest extends FormRequest
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
        $reserveService = app(ReserveService::class);
        $sections = $reserveService->makeReserveSection(10, 30, 2);
        return [
            'vis_time' => ['required', 'date_format:H:i'],
            'section' => ['required', 'numeric', Rule::in(collect($sections)->pluck('value'))],
            'menu_id' => ['nullable'],
            'user_id' => ['required'],
            'memo' => ['nullable', 'max:1000'],
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
            'section' => 'セクション',
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'section.in' => '正しい:attributeを選択してください',
        ];
    }
}
