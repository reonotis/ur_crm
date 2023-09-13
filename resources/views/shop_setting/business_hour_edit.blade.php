@extends('layouts.shop_setting')
@section('pageTitle', '店舗営業時間設定')

@section('content')
    <div class="container">
        <div class="setting-content">
            @if($futureReserve || $closeDay)
                <div style="margin-bottom: 2rem;">
                    @if($futureReserve || $closeDay)
                        <p>現在＿＿＿までの予約があります<br>予約に影響が無いように営業時間を変更して下さい</p><br>
                    @endif
                    @if($closeDay)
                        <p>閉店日:{{ $closeDay->format('Y年m月d日') }}&nbsp;以降の設定は出来ません</p>
                    @endif
                </div>
            @endif

            <div class="content-data">
                <div class="tab-navigation-area">
                    @foreach(App\Consts\ShopSettingConst::BUSINESS_HOUR_TYPE_LIST as $businessHourKey => $businessHourTypeName)
                        <label>
                            <input type="radio" name="business_hour_type" value="{{ $businessHourKey }}"
                            @if( $businessHourType == $businessHourKey)
                                checked="checked"
                            @endif>{{ $businessHourTypeName }}
                        </label>
                    @endforeach
                </div>
                <div class="tab-contents-area">
                    <div class="business-hour-edit-content type-1">
                        @include('shop_setting.business_hour_edit_everyday')
                    </div>
                    <div class="business-hour-edit-content type-2">
                        @include('shop_setting.business_hour_edit_weekday')
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection


