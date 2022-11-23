@extends('layouts.report')
@section('pageTitle', 'スタイリスト設定画面')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="setting-stylist-area" >
                <p class="" >{{ $customer->f_name . " " . $customer->l_name }}&nbsp;様のスタイリストを選択してください</p>

                <form action="{{route('report.settingStylist', ['customer'=> $customer->id ])}}" method="post" >
                    @csrf
                    <div class="set-stylist-row" >
                        <div class="set-stylist-title" >スタイリスト</div>
                        <div class="set-stylist-contents" >
                            @foreach($users as $user)
                                <input id="item_{{ $user->id }}" class="select-user-input" type="radio" name="staff_id" value="{{ $user->id }}" >
                                <label class="select-user-label" for="item_{{ $user->id }}">{{ $user->name }}</label>
                            @endforeach
                        </div>
                    </div>
                    <div class="set-stylist-row" >
                        <div class="set-stylist-title" >来店履歴を登録</div>
                        <div class="set-stylist-contents" >
                            <p class="support-comment" >お客様の本日の来店履歴を登録する場合は下記にチェックを入れてください。</p>
                            <label>
                                <input type="checkbox" name="vis_history" >登録する
                            </label>
                        </div>
                    </div>
                    <div class="flex" >
                        <input type="submit" name="setStylist" value="決定する" class="register-btn" >
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

