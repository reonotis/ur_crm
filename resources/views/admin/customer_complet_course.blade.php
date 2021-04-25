@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h3>養成講座修了者 一覧</h3>

        <div class="adminErea" >

            <table class="scheduleListTable" >
                <thead>
                    <tr>
                        <th>顧客名</th>
                        <th>コース確認</th>
                        <th>イントラ登録依頼メール</th>
                        <th>契約完了</th>
                        <th>入金依頼メール</th>
                        <th>入金確認</th>
                        <th>登録完了</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($CCMs as $CCM)
                        <tr>
                            <td><a href="{{ route('customer.display', ['id'=>$CCM->customer_id ]) }}">{{ $CCM->name }} 様</a></td>
                            <td><a href="" >確認</a></td>
                            <td>
                                @if($CCM->status == 5)
                                <a href="{{ route('admin.instructorRegistrRequest', ['id'=>$CCM->customer_id ]) }}" >メールを送る</a>
                                @else
                                送信済み
                                @endif
                            </td>
                            <td>未完了</td>
                            <td>-</td>
                            <td>未確認</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


        </div>


    </div>
</div>
<?php
// dd($CCM);
?>
@endsection


