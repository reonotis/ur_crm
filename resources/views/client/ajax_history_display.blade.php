<form action="" method="POST" >
    @csrf
    <table class="callDetailTable">
        <tr>
            <th>ID</th>
            <td>{{$Contact->id}}</td>
        </tr>
        <tr>
            <th>日時</th>
            <td>
                <input type="datetime-local" name="history_datetime" id="history_datetime" value="<?= str_replace(' ', 'T',  date('Y-m-d H:i:00', strtotime( $Contact -> history_datetime ))); ?>" >
            </td>
        </tr>
        <tr>
            <th>受電者</th>
            <td>
                <input type="" name="" id="recipient_name" value="{{$Contact->recipient_name}}" >
                <input type="" name="" id="recipient_role" value="{{$Contact->recipient_role}}" >
                <input type="" name="" id="recipient_sex" value="{{$Contact->recipient_sex}}" >
            </td>
        </tr>
        <tr>
            <th>担当者</th>
            <td>
                {{$Contact->person_charge_name}}
                {{$Contact->person_charge_role}}
                {{$Contact->person_charge_sex}}
            </td>
        </tr>
        <tr>
            <th>営業</th>
            <td>
                {{$Contact->staff}}
            </td>
        </tr>
        <tr>
            <th>方法</th>
            <td>
                <select name="" id="means_id">
                    <option value="">選択してください</option>
                    @foreach($means as $mean)
                        <option value="{{ $mean -> id }}" <?php if( $mean -> id == $Contact->means_id ) echo " selected"?> >{{ $mean -> mean_name }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <th>結果</th>
            <td>
                <select name="" id="result_id">
                    <option value="">選択してください</option>
                    @foreach($results as $result)
                        <option value="{{ $result -> id }}" <?php if( $result -> id == $Contact->result_id ) echo " selected"?> >{{ $result -> result_name }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr><th colspan="2">内容</th></tr>
        <tr><td colspan="2">
            <textarea id="history_detail" >{{$Contact->history_detail}}</textarea>
        </td></tr>
    </table>
    <input type="button" name="contact_update" value="更新" onclick="contact_up({{$Contact->id}})" >
</form>


