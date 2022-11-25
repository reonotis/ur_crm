<?php

namespace App\Http\Controllers;

use app\Common\CustomerCheck;
use App\Exceptions\ExclusionException;
use App\Consts\{Common, ErrorCode, SessionConst};
use App\Models\{Customer, CustomerNoCounter, UserShopAuthorization, VisitHistory};
use App\Models\Shop;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
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
     * Display a listing of the resource.
     * @return View
     */
    public function index(): View
    {
        $condition = $this->_setConditions();
        $customersQuery = Customer::getCustomers($condition);
        $customers = $customersQuery->paginate(50);
        return view('customer.search', compact('customers'));
    }

    /**
     * @return array
     */
    private function _setConditions(): array
    {
        $condition = [];

        // 顧客番号
        if(!empty(request('customer_no'))){
            $condition['customer_no'] = request('customer_no');
        }

        // 名前
        if(!empty(request('f_name'))){
            $condition['f_name'] = request('f_name');
        }
        if(!empty(request('l_name'))){
            $condition['l_name'] = request('l_name');
        }
        if(!empty(request('f_read'))){
            $condition['f_read'] = request('f_read');
        }
        if(!empty(request('l_read'))){
            $condition['l_read'] = request('l_read');
        }

        if(empty(request('other_shop'))){
            $condition['shop_id'] = session()->get(SessionConst::SELECTED_SHOP)->id;
        }

        if(empty(request('other_staff'))){
            $condition['staff_id'] = $this->loginUser->id;
        }


        // 誕生日
        if(!empty(request('birthday_year'))){
            $condition['birthday_year'] = request('birthday_year');
        }
        if(!empty(request('birthday_month'))){
            $condition['birthday_month'] = request('birthday_month');
        }
        if(!empty(request('birthday_day'))){
            $condition['birthday_day'] = request('birthday_day');
        }

        if(!empty(request('tel'))){
            $condition['tel'] = request('tel');
        }
        if(!empty(request('email'))){
            $condition['email'] = request('email');
        }

        // 住所
        if(!empty(request('zip21'))){
            $condition['zip21'] = request('zip21');
        }
        if(!empty(request('zip22'))){
            $condition['zip22'] = request('zip22');
        }
        if(!empty(request('pref21'))){
            $condition['pref21'] = request('pref21');
        }
        if(!empty(request('address21'))){
            $condition['address21'] = request('address21');
        }
        if(!empty(request('street21'))){
            $condition['street21'] = request('street21');
        }

        if(!empty(request('hidden_flag'))){
            $condition['hidden_flag'] = request('hidden_flag');
        }

        return $condition;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $shopId = session()->get(SessionConst::SELECTED_SHOP)->id;
        $users = UserShopAuthorization::getSelectableUsers($shopId)->get();

        return view('customer.create',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->session()->regenerateToken(); // 二重クリック防止

        $shopId = session()->get(SessionConst::SELECTED_SHOP)->id;
        if (empty($request->customer_no)){
            $customerNo = $this->_makeCustomerNo($shopId); // 作成
        } else {
            $customerNo = $this->_checkCustomerNo($request->customer_no); // 確認
        }

        $this->_checkValidate($request);
        if (count($this->errMsg)){
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, $this->errMsg)->withInput();
        }

        try {
            DB::beginTransaction();
            $customer = Customer::create([
                'customer_no' => $customerNo,
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'f_read' => $request->f_read,
                'l_read' => $request->l_read,
                'sex' => $request->sex,
                'tel' => $request->tel,
                'email' => $request->email,
                'birthday_year' => $request->birthday_year,
                'birthday_month' => $request->birthday_month,
                'birthday_day' => $request->birthday_day,
                'shop_id' => $shopId,
                'staff_id' => $request->staff_id,
                'zip21' => $request->zip21,
                'zip22' => $request->zip22,
                'pref21' => $request->pref21,
                'address21' => $request->address21,
                'street21' => $request->street21,
                'memo' => $request->memo,
            ]);

            DB::commit();
            return redirect()->route('customer.show', ['customer'=>$customer->id])->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['顧客情報を登録しました']);
        } catch (\Throwable $e) {
            DB::rollback();
            Log::error( ' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['顧客情報の登録に失敗しました'])->withInput();
        }
    }

    /**
     * 顧客登録時のバリデーションチェック
     * @param Request $request
     */
    public function _checkValidate(Request $request): void
    {
        $customerCheck = new CustomerCheck;
        $customerCheck->registerCheckValidation($request);
        if(count($customerCheck->getErrMsg())){
            $this->errMsg[] = $customerCheck->getErrMsg();
        }

        if (empty($request->staff_id)){
            $this->errMsg[] = '担当スタッフが選択されていません';
        } else {
            $shopId = session()->get(SessionConst::SELECTED_SHOP)->id;
            $users = UserShopAuthorization::getSelectableUsers($shopId)->get()->toArray();
            $myShopUsersIdList = array_column( $users, 'id');
            if (!in_array($request->staff_id , $myShopUsersIdList)){
                $this->errMsg[] = '選択した担当スタッフは店舗に所属していません';
            }
        }
    }

    /**
     * Display the specified resource.
     * @param Customer $customer
     * @return View
     */
    public function show(Customer $customer): View
    {
        if(empty($this->loginUser->checkAuthByShopId($customer->shop_id)->customer_read)){
            $this->goToExclusionErrorPage(ErrorCode::CL_030002, [
                $customer->shop_id,
                $customer->id,
                $this->loginUser->id,
            ]);
        }
        if(!$this->loginUser->checkAuthByShopId($customer->shop_id)->customer_read_none_mask){
            $customer = $this->_customerMask($customer);
        }
        $visitHistories = VisitHistory::getByCustomerId($customer->id)->get();

        return view('customer.show', compact('customer', 'visitHistories'));
    }

    /**
     * @param Customer $customer
     * @return Customer
     */
    private function _customerMask(Customer $customer): Customer
    {
        $char = '※';
        $customer->tel = mb_ereg_replace('[0-9]',$char, $customer->tel);
        $customer->zip21 = str_repeat($char, mb_strlen($customer->zip21));
        $customer->zip22 = str_repeat($char, mb_strlen($customer->zip22));
        $customer->pref21 = str_repeat($char, mb_strlen($customer->pref21));
        $customer->address21 = str_repeat($char, mb_strlen($customer->address21));
        $customer->street21 = str_repeat($char, mb_strlen($customer->street21));

        return $customer;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Customer $customer
     * @return View
     */
    public function edit(Customer $customer): View
    {
        if(empty($this->loginUser->checkAuthByShopId($customer->shop_id)->customer_read)){
            $this->goToExclusionErrorPage(ErrorCode::CL_030003, [
                $customer->shop_id,
                $customer->id,
                $this->loginUser->id,
            ]);
        }
        $shops = Shop::get();
        $users = UserShopAuthorization::getSelectableUsers()->get();

        return view('customer.edit', compact('customer', 'shops', 'users'));
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param Customer $customer
     * @return RedirectResponse
     * @throws ExclusionException
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $shops = Auth::user()->userShopAuthorizations->toArray();
        $shopIdList = array_column($shops, "shop_id");
        if(!in_array($customer->shop_id , $shopIdList)){
            $this->goToExclusionErrorPage(ErrorCode::CL_030004, [
                $customer->shop_id,
                $customer->id,
                $this->loginUser->id,
            ]);
        }

        try {
            if (empty($request->customer_no)){
                $customerNo = $this->_makeCustomerNo($customer->shop_id); // 作成
            } else {
                $customerNo = $this->_checkCustomerNo($request->customer_no, $customer->id); // 確認
            }
            if (!$customerNo){
                return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, $this->errMsg)->withInput();
            }

            DB::beginTransaction();

            $customer->customer_no = $customerNo;
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
            $customer->address21 = $request->address21;
            $customer->street21 = $request->street21;
            $customer->shop_id = $request->shop_id;
            $customer->staff_id = $request->staff_id;
            $customer->memo = $request->memo;
            $customer->save();

            DB::commit();
            return redirect()->route('customer.show', ['customer'=>$customer->id])->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['顧客情報を更新しました']);

        } catch (\Throwable $e) {
            DB::rollback();
            Log::error( ' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['顧客情報の更新に失敗しました'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        // このショップに対して削除権限があるかチェック
        if(empty($this->loginUser->checkAuthByShopId($customer->shop_id)->customer_delete)){
            $this->goToExclusionErrorPage(ErrorCode::CL_030005, [
                $customer->shop_id,
                $customer->id,
                $this->loginUser->id,
            ]);
        }

        try {
            DB::beginTransaction();
            $customer->delete();
            DB::commit();
            return redirect()->route('customer.index')->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['顧客を削除しました']);
        } catch (Exception $e) {
            Log::error( ' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['顧客の削除に失敗しました'])->withInput();
        }
    }

    /**
     * @param Customer $customer
     * @param int $customerId
     * @return string
     */
    private function _checkCustomerNo(string $customerNo, int $customerId = null): string
    {
        // バリデーションチェック
        if (!$this->_checkValidationCustomerNo($customerNo)){
            return '';
        }

        // 重複チェック
        $existCheckSql = Customer::select('customer_no')
            ->where('customer_no', $customerNo);
        if($customerId){
            $existCheckSql = $existCheckSql->where('id', '<>', $customerId);
        }
        $existCheck = $existCheckSql->first();
        if ($existCheck){
            $this->errMsg[] = 'この顧客番号は既に存在しています';
            return '';
        }

        return $customerNo;
    }

    /**
     * 顧客番号の文字列が正しいか判定
     * @param string $customerNo
     * @return bool
     * @throws Exception
     */
    private function _checkValidationCustomerNo(string $customerNo): bool
    {
        $symbol = substr($customerNo, 0, 2);  // 先頭2文字
        $nom = substr($customerNo, 2);  // 3文字目以降
        $shopSymbolCheck = Shop::where('shop_symbol', $symbol)->first();
        if (!$shopSymbolCheck){
            $this->errMsg[] = '顧客番号の先頭2文字が不正です';
            return false;
        }

        if (strlen($nom) <> Common::CUSTOMER_NO_LENGTH){
            $this->errMsg[] = '顧客番号の長さが不正です。先頭2文字と6文字の数字にしてください';
            return false;
        }

        if(!is_numeric(substr($customerNo , 2 )) ){
            $this->errMsg[] = '会員番号の3文字目以降は整数にしてください';
            return false;
        }
        return true;
    }

    /**
     * ショップシンボルを先頭にした顧客番号を生成する
     * 数次は6桁
     * 例) CA999999
     * @param int $shopId
     * @return string
     */
    private function _makeCustomerNo(int $shopId): string
    {
        $lastId = CustomerNoCounter::select('id')->latest('id')->first();
        $newId = $lastId->id + 1;
        $customerNoCounter = CustomerNoCounter::create([
            'id' => $newId,
        ]);

        $shop = Shop::find($shopId);
        return $shop->shop_symbol . str_pad($customerNoCounter->id, Common::CUSTOMER_NO_LENGTH, 0, STR_PAD_LEFT);
    }

}
