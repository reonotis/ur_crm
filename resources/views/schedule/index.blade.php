@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h3>スケジュール</h3>

        <div class="coursesErea" >
            <input type="month" name="example2" value="<?= $DATE->format('Y-m') ?>"><br><br>
            <h4><?= $DATE->format('Y年 m月') ?> のスケジュール</h4>

            <br>
            <br>
            <br>
            <br>
            <br>
        </div>
    </div>
</div>
@endsection


