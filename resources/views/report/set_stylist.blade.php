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
                            <input id="item-<?= $user->id ?>" class="selectUsersInput" type="radio" name="staff_id" value="<?= $user->id ?>" >
                            <label class="selectUsersLabel" for="item-<?= $user->id ?>"><?= $user->name ?></label>
                        @endforeach
                        <div class="itemsRow" >
                            <input type="submit" name="setStylist" value="スタイリストのみ決定する" class="button" >
                            <input type="submit" name="stylistAndVisitHistory" value="スタイリストを決めて本日の来店履歴を登録する" class="button" >
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

