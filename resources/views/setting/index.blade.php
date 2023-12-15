@extends('layouts.setting')
@section('breadcrumb')
    <ol>
        <li><a href="{{ route('home') }}">ホーム</a></li>
    </ol>
@endsection
@section('pageTitle', 'アカウント情報')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @include('setting.navigation')
                <div class="setting-basic-contents" >
                    <div class="setting-row" >
                        <div class="setting-title" >名前</div>
                        <div class="setting-contents" >{{ \Auth::user()->name }}</div>
                    </div>
                    <div class="setting-row" >
                        <div class="setting-title" >メールアドレス</div>
                        <div class="setting-contents" >{{ \Auth::user()->email }}</div>
                    </div>
                    <div class="setting-row" >
                        <div class="setting-title" >所属店舗</div>
                        <div class="setting-contents" >
                            <div class="" >
                                @foreach(\Auth::user()->userShopAuthorizations AS $shopAuthorization)
                                    <p>{{ $shopAuthorization->shop->shop_name }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
