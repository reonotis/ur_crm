@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="fullWidth">
        <button class="btn btn-outline-dark btn-sm" type="button" onClick="history.back()">戻る</button>
    </div>

    <h3>開催コース詳細</h3>

        <div class="coursesErea" >
            <h4>参加者のスケジュール</h4>
            <table class="customerSearchTable">
                <tbody>
                    <tr>
                        <th>顧客名</th>
                        <td>{{$customerSchedule->name}} 様</td>
                    </tr>
                    <tr>
                        <th>会員番号</th>
                        <td>{{$customerSchedule->course_name}}</td>
                    </tr>
                    <tr>
                        <th>実施日時 変更前</th>
                        <td>{{$customerSchedule->date_time->format('Y年 m月 d日 H:i')}}</td>
                    </tr>
                    <form action="{{ route('course_detail.changeCourseScheduleDataTime',['id'=>$customerSchedule->id ]) }}" method="post" >
                        @csrf
                        <tr>
                            <th>実施日時 変更後</th>
                            <td><input type="datetime-local" class="formInput inputDatatimeLocal" name="date" value="<?= $customerSchedule->date_time->format('Y-m-d') ."T". $customerSchedule->date_time->format('H:i') ?>" required ></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="btn btn-outline-success" type="submit" onclick="return confirmChangeScheduleDataTime();" >検索する</button>
                            </td>
                        </tr>
                    </form>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php
    // dd( $customerSchedule);
    // var_dump( $customerSchedule);
?>

@endsection

<script>
    function confirmChangeScheduleDataTime() {
        //ret変数に確認ダイアログの結果を代入する。
        result = window.confirm('このスケジュールの時間を変更します。\n宜しいですか？');
        if( result ) return true; return false;
    }
</script>