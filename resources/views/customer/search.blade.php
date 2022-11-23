@extends('layouts.customer')
@section('pageTitle', '顧客検索')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="customerSearchContents" >
                <form action="" method="get" >
                    <div class="searchRow" >
                        <div class="searchRowTitle" ><label for="customer_no">会員番号</label></div>
                        <div class="searchRowContents" >
                            <div class="w-48">
                                <input type="text" name="customer_no" id="customer_no" class="form-control" placeholder="CA20046" value="{{ request('customer_no') }}" >
                            </div>
                        </div>
                    </div>
                    <div class="searchRow" >
                        <div class="searchRowTitle" ><label for="f_name">名前</label></div>
                        <div class="searchRowContents" >
                            <div class="flex" >
                                <div class="w-48" style="margin-right: 0.5rem;">
                                    <input class="form-control" type="text" name="f_name" id="f_name" placeholder="田中" value="{{ request('f_name') }}" >
                                </div>
                                <div class="w-48" >
                                    <input class="form-control" type="text" name="l_name" id="l_name" placeholder="太郎" value="{{ request('l_name') }}" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="searchRow" >
                        <div class="searchRowTitle" ><label for="f_read">ヨミ</label></div>
                        <div class="searchRowContents" >
                            <div class="w-48" style="margin-right: 0.5rem;">
                                <input class="form-control" type="text" name="f_read" id="f_read" placeholder="タナカ" value="{{ request('f_read') }}" >
                            </div>
                            <div class="w-48" >
                                <input class="form-control" type="text" name="l_read" id="l_read" placeholder="タロウ" value="{{ request('l_read') }}" >
                            </div>
                        </div>
                    </div>
                    <div class="searchRow" >
                        <div class="searchRowTitle" >店舗</div>
                        <div class="searchRowContents" >
                            <label>
                                <input class="" type="checkbox" name="other_shop" <?= (request('other_shop') == 'on')? ' checked': ''; ?>>他店舗の顧客を含める
                            </label>
                        </div>
                    </div>
                    <div class="searchRow" >
                        <div class="searchRowTitle" >担当スタイリスト</div>
                        <div class="searchRowContents" >
                            <label>
                                <input class="" type="checkbox" name="other_staff" <?= (request('other_staff') == 'on')? ' checked': ''; ?>>他スタイリストの顧客を含める
                            </label>
                        </div>
                    </div>
                    <div class="searchRow" >
                        <div class="searchRowTitle" >生年月日</div>
                        <div class="searchRowContents" >
                            <div class="w-32" style="margin-right: 0.5rem;">
                                <input class="form-control inputYear" type="num" name="birthday_year" placeholder="年" value="{{ request('birthday_year') }}" >
                            </div>
                            /
                            <div class="w-20" style="margin: 0 0.5rem;">
                                <input class="form-control inputMonth" type="num" name="birthday_month" placeholder="月" value="{{ request('birthday_month') }}" >
                            </div>
                            /
                            <div class="w-20" style="margin-left: 0.5rem;">
                                <input class="form-control inputDay" type="num" name="birthday_day" placeholder="日" value="{{ request('birthday_day') }}" >
                            </div>
                        </div>
                    </div>
                    <div class="searchRow" >
                        <div class="searchRowTitle" >電話番号</div>
                        <div class="searchRowContents" >
                            <div class="w-56" >
                                <input class="form-control" type="text" name="tel" placeholder="090-1234-5678" value="{{ request('tel') }}" >
                            </div>
                        </div>
                    </div>
                    <div class="searchRow" >
                        <div class="searchRowTitle" >メールアドレス</div>
                        <div class="searchRowContents" >
                            <input class="form-control" type="text" name="email" placeholder="sample@exsample.com" value="{{ request('email') }}" >
                        </div>
                    </div>
                    <div class="searchRow" >
                        <div class="searchRowTitle" >住所</div>
                        <div class="searchRowContents" >
                            <div class="w-full">
                                <div class="flex w-full items-center" >
                                    <div class="w-20" style="padding-right: 0.5rem;">
                                        <input class="form-control inputZip" type="text" name="zip21" placeholder="150" value="{{ request('zip21') }}" >
                                    </div>
                                    -
                                    <div class="w-24" style="padding-left: 0.5rem;" >
                                        <input class="form-control inputZip" type="text" name="zip22" placeholder="0022" value="{{ request('zip22') }}" >
                                    </div>
                                </div>
                                <div class="flex w-full mt-1" >
                                    <div class="w-40" style="padding-right: 0.5rem;" >
                                        <input class="form-control inputAddress" type="text" name="pref21" placeholder="東京都" >
                                    </div>
                                    <div class="w-48" >
                                        <input class="form-control inputAddress" type="text" name="address21" placeholder="渋谷区" >
                                    </div>
                                </div>
                                <div class="flex w-full mt-1" >
                                    <input class="form-control inputStreet" type="text" name="street21" placeholder="恵比寿南1丁目マンション名1101号室" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="searchRow" >
                        <div class="searchRowTitle" >非表示</div>
                        <div class="searchRowContents" >
                            <label>
                                <input class="" type="checkbox" name="hidden_flag" <?= (request('hidden_flag') == 'on')? ' checked': ''; ?>>非表示にした顧客を含める
                            </label>
                        </div>
                    </div>
                        <div class="flex mt-4" >
                            <input type="submit" name="" value="検索する" class="register-btn" >
                        </div>
                </form>
            </div>
            <div >
                {{ $customers->appends(request()->input())->links() }}
                <table class="list-tbl userListTBL">
                    <thead>
                        <tr>
                            <th>顧客番号</th>
                            <th>顧客名</th>
                            <th>所属店舗</th>
                            <th>担当スタイリスト</th>
                            <th>確認</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers AS $customer)
                            <tr>
                                <td>{{ $customer->customer_no }}</td>
                                <td>{{ $customer->f_name . $customer->l_name }}</td>
                                <td>{{ $customer->shop_name }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>
                                    @if(!empty(\Auth::user()->checkAuthByShopId($customer->shop_id)->customer_read) &&
                                            \Auth::user()->checkAuthByShopId($customer->shop_id)->customer_read)
                                        <a href="{{ route('customer.show', ['customer'=>$customer->id ]) }}" >確認</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

