@extends('layouts.app')

@section('content')
    <div class="" >
        <p >操作する店舗を選択して下さい</p>
        <div class="flex" >
            @foreach($selectableShops AS $shop)
                <a href="{{ route('shop.selected', ['id' => $shop->id]) }}" >
                    <div class="selectShop" >
                        {{ $shop->shop_name }}
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection

