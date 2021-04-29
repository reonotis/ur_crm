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
                            <th>申込日時</th>
                            <th>入金依頼メール</th>
                            <th>期日</th>
                            <th>入金完了</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($CCMs as $CCM)
                            <tr>
                                <td><a href="{{ route('customer.display', $CCM->customer_id ) }}" >{{ $CCM->name }}</a></td>
                                <td><a href="{{ route('course_detail.display', $CCM->instructor_courses_id ) }}" >{{ $CCM->course_name}}</a></td>
                                <td>{{ $CCM->date->format('Y年 m月 d日') }}</td>
                                <td>
                                    @if($CCM->status == 0)
                                        <a href="{{ route('admin.requestPaymentCourseFee',[ 'id'=>$CCM->id]) }}" >送信する</a>
                                    @else
                                        <a href="{{ route('admin.requestPaymentCourseFee',[ 'id'=>$CCM->id]) }}" > 再送信する</a>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($CCM->limit_day))
                                    {{ $CCM->limit_day->format('Y年m月d日') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td><a href="{{ route('admin.confirmedPaymentCourseFee',[ 'id'=>$CCM->id]) }}" onclick="return completePayment();" >入金完了</a></td>
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


<script>
    function completePayment(){
        var result = window.confirm('この申し込みを入金済みにします。\n講座への入金確認を終えていますか？\n\nこの操作は取り消せません');
        if( result ) return true; return false;
    }
</script>




