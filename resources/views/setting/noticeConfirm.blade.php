@extends('layouts.app')

@section('content')

@include('setting.navigation')

<div class="noticeSection" >
    <div class="noticeArea" >
        <table class="tableClass_012" >
            <tr>
                <th>お知らせタイトル</th>
                <td>{{ $notice->title }}</td>
            </tr>
            <tr>
                <th>内容</th>
                <td>{!! nl2br(e($notice['comment'])) !!}</td>
            </tr>
            <tr>
                <th>お知らせ対象者</th>
                <td></td>
            </tr>
        </table>

        <a href="{{ route('setting.noticeDelete', ['id'=>$notice->id]) }}"onclick="return confirmDeleteNotice();" ><button class="button delete" type="submit">削除する</button></a>
        ※既にこのお知らせを閲覧しているユーザーがいる場合でも削除されます。
    </div>
</div>

@endsection



<script>

    function confirmDeleteNotice(){
        var result =  confirm('このお知らせを削除しますか？\nこの操作は取り消せません ');
        return result;
    }
</script>