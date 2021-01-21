
@extends('layouts.app')

@section('content')


<div class="LeftBOX">
    <div class="BOXin">
        <a href="{{route('client.index')}}" style="padding-left:10px; ">顧客</a>
    </div>
    <div class="BOXin">　>　
    </div>
    <div class="BOXin">
        <a href="{{route('client.search')}}" style="padding-left:10px; ">検索</a>
    </div>
    <div class="BOXin">
    </div>
</div>
        @include ('client.page', ['clients'=>$clients] )





<div class="CenterBOX">
    <h2>基本情報</h2>
    <div class="basicData">
        <table class="DTable">
            <tr>
                <th>ID</th>
                <td>{{$clients['0']->id}}</td>
            </tr>
            <tr>
                <th>会社名</th>
                <td>{{$clients['0']->name}}</td>
            </tr>
            <tr>
                <th>カイシャメイ</th>
                <td>{{$clients['0']->read}}</td>
            </tr>
            <tr>
                <th>電話番号</th>
                <td>{{$clients['0']->tel}}</td>
            </tr>
            <tr>
                <th>ファックス</th>
                <td>{{$clients['0']->fax}}</td>
            </tr>
            <tr>
                <th>住所</th>
                <td>
                    {{$clients['0']->zip21}}-{{$clients['0']->zip22}}<br>
                    {{$clients['0']->pref21}}{{$clients['0']->addr21}}<br>
                    {{$clients['0']->strt21}}
                </td>
            </tr>
            <tr>
                <th>代表者</th>
                <td>
                </td>
            </tr>
        </table>
    </div>
    <div class="basicData">
        <table class="DTable">
            <tr>
                <th>ステータス</th>
                <td>{{$clients['0']->status}}</td>
            </tr>
            <tr>
                <th>角度</th>
                <td>
                    <select id="angle" onchange="update_clientData({{$clients['0']->id}})" >
                        <option >選択してください</option>
                        @foreach($angles as $angle)
                        <option value="{{ $angle -> id }}" <?php if($angle -> id == $clients['0']->angle ) echo " selected"?> >{{ $angle -> angle_name }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <th>業種</th>
                <td>
                    <select id="industry" onchange="update_clientData({{$clients['0']->id}})" >
                        <option >選択してください</option>
                        @foreach($industries as $industrie)
                        <option value="{{ $industrie -> id }}" <?php if($industrie -> id == $clients['0']->industry_id ) echo " selected"?> >{{ $industrie -> name }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <th>設立</th>
                <td></td>
            </tr>
            <tr>
                <th>再コール</th>
                <td>
                    <input type="datetime-local" id="recall" value="<?= str_replace(' ', 'T',  date('Y-m-d H:i:00', strtotime( $clients['0'] -> recall ))); ?>" onchange="update_clientData({{$clients['0']->id}})" >
                </td>
            </tr>
            <tr>
                <th>資本金</th>
                <td></td>
            </tr>
            <tr>
                <th>担当営業</th>
                <td>
                    <select id="user" onchange="update_clientData({{$clients['0']->id}})" >
                        <option >選択してください</option>
                        @foreach($users as $user)
                        <option value="{{ $user -> id }}" <?php if($user -> id == $clients['0']->user ) echo " selected"?> >{{ $user -> name }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
        </table>
    </div>
    <div class="memoData">
        <table class="DTable">
            <tr>
                <th>memo</th>
            </tr>
            <tr>
                <td>
                    <textarea id="memo" onblur="update_clientData({{$clients['0']->id}})" >{{$clients['0']->memo}}</textarea>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="CenterBOX">
    <div class="BOXin callBox">
        <h2>コール履歴<a href="{{ route('client.newCall',  ['id' => $clients[0]->id ] ) }}" >新しい履歴を追加する</a></h2>
        <div class="LeftBOX">
            <div class="BOXin callList">
                <div id="result1"></div>
            </div>
            <div class="BOXin callDetail">
                <div id="result2"></div>
            </div>
        </div>
    </div>
    <div class="BOXin orderBox">
        <h2>受注履歴</h2>
        <div class="LeftBOX">
            <div class="BOXin callList">
                <div id="result3"></div>
            </div>
            <div class="BOXin callDetail">
                <div id="result4"></div>
            </div>
        </div>
    </div>
</div>
@endsection



<script>

    function call_contactList(id){
        $.ajax({
            url: '/client/aj_contactList/' + id,
            method: "GET",
            id: id,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(date){
                $("#result1").html( date );
            },
        });
    }

    function call_orderList(id){
        $.ajax({
            url: '/client/aj_orderList/' + id,
            method: "GET",
            id: id,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(date){
                $("#result3").html( date );
            },
        });
    }


    function callDisplaySet(id){
        $.ajax({
            url: '/client/aj_history_id/' + id,
            method: "GET",
            id: id,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(date){
                // console.log(date);
                $("#result2").html( date );
            },
        });
    }


    function update_clientData(id){
        var angle = document.getElementById('angle').value
        var industry = document.getElementById('industry').value
        var recall = document.getElementById('recall').value
        var memo = document.getElementById('memo').value
        var user = document.getElementById('user').value
        $.ajax({
            url: '/client/update/' + id,
            method: "POST",
            data: {
                id: id,
                angle: angle,
                industry: industry,
                recall: recall,
                memo: memo,
                user: user,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(client_id){
                // console.log(client_id + '成功しました');
            },
        });
    }


    function contact_up(id){
        var history_datetime = document.getElementById('history_datetime').value
        var recipient_name = document.getElementById('recipient_name').value
        var recipient_role = document.getElementById('recipient_role').value
        var recipient_sex = document.getElementById('recipient_sex').value
        
        var means_id = document.getElementById('means_id').value
        var result_id = document.getElementById('result_id').value
        var history_detail = document.getElementById('history_detail').value

        // console.log(history_detail)
        $.ajax({
            url: '/client/aj_contact_update/' + id,
            method: "POST",
            data: {
                id: id,
                history_datetime: history_datetime,
                recipient_name  : recipient_name,
                recipient_role  : recipient_role,
                recipient_sex   : recipient_sex,
                means_id        : means_id,
                result_id       : result_id,
                history_detail  : history_detail,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(client_id){
                // console.log(client_id)
                call_contactList(client_id);
            },
        });
    }

    function aaa(){
        console.log('ポップアップを表示したい');
    }



    window.onload = function() {
        call_contactList({{$clients['0']->id}});
        call_orderList({{$clients['0']->id}});
    };


</script>