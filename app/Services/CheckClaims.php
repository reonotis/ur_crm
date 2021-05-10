<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CheckClaims
{

    /**
     * 請求情報のステータスを確認して日本語のステータスをセットする
     */
    public static function setStatuses($claims){
        foreach($claims as $claim){
            $claim = self::setStatus($claim);
        }
        return $claims;
    }

    /**
     * 請求情報のステータスを確認して日本語のステータスをセットする
     */
    public static function setStatus($claim){
        switch ($claim->status){
            case 0:
                $claim->statusName = "未請求";
                break;
            case 1:
                $claim->statusName = "請求中";
                break;
            case 3:
                $claim->statusName = "キャンセル";
                break;
            case 5:
                $claim->statusName = "支払い済み";
                break;
            default:
                // 今のところ処理はありません
        }

        if($claim->claim_date <> '0000-00-00' ){
            $claim->claim_date = date('Y年 m月 d日' , strtotime($claim->claim_date));
        }else{
            $claim->claim_date = '-';
        }
        if($claim->complete_date == '0000-00-00' || $claim->complete_date == NULL ){
            $claim->complete_paidDate = '-';
        }else{
            $claim->complete_paidDate = date('Y年 m月 d日' , strtotime($claim->complete_date));
        }
        return $claim;
    }


}