var error_count = 0;
var errMSG = "";

function registerConfilm(){
    error_count = 0;
    errMSG = "";

    try {
        if( f_name.value== '' ){  // 日付か時間が入力されていなかったら
            errMSG = errMSG  + "・苗字は必須入力です。\n"
            error_count ++ ;
        }
        if( l_name.value== '' ){  // 日付か時間が入力されていなかったら
            errMSG = errMSG  + "・名前は必須入力です。\n"
            error_count ++ ;
        }
        if( f_read.value== '' ){  // 日付か時間が入力されていなかったら
            errMSG = errMSG  + "・ミョウジは必須入力です。\n"
            error_count ++ ;
        }
        if( l_read.value== '' ){  // 日付か時間が入力されていなかったら
            errMSG = errMSG  + "・ナマエは必須入力です。\n"
            error_count ++ ;
        }
        validation_email();

        if(error_count >= 1){
            throw new Error(errMSG + '\n上記' + error_count + 'つの異常があります');
        }
    } catch (e) {
        window.alert(e.message);
        return false;
    }

    return true;
}
function validation_email(){
    if( email.value== '' ){  // 日付か時間が入力されていなかったら
        errMSG = errMSG  + "・メールアドレスは必須入力です。\n"
        error_count ++ ;
    }
    return error_count

}