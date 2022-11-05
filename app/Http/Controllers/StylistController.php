<?php

namespace App\Http\Controllers;

// use App\Models\CustomerCourseMapping;
use App\Models\Shop;
use App\Services\CheckData;
use App\UserOld;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StylistController extends Controller
{
    private $_user;                 //Auth::user()

    public function __construct(){
        $this->middleware(function ($request, $next) {
            $this->_user = \Auth::user();
            // $this->_auth_authority_id = $this->_user->authority_id;
            // if($this->_auth_authority_id >= 8){
            //     session()->flash('msg_danger', '権限がありません');
            //     Auth::logout();
            //     return redirect()->intended('/');
            // }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = UserOld::select('users.*', 'shops.shop_name')
        ->where('authority_id', '>=', 2)
        ->where('authority_id', '<=', 7)
        ->join('shops', 'shops.id', '=', 'users.shop_id' )
        ->get();
        $users = CheckData::set_authority_names($users);

        $shops = Shop::get();
        $defaultShopId = $this->_user->shop_id;

        return view('stylist.index', compact('users', 'shops', 'defaultShopId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = UserOld::select('users.*', 'shops.shop_name')
        ->where('authority_id', '>=', 2)
        ->where('authority_id', '<=', 7)
        ->join('shops', 'shops.id', '=', 'users.shop_id' )
        ->first();
        $authorityList = config('ur.authorityList');
        $shops = Shop::get();
        return view('stylist.create', compact('user', 'authorityList', 'shops'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->store_validate($request);
        try {
            DB::beginTransaction();

            // TODO 店長が既に登録されている場合はエラーにする

            UserOld::insert([[
                'name'         => $request->name,
                'email'        => $request->email,
                'shop_id'      => $request->shop_id,
                'password'     => Hash::make($request->email),
                'authority_id' => $request->authority_id,
            ]]);
            $user_id = DB::getPdo()->lastInsertId();

            DB::commit();
            session()->flash('msg_success', 'スタイリストを登録しました。');
            return redirect()->action('StylistController@show',['id'=>$user_id]);
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back()->withInput();    // 前の画面へ戻る
        }
    }

    /**
     * 顧客登録時のバリデーションチェック
     */
    public function store_validate($request)
    {
        $request->validate(
            [
                'name' => 'required',
                'email' => 'unique:users,email|regex:/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+$/',
                'shop_id' => 'required',
                'authority_id' => 'required',
            ],[
                // 'zip21.size' => '郵便番号は 3桁 - 4桁 で入力してください',
                // 'zip22.size' => '郵便番号は 3桁 - 4桁 で入力してください',
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = UserOld::select('users.*', 'shops.shop_name')
        ->join('shops', 'shops.id', '=', 'users.shop_id')
        ->find($id);
        $user = CheckData::set_authority_name($user);

        return view('stylist.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = UserOld::find($id);
        $authorityList = config('ur.authorityList');
        $shops = Shop::get();

        return view('stylist.edit', compact('user', 'authorityList', 'shops' ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $user = UserOld::find($id);
            $user->name  = $request->name;
            $user->email = $request->email;
            $user->shop_id = $request->shop_id;
            $user->authority_id = $request->authority_id;
            $user->save();


            // throw new \Exception("強制終了");
            DB::commit();
            session()->flash('msg_success', 'スタイリストを設定しました。');
            return redirect()->action('StylistController@show', ['id' => $id]);
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
