
@extends('layouts.app')

@section('content')




                @yield('content')

<div class="">
  <h3>顧客情報</h3>
  <div class="fullWidth">
    <button class="btn btn-light btn-sm" type="button" onClick="history.back()">戻る</button>
  </div>
  <div class="customerDetail LeftBOX">
    <div class="BOXin customerBasicInformation">
      <div class="customerNam"><?= $customer->menberNumber ?></div>
      <div class=""><span class="customerNmae" ><?= $customer->name ?> </span>様 <?= $customer->sexName ?> </div>
      <div class="customerRead">( <?= $customer->read ?>　サマ )</div>
      <div class="customerIMG"></div>
    </div>
    <div class="BOXin tabsContentsArea">
        <div class="tabArea">
          <div class="editTab active">受講履歴編集</div>
        </div>
        <div class="editTabsContents">
          <div class="tabscontent show">

            <form action="{{route('courseDetails.update', ['id'=>$customerSchedule->id ])}}" method="post" >
              @csrf

              <div class="cusInfoRow" >
                <div class="editCusInfoTitle" >日付</div>
                <div class="editCusInfoContent" ><input type="date" name="date" value="<?= $customerSchedule->date ?>" class="formInput" ></div>
              </div>
              <div class="cusInfoRow" >
                <div class="editCusInfoTitle" >時間</div>
                <div class="editCusInfoContent" ><input type="time" name="time" value="<?= $customerSchedule->time ?>" class="formInput" ></div>
              </div>
              <div class="cusInfoRow" >
                <div class="editCusInfoTitle" >状況</div>
                <div class="editCusInfoContent" >
                  <label><input type="radio" name="status" value="0" class="" <?php if( $customerSchedule->status === 0) echo " checked='checked'"; ?> >未受講</label> 　
                  <label><input type="radio" name="status" value="1" class="" <?php if( $customerSchedule->status === 1) echo " checked='checked'"; ?> >受講済み</label>
                  <div><span class="support">※受講済みに更新すると編集できなくなります。</span></div>
                </div>
              </div>
              <div class="cusInfoRow" >
                <div class="editCusInfoTitle" >担当インストラクター</div>
                <div class="editCusInfoContent" ><?= $customerSchedule->name?></div>
              </div>
              <div class="cusInfoRow" >
                <div class="editCusInfoTitle" >メモ</div>
                <div class="editCusInfoContent" >
                  <textarea class="formInput" name="memo" placeholder="この内容は顧客には見られません"><?=  $customerSchedule->memo ?></textarea>
                </div>
              </div>
              <div class="cusInfoRow" >
                <input type="hidden" name="id" value="<?= $customerSchedule->id ?>" >
                <input type="submit" name="" value="更新" class="button" >
                <input type="submit" name="delete" value="削除" class="button delete" >
                
              </div>
            </form>

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