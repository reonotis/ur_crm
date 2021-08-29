@extends('layouts.app')

@include('layouts.modal')
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
                <div class="card-header">スタイリスト登録</div>
                <div class="card-body">
                    <form action="{{route('stylist.store')}}" method="post">
                        <table class="tableClass_007">
                            @csrf
                            <tbody>
                                <tr>
                                    <th>名前</th>
                                    <td><input type="text" name="name" value="{{ old('name') }}" class="formInput" ></td>
                                </tr>
                                <tr>
                                    <th>メールアドレス</th>
                                    <td><input type="email" name="email" value="{{ old('email') }}" class="formInput" ></td>
                                </tr>
                                <tr>
                                    <th>店舗</th>
                                    <td>
                                        <select name="shop_id" class="formInput" >
                                            @foreach($shops as $shop)
                                                <option value="{{ $shop->id }}" <?php if( old('shop_id') == $shop->id) echo " selected"; ?> >{{ $shop->shop_name }}</option>
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
                                                    <option value="{{ $authority['authorityId'] }}" <?php if( old('authority_id') == $authority['authorityId']) echo " selected"; ?> >{{ $authority['authorityName'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <input type="submit" value="登録"  class="button">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
