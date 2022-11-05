<?php

namespace App\Http\Controllers;

use App\Consts\SessionConst;
use App\Models\User;
use App\Models\Shop;
use App\Models\UserShopAuthorization;
use App\Models\Customer;
use App\Services\CheckData;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopSelectController extends Controller
{
    private $_auth_id ;             //Auth::user()->id;
    private $_auth_authority_id ;   //権限

    /**
     * コンストラクタ
     */
    public function __construct()
    {
    }

    /**
     */
    public function deselect()
    {
        session()->forget(SessionConst::SELECTABLE_SHOP);
        session()->forget(SessionConst::SELECTED_SHOP);

        $selectableShops = UserShopAuthorization::getShopByUserId(Auth::user()->id);

        if(count($selectableShops) == 0){
            dd('選択できる店舗がありません');
        }

        if(count($selectableShops) == 1){
            dd('1店舗しか選択できないためリダイレクトします');
        }

        return view('shopSelect.index', compact('selectableShops'));
    }

    /**
     */
    public function selected(int $id)
    {
        // 選択して良い店舗かチェック
        $selectableShops = Auth::user()->userShopAuthorizations()->get();
        $check = false;
        foreach($selectableShops AS $userShopAuthorization ){
            if($userShopAuthorization->shop_id == $id){
                $shop = Shop::find($id);
                session()->put(SessionConst::SELECTED_SHOP, $shop);
                $check = true;
                break;
            }
        }
        if(!$check){
            dd('選択できない店舗です');
        }

        return redirect()->route('myPage',)->with('flash-message-success', ['選択しました'])->withInput();
    }
}
