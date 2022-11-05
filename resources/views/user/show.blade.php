@extends('layouts.app')
@section('pageTitle', 'スタイリスト詳細')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="userShowContents" >
                <div class="userShowRow" >
                    <div class="userShowTitle" ><label for="name">名前</label></div>
                    <div class="userShowContent" >{{ $user->name }}</div>
                </div>
                <div class="userShowRow" >
                    <div class="userShowTitle" ><label for="email">メールアドレス</label></div>
                    <div class="userShowContent" >{{ $user->email }}</div>
                </div>
                <div class="userShowRow" >
                    <div class="userShowTitle" >在籍状況</div>
                    <div class="userShowContent" >{{ Common::AUTHORITY_LIST[$user->authority_level] }}</div>
                </div>
                <div class="userShowRow" >
                    <div class="userShowTitle" >所属店舗</div>
                    <div class="userShowContent" >
                        <div class="" >
                            @foreach($user->userShopAuthorizations AS $userShop)
                                <div class="flex belongShopRow" >
                                    <span class="belongShopName" >{{ $userShop->shop->shop_name }}</span>
                                    @if(Auth::user()->userShopAuthorization->user_edit)
                                        @if(session()->get(SessionConst::SELECTED_SHOP)->id == $userShop->shop->id)
                                            <div class="deleteBelongShopRow">
                                                <a href="{{ route('user.deleteBelongShop', ['user'=>$user->id]) }}" >{{ $userShop->shop->shop_name }}の所属を外す</a>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="userShowRow" >
                    <div class="userShowTitle" >各種権限</div>
                    <div class="userShowContent" >
                        <div class="" >
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >スタイリスト閲覧</div>
                                <div class="userAuthContent" >
                                    {{ Common::AUTHORIZATION_LIST[$user->userShopAuthorization->user_read] }}
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >スタイリスト作成</div>
                                <div class="userAuthContent" >
                                    {{ Common::AUTHORIZATION_LIST[$user->userShopAuthorization->user_create] }}
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >スタイリスト編集</div>
                                <div class="userAuthContent" >
                                    {{ Common::AUTHORIZATION_LIST[$user->userShopAuthorization->user_edit] }}
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >スタイリスト削除</div>
                                <div class="userAuthContent" >
                                    {{ Common::AUTHORIZATION_LIST[$user->userShopAuthorization->user_delete] }}
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >顧客閲覧</div>
                                <div class="userAuthContent" >
                                    {{ Common::AUTHORIZATION_LIST[$user->userShopAuthorization->customer_read] }}
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >顧客閲覧時マスク処理</div>
                                <div class="userAuthContent" >
                                    {{ Common::MASK_CHECK[$user->userShopAuthorization->customer_read_none_mask] }}
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >顧客作成</div>
                                <div class="userAuthContent" >
                                    {{ Common::AUTHORIZATION_LIST[$user->userShopAuthorization->customer_create] }}
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >顧客編集</div>
                                <div class="userAuthContent" >
                                    {{ Common::AUTHORIZATION_LIST[$user->userShopAuthorization->customer_edit] }}
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >顧客削除</div>
                                <div class="userAuthContent" >
                                    {{ Common::AUTHORIZATION_LIST[$user->userShopAuthorization->customer_delete] }}
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >予約閲覧</div>
                                <div class="userAuthContent" >
                                    {{ Common::AUTHORIZATION_LIST[$user->userShopAuthorization->reserve_read] }}
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >予約作成</div>
                                <div class="userAuthContent" >
                                    {{ Common::AUTHORIZATION_LIST[$user->userShopAuthorization->reserve_create] }}
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >予約編集</div>
                                <div class="userAuthContent" >
                                    {{ Common::AUTHORIZATION_LIST[$user->userShopAuthorization->reserve_edit] }}
                                </div>
                            </div>
                            <div class="userAuthRow" >
                                <div class="userAuthTitle" >予約削除</div>
                                <div class="userAuthContent" >
                                    {{ Common::AUTHORIZATION_LIST[$user->userShopAuthorization->reserve_delete] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex mt-12" >
                    <a href="javascript:history.back()" class="submit back-btn" >戻る</a>
                    @if (Auth::user()->userShopAuthorization->user_edit)
                        <a href="{{ route('user.edit', ['user'=>$user->id]) }}" class="submit edit-btn" >編集する</a>
                    @endif
                </div>
            </div>

        </div>
    </div>




@endsection

