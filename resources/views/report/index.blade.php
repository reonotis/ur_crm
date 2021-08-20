@extends('layouts.app')

@section('content')
<div class="report_index_area">
    <div class="report_index_report_area">
        来店人数や対応内容の集計が入ります。
    </div>
    <div class="report_index_contents_area">
        <div class="report_index_register_customer_area">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <h5>本日来店されたお客様</h5>
            @if(!empty($visit_histories[0]))
                <a href="{{ route('VisitHistory.edit') }}" class="button" >来店情報を編集する</a>
            @endif
            @if(!empty($visit_histories[0]))
                <table class="tableClass_003" >
                    <tr>
                        <th>来店時間</th>
                        <th>名前</th>
                        <th>担当スタイリスト</th>
                        <th>メニュー</th>
                        <th>来店タイプ</th>
                        <th>削除</th>
                    </tr>
                    @foreach($visit_histories as $visit_history)
                        <tr>
                            <td><?= date('H:i', strtotime($visit_history->vis_time)) ?></td>
                            <td><a href="{{route('customer.show', ['id' => $visit_history->customer_id ])}}"><?= $visit_history->f_name.$visit_history->l_name ?> 様</a></td>
                            <td><?= $visit_history->name ?></td>
                            <td><?= $visit_history->menu_name ?></td>
                            <td><?= '' ?></td>
                            <td><a href="{{ route('VisitHistory.destroy', ['id'=>$visit_history->id ]) }}" >削除</a></td>
                        </tr>
                    @endforeach
                </table>
            @else
                本日の来店履歴はありません
            @endif
        </div>
        <div class="report_index_register_customer_area">
            <h5>本日来店時に登録されたお客様</h5>
            @if(!empty($customers[0]))
            <table class="tableClass_003" >
                <tr>
                    <th>名前</th>
                    <th>性別</th>
                    <th>担当スタイリスト</th>
                    <th>登録時間</th>
                    <th>確認</th>
                    <th>削除</th>
                </tr>
                @foreach($customers as $customer)
                    <tr>
                        <td><?= $customer->f_name." ".$customer->l_name ?>様</td>
                        <td><?= $customer->sex_name ?></td>
                        <td>
                            @if($customer->staff_id)
                                <?= $customer->user_name ?>
                            @else
                                <a href="{{route('report.set_stylist', ['id' => $customer->id ])}}" >スタイリストを設定する</a>
                            @endif
                        </td>
                        <td><?= $customer->created_at->format('m月d日 H:i') ?></td>
                        <td><a href="{{route('customer.show', ['id' => $customer->id ])}}" class="button" >確認する</a></td>
                        <td>
                            @if(!$customer->visit_history_id)
                                <a href="{{ route('customer.delete',['id'=>$customer->id ]) }}" class="" onclick="return confirmDelete()" >削除</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
            @else
                本日の来店時に登録されたお客様はいません
            @endif
        </div>
    </div>

</div>

<script>
    function confirmDelete(){
        var result = confirm('このお客様データを削除しますか？\nこの操作は取り消せません。');
        return result;
    }
</script>

@endsection

