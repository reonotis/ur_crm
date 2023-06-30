@extends('layouts.customer')
@section('pageTitle', '顧客登録')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="customer-register-contents" >
                <div class="card-body">
                    <form method="post" action="{{route('customer.store')}}">
                        @csrf
                        <div class="customer-register-row" >
                            <div class="customer-register-title" ><label for="customer_no">会員番号</label></div>
                            <div class="customer-register-content" >
                                <input type="text" name="customer_no" id="customer_no" class="form-control" value="{{ old('customer_no') }}" placeholder="CA123456" >
                            </div>
                        </div>
                        <div class="customer-register-row" >
                            <div class="customer-register-title" ><label for="customer_no">名前</label></div>
                            <div class="customer-register-content" >
                                <div class="flex" >
                                    <div class="w-48" style="margin-right: 0.5rem;">
                                        <input type="text" name="f_name" id="f_name" class="form-control" value="{{ old('f_name') }}" placeholder="田中" >
                                    </div>
                                    <div class="w-48" >
                                        <input type="text" name="l_name" id="l_name" class="form-control" value="{{ old('l_name') }}" placeholder="太郎" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="customer-register-row" >
                            <div class="customer-register-title" ><label for="customer_no">ヨミ</label></div>
                            <div class="customer-register-content" >
                                <div class="flex" >
                                    <div class="w-48" style="margin-right: 0.5rem;">
                                        <input type="text" name="f_read" id="f_read" class="form-control" value="{{ old('f_read') }}" placeholder="タナカ" >
                                    </div>
                                    <div class="w-48" >
                                        <input type="text" name="l_read" id="l_read" class="form-control" value="{{ old('l_read') }}" placeholder="タロウ" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="customer-register-row" >
                            <div class="customer-register-title" >性別</div>
                            <div class="customer-register-content" >
                                @foreach(Common::SEX_LIST as $sexKey => $sexName)
                                    <label class="sex-label" >
                                        <input type="radio" name="sex" value="{{ $sexKey }}" >{{ $sexName }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="customer-register-row" >
                            <div class="customer-register-title" >担当</div>
                            <div class="customer-register-content" >
                                @php
                                    $staff_id = old('staff_id')? old('staff_id'): Auth::user()->id;
                                @endphp
                                <select name="staff_id" id="staff_id" class="form-control" >
                                    @foreach($users as $user)
                                        <option class="shop_id_{{ $user->shop_id }}" value="{{ $user->id }}" {{ ($user->id == $staff_id)? " selected": "" }} >{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="customer-register-row" >
                            <div class="customer-register-title" >生年月日</div>
                            <div class="customer-register-content" >
                                <div class="w-32" style="margin-right: 0.5rem;">
                                    <input type="num" class="form-control"  name="birthday_year" value="{{ old('birthday_year') }}" placeholder="年" >
                                </div>
                                /
                                <div class="w-20" style="margin: 0 0.5rem;">
                                    <input type="num" class="form-control" name="birthday_month" value="{{ old('birthday_month') }}" placeholder="月" >
                                </div>
                                /
                                <div class="w-20" style="margin-left: 0.5rem;">
                                    <input type="num" class="form-control" name="birthday_day" value="{{ old('birthday_day') }}" placeholder="日" >
                                </div>
                            </div>
                        </div>
                        <div class="customer-register-row" >
                            <div class="customer-register-title" >電話番号</div>
                            <div class="customer-register-content" >
                                <input type="text" name="tel" id="tel" class="form-control" value="{{ old('tel') }}" placeholder="090-1234-5678" >
                            </div>
                        </div>
                        <div class="customer-register-row" >
                            <div class="customer-register-title" ><label for="email">メールアドレス</label></div>
                            <div class="customer-register-content" >
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="sample@exsample.com" >
                            </div>
                        </div>
                        <div class="customer-register-row" >
                            <div class="customer-register-title" >住所</div>
                            <div class="customer-register-content" >
                                <div class="w-full">
                                    <div class="flex w-full items-center" >
                                        <div class="w-20" style="padding-right: 0.5rem;">
                                            <input class="form-control" type="text" name="zip21" id="zip21" value="{{ old('zip21') }}" placeholder="150" onKeyUp="AjaxZip3.zip2addr('zip21','zip22','pref21','address21','street21');" >
                                        </div>
                                        -
                                        <div class="w-24" style="padding-left: 0.5rem;" >
                                            <input class="form-control" type="text" name="zip22" id="zip22" value="{{ old('zip22') }}" placeholder="0022" onKeyUp="AjaxZip3.zip2addr('zip21','zip22','pref21','address21','street21');" >
                                        </div>
                                    </div>
                                    <div class="flex w-full mt-1" >
                                        <div class="w-40" style="padding-right: 0.5rem;" >
                                            <input class="form-control" type="text" name="pref21" value="{{ old('pref21') }}" placeholder="東京都" >
                                        </div>
                                        <div class="w-48" >
                                            <input class="form-control" type="text" name="address21" value="{{ old('address21') }}" placeholder="渋谷区" >
                                        </div>
                                    </div>
                                    <div class="flex w-full mt-1" >
                                        <input class="form-control" type="text" name="street21" value="{{ old('street21') }}" placeholder="恵比寿南1丁目マンション名1101号室" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="customer-register-row" >
                            <div class="customer-register-title" ><label for="memo">メモ</label></div>
                            <div class="customer-register-content" >
                                <textarea name="memo" id="memo" class="form-control" >{{ old('memo') }}</textarea>
                            </div>
                        </div>
                        <div class="flex mt-4" >
                            <input type="submit" name="" value="登録する" class="register-btn" >
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

<script>
    // window.addEventListener('load', function(){
    //     reset_users(1)
    // });
</script>
