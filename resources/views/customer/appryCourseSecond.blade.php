
@extends('layouts.app')

@section('content')



@yield('content')

<div class="">
  <h2>顧客情報</h2>
  <div class="customerDetail LeftBOX">
    <div class="BOXin customerBasicInformation">
      <div class="customerNam"><?= $customer->menberNumber ?></div>
      <div class=""><span class="customerNmae" ><?= $customer->name ?> </span>様 <?= $customer->sex ?> </div>
      <div class="customerRead">( <?= $customer->read ?>　サマ )</div>
      <div class="customerIMG"></div>
    </div>
    <div class="BOXin tabsContentsArea">
        <div class="tabArea">
          <div class="editTab active">コースの申し込み</div>
        </div>
        <div class="editTabsContents">
          <div class="tabscontent show">

            <form action="{{route('courseDetails.courseApply')}}" method="post">
              @csrf
              <table class="customerSearchTable" >
                <tr>
                  <th>申し込みコース</th>
                  <td><?= $course->course_name ?></td>
                </tr>
                <tr>
                  <th>回数</th>
                  <td>
                    <div class="inputUnits">
                      <input type="number" class="formInput inputUnit" name="how_many_times" value="<?= $course->how_many_times ?>" >回
                    </div>
                  </td>
                </tr>
                <tr>
                  <th>料金</th>
                  <td>
                    <div class="inputUnits">
                      <input type="number" class="formInput inputUnit" name="price" value="<?= $course->price ?>" step="100">円
                    </div>
                  </td>
                </tr>
                <tr>
                  <th>入金確認</th>
                  <td>
                    <div class="inputUnits">
                      <label><input type="checkbox" name="pay_confirm" value="1" >入金確認済み</label>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <input type="hidden" name="customer_id" value="<?= $customer->id ?>" >
                    <input type="hidden" name="course_id" value="<?= $course->id ?>" >
                    <input type="submit" class="button" name="" value="登録する" >
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <input type="submit" class="button cancel" name="cancel" value="キャンセル" >
                  </td>
                </tr>
              </table>
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