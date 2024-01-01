<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\FunctionAuth;
use Illuminate\Support\Facades\Auth;

class AuthHelpers
{
    /**
     * 対象機能の権限が登録されているか確認する
     * @param $functionName
     * @return bool
     */
    public static function checkHavePermissions($functionName)
    {
        $userId = Auth::user()->id;
        $functionAuth = FunctionAuth::where('user_id', $userId)->where('function_name', $functionName)->first();

        if ($functionAuth) {
            return true;
        }
        return false;
    }
}
