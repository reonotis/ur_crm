@extends('layouts.app')

@section('content')

<div class="settingNavigation">
    <ul id="settingNavigation">
        <a href="{{route('setting.index')}}"><li class="on" >設定画面TOP</li></a>
        <a href="{{route('setting.changeEmail')}}"><li>メールアドレス変更</li></a>
        <a href="{{route('setting.changePassword')}}"><li>パスワード変更</li></a>
    </ul>
</div>

<div class="container">
    <div class="row justify-content-center">

    </div>
</div>

@endsection
