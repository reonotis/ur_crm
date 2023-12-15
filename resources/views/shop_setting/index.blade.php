@extends('layouts.shop_setting')
@section('breadcrumb')
    <ol>
        <li><a href="{{ route('home') }}">ホーム</a></li>
    </ol>
@endsection
@section('pageTitle', '店舗設定')

@section('content')
    <div class="container">
        <div class="setting-content">
            <div class="setting-title">
                営業時間
                @if (session()->get(SessionConst::SELECTED_SHOP)->userShopAuthorization->shop_setting_edit)
                    <a href="{{ route('shop_setting.business_hour_edit') }}" class="inline-anker">編集</a>
                @endif
            </div>
            <div class="setting-data">
                @include('layouts.shop_setting.one_week_business_hour')
                <div class="flex" style="flex-wrap: wrap; justify-content: space-evenly;">
                    @foreach($businessHourCalendars as $key => $calendar)
                        @if($key >= 2)
                            @break
                        @endif
                        @include('layouts.shop_setting.business_hour_calendar',[$calendar])
                    @endforeach
                </div>
            </div>
        </div>

        <div class="setting-content">
            <div class="setting-title">
                臨時定休&nbsp;/&nbsp;臨時営業
                @if (session()->get(SessionConst::SELECTED_SHOP)->userShopAuthorization->shop_setting_edit)
                    <a href="{{ route('shop_setting.temporary_business_hour_edit') }}" class="inline-anker">編集</a>
                @endif
            </div>
            <div class="setting-data">
                現在臨時定休日の設定はありません
            </div>
        </div>

        <div class="setting-content">
            <div class="setting-title">
                閉店日
                @if (session()->get(SessionConst::SELECTED_SHOP)->userShopAuthorization->shop_setting_edit)
                    <a href="{{ route('shop_setting.close_day_edit') }}" class="inline-anker">編集</a>
                @endif
            </div>
            <div class="setting-data">
                @if($closeDay)
                    {{ $closeDay->format('Y年m月d日') }}に閉店予定です
                @else
                    閉店日は設定されていません
                @endif
            </div>
        </div>

        <div class="setting-content">
            <div class="setting-title">
                週の始まり
                @if (session()->get(SessionConst::SELECTED_SHOP)->userShopAuthorization->shop_setting_edit)
                    <a href="{{ route('shop_setting.start_week_edit') }}" class="inline-anker">編集</a>
                @endif
            </div>
            <div class="setting-data">
                {{ App\Consts\ShopSettingConst::WEEK_LABEL_LIST[$weekList[0]] }}から始まります
            </div>
        </div>
    </div>
@endsection


