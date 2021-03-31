@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="fullWidth">
            <button class="btn btn-light btn-sm" type="button" onClick="history.back()">戻る</button>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">パラリンビクス講座 申請内容詳細</div>
                <div class="card-body">
                    <table class="customerSearchTable">
                        <tr>
                            <th>講座</th>
                            <td>{{ $para_course->course_name }}</td>
                        </tr>
                        <tr>
                            <th>料金</th>
                            <td>{{ number_format($para_course->price) }}円</td>
                        </tr>
                        <tr>
                            <th>実施日時</th>
                            <td>{{ $para_course->date->format('Y年m月d日') . "　" . date('H:i', strtotime($para_course->time)) }}～</td>
                        </tr>
                        <tr>
                            <th>エリア</th>
                            <td>{{ $para_course->erea }}</td>
                        </tr>
                        <tr>
                            <th>会場</th>
                            <td>{{ $para_course->venue }}</td>
                        </tr>
                        <tr>
                            <th>特記事項</th>
                            <td>{{ $para_course->notices }}</td>
                        </tr>
                        <tr>
                            <th>詳細</th>
                            <td>{!! nl2br(e($para_course -> comment)) !!}</td>
                        </tr>
                        <tr>
                            <th>公開期間</th>
                            <td>{{$para_course->open_start_day->format('Y/m/d H:i') }}　～　{{$para_course->open_finish_day->format('Y/m/d H:i') }}</td>
                        </tr>
                        @if(!isset($ApprovalComments))
                            <tr>
                                <th>協会からのコメント</th>
                                <td>
                                    @foreach($ApprovalComments as $ApprovalComment )
                                        {{ $ApprovalComment->created_at->format('Y/m/d H:i ') }}　
                                        {{ $ApprovalComment->comment }}<br>
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th>状態</th>
                            <td>{{ $para_course->approval_name }}</td>
                        </tr>
                        @if($para_course->approval_name == 0 || $para_course->approval_name == 1 )
                            <tr>
                                <td colspan="2">
                                    @if($para_course->approval_flg < 5)
                                        <a href="{{route('courseSchedule.intrDelete', ['id' => $para_course->id ])}}">
                                            <button class="btn btn-outline-danger" onClick="return confilmDelete()">申請を中止して削除する</button>
                                        </a>
                                        <a href="{{route('courseSchedule.paraEdit', ['id' => $para_course->id ])}}">
                                            <button class="btn btn-outline-success" >申請内容を修正する</button>
                                        </a>
                                    @endif
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
// dd($ApprovalComments);
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


