@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">開催講座一覧</div>
                <div class="card-body">
                    <form method="POST" action="{{route('courseSchedule.create3')}}" class="form-inline my-2 my-lg-0">
                        @csrf
                        <table class="customerSearchTable">
                            <tr>
                                <th>実施コース</th>
                                <td>{{$CST -> course_name }}</td>
                            </tr>
                            <tr>
                                <th>コース名</th>
                                <td><input type="text" class="formInput" name="course_title" value="" placeholder="火曜日日中コース" required ></td>
                            </tr>
                            <tr>
                                <th>料金</th>
                                <td>{{$CST -> price }}円</td>
                            </tr>
                            <tr>
                                <th>実施日時</th>
                                <td>
                                    <div class="inputDateTime">1回目<input type="date" class="formInput inputUnit" name="date1" value="" required ><input type="time" class="formInput inputUnit" name="time1" value="" required ><br></div>
                                    <div class="inputDateTime">2回目<input type="date" class="formInput inputUnit" name="date2" value="" required ><input type="time" class="formInput inputUnit" name="time2" value="" required ><br></div>
                                    <div class="inputDateTime">3回目<input type="date" class="formInput inputUnit" name="date3" value="" required ><input type="time" class="formInput inputUnit" name="time3" value="" required ><br></div>
                                    <div class="inputDateTime">4回目<input type="date" class="formInput inputUnit" name="date4" value="" required ><input type="time" class="formInput inputUnit" name="time4" value="" required ><br></div>
                                    <div class="inputDateTime">5回目<input type="date" class="formInput inputUnit" name="date5" value="" required ><input type="time" class="formInput inputUnit" name="time5" value="" required ><br></div>
                                    <div class="inputDateTime">6回目<input type="date" class="formInput inputUnit" name="date6" value="" ><input type="time" class="formInput inputUnit" name="time6" value="" ><br></div>
                                    <div class="inputDateTime">7回目<input type="date" class="formInput inputUnit" name="date7" value="" ><input type="time" class="formInput inputUnit" name="time7" value="" ><br></div>
                                    <div class="inputDateTime">8回目<input type="date" class="formInput inputUnit" name="date8" value="" ><input type="time" class="formInput inputUnit" name="time8" value="" ><br></div>
                                    <div class="inputDateTime">9回目<input type="date" class="formInput inputUnit" name="date9" value="" ><input type="time" class="formInput inputUnit" name="time9" value="" ><br></div>
                                    <div class="inputDateTime">10回目<input type="date" class="formInput inputUnit" name="date10" value="" ><input type="time" class="formInput inputUnit" name="time10" value="" ><br></div>
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
                            <tr><div class="button_wrapper remodal-bg">
                                <td>
                                    <button class="btn btn-outline-secondary" onClick="history.back()">戻る</button>
                                </td>
                                <td>
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">確認</button>
                                </td>
                            </tr>
                        </table>
                    </form>
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


