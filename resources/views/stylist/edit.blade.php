@extends('layouts.app')

@include('layouts.modal')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">スタイリスト詳細</div>
                <div class="card-body">
                    <form action="{{route('stylist.update', ['id' => $user->id ])}}" method="post">
                        <table class="tableClass_007">
                            @csrf
                            <tbody>
                                <tr>
                                    <th>名前</th>
                                    <td><input type="text" name="name" value="{{ $user->name }}" class="formInput" ></td>
                                </tr>
                                <tr>
                                    <th>メールアドレス</th>
                                    <td><input type="email" name="email" value="{{ $user->email }}" class="formInput" ></td>
                                </tr>
                                <tr>
                                    <th>店舗</th>
                                    <td>
                                        <select name="shop_id" class="formInput" >
                                            @foreach($shops as $shop)
                                                <option value="{{ $shop->id }}" <?php if( $user->shop_id == $shop->id) echo " selected"; ?> >{{ $shop->shop_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>権限</th>
                                    <td>
                                        <select name="authority_id" class="formInput" >
                                            @foreach($authorityList as $authority)
                                                @if( $authority['display_flg'])
                                                    <option value="{{ $authority['authorityId'] }}" <?php if( $user->authority_id == $authority['authorityId']) echo " selected"; ?> >{{ $authority['authorityName'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <input type="submit" value="更新する"  class="button">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
