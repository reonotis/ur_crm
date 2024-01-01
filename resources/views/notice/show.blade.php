@extends('layouts.app')
@section('breadcrumb')
    <ol>
        <li><a href="{{ route('home') }}">ホーム</a></li>
    </ol>
@endsection
<link href="{{ asset('css/notice.css') }}?<?= date('Ymdhi') ?>" rel="stylesheet">
@section('pageTitle', 'お知らせ詳細')

@section('content')
    <div class="notice-contents">
        <div class="notice-title">
            {{ $notice->title }}
        </div>
        <div class="notice-comment">
            <div class="">{!! nl2br(e($notice->comment)) !!}</div>
        </div>
    </div>
@endsection


