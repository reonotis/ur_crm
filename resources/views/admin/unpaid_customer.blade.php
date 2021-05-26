@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="fullWidth">
            <button class="btn btn-outline-dark btn-sm" type="button" onClick="history.back()">戻る</button>
        </div>

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
                            <th>期日</th>
                            <th>確認</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($claims as $claim)
                            <tr>
                                <td><a href="{{ route('customer.display', $claim->user_id ) }}" >{{ $claim->name }} 様</a></td>
                                <td><a href="{{ route('course_detail.display', $claim->instructor_courses_id ) }}" >{{ $claim->course_name}}</a></td>
                                <td>{{ $claim->date }}</td>
                                <td>{{ $claim->limit_date->format('Y-m-d') }}</td>
                                <td><a href="{{ route('admin.courseMappingShow',[ 'id'=>$claim->CCM_id]) }}" >確認</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="admin_h5_wrap"><h5 class="admin_h5">インストラクターからの集金</h5></div>

            <div class="adminBtnErea">
                <table class="scheduleListTable" >
                    <thead>
                        <tr>
                            <th>顧客名</th>
                            <th>請求項目</th>
                            <th>金額</th>
                            <th>期日</th>
                            <th>確認</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($instructorClaims as $instructorClaim)
                            <tr>
                                <td><a href="{{ route('user.display', $instructorClaim->user_id ) }}" >{{ $instructorClaim->name }}</a></td>
                                <td>{{ $instructorClaim->title }}</td>
                                <td>{{ number_format($instructorClaim->price) }}円</td>
                                <td>{{ $instructorClaim->limit_date->format('Y-m-d') }}</td>
                                <td><a href="{{ route('claim.show', $instructorClaim->id ) }}" >確認</a></td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>


        </div>


    </div>
</div>
<?php
    // dd($instructorClaims);
?>
@endsection


<script>
    function completePayment(){
        var result = window.confirm('この申し込みを入金済みにします。\n講座への入金確認を終えていますか？\n\nこの操作は取り消せません');
        if( result ) return true; return false;
    }
</script>




