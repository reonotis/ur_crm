@extends('layouts.app')
@section('pageTitle', '警告')

@section('content')
    <div class="errorContents">
        <div class="errorMessage">
            {!! nl2br(e($errorData['errorMessage'])) !!}
        </div>
        <div class="errorCode">
            エラーコード : {{ $errorData['errorCode'] }}
        </div>
    </div>
{{--    <div class="flex mt-12" >--}}
{{--        <a href="javascript:history.back()" class="submit back-btn" >戻る</a>--}}
{{--    </div>--}}
@endsection
