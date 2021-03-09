@extends('layouts.app')

@section('content')
<div class="container">
        <!-- <a href="{{route('customer.index')}}">顧客検索</a> -->
        
    <div class="row justify-content-center">

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a href="">顧客登録</a>
                </div>

                <div class="card-body">
                    <form mothod="GET" action="{{route('customer.searching')}}" class="form-inline my-2 my-lg-0">
                        <table class="customerSearchTable">
                            <tr>
                                <th>会員番号</th>
                                <td><input class="formInput" type="text" name="menberNumber" placeholder="PAR200525001" ></td>
                            </tr>
                            <tr>
                                <th>名前</th>
                                <td><input class="formInput" type="text" name="name" placeholder="藤澤 怜臣" ></td>
                            </tr>
                            <tr>
                                <th>ヨミ</th>
                                <td><input class="formInput" type="text" name="read" placeholder="フジサワ レオン" ></td>
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
                                    <div class="inputZips">
                                        <input class="formInput inputZip" type="text" name="zip21" placeholder="150" >-
                                        <input class="formInput inputZip" type="text" name="zip22" placeholder="0022" >
                                    </div>
                                    <div class="inputAddrs">
                                        <input class="formInput inputAddr" type="text" name="pref21" placeholder="東京都" >
                                        <input class="formInput inputAddr" type="text" name="addr21" placeholder="渋谷区" >
                                    </div>
                                    <input class="formInput inputStrt" type="text" name="strt21" placeholder="恵比寿南1丁目マンション名1101号室" >
                                    <!-- この機能まだ -->
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">登録する</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
