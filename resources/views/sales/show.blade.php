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
                    {{ $date['month'] }}分の売り上げデータを表示しています<br><br>
                    @if(count($date['sales']) == 0)
                        該当月の売り上げータはありません
                    @else
                    <div class="sales_contents" >
                        <div class="gross_amount_row" >合計売上額
                            <div class="gross_amount" >{{ number_format($date['gross_amount']) }}円</div>
                        </div>

                        <div class="breakdown_title" >
                            <div class="breakdown_names" >顧客名</div>
                            <div class="breakdown_price" >金額</div>
                            <div class="breakdown_date" >計上日</div>
                        </div>

                        @foreach($date['sales'] as $sale)
                            <div class="breakdown_contents" >
                                <div class="breakdown_names" >{{ $sale->name }}</div>
                                <div class="breakdown_price" >{{ number_format($sale->price) }}円</div>
                                <div class="breakdown_date" >{{ $sale->complete_date->format('m月d日') }}</div>
                            </div>
                        @endforeach
                    </div>
                    @endif


                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<?php
// dd($date);
?>