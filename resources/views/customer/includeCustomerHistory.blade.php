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
      <td><?= $CustomerSchedule->status?></td>
      <td><?= $CustomerSchedule->intrName ?></td>
      <td><a href="{{route('courseDetails.scheduleEdit', ['id' => $CustomerSchedule->id ] )}}" >詳細を見る</a></td>
    </tr>
  <?php  } ?>

</table>








