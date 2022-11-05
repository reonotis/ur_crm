<?php

namespace App\Http\Controllers;

use App\Consts\Common;
use App\Consts\SessionConst;
use App\Consts\DatabaseConst;
use App\Consts\ErrorCode;
use App\Models\{User};
use App\Models\NoticesStatus;
use App\Models\UserShopAuthorization;
use App\Models\VisitHistory;
use App\Models\VisitHistoryImage;
use App\Models\Shop;
use App\Services\CheckData;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ErrorController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
    }

    /**
     * 除外エラー発生時にエラー画面を表示する
     * @param string $errorCode
     * @return View
     */
    public function exclusionError(string $errorCode = ''): view
    {
        if(empty($errorCode)){
            $errorCode =  ErrorCode::DEFAULT;
        }
        if (!array_key_exists($errorCode, ErrorCode::ERROR_MESSAGE_LIST)){
            $errorCode =  ErrorCode::DEFAULT;
        }

        $errorMessage = ErrorCode::ERROR_MESSAGE_LIST[$errorCode];

        $errorData = [
            'errorCode' => $errorCode,
            'errorMessage' => $errorMessage,
        ];

        return view('error.exclusion', compact('errorData'));
    }

    /**
     * 禁止操作発生時にエラー画面を表示する
     * @param string $errorCode
     * @return View
     */
    public function forbiddenError(string $authName = ''): view
    {
        if(empty($authName)){
            $authName = 'default';
        }

        if (!array_key_exists($authName, ErrorCode::ROUTE_AUTH_ERROR_MSG)){
            $authName = 'default';
        }

        $errorMessage = ErrorCode::ROUTE_AUTH_ERROR_MSG[$authName];

        return view('error.forbidden', compact('errorMessage'));
    }



}
