@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">住所変更<a href="{{route('setting.index')}}" class="textRight">戻る</a></div>
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
                  <form action="{{route('setting.updateAddress')}}" method="post" >
                    @csrf
                    <div class="cusInfoRow" >
                      <div class="editCusInfoTitle" >住所</div>
                      <div class="editCusInfoContent" >
                        <div class="inputBirthday">


                        <div class="inputZips">
                            <input class="formInput inputZip" type="text" name="zip21" value="<?= $auth-> zip21 ?>" placeholder="150" >-
                            <input class="formInput inputZip" type="text" name="zip22" value="<?= $auth-> zip22 ?>" placeholder="0022" >
                        </div>
                        <div class="inputAddrs">
                            <input class="formInput inputAddr" type="text" name="pref21" value="<?= $auth-> pref21 ?>" placeholder="東京都" >
                            <input class="formInput inputAddr" type="text" name="addr21" value="<?= $auth-> addr21 ?>" placeholder="渋谷区" >
                        </div>
                        <input class="formInput inputStrt" type="text" name="strt21" value="<?= $auth-> strt21?>" placeholder="恵比寿南1丁目マンション名1101号室" >
                        <!-- この機能まだ -->




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



