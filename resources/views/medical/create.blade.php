@extends('layouts.medical')
@section('pageTitle', 'カルテ登録')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="medical-contents-area" >
                <p class="welcome-sentence" >{{ $shop->shop_name }}&nbsp;へようこそ</p>
                <p class="welcome-sentence-support" >カルテを作成しますので下記入力をお願いいたします。</p>
                <div class="flashArea" >
                    @include('layouts.flashMessage')
                </div>
                <form method="post" action="{{route('medical.store')}}" onsubmit="return checkValidate()">
                    @csrf
                    <div class="medical-row" >
                        <div class="medical-title required" >名前</div>
                        <div class="medical-contents" >
                            <p id="f_name_error_message" class="error-message" ></p>
                            <p id="l_name_error_message" class="error-message" ></p>
                            <div class="flex" >
                                <div class="input-name" style="margin-right: 0.5rem;">
                                    <input type="text" name="f_name" id="f_name" class="form-control" value="{{ old('f_name') }}" placeholder="田中" >
                                </div>
                                <div class="input-name" >
                                    <input type="text" name="l_name" id="l_name" class="form-control" value="{{ old('l_name') }}" placeholder="太郎" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="medical-row" >
                        <div class="medical-title required" >ナマエ</div>
                        <div class="medical-contents" >
                            <p id="f_read_error_message" class="error-message" ></p>
                            <p id="l_read_error_message" class="error-message" ></p>
                            <div class="flex" >
                                <div class="input-name" style="margin-right: 0.5rem;">
                                    <input type="text" name="f_read" id="f_read" class="form-control" value="{{ old('f_read') }}" placeholder="タナカ" >
                                </div>
                                <div class="input-name" >
                                    <input type="text" name="l_read" id="l_read" class="form-control" value="{{ old('l_read') }}" placeholder="タロウ" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="medical-row" >
                        <div class="medical-title" >性別</div>
                        <div class="medical-contents" >
                            @foreach(Common::SEX_LIST as $sexKey => $sexName)
                                <label class="sex-lavel" >
                                    <input type="radio" name="sex" value="{{ $sexKey }}" >{{ $sexName }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="medical-row" >
                        <div class="medical-title" >生年月日</div>
                        <div class="medical-contents" >
                            <p id="birthday_year_error_message" class="error-message" ></p>
                            <p id="birthday_month_error_message" class="error-message" ></p>
                            <p id="birthday_day_error_message" class="error-message" ></p>
                            <div class="flex-center-middle" >
                                <div class="w-32" style="margin-right: 0.5rem;">
                                    <input type="number" class="form-control" id="birthday_year" name="birthday_year" value="{{ old('birthday_year') }}" placeholder="年" min="1900" max="2022" >
                                </div>
                                /
                                <div class="w-20" style="margin: 0 0.5rem;">
                                    <input type="number" class="form-control" id="birthday_month" name="birthday_month" value="{{ old('birthday_month') }}" placeholder="月" min="1" max="12" >
                                </div>
                                /
                                <div class="w-20" style="margin-left: 0.5rem;">
                                    <input type="number" class="form-control" id="birthday_day" name="birthday_day" value="{{ old('birthday_day') }}" placeholder="日" min="1" max="31" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="medical-row" >
                        <div class="medical-title" >電話番号</div>
                        <div class="medical-contents" >
                            <p id="tel_error_message" class="error-message" ></p>
                            <input type="text" name="tel" id="tel" class="form-control" value="{{ old('tel') }}" placeholder="090-1234-5678" >
                        </div>
                    </div>
                    <div class="medical-row" >
                        <div class="medical-title" >メールアドレス</div>
                        <div class="medical-contents" >
                            <p id="email_error_message" class="error-message" ></p>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="sample@exsample.com" >
                        </div>
                    </div>
                    <div class="medical-row" >
                        <div class="medical-title" >住所</div>
                        <div class="medical-contents" >
                            <div class="w-full">
                                <p id="zip21_error_message" class="error-message" ></p>
                                <p id="zip22_error_message" class="error-message" ></p>
                                <div class="w-full flex-center-middle" >
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
                                <div class="w-full mt-1" >
                                    <input class="form-control" type="text" name="street21" value="{{ old('street21') }}" placeholder="恵比寿南1丁目マンション名1101号室" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="medical-row" >
                        <div class="medical-title" >アンケート1</div>
                        <div class="medical-contents" >
                            <div class="" >
                                <p class="question-support">お店を知ったきっかけを教えてください。<br class="pc-hidden"><span class="welcome-sentence-support" >※複数回答可</span></p>
                                <div class="flex" style="flex-wrap: wrap;">
                                    @foreach(Common::QUESTION_1_LIST AS $key => $question1)
                                        <label>
                                            <input type="checkbox" name="question_1[]" value="{{ $key }}" >
                                            {{ $question1 }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="medical-row" >
                        <div class="medical-title" >コメント</div>
                        <div class="medical-contents" >
                            <div class="" >
                                <p class="welcome-sentence-support" >お店への要望などございましたらお気軽にご記入ください</p>
                                <textarea name="" class="form-control" ></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="flex mt-4" >
                        <input type="hidden" name="shop_id" value="{{ $shop->id }}" >
                        <input type="submit" name="" value="完了" class="register-btn" >
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
