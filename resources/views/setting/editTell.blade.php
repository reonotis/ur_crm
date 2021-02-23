@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">電話番号変更</div>
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
                  <form action="{{route('setting.updateTell')}}" method="post" >
                    @csrf
                    <div class="cusInfoRow" >
                      <div class="editCusInfoTitle" >新しい電話番号</div>
                      <div class="editCusInfoContent" >
                        <div class="inputBirthday">
                            <input class="formInput"  type="password" name="new_pass1" value="" placeholder="新しいパスワード" >
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


