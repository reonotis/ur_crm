@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    管理者の場合<br>
                    　・申請中で未受理のコースがあれば表示<br>
                    　・養成courseを終えたお客様がいれば表示<br>
                    <br><br>
                    それ以外の場合<br>
                    　・システムのからのメッセージ表示<br>
                    　・申請したコースが差し戻されていたら表示<br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
