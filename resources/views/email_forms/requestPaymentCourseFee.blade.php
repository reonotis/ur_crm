@extends('email_forms.sendMailForm')

@section('mail_title','入金依頼メール	' )

@section('text' )
<form method="post" action="{{ route('admin.sendmailPaymentCourseFee', ['id'=>$customer->id]) }}" class="form-inline my-2 my-lg-0">
@csrf
<input type="hidden" name="" value="" >
<input type="date" name="dayLimit" value="<?= $dayLimit ?>" >
<textarea name="text" class="formInput mailformInput">
{{ $customer->name }} 様
コースへのお申込み誠にありがとうございます。

下記をご確認いただき、指定期日までにご入金ください。
振込期日 : ###limitDay###
----------------------------------------------
■銀行振り込みの場合



----------------------------------------------
■paypal入金の場合



----------------------------------------------

ご不明点がある場合は下記メールアドレスまで直接お問い合わせください。
email : info@paralymbics.jp

引き続きよろしくお願いいたします。
</textarea>
<button class="btn btn-outline-success my-2 my-sm-0" type="submit" >送信する</button>
</form>
@endsection

