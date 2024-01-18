@extends('layouts.report')

@section('js')
    <script src="{{ asset('js/reception_table.js') }}?<?= date('Ymdhi') ?>"></script>
@endsection

<link href="{{ asset('css/reception_table.css') }}?<?= date('Ymdhi') ?>" rel="stylesheet">

@section('breadcrumb')
    <ol>
        <li><a href="{{ route('home') }}">ホーム</a></li>
    </ol>
@endsection
@section('pageTitle', '受付表')
@section('content')
    <div class="flex" style="margin-bottom: 1rem;">
        <div class="move-btn prev" id="prev">前日</div>
        <input type="date" id="date" class="form-control w-48" value="{{ $date }}">
        <div class="move-btn next" id="next">翌日</div>
    </div>
    <div style="min-height: 100px">
        <div id="loading" style="display: none"></div>
        <div id="reception_table_area"></div>
    </div>

    <script>
        let date = @json(date('Y-m-d'));
        let date_display = @json(1); // 受付表に日付を表示する

        $("#date").change(function() {
            reloadReserveList($("#date").val());
        });

        /**
         * 翌日ボタン押下時
         */
        $("#next").click(function() {
            var datetime = new Date($("#date").val()); // 現在セットされている日付を取得
            datetime.setDate(datetime.getDate() + 1); // 1日加算する
            loadReserveList(datetime);
        });

        /**
         * 前日ボタン押下時
         */
        $("#prev").click(function() {
            var datetime = new Date($("#date").val()); // 現在セットされている日付を取得
            datetime.setDate(datetime.getDate() - 1); // 1日加算する
            loadReserveList(datetime);
        });

        /**
         * 対象日の受付表を取得し、表示する
         * @param {Object} datetime
         */
        function loadReserveList(datetime)
        {
            // 日付の文字列を作成
            let year = datetime.getFullYear();
            let month = ('0' + (datetime.getMonth() + 1)).slice(-2);
            let date = ('0' + datetime.getDate()).slice(-2);
            let result = year + '-' + month + '-' + date;

            $("#date").val(result) // inputにセット
            reloadReserveList(result);
        }

    </script>
@endsection
