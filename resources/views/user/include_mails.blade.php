

<div class="LeftBOX">
  <a href="{{ route('user.sendEmail',['id'=>$user->id ]) }}" >
    <div class="button BOXin">新しくメールを送付する</div>
  </a>
</div>


<div class="customerMailTitlesMain">
  <div class="customerMailTitle send">送信者</div>
  <div class="customerMailTitle time">送信日時</div>
  <div class="customerMailTitle title">タイトル</div>
</div>

@foreach($HSEIs as $HSEI)
  <div class="customerMailContents">
    <div class="customerMailTitles" id="title_{{ $HSEI->id }}" onclick="mailContentToggle({{ $HSEI->id }})" >
      <div class="customerMailTitle send" >{{ $HSEI->name }}</div>
      <div class="customerMailTitle time" >{{ $HSEI->sendtime }}</div>
      <div class="customerMailTitle title" >{{ $HSEI->title }}</div>
    </div>
    <div class="customerMailText" id="text_{{ $HSEI->id }}"  >{!! nl2br ($HSEI->text) !!}</div>
</div>
@endforeach







<?php

// dd($user->id);
?>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>

  function mailContentToggle(id){
    const content = $('#text_'+ id) // 変数、定数への格納ももちろん可能
    content.slideToggle(200);
  }


</script>


