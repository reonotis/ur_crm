
<!-- <div class="LeftBOX">
  <a href="{{route('courseDetails.apply', ['id' => $customer->id ] )}}">
    <div class="button BOXin">新しいコースの申し込み</div>
  </a>
</div> -->




<table class="courseHistoryTable">
  <tr>
    <th>購入日</th>
    <th>購入コース</th>
    <th>金額</th>
    <th>入金確認</th>
    <th>確認</th>
  </tr>
  <?php foreach ($CoursePurchaseDetails as $key => $CoursePurchaseDetail) { ?>


    <tr>
      <td><?= date('Y年 m月 d日',  strtotime($CoursePurchaseDetail->date)) ?></td>
      <!-- <td><a href="{{route('course_detail.display', ['id' => $CoursePurchaseDetail->instructor_courses_id ] )}}" ><?= $CoursePurchaseDetail->course_name ?></a></td> -->
      <td><?= $CoursePurchaseDetail->course_name ?></td>
      <td><?= number_format($CoursePurchaseDetail->price) ?>円</td>
      <td><?= paymentConfirmation($CoursePurchaseDetail->claimStatus , $CoursePurchaseDetail->complete_date ) ?></td>
      <td><a href="{{route('course_detail.display', ['id' => $CoursePurchaseDetail->instructor_courses_id ] )}}" >確認</a></td>
    </tr>
  <?php  } ?>

</table>









<?php
  function paymentConfirmation($claimStatus, $complete_date ){
    $comment = "";
    if($claimStatus && $complete_date){
      if($claimStatus === 0){
        $comment = "未確認";
      }else if($claimStatus === 1){
        $comment = "請求中";
      }else if($claimStatus === 3){
        $comment = "キャンセル";
      }else if($claimStatus === 5){
        if($complete_date){
          $comment =  date('Y年 m月 d日',  strtotime($complete_date)) . " に計上済" ;
        }
      }
    }
    return $comment;
  }


?>