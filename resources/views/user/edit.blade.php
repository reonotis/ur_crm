@extends('layouts.app')
@section('pageTitle', 'スタイリスト編集')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="userEditContents" >
                <form action="{{ route('user.update', ['user'=>$user->id]) }}" method="POST" >
                    @method('put')
                    @csrf
                    <div class="userEditRow" >
                        <div class="userEditTitle" ><label for="name">名前</label></div>
                        <div class="userEditContent" >
                            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" >
                        </div>
                    </div>
                    <div class="userEditRow" >
                        <div class="userEditTitle" ><label for="email">メールアドレス</label></div>
                        <div class="userEditContent" >
                            <input type="text" name="email" id="email" class="form-control" value="{{ $user->email }}"  >
                        </div>
                    </div>
                    <div class="userEditRow" >
                        <div class="userEditTitle" ><label for="authority">在籍状況</label></div>
                        <div class="userEditContent" >
                            <select name="authority" id="authority" class="form-control" >
                                @foreach(Common::AUTHORITY_LIST AS $authorityId => $authorityName)
                                    <option value="{{ $authorityId }}"
                                        @if($authorityId == $user->authority_level)
                                            selected
                                        @endif
                                    >
                                        {{ $authorityName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="userEditRow" >
                        <div class="userEditTitle" >各種権限</div>
                        <div class="userEditContent" >
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >スタイリスト閲覧</div>
                                <div class="userAuthContent" >
                                    <div class="flex" >
                                        <label>
                                            <input type="radio" name="user_read" value="0"
                                                @if($user->userShopAuthorization->user_read == 0)
                                                    checked="checked"
                                                @endif
                                            >
                                            権限なし
                                        </label>
                                        <label>
                                            <input type="radio" name="user_read" value="1"
                                               @if($user->userShopAuthorization->user_read == 1)
                                                   checked="checked"
                                                @endif
                                            >
                                            権限あり
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >スタイリスト作成</div>
                                <div class="userAuthContent" >
                                    <div class="flex" >
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="user_create" value="0"
                                                    @if($user->userShopAuthorization->user_create == 0)
                                                        checked="checked"
                                                    @endif
                                                >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="user_create" value="1"
                                                    @if($user->userShopAuthorization->user_create == 1)
                                                        checked="checked"
                                                    @endif
                                                >
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
                                                <input type="radio" name="user_edit" value="0"
                                                    @if($user->userShopAuthorization->user_edit == 0)
                                                        checked="checked"
                                                    @endif
                                                >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="user_edit" value="1"
                                                    @if($user->userShopAuthorization->user_edit == 1)
                                                        checked="checked"
                                                    @endif
                                                >
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
                                                <input type="radio" name="user_delete" value="0"
                                                    @if($user->userShopAuthorization->user_delete == 0)
                                                        checked="checked"
                                                    @endif
                                                >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="user_delete" value="1"
                                                    @if($user->userShopAuthorization->user_delete == 1)
                                                        checked="checked"
                                                    @endif
                                                >
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
                                                <input type="radio" name="customer_read" value="0"
                                                    @if($user->userShopAuthorization->customer_read == 0)
                                                        checked="checked"
                                                    @endif
                                                >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="customer_read" value="1"
                                                    @if($user->userShopAuthorization->customer_read == 1)
                                                        checked="checked"
                                                    @endif
                                                >
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
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="customer_read_none_mask" value="0"
                                                    @if($user->userShopAuthorization->customer_read_none_mask == 0)
                                                        checked="checked"
                                                    @endif
                                                >
                                                する
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="customer_read_none_mask" value="1"
                                                    @if($user->userShopAuthorization->customer_read_none_mask == 1)
                                                        checked="checked"
                                                    @endif
                                                >
                                                しない
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >顧客作成</div>
                                <div class="userAuthContent" >
                                    <div class="flex" >
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="customer_create" value="0"
                                                    @if($user->userShopAuthorization->customer_create == 0)
                                                        checked="checked"
                                                    @endif
                                                >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="customer_create" value="1"
                                                    @if($user->userShopAuthorization->customer_create == 1)
                                                        checked="checked"
                                                    @endif
                                                >
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
                                                <input type="radio" name="customer_edit" value="0"
                                                    @if($user->userShopAuthorization->customer_edit == 0)
                                                        checked="checked"
                                                    @endif
                                                >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="customer_edit" value="1"
                                                    @if($user->userShopAuthorization->customer_edit == 1)
                                                        checked="checked"
                                                    @endif
                                                >
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
                                                <input type="radio" name="customer_delete" value="0"
                                                    @if($user->userShopAuthorization->customer_delete == 0)
                                                        checked="checked"
                                                    @endif
                                                >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="customer_delete" value="1"
                                                    @if($user->userShopAuthorization->customer_delete == 1)
                                                        checked="checked"
                                                    @endif
                                                >
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
                                                <input type="radio" name="reserve_read" value="0"
                                                    @if($user->userShopAuthorization->reserve_read == 0)
                                                        checked="checked"
                                                    @endif
                                                >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="reserve_read" value="1"
                                                    @if($user->userShopAuthorization->reserve_read == 1)
                                                        checked="checked"
                                                    @endif
                                                >
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
                                                <input type="radio" name="reserve_create" value="0"
                                                    @if($user->userShopAuthorization->reserve_create == 0)
                                                        checked="checked"
                                                    @endif
                                                >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="reserve_create" value="1"
                                                    @if($user->userShopAuthorization->reserve_create == 1)
                                                        checked="checked"
                                                    @endif
                                                >
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
                                                <input type="radio" name="reserve_edit" value="0"
                                                    @if($user->userShopAuthorization->reserve_edit == 0)
                                                        checked="checked"
                                                    @endif
                                                >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="reserve_edit" value="1"
                                                    @if($user->userShopAuthorization->reserve_edit == 1)
                                                        checked="checked"
                                                    @endif
                                                >
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
                                                <input type="radio" name="reserve_delete" value="0"
                                                    @if($user->userShopAuthorization->reserve_delete == 0)
                                                        checked="checked"
                                                    @endif
                                                >
                                                権限なし
                                            </label>
                                        </div>
                                        <div class="" >
                                            <label>
                                                <input type="radio" name="reserve_delete" value="1"
                                                    @if($user->userShopAuthorization->reserve_delete == 1)
                                                        checked="checked"
                                                    @endif
                                                >
                                                権限あり
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex mt-4" >
                        <a href="javascript:history.back()" class="submit back-btn" >戻る</a>
                        <input type="submit" name="" value="更新する" class="edit-btn" >
                    </div>
                </form>

                @if (Auth::user()->userShopAuthorization->user_delete)
                    <form action="{{ route('user.destroy', ['user'=>$user->id]) }}" method="POST" >
                        @method('DELETE')
                        @csrf
                        <div class="flex mt-12" >
                            <input type="submit" name="" value="このスタイリストを削除する" class="delete-btn" >
                        </div>
                    </form>
                @endif
            </div>

        </div>
    </div>

@endsection

