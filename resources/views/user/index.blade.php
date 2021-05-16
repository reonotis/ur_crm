@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="fullWidth">
            <button class="btn btn-outline-dark btn-sm" type="button" onClick="history.back()">戻る</button>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    インストラクター検索
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <!-- <div class="LeftBOX">
                        <a href="{{route('user.create')}}">
                            <div class="button BOXin">新しいインストラクターを追加する</div>
                        </a>
                    </div> -->

                    <form action="{{route('user.searching')}}" mothod="GET" >
                        <table class="customerSearchTable">
                            <tr>
                                <th>会員番号</th>
                                <td><input class="formInput" type="text" name="menberNumber" placeholder="PAR200525001"></td>
                            </tr>
                            <tr>
                                <th>インストラクター名</th>
                                <td><input class="formInput" type="text" name="name" placeholder="田中 太郎"></td>
                            </tr>
                            <tr>
                                <th>ヨミ</th>
                                <td><input class="formInput" type="text" name="read" placeholder="フジサワ レオン" ></td>
                            </tr>
                            <tr>
                                <th>権限</th>
                                <td>
                                    <select class="formInput" name="authority" >
                                        <option value="1" >あり</option>
                                        <option value="0" >なし</option>
                                    </select>
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
                                <td colspan="2">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">検索する</button>
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
