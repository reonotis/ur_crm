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
                            <td>{{ $courses->course_name }}</td>
                        </tr>
                        <tr>
                            <th>料金</th>
                            <td>{{ number_format($request -> price) }}円</td>
                        </tr>
                        <tr>
                            <th>実施日時</th>
                            <td>
                                {{date('Y年m月d日', strtotime($request -> date)) }}　{{ date('H:i', strtotime($request->time)) }}～
                            </td>
                        </tr>
                        <tr>
                            <th>エリア</th>
                            <td>
                                {{$request -> erea }}
                            </td>
                        </tr>
                        <tr>
                            <th>会場</th>
                            <td>{{$request -> venue }}</td>
                        </tr>
                        <tr>
                            <th>特記事項</th>
                            <td>{{$request -> notices }}</td>
                        </tr>
                        <tr>
                            <th>詳細</th>
                            <td>{!! nl2br(e($request -> comment)) !!}</td>
                        </tr>
                        <tr>
                            <th>公開期間</th>
                            <td>{{date('Y年m月d日 H:i', strtotime($request -> open_start_day)) }}　～　{{date('Y年m月d日 H:i', strtotime($request -> open_finish_day)) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span class="support" id="support" >上記の内容で申請しても宜しいでしょうか？</span>
                                <form method="post" action="{{route('courseSchedule.paraStore')}}" class="form-inline my-2 my-lg-0">
                                {{csrf_field()}}
                                    <button class="btn btn-outline-secondary" type="button" onClick="history.back()">戻る</button>
                                    <input type="hidden" name="price" value="<?= $request->price ?>" >
                                    <input type="hidden" name="date" value="<?= $request->date ?>" >
                                    <input type="hidden" name="course_id" value="<?= $request->course_id ?>" >
                                    <input type="hidden" name="time" value="<?= $request->time ?>" >
                                    <input type="hidden" name="erea" value="<?= $request->erea ?>" >
                                    <input type="hidden" name="venue" value="<?= $request->venue ?>" >
                                    <input type="hidden" name="notices" value="<?= $request->notices ?>" >
                                    <input type="hidden" name="comment" value="<?= $request->comment ?>" >
                                    <input type="hidden" name="open_start_day" value="<?= $request->open_start_day ?>" >
                                    <input type="hidden" name="open_finish_day" value="<?= $request->open_finish_day ?>" >
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
    // dd($request);
?>

<script>
</script>
<style>
    th{
        width:120px;
    }
</style>

@endsection


