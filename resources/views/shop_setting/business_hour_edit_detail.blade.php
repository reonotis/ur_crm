<div class="shop-setting-h4">現在適用中の営業時間</div>

@include('layouts.shop_setting.one_week_business_hour')

<div class="shop-setting-h4">適用させる営業時間を追加</div>
<form action="{{route('shop_setting.business_hour_register')}}" method="post">
    @method('post')
    @csrf
    <table class="list-tbl setting-tbl" style="margin-bottom: 4rem;">
        <thead>
        <tr>
            <th>対象曜日</th>
            <th>適用開始日</th>
            <th>定休日</th>
            <th>営業開始時間</th>
            <th>最終受付時間</th>
            <th>営業終了時間</th>
            <th>登録</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                @component('components.input-one-items', [
                    'type' => 'select',
                    'inputName' => 'day_of_week',
                    'id' => 'day_of_week',
                    'required' => true,
                    'options' => $weekSelectOptions,
                    'class' => 'form-control w-48 mx-auto',
                    'value' => old('day_of_week'),
                ])
                @endcomponent
            </td>
            <td>
                <div class="flex-center-middle">
                    <div class="">
                        @component('components.input-one-items', [
                            'type' => 'date',
                            'inputName' => 'setting_start_date',
                            'id' => 'setting_start_date_week_register',
                            'required' => true,
                            'value' => old('setting_start_date'),
                            'class' => 'form-control w-40 mx-auto',
                        ])
                        @endcomponent
                    </div>
                    ～
                </div>
            </td>
            <td>
                @component('components.input-one-items', [
                    'type' => 'checkbox',
                    'inputName' => 'regular_holiday',
                    'id' => 'regular_holiday',
                    'class' => 'form-control w-48 mx-auto',
                    'value' => 1,
                    'checked' => old('regular_holiday') == '1' ?? true,
                ])
                @endcomponent
            </td>
            <td>
                @include('layouts.input_format', [
                    'type' => 'time',
                    'inputName' => 'business_open_time',
                    'id' => 'business_open_time',
                    'oldName' => 'business_open_time',
                    'class' => 'form-control w-32 mx-auto',
                ])
            </td>
            <td>
                @include('layouts.input_format', [
                    'type' => 'time',
                    'inputName' => 'last_reception_time',
                    'id' => 'last_reception_time',
                    'oldName' => 'last_reception_time',
                    'class' => 'form-control w-32 mx-auto',
                ])
            </td>
            <td>
                @include('layouts.input_format', [
                    'type' => 'time',
                    'inputName' => 'business_close_time',
                    'id' => 'business_close_time',
                    'oldName' => 'business_close_time',
                    'class' => 'form-control w-32 mx-auto',
                ])
            </td>
            <td>
                <input type="submit" class="register-btn min-btn" value="登録">
            </td>
        </tr>
        </tbody>
    </table>
</form>

<div class="shop-setting-h4">適用前の営業時間</div>
<table class="list-tbl setting-tbl edit" style="margin-bottom: 4rem;">
    <thead>
    <tr>
        <th>No.</th>
        <th>曜日</th>
        <th>適用開始日</th>
        <th>定休日</th>
        <th>営業開始時間</th>
        <th>最終受付時間</th>
        <th>営業終了時間</th>
        @if (session()->get(SessionConst::SELECTED_SHOP)->userShopAuthorization->shop_setting_delete)
            <th>削除</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach($shopBusinessHours as $key => $shopBusinessHour)
        <tr class="apply-type-{{ $shopBusinessHour->applyType }}" style="position: relative;">
            <td>{{ $key + 1 }}</td>
            <td>{{ App\Consts\ShopSettingConst::WEEK_LABEL_LIST[$shopBusinessHour->week_no] }}</td>
            <td>{{ Carbon\Carbon::createFromTimeString($shopBusinessHour->setting_start_date)->format('Y-m-d') }}～</td>
            <td>
                @if($shopBusinessHour->regular_holiday)
                    定休日
                @else
                    -
                @endif
            </td>
            <td>
                @if($shopBusinessHour->regular_holiday)
                    -
                @else
                    {{ Carbon\Carbon::createFromTimeString($shopBusinessHour->business_open_time)->format('H:i') }}
                @endif
            </td>
            <td>
                @if($shopBusinessHour->regular_holiday)
                    -
                @else
                    {{ Carbon\Carbon::createFromTimeString($shopBusinessHour->last_reception_time)->format('H:i') }}
                @endif
            </td>
            <td>
                @if($shopBusinessHour->regular_holiday)
                    -
                @else
                    {{ Carbon\Carbon::createFromTimeString($shopBusinessHour->last_reception_time)->format('H:i') }}
                @endif
            </td>
            @if (session()->get(SessionConst::SELECTED_SHOP)->userShopAuthorization->shop_setting_delete)
                <td>
                    <a href="{{ route('shop_setting.business_hour_delete', ['shopBusinessHour'=>$shopBusinessHour->id]) }}"
                       class="submit delete-btn min-btn"
                       onclick="return confirm({{ ConfirmMessage::SHOP_BUSINESS_FOUR_DELETE }});">削除</a>
                </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>

<div class="shop-setting-h4">現在の設定されている営業時間</div>
<p style="margin: 0 0 1rem 1rem; font-family: monospace;">O:営業開始時間 L:最終受付時間 C:営業終了時間</p>
<div class="flex" style="flex-wrap: wrap; justify-content: space-evenly;">
    @foreach($businessHourCalendars as $calendar)
        @include('layouts.shop_setting.business_hour_calendar', [$calendar])
    @endforeach
</div>
