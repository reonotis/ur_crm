@extends('layouts.report')

@section('js')
    <script src="{{ asset('js/reception_table.js') }}?<?= date('Ymdhi') ?>"></script>
@endsection

@section('breadcrumb')
    <ol>
        <li><a href="{{ route('home') }}">ホーム</a></li>
    </ol>
@endsection
@section('pageTitle', '日報')

@section('content')
    <div class="flex">
        <div class="basic-report-area">
            <div class="report-title">基本レポート</div>
            <div class="report-contents">
                <table class="basic-report-tbl">
                    <tr>
                        <th>営業日</th>
                        <td>{{ $businessTime['date']->format('Y年m月d日') }}</td>
                    </tr>
                    <tr>
                        <th>営業時間</th>
                        <td>
                            @if($businessTime['temporary'])
                                @if($businessTime['temporary']->holiday)
                                    臨時休業
                                @else
                                    臨時営業<br>
                                    <p>O : {{ Carbon\Carbon::createFromTimeString($businessTime['business_hours']['business_open_time'])->format('H:i') }}</p>
                                    <p>L : {{ Carbon\Carbon::createFromTimeString($businessTime['business_hours']['business_close_time'])->format('H:i') }}</p>
                                    <p>C : {{ Carbon\Carbon::createFromTimeString($businessTime['business_hours']['last_reception_time'])->format('H:i') }}</p>
                                @endif
                            @elseif($businessTime['close'])
                                閉店しています
                            @elseif(is_null($businessTime['business_hours']))
                                未設定
                            @else
                                <p>O : {{ Carbon\Carbon::createFromTimeString($businessTime['business_hours']['business_open_time'])->format('H:i') }}</p>
                                <p>L : {{ Carbon\Carbon::createFromTimeString($businessTime['business_hours']['business_close_time'])->format('H:i') }}</p>
                                <p>C : {{ Carbon\Carbon::createFromTimeString($businessTime['business_hours']['last_reception_time'])->format('H:i') }}</p>
                            @endif
                        </td>
                    </tr>
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
                <div class="report-index-report-wrapper">スタイリスト別 担当顧客人数</div>
                <table class="basic-report-tbl">
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
                <div class="report-title">本日来店されたお客様</div>
                <div class="report-contents">
                    @if(!count($visitHistories))
                        本日来店されたお客様はいません
                    @else
                        <table class="list-tbl report-tbl">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>顧客番号</th>
                                <th>顧客名</th>
                                <th>メニュー</th>
                                <th>スタイリスト</th>
                                <th>編集</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($visitHistories AS $visitHistory)
                                <tr>
                                    <td class="tbl-id">{{ $visitHistory->id }}</td>
                                    <td>{{ $visitHistory->customer_no }}</td>
                                    <td>
                                        @if($shopAuthorizationFlg->user_read)
                                            <a href="{{ route('customer.show', ['customer'=>$visitHistory->customer_id]) }}" class="customer-anchor sex-{{ $visitHistory->sex }}" >
                                                {{ $visitHistory->f_name . " " . $visitHistory->l_name }}
                                                <span class="honorific-title">&nbsp;様</span>
                                            </a>
                                        @else
                                            <p class="sex-{{ $visitHistory->sex }}">
                                                {{ $visitHistory->f_name . " " . $visitHistory->l_name }}
                                                <span class="honorific-title">&nbsp;様</span>
                                            </p>
                                        @endif
                                    </td>
                                    <td>{{ $visitHistory->menu_name }}</td>
                                    <td>{{ $visitHistory->name }}</td>
                                    <td>
                                        <div class="">
                                            <form
                                                action="{{ route('visitHistory.destroy', ['reserve_info'=>$visitHistory->id]) }}"
                                                method="POST"
                                                onsubmit="return confirmDialog({{ ConfirmMessage::VISIT_HISTORY_DELETE }});">
                                                @method('POST')
                                                @csrf
                                                <input type="submit" name="" value="削除" class="delete-btn min-btn">
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
            <div class="report-contents-area">
                <div class="report-title">本日登録されたお客様</div>
                <div class="report-contents">
                    @if(!count($todayCustomers))
                        本日登録されたお客様はいません
                    @else
                        <table class="list-tbl report-tbl">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>顧客番号</th>
                                <th>顧客名</th>
                                <th>スタイリスト</th>
                                <th>編集</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($todayCustomers AS $todayCustomer)
                                <tr>
                                    <td class="tbl-id">{{ $todayCustomer->id }}</td>
                                    <td>{{ $todayCustomer->customer_no }}</td>
                                    <td>
                                        @if($shopAuthorizationFlg->user_read)
                                            <a href="{{ route('customer.show', ['customer'=>$todayCustomer->id]) }}"
                                               class="customer-anchor sex-{{ $todayCustomer->sex }}">
                                                {{ $todayCustomer->f_name . " " . $todayCustomer->l_name }}
                                                <span class="honorific-title">&nbsp;様</span>
                                            </a>
                                        @else
                                            <p class="sex-{{ $todayCustomer->sex }}">
                                                {{ $todayCustomer->f_name . " " . $todayCustomer->l_name }}
                                                <span class="honorific-title">&nbsp;様</span>
                                            </p>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($todayCustomer->staff_id)
                                            {{ $todayCustomer->name }}
                                        @else
                                            <a href="{{ route('report.setStylist', ['customer'=>$todayCustomer->id]) }}">スタイリストを設定する</a>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="">
                                            @if($shopAuthorizationFlg->customer_delete)
                                                <form
                                                    action="{{ route('customer.destroyReport', ['customer'=>$todayCustomer->id]) }}"
                                                    method="POST"
                                                    onsubmit="return confirmDialog({{ ConfirmMessage::CUSTOMER_DELETE }});">
                                                    @method('POST')
                                                    @csrf
                                                    <input type="submit" name="" value="削除" class="delete-btn min-btn">
                                                </form>
                                            @endif
                                        </div>
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

    <div class="report-contents-area">
        <div class="report-title">受付表</div>
        <div class="report-contents" style="min-height: 80px">
            <div id="loading" style="display: none"></div>
            <div id="reserve_list"></div>
        </div>
    </div>

    <script>
        $(".report-title").click(function () {
            if ($(this).hasClass('report-title-close')) {
                $(this).removeClass('report-title-close')
                $(this).next().slideDown(200);
            } else {
                $(this).addClass('report-title-close')
                $(this).next().slideUp(200);
            }
        })
    </script>

@endsection


