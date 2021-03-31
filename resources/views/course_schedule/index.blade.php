@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

    <h3>実施講座一覧</h3>



        <div class="coursesErea" >
            <h4>パラリンビクス講座
                <a href="{{route('courseSchedule.paraCreate')}}">新しくパラリンビクス講座を申請する</a>
            </h4>
            <table class="scheduleListTable">
                <thead>
                    <tr>
                        <th>コース名</th>
                        <th>実施日時</th>
                        <th>エリア</th>
                        <th>会場</th>
                        <th>料金</th>
                        <th>状態</th>
                        <th>詳細</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($para_course_schedules as $course_schedule)
                        <tr>
                            <td>{{ $course_schedule->course_name }}</td>
                            <td>{{ $course_schedule->date->format('Y年m月d日') . " 　" . date('H:i', strtotime($course_schedule->open_time)) }}</td>
                            <td>{{ $course_schedule->erea }}</td>
                            <td>{{ $course_schedule->venue }}</td>
                            <td>{{ number_format($course_schedule->price) }}円</td>
                            <td>{{ $course_schedule->approval_name }}</td>
                            <td>
                                <a href="{{route('courseSchedule.paraShow', ['id' => $course_schedule->id ] )}}">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="coursesErea" >
            <h4>インストラクター養成講座
                <a href="{{route('courseSchedule.intrCreate')}}">新しく養成講座を申請する</a>
            </h4>
            <table class="scheduleListTable">
                <thead>
                    <tr>
                        <th>コースタイトル</th>
                        <th>開講日</th>
                        <th>エリア</th>
                        <th>会場</th>
                        <th>料金</th>
                        <th>状態</th>
                        <th>詳細</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($intr_course_schedules as $intr_course_schedule)
                        <tr>
                            <td>{{ $intr_course_schedule->course_title }}</td>
                            <td>-</td>
                            <td>{{ $intr_course_schedule->erea }}</td>
                            <td>{{ $intr_course_schedule->venue }}</td>
                            <td>{{ number_format($intr_course_schedule->price) }}円</td>
                            <td>{{ $intr_course_schedule->approval_name }}</td>
                            <td>
                                <a href="{{route('courseSchedule.intrShow', ['id' => $intr_course_schedule->id ] )}}">詳細</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
<?php
// dd($intr_course_schedules);
?>


    </div>
</div>

@endsection


