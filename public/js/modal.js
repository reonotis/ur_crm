/**
 * モーダルのクローズボタンを押したときにモーダルを非表示にする
 */
$('#modalClose').on('click', function() {
     $("#modalBackground").slideToggle("fast");
});

/**
 * モーダルの背景を押したときはモーダルを非表示にする
 */
$('#modalBackground').on('click',function(e) {
     if(!$(e.target).closest('.modalContent').length) {
     $("#modalBackground").slideToggle("fast");
     }
});

function modalPopUp(visitHistories, id){
     visitHistories.forEach(function(visitHistory){
          if(visitHistory['id'] === id){
               $('#modalTextArea').html($('<dummy>').text(visitHistory['memo']).html().replace(/\n/g, '<br>'));
          }
     });
     $("#modalBackground").slideToggle("fast");
}