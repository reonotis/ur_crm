@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

    <h3>開催コース詳細</h3>

        <div class="coursesErea" >
            <h4>コース概要</h4>
            <?= $IC->course_name ."　". $IC->course_title  ?>
<br>
<br>
<br>
<br>
            <h4>参加者のスケジュール</h4>
            <table class="scheduleListTable" >
                <tr>
                    <th>日時</th>
                    <th>内容</th>
                    <th>参加者</th>
                    <th>受講状態</th>
                </tr>
                @foreach($customer_schedules as $data)
                    <tr>
                        <td>{{ $data->date_time->format('Y-m-d　H:i～') }}</td>
                        <td>{{ $data->howMany }}回目</td>
                        <td>{{ $data->name }}</td>
                        <td>
                            <?php if($data->status){
                                echo "受講済み";
                            }else{
                                ?>
                                <a href="{{ route('course_detail.completCustomerSchedule', ['id' => $data->id ]) }}" onclick="return confirmFunction1()">受講済みにする</a>

                                <?php 
                            }
                            ?>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>


<?php
    // dd($IC, $ICS, $customer_schedules);
?>

@endsection

<script>
    function confirmFunction1() {
        //ret変数に確認ダイアログの結果を代入する。
        ret = window.confirm('このスケジュールを受講済みにします。\n宜しいですか？\nこの操作は元には戻せません');

        //確認ダイアログの結果がOKの場合外部リンクを開く
        if (ret == true){
            return true
        }else{
            return false
        }
    }
</script>