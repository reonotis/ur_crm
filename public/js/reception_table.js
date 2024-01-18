/**
 * ajaxを実行し、データを予約表を更新する
 */
function reloadReserveList(date) {
    startRoadContent();

    $.ajax({
        type: 'post',
        url: './reserve/getReceptionTable',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        dataType: 'json',
        data: {
            date: date,
            date_display: date_display,
        }
    }).done(function (data) {
        if (typeof data.error == 'undefined') {
            updateReserveList(data);
            resetWith();
        } else {
            alert(data.error.message)
        }
        endRoadContent();

    }).fail(function (error) { // ajax失敗時の処理
        alert(error.statusText + ' : データの取得に失敗しました')
        if (error.status === 419) {
            alert('CSRF : 画面をリロードしてから再度操作を行って下さい')
        }
        endRoadContent();
    })
}

function updateReserveList(data) {
    $('#reception_table_area').html(data['view']);
}

$(window).on('load', function () {
    reloadReserveList(date);
});


/**
 * 各予約の幅を調節する
 */
function resetWith() {
    $(".reserve-content").each(function (i, e) {
        let visTime = new Date($(e).data().visTime); // 開始時間
        let endTime = new Date($(e).data().endTime); // 終了時間
        let diff = endTime.getTime() - visTime.getTime(); // 差分
        let diffMinutes = Math.abs(diff) / (60 * 1000)　// 経過分数

        $(e).width(diffMinutes * 2 - 2); // (1分 × 2px) - borderの2px
    });
}

