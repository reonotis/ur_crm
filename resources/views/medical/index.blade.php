@extends('layouts.medical')
@section('pageTitle', 'カルテ登録案内')

@section('content')
    <div class="row justify-content-center">
        <div class="flashArea" >
            @include('layouts.flashMessage')
        </div>
        <div class="medical-contents-area" >
            <div class="" >
                <p>お客様の携帯電話等の端末で入力していただく場合は、下記QRコードを読み込んでいただく様、お客様にお伝えください</p>
                <br>
                <div class="" style="width: 250px;margin: 0 auto;" >
                    {{ QrCode::size(250)->generate($createURL) }}
                </div>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <p>お客様に端末を渡す場合は下記を押下してください</p>
                <a href="{{ $createURL}}" >リンクはこちら</a>
                <br>
                <br>
                <br>
                <br>
            </div>
        </div>
    </div>
</div>
@endsection
