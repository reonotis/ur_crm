
@if($reserve_list)
    @if($dateDisplay === true)
        <div class="date-display">
            <span class="emphasize-y">{{ $businessTime['date']->format('Y') }}</span>
            <span class="emphasize-y-s">年</span>
            <span class="emphasize-m">{{ $businessTime['date']->format('n') }}</span>
            <span class="emphasize-m-s">月</span>
            <span class="emphasize-d">{{ $businessTime['date']->format('j') }}</span>
            <span class="emphasize-d-s">日</span>
            <span class="emphasize-w">{{ App\Consts\ShopSettingConst::WEEK_LABEL_OMITTED[$businessTime['date']->dayOfWeekIso] }}</span>
        </div>
    @endif
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
                                                    @php $reserve_content = 'reserve-content reserve-status'; @endphp

                                                    @if($reserve['status'] == 5)
                                                        @php $reserve_content = $reserve_content . ' guided'; @endphp
                                                    @endif
                                                    <div
                                                        class="{{ $reserve_content }}"
                                                        data-vis-time="{{ Carbon\Carbon::parse($reserve['vis_time'])->format('Y-m-d H:i:s') }}"
                                                        data-end-time="{{ Carbon\Carbon::parse($reserve['vis_end_time'])->format('Y-m-d H:i:s') }}"
                                                    >
                                                        <div class="reserve-type">
                                                            @switch($reserve['reserve_type'])
                                                                @case(0)
                                                                    <span>不</span>
                                                                    @break
                                                                @case(1)
                                                                    <span>来</span>
                                                                    @break
                                                                @case(5)
                                                                    <span>予</span>
                                                                    @break
                                                            @endswitch
                                                        </div>
                                                        <div>
                                                            <div>
                                                                @if($reserve['treatment_time'] <= 60)
                                                                    {{ Carbon\Carbon::parse($reserve['vis_time'])->format('H:i') }}
                                                                @else
                                                                    {{ Carbon\Carbon::parse($reserve['vis_time'])->format('H:i') }}
                                                                    ～
                                                                    {{ Carbon\Carbon::parse($reserve['vis_end_time'])->format('H:i') }}
                                                                @endif
                                                            </div>
                                                            <div>
                                                                @if($reserve['treatment_time'] <= 30)
                                                                    {{ $reserve['f_name'] }}様
                                                                @else
                                                                    {{ $reserve['f_name'] }}&nbsp;{{ $reserve['l_name'] }}&nbsp;様
                                                                @endif
                                                            </div>
                                                        </div>
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
    <div class="reception-table-support">
        <div class="reception-support-contents">
            <div class="reception-support-header">受付方法</div>
            <div class="reception-support-body">
                <div class="flex-start-middle">
                    <div class="reserve-type"><span>不</span></div>
                    不明
                </div>
                <div class="flex-start-middle">
                    <div class="reserve-type"><span>来</span></div>
                    来店時
                </div>
                <div class="flex-start-middle">
                    <div class="reserve-type"><span>予</span></div>
                    予約
                </div>
            </div>
        </div>
        <div class="reception-support-contents">
            <div class="reception-support-header">ステータス</div>
            <div class="reception-support-body">
                <div class="flex-start-middle">
                    <div class="reserve-status" style="width:30px;">　</div>
                    未案内
                </div>
                <div class="flex-start-middle">
                    <div class="reserve-status guided" style="width:30px;">　</div>
                    案内済み
                </div>
            </div>
        </div>
    </div>
@else
    受付表を作成するための来店データがありません
@endif
