@extends('layouts.app')

@section('content')

@include('setting.navigation')

<div class="noticeSection" >
    <div class="noticeArea" >
        <form method="POST" action="{{ route('setting.noticeRegister') }}">
            @csrf
            <table class="tableClass_012" >
                <tr>
                    <th>お知らせタイトル</th>
                    <td>{{ $notice['title'] }}</td>
                </tr>
                <tr>
                    <th>内容</th>
                    <td>{!! nl2br(e($notice['comment'])) !!}</td>
                </tr>
            </table>

            <button class="button edit" type="submit">登録</button>
        </form>
    </div>
</div>

@endsection
