@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">メールアドレス変更</div>
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
                  <form action="{{route('setting.sendChangeEmailLink')}}" method="post" >
                    @csrf
                    <div class="cusInfoRow" >
                      <div class="editCusInfoTitle" >新しいメールアドレス</div>
                      <div class="editCusInfoContent" >
                        <input class="formInput"  type="email" name="new_email1" value="" placeholder="新しいメールアドレス" >
                      </div>
                    </div>
                    <div class="cusInfoRow" >
                      <div class="editCusInfoTitle" >新しいメールアドレス</div>
                      <div class="editCusInfoContent" >
                        <input class="formInput"  type="email" name="new_email2" value="" placeholder="確認用の為再度入力してください" >
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


