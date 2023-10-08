@extends('layouts.setting')
@section('breadcrumb')
    <ol>
        <li><a href="{{ route('myPage') }}">ホーム</a></li>
        <li><a href="{{ route('setting.changeEmail') }}">アカウント情報</a></li>
    </ol>
@endsection
@section('pageTitle', 'メールアドレス変更')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @include('setting.navigation')
                <div class="setting-basic-contents" >
                    <form action="{{ route('setting.updateEmail') }}" method="post" >
                        @csrf
                        <div class="setting-row" >
                            <div class="setting-title" >現在のメールアドレス</div>
                            <div class="setting-contents" >
                                <input class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="sample@exsample.com" >
                            </div>
                        </div>
                        <div class="setting-row" >
                            <div class="setting-title" >新しいメールアドレス</div>
                            <div class="setting-contents" >
                                <input class="form-control" type="email" name="new_email1" value="{{ old('new_email1') }}" placeholder="sample@exsample.com" >
                                <input class="form-control" type="email" name="new_email2" value="{{ old('new_email2') }}" placeholder="sample@exsample.com" >
                            </div>
                        </div>
                        <div class="flex mb-4" >
                            <input type="submit" name="" class="button edit-btn" value="更新する" >
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
