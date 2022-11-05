@extends('layouts.app')
@section('pageTitle', 'スタイリスト登録')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="userRegisterContents" >
                <form action="{{ route('user.store') }}" method="post" >
                    @csrf
                    <div class="userRegisterRow" >
                        <div class="userRegisterTitle" ><label for="name">名前</label></div>
                        <div class="userRegisterContent" >
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" >
                        </div>
                    </div>
                    <div class="userRegisterRow" >
                        <div class="userRegisterTitle" ><label for="email">メールアドレス</label></div>
                        <div class="userRegisterContent" >
                            <input type="text" name="email" id="email" class="form-control" value="{{ old('email') }}"  >
                        </div>
                    </div>
                    <div class="userRegisterRow" >
                        <div class="userRegisterTitle" >在籍状況</div>
                        <div class="userRegisterContent" >
                            <select name="authority" class="form-control" >
                                @foreach(Common::AUTHORITY_LIST AS $authorityId => $authorityName)
                                    <option value="{{ $authorityId }}">{{ $authorityName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="userRegisterRow" >
                        <div class="userRegisterTitle" >各種権限</div>
                        <div class="userRegisterContent" >
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >スタイリスト閲覧</div>
                                <div class="userAuthContent" >
                                    <div class="flex" >
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="user_read" value="0" class="" <?php if(old('user_read') <> 1) echo 'checked="checked"' ?> >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="user_read" value="1" class="" <?php if(old('user_read') == 1) echo 'checked="checked"' ?> >
                                                権限あり
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >スタイリスト作成</div>
                                <div class="userAuthContent" >
                                    <div class="flex" >
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="user_create" value="0" class="" <?php if(old('user_create') <> 1) echo 'checked="checked"' ?> >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="user_create" value="1" class="" <?php if(old('user_create') == 1) echo 'checked="checked"' ?> >
                                                権限あり
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >スタイリスト編集</div>
                                <div class="userAuthContent" >
                                    <div class="flex" >
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="user_edit" value="0" class="" <?php if(old('user_edit') <> 1) echo 'checked="checked"' ?> >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="user_edit" value="1" class="" <?php if(old('user_edit') == 1) echo 'checked="checked"' ?> >
                                                権限あり
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >スタイリスト削除</div>
                                <div class="userAuthContent" >
                                    <div class="flex" >
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="user_delete" value="0" class="" <?php if(old('user_delete') <> 1) echo 'checked="checked"' ?> >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="user_delete" value="1" class="" <?php if(old('user_delete') == 1) echo 'checked="checked"' ?> >
                                                権限あり
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >顧客閲覧</div>
                                <div class="userAuthContent" >
                                    <div class="flex" >
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="customer_read" value="0" class="" <?php if(old('customer_read') <> 1) echo 'checked="checked"' ?> >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="customer_read" value="1" class="" <?php if(old('customer_read') == 1) echo 'checked="checked"' ?> >
                                                権限あり
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >顧客閲覧時マスク処理</div>
                                <div class="userAuthContent" >
                                    <div class="flex" >
                                        <div class="" ><label><input type="radio" name="customer_read_none_mask" value="0" class="" <?php if(old('customer_read_none_mask') <> 1) echo 'checked="checked"' ?> >する</label></div>
                                        <div class="" ><label><input type="radio" name="customer_read_none_mask" value="1" class="" >しない</label></div>
                                    </div>
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >顧客作成</div>
                                <div class="userAuthContent" >
                                    <div class="flex" >
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="customer_create" value="0" class="" <?php if(old('customer_create') <> 1) echo 'checked="checked"' ?> >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="customer_create" value="1" class="" <?php if(old('customer_create') == 1) echo 'checked="checked"' ?> >
                                                権限あり
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >顧客編集</div>
                                <div class="userAuthContent" >
                                    <div class="flex" >
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="customer_edit" value="0" class="" <?php if(old('customer_edit') <> 1) echo 'checked="checked"' ?> >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="customer_edit" value="1" class="" <?php if(old('customer_edit') == 1) echo 'checked="checked"' ?> >
                                                権限あり
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >顧客削除</div>
                                <div class="userAuthContent" >
                                    <div class="flex" >
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="customer_delete" value="0" class="" <?php if(old('customer_delete') <> 1) echo 'checked="checked"' ?> >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="customer_delete" value="1" class="" <?php if(old('customer_delete') == 1) echo 'checked="checked"' ?> >
                                                権限あり
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >予約閲覧</div>
                                <div class="userAuthContent" >
                                    <div class="flex" >
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="reserve_read" value="0" class="" <?php if(old('reserve_read') <> 1) echo 'checked="checked"' ?> >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="reserve_read" value="1" class="" <?php if(old('reserve_read') == 1) echo 'checked="checked"' ?> >
                                                権限あり
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >予約作成</div>
                                <div class="userAuthContent" >
                                    <div class="flex" >
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="reserve_create" value="0" class="" <?php if(old('reserve_create') <> 1) echo 'checked="checked"' ?> >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="reserve_create" value="1" class="" <?php if(old('reserve_create') == 1) echo 'checked="checked"' ?> >
                                                権限あり
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >予約編集</div>
                                <div class="userAuthContent" >
                                    <div class="flex" >
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="reserve_edit" value="0" class="" <?php if(old('reserve_edit') <> 1) echo 'checked="checked"' ?> >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="reserve_edit" value="1" class="" <?php if(old('reserve_edit') == 1) echo 'checked="checked"' ?> >
                                                権限あり
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >予約削除</div>
                                <div class="userAuthContent" >
                                    <div class="flex" >
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="reserve_delete" value="0" class="" <?php if(old('reserve_delete') <> 1) echo 'checked="checked"' ?> >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="reserve_delete" value="1" class="" <?php if(old('reserve_delete') == 1) echo 'checked="checked"' ?> >
                                                権限あり
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex mt-12" >
                        <a href="javascript:history.back()" class="submit back-btn" >戻る</a>
                        <input type="submit" name="" value="登録する" class="register-btn" >
                    </div>
                </form>
            </div>

        </div>
    </div>




@endsection

