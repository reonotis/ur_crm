@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">インストラクター養成講座 申請画面</div>
                <div class="card-body">
                    <form method="POST" action="{{route('courseSchedule.intrConfilm')}}" class="form-inline my-2 my-lg-0" onSubmit="return check()">
                        @csrf
                        <table class="customerSearchTable">
                            <tr>
                                <th>実施コース</th>
                                <td>インストラクター養成コース</td>
                            </tr>
                            <tr>
                                <th>コース名</th>
                                <td><input type="text" name="course_title" value="" class="formInput" placeholder="火曜日日中コース" ></td>
                            </tr>
                            <tr>
                                <th>料金</th>
                                <td>
                                    <div class="inputUnits">
                                        <input class="formInput inputUnit" type="number" name="price" value="" placeholder="360000" step="100" >円
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>実施日時</th>
                                <td>
                                    <div class="inputDateTime">1回目<input type="datetime-local" class="formInput inputDatatimeLocal" name="date1" value="" required ></div>
                                    <div class="inputDateTime">2回目<input type="datetime-local" class="formInput inputDatatimeLocal" name="date2" value="" required ></div>
                                    <div class="inputDateTime">3回目<input type="datetime-local" class="formInput inputDatatimeLocal" name="date3" value="" required ></div>
                                    <div class="inputDateTime">4回目<input type="datetime-local" class="formInput inputDatatimeLocal" name="date4" value="" required ></div>
                                    <div class="inputDateTime">5回目<input type="datetime-local" class="formInput inputDatatimeLocal" name="date5" value="" required ></div>
                                    <div class="inputDateTime">6回目<input type="datetime-local" class="formInput inputDatatimeLocal" name="date6" value="" ></div>
                                    <div class="inputDateTime">7回目<input type="datetime-local" class="formInput inputDatatimeLocal" name="date7" value="" ></div>
                                    <div class="inputDateTime">8回目<input type="datetime-local" class="formInput inputDatatimeLocal" name="date8" value="" ></div>
                                    <div class="inputDateTime">9回目<input type="datetime-local" class="formInput inputDatatimeLocal" name="date9" value="" ></div>
                                    <div class="inputDateTime">10回目<input type="datetime-local" class="formInput inputDatatimeLocal" name="date10" value="" ></div>
                                </td>
                            </tr>
                            <tr>
                                <th>エリア</th>
                                <td>
                                    <input class="formInput" type="text" name="erea" >
                                </td>
                            </tr>
                            <tr>
                                <th>会場</th>
                                <td>
                                    <input class="formInput" type="text" name="venue" >
                                </td>
                            </tr>
                            <tr>
                                <th>特記事項</th>
                                <td><input class="formInput" type="text" name="notices" placeholder="特記事項" ></td>
                            </tr>
                            <tr>
                                <th>詳細</th>
                                <td>
                                    <textarea name="comment" placeholder="詳細" class="formInput" ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <th>公開日</th>
                                <td>
                                    <div class="inputOpenDateTime">
                                        公開開始日<input type="datetime-local" name="open_start_day" class="formInput inputDatatimeLocal" >から
                                    </div>
                                    <div class="inputOpenDateTime">
                                        公開終了日<input type="datetime-local" name="open_finish_day" class="formInput inputDatatimeLocal" >まで
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">確認する</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>




</script>
<style>
    th{
        width:120px;
    }
</style>

@endsection


