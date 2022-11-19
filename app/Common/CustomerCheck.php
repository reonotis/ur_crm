<?php

namespace app\Common;

use App\Consts\Common;
use App\Models\CustomerNoCounter;
use App\Models\Shop;
use Illuminate\Http\Request;

class CustomerCheck
{
    public $errMsg = [];

    /**
     * @return array
     */
    public function getErrMsg(): array
    {
        return $this->errMsg;
    }

    /**
     * @param Request $r
     * @return void
     */
    public function registerCheckValidation(Request $r): void
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
            if (!preg_match(Common::VALIDATE_TEL, $r->tel) ) {
                $this->errMsg[] = '電話番号はハイフンを含む半角数字で入力してください';
            }
        }
        if (!empty($r->email)){
            if (!preg_match(Common::VALIDATE_EMAIL, $r->email) ) {
                $this->errMsg[] = 'メールアドレスの形式が不正です';
            }
        }
        if (!empty($r->zip21) || !empty($r->zip22)){
            if(!strlen($r->zip21) == 3  || !strlen($r->zip22) == 4){
                $this->errMsg[] = '郵便番号は3桁-4桁で入力してください';
            }
        }
    }

    /**
     * ショップシンボルを先頭にした顧客番号を生成する
     * 数次は6桁
     * 例) CA999999
     * @param int $shopId
     * @return string
     */
    public function _makeCustomerNo(int $shopId): string
    {
        $lastId = CustomerNoCounter::select('id')->latest('id')->first();
        $newId = $lastId->id + 1;
        $customerNoCounter = CustomerNoCounter::create([
            'id' => $newId,
        ]);

        $shop = Shop::find($shopId);
        return $shop->shop_symbol . str_pad($customerNoCounter->id, Common::CUSTOMER_NO_LENGTH, 0, STR_PAD_LEFT);
    }

}



