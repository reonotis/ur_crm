@extends('email_forms.sendMailForm')

@section('mail_title','インストラクター規約同意依頼メール' )

@section('text' )
<form method="post" action="{{ route('admin.sendmailRegistrRequest',['id' => $customer->id ]) }}" class="form-inline my-2 my-lg-0">
@csrf
<input type="hidden" name="title" value="インストラクター規約同意依頼メール" >
<textarea name="text" class="formInput mailformInput">
<?= $customer->name ?> 様
この度はインストラクター養成講座の修了おめでとうございます。

今後、協会へのインストラクター登録を行うために
このあとお客様に送付されます、クラウドサインからのメールをご確認ください。
メール内部のURLを確認し、規約への同意をお願い致します。

規約への同意が認められた後に、年会費 {{ number_format($annualFee) }}円 のお支払いをお願いいたします。
※振込の詳細は別途お送りいたします。

ご不明点がある場合は下記メールアドレスまで直接お問い合わせください。
email : info@paralymbics.jp

引き続きよろしくお願いいたします。

@include('email_forms.footer')
</textarea>
<button class="btn btn-outline-success my-2 my-sm-0" type="submit" onclick="return confirmSendMail();" >送信する</button>
</form>
@endsection

