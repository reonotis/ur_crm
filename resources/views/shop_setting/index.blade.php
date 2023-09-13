@extends('layouts.shop_setting')
@section('pageTitle', '店舗設定')

@section('content')
    <div class="container">
        <div class="setting-content" style="margin-bottom: 2rem">
            <div class="setting-title">
                営業時間
                @if (session()->get(SessionConst::SELECTED_SHOP)->userShopAuthorization->shop_setting_edit)
                    <a href="{{ route('shop_setting.business_hour_edit') }}" class="inline-anker">編集</a>
                @endif
            </div>
            <div class="setting-data">
                <p>{{ App\Consts\ShopSettingConst::BUSINESS_HOUR_TYPE_LIST[$businessHourType] }}</p>
                <table class="list-tbl setting-tbl">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>状態</th>
                            <th>営業開始時間</th>
                            <th>最終受付時間</th>
                            <th>営業終了時間</th>
                            <th>適用期間</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shopBusinessHours as $key => $shopBusinessHour)
                            <tr class="apply-type-{{ $shopBusinessHour['applyType'] }}" style="position: relative;">
                                <td>
                                    {{ $key + 1 }}
                                </td>
                                <td>{{ App\Consts\ShopSettingConst::APPLY_LIST[$shopBusinessHour['applyType']] }}</td>
                                <td>{{ Carbon\Carbon::createFromTimeString($shopBusinessHour['business_open_time'])->format('H:i') }}</td>
                                <td>{{ Carbon\Carbon::createFromTimeString($shopBusinessHour['last_reception_time'])->format('H:i') }}</td>
                                <td>{{ Carbon\Carbon::createFromTimeString($shopBusinessHour['business_close_time'])->format('H:i') }}</td>
                                <td>
                                    {{ $shopBusinessHour['setting_start_date']->format('Y / m / d') }}
                                    ～
                                    @if(is_null($shopBusinessHour['setting_end_date']))
                                        未設定
                                    @else
                                        {{ $shopBusinessHour['setting_end_date']->format('Y / m / d') }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="setting-content">
            <div class="setting-title">
                定休日
            </div>
            <div class="setting-data">
            </div>
        </div>
    </div>
@endsection


