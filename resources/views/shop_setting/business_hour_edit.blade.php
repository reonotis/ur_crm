@extends('layouts.shop_setting')
@section('breadcrumb')
    <ol>
        <li><a href="{{ route('myPage') }}">ホーム</a></li>
        <li><a href="{{ route('shop_setting.index') }}">店舗設定</a></li>
    </ol>
@endsection
@section('pageTitle', '店舗営業時間設定')

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
                    @include('shop_setting.business_hour_edit_detail')
                </div>
            </div>
        </div>
    </div>
@endsection


