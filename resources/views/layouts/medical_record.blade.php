
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>	<!-- 住所入力 -->



    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}?<?= date('Ymdhi') ?>" rel="stylesheet">
    <link href="{{ asset('css/medical_record.css') }}?<?= date('Ymdhi') ?>" rel="stylesheet">

    <!-- <link rel="shortcut icon" href="../../../../img/logo/Reonotis01-150x150.png"> -->
</head>









<body>




<script type="text/javascript">
    // {{--成功時--}}
    @if (session('msg_success'))
        $(function () {
            toastr.success('{{ session('msg_success') }}');
        });
    @endif

    // {{--失敗時--}}
    @if (session('msg_danger'))
        $(function () {
            toastr.warning('{{ session('msg_danger') }}');
        });
    @endif
</script>




    <main id="main">
        <div id="main-in">
            <div id="header">
                いらっしゃいませ
            </div>
            <div id="contents">
                @yield('content')
            </div>
        </div><!-- /#main-in -->
    </main>
    <script src="{{ asset('assets/js/medical_record.js') }}"></script>
</body>
</html>