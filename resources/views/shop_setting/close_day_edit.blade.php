@extends('layouts.shop_setting')
@section('breadcrumb')
    <ol>
        <li><a href="{{ route('myPage') }}">ホーム</a></li>
        <li><a href="{{ route('shop_setting.index') }}">店舗設定</a></li>
    </ol>
@endsection
@section('pageTitle', '店舗閉店日設定')

@section('content')
    <div style="width: 100%;max-width: 500px;">
        @if($futureReserve)
            <div style="margin-bottom: 2rem;">
                @if($futureReserve)
                    <p>現在＿＿＿までの予約があります<br>予約に影響が無いように閉店日を変更して下さい</p><br>
                @endif
            </div>
        @endif

        <div style="margin: 2rem 0;">
            <form action="{{ route('shop_setting.update_close_day') }}" method="post" >
                @method('post')
                @csrf
                <div class="flex-start-middle mb-4" style="border-bottom: solid 1px var(--mainColor)">
                    @php
                        if($closeDay){
                            $closeDay = $closeDay->format('Y-m-d');
                        }else{
                            $closeDay = null;
                        }
                    @endphp
                    @component('components.input-one-items', [
                        'type' => 'date',
                        'labelName' => '閉店日',
                        'inputName' => 'shop_close_day',
                        'id' => 'shop_close_day',
                        'value' => old('shop_close_day', $closeDay),
                        'class' => 'form-control w-40 mx-auto',
                    ])
                    @endcomponent
                </div>
                <div class="flex-center-middle mb-4">
                    <input type="submit" class="register-btn" value="更新">
                </div>
            </form>

        </div>
    </div>
@endsection


