@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
<div class="coursesErea" >
        <form action="" method="{{route('schedule.list')}}">
            <div class="inputYearMonths">
                <a href="{{ route('schedule.list', ['month' => $monthList[0]] ) }}" class="btn btn-outline-success btn-sm" ><< 前月</a>
                <input type="month" name="month" class="formInput inputYearMonth" value="<?= $monthList[1] ?>" onchange="this.form.submit();" >
                <!-- <button class="btn btn-outline-success my-2 my-sm-0" type="submit">更新</button> -->
                <a href="{{ route('schedule.list', ['month' => $monthList[2]] ) }}" class="btn btn-outline-success btn-sm" >翌月 >></a>
                
            </div>
        </form>
<?php
$time_value = $monthList[1] .'-01';
$month = date('Y年m月',strtotime($time_value));
?>
        <h4><?= $month ?>のスケジュール</h4>
        <table class="calendarTable">
            <tr>
                <th>日付</th>
                <th>曜日</th>
                <th>スケジュール　(申し込み人数)</th>
            </tr>
            @foreach ($monthData as $dayData)
                <tr
                    <?php if($dayData['week'] == 'Sun'){
                                echo " class='SunRows' " ;
                            }else if($dayData['week'] == 'Sat'){
                                echo " class='SatRows' " ;
                            }
                    ?>
                >
                    <td>{{ $dayData['date'] }}</td>
                    <td>{{ $dayData['week'] }}</td>
                    <td>
                        <?php
                            if(isset($dayData['schedules'])){
                                foreach($dayData['schedules'] as $schedule){  ?>
                                    <a href="{{ route('course_detail.display', ['id' => $schedule['id'] ]) }}" >
                                        <?= $schedule['time'] ."～　". $schedule['course_name'] ?>　
                                        <?php if($schedule['name']) echo $schedule['name']."　"; ?>
                                        ( <?= $schedule['NINZUU'] ?> )
                                    </a><br>
                                <?php
                                }
                            }else{
                                echo"　";
                            }
                        ?>
                    </td>
                </tr>
            @endforeach
        </table>
</div>

    </div>
</div>
@endsection


