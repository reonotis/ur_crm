
@extends('layouts.app')

@section('content')




                @yield('content')

<div class="">
  <h3>顧客情報</h3>
<div class="customerDetail LeftBOX">
  <div class="BOXin customerBasicInformation">
    <div class="customerNam"><?= $customer->menberNumber ?></div>
    <div class=""><span class="customerNmae" ><?= $customer->name ?> </span>様 <?= $customer->sexName ?> </div>
    <div class="customerRead">( <?= $customer->read ?>　サマ )</div>
    <div class="customerIMG"></div>
  </div>
  <div class="BOXin tabsContentsArea">
      <div class="tabArea">
        <div class="tab active">基本情報</div>
        <div class="tab">受講履歴</div>
        <div class="tab">申し込み講座一覧</div>
      </div>
      <div class="tabsContents">
        <div class="tabscontent show">
          @include('customer.includeCustomerInfomation')
        </div>
        <div class="tabscontent">
          @include('customer.includeCustomerHistory')
        </div>
        <div class="tabscontent">
          @include('customer.includeCustomerCourseList')
        </div>
      </div>
  </div>

</div>





</div>




@endsection



<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js">
</script>
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

// $(function(){
//   // class='secList'がついている全てのbuttonを取得
//   const secList = document.getElementsByClassName('secList');

//   // デフォルトとしてWho を選択する
//   secList[0].classList.add('selected');
//   $('.Who').show( 'blind', '', 300, 'linear' );

//   $('.secList').on('click',function(){     //クリックした時の挙動
//     // class'secList'がついている全てのbuttonを取得
//     const secList = document.getElementsByClassName('secList');
//     for (var i = 0; i < secList.length; i++) {
//       // 一度全てのselectedをはずす
//       secList[i].classList.remove('selected');
//     }
//     // クリックしたボタンにselectedを付ける
//     this.classList.add('selected');

//     // 最新情報を読み込み直す

//     // 他のセクションを非表示にする
//     $('.section').not($('.'+$(this).attr('id'))).hide('blind', '', 300);
//     // 選択したセクションを表示
//     // $('.'+$(this).attr('id')).show('blind', '', 400);
//     // toggle にすると、同じボタンを 2 回押すと非表示になる
//     $('.'+$(this).attr('id')).toggle('blind', '', 300);
//   });
// });



</script>