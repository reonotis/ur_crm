@php
    $key = 0;
@endphp

<div style="">

    {{ $calendar[0]['date']->format('Y年m月') }}
    <table class="list-tbl calendar-tbl" style="margin-bottom: 4rem;">
        <thead>
            <tr>
                @foreach($weekList as $weekKey)
                    <th>{{ App\Consts\ShopSettingConst::WEEK_LABEL_LIST[$weekKey] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{-- 6週間分ループ --}}
            @for ($counter = 0; $counter <= 6; $counter++)
                <tr>
                    {{-- 7曜日をループ --}}
                    @foreach($weekList as $weekKey)
                        @if(isset($calendar[$key]))
                            @if($calendar[$key]['date']->dayOfWeekIso == $weekKey)
                                <td>
                                    <p class="day-paragraph">
                                        {{ $calendar[$key]['date']->format('j') }}
                                        @if($calendar[$key]['holiday'] == App\Consts\ShopSettingConst::HOLIDAY)
                                            <span class="float-right" style="color: var(--red);">祝日</span>
                                        @elseif($calendar[$key]['holiday'] == App\Consts\ShopSettingConst::BEFORE_HOLIDAY)
                                            <span class="float-right" style="color: var(--blue);">祝前日</span>
                                        @endif
                                        @if($calendar[$key]['temporary'])
                                            <span class="temporary-day">※
                                                <span class="tooltip-text">臨時定休/臨時営業が適用されています</span>
                                            </span>
                                        @endif
                                    </p>
                                        {{-- 臨時定休/臨時営業の場合 --}}
                                        @if($calendar[$key]['temporary'])
                                            @if($calendar[$key]['temporary']->holiday)
                                                <p>臨時定休</p>
                                            @else
                                                <p>O : {{ Carbon\Carbon::createFromTimeString($calendar[$key]['business_hours']['business_open_time'])->format('H:i') }}</p>
                                                <p>L : {{ Carbon\Carbon::createFromTimeString($calendar[$key]['business_hours']['business_close_time'])->format('H:i') }}</p>
                                                <p>C : {{ Carbon\Carbon::createFromTimeString($calendar[$key]['business_hours']['last_reception_time'])->format('H:i') }}</p>
                                            @endif
                                        @elseif($calendar[$key]['close'])
                                            {{-- 閉店している場合 --}}
                                            閉店
                                        @elseif($calendar[$key]['regular_holiday'])
                                            <p>定休日</p>
                                        @elseif(empty($calendar[$key]['business_hours']))
                                            <p>未設定</p>
                                        @else
                                            {{-- 通常営業 --}}
                                            <p>O : {{ Carbon\Carbon::createFromTimeString($calendar[$key]['business_hours']['business_open_time'])->format('H:i') }}</p>
                                            <p>L : {{ Carbon\Carbon::createFromTimeString($calendar[$key]['business_hours']['last_reception_time'])->format('H:i') }}</p>
                                            <p>C : {{ Carbon\Carbon::createFromTimeString($calendar[$key]['business_hours']['business_close_time'])->format('H:i') }}</p>
                                        @endif
                                </td>

                                @php
                                    $key++;
                                @endphp
                            @else
                                <td class="gray-out"></td>
                            @endif
                        @else
                            <td class="gray-out"></td>
                        @endif
                    @endforeach
                </tr>
                {{-- 次のデータがなければ終了 --}}
                @if(!isset($calendar[$key]))
                    @break
                @endif
            @endfor
        </tbody>
    </table>
</div>
