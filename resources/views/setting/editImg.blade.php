@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">画像変更<a href="{{route('setting.index')}}" class="textRight">戻る</a></div>
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
            <form action="{{route('setting.updateImage')}}" method="post" method="post" enctype="multipart/form-data">
              @csrf
              <div class="cusInfoRow" >
                <div class="editCusInfoTitle" >画像</div>
                <div class="editCusInfoContent" >
                  <div class="">
                    <img src="{{ asset('storage/images/' . $auth->img_path) }}" alt="avatar" width="150px" height="150px" />
                  </div>
                  <div class="">
                    <label>画像を選択する
                  </div>
                  <div class="">
                    <input type="file" name="img" accept=".png, .jpg, .jpeg, image/png, image/jpg"></label>
                  </div>
                </div>
              </div>
              <input type="submit" name="" value="更新する" class="button">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection



