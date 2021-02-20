
<?php

// use App\Http?

    $session_all = Session::all();

    
    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
    }
    $auths = Auth::user();
    // console_log( $session_all );
?>

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-1.8.3.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.js"></script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}?<?= date('Ymdhi') ?>" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}?<?= date('Ymdhi') ?>" rel="stylesheet">

    <!-- <link rel="shortcut icon" href="../../../../img/logo/Reonotis01-150x150.png"> -->

</head>









<body>

@guest

@else
    <navi>
        <div class="logo">
            <h1><a href="/home">Home</a></h1>
        </div>

        <div class="menu">
            <div class="bar bar1"></div>
            <div class="bar bar2"></div>
            <div class="bar bar3"></div>
        </div>

        <ul class="navi-links">
            <li><a href="{{route('client.index')}}">日報</a></li>
            <li><a href="{{route('customer.index')}}">顧客</a></li>
            <li><a href="{{route('user.index')}}">イントラ一覧</a></li>
            <!-- <li><a href="">所有企業</a></li> -->
            <li><a href="">受注履歴</a></li>
            <li><a href="">行動履歴</a></li>
            <li><a href="">設定</a></li>
            <li>
                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('ログアウト') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>

    </navi>


@endguest
    <main id="main">
        <div id="main-in">
            <div id="header">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/home') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    @guest
                    @else
                    ようこそ【{{$auths->name}}】さん
                    @endguest
                </div>
            </div>

            <div id="contents">
                @yield('content')
            </div>
        </div><!-- /#main-in -->
    </main>
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
