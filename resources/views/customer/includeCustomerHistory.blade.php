<table class="courseHistoryTable">
  <tr>
    <th>日時</th>
    <th>コース内容</th>
    <th>状況</th>
    <th>インストラクター</th>
  </tr>

  <?php foreach ($CustomerSchedules as $key => $CustomerSchedule) { ?>
    <tr>
      <td>
        <?= date('Y年m月d日　H:i',  strtotime($CustomerSchedule->date_time)) ?>
      </td>
      <td>
        <?= $CustomerSchedule->course_name  ?> の　
        <?= $CustomerSchedule->howMany  ?>回目
      </td>
      <td><?= $CustomerSchedule->status?></td>
      <td><?= $CustomerSchedule->intrName ?></td>
    </tr>
  <?php  } ?>

</table>








