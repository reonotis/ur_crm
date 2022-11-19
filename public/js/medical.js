
$("#f_name").change(function() {
    checkFName();
});

$("#l_name").change(function() {
    checkLName();
});

$("#f_read").change(function() {
    checkFRead();
});

$("#l_read").change(function() {
    checkLRead();
});

$("#birthday_year").change(function() {
    checkBirthDay();
});

$("#birthday_month").change(function() {
    checkBirthDay();
});

$("#birthday_day").change(function() {
    checkBirthDay();
});

$("#tel").change(function() {
    checkTel();
});

$("#email").change(function() {
    checkEMail();
});

$("#zip21").change(function () {
    checkZip21();
});

$("#zip22").change(function () {
    checkZip22();
});

function checkValidate(){
    var ret = 0
    ret = ret + checkFName();
    ret = ret + checkLName();
    ret = ret + checkFRead();
    ret = ret + checkLRead();
    ret = ret + checkBirthDay();
    ret = ret + checkTel();
    ret = ret + checkEMail();

    if(ret){
        return false
    }
    return window.confirm('この内容で送信しますがよろしいでしょうか？');
}

function checkFName(){
    const f_name = $("#f_name");
    if (f_name.val() === '') {
        f_name.addClass("input-error");
        $("#f_name_error_message").text("苗字は必須入力です");
        return 1;
    }
    f_name.removeClass("input-error");
    $("#f_name_error_message").text("");
    return 0;
}

function checkLName(){
    const l_name = $("#l_name");
    if (l_name.val() === '') {
        l_name.addClass("input-error");
        $("#l_name_error_message").text("名前は必須入力です");
        return 1;
    }
    l_name.removeClass("input-error");
    $("#l_name_error_message").text("");
    return 0;
}

function checkFRead(){
    const f_read = $("#f_read");
    if (f_read.val() === '') {
        f_read.addClass("input-error");
        $("#f_read_error_message").text("ミョウジは必須入力です");
        return 1;
    }
    if (!f_read.val().match(/^[ァ-ヶー　]+$/)) {
        f_read.addClass("input-error");
        $("#f_read_error_message").text("ミョウジは全角カタカナで入力してください");
        return 1;
    }
    f_read.removeClass("input-error");
    $("#f_read_error_message").text("");
    return 0;
}

function checkLRead(){
    const l_read = $("#l_read");
    const l_read_error_message = $("#l_read_error_message");
    if (l_read.val() === '') {
        l_read.addClass("input-error");
        l_read_error_message.text("ナマエは必須入力です");
        return 1;
    }
    if (!l_read.val().match(/^[ァ-ヶー　]+$/)) {
        l_read.addClass("input-error");
        l_read_error_message.text("ナマエは全角カタカナで入力してください");
        return 1;
    }
    l_read.removeClass("input-error");
    l_read_error_message.text("");
    return 0;
}

function checkBirthDay(){
    var ret = 0
    ret = ret + checkBirthDayYear();
    ret = ret + checkBirthDayMonth();
    ret = ret + checkBirthDayDay();
    if(ret){
        return ret
    }

    // 年月日が全て入っている場合は存在する日付かチェックする
    if ($("#birthday_year").val() !== '' &&  $("#birthday_month").val() !== '' && $("#birthday_day").val() !== '') {
        const birthday = $("#birthday_year").val() + "-" + $("#birthday_month").val() + "-" + $("#birthday_day").val();
        let date = new Date(birthday)
        if(date.getDate() != $("#birthday_day").val()){
            $("#birthday_year").addClass("input-error");
            $("#birthday_month").addClass("input-error");
            $("#birthday_day").addClass("input-error");
            $("#birthday_year_error_message").text("存在しない年月日です");
            return 1;
        }
        $("#birthday_year").removeClass("input-error");
        $("#birthday_month").removeClass("input-error");
        $("#birthday_day").removeClass("input-error");
        return 0;
    }
    return 0;
}

function checkBirthDayYear(){
    const birthday_year = $("#birthday_year");
    if (birthday_year.val() !== '') {
        if (1900 <= birthday_year.val() && birthday_year.val() < 2022) {
            birthday_year.removeClass("input-error");
            $("#birthday_year_error_message").text("");
            return 0;
        }
        birthday_year.addClass("input-error");
        $("#birthday_year_error_message").text("年の値が不正です");
        return 1;
    }
    birthday_year.removeClass("input-error");
    $("#birthday_year_error_message").text("");
    return 0;
}

function checkBirthDayMonth(){
    const birthday_month = $("#birthday_month");
    if (birthday_month.val() !== '') {
        if (1 <= birthday_month.val() && birthday_month.val() <= 12) {
            birthday_month.removeClass("input-error");
            $("#birthday_month_error_message").text("");
            return 0;
        }
        birthday_month.addClass("input-error");
        $("#birthday_month_error_message").text("月の値が不正です");
        return 1;
    }
    birthday_month.removeClass("input-error");
    $("#birthday_month_error_message").text("");
    return 0;
}

function checkBirthDayDay(){
    const birthday_day = $("#birthday_day");
    if (birthday_day.val() !== '') {
        if (1 <= birthday_day.val() && birthday_day.val() <= 31) {
            birthday_day.removeClass("input-error");
            $("#birthday_day_error_message").text("");
            return 0;
        }
        birthday_day.addClass("input-error");
        $("#birthday_day_error_message").text("日付の値が不正です");
        return 1;
    }
    birthday_day.removeClass("input-error");
    $("#birthday_day_error_message").text("");
    return 0;
}

function checkTel(){
    const regexp = /^0\d{1,3}-\d{2,4}-\d{3,4}$/;
    const tel = $("#tel");

    if (tel.val() !== '' && !regexp.test(tel.val())) {
        tel.addClass("input-error");
        $("#tel_error_message").text("電話番号はハイフン(-)を含む半角数字で入力してください");
        return 1;
    }
    tel.removeClass("input-error");
    $("#tel_error_message").text("");
    return 0;
}

function checkEMail(){
    const email_regexp = /^[A-Za-z\d]{1}[A-Za-z\d_.-]*@{1}[A-Za-z\d_.-]+.[A-Za-z\d]+$/;
    const email = $("#email");

    if (email.val() !== '' && !email_regexp.test(email.val())) {
        email.addClass("input-error");
        $("#email_error_message").text("正しいメールアドレスを入力してください");
        return 1;
    }
    email.removeClass("input-error");
    $("#email_error_message").text("");
    return 0;
}

function checkZip21(){
    const zip21 = $("#zip21");
    if (zip21.val() !== '' && zip21.val().length !== 3){
        zip21.addClass("input-error");
        $("#zip21_error_message").text("郵便番号の先頭は3桁で入力して下さい");
        return 1;
    }
    zip21.removeClass("input-error");
    $("#zip21_error_message").text("");
    return 0;
}

function checkZip22(){
    const zip22 = $("#zip22");
    if (zip22.val() !== '' && zip22.val().length !== 4){
        zip22.addClass("input-error");
        $("#zip22_error_message").text("郵便番号の末尾は4桁で入力して下さい");
        return 1;
    }
    zip22.removeClass("input-error");
    $("#zip22_error_message").text("");
    return 0;
}





















