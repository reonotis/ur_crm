@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h5>本日登録されたお客様</h5>
                    <table class="tableClass_003" >
                        <tr>
                            <th>名前</th>
                            <th>性別</th>
                            <th>担当スタイリスト</th>
                            <th>登録時間</th>
                            <th>確認</th>
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
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

