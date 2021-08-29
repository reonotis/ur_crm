@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">来店データの編集画面</div>
                <div class="card-body">
                    <a href="{{ route('report.index') }}" class="button" >戻る</a>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div id="aaaaaaaaaa"></div>
                    ※注意 : 変更した内容はリアルタイムで保存されます。
                    <table class="tableClass_003" >
                        <tr>
                            <th>来店時間</th>
                            <th>名前</th>
                            <th>担当スタイリスト</th>
                            <th>メニュー</th>
                            <th>来店タイプ</th>
                        </tr>
                        @foreach($visit_histories as $visit_history)
                            <tr id="<?= $visit_history->id ?>" >
                                <td><input type="time" name="" id="time_<?= $visit_history->id ?>" value="<?= date('H:i', strtotime($visit_history->vis_time)) ?>" onchange="updateVisHis(<?= $visit_history->id ?>)" ></td>
                                <td><?= $visit_history->f_name.$visit_history->l_name ?></td>
                                <td>
                                    <select name="" id="user_<?= $visit_history->id ?>" onchange="updateVisHis(<?= $visit_history->id ?>)"  >
                                        <option value="NULL" >選択してください</option>
                                        @foreach($users as $user )
                                            <option value="<?= $user->id ?>" id="" <?php if($visit_history->staff_id == $user->id) echo ' selected' ;?> ><?= $user->name ?></option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="" value="" id="menu_<?= $visit_history->id ?>" onchange="updateVisHis(<?= $visit_history->id ?>)"  >
                                    <option value="NULL" >選択してください</option>
                                        @foreach($menus as $menu )
                                            <option value="<?= $menu->id ?>" id="" <?php if($visit_history->menu_id == $menu->id) echo ' selected' ;?> ><?= $menu->menu_name ?></option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="" value="" id="visitType_<?= $visit_history->id ?>" onchange="updateVisHis(<?= $visit_history->id ?>)"  >
                                        <option value="" >選択してください</option>
                                        @foreach($visitTypes as $visitType )
                                            <option value="<?= $visitType->id ?>" id="" <?php if($visit_history->visit_type_id == $visitType->id) echo ' selected' ;?> ><?= $visitType->type_name ?></option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><?= '' ?></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<script>

    function updateVisHis(id){
        var time = document.getElementById("time_" + id).value
        var user = document.getElementById("user_" + id).value
        var menu = document.getElementById("menu_" + id).value
        var visitType = document.getElementById("visitType_" + id).value

        $.get({
            url:  "/VisitHistory/updates/" + id,
            method: 'GET',
            dataType: 'json',
            data: {
                'time' : time,
                'user' : user,
                'menu' : menu,
                'visitType' : visitType,
            }
        }).done(function (data) { // ajaxが成功したときの処理
            if(data[0] == 'fail'){ // 結果が失敗したときの処理
                alert( data[1]['original'] )
            }else{

            }
        }).fail(function () { // ajax通信がエラーのときの処理
            console.log('更新失敗');
        })
    }





</script>



@endsection

