@extends('layouts.report')
@section('pageTitle', '日報')

@section('content')
<div class="flex" >
    <div class="basic-report-area">
        <div class="report-title" >基本レポート</div>
        <div class="report-contents" >
            <table class="basic-report-tbl" >
                <tr>
                    <th>来店客数</th>
                    <td>{{ $basicReport['todayCount'] }}人</td>
                </tr>
                <tr>
                    <th>カルテ登録人数</th>
                    <td>{{ count($todayCustomers) }}人</td>
                </tr>
                <tr>
                    <th>稼働スタイリスト</th>
                    <td>{{ count($basicReport['opeMembers']) }}人</td>
                </tr>
            </table>
            <div class="report_index_report_wrapper" >スタイリスト別 担当顧客人数</div>
            <table class="basic-report-tbl" >
                @foreach($basicReport['opeMembers'] AS $opeMember)
                    <tr>
                        <th>{{ $opeMember->name }}</th>
                        <td>{{ $opeMember->count }}人</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="vis-history-area">
        <div class="report-contents-area">
            <div class="report-title" >本日来店されたお客様</div>
            <div class="report-contents" >
                @if(!count($visitHistories))
                    本日来店されたお客様はいません
                @else
                    <table class="list-tbl non-stylist-customer-tbl" >
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>顧客番号</th>
                            <th>顧客名</th>
                            <th>メニュー</th>
                            <th>スタイリスト</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($visitHistories AS $visitHistory)
                            <tr>
                                <td>{{ $visitHistory->id }}</td>
                                <td>{{ $visitHistory->customer_no }}</td>
                                <td>{{ $visitHistory->f_name . " " . $visitHistory->l_name }}様</td>
                                <td>{{ $visitHistory->menu_name }}</td>
                                <td>{{ $visitHistory->name }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
        <div class="report-contents-area">
            <div class="report-title" >本日登録されたお客様</div>
            <div class="report-contents" >
                @if(!count($todayCustomers))
                    本日登録されたお客様はいません
                @else
                    <table class="list-tbl report-tbl" >
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>顧客番号</th>
                                <th>顧客名</th>
                                <th>スタイリスト</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todayCustomers AS $todayCustomer)
                                <tr>
                                    <td class="tbl-id" >{{ $todayCustomer->id }}</td>
                                    <td>{{ $todayCustomer->customer_no }}</td>
                                    <td>{{ $todayCustomer->f_name . " " . $todayCustomer->l_name }}様</td>
                                    <td>
                                        @if ($todayCustomer->staff_id)
                                            {{ $todayCustomer->name }}
                                        @else
                                            <a href="{{ route('report.setStylist', ['customer'=>$todayCustomer->id]) }}" >スタイリストを設定する</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>

<script>

    $(".report-title").click(function() {

        if($(this).hasClass('report-title-close')){
            $(this).removeClass('report-title-close')
            $(this).next().slideDown(200);
        }else{
            $(this).addClass('report-title-close')
            $(this).next().slideUp(200);
        }

    })



</script>

@endsection

