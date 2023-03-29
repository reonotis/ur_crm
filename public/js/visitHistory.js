/**
 * 画像選択を変更したとき
 */
$('.img-file').on('change', function () {
    var file = $(this).prop('files')[0];
    var imgInputId = $(this).attr('id')
    var imgParagraphId = imgInputId + '-paragraph'

    if (file === undefined) {
        $('#' + imgParagraphId).text('選択されていません');
    } else {
        $('#' + imgParagraphId).text(file.name);
    }
});


/**
 * 画像削除ボタンを押したとき
 */
$('.img-delete').on('change', function () {
    var deleteId = $(this).attr('id');
    var idStringTemp = deleteId.substring( 8 );
    var deleteNum = idStringTemp.replace('-delete', '');

    var isCheck = $(this).prop('checked');
    if (isCheck) {
        // 画像をグレーアウトする
        $('#img' + deleteNum).fadeTo(300, 0.01);
    } else {
        // 画像をグレーアウトを解除
        $('#img' + deleteNum).fadeTo(300, 1);
    }
});
