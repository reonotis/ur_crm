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
                            <td>{{ $intr_course->course_name }}</td>
                        </tr>
                        <tr>
                            <th>料金</th>
                            <td>{{ number_format($intr_course->price) }}円</td>
                        </tr>
                        <tr>
                            <th>実施日時</th>
                            <td>{{ $intr_course->date->format('Y年m月d日') . "　" . date('H:i', strtotime($intr_course->time)) }}～</td>
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
                        @if($ApprovalComments)
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
                            <td>{{ $intr_course->approval_name }}</td>
                        </tr>
                        @if($intr_course->approval_name == 0 || $intr_course->approval_name == 1 )
                            <tr>
                                <td colspan="2">
                                    @if($intr_course->approval_flg < 5)
                                        <a href="{{route('courseSchedule.intrDelete', ['id' => $intr_course->id ])}}">
                                            <button class="btn btn-outline-danger" onClick="return confilmDelete()">申請を中止して削除する</button>
                                        </a>
                                        <a href="{{route('courseSchedule.paraEdit', ['id' => $intr_course->id ])}}">
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


