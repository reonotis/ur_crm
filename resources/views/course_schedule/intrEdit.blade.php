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
                                <td>調整中です</td>
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
// dd($CST);
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


