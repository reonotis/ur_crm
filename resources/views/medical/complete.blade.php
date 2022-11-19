@extends('layouts.medical')
@section('pageTitle', 'カルテ登録案内')

@section('content')
    <div class="row justify-content-center">
        <div class="flashArea" >
            @include('layouts.flashMessage')
        </div>
        <div class="medical-contents-area" >
            <div class="" >
                {{ $customer->f_name . "   " .$customer->l_name }} 様<br>
                <br>
                カルテの登録が完了しましたので<br>
                スタッフにお声掛けください<br>
                <br>
                <input type="submit" onclick="window.close();" value="ウィンドウを閉じる" class="register-btn" >
                <br>
            </div>
        </div>
    </div>
</div>
@endsection
