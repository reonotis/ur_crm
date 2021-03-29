@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">インストラクター養成講座 申請確認画面</div>
                <div class="card-body">
                    <table class="customerSearchTable">
                        <tr>
                            <th>実施コース</th>
                            <td>インストラクター養成コース</td>
                        </tr>
                        <tr>
                            <th>コース名</th>
                            <td>{{$CSLT->course_title}}</td>
                        </tr>
                        <tr>
                            <th>料金</th>
                            <td>{{ number_format($CST->price) }}円</td>
                        </tr>
                        <tr>
                            <th>実施日時</th>
                            <td>
                                <div class="inputDateTime">1回目　{{$CSLT->date1->format('Y/m/d H:i')}}~</div>
                                <div class="inputDateTime">2回目　{{$CSLT->date2->format('Y/m/d H:i')}}~</div>
                                <div class="inputDateTime">3回目　{{$CSLT->date3->format('Y/m/d H:i')}}~</div>
                                <div class="inputDateTime">4回目　{{$CSLT->date4->format('Y/m/d H:i')}}~</div>
                                <div class="inputDateTime">5回目　{{$CSLT->date5->format('Y/m/d H:i')}}~</div>
                                @if($CSLT->date6)
                                    <div class="inputDateTime">6回目　{{$CSLT->date6->format('Y/m/d H:i')}}~</div>
                                @endif
                                @if($CSLT->date7)
                                    <div class="inputDateTime">7回目　{{$CSLT->date7->format('Y/m/d H:i')}}~</div>
                                @endif
                                @if($CSLT->date8)
                                    <div class="inputDateTime">8回目　{{$CSLT->date8->format('Y/m/d H:i')}}~</div>
                                @endif
                                @if($CSLT->date9)
                                    <div class="inputDateTime">9回目　{{$CSLT->date9->format('Y/m/d H:i')}}~</div>
                                @endif
                                @if($CSLT->date10)
                                    <div class="inputDateTime">10回目　{{$CSLT->date10->format('Y/m/d H:i')}}~</div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>エリア</th>
                            <td>{{$CST->erea}}</td>
                        </tr>
                        <tr>
                            <th>会場</th>
                            <td>{{$CST->venue}}</td>
                        </tr>
                        <tr>
                            <th>特記事項</th>
                            <td>{{$CST->notices}}</td>
                        </tr>
                        <tr>
                            <th>詳細</th>
                            <td>{!! nl2br(e($CST -> comment)) !!}</td>
                        </tr>
                        <tr>
                            <th>公開期間</th>
                            <td>{{$CST->open_start_day->format('Y/m/d H:i') }}　～　{{$CST->open_finish_day->format('Y/m/d H:i') }}</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                    <span class="support" id="support" >上記の内容で事務局に申請しますか<br>承認後の変更は出来ません。</span>
                                <form action="{{route('courseSchedule.intrStore', ['id' => $CSLT->id ] )}}" method="" >
                                    <button class="btn btn-outline-secondary" type="button" onClick="history.back()">戻る</button>
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" >申請する</button>
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
// dd($CST);
?>

<script>




</script>
<style>
    th{
        width:120px;
    }
</style>

@endsection


