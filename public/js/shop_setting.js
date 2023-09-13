$(function () {
    displayBusinessHourTypeEditView();
    $('[name="business_hour_type"]:radio').change(function () {
        displayBusinessHourTypeEditView();
    });
});


function displayBusinessHourTypeEditView() {
    let radio_value = $('[name="business_hour_type"]:radio:checked').val();
    $(".business-hour-edit-content").each(function () {
        if ($(this).hasClass('type-' + radio_value)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

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
