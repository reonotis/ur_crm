<link href="{{ asset('css/reception_table.css') }}?<?= date('Ymdhi') ?>" rel="stylesheet">

@if($reserve_list)
    <div class="reserve-area">
        <div class="reserve-tbl-box">
            <div class="reserve-tbl-header">
                <div class="user-name">スタイリスト名</div>
                @foreach ($time_array as $key => $time)
                    {{-- クラス名作成 --}}
                    @php $time_box_class = ''; @endphp  {{-- 初期化 --}}
                    @if($time->format('i') == '00' )
                        @php $time_box_class = 'bl-gray-1'; @endphp  {{-- ボーダークラス追加 --}}
                    @endif

                    <div class="time-box {{ $time_box_class }}">
                        {{-- 1時間毎 かつ 配列の最後でなければ時間を表示 --}}
                        @if($time->format('i') == 0 && $time <> end($time_array))
                            <div>{{ $time->format('H')}}</div>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="reserve-tbl-body">
                {{-- ユーザー毎にループ --}}
                @foreach($reserve_list as $user_reserves)
                    <div class="user-row">
                        <div class="user-name">{{ $user_reserves['user_name'] }}</div>
                        <div class="user-reserve-rows">
                            {{-- ユーザーの予約ラインをループ --}}
                            @foreach($user_reserves['lines'] as $line_reserves)
                                <div class="reserve-row">
                                    {{-- 営業時間をループ --}}
                                    @foreach ($time_array as $key => $time)
                                        {{-- クラス名作成 --}}
                                        @php $time_box_class = ''; @endphp
                                        @if($open_time > $time || $close_time <= $time)
                                            @php $time_box_class = 'close-time'; @endphp
                                        @elseif($lo_time <= $time)
                                            @php $time_box_class = 'lo-time'; @endphp
                                        @endif

                                        @if($time->format('i') == '00' || $time->format('i') == '30' )
                                            @php $time_box_class = $time_box_class . ' bl-gray-1'; @endphp
                                        @endif
                                        <div class="time-box {{ $time_box_class }}">
                                            {{-- ラインの予約をループ--}}
                                            @foreach($line_reserves as $reserve_key => $reserve)
                                                {{-- 予約時間だったら--}}
                                                @if($time->format('H:i:s') >= $reserve['vis_time'])
                                                    <div
                                                        class="reserve-content @if(isset($reserve['check_data'])) check-data @endif"
                                                        data-vis-time="{{ Carbon\Carbon::parse($reserve['vis_time'])->format('Y-m-d H:i:s') }}"
                                                        data-end-time="{{ Carbon\Carbon::parse($reserve['vis_end_time'])->format('Y-m-d H:i:s') }}"
                                                    >
                                                        <p>
                                                            {{ Carbon\Carbon::parse($reserve['vis_time'])->format('H:i') }}
                                                            ～
                                                            {{ Carbon\Carbon::parse($reserve['vis_end_time'])->format('H:i') }}
                                                        </p>
                                                        <p>{{ $reserve['f_name'] }}&nbsp{{ $reserve['l_name'] }}&nbsp様</p>
                                                    </div>
                                                    @php
                                                        unset($line_reserves[$reserve_key]);
                                                    @endphp
                                                @endif
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@else
    受付表を作成するための来店データがありません
@endif
