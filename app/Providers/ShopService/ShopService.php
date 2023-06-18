<?php

namespace App\Providers\ShopService;

use Illuminate\Support\Facades\Log;
use App\Models\{Shop, UserShopAuthorization};
use App\Consts\SessionConst;
use Exception;


/**
 * スタッフでログインしている場合に、ショップを選択させたい
 *
 */
class ShopService
{

    /**
     * ショップを選択させるか確認する
     * @param int $loginUserId
     * @return bool
     */
    public function shopCheck(int $loginUserId): bool
    {
        // 選択できる店舗を全て取得
//        $selectableShops = UserShopAuthorization::getShopByUserId($loginUserId);
        $selectableShops = Shop::getMyShop($loginUserId);
        if (count($selectableShops) == 0) { // 選択できる店舗がない場合、エラー処理
            session()->flash(SessionConst::FLASH_MESSAGE_ERROR, ['どの店舗も閲覧する権限がありません']);
            return false;
        }

        if (count($selectableShops) == 1) { // 1店舗しかない場合、セッションに格納する
            session()->forget(SessionConst::SELECTABLE_SHOP);
            session()->put(SessionConst::SELECTED_SHOP, $selectableShops[0]);
            return true;
        }

        // 複数選択できる場合
        session()->put(SessionConst::SELECTABLE_SHOP, $selectableShops);
        // 既にショップを選択している場合
        if (session()->get(SessionConst::SELECTED_SHOP)) {
            // TODO 選択して良いショップなのか確認する手順を追加したい
            return true;
        }
        return false;
    }


}
