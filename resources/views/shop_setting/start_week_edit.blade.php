@extends('layouts.shop_setting')
@section('breadcrumb')
    <ol>
        <li><a href="{{ route('home') }}">ホーム</a></li>
        <li><a href="{{ route('shop_setting.index') }}">店舗設定</a></li>
    </ol>
@endsection
@section('pageTitle', '週始まり設定')

@section('content')
    <div style="width: 100%;max-width: 500px;">

        <div style="margin: 2rem 0;">
            <form action="{{ route('shop_setting.update_start_week') }}" method="post" >
                @method('post')
                @csrf
                <div class="flex-start-middle mb-4" style="border-bottom: solid 1px var(--mainColor)">
                    @component('components.input-one-items', [
                        'type' => 'select',
                        'labelName' => '週始まり',
                        'inputName' => 'start_week',
                        'id' => 'start_week',
                        'required' => true,
                        'options' => $startWeekSelectOptions,
                        'class' => 'form-control w-48 mx-auto',
                        'value' => old('start_week', $selected),
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


