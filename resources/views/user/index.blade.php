@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a href="{{route('user.index')}}">社員一覧</a>
                </div>


                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <a href="{{route('user.create')}}">新規社員を追加する</a>
                    <br>
                    <a href="{{route('client.search')}}">検索する</a>
                    <br>
                    <br>
                    <table class="">
                        <thead>
                            <tr>
                                <th>社員名</th>
                                <th>チーム</th>
                                <th>権限</th>
                                <th>在籍</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td><a href="{{route('user.display', ['id' => $user-> id ])}}">{{ $user-> name }}</a></td>
                                    <td></td>
                                    <td>{{ $user-> authority }}</td>
                                    <td>{{ $user-> enrollment }}</td>
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
