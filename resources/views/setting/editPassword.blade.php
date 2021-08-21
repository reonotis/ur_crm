@extends('layouts.app')

@section('content')

@include('setting.navigation')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">パスワード更新</div>
                <div class="card-body">
                    <form action="{{ route('setting.updatePassword') }}" method="post" >
                        @csrf
                        <table class="tableClass_008">
                            <tbody>
                                <tr>
                                    <th>現在のパスワード</th>
                                    <td><input class="formInput" type="password" name="password" value="{{ old('password') }}" ></td>
                                </tr>
                                <tr>
                                    <th>新しいパスワード</th>
                                    <td><input class="formInput" type="password" name="new_password1" value="{{ old('new_password1') }}" ></td>
                                </tr>
                                <tr>
                                    <th>新しいパスワード 　※確認用</th>
                                    <td><input class="formInput" type="password" name="new_password2" value="{{ old('new_password2') }}" ></td>
                                </tr>
                            </tbody>
                        </table>
                        <input type="submit" name="" class="button edit" value="更新する" >
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
