@extends('layouts.customer')
@section('pageTitle', '顧客編集')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="customer-edit-contents" >
                <div class="card-body">
                    <form action="{{route('customer.update', ['customer' => $customer->id ])}}" method="post">
                        @method('put')
                        @csrf
                        <div class="customer-edit-row" >
                            <div class="customer-edit-title" ><label for="customer_no">会員番号</label></div>
                            <div class="customer-edit-content" >
                                <input type="text" name="customer_no" id="customer_no" class="form-control" value="{{ (old('customer_no'))? old('customer_no'): $customer->customer_no }}" placeholder="CA123456" >
                            </div>
                        </div>
                        <div class="customer-edit-row" >
                            <div class="customer-edit-title" ><label for="customer_no">名前</label></div>
                            <div class="customer-edit-content" >
                                <div class="flex" >
                                    <div class="w-48" style="margin-right: 0.5rem;">
                                        <input type="text" name="f_name" id="f_name" class="form-control" value="{{ (old('f_name'))? old('f_name'): $customer->f_name }}" placeholder="田中" >
                                    </div>
                                    <div class="w-48" >
                                        <input type="text" name="l_name" id="l_name" class="form-control" value="{{ (old('l_name'))? old('l_name'): $customer->l_name }}" placeholder="太郎" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="customer-edit-row" >
                            <div class="customer-edit-title" ><label for="customer_no">ヨミ</label></div>
                            <div class="customer-edit-content" >
                                <div class="flex" >
                                    <div class="w-48" style="margin-right: 0.5rem;">
                                        <input type="text" name="f_read" id="f_read" class="form-control" value="{{ (old('f_read'))? old('f_read'): $customer->f_read }}" placeholder="タナカ" >
                                    </div>
                                    <div class="w-48" >
                                        <input type="text" name="l_read" id="l_read" class="form-control" value="{{ (old('l_read'))? old('l_read'): $customer->l_read }}" placeholder="タロウ" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="customer-edit-row" >
                            <div class="customer-edit-title" ><label for="customer_no">店舗</label></div>
                            <div class="customer-edit-content" >
                                <select name="shop_id" class="form-control" onchange="change_shops()" >
                                    {{ $shopId = old('shop_id')? old('shop_id'): $customer->shop_id }}
                                    @foreach($shops as $shop)
                                        <option class="shop_id_{{ $shop->id }}" value="{{ $shop->id }}" {{ ($shop->id == $shopId)? " selected": "" }} >{{ $shop->shop_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="customer-edit-row" >
                            <div class="customer-edit-title" >担当</div>
                            <div class="customer-edit-content" >
                                <select name="staff_id" id="staff_id" class="form-control" >
                                    {{ $staffId = old('staff_id')? old('staff_id'): $customer->staff_id }}
                                    @foreach($users as $user)
                                        <option class="shop_id_{{ $user->shop_id }}" value="{{ $user->id }}" {{ ($user->id == $staffId)? " selected": "" }} >{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="customer-edit-row" >
                            <div class="customer-edit-title" >生年月日</div>
                            <div class="customer-edit-content" >
                                <div class="w-32" style="margin-right: 0.5rem;">
                                    <input type="num" class="form-control"  name="birthday_year" value="{{ old('birthday_year')? old('birthday_year'): $customer->birthday_year }}" placeholder="年" >
                                </div>
                                /
                                <div class="w-20" style="margin: 0 0.5rem;">
                                    <input type="num" class="form-control" name="birthday_month" value="{{ old('birthday_month')? old('birthday_month'): $customer->birthday_month }}" placeholder="月" >
                                </div>
                                /
                                <div class="w-20" style="margin-left: 0.5rem;">
                                    <input type="num" class="form-control" name="birthday_day" value="{{ old('birthday_day')? old('birthday_day'): $customer->birthday_day }}" placeholder="日" >
                                </div>
                            </div>
                        </div>
                        <div class="customer-edit-row" >
                            <div class="customer-edit-title" >電話番号</div>
                            <div class="customer-edit-content" >
                                <input type="text" name="tel" id="tel" class="form-control" value="{{ old('tel')? old('tel'): $customer->tel }}" placeholder="090-1234-5678" >
                            </div>
                        </div>
                        <div class="customer-edit-row" >
                            <div class="customer-edit-title" ><label for="email">メールアドレス</label></div>
                            <div class="customer-edit-content" >
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email')? old('email'): $customer->email }}" placeholder="sample@exsample.com" >
                            </div>
                        </div>
                        <div class="customer-edit-row" >
                            <div class="customer-edit-title" >住所</div>
                            <div class="customer-edit-content" >
                                <div class="w-full">
                                    <div class="flex w-full items-center" >
                                        <div class="w-20" style="padding-right: 0.5rem;">
                                            <input class="form-control" type="text" name="zip21" value="{{ old('zip21')? old('zip21'): $customer->zip21 }}" placeholder="150" >
                                        </div>
                                        -
                                        <div class="w-24" style="padding-left: 0.5rem;" >
                                            <input class="form-control" type="text" name="zip22" value="{{ old('zip22')? old('zip22'): $customer->zip22 }}" placeholder="0022" >
                                        </div>
                                    </div>
                                    <div class="flex w-full mt-1" >
                                        <div class="w-40" style="padding-right: 0.5rem;" >
                                            <input class="form-control" type="text" name="pref21" value="{{ old('pref21')? old('pref21'): $customer->pref21 }}" placeholder="東京都" >
                                        </div>
                                        <div class="w-48" >
                                            <input class="form-control" type="text" name="address21" value="{{ old('address21')? old('address21'): $customer->address21 }}" placeholder="渋谷区" >
                                        </div>
                                    </div>
                                    <div class="flex w-full mt-1" >
                                        <input class="form-control" type="text" name="street21" value="{{ old('street21')? old('street21'): $customer->street21 }}" placeholder="恵比寿南1丁目マンション名1101号室" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="customer-edit-row" >
                            <div class="customer-edit-title" ><label for="memo">メモ</label></div>
                            <div class="customer-edit-content" >
                                <textarea name="memo" id="memo" class="form-control" >{{ old('memo')? old('memo'): $customer->memo }}</textarea>
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
    window.addEventListener('load', function(){
        reset_users()
    });
</script>
