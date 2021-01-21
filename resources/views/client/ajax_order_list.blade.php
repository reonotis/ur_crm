

<table class="callListTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>日付</th>
            <th>営業</th>
            <th>状態</th>
            <th>商品</th>
            <th>金額</th>
        </tr>
    </thead>
    <tbody>
        @foreach( $orders as $order )
        <tr onclick="call_order_disp({{ $order -> id}})" id="row_{{ $order -> id}}">
            <td>{{ $order -> id}}</td>
            <td><?= date('y/m/d', strtotime( $order -> date )); ?></td>
            <td>{{ $order -> name }}</td>
            <td>{{ $order -> status }}</td>
            <td>{{ $order -> product }}</td>
            <td>{{ $order -> fee }}円</td>
        </tr>
        @endforeach
    </tbody>
</table>