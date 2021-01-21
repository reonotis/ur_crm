
<table class="callListTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>日時</th>
            <th>担当者</th>
            <th>営業</th>
            <th>方法</th>
            <th>結果</th>
            <th>内容</th>
        </tr>
    </thead>
    <tbody>
        @foreach( $Contacts as $Contact )
        <tr onclick="callDisplaySet({{ $Contact -> id}})" id="row_{{ $Contact -> id}}">
            <td>{{ $Contact -> id}}</td>
            <td><?= date('y/m/d H:i', strtotime( $Contact -> history_datetime )); ?></td>
            <td>{{ $Contact -> recipient_name}}</td>
            <td>{{ $Contact -> name}}</td>
            <td>{{ $Contact -> mean_name}}</td>
            <td>{{ $Contact -> result_name}}</td>
            <td class="history_detail">{{ Str::limit($Contact -> history_detail, 20) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>