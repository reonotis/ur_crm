
@extends('layouts.app')

@section('content')




@yield('content')

<div class="display-container">
  <div class="fullWidth">
    <button class="btn btn-outline-dark btn-sm" type="button" onClick="history.back()">戻る</button>
  </div>
  <h3>インストラクター情報</h3>
  <div class="customerDetail LeftBOX">
    <div class="BOXin customerBasicInformation">
      <div class="customerNam"><?= $user->intr_No ?></div>
      <div class=""><span class="customerNmae" ><?= $user->name ?> </span></div>
      <div class="customerRead">( <?= $user->read ?> )</div>
      <div class="customerIMG"></div>
    </div>
    <div class="BOXin tabsContentsArea">
        <div class="tabArea">
          <div class="tab active">支払い情報</div>
          <div class="tab">基本情報</div>
          <div class="tab">顧客一覧</div>
          <div class="tab">メール</div>
        </div>
        <div class="tabsContents">
          <div class="tabscontent show">
            @include('user.include_payment')
          </div>
          <div class="tabscontent">
            @include('user.include_information')
          </div>
          <div class="tabscontent">
            @include('user.include_customer')
          </div>
          <div class="tabscontent">
            @include('user.include_mails')
          </div>
        </div>
    </div>
  </div>
</div>

@endsection



<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script>
$(function() {
  let tabs = $(".tab"); // tabのクラスを全て取得し、変数tabsに配列で定義
  $(".tab").on("click", function() { // tabをクリックしたらイベント発火
    $(".active").removeClass("active"); // activeクラスを消す
    $(this).addClass("active"); // クリックした箇所にactiveクラスを追加
    const index = tabs.index(this); // クリックした箇所がタブの何番目か判定し、定数indexとして定義
    $(".tabscontent").removeClass("show").eq(index).addClass("show"); // showクラスを消して、contentクラスのindex番目にshowクラスを追加
  })
})

</script>