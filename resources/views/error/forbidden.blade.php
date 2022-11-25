@extends('layouts.app')
@section('pageTitle', '禁止された操作です')

@section('content')
    <div class="error-contents">
        <div class="error-message">
            {!! nl2br(e($errorMessage)) !!}
        </div>
    </div>
@endsection
