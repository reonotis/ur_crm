<?php
    $routeNum = 0;
    $routeName = Route::currentRouteName();
    switch($routeName){
        case 'myPage':
            $routeNum = 1;
            break;
        case str_starts_with($routeName, 'report'):
            $routeNum = 2;
            break;
        case str_starts_with($routeName, 'data'):
            $routeNum = 3;
            break;
        case str_starts_with($routeName, 'reserve'):
            $routeNum = 4;
            break;
        case str_starts_with($routeName, 'customer'):
            $routeNum = 5;
            break;
        case str_starts_with($routeName, 'user'):
            $routeNum = 6;
            break;
        case str_starts_with($routeName, 'medical'):
            $routeNum = 7;
            break;
        case str_starts_with($routeName, 'setting'):
        case str_starts_with($routeName, 'shop_setting'):
            $routeNum = 8;
            break;
        case '':
            break;
    }

    $myShop = session()->get(SessionConst::SELECTED_SHOP);
?>

<div class="sideMenu open" >
    <div class="menuOpenButton" >>></div>
    <div class="menuContents" >
        <ul class="sideMenuUl" >
            <li><a href="{{ route('myPage') }}" class="sidebarTitle <?= ($routeNum === 1) ? "active": ""; ?>" >ホーム</a></li>
            @if (session()->get(SessionConst::SELECTED_SHOP))
                <li><a href="{{ route('report.index') }}" class="sidebarTitle <?= ($routeNum === 2) ? "active": ""; ?>" >日報</a></li>
                <li><a href="{{ route('data', ['date'=>date('Ymd')]) }}" class="sidebarTitle <?= ($routeNum === 3) ? "active": ""; ?>" >データ分析</a></li>
                {{-- <li><a href=" route('reserve')" class="sidebarTitle" >予約</a></li> --}}
                <li>
                    <div class="sidebarTitle parentMenu <?= ($routeNum === 5) ? "active open": ""; ?>" id="parentMenu_4">顧客管理</div>
                    <ul class="childMenu" id="childMenu_4" <?= ($routeNum <> 5) ? 'style="overflow: hidden; display: none;"': ""; ?> >
                        <?php $subRoute = request()->routeIs('customer.index') || request()->routeIs('customer.list') || request()->routeIs('customer.show'); ?>
                        @if ($myShop->userShopAuthorization->customer_read)
                            <li><a href="{{ route('customer.index') }}" class="sidebarTitleIn <?= ($subRoute) ? 'active': ''; ?>" >顧客確認</a></li>
                        @endif
                        @if ($myShop->userShopAuthorization->customer_create)
                            <li><a href="{{ route('customer.create') }}" class="sidebarTitleIn <?= (request()->routeIs('customer.create')) ? 'active': ''; ?>" >顧客登録</a></li>
                        @endif
                    </ul>
                </li>
                <li>
                    <div class="sidebarTitle parentMenu <?= ($routeNum === 6) ? "active open": ""; ?>" id="parentMenu_5">スタイリスト管理</div>
                    <ul class="childMenu" id="childMenu_5" <?= ($routeNum <> 6) ? 'style="overflow: hidden; display: none;"': ""; ?> >
                        <?php $subRoute = request()->routeIs('user.index') || request()->routeIs('user.show'); ?>
                        @if ($myShop->userShopAuthorization->user_read)
                            <li><a href="{{ route('user.index') }}" class="sidebarTitleIn <?= ($subRoute) ? 'active': ''; ?>" >スタイリスト確認</a></li>
                        @endif
                        @if ($myShop->userShopAuthorization->user_create)
                            <li><a href="{{ route('user.create') }}" class="sidebarTitleIn <?= (request()->routeIs('user.create')) ? 'active': ''; ?>" >スタイリスト登録</a></li>
                        @endif
                    </ul>
                </li>
                @if (session()->get(SessionConst::SELECTED_SHOP))
                    <li><a href="{{ route('medical.index', ['shop'=>session()->get(SessionConst::SELECTED_SHOP)->id]) }}" target="_blank" class="sidebarTitle <?= ($routeNum === 7) ? "active": ""; ?>" >カルテ登録</a></li>
                @endif
                <li>
                    <div class="sidebarTitle parentMenu <?= ($routeNum === 8) ? "active open": ""; ?>" id="parentMenu_7"><p class="">各種設定</p></div>
                    <ul class="childMenu" id="childMenu_7"  <?= ($routeNum <> 8) ? 'style="overflow: hidden; display: none;"': ""; ?>>
                        <li><a href="{{ route('setting.index') }}" class="sidebarTitleIn <?= (request()->routeIs('setting.*')) ? 'active': ''; ?>" >アカウント情報</a></li>
                        @if ($myShop->userShopAuthorization->shop_setting_read)
                            <li><a href="{{ route('shop_setting.index') }}" class="sidebarTitleIn <?= (request()->routeIs('shop_setting.*')) ? 'active': ''; ?>" >ショップ基本情報</a></li>
                        @endif
                        {{-- <li><a href="" class="sidebarTitleIn" >税率設定</a></li> --}}
                        {{-- <li><a href="" class="sidebarTitleIn" >メール設定</a></li> --}}
                    </ul>
                </li>
            @endif
        </ul>

    </div>
</div>

