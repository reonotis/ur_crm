@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">スタイリスト一覧</div>

                <div class="card-body">
                    <select id="shops" class="formInput inputShop" >
                        @foreach($shops as $shop)
                            <option value="<?= $shop->id ?>" <?php if( $shop->id == $defaultShopId) echo " selected" ; ?> ><?= $shop->shop_name ?></option>
                        @endforeach
                    </select>
                    <table class="tableClass_006">
                        <tbody>
                            <tr>
                                <th>スタイリスト名</th>
                                <th>店舗</th>
                                <th>権限</th>
                                <th>確認</th>
                            </tr>
                            @foreach($users as $user)
                                <tr style="<?php if($user->shop_id <> $defaultShopId) echo 'display:none;' ?>" class="user_row user_row<?= $user->shop_id ?> " >
                                    <td><?= $user->name ?></td>
                                    <td><?= $user->shop_name ?></td>
                                    <td><?= $user->authority_name ?></td>
                                    <td><a href="{{ route('stylist.show', ['id'=>$user->id]) }}">確認</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<script>

    /**
     * ショップのセレクトを切り替えた時
     */
    $('#shops').on('change',function(e) {
        var selectShopId = $('#shops').val();
        $(".user_row").each( function() {
            if ($(this).hasClass('user_row' + selectShopId)) {
                    $(this).show('slow');
            } else {
                    $(this).hide();
            }
        });
    });

</script>


@endsection

