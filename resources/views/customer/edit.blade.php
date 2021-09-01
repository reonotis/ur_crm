@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">顧客情報編集</div>
                <div class="card-body">
                    <form action="{{route('customer.update', ['id' => $customer->id ])}}" method="post">
                        @csrf
                        <div class="editRow_01" >
                            <div class="editTitle_01" >会員番号</div>
                            <div class="editContent_01" >
                                <input class="formInput" type="text" name="member_number" value="<?= $customer->member_number ?>" placeholder="PAR200525001" >
                            </div>
                        </div>
                        <div class="editRow_01" >
                            <div class="editTitle_01" >名前</div>
                            <div class="editContent_01" >
                                <div class="CenterBOX" >
                                    <input class="formInput formInput_2" type="text" name="f_name" value="<?= $customer->f_name ?>" placeholder="藤澤" >
                                    <input class="formInput formInput_2" type="text" name="l_name" value="<?= $customer->l_name ?>" placeholder="怜臣" >
                                </div>
                            </div>
                        </div>
                        <div class="editRow_01" >
                            <div class="editTitle_01" >ヨミ</div>
                            <div class="editContent_01" >
                                <div class="CenterBOX" >
                                    <input class="formInput formInput_2" type="text" name="f_read" value="<?= $customer->f_read ?>" placeholder="フジサワ" >
                                    <input class="formInput formInput_2" type="text" name="l_read" value="<?= $customer->l_read ?>" placeholder="レオン" >
                                </div>
                            </div>
                        </div>
                        <div class="editRow_01" >
                            <div class="editTitle_01" >店舗</div>
                            <div class="editContent_01" >
                                <?php $select_shop_id = old('shop_id') ? old('shop_id') : \Auth::user()->shop_id ; ?>
                                <select class="formInput" name="shop_id" onchange="change_shops()" id="shop_id" >
                                    <option value="" >選択しない</option>
                                    @foreach($shops as $shop)
                                        <option value="<?= $shop->id ?>" <?php if( $shop->id == $select_shop_id )echo" selected"; ?> ><?= $shop->shop_name ?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="editRow_01" >
                            <div class="editTitle_01" >担当</div>
                            <div class="editContent_01" >
                                <?php $select_staff_id = old('staff_id') ? old('staff_id') :  $customer->staff_id ; ?>
                                <select class="formInput" name="staff_id" id="staff_id" >
                                    <option value="" >選択しない</option>
                                    @foreach($users as $user)
                                        <option value="<?= $user->id ?>" <?php if( $user->id == $select_staff_id )echo" selected"; ?> ><?= $user->name ?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="editRow_01" >
                            <div class="editTitle_01_2" >生年月日</div>
                            <div class="editContent_01_2" >
                                <div class="inputBirthday" >
                                    <input class="formInput inputYear" type="num" name="birthday_year" value="<?= $customer->birthday_year ?>" placeholder="年" > /
                                    <input class="formInput inputMonth" type="num" name="birthday_month" value="<?= $customer->birthday_month ?>" placeholder="月" > /
                                    <input class="formInput inputDay" type="num" name="birthday_day" value="<?= $customer->birthday_day ?>" placeholder="日" >
                                </div>
                            </div>
                        </div>
                        <div class="editRow_01" >
                            <div class="editTitle_01_2" >電話番号</div>
                            <div class="editContent_01_2" ><input class="formInput" type="text" name="tel" value="<?= $customer->tel ?>" placeholder="090-1234-5678" ></div>
                        </div>
                        <div class="editRow_01" >
                            <div class="editTitle_01_2" >メールアドレス</div>
                            <div class="editContent_01_2" ><input class="formInput" type="text" name="email" value="<?= $customer->email ?>" placeholder="sample@exsample.com" ></div>
                        </div>
                        <div class="editRow_01" >
                            <div class="editTitle_01_2" >住所</div>
                            <div class="editContent_01_2" >
                                <div class="inputZips">
                                    <input class="formInput inputZip" type="text" name="zip21" value="<?= $customer->zip21 ?>" placeholder="150" >-
                                    <input class="formInput inputZip" type="text" name="zip22" value="<?= $customer->zip22 ?>" placeholder="0022" >
                                </div>
                                <div class="inputAddresses">
                                    <input class="formInput inputAddress" type="text" name="pref21" value="<?= $customer->pref21 ?>" placeholder="東京都" >
                                    <input class="formInput inputAddress" type="text" name="addr21" value="<?= $customer->addr21 ?>" placeholder="渋谷区" >
                                </div>
                                <input class="formInput inputStrt" type="text" name="strt21" value="<?= $customer->strt21 ?>" placeholder="恵比寿南1丁目マンション名1101号室" >
                            </div>
                        </div>
                        <div class="editRow_01" >
                            <div class="editTitle_01_2" >メモ</div>
                            <div class="editContent_01_2" >
                                <textarea class="formInput" name="memo" ><?= $customer->memo ?></textarea>
                            </div>
                        </div>
                        <input type="submit" name="" value="更新する" class="button" >
                        <input type="submit" name="cancel" value="キャンセル" class="button cancel" >
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

<script>


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