@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">インストラクター養成講座詳細</div>
                <div class="card-body">
                    <form action="{{route('courseSchedule.intrUpdate', ['id' => $intr_course->id ])}}" method="POST" class="form-inline my-2 my-lg-0">
                        @csrf
                        <table class="customerSearchTable">
                            <tr>
                                <th>実施コース</th>
                                <td><input type="text" class="formInput" name="course_title" value="{{ $intr_schedule->course_title }}"></td>
                            </tr>
                            <tr>
                                <th>料金</th>
                                <td><input type="text" class="formInput" name="price" value="{{ $intr_course->price }}"></td>
                            </tr>
                            <tr>
                                <th>実施日時</th>
                                <td>
                                    <div class="inputDateTime">1回目<input type="datetime-local" class="formInput inputDatatimeLocal" name="date1" value="<?= $intr_schedule->date1->format('Y-m-d').'T'.$intr_schedule->date1->format('H:i:s') ?>" required ></div>
                                    <div class="inputDateTime">2回目<input type="datetime-local" class="formInput inputDatatimeLocal" name="date2" value="<?= $intr_schedule->date2->format('Y-m-d').'T'.$intr_schedule->date2->format('H:i:s') ?>" required ></div>
                                    <div class="inputDateTime">3回目<input type="datetime-local" class="formInput inputDatatimeLocal" name="date3" value="<?= $intr_schedule->date3->format('Y-m-d').'T'.$intr_schedule->date3->format('H:i:s') ?>" required ></div>
                                    <div class="inputDateTime">4回目<input type="datetime-local" class="formInput inputDatatimeLocal" name="date4" value="<?= $intr_schedule->date4->format('Y-m-d').'T'.$intr_schedule->date4->format('H:i:s') ?>" required ></div>
                                    <div class="inputDateTime">5回目<input type="datetime-local" class="formInput inputDatatimeLocal" name="date5" value="<?= $intr_schedule->date5->format('Y-m-d').'T'.$intr_schedule->date5->format('H:i:s') ?>" required ></div>
                                    <div class="inputDateTime">6回目<input type="datetime-local" class="formInput inputDatatimeLocal" name="date6" value="<?php if($intr_schedule->date6) echo $intr_schedule->date6->format('Y-m-d').'T'.$intr_schedule->date6->format('H:i:s') ?>" ></div>
                                    <div class="inputDateTime">7回目<input type="datetime-local" class="formInput inputDatatimeLocal" name="date7" value="<?php if($intr_schedule->date7) echo $intr_schedule->date7->format('Y-m-d').'T'.$intr_schedule->date7->format('H:i:s') ?>" ></div>
                                    <div class="inputDateTime">8回目<input type="datetime-local" class="formInput inputDatatimeLocal" name="date8" value="<?php if($intr_schedule->date8) echo $intr_schedule->date8->format('Y-m-d').'T'.$intr_schedule->date8->format('H:i:s') ?>" ></div>
                                    <div class="inputDateTime">9回目<input type="datetime-local" class="formInput inputDatatimeLocal" name="date9" value="<?php if($intr_schedule->date9) echo $intr_schedule->date9->format('Y-m-d').'T'.$intr_schedule->date9->format('H:i:s') ?>" ></div>
                                    <div class="inputDateTime">10回目<input type="datetime-local" class="formInput inputDatatimeLocal" name="date10" value="<?php if($intr_schedule->date10) echo $intr_schedule->date10->format('Y-m-d').'T'.$intr_schedule->date10->format('H:i:s') ?>" ></div>
                                </td>
                            </tr>
                            <tr>
                                <th>エリア</th>
                                <td><input type="text" class="formInput" name="erea" value="{{ $intr_course->erea }}"></td>
                            </tr>
                            <tr>
                                <th>会場</th>
                                <td><input type="text" class="formInput" name="venue" value="{{ $intr_course->venue }}"></td>
                            </tr>
                            <tr>
                                <th>特記事項</th>
                                <td><input type="text" class="formInput" name="notices" value="{{ $intr_course->notices }}"></td>
                            </tr>
                            <tr>
                                <th>詳細</th>
                                <td><textarea name="comment" placeholder="詳細" class="formInput" >{{ $intr_course -> comment }}</textarea></td>
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


