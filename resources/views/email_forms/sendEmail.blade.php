@extends('email_forms.sendMailForm')

@section('mail_title','メール送信' )

@section('text' )
<form method="post" action="{{ route('user.sendMail', ['id'=>$user->id]) }}" class="form-inline my-2 my-lg-0">
@csrf

<div class="inputRowErea" >
  <div class="inputRowEreaTitle" >送信先</div>
  <div class="" >{{ $user-> name }}</div>
</div>
<div class="inputRowErea" >
  <div class="inputRowEreaTitle" >メールタイトル</div>
  <div class="inputRowEreaValue" >
    <input type="text" name="title" value="" class="formInput" placeholder="タイトル" >
  </div>
</div>
<textarea name="text" class="formInput mailformInput">
{{ $user-> name }}様

いつも大変お世話になっております。
パラリンビクス協会でございます。





@include('email_forms.footer')
</textarea>
<button class="btn btn-outline-success my-2 my-sm-0" type="submit" onclick="return confirmSendMail();" >送信する</button>
</form>
@endsection

