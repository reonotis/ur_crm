@extends('layouts.setting')
@section('pageTitle', 'システム情報')

@section('content')
    <div class="container">
        @php
            echo phpinfo();
        @endphp
    </div>
@endsection
