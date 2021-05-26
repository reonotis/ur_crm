
@extends('layouts.app')

@section('content')

@yield('content')

<div class="container">
  <div class="fullWidth">
    <button class="btn btn-outline-dark btn-sm" type="button" onClick="history.back()">戻る</button>
  </div>
  <h3>顧客情報</h2>
  <div class="customerDetail LeftBOX">
    <div class="BOXin customerBasicInformation">
      <div class="customerNam"><?= $customer->menberNumber ?></div>
      <div class=""><span class="customerNmae" ><?= $customer->name ?> </span>様 <?= $customer->sex ?> </div>
      <div class="customerRead">( <?= $customer->read ?>　サマ )</div>
      <div class="customerIMG"></div>
    </div>
    <div class="BOXin tabsContentsArea">
        <div class="tabArea">
          <div class="editTab active">基本情報編集</div>
        </div>
        <div class="editTabsContents">
          <div class="tabscontent show">

            <form action="{{route('customer.update', ['id'=>$customer->id ])}}" method="post" >
              @csrf
              <div class="cusInfoRow" >
                <div class="editCusInfoTitle" >生年月日</div>
                <div class="editCusInfoContent" >
                  <div class="inputBirthday">
                      <input class="formInput inputYear"  type="number" name="birthdayYear"  value="<?= $customer->birthdayYear ?>"  placeholder="年" step="1" min="1900" max="2020" > 年
                      <input class="formInput inputMonth" type="number" name="birthdayMonth" value="<?= $customer->birthdayMonth ?>" placeholder="月" step="1" min="1"    max="12" > 月
                      <input class="formInput inputDay"   type="number" name="birthdayDay"   value="<?= $customer->birthdayDay ?>"   placeholder="日" step="1" min="1"    max="31" > 日
                  </div>
                </div>
              </div>
              <div class="cusInfoRow" >
                <div class="editCusInfoTitle" >電話番号</div>
                <div class="editCusInfoContent" ><input type="text" name="tel" value="<?= $customer->tel ?>" class="formInput" ></div>
              </div>
              <div class="cusInfoRow" >
                <div class="editCusInfoTitle" >メールアドレス</div>
                <div class="editCusInfoContent" ><input type="text" name="email" value="<?= $customer->email ?>" class="formInput" ></div>
              </div>
              <div class="cusInfoRow" >
                <div class="editCusInfoTitle" >住所</div>
                <div class="editCusInfoContent" >
                  <div class="inputZips">
                      <input class="formInput inputZip" type="text" name="zip21" value="<?= $customer->zip21 ?>" placeholder="150" >-
                      <input class="formInput inputZip" type="text" name="zip22" value="<?= $customer->zip22 ?>" placeholder="0022" >
                  </div>
                  <div class="inputAddrs">
                      <input class="formInput inputAddr" type="text" name="pref21" value="<?= $customer->pref21 ?>" placeholder="東京都" >
                      <input class="formInput inputAddr" type="text" name="addr21" value="<?= $customer->addr21 ?>" placeholder="渋谷区" >
                  </div>
                  <input class="formInput inputStrt" type="text" name="strt21" value="<?= $customer->strt21 ?>" placeholder="恵比寿南1丁目マンション名1101号室" >
                </div>
              </div>
              <div class="cusInfoRow" >
                <div class="editCusInfoTitle" >担当インストラクター</div>
                <div class="editCusInfoContent" >
                  <select class="formInput" name="instructor" required >
                    <option value="" >--指定しない--</option>
                    <?php foreach( $users as $user ){ ?>
                      <option value="<?= $user->id ?>" <?php if($user->id == $customer->instructor  ) echo " selected" ; ?> ><?= $user->name  ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="cusInfoRow" >
                <div class="editCusInfoTitle" >メモ</div>
                <div class="editCusInfoContent" ><textarea name="memo" class="formInput"><?= $customer->memo ?></textarea></div>
              </div>
              <div class="cusInfoRow" >
                <div class="editCusInfoTitle" >表示ステータス</div>
                <div class="editCusInfoContent" >
                  <label style="justify-content: left;"><input class="" type="checkbox" name="hidden_flag" value="1" <?php if($customer->hidden_flag == 1) echo " checked='checked' " ?> >非表示にする</label>
                </div>
              </div>
              <div class="cusInfoRow">
                <input type="submit" name="" value="更新する" class="button" >
              </div>
              <div class="cusInfoRow">
                <input type="submit" name="cancel" value="キャンセル" class="button cancel" >
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