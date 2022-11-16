@extends('layouts.medical')
@section('pageTitle', 'カルテ登録')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="flashArea" >
                @include('layouts.flashMessage')
            </div>
            <div class="medical-contents-area" >
                <p class="welcome-sentence" >{{ $shop->shop_name }}&nbsp;へようこそ</p>
                <p class="welcome-sentence-support" >カルテを作成しますので下記入力をお願いいたします。</p>
                <form method="post" action="{{route('medical.store')}}" >
                    @csrf
                    <div class="medical-row" >
                        <div class="medical-title required" >名前</div>
                        <div class="medical-contents" >
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
                        <div class="medical-contents flex" >
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
                    <div class="medical-row" >
                        <div class="medical-title" >電話番号</div>
                        <div class="medical-contents" >
                            <input type="text" name="tel" id="tel" class="form-control" value="{{ old('tel') }}" placeholder="090-1234-5678" >

                        </div>
                    </div>
                    <div class="medical-row" >
                        <div class="medical-title" >メールアドレス</div>
                        <div class="medical-contents" >
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="sample@exsample.com" >
                        </div>
                    </div>
                    <div class="medical-row" >
                        <div class="medical-title" >住所</div>
                        <div class="medical-contents" >
                            <div class="w-full">
                                <div class="flex w-full items-center" >
                                    <div class="w-20" style="padding-right: 0.5rem;">
                                        <input class="form-control" type="text" name="zip21" value="{{ old('zip21') }}" placeholder="150" >
                                    </div>
                                    -
                                    <div class="w-24" style="padding-left: 0.5rem;" >
                                        <input class="form-control" type="text" name="zip22" value="{{ old('zip22') }}" placeholder="0022" >
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
                    <div class="medical-row" >
                        <div class="medical-title" >アンケート1</div>
                        <div class="medical-contents" >
                            <div class="" >
                                <p class="question-support">お店を知ったきっかけを教えてください。<br class="pc-hidden"><span class="welcome-sentence-support" >※複数回答可</span></p>
                                <div class="flex" style="flex-wrap: wrap;">
                                    @foreach(Common::QUESTION_1_LIST AS $key => $question1)
                                        <label>
                                            <input type="checkbox" name="" value="{{ $key }}" >
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
