@extends('layouts.app')

@section('content')

@include('setting.navigation')

<div class="noticeSection" >
    <div class="noticeArea" >
        <form method="POST" action="{{ route('setting.noticeRegisterConfirm') }}">
            @csrf
            <table class="tableClass_012" >
                <tr>
                    <th>お知らせタイトル</th>
                    <td><input type="text" name="title" value="{{ old('title') }}" class="formInput"></td>
                </tr>
                <tr>
                    <th>内容</th>
                    <td>
                        <textarea name="comment" class="formInput" >{{ old('comment') }}</textarea>
                    </td>
                </tr>
            </table>

            <button class="button edit" type="submit">確認</button>
        </form>
    </div>
</div>

@endsection
