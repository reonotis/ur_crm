@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h3>未入金者 一覧</h3>

        <div class="adminErea" >
            <div class="admin_h5_wrap"><h5 class="admin_h5">コース代金</h5></div>

            <div class="adminBtnErea">
                <table class="scheduleListTable" >
                    <thead>
                        <tr>
                            <th>顧客名</th>
                            <th>受講予定コース</th>
                            <th>期日</th>
                            <th>確認</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($CCMs as $CCM)
                            <tr>
                                <td><a href="{{ route('customer.display', $CCM->customer_id ) }}" >{{ $CCM->name }}</a></td>
                                <td>{{ $CCM->course_name}}</td>
                                <td> - -</td>
                                <td><a href="" >確認</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="admin_h5_wrap"><h5 class="admin_h5">年会費</h5></div>

            <div class="adminBtnErea">
                <table class="scheduleListTable" >
                    <thead>
                        <tr>
                            <th>顧客名</th>
                            <th>確認</th>
                            <th>イントラにする</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>


        </div>


    </div>
</div>
<?php
// dd($CCM);
?>
@endsection


