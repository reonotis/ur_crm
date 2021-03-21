@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">インストラクター養成講座詳細</div>
                <div class="card-body">
                    <table class="customerSearchTable">
                        <tr>
                            <th>実施コース</th>
                            <td>{{ $intr_schedule->course_title }}</td>
                        </tr>
                        <tr>
                            <th>料金</th>
                            <td>{{ number_format($intr_course->price) }}円</td>
                        </tr>
                        <tr>
                            <th>実施日時</th>
                        </tr>
                        <tr>
                            <th>エリア</th>
                            <td>{{ $intr_course->erea }}</td>
                        </tr>
                        <tr>
                            <th>会場</th>
                            <td>{{ $intr_course->venue }}</td>
                        </tr>
                        <tr>
                            <th>特記事項</th>
                            <td>{{ $intr_course->notices }}</td>
                        </tr>
                        <tr>
                            <th>詳細</th>
                            <td>{!! nl2br(e($intr_course -> comment)) !!}</td>
                        </tr>
                        <tr>
                            <th>状態</th>
                            <td>{{ $intr_course->approval_name }}</td>
                        </tr>
                        @if($intr_course->approval_name == 0 || $intr_course->approval_name == 1 )
                        <tr>
                            <td colspan="2">
                                <a href="{{route('courseSchedule.intrDelete', ['id' => $intr_course->id ])}}">
                                    <button class="btn btn-outline-danger" onClick="return confilmDelete()">申請を中止して削除する</button>
                                </a>
                                <a href="">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">申請内容を修正する</button>
                                </a>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// dd($CST);
?>

<script>
function confilmDelete(){
    var result = window.confirm('本当にこのスケジュールを削除しますか？\nこの操作は取り消せません。');
    if( result ) {
        return true;
    } else {
        console.log('キャンセルがクリックされました');
        return false;
    }
}
</script>
<style>
    th{
        width:120px;
    }
</style>

@endsection


