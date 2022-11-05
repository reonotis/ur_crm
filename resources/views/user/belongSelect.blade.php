@extends('layouts.app')
@section('pageTitle', '未所属スタイリスト一覧')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="" >
                <p class="belongSelectSupplement" >{{ session()->get(App\Consts\SessionConst::SELECTED_SHOP)->shop_name }}&nbsp;に紐づけたいスタイリストを下記から選択してください</p>
                <table class="listTBL userListTBL">
                    <thead>
                        <tr>
                            <th>スタイリスト名</th>
                            <th>所属店舗</th>
                            <th>登録</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>
                                    @if( !count($user->userShopAuthorizations))
                                        -
                                    @endif
                                    @foreach($user->userShopAuthorizations AS $userShopAuthorization)
                                        <p>{{ $userShopAuthorization->shop->shop_name }}</p>
                                    @endforeach
                                </td>
                                <td><a href="{{ route('user.belongSelected', ['user'=>$user->id]) }}" >登録する</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex mt-12" >
                <a href="javascript:history.back()" class="submit back-btn" >戻る</a>
            </div>

        </div>
    </div>




@endsection

