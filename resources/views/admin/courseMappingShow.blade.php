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
                    <div class="cusInfoContent" >{{ $CCMs->name }} 様</div>
                  </div>
                  <div class="cusInfoRow" >
                    <div class="cusInfoTitle" >申込コース</div>
                    <div class="cusInfoContent" >{{ $CCMs->course_name }}</div>
                  </div>
                  <div class="cusInfoRow" >
                    <div class="cusInfoTitle" >担当インストラクター</div>
                    <div class="cusInfoContent" >{{ $CCMs->intr_name }}</div>
                  </div>
                  <div class="cusInfoRow" >
                    <div class="cusInfoTitle" >講座開始日</div>
                    <div class="cusInfoContent" >{{ $firstDate->format('Y年 m月 d日') }}</div>
                  </div>
                  <div class="cusInfoRow" >
                    <div class="cusInfoTitle" >請求金額</div>
                    <div class="cusInfoContent" >{{ number_format($claim->price) }} 円</div>
                  </div>
                  <div class="cusInfoRow" >
                    <div class="cusInfoTitle" >ステータス</div>
                    <div class="cusInfoContent" >{{ $claim->statusName }}</div>
                  </div>

                  @if($claim->status == 5 )
                  <div class="cusInfoRow" >
                    <div class="cusInfoTitle" >売上計上日</div>
                    <div class="cusInfoContent" >{{ $claim->complete_date }}</div>
                  </div>
                  @endif
                  @if($claim->status==0 || $claim->status==1)
                    <div class="cusInfoRow">
                        <div class="cusInfoTitle">入金確認</div>
                        <div class="cusInfoContent">
                          <form action="{{ route('admin.completeCourseFee',['id'=>$CCMs->id]) }}" method="post">
                            @csrf
                            売上計上日<input type="date" name="complete_date">
                            <button class="btn btn-outline-success" type="submit" onclick="return confirmCompletePaidClaim();">入金済みにする</button>
                        </form>
                      </div>
                    </div>
                  @endif
                  @if( $claim->status < 3 )
                    <div class="cusInfoRow" >
                      <div class="cusInfoTitle" >キャンセル処理</div>
                      <div class="cusInfoContent" >
                        <a href="{{ route('admin.cancelCourseMapping',['id'=>$CCMs->id]) }}" onclick="return confirmCancelCourse();" >この申し込みをキャンセル扱いにする</a>
                      </div>
                    </div>
                    <div class="cusInfoRow" >
                      <div class="cusInfoTitleMail" >請求メール送付</div>
                      <div class="cusInfoContentMail" >
                        <form action="{{ route('admin.sendmailPaymentCourseFee',['id'=>$CCMs->id]) }}" method="POST" >
                          @csrf

                          <div class="cusInfoRow">
                            <div class="cusInfoContent">件名　：　{{ $CCMs->course_name }}受講料のご請求につきまして</div>
                          </div>
                          <div class="cusInfoRow">
                            <div class="cusInfoContent">振込金額　：　<input type="number" name="price" value="{{$claim->price}}" class="formInput inputPrice" >円</div>
                          </div>
                          <div class="cusInfoRow">
                            <div class="cusInfoContent">振込期日　：　<input type="date" name="dayLimit" value="<?php if($claim->limit_date) echo $claim->limit_date->format('Y-m-d') ?>" class="formInput inputDate" ></div>
                          </div>
                          <textarea class="formInput mailformInput" name="text" >@include('admin.include_requestClaimMail')</textarea>
                          <!-- <textarea class="formInput mailformInput" name="text" ></textarea> -->
                          ※メールを送付すると請求中になります。<br>
                          <button class="btn btn-outline-success" type="submit" onclick="return confirmSendMail();">メールを送信する</button>
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
// dd($CCMs);
?>
@endsection

<script>
    function confirmCompletePaidClaim(){
        var result = window.confirm('ステータスを【入金済み】にします\n入金の確認を終えていますか？');
        if( result ) return true; return false;
    }
    function confirmSendMail(){
        var result = window.confirm('この内容でお客様に請求メールを送信しますか？\nメール送信後はステータスが【請求中】になります');
        if( result ) return true; return false;
    }
    function confirmCancelCourse(){
        var result = window.confirm('このコースへの申し込みをキャンセル扱いにしますか？\nこの操作は取り消せません');
        if( result ) return true; return false;
    }
</script>
