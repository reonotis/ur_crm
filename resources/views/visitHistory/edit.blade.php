@extends('layouts.visitHistory')
@section('pageTitle', '来店履歴編集')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="customer-edit-contents" >
                <div class="card-body">
                    <form action="{{route('visitHistory.update', ['visitHistory' => $visitHistory->id ])}}" method="post">
                        @method('post')
                        @csrf
                        <div class="customer-edit-row" >
                            <div class="customer-edit-title" >顧客名</div>
                            <div class="customer-edit-content" >{{ $visitHistory->customer->f_name . " " . $visitHistory->customer->l_name }}&nbsp;様</div>
                        </div>
                        <div class="customer-edit-row" >
                            <div class="customer-edit-title" >日付</div>
                            <div class="customer-edit-content" >{{ $visitHistory->vis_date->format('Y 年 m 月 d 日') }}</div>
                        </div>
                        <div class="customer-edit-row" >
                            <div class="customer-edit-title" ><label for="vis_time">時間</label></div>
                            <div class="customer-edit-content" >
                                <input type="time" name="vis_time" class="form-control" id="vis_time" value="{{ substr($visitHistory->vis_time, 0, 5) }}" >
                            </div>
                        </div>
                        <div class="customer-edit-row" >
                            <div class="customer-edit-title" ><label for="">メニュー</label></div>
                            <div class="customer-edit-content" >
                                <select name="menu_id" id="menu_id" class="form-control" >
                                    @foreach ($menus AS $menu)
                                        <option value="{{ $menu->id }}" {{ ($visitHistory->menu_id == $menu->id)? "selected": "" }}   >{{ $menu->menu_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="customer-edit-row" >
                            <div class="customer-edit-title" ><label for="">スタイリスト</label></div>
                            <div class="customer-edit-content" >
                                <select name="user_id" id="user_id" class="form-control" >
                                    @foreach ($users AS $user)
                                        <option value="{{ $user->id }}" {{ ($visitHistory->user_id == $user->id)? "selected": "" }}   >{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="customer-edit-row" >
                            <div class="customer-edit-title" ><label for="">スタイリスト</label></div>
                            <div class="customer-edit-content" >
                                <textarea name="memo" class="form-control" >{{ $visitHistory->memo }}</textarea>
                            </div>
                        </div>
                        <div class="flex mt-4" >
                            <a href="javascript:history.back()" class="submit back-btn" >戻る</a>
                            <input type="submit" name="" value="更新する" class="edit-btn" >
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    // window.addEventListener('load', function(){
    //     reset_users()
    // });
</script>
