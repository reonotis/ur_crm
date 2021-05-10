@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="fullWidth">
        <button class="btn btn-outline-dark btn-sm" type="button" onClick="history.back()">戻る</button>
    </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">売上確認</div>

                <div class="card-body">

<!-- TODO データを作成して表示させるようにします。 -->
                {{ $month }}のデータを作成して表示させるようにします。<br><br>
                機能作成中

                </div>
            </div>
        </div>
    </div>
</div>
@endsection


