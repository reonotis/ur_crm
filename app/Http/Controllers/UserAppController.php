<?php

namespace App\Http\Controllers;

use App\Consts\Common;
use App\Consts\ErrorCode;
use App\Consts\SessionConst;
use App\Exceptions\ExclusionException;
use App\Exceptions\ForbiddenException;
use App\Models\{User, UserShopAuthorization};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Route;

/**
 *
 */
class UserAppController extends Controller
{

    /**
     * @var User $loginUser 現在ログインしているユーザー
     * @var int $shopId 現在選択している店舗のID
     * @var UserShopAuthorization $userShopAuthorization ログインユーザーが選択している店舗の権限
     */
    public $loginUser;
    public $shopId;
    public $userShopAuthorization;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->loginUser = Auth::user();

            // 退職している場合
            if ($this->loginUser->authority_level == Common::AUTHORITY_RETIREMENT) {
                throw new ExclusionException(ErrorCode::CL_010001);
            }

            // 操作できるショップがあるかチェックする
            $shopService = app()->make('ShopService');
            if (!$shopService->shopCheck($this->loginUser->id)) {
                Redirect::route('shop.deselect')->send();
                return $next($request);
            }
            $shop = session()->get(SessionConst::SELECTED_SHOP);
            $this->shopId = $shop->id;
            $this->userShopAuthorization = $this->loginUser->userShopAuthorization;

            // 権限がない操作を実行していないかチェックをする
            $this->routeAuthCheck();

            return $next($request);
        });
    }

    /**
     * ルーティングに対してログインユーザーの権限があるか確認する
     * @return void
     * @throws ForbiddenException
     */
    public function routeAuthCheck(): void
    {
        $routeName = Route::currentRouteName();

        // 権限管理されているルーティングでなければチェックしない
        if (empty(Common::ROUTE_AUTH_LIST[$routeName])) {
            return;
        }

        // ルーティングに対する権限があればチェック完了
        $authName = Common::ROUTE_AUTH_LIST[$routeName];
        if (empty($this->userShopAuthorization)) {
            Redirect::route('myPage')->send();
        }
        Log::info($this->userShopAuthorization);
        if ($this->userShopAuthorization->{$authName}) {
            return;
        }

        Log::error('不正なアクセスを行おうとしています。 route_name:' . $routeName . ', login_user_id:' . $this->loginUser->id);
        throw new ForbiddenException($authName);
    }

    /**
     * 対象コードのエラーログを出力し、例外処理を行う
     * @param string $errCode
     * @param array $values
     * @return void
     * @throws ExclusionException
     */
    public function goToExclusionErrorPage(string $errCode, array $values): void
    {
        $errLogFmt = ErrorCode::ERROR_LOG_LIST[$errCode];
        $errLog = vsprintf($errLogFmt, $values);
        Log::error($errLog);

        throw new ExclusionException($errCode);
    }

}
