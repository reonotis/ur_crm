<?php

namespace App\Http\Controllers;

use App\Consts\SessionConst;
use App\Models\Shop;
use App\Models\UserShopAuthorization;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ShopSelectController extends Controller
{

    /**
     * コンストラクタ
     */
    public function __construct()
    {
    }

    /**
     * 作業店舗を選択しなおす画面に遷移する
     * @return RedirectResponse|View
     */
    public function deselect()
    {
        // セッションから選択可能店舗を削除
        session()->forget(SessionConst::SELECTABLE_SHOP);
        session()->forget(SessionConst::SELECTED_SHOP);

        // ユーザーに紐づくショップを取得する
        $selectableShops = UserShopAuthorization::getShopByUserId(Auth::user()->id)->get();

        if(count($selectableShops) == 0){
            return redirect()->route('setting.index')->with(SessionConst::FLASH_MESSAGE_ERROR, ['操作できる店舗がありません']);
        }

        if(count($selectableShops) == 1){
            return redirect()->route('home')->with(SessionConst::FLASH_MESSAGE_ERROR, ['操作できる店舗は1店舗しかありません']);
        }

        return view('shopSelect.index', compact('selectableShops'));
    }

    /**
     * 作業する店舗を選択してマイページに移動する
     * @param Shop $shop
     * @return RedirectResponse
     */
    public function selected(Shop $shop): RedirectResponse
    {
        // 選択可能な店舗かチェック
        if(!$this->_checkMyShop($shop)){
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['操作できない店舗を指定しています'])->withInput();
        }
        return redirect()->route('home')->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['操作店舗を選択しました'])->withInput();
    }

    /**
     * 指定された店舗が、ログインユーザーが選択して良い店舗であればセッションに格納する
     * @param Shop $shop
     * @return bool
     */
    private function _checkMyShop(Shop $shop): bool
    {
        // 操作可能な店舗を取得
        $selectableShops = Auth::user()->userShopAuthorizations()->get();
        foreach($selectableShops AS $userShopAuthorization){
            // 操作可能な店舗であれば、セッションに格納してリターンする
            if($userShopAuthorization->shop_id == $shop->id){
                session()->put(SessionConst::SELECTED_SHOP, $shop);
                return true;
            }
        }
        return false;
    }

}
