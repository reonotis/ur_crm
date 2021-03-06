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
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>メールアドレス</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>店舗</th>
                                <td>{{ $user->shop_name }}</td>
                            </tr>
                            <tr>
                                <th>権限</th>
                                <td>{{ $user->authority_name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
