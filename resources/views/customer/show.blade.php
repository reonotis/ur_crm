@extends('layouts.customer')
@section('pageTitle', '顧客詳細')

@section('content')
    <div class="customer-info-area" >
        <div class="customer-basic-infos customer-contents-box" >
            <div class="customer-img" >
                画像はありません。<br><br>
                来店履歴のデータに画像を挿入した場合に自動的に更新されます。
            </div>
            <div class="customer-no" >{{ $customer->customer_no }}</div>
            <div class="customer-name" >
                <p>{{ $customer->f_name }}&nbsp;{{ $customer->l_name }}&nbsp;<span class="rem-3/4">様</span></p>
                <p class="customer-read" >
                    (&nbsp;<span class="rem-3/4">{{ $customer->f_read }}&nbsp;{{ $customer->l_read }}</span>&nbsp;)&nbsp;<span class="rem-3/4">サマ</span>
                </p>
            </div >
            <div class="customer-sex" >性別&nbsp;:&nbsp;
                @if($customer->sex)
                    <span class="sex-{{ $customer->sex }}" >
                        {{ Common::SEX_LIST[$customer->sex] }}&nbsp;{{ Common::SEX_SYMBOL[$customer->sex] }}
                    </span>
                @endif
            </div >
            <div class="customer-birthday" ></div >
            <div class="customer-shop" >
                登録店舗&nbsp;:&nbsp;
                @if(!empty($customer->shop->shop_name))
                    {{ $customer->shop->shop_name }}
                @endif
            </div>
            <div class="customer-staff" >
                担当者&nbsp;:&nbsp;
                @if(!empty($customer->user->name))
                    {{ $customer->user->name }}
                @endif
            </div>
        </div>
        <div class="customer-contents-area customer-contents-box" >
            <div class="customer-show-title" >基本情報</div>
            <div class="customer-detail-row" >
                <div class="customer-detail-title" >電話番号</div>
                <div class="customer-detail-content" >{{ $customer->tel }}</div>
            </div>
            <div class="customer-detail-row" >
                <div class="customer-detail-title" >住所</div>
                <div class="customer-detail-content" >
                    {{ $customer->zip21 }}-{{ $customer->zip22 }}<br>
                    {{ $customer->pref21 }}&nbsp;{{ $customer->address21 }}<br>
                    {{ $customer->street21 }}
                </div>
            </div>
            <div class="customer-detail-row" >
                <div class="customer-detail-title" >メモ</div>
                <div class="customer-detail-content" >{!! nl2br(e($customer->memo)) !!}</div>
            </div>
            <div class="customer-detail-row" >
                <div class="customer-detail-title" >アンケート</div>
                <div class="customer-detail-content" >
                    <p class="question-title" >{{ Common::Q1_MESSAGE }}</p>
                    @if(!empty($customer->question1))
                        @php
                            $answers1 = explode (",", $customer->question1);
                        @endphp
                        @foreach($answers1 AS $answer)
                            <span class="question-answer" >{{ Common::QUESTION_1_LIST[$answer] }}</span>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="customer-detail-row" >
                <div class="customer-detail-title" >来店時のコメント</div>
                <div class="customer-detail-content" >{!! nl2br(e($customer->comment)) !!}</div>
            </div>
            <div class="flex" >
                @if(!empty(Auth::user()->checkAuthByShopId($customer->shop_id)->customer_edit) &&
                    Auth::user()->checkAuthByShopId($customer->shop_id)->customer_edit &&
                    Auth::user()->userShopAuthorization->customer_edit
                    )
                    <a href="{{ route('customer.edit', ['customer'=>$customer->id]) }}" class="submit edit-btn" >編集する</a>
                @endif
                @if(!empty(Auth::user()->checkAuthByShopId($customer->shop_id)->customer_delete) &&
                    Auth::user()->checkAuthByShopId($customer->shop_id)->customer_delete &&
                    Auth::user()->userShopAuthorization->customer_delete
                    )
                    <div class="" style="margin: 0 auto;" >
                        <form action="{{ route('customer.destroy', ['customer'=>$customer->id]) }}" method="POST" onsubmit="return deleteConfirm()" >
                            @method('DELETE')
                            @csrf
                            <input type="submit" name="" value="削除する" class="delete-btn" >
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="customer-history-area customer-contents-box" >
        <div class="customer-show-title" >来店履歴</div>

        @if (!count($visitHistories))
            来店履歴はありません
        @else
            {{-- safariでバグるので table ではなく、divにしようかな。--}}
            <table class="customer-history-table">
                <thead >
                    <tr>
                        <th>id</th>
                        <th>来店日時</th>
                        <th>担当</th>
                        <th>予約タイプ</th>
                        <th>メニュー</th>
                        <th>画像</th>
                        <th>コメント</th>
                        <th>編集</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($visitHistories AS $visitHistory)
                        <tr>
                            <td class="tbl-id" >{{ $visitHistory->id }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($visitHistory->vis_date)->format("Y/m/d") }}
                                {{ substr($visitHistory->vis_time, 0, 5) }}
                            </td>
                            <td>{{ $visitHistory->name }}</td>
                            <td>
                                @if($visitHistory->visit_type_id)
                                    {{ $visitHistory->visit_type_id }}
                                @endif
                            </td>
                            <td>{{ $visitHistory->menu_name }}</td>
                            <td>
                                <div class="customer-image">
                                    <img src="" >
                                </div>
                            </td>
                            <td class="comment" >{!! nl2br(e(mb_strimwidth($visitHistory->memo, 0, 50, "..."))) !!}</td>
                            <td>編集</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection



<script>
    function deleteConfirm() {
        return (window.confirm('この顧客を削除します。宜しいでしょうか？'));
    }
</script>










