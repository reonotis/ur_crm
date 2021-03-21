@extends('layouts.app')

@section('content')
<?php
$courses_json = json_encode($courses);
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">開催講座一覧</div>
                <div class="card-body">
                    
                    <form method="POST" action="{{route('courseSchedule.create2')}}" class="form-inline my-2 my-lg-0" onSubmit="return check()">
                        @csrf
                        <table class="customerSearchTable">
                            <tr>
                                <th>実施コース</th>
                                <td>
                                    <select name="course_id" onchange="setPrice()" id="course_id">
                                        <option value="0">--選択してください--</option>
                                        <?php foreach($courses as $course) { ?>
                                            <option value="{{$course->id}}">{{$course->course_name}}</option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>料金</th>
                                <td>
                                    <div class="inputUnits">
                                        <input class="formInput inputUnit" type="number" name="price" id="price" placeholder="360000" step="100" >円
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>実施日時</th>
                                <td>
                                    <input class="formInput" type="date" name="date" id="date" min="<?php echo date('Y-m-d',strtotime("+5 day"));?>">
                                    <input class="formInput" type="time" name="time" id="time" >
                                    <span class="support" id="support" style="display:none;"></span>
                                </td>
                            </tr>
                            <tr>
                                <th>エリア</th>
                                <td>
                                    <input class="formInput" type="text" name="erea" >
                                </td>
                            </tr>
                            <tr>
                                <th>会場</th>
                                <td>
                                    <input class="formInput" type="text" name="venue" >
                                </td>
                            </tr>
                            <tr>
                                <th>特記事項</th>
                                <td><input class="formInput" type="text" name="notices" placeholder="特記事項" ></td>
                            </tr>
                            <tr>
                                <th>詳細</th>
                                <td>
                                    <textarea name="comment" placeholder="詳細" class="formInput" ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">次へ</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    window.onload = function(){
        dataTimeCheck();
    }

    function dataTimeCheck(){
        var js_array = JSON.parse('<?php echo $courses_json; ?>');
        if(course_id.value == 0){
        }else if(course_id.value == 6){
            date.disabled = true;
            time.disabled = true;
            support.innerHTML ="※パラリンビクス養成講座を実施する場合、開催日は次のページで登録してください"
            support.style.display ="block";
        }else{
            date.disabled = false;
            time.disabled = false;
            support.innerHTML =""
            support.style.display ="none";
        }
    }

    function setPrice(){
        var js_array = JSON.parse('<?php echo $courses_json; ?>');
        js_array.forEach(function( value ) {
            if(course_id.value == value.id){
                // 料金を設定
                price.value = value.price ;
            }
        });
        dataTimeCheck();
    }


    function check(){
        try {
            var error = 0 ;
            var errMSG = "" ;
            var choice_id =  course_id.value
            if(choice_id == 0){
                errMSG = errMSG  + "コースを選択して下さい。\n"
                error ++ ;
            }else if(choice_id == 6 ){  // イントラ養成講座だったら
                // window.alert('イントラ養成講座');
            }else{   // パラリン講座だったら
                if( date.value== '' ){  // 日付か時間が入力されていなかったら
                    errMSG = errMSG  + "日付は必須入力です。\n"
                    error ++ ;
                }
                if( time.value== ''){  // 日付か時間が入力されていなかったら
                    errMSG = errMSG  + "時間は必須入力です。\n"
                    error ++ ;
                }
            }
            if(error >= 1){
                throw new Error(errMSG + error + 'つの異常があります');
            }
        } catch (e) {
            window.alert(e.message);
            return false;
        }

        return true;
    }



</script>
<style>
    th{
        width:120px;
    }
</style>

@endsection


