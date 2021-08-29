window.onload = function () {
     getMonthRecord()
};

/**
 * 前日の日付を求めてセットする
 */
$('#previousMonth').on('click', function() {
     var targetMonth = new Date($('#targetMonth').val());
     targetMonth.setMonth(targetMonth.getMonth() - 1);
     var year = targetMonth.getFullYear();
     var month = ("0"+(targetMonth.getMonth()+1)).slice(-2);
     var day = 21;
     var setDate = year + '-' + month + '-' + day
     $('#targetMonth').val(setDate);
     $('#targetMonth_string').html(year + '年' + month + '月度');
     getMonthRecord()
});

/**
 * 翌日の日付を求めてセットする
 */
$('#nextMonth').on('click', function() {
     var targetMonth = new Date($('#targetMonth').val());
     targetMonth.setMonth(targetMonth.getMonth() + 1);
     var year = targetMonth.getFullYear();
     var month = ("0"+(targetMonth.getMonth()+1)).slice(-2);
     var day = 21;
     var setDate = year + '-' + month + '-' + day
     $('#targetMonth').val(setDate);
     $('#targetMonth_string').html(year + '年' + month + '月度');
     getMonthRecord()
});

$('.shopChoice').on('click', function() {
     getMonthRecord()
});

$('.selectChoice').on('click', function() {
     getMonthRecord()
});

/**
 * 渡された日付からデータを取得する
 */
function getMonthRecord(){
     var targetMonth = $('#targetMonth').val();
     var shopChoice = $('input:radio[name="shopChoice"]:checked').val();
     var selectChoice = $('input:radio[name="selectChoice"]:checked').val();
     console.log(targetMonth, shopChoice, selectChoice);
     $.get({
          url:  "/oldReport/getMonthRecord",
          method: 'GET',
          dataType: 'json',
          data: {
               'targetMonth' : targetMonth,
               'shopChoice' : shopChoice,
               'selectChoice' : selectChoice,
          }
     }).done(function (data) { // ajaxが成功したときの処理
          if(data[0] == 'fail'){ // 結果が失敗したときの処理
               alert( data[1]['original'] )
          }else{
               if(selectChoice == 1){
                    displayArea(data)
               }else if(selectChoice == 2){
                    setMonthAndMenu(data)
               }else if(selectChoice == 3){
                    setMonthAndStylist(data)
               }
          }
     }).fail(function () { // ajax通信がエラーのときの処理
          console.log('更新失敗');
     })
}

function setMonthAndMenu(data){
     var html = '';
     var html = html + '<div >抽出期間 : ' + data['$fromMonth'] + ' ～ ' + data['$toMonth'] + '</div>';
     var html = html + '<div >メニュー別</div>';
     html = html + '<table class="tableClass_010" >';
          html = html + '<th>メニュー</th>';
          html = html + '<th>対応客数</th>';

          data['$visitHistory'].forEach(value => {
               html = html + '<tr>';
                    html = html + '<td>';
                         if(value['menu_name'] == null){
                              html = html + '未設定' ;
                         }else{
                              html = html + value['menu_name'] ;
                         }
                    html = html + '</td>';
                    html = html + '<td>' + value['numberOfVisitors'] + '名</td>';
               html = html + '</tr>';
          });
     html = html + '</table>';
     displayArea(html)
}

function setMonthAndStylist(data){
     // var html = '<div >会計別</div>';
     var html = '';
     var html = html + '<div >抽出期間 : ' + data['$fromMonth'] + ' ～ ' + data['$toMonth'] + '</div>';
     var html = html + '<div >スタイリスト別</div>';
     html = html + '<table class="tableClass_010" >';
          html = html + '<th>担当スタッフ</th>';
          html = html + '<th>対応客数</th>';
          html = html + '<th>S_指名</th>';
          html = html + '<th>SH_紹介</th>';
          html = html + '<th>K_交代</th>';
          html = html + '<th>F_フリー</th>';
          html = html + '<th>D_代理</th>';

          data['$visitHistory'].forEach(value => {
               html = html + '<tr>';
                    html = html + '<td>' + value['name'] + '</td>';
                    html = html + '<td>' + value['total_NINNZUU'] + '名</td>';
                    html = html + '<td>';
                         if(value['VHT1_NINNZUU']){
                              html = html + value['VHT1_NINNZUU'] + '名';
                         }else{
                              html = html + '-';
                         }
                    html = html + '</td>';
                    html = html + '<td>';
                         if(value['VHT2_NINNZUU']){
                              html = html + value['VHT2_NINNZUU'] + '名';
                         }else{
                              html = html + '-';
                         }
                    html = html + '</td>';
                    html = html + '<td>';
                         if(value['VHT3_NINNZUU']){
                              html = html + value['VHT3_NINNZUU'] + '名';
                         }else{
                              html = html + '-';
                         }
                    html = html + '</td>';
                    html = html + '<td>';
                         if(value['VHT4_NINNZUU']){
                              html = html + value['VHT4_NINNZUU'] + '名';
                         }else{
                              html = html + '-';
                         }
                    html = html + '</td>';
                    html = html + '<td>';
                         if(value['VHT5_NINNZUU']){
                              html = html + value['VHT5_NINNZUU'] + '名';
                         }else{
                              html = html + '-';
                         }
                    html = html + '</td>';

               html = html + '</tr>';
          });
     html = html + '</table>';
     displayArea(html)
}




function displayArea(data){
     document.getElementById('displayArea').innerHTML = data;
}







