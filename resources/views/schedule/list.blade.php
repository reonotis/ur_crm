@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
<div class="coursesErea" >
        <h4>スケジュール</h4>
        <form action="" method="{{route('schedule.list')}}">
            <div class="inputYearMonths">
                <input type="month" name="month" class="formInput inputYearMonth" value="<?= $month ?>" >
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">更新</button>
            </div>
        </form>

        <table class="calendarTable">
            <tr>
                <th>日付</th>
                <th>曜日</th>
                <th>スケジュール (申し込み人数)</th>
            </tr>
            @foreach ($monthData as $dayData)
                <tr <?php if($dayData['week'] == 'Sun'){
                    echo " class='SunRows' " ;
                }else if($dayData['week'] == 'Sat'){
                    echo " class='SatRows' " ;

                } 

                ?> >
                    <td>{{ $dayData['date'] }}</td>
                    <td>{{ $dayData['week'] }}</td>
                    <td>
                        <?php
                            if(isset($dayData['schedules'])){
                                foreach($dayData['schedules'] as $schedule){
                                    echo $schedule->course_name ."　" . $schedule->howMany . "回目　(". $schedule->NINZUU . "名)<br>";
                                }
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


