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
別途お客様に送付されます、クラウドサインからのメールをご確認ください。
メール内部のURLを確認し、規約への同意をお願い致します。

規約への同意が認められた後に、下記料金をご請求致します。
認定料金 :　　　　　　{{ number_format($claims['certificationFee']) }}円
ライセンス維持費 :　　{{ number_format($claims['licenseFee']) }}円
ライセンス維持期間 :　{{ date('Y年m月d日',strtotime($claims['licenseStartDay'])) . "　～　" . date('Y年m月d日',strtotime($claims['licenseFinishDay'])) }}

※上記お振込みいただく金額等は規約に同していただく時期によって変動する場合があるため、利用規約同意後に再度正確な詳細情報をお送りいたします。

引き続きよろしくお願いいたします。

@include('email_forms.footer')
</textarea>
<button class="btn btn-outline-success my-2 my-sm-0" type="submit" onclick="return confirmSendMail();" >送信する</button>
</form>
@endsection

