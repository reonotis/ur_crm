@extends('layouts.app')
@section('pageTitle', '警告')

@section('content')
    <div class="error-contents">
        <div class="error-message">
            {!! nl2br(e($errorData['errorMessage'])) !!}
        </div>
        <div class="error-code">
            エラーコード : {{ $errorData['errorCode'] }}
        </div>
    </div>
@endsection
