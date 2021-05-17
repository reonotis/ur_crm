@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
      <div class="fullWidth">
        <button class="btn btn-outline-dark btn-sm" type="button" onClick="history.back()">戻る</button>
      </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">パスワード変更</div>
                <div class="card-body">
                  @if($errors->any())
                    <div class="alert alert-danger" >
                      <ul>
                        @foreach($errors->all() as $error )
                          <li>{{$error}}</li>
                        @endforeach
                      </ul>
                    </div>
                  @endif
                  <form action="{{route('setting.updatePassword')}}" method="post" >
                    @csrf
                    <div class="userSettingRow" >
                      <div class="userSettingTitle" >現在のパスワード</div>
                      <div class="userSettingContent" >
                        <div class="inputBirthday">
                            <input class="formInput"  type="password" name="old_pass"  value="" placeholder="現在のパスワード" >
                        </div>
                      </div>
                    </div>
                    <div class="cusInfoRow" >
                      <div class="editCusInfoTitle" >新しいパスワード</div>
                      <div class="editCusInfoContent" >
                        <div class="inputBirthday">
                            <input class="formInput"  type="password" name="new_pass1" value="" placeholder="新しいパスワード" >
                        </div>
                      </div>
                    </div>
                    <div class="cusInfoRow" >
                      <div class="editCusInfoTitle" >新しいパスワード</div>
                      <div class="editCusInfoContent" >
                        <div class="inputBirthday">
                            <input class="formInput"  type="password" name="new_pass2" value="{{ old('new_pass2') }}" placeholder="確認用の為再度入力してください" >
                        </div>
                      </div>
                    </div>
                    <input type="submit" name="" value="変更する" class="button">
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


