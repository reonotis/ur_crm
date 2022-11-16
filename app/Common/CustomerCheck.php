<?php

namespace app\Common;

use App\Consts\Common;
use Illuminate\Http\Request;

class CustomerCheck
{
    public $errMsg = [];

    public function registerCheckValidation(Request $r): bool
    {
        if (empty($r->f_name)) $this->errMsg[] = '苗字は必須入力です';
        if (empty($r->l_name)) $this->errMsg[] = '名前は必須入力です';
        if (empty($r->f_read)){
            $this->errMsg[] = 'ミョウジは必須入力です';
        } elseif (!preg_match("/^[ァ-ヾ]+$/u", $r->f_read)){
                $this->errMsg[] = 'ミョウジは全角カタカナで入力してください';
        }
        if (empty($r->l_read)){
            $this->errMsg[] = 'ナマエは必須入力です';
        } elseif (!preg_match("/^[ァ-ヾ]+$/u", $r->l_read)){
            $this->errMsg[] = 'ナマエは全角カタカナで入力してください';
        }

        if (!empty($r->sex)){
            if(!array_key_exists($r->sex, Common::SEX_LIST)){
                $this->errMsg[] = '性別の値が不正です';
            }
        }

        if (!empty($r->birthday_year)){
            if(!(1900 <= $r->birthday_year) || !($r->birthday_year <= date('Y'))){
                $this->errMsg[] = '誕生年が不正です';
            }
        }
        if (!empty($r->birthday_month)){
            if(!(1 <= $r->birthday_month) || !($r->birthday_month <= 12)){
                $this->errMsg[] = '誕生月が不正です';
            }
        }
        if (!empty($r->birthday_day)){
            if(!(1 <= $r->birthday_day) || !($r->birthday_day <= 31)){
                $this->errMsg[] = '誕生日が不正です';
            }
        }
        if(!empty($r->birthday_year) && !empty($r->birthday_month) && !empty($r->birthday_day)){
            if(!checkdate($r->birthday_month, $r->birthday_day, $r->birthday_year)) {
                $this->errMsg[] = '誕生日が不正な年月日です';
            }
        }

        if (!empty($r->tel)){
            $pattern = Common::VALIDATE_TEL;
            if (!preg_match($pattern, $r->tel) ) {
                $this->errMsg[] = '電話番号はハイフンを含む半角数字で入力してください';
            }
        }
        if (!empty($r->email)){
            $pattern = Common::VALIDATE_EMAIL;
            if (!preg_match($pattern, $r->email) ) {
                $this->errMsg[] = 'メールアドレスの形式が不正です';
            }
        }
        if (!empty($r->zip21) || !empty($r->zip22)){
            if(!strlen($r->zip21) == 3  || !strlen($r->zip22) == 4){
                $this->errMsg[] = '郵便番号は3桁-4桁で入力してください';
            }
        }
        return false;
    }

    public function getErrMsg(): array
    {
        return $this->errMsg;
    }
}



