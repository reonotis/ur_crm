




  <div class="customerMailTitlesMain" >
    <div class="customerMailTitle send" >送信者</div>
    <div class="customerMailTitle time" >送信日時</div>
    <div class="customerMailTitle title" >タイトル</div>
  </div>

<?php foreach ($HSEmails as $key => $HSEmail) { ?>
  <div class="customerMailContents">
    <div class="customerMailTitles" id="title_{{ $HSEmail->id }}" onclick="mailContentToggle({{ $HSEmail->id }})" >
      <div class="customerMailTitle send" >{{ $HSEmail->name }}</div>
      <div class="customerMailTitle time" >{{ $HSEmail->sendtime }}</div>
      <div class="customerMailTitle title" >{{ $HSEmail->title }}</div>
    </div>
    <div class="customerMailText" id="text_{{ $HSEmail->id }}"  >{!! nl2br ($HSEmail->text) !!}</div>
  </div>
<?php  } ?>





<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>

  function mailContentToggle(id){
    const content = $('#text_'+ id) // 変数、定数への格納ももちろん可能
    content.slideToggle(200);
  }


</script>





