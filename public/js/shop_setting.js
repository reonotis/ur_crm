function check(id) {
    // 設定値を取得
    let input_business_open_time = $('#business_open_time_' + id);
    let input_last_reception_time = $('#last_reception_time_' + id);
    let input_business_close_time = $('#business_close_time_' + id);
    let input_setting_start_date = $('#setting_start_date_' + id);

    // 取得した値をセット
    let formId = '#form_' + id;
    $(formId + ' [name=business_open_time_' + id + ']').val(input_business_open_time.val()); // 営業開始時間
    $(formId + ' [name=last_reception_time_' + id + ']').val(input_last_reception_time.val()); // 最終受付時間
    $(formId + ' [name=business_close_time_' + id + ']').val(input_business_close_time.val()); // 営業終了時間
    $(formId + ' [name=setting_start_date_' + id + ']').val(input_setting_start_date.val()); // 適用開始日

    return true;
}

$(function () {
    checkRegularHoliday();
    $('[name="regular_holiday"]').change(function () {
        checkRegularHoliday();
    });
});

function checkRegularHoliday() {
    if ($("#regular_holiday").prop("checked")) {
        $("#business_open_time").prop('disabled', true)
        $("#last_reception_time").prop('disabled', true)
        $("#business_close_time").prop('disabled', true)
    } else {
        $("#business_open_time").prop('disabled', false)
        $("#last_reception_time").prop('disabled', false)
        $("#business_close_time").prop('disabled', false)
    }
}
