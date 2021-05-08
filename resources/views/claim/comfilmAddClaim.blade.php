@extends('layouts.app')

@section('content')
<div class="container">
        <!-- <a href="{{route('customer.index')}}">顧客検索</a> -->
        
    <div class="row justify-content-center">

    <div class="fullWidth">
        <button class="btn btn-outline-dark btn-sm" type="button" onClick="history.back()">戻る</button>
    </div>

        <div class="col-md-10">
            <div class="card">
                <div class="card-header">請求データ作成 確認画面</div>

                <div class="card-body">
                        <table class="customerSearchTable">
                            <tr>
                                <th>請求先</th>
                                <td><?= $user->name ?> 様</td>
                            </tr>
                            <tr>
                                <th>請求名目</th>
                                <td><?= $ClaimTrn->title ?></td>
                            </tr>
                            <tr>
                                <th>期日</th>
                                <td><?= $ClaimTrn->limit_date->format('Y年 m月 d日') ?></td>
                            </tr>
                            <tr>
                                <th>請求金額</th>
                                <td>{{ number_format($grossAmount) }} 円</td>
                            </tr>
                            <tr>
                                <th>内訳</th>
                                <td>
                                    @foreach($CDTrns as $CDTrn)
                                        <div class="confilmClaimDetailRow">
                                            <div class="item_name">
                                                {{ $CDTrn->item_name }} 
                                            </div>
                                            <div class="unit_price">
                                                {{ number_format($CDTrn->unit_price) }} 円 
                                            </div>
                                            <div class="quantity">
                                                {{ number_format($CDTrn->quantity) ." ". $CDTrn->unit }} 
                                            </div>
                                            <div class="price">
                                                {{ number_format($CDTrn->price) }} 円
                                            </div>
                                        </div>
                                    @endforeach
                                </td>
                            </tr>
                        </table>
                        上記内容で請求情報を作成しますか？<br>
                        ※作成した請求情報は変更できません<br>


                            <button class="btn btn-outline-dark" onClick="history.back()">戻る</button>
                        <a href="{{ route('claim.storeClaim', ['id'=>$user->id]) }}">
                            <button class="btn btn-outline-success">作成する</button>
                        </a>
                </div>
            </div>
        </div>
    </div>

    <?php
        // dd($CDTrns);
    ?>
</div>
@endsection


<script>

    function confilmDeleteClaim(){
        var result = window.confirm('この請求データを削除ますか？\nこの操作は取り消せません。');
        if( result ) return true; return false;
    }

</script>