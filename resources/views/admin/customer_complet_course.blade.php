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
                        <th>確認</th>
                        <th>イントラにする</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($CCMs as $CCM)
                        <tr>
                            <td><a href="{{ route('customer.display', ['id' =>$CCM->customer_id ]) }}">{{ $CCM->name }} 様</a></td>
                            <td></td>
                            <td><a href="" >イントラにする</a></td>
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


