@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>スタイリスト選択画面</h4>
                    <div><?= $customer->f_name ?>様のスタイリストを選択してください</div>

                    <form action="{{route('report.setting_stylist',['id'=> $customer->id ])}}" method="post" >
                        @csrf
                        @foreach($users as $user)
                            <input type="radio" name="staff_id" value="<?= $user->id ?>"><?= $user->name ?>
                        @endforeach
                        <div class="itemsRow" >
                            <input type="submit" name="" value="決定する" class="button" >
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

