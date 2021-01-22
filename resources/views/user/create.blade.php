@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a href="{{route('user.index')}}">社員</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="{{route('user.store')}}" >
                        @csrf
                        <table class="userCreateTable" >
                            <tr>
                                <td>
                                    <div class="cp_iptxt">
                                        <input class="ef" type="text" name="name" required >
                                        <label>お名前</label>
                                        <span class="focus_line"></span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="cp_iptxt">
                                        <input class="ef" type="email" name="email" required >
                                        <label>email</label>
                                        <span class="focus_line"></span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="cp_ipselect">
                                        <select class="cp_sl06" name="authority" required >
                                            <option value="" hidden disabled selected></option>
                                            @foreach($authoritys as $authority )
                                            <option value="{{ $authority -> id}}">{{ $authority -> authority_name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="cp_sl06_highlight"></span>
                                        <span class="cp_sl06_selectbar"></span>
                                        <label class="cp_sl06_selectlabel">権限</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="cp_ipselect">
                                        <select class="cp_sl06" name="enrollment" required >
                                            <option value="" hidden disabled selected></option>
                                            <option value="1">在籍</option>
                                            <option value="2">長期休暇</option>
                                            <option value="3">退職</option>
                                        </select>
                                        <span class="cp_sl06_highlight"></span>
                                        <span class="cp_sl06_selectbar"></span>
                                        <label class="cp_sl06_selectlabel">在籍</label>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <input type="submit" name="store" value="登録する" class="btn btn-info">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
