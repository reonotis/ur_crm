/**
 */
function startRoadContent(){
    console.log('クルクル実施');
}

/**
 */
function endRoadContent(){
    console.log('クルクル終了');
}

/**
 */
$("#getDataButton").click(function () {
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
                console.log('222222222222')
                break
            case '3':
                console.log('333333333333')
                break
        }
        endRoadContent()
    }).fail(function (error) { // ajax失敗時の処理
        alert(error.statusText + ' : データの取得に失敗しました')
        if(error.status === 419){
            alert('CSRF : 画面をリロードしてから再度同様の動作を行って下さい')
        }
        endRoadContent()
    })
});

/**
 * 日付の妥当性を検証し、問題がある場合はエラーメッセージを返す
 * @returns {string}
 */
function checkDataValid() {
    const from = $('#fromDate').val();
    const end = $('#endDate').val();

    if (from === '') {
        return '開始日が設定されていません';
    }
    if (end === '') {
        return '終了日が設定されていません';
    }
    if (from > end) {
        return '開始日が終了日よりも過去に設定されています';
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
function makeHTMLForTypeVisitHistory(data){
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
        let date = new Date(year, month, day, hour, minute)

        let format_str = 'YYYY-mm-dd HH:ii';
        format_str = format_str.replace(/YYYY/g, date.getFullYear());
        format_str = format_str.replace(/mm/g, ("0" + date.getMonth()).slice(-2));
        format_str = format_str.replace(/dd/g, ("0" + date.getDate()).slice(-2));
        format_str = format_str.replace(/HH/g, ("0" + date.getHours()).slice(-2));
        format_str = format_str.replace(/ii/g, ("0" + date.getMinutes()).slice(-2));

        let manu_name = '';
        if (value['menu_name'] !== null) {
            manu_name = value['menu_name'];
        }

        html += "<tr>";
        html += "<td>" + value['id'] + "</td>";
        html += "<td>" + format_str + "</td>";
        html += "<td>" + value['name'] + "</td>";
        html += "<td>" + manu_name + "</td>";
        html += "<td>";
            html += "<a href='../customer/" + value['customer_id'] + "' class='customer-anchor sex-" + value['sex'] + "' >";
            html += value['f_name'] + value['l_name'];
            html += "</a>";
        html += "</td>";
        html += "</tr>";
    });
    html += "</table>";

    return html;
}
