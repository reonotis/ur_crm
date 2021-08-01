@extends('layouts.medical_record')

@section('content')
<div class="formArea" >
    <p class="supportMessage" >登録が完了しました。</p>
    <p class="supportMessage" >お近くのスタッフにお伝えください</p>
    <br>
    <br>
    <br>
    <p class="supportMessage2" >staffは下記ボタンを押してください</p>
    <div class="itemsRow" >
        <a href="{{route('medical_record', ['id' => $shop_id ])}}" class="button"  >登録画面に戻る</a>
    </div>
</div>



@endsection
