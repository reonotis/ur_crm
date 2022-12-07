
// ショップを変更したとき
// reset_users() との違いはユーザーの選択を解除する
function change_shops(){
    shop_id = $('select[name="shop_id"] option:selected').attr("class");
    // ユーザーの要素数を取得
    var count = $('select[name="staff_id"]').children().length;

    var first_user = 0;
    // ユーザーの要素数分、for文で回す
    for (var i = 0; i < count; i++) {
        var user = $('select[name="staff_id"] option:eq(' + i + ')');

        if (user.attr("class") === shop_id) {
            // 選択した国と同じクラス名だった場合 都市の要素を表示
            user.show();
            if(first_user === 0){
                first_user = user.val();
                $('#staff_id').val(first_user);
            }
        } else {
            // 選択した国とクラス名が違った場合 都市の要素を非表示
            user.hide();
        }
    }

}

// 選択できるスタイリストを設定する
function reset_users(){
    var shop_id = $('select[name="shop_id"] option:selected').attr("class");

    // ユーザーの要素数を取得
    var count = $('select[name="staff_id"]').children().length;

    // ユーザーの要素数分、for文で回す
    for (var i = 0; i < count; i++) {
        var user = $('select[name="staff_id"] option:eq(' + i + ')');

        if (user.attr("class") === shop_id) {
            // 選択した国と同じクラス名だった場合 都市の要素を表示
            user.show();
        } else {
            // 選択した国とクラス名が違った場合 都市の要素を非表示
            user.hide();
        }
    }
}

// 来店履歴を登録する時のコンフィルムダイアログ
function registerConfirm(){
    return (window.confirm('本日の来店履歴を登録します。宜しいでしょうか？'));
}
