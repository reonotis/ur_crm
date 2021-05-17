@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="fullWidth">
        <button class="btn btn-outline-dark btn-sm" type="button" onClick="history.back()">戻る</button>
      </div>
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">画像変更</div>
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

              <div class="userSettingRow" >
                <div class="userSettingTitle" >現在の画像</div>
                <div class="userSettingContent" >
                  <?php if($auth->img_path) {?>
                    <img src="{{ asset('storage/mainImages/' . $auth->img_path) }}" alt="avatar" width="150px" height="150px" />
                  <?php }else{ ?>
                    画像はありません。
                  <?php } ?>
                </div>
              </div>

            <form action="{{route('setting.updateImage')}}" method="post" method="post" enctype="multipart/form-data">
              @csrf
              <div class="cusInfoRow" >
                <div class="editCusInfoTitle" >新しい画像に更新</div>
                <div class="editCusInfoContent" >
                    <input type="file" name="img" accept=".png, .jpg, .jpeg, image/png, image/jpg">
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



