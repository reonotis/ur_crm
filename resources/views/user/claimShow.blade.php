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
                    <div class="cusInfoContent" >{{ number_format($claim->price) }} 円</div>
                  </div>
                  <div class="cusInfoRow" >
                    <div class="cusInfoTitle" >期日</div>
                    <div class="cusInfoContent" >{{ $claim->limit_date->format('Y年 m月 d日') }}</div>
                  </div>
                  <div class="cusInfoRow" >
                    <div class="cusInfoTitle" >内訳</div>
                    <div class="cusInfoContent" >
                      @foreach($claimsDetails as $claimsDetail)
                        <div class="confilmClaimDetailRow">
                            <div class="item_name">
                                {{ $claimsDetail->item_name }}
                            </div>
                            <div class="unit_price">
                                {{ number_format($claimsDetail->unit_price) }} 円 
                            </div>
                            <div class="quantity">
                                {{ number_format($claimsDetail->quantity) ." ". $claimsDetail->unit }} 
                            </div>
                            <div class="price">
                                {{ number_format($claimsDetail->price) }} 円
                            </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                  <div class="cusInfoRow" >
                    <div class="cusInfoTitle" >ステータス</div>
                    <div class="cusInfoContent" >{{ $claim->statusName }}</div>
                  </div>
                  @if($claim->status <= 1)
                    <div class="cusInfoRow" >
                      <div class="cusInfoTitle" >ステータス変更</div>
                      <div class="cusInfoContent" >
                        @if($claim->status >= 1 && $claim->status <> 3 )
                          <a href="{{ route('claim.cancelClaims', ['id'=>$claim->id] ) }}" onclick="return confilmCancel();" >キャンセル</a> <br>
                        @endif
                        @if($claim->status == 0)
                        <a href="{{ route('claim.deleteClaims', ['id'=>$claim->id] ) }}" onclick="return confilmDelete();">請求データを削除</a> <br>
                        @endif
                      </div>
                    </div>
                    <div class="cusInfoRow" >
                      <div class="cusInfoTitleMail" >請求メール送付</div>
                      <div class="cusInfoContentMail" >
                        <form action="{{route('claim.sendRequestClaimMail',['id'=>$claim->id ] )}}" method="POST" >
                          @csrf
                          件名　:　{{ $claim->title }} のご請求につきまして
                          <textarea class="formInput mailformInput" name="text" >@include('user.include_requestClaimMail')</textarea>
                          ※メールを送付すると請求中になります。<br>
                          <button class="btn btn-outline-success my-2 my-sm-0" type="submit" onclick="return confirmSendMail();">送信する</button>
                        </form>
                      </div>
                    </div>
                    <div class="cusInfoRow" >
                      <div class="cusInfoTitle" >ステータス</div>
                      <div class="cusInfoContent" >
                        <form action="{{ route('claim.completePaidClaim', ['id'=>$claim->id] ) }}" method="post" >
                          @csrf
                          売上計上日<input type="date" name="complete_date" >
                          <button class="btn btn-outline-success my-2 my-sm-0" type="submit" onclick="return confirmCompletePaidClaim();">入金済みにする</button>
                      </form>
                    </div>
                  </div>
                  @endif

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
    function confirmCompletePaidClaim(){
        var result = window.confirm('ステータスを【入金済み】にします\n入金の確認を終えていますか？');
        if( result ) return true; return false;
    }
    function confirmSendMail(){
        var result = window.confirm('インストラクターに請求メールを送信しますか？\nメール送信後はステータスが【請求中】になります');
        if( result ) return true; return false;
    }
    function confilmCancel(){
        var result = window.confirm('この請求をキャンセルしますか？\nこの操作は取り消せません');
        if( result ) return true; return false;
    }
    function confilmDelete(){
        var result = window.confirm('この請求情報を削除しますか？\nこの操作は取り消せません');
        if( result ) return true; return false;
    }
</script>
