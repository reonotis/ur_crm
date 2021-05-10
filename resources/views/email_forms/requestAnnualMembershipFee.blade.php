@extends('email_forms.sendMailForm')

@section('mail_title','年会費入金依頼メール' )

@section('text' )
<form method="post" action="{{ route('admin.sendRequestAnnualMembershipFee',['id'=>$CCM->id]) }}" class="form-inline my-2 my-lg-0">
@csrf
<input type="hidden" name="title" value="年会費入金依頼メール" >
<div class="inputRowErea" >
  <div class="inputRowEreaTitle" >振込金額</div>
  <div class="inputUnits" >
    <input type="number" name="price" value="{{ $annualFee }}" class="formInput inputUnit" > 円
  </div>
</div>
<textarea name="text" class="formInput mailformInput">
{{ $CCM->name }} 様

インストラクターへの規約に同意されたため
{{ $CCM->name }} 様をインストラクターとして協会で登録を致します。

〇年〇月〇日～〇年〇月〇日までパラリンビクスインストラクターとしての活動を許可致します。
###price###円

つきましては、年会費をいつまでにお支払いください。

@include('email_forms.transferAccount')

ご不明点がある場合は下記メールアドレスまで直接お問い合わせください。
email : info@paralymbics.jp

引き続きよろしくお願いいたします。


@include('email_forms.footer')
</textarea>
<button class="btn btn-outline-success my-2 my-sm-0" type="submit" onclick="return confirmSendMail();" >送信する</button>
</form>
@endsection

