@extends('layouts.app')

@section('content')

@include('oldReport.navigation')
<script src="{{ asset('js/oldReport.js') }}" defer></script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="setDayRecode" >
                <input type="submit" id="previousDay" value="前日" class="changeRecodeBtn" >
                <input type="date" id="selectedDay" value="{{ $setData }}" >
                <input type="submit" id="nextDay" value="翌日" class="changeRecodeBtn" >
                <input type="submit" id="today" value="本日" class="changeRecodeBtn" >
            </div>
            <div class="setDayRecode" >
                @foreach($shops as $shop)
                    <input type="radio" name="shopChoice" id="item-<?= $shop['id'] ?>" class="selectRadioChoice shopChoice" value="<?= $shop['id'] ?>" <?php if($shop['id'] == $defaultShopId) echo ' checked="checked"'; ?> >
                    <label class="selectRadiosLabel" for="item-<?= $shop['id'] ?>"><?= $shop['shop_name'] ?></label>
                @endforeach
            </div>
            <div class="setDayRecode" >
                <input type="radio" name="selectChoice" id="selectChoice-1" class="selectRadioChoice selectChoice" value="1" <?php if($defaultSelectChoice == 1) echo ' checked="checked"'; ?> >
                <label class="selectRadiosLabel" for="selectChoice-1">会計別</label>
                <input type="radio" name="selectChoice" id="selectChoice-2" class="selectRadioChoice selectChoice" value="2" <?php if($defaultSelectChoice == 2) echo ' checked="checked"'; ?> >
                <label class="selectRadiosLabel" for="selectChoice-2">メニュー別</label>
                <input type="radio" name="selectChoice" id="selectChoice-3" class="selectRadioChoice selectChoice" value="3" <?php if($defaultSelectChoice == 3) echo ' checked="checked"'; ?> >
                <label class="selectRadiosLabel" for="selectChoice-3">スタイリスト別</label>
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