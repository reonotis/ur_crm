window.onload = function () {
     getDayRecord()
};
/**
 * 前日の日付を求めてセットする
 */
$('#previousDay').on('click', function() {
     var date = new Date($('#selectedDay').val());
     date.setDate(date.getDate() - 1);
     var year = date.getFullYear();
     var month = ("0"+(date.getMonth()+1)).slice(-2);
     var day = ("0"+date.getDate()).slice(-2);
     var setDate = year + '-' + month + '-' + day
     $('#selectedDay').val(setDate);
     getDayRecord()
});

/**
 * 翌日の日付を求めてセットする
 */
$('#nextDay').on('click', function() {
     var date = new Date($('#selectedDay').val());
     date.setDate(date.getDate() + 1);
     var year = date.getFullYear();
     var month = ("0"+(date.getMonth()+1)).slice(-2);
     var day = ("0"+date.getDate()).slice(-2);
     var setDate = year + '-' + month + '-' + day
     $('#selectedDay').val(setDate);
     getDayRecord()
});

$('#today').on('click', function() {
     var today = new Date();
     var year = today.getFullYear();
     var month = ("0"+(today.getMonth()+1)).slice(-2);
     var day = ("0"+today.getDate()).slice(-2);
     var setDate = year + '-' + month + '-' + day
     $('#selectedDay').val(setDate);
     getDayRecord()
});



/**
 * 日付を変更した場合
 */
$('#selectedDay').on('change', function() {
     getDayRecord()
});

$('.shopChoice').on('click', function() {
     getDayRecord()
});

$('.selectChoice').on('click', function() {
     getDayRecord()
});

/**
 * 渡された日付からデータを取得する
 */
function getDayRecord(){
     var setDate = $('#selectedDay').val();
     var shopChoice = $('input:radio[name="shopChoice"]:checked').val();
     var selectChoice = $('input:radio[name="selectChoice"]:checked').val();

     $.get({
          url: LOCAL_ENVIRONMENT + "oldReport/getDayRecord",
          method: 'GET',
          dataType: 'json',
          data: {
               'setDate' : setDate,
               'shopChoice' : shopChoice,
               'selectChoice' : selectChoice,
          }
     }).done(function (data) { // ajaxが成功したときの処理
          if(data[0] == 'fail'){ // 結果が失敗したときの処理
               alert( data[1]['original'] )
          }else{
               if(selectChoice == 1){
                    setPayment(data)
               }else if(selectChoice == 2){
                    setMenu(data)
               }else if(selectChoice == 3){
                    setStylist(data)
               }
          }
     }).fail(function () { // ajax通信がエラーのときの処理
          console.log('更新失敗');
     })
}

function setPayment(data){
     // var html = '<div >会計別</div>';
     var html = '';
     var html = html + '<div >対象日 : ' + data['setDate'] + '</div>';
     var html = html + '<div >店舗 : ' + data['shop_name'] + '</div>';
     var html = html + '<div >表示方法 : 会計別</div>';
     html = html + '<table class="tableClass_010" >';
          html = html + '<th>来店時間</th>';
          html = html + '<th>担当スタッフ</th>';
          html = html + '<th>顧客名</th>';
          html = html + '<th>来店種別</th>';
          html = html + '<th>メニュー</th>';

          data['visitHistory'].forEach(value => {
               html = html + '<tr>';
                    html = html + '<td>' + value['vis_time'] + '</td>';
                    html = html + '<td>' + value['name'] + '</td>';
                    html = html + '<td>';
                         html = html + '<a href="../customer/show/' + value["customer_id"] + '">';
                              html = html + value['f_name'] + ' ' + value['l_name'];
                         html = html + '</a>';
                    html = html + '</td>';
                    html = html + '<td>';
                         if(value['type_name']){
                              html = html + value['type_name'] ;
                         }else{
                              html = html + '-' ;
                         }
                    html = html + '</td>';
                    html = html + '<td>';
                         if(value['menu_name']){
                              html = html + value['menu_name'] ;
                         }else{
                              html = html + '-' ;
                         }
                    html = html + '</td>';
               html = html + '</tr>';
          });
     html = html + '</table>';
     displayArea(html)
}

function setMenu(data){
     var html = '';
     var html = html + '<div >対象日 : ' + data['setDate'] + '</div>';
     var html = html + '<div >店舗 : ' + data['shop_name'] + '</div>';
     var html = html + '<div >表示方法 : メニュー別</div>';
     html = html + '<table class="tableClass_010" >';
          html = html + '<tr>';
               html = html + '<th>担当スタッフ</th>';
               html = html + '<th>人数</th>';
          html = html + '</tr>';

          data['visitHistory'].forEach(value => {
               html = html + '<tr>';
                    html = html + '<td>' + value['menu_name'] + '</td>';
                    html = html + '<td>' + value['numberOfVisitors'] + '名</td>';
               html = html + '</tr>';
          });
     html = html + '</table>';
     displayArea(html)
}

function setStylist(data){
     var html = '';
     var html = html + '<div >対象日 : ' + data['setDate'] + '</div>';
     var html = html + '<div >店舗 : ' + data['shop_name'] + '</div>';
     var html = html + '<div >表示方法 : 会計別</div>';
     html = html + '<table class="tableClass_010" >';
          html = html + '<th>担当スタッフ</th>';
          html = html + '<th>対応客数</th>';
          html = html + '<th>S_指名</th>';
          html = html + '<th>SH_紹介</th>';
          html = html + '<th>K_交代</th>';
          html = html + '<th>F_フリー</th>';
          html = html + '<th>D_代理</th>';

          data['visitHistory'].forEach(value => {
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







