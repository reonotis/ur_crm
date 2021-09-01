@extends('layouts.app')

@section('content')

@include('oldReport.navigation')
<script src="{{ asset('js/oldReport_month.js') }}" defer></script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="setDayRecode" >
                <input type="submit" id="previousMonth" value="前月" class="changeRecodeBtn" >
                <span id="targetMonth_string" >{{ date('Y年m月度', strtotime($months)) }}</span>
                <input type="hidden" id="targetMonth" name="targetMonth" value="{{ $months }}" >
                <input type="submit" id="nextMonth" value="翌月" class="changeRecodeBtn" >
            </div>
            <div class="setDayRecode" >
                @foreach($shops as $shop)
                    <input type="radio" name="shopChoice" id="item-<?= $shop['id'] ?>" class="selectRadioChoice shopChoice" value="<?= $shop['id'] ?>" <?php if($shop['id'] == $defaultShopId) echo ' checked="checked"'; ?> >
                    <label class="selectRadiosLabel" for="item-<?= $shop['id'] ?>"><?= $shop['shop_name'] ?></label>
                @endforeach
            </div>
            <div class="setDayRecode" >
                <input type="radio" name="selectChoice" id="selectChoice-2" class="selectRadioChoice selectChoice" value="2" <?php if($defaultSelectChoice == 2) echo ' checked="checked"'; ?> >
                <label class="selectRadiosLabel selectRadiosLabel2" for="selectChoice-2">メニュー別</label>
                <input type="radio" name="selectChoice" id="selectChoice-3" class="selectRadioChoice selectChoice" value="3" <?php if($defaultSelectChoice == 3) echo ' checked="checked"'; ?> >
                <label class="selectRadiosLabel selectRadiosLabel2" for="selectChoice-3">スタイリスト別</label>
            </div>
            <div class="card">
                <div class="" id="displayArea" >
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

<script type="text/javascript">
    var LOCAL_ENVIRONMENT = "<?= $LOCAL_ENVIRONMENT ?>";
</script>