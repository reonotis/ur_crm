@extends('email_forms.sendMailForm')

@section('mail_title','インストラクター規約同意依頼メール' )

@section('text' )
<form method="post" action="{{ route('user.sendMailNewClaim', ['id'=>$user->id]) }}" class="form-inline my-2 my-lg-0">
@csrf

<div class="inputRowErea" >
  <div class="inputRowEreaTitle" >送信先</div>
  <div class="" >{{ $user-> name }}様</div>
</div>
<div class="inputRowErea" >
  <div class="inputRowEreaTitle" >メールタイトル</div>
  <div class="inputRowEreaValue" >
    <input type="text" name="title" value="【ご請求】認定料金について" class="formInput" >
  </div>
</div>
<div class="inputRowErea" >
  <div class="inputRowEreaTitle" >請求項目</div>
  <div class="inputRowEreaValue" >
    <input type="text" name="item_name" value="認定料金 & ライセンス維持費(YYYY年度)" class="formInput" >
  </div>
</div>
<div class="inputRowErea" >
  <div class="inputRowEreaTitle" >請求金額</div>
  <div class="inputUnits" >
    <input type="number" name="price" value="" class="formInput inputUnit" >円
  </div>
</div>

<textarea name="text" class="formInput mailformInput">
{{ $user-> name }}様

いつも大変お世話になっております。
パラリンビクス協会でございます。

下記の通りご請求致します。
〇年〇月〇日迄にお支払いの手続きをお願いいたします。

■ご請求項目 　:　###item_name###
■ご請求金額 　:　###price###円

■内訳-------------------------
認定料金 　:　　　　　　_____円
ライセンス維持費 　:　　_____円
ライセンス維持期間 　:　〇年〇月01日 ～ 〇年03月31日迄
--------------------------------


@include('email_forms.transferAccount')

@include('email_forms.footer')
</textarea>
<button class="btn btn-outline-success my-2 my-sm-0" type="submit" onclick="return confirmSendMail();" >送信する</button>
</form>
@endsection

