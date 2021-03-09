
<div class="courseAppTitle">既に申し込みがあるコース一覧</div>
      <div class="courseAppContent">
          <?php if($CoursePurchaseDetails->count() >= 1 ){?>
              <ul class="PurchaseCourseName">
                <?php foreach ($CoursePurchaseDetails as $key => $CoursePurchaseDetail) { ?>
                    <li><?= $CoursePurchaseDetail->course_name ?></li>
                <?php  } ?>
              </ul>
          <?php }else{?>
            過去に申し込まれているコースはありません。
          <?php }?>
      </div>

<div class="courseAppTitle">申し込みたいコースを選択して下さい</div>

<form action="{{route('courseDetails.applySecond')}}" method="post">
@csrf
  <select class="parent" name="parent" name="foo" >
    <option value="" selected="selected">--大項目を選択--</option>
    <?php foreach ($courses as $key => $course) { ?>
      <?php if($course->parent_id == null) {?>
        <option value="<?= $course->id ?>" ><?= $course->course_name ?></option>
      <?php }?>
    <?php } ?>
  </select>

  <select class="children" name="children" name="bar" disabled required>
    <option value="" selected="selected">--コースを選択--</option>
    <?php foreach ($courses as $key => $course) { ?>
      <?php if($course->parent_id != null) {?>
        <option value="<?= $course->id ?>" data-val="<?= $course->parent_id ?>" ><?= $course->course_name ?></option>
      <?php }?>
    <?php } ?>
  </select>
  <input type="hidden" name="customer_id" value="<?= $customer->id ?>" >
  <input type="submit" class="button" name="" value="申し込み" >
  <input type="submit" class="button cancel" name="cancel" value="キャンセル" >
</form>






<!-- <?php var_dump($courses) ;?> -->


<?php


function paymentConfirmation($pay_confirm, $payment_day ){
  if($pay_confirm === 1){
    if($payment_day){
      $date = date('Y年 m月 d日',  strtotime($payment_day)) . " に確認済" ;
    }else{
      $date = "入金確認済み" ;
    }
  }else{
    $date = "未確認" ;
  }
  return $date;
}



?>



<script>
  var $children = $('.children');
  var original = $children.html();

  $('.parent').change(function() {

    var val1 = $(this).val();
    
    $children.html(original).find('option').each(function() {
      var val2 = $(this).data('val');
      if (val1 != val2) {
        $(this).not(':first-child').remove();
      }
    });

    if ($(this).val() == "") {
      $children.attr('disabled', 'disabled');
    } else {
      $children.removeAttr('disabled');
    }

  });
</script>
