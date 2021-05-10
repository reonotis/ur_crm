@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">売上確認</div>

                <div class="card-body">
                    ※過去1年分を表示しています。<br>
                    @foreach( $months as $month )
                    <a href="{{ route('sales.show', ['month'=>$month['month'] ]) }}" >{{ $month['displayName'] }}</a> <br>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
@endsection


