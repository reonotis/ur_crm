@extends('layouts.shop_setting')
@section('breadcrumb')
    <ol>
        <li><a href="{{ route('home') }}">ホーム</a></li>
        <li><a href="{{ route('shop_setting.index') }}">店舗設定</a></li>
    </ol>
@endsection
@section('pageTitle', '臨時定休/臨時営業設定')

@section('content')
    <div style="width: 100%;max-width: 1500px;padding-right: 15px;padding-left: 15px;margin-right: auto;margin-left: auto;">
        <div class="setting-content">
            @if($futureReserve || $closeDay)
                <div style="margin-bottom: 2rem;">
                    @if($futureReserve)
                        <p>現在＿＿＿までの予約があります<br>予約に影響が無いように営業時間を変更して下さい</p><br>
                    @endif
                    @if($closeDay)
                        <p>閉店日:{{ $closeDay->format('Y年m月d日') }}&nbsp;を超えた設定は出来ません</p>
                    @endif
                </div>
            @endif

            <div class="content-data">
                <div class="tab-contents-area">
                    <div class="shop-setting-h4">適用させる臨時定休/臨時営業を追加</div>
                    <form action="{{route('shop_setting.temporary_business_hour_register')}}" method="post">
                        @method('post')
                        @csrf
                        <table class="list-tbl setting-tbl" style="margin-bottom: 4rem;">
                            <thead>
                                <tr>
                                    <th>対象日</th>
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
                                        <div class="flex-center-middle">
                                            <div class="">
                                                @component('components.input-one-items', [
                                                    'type' => 'date',
                                                    'inputName' => 'target_date',
                                                    'id' => 'target_date',
                                                    'required' => true,
                                                    'value' => old('target_date'),
                                                    'class' => 'form-control w-40 mx-auto',
                                                ])
                                                @endcomponent
                                            </div>
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

                    <div class="shop-setting-h4">現在設定中の定休日</div>
                    <table class="list-tbl setting-tbl" style="margin-bottom: 4rem;">
                        <thead>
                            <tr>
                                <th>対象日</th>
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
                            @foreach($featureTemporaryBusinessHours as $temporaryBusinessHour)
                                <tr>
                                    <td>{{ $temporaryBusinessHour->target_date }}</td>
                                    <td>
                                        @if($temporaryBusinessHour->holiday)
                                            定休日
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($temporaryBusinessHour->holiday)
                                            -
                                        @else
                                            {{ Carbon\Carbon::createFromTimeString($temporaryBusinessHour->business_open_time)->format('H:i') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($temporaryBusinessHour->holiday)
                                            -
                                        @else
                                            {{ Carbon\Carbon::createFromTimeString($temporaryBusinessHour->last_reception_time)->format('H:i') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($temporaryBusinessHour->holiday)
                                            -
                                        @else
                                            {{ Carbon\Carbon::createFromTimeString($temporaryBusinessHour->business_close_time)->format('H:i') }}
                                        @endif
                                    </td>
                                    @if (session()->get(SessionConst::SELECTED_SHOP)->userShopAuthorization->shop_setting_delete)
                                        <td>
                                            <a href="{{ route('shop_setting.temporary_business_hour_delete', ['shopBusinessHourTemporary' => $temporaryBusinessHour->id]) }}"
                                               class="submit delete-btn min-btn"
                                               onclick="return confirm({{ ConfirmMessage::SHOP_BUSINESS_FOUR_DELETE }});">削除</a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="flex" style="flex-wrap: wrap; justify-content: space-evenly;">
                        @foreach($businessHourCalendars as $key => $calendar)
                            @include('layouts.shop_setting.business_hour_calendar',[$calendar])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


