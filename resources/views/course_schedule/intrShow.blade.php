@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="fullWidth">
            <button class="btn btn-light btn-sm" type="button" onClick="history.back()">戻る</button>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">インストラクター養成講座詳細</div>
                <div class="card-body">
                    <table class="customerSearchTable">
                        <tr>
                            <th>実施コース</th>
                            <td>{{ $intr_course->course_title }}</td>
                        </tr>
                        <tr>
                            <th>料金</th>
                            <td>{{ number_format($intr_course->price) }}円</td>
                        </tr>
                        <tr>
                            <th>実施日時</th>
                            <td>
                                1回目　{{$WPMyScheduleIntrCourses->date1->format('Y/m/d')}}　{{$WPMyScheduleIntrCourses->time1}}<br>
                                2回目　{{$WPMyScheduleIntrCourses->date2->format('Y/m/d')}}　{{$WPMyScheduleIntrCourses->time2}}<br>
                                3回目　{{$WPMyScheduleIntrCourses->date3->format('Y/m/d')}}　{{$WPMyScheduleIntrCourses->time3}}<br>
                                4回目　{{$WPMyScheduleIntrCourses->date4->format('Y/m/d')}}　{{$WPMyScheduleIntrCourses->time4}}<br>
                                5回目　{{$WPMyScheduleIntrCourses->date5->format('Y/m/d')}}　{{$WPMyScheduleIntrCourses->time5}}<br>
                                @if($WPMyScheduleIntrCourses->date6)
                                    6回目　{{$WPMyScheduleIntrCourses->date6->format('Y/m/d')}}　{{$WPMyScheduleIntrCourses->time6}}<br>
                                @endif
                                @if($WPMyScheduleIntrCourses->date7)
                                    7回目　{{$WPMyScheduleIntrCourses->date7->format('Y/m/d')}}　{{$WPMyScheduleIntrCourses->time7}}<br>
                                @endif
                                @if($WPMyScheduleIntrCourses->date8)
                                    8回目　{{$WPMyScheduleIntrCourses->date8->format('Y/m/d')}}　{{$WPMyScheduleIntrCourses->time8}}<br>
                                @endif
                                @if($WPMyScheduleIntrCourses->date9)
                                    9回目　{{$WPMyScheduleIntrCourses->date9->format('Y/m/d')}}　{{$WPMyScheduleIntrCourses->time9}}<br>
                                @endif
                                @if($WPMyScheduleIntrCourses->date10)
                                    10回目　{{$WPMyScheduleIntrCourses->date10->format('Y/m/d')}}　{{$WPMyScheduleIntrCourses->time10}}<br>
                                @endif
                            </td>
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
                        <tr>
                            <th>協会からのコメント</th>
                            <td>
                            </td>
                        </tr>
                        @if($intr_course->approval_name == 0 || $intr_course->approval_name == 1 )
                        <tr>
                            <td colspan="2">
                                @if($intr_course->approval_flg < 5)
                                    <a href="{{route('courseSchedule.intrDelete', ['id' => $intr_course->id ])}}">
                                        <button class="btn btn-outline-danger" onClick="return confilmDelete()">申請を中止して削除する</button>
                                    </a>
                                    <a href="{{route('courseSchedule.intrEdit', ['id' => $intr_course->id ])}}">
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


