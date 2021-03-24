<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CheckCouses
{

    /**
    * コースの承認状態を確認して承認名を付与する
    */
    public static function setApprovalName($data){
        switch ($data->approval_flg) {
            case '0':
                $data->approval_name = '申請中';
                break;
            case '1':
                $data->approval_name = '差し戻し';
                break;
            case '2':
                $data->approval_name = '申請中';
                break;
            case '5':
                $data->approval_name = '受理済み';
                break;
            default:
                $data->approval_name = '--';
                break;
        }
        return $data;
    }


}