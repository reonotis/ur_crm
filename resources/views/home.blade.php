@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif


                    <!-- 管理者へのメッセージ -->
                    @if(isset($adminMessage['UnAppCourse']) && count($adminMessage['UnAppCourse']))
                        <a href="<?= url('').'/approval/index'  ?>" class="messegeLink" >申請中のコースが <?= count($adminMessage['UnAppCourse']) ?> 件あります<br></a>
                    @endif
                    @if(count($unPayd) >= 1)
                    <!-- TODO 変数名を変えたほうがいい。  -->
                        <a href="<?= url('').'/admin/newApply'  ?>" class="messegeLink" >コースへの新しいお申し込みが <?= count($newApply) ?> 件あります。<br></a>
                        <a href="<?= url('').'/admin/unPayd'  ?>" class="messegeLink" >未入金のお客様が <?= count($unPayd) ?> 名います<br></a>
                    @endif
                    @if(isset($adminMessage['compCourse']) && count($adminMessage['compCourse']))
                        @foreach($adminMessage['compCourse'] as $data)
                            <a href="<?= url('').'/admin/customer_complet_course'  ?>" class="messegeLink" ><?= $data->name ?>様が養成courseを終了しました<br></a>
                        @endforeach
                    @endif



                    <!-- インストラクターへのメッセージ -->
                    @if(count($intrMessage['NgAppCourse']))
                        <a href="<?= url('').'/courseSchedule/index'  ?>" class="messegeLink" >差し戻されたコースが <?= count($intrMessage['NgAppCourse']) ?> 件あります<br></a>
                    @endif


                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<style>
.messegeLink{
    display: block;

    margin-top :10px;
}
</style>