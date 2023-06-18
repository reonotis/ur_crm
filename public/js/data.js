window.addEventListener('load', () => {
    let params = new URL(window.location.href).searchParams;
    if (params.get('back')) {
        displayHistoryData();
    }
})

$("#getDataButton").click(function () {
    displayHistoryData();
});

/**
 * 検索条件に伴い検索実行し、結果を表示する
 */
function displayHistoryData() {
    const errMsg = checkDataValid();
    if (errMsg) {
        alert(errMsg);
        return;
    }
    const getType = $('input:radio[name="getType"]:checked').val();
    startRoadContent()

    $.ajax({
        type: 'post',
        url: 'getAnalyzed',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        dataType: 'json',
        data: {
            fromDate: $('#fromDate').val(),
            endDate: $('#endDate').val(),
            type: getType,
        }
    }).done(function (data) {
        switch (getType) {
            case '1':
                setVisitHistory(data)
                break
            case '2':
                setStylist(data)
                break
            case '3':
                console.log('333333333333')
                break
        }
        endRoadContent()
    }).fail(function (error) { // ajax失敗時の処理
        alert(error.statusText + ' : データの取得に失敗しました')
        if (error.status === 419) {
            alert('CSRF : 画面をリロードしてから再度同様の動作を行って下さい')
        }
        endRoadContent()
    })
}

/**
 * 日付の妥当性を検証し、問題がある場合はエラーメッセージを返す
 * @returns {string}
 */
function checkDataValid() {
    const from = $('#fromDate').val();
    const end = $('#endDate').val();
    const getType = $('input:radio[name="getType"]:checked').val();

    if (from === '') {
        return '開始日が設定されていません';
    }
    if (end === '') {
        return '終了日が設定されていません';
    }
    if (from > end) {
        return '開始日が終了日よりも過去に設定されています';
    }
    if (getType === undefined) {
        return '取得情報が設定されていません';
    }

}

/**
 * 分析結果に来店履歴の検索結果を表示する
 * @param data
 */
function setVisitHistory(data) {
    let html;
    if (data.length === 0) {
        html = "データはありません";
    } else {
        html = makeHTMLForTypeVisitHistory(data);
    }
    $('#data-analyzed').html(html);
}

/**
 * 来店情報 を表示するためのHTMLを作成する
 * @param data
 * @returns {string}
 */
function makeHTMLForTypeVisitHistory(data) {
    let html = '';
    html = "<table class='data-table'>";
    html += "<tr>";
    html += "<th>" + 'id' + "</th>";
    html += "<th>" + '来店日時' + "</th>";
    html += "<th>" + '担当者' + "</th>";
    html += "<th>" + 'メニュー' + "</th>";
    html += "<th>" + '顧客名' + "</th>";
    html += "</tr>";
    data.forEach(value => {
        let year = parseInt(value['vis_date'].substring(0, 4));
        let month = parseInt(value['vis_date'].substring(5, 7));
        let day = parseInt(value['vis_date'].substring(8, 10));
        let hour = parseInt(value['vis_time'].substring(0, 2));
        let minute = parseInt(value['vis_time'].substring(3, 5));
        let date = changeFormat('YYYY-mm-dd HH:ii', year + '/' + month + '/' + day + ' ' + hour + ':' + minute);

        let manu_name = '';
        if (value['menu_name'] !== null) {
            manu_name = value['menu_name'];
        }

        html += "<tr>";
        html += "<td>" + value['id'] + "</td>";
        html += "<td>" + date + "</td>";
        html += "<td>" + value['name'] + "</td>";
        html += "<td>" + manu_name + "</td>";
        html += "<td>";
        html += "<a href='../customer/" + value['customer_id'] + "' class='customer-anchor sex-" + value['sex'] + "' >";
        html += value['f_name'] + " " + value['l_name'] + " 様";
        html += "</a>";
        html += "</td>";
        html += "</tr>";
    });
    html += "</table>";

    return html;
}

/**
 * 分析結果にスタイリスト別の検索結果を表示する
 * @param data
 */
function setStylist(data) {
    let html;
    if (data.length === 0) {
        html = "データはありません";
    } else {
        html = makeHTMLForTypeStylist(data);
    }
    $('#data-analyzed').html(html);
}

/**
 * スタイリストの施術情報を表示する為のHTMLを作成する
 * @param data
 * @returns {string}
 */
function makeHTMLForTypeStylist(data) {

    const entries = Object.entries(data)

    let html = '';
    html = "<table class='data-table'>";
    html += "<tr>";
    html += "<th>スタイリスト</th>";
    html += "<th>施術人数</th>";
    html += "<th>お客様名</th>";
    html += "<th>来店日</th>";
    html += "<th>来店時間</th>";
    html += "</tr>";

    entries.forEach(value => {
        html += "<tr>";
        html += '<td rowspan="' + value[1].length + '">' + value[1][0]['name'] + "</td>";
        html += '<td rowspan="' + value[1].length + '">' + value[1].length + "名</td>";
        value[1].forEach(history => {
            html += '<td>';

            html += "<a href='../customer/" + history['customer_id'] + "' class='customer-anchor sex-" + history['sex'] + "' >";
                html += history['f_name'] + " " + history['l_name'] + " 様</td>";
            html += "</a>";
            html += '</td>';
            html += '<td>' + changeFormat("YYYY年mm月dd日", history['vis_date']) + "</td>";
            html += '<td>' + changeFormat("HH:ii", history['vis_time'], "HH:ii:ss") + "</td>";
            html += "</tr>";
        })
        html += "</tr>";
    });
    html += "</table>";

    return html;
}
