<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\VisitHistory;
use App\Models\VisitHistoryImage;
use App\Models\Shop;
use App\Services\CheckData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends UserAppController
{
    public $errMsg = [];

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display the search screen.
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        $shops = Shop::get_shopList();
        $users = UserOld::get_userList();

        return view('customer.search',compact('shops', 'users'));
    }

    /**
     * 検索条件から結果を表示する
     *
     * @param Request $request
     * @return void
     */
    public function searching(Request $request){

        $query = Customer::select('customers.*', 'shops.shop_name', 'users.name')
        ->join('shops', 'shops.id', 'shop_id')
        ->leftJoin('users', 'customers.staff_id', '=', 'users.id');

        // 顧客番号の条件を設定する
        $this->setQueryLike($query, $request->input('member_number'), 'member_number');
        // 苗字の条件を設定する
        $this->setQueryLike($query, $request->input('f_name'), 'f_name');
        // 名前の条件を設定する
        $this->setQueryLike($query, $request->input('l_name'), 'l_name');
        // ミョウジの条件を設定する
        $this->setQueryLike($query, $request->input('l_read'), 'l_read');
        // ナマエの条件を設定する
        $this->setQueryLike($query, $request->input('l_read'), 'l_read');
        // 店舗の条件を設定する
        if(!empty($_GET['shop_id'])){
            $query->where('customers.shop_id', $_GET['shop_id']);
        }
        // 担当者の条件を設定する
        if(!empty($_GET['staff_id'])){
            $query->where('customers.staff_id', $_GET['staff_id']);
        }
        // 電話番号の条件を設定する
        $this->setQueryLike($query, $request->input('tel'), 'tel');
        // emailの条件を設定する
        $this->setQueryLike($query, $request->input('email'), 'email');
        // 生年月日の条件を設定する
        $this->setQueryLike($query, $request->input('birthday_year'), 'birthday_year');
        $this->setQueryLike($query, $request->input('birthday_month'), 'birthday_month');
        $this->setQueryLike($query, $request->input('birthday_day'), 'birthday_day');
        // 住所の条件を設定する
        $this->setQueryLike($query, $request->input('zip21'), 'zip21');
        $this->setQueryLike($query, $request->input('zip22'), 'zip22');
        $this->setQueryLike($query, $request->input('pref21'), 'pref21');
        $this->setQueryLike($query, $request->input('addr21'), 'addr21');
        $this->setQueryLike($query, $request->input('strt21'), 'strt21');
        // 非表示の顧客を表示するか設定する
        if( !$request->input('hidden_flag')) $query -> where('customers.hidden_flag','=','0');


        $query -> orderby('customers.id','asc');
        $customers = $query -> paginate(20);
        return view('customer.list', compact('customers'));
    }

    /**
     * 渡されたqueryにwhere句を追加する
     */
    public function setQueryLike($query, $data, $name){
        if($data !== null){
            $data_split = mb_convert_kana($data, 's');    //全角スペースを半角にする
            $data_split2 = preg_split('/[\s]+/', $data_split, -1, PREG_SPLIT_NO_EMPTY);    //半角スペースで区切る
            foreach($data_split2 as $value){
                $query -> where('customers.'.$name ,'like','%'.$value.'%');
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if($this->_auth_authority_id >= 5){
            $shopsId = $this->_user->shop_id;
        }else{
            $shopsId = NULL;
        }
        $shops = Shop::get_shopList($shopsId);
        $users = UserOld::get_userList($shopsId);
        return view('customer.create',compact('shops', 'users' ));
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
            $member_number = $this->store_validate2($request);
            // dd($member_number);
            DB::beginTransaction();
            Customer::insert([[
                'TOKKAI_shop' => $member_number['TOKKAI_shop'],
                'TOKKAI_no'   => $member_number['TOKKAI_no'],
                'member_number' => $member_number['member_number'],
                'f_name'      => $request->f_name,
                'l_name'      => $request->l_name,
                'f_read'      => $request->f_read,
                'l_read'      => $request->l_read,
                'sex'         => $request->sex,
                'tel'         => $request->tel,
                'email'       => $request->email,
                'birthday_year'  => $request->birthday_year,
                'birthday_month' => $request->birthday_month,
                'birthday_day'   => $request->birthday_day,
                'shop_id'     => $request->shop_id,
                'staff_id'    => $request->staff_id,
                'zip21'       => $request->zip21,
                'zip22'       => $request->zip22,
                'pref21'      => $request->pref21,
                'addr21'      => $request->addr21,
                'strt21'      => $request->strt21,
                ]
            ]);
            $id = DB::getPdo()->lastInsertId();

            // throw new \Exception("強制終了");
            DB::commit();
            return redirect(route('customer.show', [
                'id' => $id,
            ]));
            // return redirect()->action('MedicalRecordController@complete',['id'=> $request->shop_id ]);
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
                'member_number' => 'sometimes|nullable|unique:customers,member_number',
                'f_name' => 'required',
                'l_name' => 'required',
                'f_read' => 'required|regex:/^[ァ-ヶー]+$/u',
                'l_read' => 'required|regex:/^[ァ-ヶー]+$/u',
                'tel' => '',
                'shop_id' => 'required|',
                'staff_id' => 'required|',
                'email' => 'sometimes|nullable|regex:/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+$/',
                'zip21' => 'size:3',
                'zip22' => 'size:4',
            ],[
                'zip21.size' => '郵便番号は 3桁 - 4桁 で入力してください',
                'zip22.size' => '郵便番号は 3桁 - 4桁 で入力してください',
            ]
        );
    }

    /**
     * 顧客登録時のバリデーションチェック2
     */
    public function store_validate2($request)
    {
        // 会員番号を設定
        $member_number['TOKKAI_shop'] = Shop::select('tokkai_shop')->find($request->shop_id)->tokkai_shop;
        if($request->member_number){
            if( $member_number['TOKKAI_shop'] <> substr($request->member_number , 0 ,2) ){
                throw new \Exception("会員番号の2文字が正しくありません");
            }
            $member_number['TOKKAI_no'] = preg_replace('/[^0-9]/', '', $request->member_number);
            if(!is_numeric(substr($request->member_number , 2 )) ){
                throw new \Exception("会員番号の3文字目移行は整数にしてください");
            }
            $member_number['member_number'] = $member_number['TOKKAI_shop'] . $member_number['TOKKAI_no'] ;
        }else{
            $sql = "SELECT max(TOKKAI_no) as TOKKAI_no FROM customers limit 1";
            $result = DB::select($sql);
            $member_number['TOKKAI_no'] = $result[0]->TOKKAI_no + 1 ;
            $member_number['member_number'] = $member_number['TOKKAI_shop'] . $member_number['TOKKAI_no'] ;
        }

        // 誕生日が正しいかを確認
        if (!checkdate($request->birthday_month, $request->birthday_day, $request->birthday_year)) {
            throw new \Exception("誕生日が存在しない日付です。");
        }

        return $member_number ;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $customer = $this->_customerOBJ->get_customer($id);
            $customer = CheckData::set_sex_name($customer);
            $customer = CheckData::set_birthday($customer);
            if($this->_auth_authority_id >= 5){
                $customer = CheckData::mask_tel($customer);
                $customer = CheckData::mask_address($customer);
            }

            $visitHistories = VisitHistory::select('visit_histories.*', 'users.name', 'visit_types.type_name', 'menus.menu_name', 'angle1.img_pass as img_pass1', 'angle2.img_pass as img_pass2', 'angle3.img_pass as img_pass3')
                ->where('visit_histories.customer_id', $id)
                ->where('visit_histories.delete_flag', 0)
                ->leftJoin('users', 'visit_histories.staff_id', '=', 'users.id')
                ->leftJoin('visit_types', 'visit_histories.visit_type_id', '=', 'visit_types.id')
                ->leftJoin('menus', 'visit_histories.menu_id', '=', 'menus.id')
                ->leftJoin('visit_history_images as angle1', function ($join) {
                    $join->on('visit_histories.id', '=', 'angle1.visit_history_id')
                        ->where('angle1.angle', '1')
                        ->where('angle1.delete_flag', '0');
                })
                ->leftJoin('visit_history_images as angle2', function ($join) {
                    $join->on('visit_histories.id', '=', 'angle2.visit_history_id')
                        ->where('angle2.angle', '2')
                        ->where('angle2.delete_flag', '0');
                })
                ->leftJoin('visit_history_images as angle3', function ($join) {
                    $join->on('visit_histories.id', '=', 'angle3.visit_history_id')
                        ->where('angle3.angle', '3')
                        ->where('angle3.delete_flag', '0');
                })
                ->orderBy('vis_date', 'desc')
                ->get();

            // 表示できる画像があれば取得する
            $userImgRecord = VisitHistoryImage::select('visit_history_images.*')
            ->join('visit_histories', 'visit_history_images.visit_history_id', '=', 'visit_histories.id')
            ->where('visit_history_images.delete_flag', 0)
            ->where('visit_histories.delete_flag', 0)
            ->where('visit_history_images.customer_id', $id)
            ->orderByRaw("visit_histories.vis_date DESC, visit_history_images.angle ASC")
            ->first();
            if($userImgRecord){
                $userImgPass = $userImgRecord->img_pass;
            }else{
                $userImgPass = NULL;
            }

            // 本日の来店履歴があるかを調べて、来店情報登録可能フラグを設定する
            $visitHistory = VisitHistory::where('customer_id', $id)
            ->where('vis_date', date('Y-m-d'))
            ->where('delete_flag', 0)
            ->first();
            if(!empty($visitHistory)){
                $register_flg = false;
            }else{
                $register_flg = true;
            }

            return view('customer.show', compact('customer', 'visitHistories', 'register_flg', 'userImgPass' ));
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function visit_history($id)
    {

        try {
            // throw new \Exception("処理作成中");
            $visitHistories = VisitHistory::select('visit_histories.*', 'users.name', 'visit_types.type_name', 'menus.menu_name', 'angle1.img_pass as img_pass1', 'angle2.img_pass as img_pass2', 'angle3.img_pass as img_pass3')
            ->where('visit_histories.customer_id', $id)
            ->leftJoin('users', 'visit_histories.staff_id', '=', 'users.id')
            ->leftJoin('visit_types', 'visit_histories.visit_type_id', '=', 'visit_types.id')
            ->leftJoin('menus', 'visit_histories.menu_id', '=', 'menus.id')
            ->leftJoin('visit_history_images as angle1', function ($join) {
                $join->on('visit_histories.id', '=', 'angle1.visit_history_id')
                    ->where('angle1.angle', '1');
            })
            ->leftJoin('visit_history_images as angle2', function ($join) {
                $join->on('visit_histories.id', '=', 'angle2.visit_history_id')
                    ->where('angle2.angle', '2');
            })
            ->leftJoin('visit_history_images as angle3', function ($join) {
                $join->on('visit_histories.id', '=', 'angle3.visit_history_id')
                    ->where('angle3.angle', '3');
            })
            ->get();

            // 本日の来店履歴があるかを調べて、来店情報登録可能フラグを設定する
            $visitHistory = VisitHistory::where('customer_id', $id)
            ->where('vis_date', date('Y-m-d'))
            ->first();
            if(!empty($visitHistory)){
                $register_flg = false;
            }else{
                $register_flg = true;
            }

            $customer = $this->_customerOBJ->get_customer($id);
            $customer = CheckData::set_sex_name($customer);
            return view('customer.visit_history', compact('customer', 'visitHistories', 'register_flg'));
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            if($this->_auth_authority_id >= 5) throw new \Exception("編集権限がありません");

            $customer = Customer::get_customer($id);
            $shops = Shop::get_shopList();
            $users = UserOld::get_userList();

            return view('customer.edit', compact('customer', 'shops', 'users'));
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
        //
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
            if($request->cancel){
                session()->flash('msg_success', 'キャンセルしました');
                return redirect()->action('CustomerController@show', ['id' => $id]);
            }
            DB::beginTransaction();

            $customer = Customer::find($id);
            $customer->member_number = $request->member_number;
            $customer->f_name = $request->f_name;
            $customer->l_name = $request->l_name;
            $customer->f_read = $request->f_read;
            $customer->l_read = $request->l_read;
            $customer->birthday_year = $request->birthday_year;
            $customer->birthday_month = $request->birthday_month;
            $customer->birthday_day = $request->birthday_day;
            $customer->tel = $request->tel;
            $customer->email = $request->email;
            $customer->zip21 = $request->zip21;
            $customer->zip22 = $request->zip22;
            $customer->pref21 = $request->pref21;
            $customer->addr21 = $request->addr21;
            $customer->strt21 = $request->strt21;
            $customer->memo = $request->memo;
            $customer->save();

            // throw new \Exception("強制終了");

            DB::commit();
            session()->flash('msg_success', '更新完了しました');
            return redirect()->action('CustomerController@show', ['id' => $id]);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $customer = Customer::find($id);

            // 削除していい顧客か確認
            // 来店履歴が紐づいているか
            $visitHistory = VisitHistory::where('customer_id', $id)->where('delete_flag', 0)->first();
            if($visitHistory) throw new \Exception('来店履歴が存在するため削除できません');

            // 権限がないユーザーの場合
            if($this->_user->authority_id >= 8){
                // 多店舗の顧客を削除しようとしていないか
                if($this->_user->shop_id <> $customer->shop_id){
                    throw new \Exception('多店舗の顧客を削除する権限がありません');
                }
            }

            $customer->delete_flag = 1;
            $customer->save();

            DB::commit();
            session()->flash('msg_success', '削除完了しました');
            // report/index
            return redirect()->action('ReportController@index');
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
        //
    }

}
