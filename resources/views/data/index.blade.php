@extends('layouts.data')
@section('pageTitle', 'データ分析')

@section('content')
    <div class="">
        <div class="flex mb-2">
            <div class="data-condition-title">対象期間</div>
            <div class="data-condition-content">
                <input type="date" id="fromDate" value="<?= date('Y-m-d'); ?>">～
                <input type="date" id="endDate" value="<?= date('Y-m-d'); ?>">
            </div>
        </div>
        <div class="flex mb-2">
            <div class="data-condition-title">取得情報</div>
            <div class="data-condition-content flex">
                @foreach(DataAnalyze::ANALYZE_TYPE_LIST as $analyzeTypeKye=> $analyzeType)
                    <div class="">
                        <label>
                            <input type="radio" name="getType" value="{{ $analyzeTypeKye }}" checked="checked">
                            {{ $analyzeType }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="flex">
        <div class="basic-report-area">
            <input type="button" id="getDataButton" value="取得">
        </div>
    </div>
    <div class="flex">
        <div class="data-analyzed" id="data-analyzed">
            ここにデータが入ります
        </div>
    </div>

@endsection


