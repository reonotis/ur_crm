@extends('layouts.app')
@section('pageTitle', '操作店舗選択画面')

@section('content')
    <div class="" >
        <p >操作する店舗を選択して下さい</p>
        <div class="flex" >
            @foreach($selectableShops AS $shop)
                <a href="{{ route('shop.selected', ['shop' => $shop->id]) }}" >
                    <div class="selectShop" >
                        {{ $shop->shop_name }}
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection

