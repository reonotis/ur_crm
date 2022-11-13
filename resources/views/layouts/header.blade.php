
<?php
    $auths = Auth::user();
?>

<div class="headerContent" >
    <div class="selectedShopArea" >
        @if(!empty(session()->get(App\Consts\SessionConst::SELECTED_SHOP)->shop_name))
            <span class="selectedShopName" >
                {{ session()->get(App\Consts\SessionConst::SELECTED_SHOP)->shop_name }}
            </span>
            @if(!empty(session()->get(App\Consts\SessionConst::SELECTABLE_SHOP)))
                <a href="{{ route('shop.deselect') }}"  class="deselect" >他の店舗を選択する</a>
            @endif
        @endif
    </div>
    <div class="nameArea">
        {{$auths->name}}　
        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('ログアウト') }}</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

</div>

