
<table class="list-tbl setting-tbl" style="margin-bottom: 4rem;">
    <thead>
        <tr>
            <th>曜日</th>
            <th>定休日</th>
            <th>営業開始時間</th>
            <th>最終受付時間</th>
            <th>営業終了時間</th>
            <th>適用終了日</th>
        </tr>
    </thead>
    <tbody>
        @foreach($weekList as $weekNo)
            <tr>
                <td>{{ App\Consts\ShopSettingConst::WEEK_LABEL_LIST[$weekNo] }}</td>
                @if($organizeBusinessHours[$weekNo]) {{-- 設定がある場合--}}
                    {{-- 定休日場合--}}
                    @if($organizeBusinessHours[$weekNo]->regular_holiday)
                        <td>定休日</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    @else
                        <td>-</td>
                        <td>{{ Carbon\Carbon::createFromTimeString($organizeBusinessHours[$weekNo]->business_open_time)->format('H:i') }}</td>
                        <td>{{ Carbon\Carbon::createFromTimeString($organizeBusinessHours[$weekNo]->last_reception_time)->format('H:i') }}</td>
                        <td>{{ Carbon\Carbon::createFromTimeString($organizeBusinessHours[$weekNo]->business_close_time)->format('H:i') }}</td>
                    @endif
                    <td>
                        @if($organizeBusinessHours[$weekNo]->setting_end_date)
                            {{ $organizeBusinessHours[$weekNo]->setting_end_date->format('Y-m-d') }}
                        @endif
                    </td>
                @else
                    {{-- 設定がない場合--}}
                    <td colspan="5"></td>
                @endif
            </tr>
        @endforeach
        <tr>
            <td>{{ App\Consts\ShopSettingConst::WEEK_LABEL_LIST[App\Consts\ShopSettingConst::HOLIDAY] }}</td>
            @if($organizeBusinessHours[App\Consts\ShopSettingConst::HOLIDAY]) {{-- 設定がある場合--}}
                {{-- 定休日場合--}}
                @if($organizeBusinessHours[App\Consts\ShopSettingConst::HOLIDAY]->regular_holiday)
                    <td>定休日</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                @else
                    <td>-</td>
                    <td>{{ Carbon\Carbon::createFromTimeString($organizeBusinessHours[App\Consts\ShopSettingConst::HOLIDAY]->business_open_time)->format('H:i') }}</td>
                    <td>{{ Carbon\Carbon::createFromTimeString($organizeBusinessHours[App\Consts\ShopSettingConst::HOLIDAY]->last_reception_time)->format('H:i') }}</td>
                    <td>{{ Carbon\Carbon::createFromTimeString($organizeBusinessHours[App\Consts\ShopSettingConst::HOLIDAY]->business_close_time)->format('H:i') }}</td>
                @endif
                <td>
                    @if($organizeBusinessHours[App\Consts\ShopSettingConst::HOLIDAY]->setting_end_date)
                        {{ $organizeBusinessHours[App\Consts\ShopSettingConst::HOLIDAY]->setting_end_date->format('Y-m-d') }}
                    @endif
                </td>
            @else
                {{-- 設定がない場合--}}
                <td colspan="5"></td>
            @endif
        </tr>
        <tr>
            <td>{{ App\Consts\ShopSettingConst::WEEK_LABEL_LIST[App\Consts\ShopSettingConst::BEFORE_HOLIDAY] }}</td>
            @if($organizeBusinessHours[App\Consts\ShopSettingConst::BEFORE_HOLIDAY]) {{-- 設定がある場合--}}
                {{-- 定休日場合--}}
                @if($organizeBusinessHours[App\Consts\ShopSettingConst::BEFORE_HOLIDAY]->regular_holiday)
                    <td>定休日</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                @else
                    <td>-</td>
                    <td>{{ Carbon\Carbon::createFromTimeString($organizeBusinessHours[App\Consts\ShopSettingConst::BEFORE_HOLIDAY]->business_open_time)->format('H:i') }}</td>
                    <td>{{ Carbon\Carbon::createFromTimeString($organizeBusinessHours[App\Consts\ShopSettingConst::BEFORE_HOLIDAY]->last_reception_time)->format('H:i') }}</td>
                    <td>{{ Carbon\Carbon::createFromTimeString($organizeBusinessHours[App\Consts\ShopSettingConst::BEFORE_HOLIDAY]->business_close_time)->format('H:i') }}</td>
                @endif
                <td>
                    @if($organizeBusinessHours[App\Consts\ShopSettingConst::BEFORE_HOLIDAY]->setting_end_date)
                        {{ $organizeBusinessHours[App\Consts\ShopSettingConst::BEFORE_HOLIDAY]->setting_end_date->format('Y-m-d') }}
                    @endif
                </td>
            @else
                {{-- 設定がない場合--}}
                <td colspan="5"></td>
            @endif
        </tr>
    </tbody>
</table>
