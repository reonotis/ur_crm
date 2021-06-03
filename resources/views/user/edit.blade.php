
@extends('layouts.app')

@section('content')




@yield('content')

<div class="container">
  <div class="fullWidth">
    <button class="btn btn-outline-dark btn-sm" type="button" onClick="history.back()">戻る</button>
  </div>
  <h3>インストラクター情報編集</h3>
  <div class="customerDetail LeftBOX">
    <div class="BOXin customerBasicInformation">
      <div class="customerNam"><?= $user->intr_No ?></div>
      <div class=""><span class="customerNmae" ><?= $user->name ?> </span></div>
      <div class="customerRead">( <?= $user->read ?> )</div>
      <div class="customerIMG"></div>
    </div>
    <div class="BOXin tabsContentsArea">
      <div class="BOXin userEdit">
        <form action="{{ route('user.update',['id'=>$user->id]) }}" method="post" >
          @csrf
          <div class="intrEditRow">
            <div class="intrEditTitle">名前</div>
            <div class="intrEditContent">
                  <input class="formInput name" type="text" name="name" value="<?= $user->name ?>" >
            </div>
          </div>
          <div class="intrEditRow">
            <div class="intrEditTitle">メールアドレス</div>
            <div class="intrEditContent">
                  <input class="formInput name" type="email" name="email" value="<?= $user->email ?>" >
            </div>
          </div>
          <div class="intrEditRow">
            <div class="intrEditTitle">権限</div>
            <div class="intrEditContent">
              <select name="authority_id" >
                <option value="9" <?php if($user->authority_id == 9 ) echo " selected" ?> >なし</option>
                <option value="7" <?php if($user->authority_id == 7 ) echo " selected" ?> >エージェント</option>
                <option value="6" <?php if($user->authority_id == 6 ) echo " selected" ?> >社員</option>
                <option value="5" <?php if($user->authority_id == 5 ) echo " selected" ?> >課長</option>
                <option value="4" <?php if($user->authority_id == 4 ) echo " selected" ?> >部長</option>
                <option value="3" <?php if($user->authority_id == 3 ) echo " selected" ?> >総務</option>
              </select>
            </div>
          </div>
          <input type="submit" name="" value="更新する" class="button">
          編集機能作成中
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

<?php
  // dd( $user );
?>
