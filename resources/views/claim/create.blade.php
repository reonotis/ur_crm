@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row justify-content-center">

    <div class="fullWidth">
        <button class="btn btn-outline-dark btn-sm" type="button" onClick="history.back()">戻る</button>
    </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">請求データ作成</div>

                <div class="card-body">
                        <table class="customerSearchTable">
                            <tr>
                                <th>請求先</th>
                                <td><?= $user->name ?> 様</td>
                            </tr>
                            <tr>
                                <th>請求名目</th>
                                <td><input class="formInput" type="text" name="claim_name" id="claim_name" placeholder="年会費" value="<?php if(isset($ClaimTrn->title)) echo $ClaimTrn->title; ?>" onchange="updateOrInsert_claimsTrn();" ></td>
                            </tr>
                            <tr>
                                <th>期日</th>
                                <td><input class="formInput" type="date" name="limit_date" id="limit_date" value="<?php if(isset($ClaimTrn->limit_date)) echo $ClaimTrn->limit_date->format('Y-m-d'); ?>" onchange="updateOrInsert_claimsTrn();" ></td>
                            </tr>
                        </table>
                        <br>

                        <div class="btn btn-outline-success" onclick="add_claimDetail();" >項目を追加する</div>
                        ※項目名に　<?= $item_name_list?>　が記載された場合、デフォルトの単価が自動で表示されます。
                        <div class="ClaimDetailRowMain" >
                            <div class="ClaimDetail_itemName" >項目名</div>
                            <div class="ClaimDetail_unitPrice" >単価</div>
                            <div class="ClaimDetail_quantity" >数量</div>
                            <div class="ClaimDetail_price" >金額</div>
                            <div class="ClaimDetail_rank" >並び順</div>
                            <div class="ClaimDetail_delete" >削除</div>
                        </div>
                        <div id="result">
                            @if($CDTrans)
                                @foreach($CDTrans as $CDTran )
                                    <div class="ClaimDetailRow">
                                        <div class="ClaimDetail_itemName" >
                                            <input type="text" id="item_name_<?= $CDTran->id ?>" value="{{$CDTran->item_name}}" onchange="setDefaultPrice(<?= $CDTran->id ?>);" >
                                        </div>
                                        <div class="ClaimDetail_unitPrice" >
                                            <input type="number" id="unit_price_<?= $CDTran->id ?>" value="{{$CDTran->unit_price}}" onchange="reCalculation(<?= $CDTran->id ?>);" >円
                                        </div>
                                        <div class="ClaimDetail_quantity" >
                                            <input type="number" id="quantity_<?= $CDTran->id ?>" value="{{$CDTran->quantity}}" onchange="reCalculation(<?= $CDTran->id ?>);" >
                                            <input type="text" id="unit_<?= $CDTran->id ?>" value="{{$CDTran->unit}}" onchange="updateOrInsert_claimDetail(<?= $CDTran->id ?>);" >
                                        </div>
                                        <div class="ClaimDetail_price" >
                                            <input type="number" id="price_<?= $CDTran->id ?>" value="{{$CDTran->price}}" onchange="updateOrInsert_claimDetail(<?= $CDTran->id ?>);" >円
                                        </div>
                                        <div class="ClaimDetail_rank" >
                                            <a href="" onclick="return rankDuwn(<?= $CDTran->id ?>);" >↓</a>
                                        </div>
                                        <div class="ClaimDetail_delete" >
                                            <a href="" onclick="return confilmDelete(<?= $CDTran->id ?>);" >削除</a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <a href="{{ route('claim.deleteTran',['id'=>$user->id ]) }}">
                            <button class="btn btn-outline-danger" onclick="return confilmDeleteClaim()">リセット</button>
                        </a>
                        <a href="{{ route('claim.confilmAddClaim',['id'=>$user->id ]) }}">
                            <button class="btn btn-outline-success">確認する</button>
                        </a>
                </div>
            </div>
        </div>
    </div>
<?php
    // dd($claimDetailList);
?>
</div>
@endsection


<script>
    function reload_claimDetail(data){
        console.log("再度読み込み処理")
        // $('#result').html("");
            let html = "";
            for(var i = 0; i < data.length; i++){
                var id = data[i]['id'];
                if(data[i]['item_name']){
                    var item_name  = data[i]['item_name'];
                }else{
                    var item_name  = "";
                }
                var unit_price = data[i]['unit_price'];
                var quantity   = data[i]['quantity'];
                var price      = data[i]['price'];
                var unit       = data[i]['unit'];
                if(data[i]['unit']){
                    var unit  = data[i]['unit'];
                }else{
                    var unit  = "";
                }

                html=html + "<div class=\"ClaimDetailRow\" >";
                html=html +     "<div class=\"ClaimDetail_itemName\" >";
                html=html +         "<input type=\"text\" id=\"item_name_" + id + "\" value=\"" + item_name + "\" onchange=\"setDefaultPrice(" + id + ");\" >";
                html=html +     "</div>";
                html=html +     "<div class=\"ClaimDetail_unitPrice\" >";
                html=html +         "<input type=\"number\" id=\"unit_price_" + id + "\" value=\"" + unit_price + "\" onchange=\"reCalculation(" + id + ");\" >円";
                html=html +     "</div>";
                html=html +     "<div class=\"ClaimDetail_quantity\" >";
                html=html +         "<input type=\"number\" id=\"quantity_" + id + "\" value=\"" + quantity + "\" onchange=\"reCalculation(" + id + ");\" >";
                html=html +         "<input type=\"text\" id=\"unit_" + id + "\" value=\"" + unit + "\" onchange=\"updateOrInsert_claimDetail(" + id + ");\" >";
                html=html +     "</div>";
                html=html +     "<div class=\"ClaimDetail_price\" >";
                html=html +         "<input type=\"number\" id=\"price_" + id + "\" value=\"" + price + "\" onchange=\"updateOrInsert_claimDetail(" + id + ");\" >円";
                html=html +     "</div>";

                html=html +     "<div class=\"ClaimDetail_rank\" >";
                html=html +         "<a href=\" \"  onclick=\"return rankDuwn(" + id + ");\"    >↓</a>";
                html=html +     "</div>";
                html=html +     "<div class=\"ClaimDetail_delete\" >";
                html=html +         "<a href=\" \"  onclick=\"return confilmDelete(" + id + ");\"    >削除</a>";
                html=html +     "</div>";
                html=html + "</div>";
            }
        $('#result').html(html);
    }

    function reCalculation(id){
        var unit_price = document.getElementById("unit_price_" + id)
        var quantity   = document.getElementById("quantity_" + id)
        var price      = document.getElementById("price_" + id)
        price.value = unit_price.value * quantity.value;
        updateOrInsert_claimDetail(id)
    }

    function setDefaultPrice(id){
        var claimDetailList = JSON.parse('<?php echo $claimDetailList; ?>');
        var item_name  = document.getElementById("item_name_" + id).value
        var unit_price = document.getElementById("unit_price_" + id)
        var unit       = document.getElementById("unit_" + id)
        claimDetailList.some(function(List){
            if(item_name.includes(List.item_name)){
                console.log(List.unit_price)
                unit_price.value = List.unit_price
                unit.value       = List.unit
                return true;
            }
        });

        reCalculation(id)
    }

    function updateOrInsert_claimDetail(id){
        var item_name  = document.getElementById("item_name_" + id)
        var unit_price = document.getElementById("unit_price_" + id)
        var quantity   = document.getElementById("quantity_" + id)
        var unit       = document.getElementById("unit_" + id)
        var price      = document.getElementById("price_" + id)
        $.get({
            url: "/claim/updateOrInsert_claimDetail/" + id,
            method: 'GET',
            dataType: 'json',
            data: {
                'item_name' : item_name.value,
                'unit_price': unit_price.value,
                'quantity'  : quantity.value,
                'unit'      : unit.value,
                'price'     : price.value,
            }
        }).done(function (data) { //ajaxが成功したときの処理
            // console.log("更新成功 ID : " + data);
        }).fail(function () { //ajax通信がエラーのときの処理
            console.log('更新失敗');
        })

    }

    function updateOrInsert_claimsTrn(){
        const claim_name = document.getElementById("claim_name")
        const limit_date = document.getElementById("limit_date")
        $.get({
            url: "/claim/updateOrInsert/" + <?= $user->id ?>,
            method: 'GET',
            dataType: 'json',
            data: {
                'user_type' : 2,
                'claim_name': claim_name.value,
                'limit_date': limit_date.value
            }
        }).done(function (data) { //ajaxが成功したときの処理
            console.log("更新成功 ID : " + data);
        }).fail(function () { //ajax通信がエラーのときの処理
            console.log('更新失敗');
        })
    }

    function add_claimDetail(){
        $.get({
            url: "/claim/addClaimDetail/" + <?= $user->id ?>,
            method: 'GET',
            dataType: 'json',
            data: {
                'user_type' : 2,
            }
        }).done(function (data) {
            reload_claimDetail(data)
        }).fail(function () {
            console.log('追加失敗');
            alert('項目の追加に失敗しました\n請求名目を入力してください');

        })
    }

    function rankDuwn(id){
        $.get({
            url: "/claim/rankDuwn/" + id ,
            method: 'GET',
            dataType: 'json',
            data: {
                'user_type' : 2,
                'user_id' : <?= $user->id ?>,
            }
        }).done(function (data) {
            reload_claimDetail(data)
            // console.log(data)
        }).fail(function () {
            console.log('sort失敗');
        })
        return false;
    }

    function confilmDelete(id){
        var result = window.confirm('この項目を削除しますか？');
        if( result ) delete_claimDetail(id);
        return false;
    }

    function delete_claimDetail(id){
        $.get({
            url: "/claim/deleteClaimDetail/" + id ,
            method: 'GET',
            dataType: 'json',
            data: {
                'user_type' : 2,
                'user_id' : <?= $user->id ?>,
            }
        }).done(function (data) {
            reload_claimDetail(data)
        }).fail(function () {
            console.log('追加失敗');
        })
        return true;
    }

    function confilmDeleteClaim(){
        var result = window.confirm('この請求データを削除ますか？\nこの操作は取り消せません。');
        if( result ) return true; return false;
    }

</script>
