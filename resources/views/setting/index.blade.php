@extends('layouts.app')

@section('content')

@include('setting.navigation')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">アカウント情報</div>
                <div class="card-body">
                    <table class="tableClass_007">
                        <tbody>
                            <tr>
                                <th>名前</th>
                                <td>{{ \Auth::user()->name }}</td>
                            </tr>
                            <tr>
                                <th>メールアドレス</th>
                                <td>{{ \Auth::user()->email }}</td>
                            </tr>
                            <tr>
                                <th>店舗</th>
                                <td>
                                    @foreach(\Auth::user()->userShopAuthorizations AS $shopAuthorization)
                                        <p>{{ $shopAuthorization->shop->shop_name }}</p>
                                    @endforeach
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
