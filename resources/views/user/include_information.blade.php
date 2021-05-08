
<div class="cusInfoRow" >
  <div class="cusInfoTitle" >電話番号</div>
  <div class="cusInfoContent" ><?= $user->tel ?></div>
</div>


<div class="cusInfoRow" >
  <div class="cusInfoTitle" >メールアドレス</div>
  <div class="cusInfoContent" ><?= $user->email ?></div>
</div>
<div class="cusInfoRow" >
  <div class="cusInfoTitle" >生年月日</div>
  <div class="cusInfoContent" >
      <?= getBarthday($user->birthdayYear, $user->birthdayMonth, $user->birthdayDay) ?>
  </div>
</div>

<div class="cusInfoRow" >
  <div class="cusInfoTitle" >住所</div>
  <div class="cusInfoContent" >
    <?= $user->zip21 ." - ". $user->zip22 ?><br>
    <?= $user->pref21 ?>　<?= $user->addr21 ?><br>
    <?= $user->strt21 ?>
  </div>
</div>


<div class="LeftBOX">
  <a href="">
    <div class="button BOXin">編集する</div>
  </a>
</div>










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