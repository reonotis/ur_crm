@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">開催講座一覧</div>
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
                            <th>実施日</th>
                            <td>
                                {{$CST -> date ->format('Y年m月d日') }}　{{ date('H:i', strtotime($CST->time)) }}～
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
                            <td>{{$CST -> comment }}</td>
                        </tr>
                        <tr>
                            <th>公開期間</th>
                            <td>{{$CST->open_start_day->format('Y/m/d H:i') }}　～　{{$CST->open_finish_day->format('Y/m/d H:i') }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span class="support" id="support" >上記の内容で申請しても宜しいでしょうか？</span>
                                <form method="GET" action="{{route('courseSchedule.paraStore')}}" class="form-inline my-2 my-lg-0">
                                    <button class="btn btn-outline-secondary" type="button" onClick="history.back()">戻る</button>
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" >申請する</button>
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
// dd($CST);
?>

<script>
</script>
<style>
    th{
        width:120px;
    }
</style>

@endsection


