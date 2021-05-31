@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h3></h3>
        <form action="" method="get" >
        <input type="month" name="month" value="送信" >
        <input type="submit" name="" value="送信" >
        </form>
    </div>
</div>
<?php
dd($claim);
?>
@endsection


