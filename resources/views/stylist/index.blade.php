@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">スタイリスト検索</div>

                <div class="card-body">
                    @foreach($users as $user)
                        <?= $user->name ?>

                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

