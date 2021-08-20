@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-8">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card">
                <div class="card-header">顧客登録</div>
                <div class="card-body">
                    <form method="post" action="{{route('customer.store')}}" class="form-inline my-2 my-lg-0">
                        @csrf
                        <table class="tableClass_004">
                            <tr>
                                <th>会員番号</th>
                                <td><input class="formInput" type="text" name="member_number" placeholder="CA00001" value="{{ old('member_number') }}" ></td>
                            </tr>
                            <tr>
                                <th>名前</th>
                                <td>
                                    <div class="CenterBOX" >
                                        <input class="formInput formInput_2" type="text" name="f_name" placeholder="藤澤" value="{{ old('f_name') }}" >
                                        <input class="formInput formInput_2" type="text" name="l_name" placeholder="怜臣" value="{{ old('l_name') }}" >
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>ヨミ</th>
                                <td>
                                    <div class="CenterBOX" >
                                        <input class="formInput formInput_2" type="text" name="f_read" placeholder="フジサワ" value="{{ old('f_read') }}" >
                                        <input class="formInput formInput_2" type="text" name="l_read" placeholder="レオン" value="{{ old('l_read') }}" >
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>性別</th>
                                <td>
                                    <div class="" >
                                        <div class="inputSex" >
                                            <label><input type="radio" name="sex" value="1" >男性</label>
                                            <label><input type="radio" name="sex" value="2" >女性</label>
                                            <label><input type="radio" name="sex" value="3" >その他</label>
                                            <label><input type="radio" name="sex" value="4" >未回答</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>店舗</th>
                                <td>
                                    <?php $select_shop_id = old('shop_id') ? old('shop_id') : \Auth::user()->shop_id ; ?>
                                    <select class="formInput" name="shop_id" onchange="change_shops()" id="shop_id" >
                                        <option value="" >選択しない</option>
                                        @foreach($shops as $shop)
                                            <option value="<?= $shop->id ?>" <?php if( $shop->id == $select_shop_id )echo" selected"; ?> ><?= $shop->shop_name ?></option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>担当</th>
                                <td>
                                    <?php $select_staff_id = old('staff_id') ? old('staff_id') : \Auth::user()->id ; ?>
                                    <select class="formInput" name="staff_id" id="staff_id" >
                                        <option value="" >選択しない</option>
                                        @foreach($users as $user)
                                            <option value="<?= $user->id ?>" <?php if( $user->id == $select_staff_id )echo" selected"; ?> ><?= $user->name ?></option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>生年月日</th>
                                <td>
                                    <div class="inputBirthday">
                                        <input class="formInput inputYear" type="num" name="birthday_year" placeholder="年" value="{{ old('birthday_year') }}" > /
                                        <input class="formInput inputMonth" type="num" name="birthday_month" placeholder="月" value="{{ old('birthday_month') }}" > /
                                        <input class="formInput inputDay" type="num" name="birthday_day" placeholder="日" value="{{ old('birthday_day') }}" >
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>電話番号</th>
                                <td><input class="formInput" type="text" name="tel" placeholder="090-1234-5678" value="{{ old('tel') }}" ></td>
                            </tr>
                            <tr>
                                <th>メールアドレス</th>
                                <td><input class="formInput" type="text" name="email" placeholder="sample@exsample.com" value="{{ old('email') }}" ></td>
                            </tr>
                            <tr>
                                <th>住所</th>
                                <td>
                                    <div class="inputZips">
                                        <input class="formInput inputZip" type="text" name="zip21" placeholder="150" value="{{ old('zip21') }}" >-
                                        <input class="formInput inputZip" type="text" name="zip22" placeholder="0022" value="{{ old('zip22') }}" >
                                    </div>
                                    <div class="inputAddress">
                                        <input class="formInput inputAddr" type="text" name="pref21" placeholder="東京都" value="{{ old('pref21') }}" >
                                        <input class="formInput inputAddr" type="text" name="addr21" placeholder="渋谷区" value="{{ old('addr21') }}" >
                                    </div>
                                    <input class="formInput inputStrt" type="text" name="strt21" placeholder="恵比寿南1丁目マンション名1101号室" value="{{ old('strt21') }}" >
                                </td>
                            </tr>
                            <tr>
                                <th>非表示</th>
                                <td><label style="justify-content: left;"><input class="" type="checkbox" name="hidden_flag" <?php if(old('hidden_flag')) echo ' checked="checked"' ; ?> >非表示にする</label></td>
                            </tr>
                        </table>
                        <button class="button edit" type="submit">登録する</button>
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