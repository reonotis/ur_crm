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
                            <td>{{$request->course_title}}</td>
                        </tr>
                        <tr>
                            <th>料金</th>
                            <td>{{ number_format($request->price) }}円</td>
                        </tr>
                        <tr>
                            <th>実施日時</th>
                            <td>
                                <div class="inputDateTime">1回目　{{date('Y年m月d日 H:i', strtotime($request -> date1))}}~</div>
                                <div class="inputDateTime">2回目　{{date('Y年m月d日 H:i', strtotime($request -> date2))}}~</div>
                                <div class="inputDateTime">3回目　{{date('Y年m月d日 H:i', strtotime($request -> date3))}}~</div>
                                <div class="inputDateTime">4回目　{{date('Y年m月d日 H:i', strtotime($request -> date4))}}~</div>
                                <div class="inputDateTime">5回目　{{date('Y年m月d日 H:i', strtotime($request -> date5))}}~</div>
                                @if($request->date6)
                                    <div class="inputDateTime">6回目　{{date('Y年m月d日 H:i', strtotime($request -> date6))}}~</div>
                                @endif
                                @if($request->date7)
                                    <div class="inputDateTime">7回目　{{date('Y年m月d日 H:i', strtotime($request -> date7))}}~</div>
                                @endif
                                @if($request->date8)
                                    <div class="inputDateTime">8回目　{{date('Y年m月d日 H:i', strtotime($request -> date8))}}~</div>
                                @endif
                                @if($request->date9)
                                    <div class="inputDateTime">9回目　{{date('Y年m月d日 H:i', strtotime($request -> date9))}}~</div>
                                @endif
                                @if($request->date10)
                                    <div class="inputDateTime">10回目　{{date('Y年m月d日 H:i', strtotime($request -> date10))}}~</div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>エリア</th>
                            <td>{{$request->erea}}</td>
                        </tr>
                        <tr>
                            <th>会場</th>
                            <td>{{$request->venue}}</td>
                        </tr>
                        <tr>
                            <th>特記事項</th>
                            <td>{{$request->notices}}</td>
                        </tr>
                        <tr>
                            <th>詳細</th>
                            <td>{!! nl2br(e($request -> comment)) !!}</td>
                        </tr>
                        <tr>
                            <th>公開期間</th>
                            <td>
                                {{date('Y年m月d日 H:i', strtotime($request -> open_start_day))}}　～　{{date('Y年m月d日 H:i', strtotime($request -> open_finish_day))}}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span class="support" id="support" >上記の内容で事務局に申請しますか<br>承認後の変更は出来ません。</span>
                                <form action="{{route('courseSchedule.requestIntrCourse', ['id' => $request->id ] )}}" method="post" >
                                    @csrf
                                    <button class="btn btn-outline-secondary" type="button" onClick="history.back()">戻る</button>
                                    <input type="hidden" name="course_title" value="{{ $request->course_title }}" >
                                    <input type="hidden" name="price" value="{{ $request->price }}" >
                                    <input type="hidden" name="date1" value="{{ $request->date1 }}" >
                                    <input type="hidden" name="date2" value="{{ $request->date2 }}" >
                                    <input type="hidden" name="date3" value="{{ $request->date3 }}" >
                                    <input type="hidden" name="date4" value="{{ $request->date4 }}" >
                                    <input type="hidden" name="date5" value="{{ $request->date5 }}" >
                                    <input type="hidden" name="date6" value="{{ $request->date6 }}" >
                                    <input type="hidden" name="date7" value="{{ $request->date7 }}" >
                                    <input type="hidden" name="date8" value="{{ $request->date8 }}" >
                                    <input type="hidden" name="date9" value="{{ $request->date9 }}" >
                                    <input type="hidden" name="date10" value="{{ $request->date10 }}" >
                                    <input type="hidden" name="erea" value="{{ $request->erea }}" >
                                    <input type="hidden" name="venue" value="{{ $request->venue }}" >
                                    <input type="hidden" name="notices" value="{{ $request->notices }}" >
                                    <input type="hidden" name="comment" value="{{ $request->comment }}" >
                                    <input type="hidden" name="open_start_day" value="{{ $request->open_start_day }}" >
                                    <input type="hidden" name="open_finish_day" value="{{ $request->open_finish_day }}" >
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
    // dd($request);
?>

<script>




</script>
<style>
    th{
        width:120px;
    }
</style>

@endsection


