@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('customer.nav', ['id' =>$customer->id ])
        <div class="col-md-8">

            <div class="card">

                <div class="card-body">
                    <table class="tableClass_002">
                        <tr>
                            <th>会員番号</th>
                            <td><?= $customer->member_number ?></td>
                        </tr>
                        <tr>
                            <th>名前</th>
                            <td><?= $customer->f_name.' '.$customer->l_name ?> <span class="name_read" > ( <?= $customer->f_read . ' ' . $customer->l_read ?> )</span> 様</td>
                        </tr>
                        <tr>
                            <th>性別</th>
                            <td><?= $customer->sex_name ?></td>
                        </tr>
                        <tr>
                            <th>生年月日</th>
                            <td><?= $customer->birthday ?></td>
                        </tr>
                        <tr>
                            <th>登録店舗 : 担当者</th>
                            <td><?= $customer->shop_name.'　：　'.$customer->staff_name ?></td>
                        </tr>
                        <tr>
                            <th>電話番号</th>
                            <td><?= $customer->tel ?></td>
                        </tr>
                        <tr>
                            <th>住所</th>
                            <td><?= $customer->zip21."-".$customer->zip22 ?><br>
                                <?= $customer->pref21."".$customer->addr21."".$customer->strt21 ?>
                            </td>
                        </tr>
                        <tr>
                            <th>コメント</th>
                            <td>{!! nl2br(e($customer->memo)) !!}</td>
                        </tr>
                    </table>
                    @if(\Auth::user()->authority_id <= 4 )
                        <a href="{{route('customer.edit', ['id' => $customer->id ])}}" class="btn btn-outline-success">編集する</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
