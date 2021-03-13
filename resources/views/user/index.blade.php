@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{route('user.index')}}">インストラクター一覧</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <!-- <div class="LeftBOX">
                        <a href="{{route('user.create')}}">
                            <div class="button BOXin">新しいインストラクターを追加する</div>
                        </a>
                    </div> -->

                    <table class="customerListTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>インストラクター名</th>
                                <th>権限</th>
                                <th>在籍</th>
                                <th>確認</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user-> id }}</td>
                                    <td>{{ $user-> name }}</td>
                                    <td>{{ $user-> authority }}</td>
                                    <td>{{ $user-> enrolled }}</td>
                                    <td><a href="" >確認する</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
