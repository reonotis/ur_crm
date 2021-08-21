@extends('layouts.app')

@section('content')

@include('setting.navigation')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">メールアドレス更新</div>
                <div class="card-body">
                    <form action="{{ route('setting.updateEmail') }}" method="post" >
                        @csrf
                        <table class="tableClass_008">
                            <tbody>
                                <tr>
                                    <th>現在のメールアドレス</th>
                                    <td><input class="formInput" type="email" name="email" value="{{ old('email') }}" placeholder="sample@exsample.com" ></td>
                                </tr>
                                <tr>
                                    <th>新しいメールアドレス</th>
                                    <td><input class="formInput" type="email" name="new_email1" value="{{ old('new_email1') }}" placeholder="sample@exsample.com" ></td>
                                </tr>
                                <tr>
                                    <th>新しいメールアドレス 　※確認用</th>
                                    <td><input class="formInput" type="email" name="new_email2" value="{{ old('new_email2') }}" placeholder="sample@exsample.com" ></td>
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
