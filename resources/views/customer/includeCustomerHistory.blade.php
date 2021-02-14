<table class="courseHistoryTable">
  <tr>
    <th>日時</th>
    <th>コース内容</th>
    <th>状況</th>
    <th>インストラクター</th>
    <th>詳細</th>
  </tr>

  <?php foreach ($CustomerSchedules as $key => $CustomerSchedule) { ?>
    <tr>
      <td>
        <?= date('Y年m月d日 ',  strtotime($CustomerSchedule->date)) ?>
        <?= date('　h:i',  strtotime($CustomerSchedule->time)) ?>
      </td>
      <td>
        <?= $CustomerSchedule->course_name  ?> の　
        <?= $CustomerSchedule->howMany  ?>回目
      </td>
      <td><?= getAttendanceStatus($CustomerSchedule->status) ; ?></td>
      <td><?= $CustomerSchedule->name ?></td>
      <td><a href="" >詳細を見る</a></td>
    </tr>
  <?php  } ?>

</table>


<?php
  /**
   * 受講ステータスを表示する
   */
  function getAttendanceStatus($date){
    return ($date) ? "受講済み" : "未受講" ;
  }


?>