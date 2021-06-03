
<table class="courseHistoryTable">
  <tbody>
    <tr>
      <th>顧客番号</th>
      <th>顧客名</th>
    </tr>
    @foreach($customers as $customer)
      <tr>
        <td> {{ $customer->menberNumber }} </td>
        <td><a href="{{ route('customer.display', ['id'=> $customer->id] ) }}" >{{ $customer->name }}</a></td>
      </tr>
    @endforeach
  </tbody>
</table>
表示内容確認中








<?php


?>