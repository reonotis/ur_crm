@extends('layouts.medical_record')

@section('content')
<div class="formArea" >
    <p class="supportMessage" ><?= $shop->shop_name ?> へようこそ</p>
    <p class="supportMessage" >お客様のカルテを作成いたしますので、下記ご入力後送信して下さい</p>
    <form action="{{route('medical_record.confirm')}}" method="post" onSubmit="return registerConfilm()" >
        @csrf
        <div class="itemsRow" >
            <div class="itemsTitle required" >名前</div>
            <div class="itemsContent" >
                <div class="CenterBOX" >
                    <input class="formInput formInput_2" type="text" name="f_name" id="f_name" placeholder="田中" >
                    <input class="formInput formInput_2" type="text" name="l_name" id="l_name" placeholder="太郎" >
                </div>
            </div>
        </div>
        <div class="itemsRow" >
            <div class="itemsTitle" >ナマエ</div>
            <div class="itemsContent" >
                <div class="CenterBOX" >
                    <input class="formInput formInput_2" type="text" name="f_read" id="f_read" placeholder="タナカ" >
                    <input class="formInput formInput_2" type="text" name="l_read" id="l_read" placeholder="タロウ" >
                </div>
            </div>
        </div>
        <div class="itemsRow" >
            <div class="itemsTitle" >性別</div>
            <div class="itemsContent" >
                <div class="itemsContent_label" >
                    <label><input type="radio" name="sex" value="1" >男性</label>
                    <label><input type="radio" name="sex" value="2" >女性</label>
                    <label><input type="radio" name="sex" value="3" >その他</label>
                    <label><input type="radio" name="sex" value="4" >未回答</label>
                </div>
            </div>
        </div>
        <div class="itemsRow" >
            <div class="itemsTitle" >電話番号</div>
            <div class="itemsContent" ><input class="formInput" type="text" name="tel" placeholder="090-1234-5678" ></div>
        </div>
        <div class="itemsRow" >
            <div class="itemsTitle" >メールアドレス</div>
            <div class="itemsContent" ><input class="formInput" type="text" name="email" id="email" placeholder="sample@exsample.com" ></div>
        </div>
        <div class="itemsRow" >
            <div class="itemsTitle" >誕生日</div>
            <div class="itemsContent" >
                <div class="inputBirthday">
                    <input class="formInput inputYear" type="num" name="birthday_year" placeholder="年" > /
                    <input class="formInput inputMonth" type="num" name="birthday_month" placeholder="月" > /
                    <input class="formInput inputDay" type="num" name="birthday_day" placeholder="日" >
                </div>
            </div>
        </div>
        <div class="itemsRow" >
            <div class="itemsTitle" >住所</div>
            <div class="itemsContent" >
                <div class="inputZips">
                    <input class="formInput inputZip" type="text" name="zip21" placeholder="150" >-
                    <input class="formInput inputZip" type="text" name="zip22" placeholder="0022" >
                </div>
                <div class="inputAddrs">
                    <input class="formInput inputAddr" type="text" name="pref21" placeholder="東京都" >
                    <input class="formInput inputAddr" type="text" name="addr21" placeholder="渋谷区" >
                </div>
                <input class="formInput inputStrt" type="text" name="strt21" placeholder="恵比寿南1丁目マンション名1101号室" >
            </div>
        </div>
        <div class="itemsRow" >
            <div class="itemsTitle" >アンケート</div>
            <div class="itemsContent" >
                <p class="support">気になる項目全てにチェックを入れてください</p>
                <div class="itemsContent_checkbox">
                    @foreach($questions as $question)
                        <label><input type="checkbox" name="question1[]" value="<?= $question->answer_name ?>" ><?= $question->answer_name ?></label>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="itemsRow" >
            <div class="itemsTitle" >店舗へのコメント</div>
            <div class="itemsContent" ><textarea class="formInput" name="comment" ></textarea></div>
        </div>
        <div class="itemsRow" >
            <input type="hidden" name="shop_id" value="<?= $shop->id ?>" >
            <input type="submit" name="" class="button" value="確認" >
        </div>
    </form>
</div>



@endsection
