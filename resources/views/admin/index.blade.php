@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h3>管理画面</h3>

        <div class="adminErea" >
            <div class="admin_h5_wrap"><h5 class="admin_h5">コース管理</h5></div>
            <div class="adminBtnErea">
                <a href="{{route('approval.index')}}" class="btn btn-admin">未承認コースの確認</a>
                <a href="" class="btn btn-admin">?? 承認済コースの確認 ??</a>
            </div>
            <div class="admin_h5_wrap"><h5 class="admin_h5">顧客管理</h5></div>
            <div class="adminBtnErea">
                <!-- <a href="{{route('customer.search')}}" class="btn btn-admin">顧客検索</a> -->
                <a href="{{route('admin.customer_complet_course')}}" class="btn btn-admin">修了者一覧</a>
            </div>
            <div class="admin_h5_wrap"><h5 class="admin_h5">インストラクター管理</h5></div>
            <div class="adminBtnErea">
                <a href="{{route('user.index')}}" class="btn btn-admin">インストラクター一覧</a>
                <a href="{{route('user.index')}}" class="btn btn-admin">?? 新規イントラ追加 ??</a>
            </div>
            <div class="admin_h5_wrap"><h5 class="admin_h5">入金管理</h5></div>
            <div class="adminBtnErea">
                <a href="{{route('admin.unPayd')}}" class="btn btn-admin">?? 未入金リスト ??</a>
            </div>

        </div>


    </div>
</div>
<?php
// dd($courseSchedules);
?>
@endsection


