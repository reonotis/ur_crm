
<div class="shop-setting-h4">営業時間を変更する予定を追加</div>
<form action="{{route('shop_setting.business_hour_register_everyday')}}" method="post">
    @method('post')
    @csrf
    <table class="list-tbl setting-tbl" style="margin-bottom: 4rem;">
        <thead>
            <tr>
                <th>営業開始時間</th>
                <th>最終受付時間</th>
                <th>営業終了時間</th>
                <th>適用開始日</th>
                <th>登録</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    @include('layouts.input_format', [
                        'type' => 'time',
                        'inputName' => 'business_open_time',
                        'oldName' => 'business_open_time',
                        'class' => 'form-control w-32 mx-auto',
                    ])
                </td>
                <td>
                    @include('layouts.input_format', [
                        'type' => 'time',
                        'inputName' => 'last_reception_time',
                        'oldName' => 'last_reception_time',
                        'class' => 'form-control w-32 mx-auto',
                    ])
                </td>
                <td>
                    @include('layouts.input_format', [
                        'type' => 'time',
                        'inputName' => 'business_close_time',
                        'oldName' => 'business_close_time',
                        'class' => 'form-control w-32 mx-auto',
                    ])
                </td>
                <td>
                    <div class="flex-center-middle">
                        <div>
                            @include('layouts.input_format', [
                                'type' => 'date',
                                'inputName' => 'setting_start_date',
                                'oldName' => 'setting_start_date',
                                'class' => 'form-control w-40 mx-auto',
                            ])
                        </div>
                        ～
                    </div>
                </td>
                <td>
                    <input type="hidden" name="business_hour_type" value="{{ App\Consts\ShopSettingConst::BUSINESS_HOUR_EVERYDAY }}">
                    <input type="submit" class="register-btn min-btn" value="登録">
                </td>
            </tr>
        </tbody>
    </table>
</form>

<div class="shop-setting-h4">現在の設定されている営業時間を変更</div>
<table class="list-tbl setting-tbl edit">
    <thead>
    <tr>
        <th>No.</th>
        <th>状態</th>
        <th>営業開始時間</th>
        <th>最終受付時間</th>
        <th>営業終了時間</th>
        <th>適用期間</th>
        @if (session()->get(SessionConst::SELECTED_SHOP)->userShopAuthorization->shop_setting_edit)
            <th>更新</th>
        @endif
        @if (session()->get(SessionConst::SELECTED_SHOP)->userShopAuthorization->shop_setting_delete)
            <th>削除</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach($shopBusinessHours as $key => $shopBusinessHour)
        <tr class="apply-type-{{ $shopBusinessHour->applyType }}" style="position: relative;">
            <td>{{ $key + 1 }}</td>
            <td>{{ App\Consts\ShopSettingConst::APPLY_LIST[$shopBusinessHour->applyType] }}</td>
            <td>
                @if($shopBusinessHour->applyType == 1)
                    @include('layouts.input_format', [
                        'type' => 'time',
                        'inputName' => 'business_open_time_' . $shopBusinessHour->id,
                        'id' => 'business_open_time_' . $shopBusinessHour->id ,
                        'oldName' => 'business_open_time_' . $shopBusinessHour->id,
                        'class' => 'form-control w-32 mx-auto',
                        'value' =>Carbon\Carbon::createFromTimeString($shopBusinessHour->business_open_time)->format('H:i'),
                    ])
                @else
                    {{ Carbon\Carbon::createFromTimeString($shopBusinessHour->business_open_time)->format('H:i') }}
                @endif
            </td>
            <td>
                @if($shopBusinessHour->applyType == 1)
                    @include('layouts.input_format', [
                        'type' => 'time',
                        'inputName' => 'last_reception_time[' . ($shopBusinessHour->id) . ']',
                        'id' => 'last_reception_time_' . ($shopBusinessHour->id) ,
                        'oldName' => 'last_reception_time_' . ($shopBusinessHour->id),
                        'class' => 'form-control w-32 mx-auto',
                        'value' =>Carbon\Carbon::createFromTimeString($shopBusinessHour->last_reception_time)->format('H:i'),
                    ])
                @else
                    {{ Carbon\Carbon::createFromTimeString($shopBusinessHour->last_reception_time)->format('H:i') }}
                @endif
            </td>
            <td>
                @if($shopBusinessHour->applyType == 1)
                    @include('layouts.input_format', [
                        'type' => 'time',
                        'inputName' => 'business_close_time[' . ($shopBusinessHour->id) . ']',
                        'id' => 'business_close_time_' . ($shopBusinessHour->id) ,
                        'oldName' => 'business_close_time_' . ($shopBusinessHour->id),
                        'class' => 'form-control w-32 mx-auto',
                        'value' =>Carbon\Carbon::createFromTimeString($shopBusinessHour->business_close_time)->format('H:i'),
                    ])
                @else
                    {{ Carbon\Carbon::createFromTimeString($shopBusinessHour->last_reception_time)->format('H:i') }}
                @endif
            </td>
            <td>
                <div class="flex-center-middle">
                    @if($shopBusinessHour->applyType == 1)
                        {{-- 適用開始日 --}}
                        <div>
                            @include('layouts.input_format', [
                                'type' => 'date',
                                'inputName' => 'setting_start_date_' . ($shopBusinessHour->id),
                                'id' => 'setting_start_date_' . ($shopBusinessHour->id) ,
                                'oldName' => 'setting_start_date_' . ($shopBusinessHour->id),
                                'class' => 'form-control w-40',
                                'value' => $shopBusinessHour->setting_start_date->format('Y-m-d') ,
                            ])
                        </div>
                    @else
                        {{ $shopBusinessHour->setting_start_date->format('Y / m / d') }}
                    @endif
                    ～
                    {{-- 適用終了日 --}}
                    @if(!is_null($shopBusinessHour->setting_end_date))
                        {{ $shopBusinessHour->setting_end_date->format('Y / m / d') }}
                    @endif
                </div>
            </td>
            <td>
                @if (session()->get(SessionConst::SELECTED_SHOP)->userShopAuthorization->shop_setting_edit)
                    @if($shopBusinessHour->applyType == 1)
                        <form action="{{ route('shop_setting.business_hour_edit_everyday', ['shopBusinessHour'=>$shopBusinessHour->id]) }}" id="form_{{ $shopBusinessHour->id }}"
                            onSubmit="return check({{ $shopBusinessHour->id }});" method="post">
                            @method('post')
                            @csrf
                            <input type="hidden" name="business_hour_type" value="{{ App\Consts\ShopSettingConst::BUSINESS_HOUR_EVERYDAY }}">
                            <input type="hidden" name="shop_business_hour_id" value="{{ $shopBusinessHour->id }}">
                            <input type="hidden" name="business_open_time_{{ $shopBusinessHour->id }}" value="">
                            <input type="hidden" name="last_reception_time_{{ $shopBusinessHour->id }}" value="">
                            <input type="hidden" name="business_close_time_{{ $shopBusinessHour->id }}" value="">
                            <input type="hidden" name="setting_start_date_{{ $shopBusinessHour->id }}" value="">
                            <input type="submit" class="edit-btn min-btn" value="更新">
                        </form>
                    @endif
                @endif
            </td>
            <td>
                @if (session()->get(SessionConst::SELECTED_SHOP)->userShopAuthorization->shop_setting_delete)
                    @if($shopBusinessHour->applyType == 1)
                        <a href="{{ route('shop_setting.business_hour_delete_everyday', ['shopBusinessHour'=>$shopBusinessHour->id]) }}" class="submit delete-btn min-btn"
                           onclick="return confirm({{ ConfirmMessage::SHOP_BUSINESS_FOUR_DELETE }});">削除</a>
                    @endif
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
