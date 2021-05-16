@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="fullWidth">
            <button class="btn btn-outline-dark btn-sm" type="button" onClick="history.back()">戻る</button>
        </div>
        <h3>養成講座修了者 一覧</h3>

        <div class="adminErea" >

            <table class="scheduleListTable" >
                <thead>
                    <tr>
                        <th>顧客名</th>
                        <th>コース確認</th>
                        <th>規約同意依頼メール</th>
                        <th>契約</th>
                        <!-- <th>入金依頼メール</th>
                        <th>入金確認</th>
                        <th>登録完了</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($CCMs as $CCM)
                        <tr>
                            <td><a href="{{ route('customer.display', ['id'=>$CCM->customer_id ]) }}">{{ $CCM->name }} 様</a></td>
                            <td><a href="{{ route('course_detail.display', ['id'=>$CCM->instructor_courses_id ]) }}" >確認</a></td>
                            <td>
                                @if($CCM->status == 5)
                                    <a href="{{ route('admin.instructorRegistrRequest', ['id'=>$CCM->customer_id ]) }}" >メールを送る</a>
                                @elseif($CCM->status == 6)
                                    <a href="{{ route('admin.instructorRegistrRequest', ['id'=>$CCM->customer_id ]) }}" >再送信する</a>
                                @else
                                    送信済み
                                @endif
                            </td>
                            <td>
                                @if($CCM->status < 6)
                                    未完了
                                @elseif($CCM->status == 6)
                                    <a href="{{ route('admin.completeContract', ['id'=>$CCM->id ]) }}" onclick="return confilmCompleteContract();" >完了にする</a>
                                @else
                                    完了
                                @endif
                            </td>
                            <!-- <td>
                                @if($CCM->status < 7)
                                    未送信
                                @elseif($CCM->status == 7)
                                    <a href="{{ route('admin.RequestAnnualMembershipFee', ['id'=>$CCM->customer_id ]) }}" >メールを送る</a>
                                @else
                                    <a href="{{ route('admin.RequestAnnualMembershipFee', ['id'=>$CCM->customer_id ]) }}" >再送する</a>
                                @endif
                            </td>
                            <td>
                                @if($CCM->status < 8)
                                    未確認
                                @elseif($CCM->status == 8)
                                    <a href="" onclick="return confilmAnnualMembershipFee();" >完了にする</a>
                                @else
                                    完了
                                @endif
                            </td>
                            <td>未確認</td> -->
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


<script>
    function confilmCompleteContract(){
        var result = window.confirm('このお客様の契約を完了しますか？\n完了した時点でインストラクターに登録されますが、権限は付与されません。');
        if( result ) return true; return false;
    }

    
    function confilmAnnualMembershipFee(){
        var result = window.confirm('年会費の入金を確認済みにします。\n講座への入金を確認しましたか？\n\nこの操作は取り消せません');
        if( result ) return true; return false;
    }


</script>

