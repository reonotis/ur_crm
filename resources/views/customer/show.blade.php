@extends('layouts.app')

@include('layouts.modal')
@section('content')
<?php

$json_array = json_encode($visitHistories);

?>
<div class="customer_show_area" >
    <div class="customer_show_customer_area" >
        <div class="customer_show_customer_img" >
            @if($userImgPass)
                <img src="{{asset('storage/customer_img/'.$userImgPass)}}" >
            @else
                画像はありません。<br><br>
                来店履歴のデータに画像を挿入した場合に自動的に更新されます。
            @endif
        </div>
        <div class="customer_show_customer_member_number" ><?= $customer->member_number ?></div>
        <div class="customer_show_customer_name" >
            <span class="name_read" > ( <?= $customer->f_read . ' ' . $customer->l_read ?> )</span><br>
            <?= $customer->f_name.' '.$customer->l_name ?> 様
        </div >
        <div class="customer_show_customer_sex" >性別 : <?= $customer->sex_name ?></div >
        <div class="customer_show_customer_birthday" ><?= $customer->birthday ?></div >
        <div class="customer_show_customer_shop" ><?= '登録店舗'.' : '.$customer->shop_name ?></div >
        <div class="customer_show_customer_staff" ><?= '担当者'.' : '.$customer->staff_name ?></div >
    </div>
    <div class="customer_show_contents_area" >
        <div class="customer_show_basicData_area" >
            <div class="customer_show_contents_title" >基本情報
                @if(\Auth::user()->authority_id <= 4 )
                    <a href="{{route('customer.edit', ['id' => $customer->id ])}}" class="customer_show_contents_title_btn">編集する</a>
                @endif
            </div>
            <div class="customerShow_basicDataArea_row" >
                <div class="customerShow_basicDataArea_title" >電話番号</div>
                <div class="customerShow_basicDataArea_content" ><?= $customer->tel ?></div>
            </div>
            <div class="customerShow_basicDataArea_row" >
                <div class="customerShow_basicDataArea_title" >住所</div>
                <div class="customerShow_basicDataArea_content" >
                    <?= $customer->zip21."-".$customer->zip22 ?>　　　<?= $customer->pref21."".$customer->addr21."".$customer->strt21 ?>
                </div>
            </div>
            <div class="customerShow_basicDataArea_row" >
                <div class="customerShow_basicDataArea_title" >メモ</div>
                <div class="customerShow_basicDataArea_content" >{!! nl2br(e($customer->memo)) !!}</div>
            </div>
            <div class="customerShow_basicDataArea_row" >
                <div class="customerShow_basicDataArea_title" >アンケート</div>
                <div class="customerShow_basicDataArea_content" >{{ $customer->question1 }}</div>
            </div>
            <div class="customerShow_basicDataArea_row" >
                <div class="customerShow_basicDataArea_title" >来店時のコメント</div>
                <div class="customerShow_basicDataArea_content" >{!! nl2br(e($customer->comment)) !!}</div>
            </div>
        </div>
        <div class="customer_show_visitHistory_area" >
            <div class="customer_show_contents_title" >来店履歴
                @if($register_flg)
                    <a href="{{route('VisitHistory.register', ['id' => $customer->id ])}}" class="customer_show_contents_title_btn" >本日の来店履歴を登録する</a>
                @endif
            </div>
            <table class="tableClass_005">
                <tr>
                    <th class="id">ID</th>
                    <th>来店日時</th>
                    <th>担当スタイリスト</th>
                    <th>予約タイプ</th>
                    <th>メニュー</th>
                    <th>正面画像</th>
                    <th>側面画像</th>
                    <th>背面画像</th>
                    <th>コメント</th>
                    <th>編集</th>
                </tr>
                @foreach($visitHistories as $visitHistory)
                <tr>
                    <td class="id">{{ $visitHistory->id }}</td>
                    <td>{{ $visitHistory->vis_date->format('y年m月d日') ."  ". date('H:i', strtotime($visitHistory->vis_time)) }}</td>
                    <td>{{ $visitHistory->name }}</td>
                    <td>{{ $visitHistory->type_name }}</td>
                    <td>{{ $visitHistory->menu_name }}</td>
                    <td>
                        @if($visitHistory->img_pass1)
                            <div class="customer_img" >
                                <img src="{{asset('storage/customer_img_resize/'.$visitHistory->img_pass1)}}" >
                            </div>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($visitHistory->img_pass2)
                            <div class="customer_img" >
                                <img src="{{asset('storage/customer_img_resize/'.$visitHistory->img_pass2)}}" >
                            </div>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($visitHistory->img_pass3)
                            <div class="customer_img" >
                                <img src="{{asset('storage/customer_img_resize/'.$visitHistory->img_pass3)}}" >
                            </div>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($visitHistory->memo)
                            <div class="modalText" onclick="modalPopUp( visitHistories , <?= $visitHistory->id ?>)" >{{ Str::limit($visitHistory->memo, 20, '...') }}</div>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($visitHistory->vis_date->format('Ymd') == date('Ymd'))
                        <a href="{{ route('VisitHistory.single_edit', ['id' => $visitHistory->id ]) }}" class="" >編集</a>
                        @else
                        -
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>


<script>
    let visitHistories = <?php echo $json_array; ?>

</script>

@endsection
