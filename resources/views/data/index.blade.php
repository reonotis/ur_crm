@extends('layouts.data')
@section('pageTitle', 'データ分析')

@section('content')
    <div id="loading" style="display: none"></div>
    <div class="">
        <div class="flex mb-2">
            <div class="data-condition-title">対象期間</div>
            <div class="data-condition-content">
                <input type="date" id="fromDate" value="{{ $dataSearch['fromDate'] }}">～
                <input type="date" id="endDate" value="{{ $dataSearch['endDate'] }}">
            </div>
        </div>
        <div class="flex mb-2">
            <div class="data-condition-title">取得情報</div>
            <div class="data-condition-content flex analyze-radio">
                @foreach(DataAnalyze::ANALYZE_TYPE_LIST as $analyzeTypeKye=> $analyzeType)
                    <label>
                        <input type="radio" name="getType" value="{{ $analyzeTypeKye }}"
                           @if( $analyzeTypeKye == $dataSearch['type'])
                                checked
                           @endif
                        >
                        {{ $analyzeType }}
                    </label>
                @endforeach
            </div>
        </div>
    </div>
    <div class="flex">
        <div class="">
            <input type="button" id="getDataButton" value="取得">
            <a href="?back=true" class="submit back-btn min-btn" >前回検索した条件で取得する</a>
        </div>
    </div>
    <div class="flex">
        <div class="data-analyzed" id="data-analyzed"></div>
    </div>

@endsection


