<?php
// dd($shops);
// $json_shops = json_encode($shops);
?>

@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">顧客検索</div>
                <div class="card-body">
                    <form method="get" action="{{route('customer.searching')}}" class="form-inline my-2 my-lg-0">
                        <table class="tableClass_002">
                            <tr>
                                <th>会員番号</th>
                                <td><input class="formInput" type="text" name="member_number" placeholder="PAR200525001" ></td>
                            </tr>
                            <tr>
                                <th>名前</th>
                                <td>
                                    <div class="CenterBOX" >
                                        <input class="formInput formInput_2" type="text" name="f_name" placeholder="藤澤" >
                                        <input class="formInput formInput_2" type="text" name="l_name" placeholder="怜臣" >
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>ヨミ</th>
                                <td>
                                    <div class="CenterBOX" >
                                        <input class="formInput formInput_2" type="text" name="f_read" placeholder="フジサワ" >
                                        <input class="formInput formInput_2" type="text" name="l_read" placeholder="レオン" >
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>店舗</th>
                                <td>
                                    <select class="formInput" name="shop_id" onchange="change_shops()" id="shop_id" >
                                        <option value="" >選択しない</option>
                                        @foreach($shops as $shop)
                                            <option value="<?= $shop->id ?>" <?php if(\Auth::user()->shop_id == $shop->id)echo" selected"; ?> ><?= $shop->shop_name ?></option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>担当</th>
                                <td>
                                    <select class="formInput" name="staff_id" id="staff_id" >
                                        <option value="" >選択しない</option>
                                        @foreach($users as $user)
                                            <option value="<?= $user->id ?>" ><?= $user->name ?></option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>生年月日</th>
                                <td>
                                    <div class="inputBirthday">
                                        <input class="formInput inputYear" type="num" name="birthdayYear" placeholder="年" > /
                                        <input class="formInput inputMonth" type="num" name="birthdayMonth" placeholder="月" > /
                                        <input class="formInput inputDay" type="num" name="birthdayDay" placeholder="日" >
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>電話番号</th>
                                <td><input class="formInput" type="text" name="tel" placeholder="090-1234-5678" ></td>
                            </tr>
                            <tr>
                                <th>メールアドレス</th>
                                <td><input class="formInput" type="text" name="email" placeholder="sample@exsample.com" ></td>
                            </tr>
                            <tr>
                                <th>住所</th>
                                <td>
                                    <div class="inputAddressArea">
                                        <div class="inputZips">
                                            <input class="formInput inputZip" type="text" name="zip21" placeholder="150" >-
                                            <input class="formInput inputZip" type="text" name="zip22" placeholder="0022" >
                                        </div>
                                        <div class="inputAddresses">
                                            <input class="formInput inputAddress" type="text" name="pref21" placeholder="東京都" >
                                            <input class="formInput inputAddress" type="text" name="addr21" placeholder="渋谷区" >
                                        </div>
                                        <input class="formInput inputStreet" type="text" name="strt21" placeholder="恵比寿南1丁目マンション名1101号室" >
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>非表示</th>
                                <td><label style="justify-content: left;"><input class="" type="checkbox" name="hidden_flag" >非表示にした顧客を含める</label></td>
                            </tr>
                        </table>
                        <button class=" button" type="submit">検索する</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



<script>
    window.onload = function () {
        change_shops()
    };

    let users = JSON.parse('<?= $users; ?>');

    function change_shops(){
        const shops = document.getElementById('shop_id');
        const usersSelectList = document.getElementById( "staff_id" ) ;
        const length = usersSelectList.length ;

        // セレクトボックスから「選択しない」以外削除
        for (let index = 1 ; index < length ; index++ ) {
            usersSelectList.remove( 1 ) ;
        }

        // スタイリストをセット
        for (let index = 0 ; index <users.length ; index++ ) {
            // 選択していた場合
            if(shops.value){
                if(users[index]['shop_id']== shops.value){
                    usersSelectList.add( new Option( users[index]['name'], users[index]['id'] ) ) ;
                }
            }else{
            // 選択していなかった場合
                usersSelectList.add( new Option( users[index]['name'], users[index]['id'] ) ) ;
            }
        }
    }
</script>