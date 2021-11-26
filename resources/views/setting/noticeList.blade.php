@extends('layouts.app')

@section('content')

@include('setting.navigation')

<div class="noticeSection" >
    <div class="noticeArea" >
        <a href="{{ route('setting.noticeCreate') }}" class="button" >お知らせを登録する</a>
        @if ($notices)
            <table class="tableClass_011" >
                <tr>
                    <th>登録日時</th>
                    <th>お知らせタイトル</th>
                    <th>お知らせ内容</th>
                    <th>既読数 / 通知数</th>
                    <th>確認</th>
                </tr>
                @foreach($notices as $notice)
                    <tr>
                        <td>{{ $notice->created_at->format('m月d日 H:i') }}</td>
                        <td>{{ $notice->title }}</td>
                        <td>{{ Str::limit($notice->comment, 20, '...') }}</td>
                        <td></td>
                        <td><a href="{{ route('setting.noticeConfirm', ['id'=>$notice->id]) }}" >確認</a></td>

                    </tr>
                @endforeach
            </table>
        @endif
    </div>
</div>

@endsection
