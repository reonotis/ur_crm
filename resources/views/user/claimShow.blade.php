@extends('layouts.app')

@section('content')
<?php $date = date('Y-m-d', strtotime('-1 day')); ?>
<div class="container">
  <div class="fullWidth">
    <button class="btn btn-outline-dark btn-sm" type="button" onClick="history.back()">戻る</button>
  </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">請求情報確認画面</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                  <div class="cusInfoRow" >
                    <div class="cusInfoTitle" >請求先</div>
                    <div class="cusInfoContent" >{{ $claim->name }} 様</div>
                  </div>
                  <div class="cusInfoRow" >
                    <div class="cusInfoTitle" >項目名</div>
                    <div class="cusInfoContent" >{{ $claim->title }}</div>
                  </div>
                  <div class="cusInfoRow" >
                    <div class="cusInfoTitle" >請求金額</div>
                    <div class="cusInfoContent" >{{ $claim->price }}</div>
                  </div>
                  <div class="cusInfoRow" >
                    <div class="cusInfoTitle" >ステータス</div>
                    <div class="cusInfoContent" ></div>
                  </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php
// dd($users);
?>
@endsection

<script>
    function confirmClaimComplete(){
        var result = window.confirm('入金完了にし、売り上げ情報に登録します。\n宜しいですか？');
        if( result ) return true; return false;
    }
</script>
