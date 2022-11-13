@extends('layouts.app')
@section('pageTitle', 'スタイリスト確認')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="userSearchContents" >
                <form action="" method="get" >
                    <div class="userSearchRow" >
                        <div class="userSearchTitle" ><label for="name">名前</label></div>
                        <div class="userSearchContent" >
                            <input type="text" name="name" id="name" class="form-control" value="{{ request('name') }}" >
                        </div>
                    </div>
                    <div class="userSearchRow" >
                        <div class="userSearchTitle" ><label for="email">メールアドレス</label></div>
                        <div class="userSearchContent" >
                            <input type="text" name="email" id="email" class="form-control" value="{{ request('email') }}"  >
                        </div>
                    </div>
                    <div class="userSearchRow" >
                        <div class="userSearchTitle" >在籍状況</div>
                        <div class="userSearchContent" >
                            <label>
                                <input type="checkbox" name="authority_level" <?php if(request('authority_level'))echo " checked" ?>>在籍以外のスタイリストを表示します
                            </label>
                        </div>
                    </div>
                    <div class="flex mt-4" >
                        <input type="submit" name="" value="検索する" class="register-btn" >
                    </div>
                </form>
            </div>
            <div >
                <table class="listTBL userListTBL">
                    <thead>
                        <tr>
                            <th>スタイリスト名</th>
                            <th>メールアドレス</th>
                            <th>在籍状況</th>
                            <th>所属店舗</th>
                            <th>確認</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email}}</td>
                                <td>{{ Common::AUTHORITY_LIST[$user->authority_level]}}</td>
                                <td>
                                    @foreach($user->userShopAuthorizations AS $userShop)
                                        <p>{{ $userShop->shop->shop_name }}</p>
                                    @endforeach
                                </td>
                                <td><a href="{{ route('user.show', ['user'=>$user->id]) }}">確認</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex mt-12" >
                @if (Auth::user()->userShopAuthorization->user_create)
                    <a href="{{ route('user.create') }}" class="submit register-btn" >新しいスタイリストを登録する</a>
                @endif
                @if (Auth::user()->userShopAuthorization->user_create)
                    <a href="{{ route('user.belongSelect') }}" class="submit register-btn" >未所属のスタイリストを&nbsp;{{ session()->get(App\Consts\SessionConst::SELECTED_SHOP)->shop_name }}&nbsp;に所属させる</a>
                @endif
            </div>

        </div>
    </div>




@endsection























