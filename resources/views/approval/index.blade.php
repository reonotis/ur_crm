@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

    <h3>申請関係一覧
</h3>

        <div class="LeftBOX" style="width:100%;">
            <!-- <a href="">
                申請済みの講座を確認する
            </a> -->
            <!-- <button class="btn btn-outline-success my-2 my-sm-0 BOXin" type="submit">申請する</button> -->
        </div>


        <div class="coursesErea" >
            <h4>申請中講座一覧</h4>
            <table class="scheduleListTable">
                <thead>
                    <tr>
                        <th>コース名</th>
                        <th>開講日</th>
                        <th>インストラクター</th>
                        <th>料金</th>
                        <th>状態</th>
                        <th>確認</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courseSchedules as $courseSchedule)
                        <tr>
                            <td>{{ $courseSchedule->course_name}}</td>
                            <td>{{ $courseSchedule->date->format('Y年m月d日 H:i') }}</td>
                            <td>{{ $courseSchedule->name }}</td>
                            <td>{{ number_format($courseSchedule->price) }}円</td>
                            <td>{{ $courseSchedule->approval_name }}</td>
                            <td>
                                <a href="{{route('approval.confilm', ['id' => $courseSchedule->id ] )}}">確認</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>
<?php
// dd($courseSchedules);
?>
@endsection


