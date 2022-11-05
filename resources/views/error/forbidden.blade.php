@extends('layouts.app')
@section('pageTitle', '禁止された操作です')

@section('content')
    <div class="errorContents">
        <div class="errorMessage">
            {!! nl2br(e($errorMessage)) !!}
        </div>
    </div>
@endsection
