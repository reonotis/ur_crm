<!DOCTYPE html>
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}?<?= date('Ymdhi') ?>" rel="stylesheet">
        <link href="{{ asset('css/common.css') }}?<?= date('Ymdhi') ?>" rel="stylesheet">
        <link href="{{ asset('css/visitHistory.css') }}?<?= date('Ymdhi') ?>" rel="stylesheet">

    </head>

    <body>
        @auth
            <main id="main" class="main flex" >
                @include('layouts.sideMenu')
                <div class="meinContents sideMenuOpen" >
                    <div class="header" >
                        @include('layouts.header')
                    </div>
                    <div class="pageTitle" >
                        @yield('pageTitle')
                    </div>
                    <div class="flashArea" >
                        @include('layouts.flashMessage')
                    </div>
                    <div class="contentsArea" >
                        @yield('content')
                    </div>
                </div>
            </main>

            <script src="{{ asset('js/common.js') }}?<?= date('Ymdhi') ?>"></script>
            <script src="{{ asset('js/visitHistory.js') }}?<?= date('Ymdhi') ?>"></script>
        @endauth
    </body>
</html>
