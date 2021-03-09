@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">設定</div>

                <div class="card-body">

                    <div class="userSettingRow">
                        <div class="userSettingTitle">インストラクター番号</div>
                        <div class="userSettingContent"><?= $auth->intr_No ?></div>
                    </div>
                    <div class="userSettingRow">
                        <div class="userSettingTitle">名前</div>
                        <div class="userSettingContent"><?= $auth->name ?>(<?= $auth->read ?>)</div>
                    </div>
                    <div class="userSettingRow">
                        <div class="userSettingTitle">電話番号</div>
                        <div class="userSettingContent"><?= $auth->tel ?><a href="{{route('setting.editTell')}}" class="textRight">変更する</a></div>
                    </div>
                    <div class="userSettingRow">
                        <div class="userSettingTitle">メールアドレス</div>
                        <div class="userSettingContent"><?= $auth->email ?><a href="" class="textRight">変更出来るようにしたい</a></div>
                    </div>
                    <div class="userSettingRow">
                        <div class="userSettingTitle">住所</div>
                        <div class="userSettingContent">
                            <?= $auth->zip21 ." - ". $auth->zip22 ?><br>
                            <?= $auth->pref21 ?>　<?= $auth->addr21 ?><br>
                            <?= $auth->strt21 ?>
                            <a href="{{route('setting.editAddress')}}" class="textRight">変更する</a>
                        </div>
                    </div>
                    <div class="userSettingRow">
                        <div class="userSettingTitle">生年月日</div>
                        <div class="userSettingContent">
                            <?= getBarthday($auth->birthdayYear, $auth->birthdayMonth, $auth->birthdayDay) ?>
                        </div>
                    </div>
                    <div class="userSettingRow">
                        <div class="userSettingTitle">パスワード</div>
                        <div class="userSettingContent">********
                            <a href="{{route('setting.editPassword')}}" class="textRight">変更する</a>
                        </div>
                    </div>
                    <div class="userSettingRow">
                        <div class="userSettingTitle">写真</div>
                        <div class="userSettingContent">
                            <?php if($auth->img_path) { ?>
                                <img src="{{ asset('storage/mainImages/' . $auth->img_path) }}" alt="avatar" width="150px" height="150px" />
                            <?php } ?>
                            <a href="{{route('setting.editImage')}}" class="textRight">変更する</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<?php

  /**
  * 生年月日を計算し、正確な日付であれば年齢を出力する
  */
  function getBarthday($Year, $Month, $Day){
    $stringBirthday = "" ;

    // 月・日が登録されていれば、2桁に0埋めしてなければ「--」にする
    $stringMonth = ($Month)? str_pad($Month, 2, '0', STR_PAD_LEFT) : "--" ;
    $stringDay   = ($Day)  ? str_pad($Day, 2, '0', STR_PAD_LEFT)   : "--" ;

    // 表示用の生年月日を作成
    $stringBirthday =  ($Year) ?  $Year  ."年 " : "----年 ";
    $stringBirthday .= ($Month)?  $Month ."月 " : "--月 "  ;
    $stringBirthday .= ($Day)  ?  $Day   ."日"  : "--日"   ;

    // 年月日が登録されていれば年齢を求める
    if($Year && $Month && $Day){
      $now = date("Ymd");
      $birthday = $Year . $stringMonth . $stringDay;
      $NENREI = "　　　年齢 : 満 " . floor(($now-$birthday)/10000).' 歳';
      $stringBirthday .=  $NENREI ;
    }
    return $stringBirthday;
  }



?>