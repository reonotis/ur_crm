@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">インストラクター養成講座詳細</div>
                <div class="card-body">
                    <form action="{{route('courseSchedule.intrUpdateOpenDay', ['id' => $intr_course->id ])}}" method="POST" class="form-inline my-2 my-lg-0">
                        @csrf
                        <table class="customerSearchTable">
                            <tr>
                                <th>実施コース</th>
                                <td>{{$intr_schedule->course_title}}</td>
                            </tr>
                            <tr>
                                <th>料金</th>
                                <td>{{ number_format($intr_course->price) }}円</td>
                            </tr>
                            <tr>
                                <th>実施日時</th>
                                <td>
                                    <div class="inputDateTime">1回目　{{$intr_schedule->date1->format('Y/m/d H:i')}}~</div>
                                    <div class="inputDateTime">2回目　{{$intr_schedule->date2->format('Y/m/d H:i')}}~</div>
                                    <div class="inputDateTime">3回目　{{$intr_schedule->date3->format('Y/m/d H:i')}}~</div>
                                    <div class="inputDateTime">4回目　{{$intr_schedule->date4->format('Y/m/d H:i')}}~</div>
                                    <div class="inputDateTime">5回目　{{$intr_schedule->date5->format('Y/m/d H:i')}}~</div>
                                    @if($intr_schedule->date6)
                                        <div class="inputDateTime">6回目　{{$intr_schedule->date6->format('Y/m/d H:i')}}~</div>
                                    @endif
                                    @if($intr_schedule->date7)
                                        <div class="inputDateTime">7回目　{{$intr_schedule->date6->format('Y/m/d H:i')}}~</div>
                                    @endif
                                    @if($intr_schedule->date8)
                                        <div class="inputDateTime">8回目　{{$intr_schedule->date6->format('Y/m/d H:i')}}~</div>
                                    @endif
                                    @if($intr_schedule->date9)
                                        <div class="inputDateTime">9回目　{{$intr_schedule->date6->format('Y/m/d H:i')}}~</div>
                                    @endif
                                    @if($intr_schedule->date10)
                                        <div class="inputDateTime">10回目　{{$intr_schedule->date6->format('Y/m/d H:i')}}~</div>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>エリア</th>
                                <td>{{$intr_course->erea}}</td>
                            </tr>
                            <tr>
                                <th>会場</th>
                                <td>{{$intr_course->venue}}</td>
                            </tr>
                            <tr>
                                <th>特記事項</th>
                                <td>{{$intr_course->notices}}</td>
                            </tr>
                            <tr>
                                <th>詳細</th>
                                <td>{!! nl2br(e($intr_course -> comment)) !!}</td>
                            </tr>
                            <tr>
                                <th>公開期間</th>
                                <td>
                                    <div class="inputOpenDateTime">
                                        公開開始日<input type="datetime-local" name="open_start_day" class="formInput inputDatatimeLocal" value="{{ $intr_course->open_start_day->format('Y-m-d').'T'.$intr_course->open_start_day->format('H:i') }}" min="<?php echo date('Y-m-d',strtotime("-1 day"));?>T00:00" >から
                                    </div>
                                    <div class="inputOpenDateTime">
                                        公開終了日<input type="datetime-local" name="open_finish_day" class="formInput inputDatatimeLocal" value="{{ $intr_course->open_finish_day->format('Y-m-d').'T'.$intr_course->open_finish_day->format('H:i') }}" >まで
                                        
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <button class="btn btn-outline-success" onClick="return confilmUpdate()">申請内容を更新する</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    // dd($intr_schedule);
?>

<script>
function confilmUpdate(){
    var result = window.confirm('この内容で更新しますか？');
    if( result ) {
        return true;
    } else {
        return false;
    }
}
</script>
<style>
    th{
        width:120px;
    }
</style>

@endsection


