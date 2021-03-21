@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">インストラクターお湯性講座申請確認</div>
                <div class="card-body">
                        <table class="customerSearchTable">
                            <tr>
                                <th>実施コース</th>
                                <td>{{$CST -> course_name }}</td>
                            </tr>
                            <tr>
                                <th>料金</th>
                                <td>{{$CST -> price }}円</td>
                            </tr>
                            <tr>
                                <th>日程</th>
                                <td>
                                    1回目　{{$CSTL -> date1 ->format('Y年m月d日') }}　{{ date('H:i', strtotime($CSTL->time1)) }}～<br>
                                    2回目　{{$CSTL -> date2 ->format('Y年m月d日') }}　{{ date('H:i', strtotime($CSTL->time2)) }}～<br>
                                    3回目　{{$CSTL -> date3 ->format('Y年m月d日') }}　{{ date('H:i', strtotime($CSTL->time3)) }}～<br>
                                    4回目　{{$CSTL -> date4 ->format('Y年m月d日') }}　{{ date('H:i', strtotime($CSTL->time4)) }}～<br>
                                    5回目　{{$CSTL -> date5 ->format('Y年m月d日') }}　{{ date('H:i', strtotime($CSTL->time5)) }}～<br>
                                </td>
                            </tr>
                            <tr>
                                <th>エリア</th>
                                <td>
                                    {{$CST -> erea }}
                                </td>
                            </tr>
                            <tr>
                                <th>会場</th>
                                <td>{{$CST -> venue }}</td>
                            </tr>
                            <tr>
                                <th>特記事項</th>
                                <td>{{$CST -> notices }}</td>
                            </tr>
                            <tr>
                                <th>詳細</th>
                                <td>{!! nl2br(e($CST -> comment)) !!}</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <span class="support" id="support" >上記の内容で申請しても宜しいでしょうか？</span><br>
                                    <form method="GET" action="{{route('courseSchedule.intrRegister')}}"class=”form-inline”>
                                        <button class="btn btn-outline-secondary" type="button" onClick="history.back()">戻る</button>
                                        <button class="btn btn-outline-success" >申請する</button>
                                    </form>
                                </td>
                            </tr>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// dd($CST, $CSTL);
?>

<script>
</script>
<style>
    th{
        width:120px;
    }
</style>

@endsection


