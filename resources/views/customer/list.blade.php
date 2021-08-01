@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">顧客一覧</div>
                <div class="card-body">
                    <table class="tableClass_001" >
                        <tr>
                            <th class="id" >ID</th>
                            <th>顧客番号</th>
                            <th>顧客名</th>
                            <th>店舗</th>
                            <th>担当</th>
                            <th>最終来店日</th>
                            <th>登録日</th>
                            <th>確認</th>
                        </tr>
                        @foreach($customers as $customer)
                            <tr>
                                <td class="id" ><?= $customer->id ?></td>
                                <td><?= $customer->member_number ?></td>
                                <td><?= $customer->f_name . ' ' . $customer->l_name ?>
                                    <span class="name_read" > ( <?= $customer->f_read . ' ' . $customer->l_read ?> )</span>
                                </td>
                                <td><?= $customer->shop_name ?></td>
                                <td><?= $customer->name ?></td>
                                <td></td>
                                <td></td>
                                <td><a href="{{route('customer.show', ['id' => $customer->id ])}}" >確認</a></td>
                            </tr>
                        @endforeach
                    </table>

        {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

