
<div class="LeftBOX">
  <a href="{{route('user.newClaim', ['id' => $user->id ] ) }}" >
    <div class="button BOXin">新しく請求する</div>
  </a>
</div>


<table class="courseHistoryTable">
  <tbody>
    <tr>
      <th>項目</th>
      <th>金額</th>
      <th>請求日</th>
      <th>請求状態</th>
      <th>入金日</th>
      <th>確認</th>
    </tr>
    @foreach($claims as $claim)
      <tr>
        <td>{{ $claim->title }}</td>
        <td>{{ number_format($claim->price) }} 円</td>
        <td>{{ $claim->claim_date }}</td>
        <td>
          {{ $claim->statusName }}
        </td>
        <td>{{ $claim->payment_complete_date }}</td>
        <td><a href="{{ route('claim.show',['id'=>$claim->id]) }}">確認</a></td>
      </tr>
    @endforeach
  </tbody>
</table>










<?php

// dd($user->id);
?>