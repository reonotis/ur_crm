<?php

namespace App\Http\Controllers;

use App\Exceptions\ExclusionException;
use App\Consts\{
        DatabaseConst,
        ErrorCode,
        SessionConst
    };
use App\Models\{
        User,
        UserShopAuthorization
    };
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserController extends UserAppController
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
     * ユーザー一覧画面を表示する
     * @return View
     */
    public function index(): View
    {
        $condition = $this->_setConditions();
        $shopUsersQuery = User::getUsersByShopId($condition);
        $users = $shopUsersQuery->get();

        return view('user.index', compact('users'));
    }

    /**
     * ユーザー作成画面を表示する
     * @return View
     */
    public function create(): View
    {
        return view('user.create');
    }

    /**
     * ユーザーを登録する
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->session()->regenerateToken(); // 二重クリック防止
        try {
            $this->checkValid($request);
            $this->checkAuthValid($request);
            if(count($this->errMsg)){
                return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, $this->errMsg)->withInput();
            }
            DB::beginTransaction();

            $randomPass = $request->password;

            $user = $this->_insertUser($request, $randomPass);
            $userShopAuthorization = $this->_insertUserShopAuthorization($user, $request);

            $res = $this->_sendFirstUserRegistMail($user, $randomPass);
            if($res){
                $resMsg = 'スタッフ情報を登録しました';
            } else {
                $resMsg = 'スタッフ情報を登録しましたが、メール送信に失敗しました';
            }

            DB::commit();
            return redirect()->route('user.index')->with(SessionConst::FLASH_MESSAGE_SUCCESS, [$resMsg]);
        } catch (Exception $e) {
            Log::error( ' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['スタッフ情報の登録に失敗しました'])->withInput();
        }
    }

    /**
     * ユーザー詳細画面を表示する
     * @param User $user
     * @return View
     * @throws ExclusionException
     */
    public function show(User $user): View
    {
        $this->_myShopUserCheck($user, ErrorCode::CL_010002);

        return view('user.show', compact('user'));
    }

    /**
     * ユーザー編集画面を表示する
     * @param User $user
     * @return View
     * @throws ExclusionException
     */
    public function edit(User $user): View
    {
        $this->_myShopUserCheck($user, ErrorCode::CL_010003);

        return view('user.edit', compact('user'));
    }

    /**
     * ユーザー情報を更新する
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     * @throws ExclusionException
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // 自店舗のスタッフかチェックする
        $this->_myShopUserCheck($user, ErrorCode::CL_010004);

        $request->session()->regenerateToken(); // 重複クリック対策
        try {
            $this->checkValid($request);
            $this->checkAuthValid($request);
            if(count($this->errMsg)){
                return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, $this->errMsg)->withInput();
            }
            DB::beginTransaction();

            // ユーザー情報更新
            $user->name = $request->name;
            $user->email = $request->email;
            $user->authority_level = $request->authority;
            $user->save();

            $userShopAuth = $user->userShopAuthorization;
            $userShopAuth->user_read = $request->user_read;
            $userShopAuth->user_create = $request->user_create;
            $userShopAuth->user_edit = $request->user_edit;
            $userShopAuth->user_delete = $request->user_delete;
            $userShopAuth->customer_read = $request->customer_read;
            $userShopAuth->customer_read_none_mask = $request->customer_read_none_mask;
            $userShopAuth->customer_create = $request->customer_create;
            $userShopAuth->customer_edit = $request->customer_edit;
            $userShopAuth->customer_delete = $request->customer_delete;
            $userShopAuth->reserve_read = $request->reserve_read;
            $userShopAuth->reserve_create = $request->reserve_create;
            $userShopAuth->reserve_edit = $request->reserve_edit;
            $userShopAuth->reserve_delete = $request->reserve_delete;
            $userShopAuth->save();

            DB::commit();
            return redirect()->route('user.show', ['user'=>$user->id])->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['スタッフ情報を更新しました']);
        } catch (Exception $e) {
            Log::error( ' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['スタッフ情報の更新に失敗しました'])->withInput();
        }
    }

    /**
     * @param User $user
     * @return RedirectResponse
     * @throws ExclusionException
     */
    public function destroy(User $user): RedirectResponse
    {
        // 自店舗のスタッフかチェックする
        $this->_myShopUserCheck($user, ErrorCode::CL_010005);
        try {
            DB::beginTransaction();

            $user->delete();

            DB::commit();
            return redirect()->route('user.index')->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['スタッフを削除しました']);
        } catch (Exception $e) {
            Log::error( ' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['スタッフの削除に失敗しました'])->withInput();
        }
    }

    /**
     * 自店舗に所属していないユーザー一覧を表示します
     * @return View
     */
    public function belongSelect(): View
    {
        $users = $this->_getUsersOtherShop();
        return View('user.belongSelect', compact('users'));
    }

    /**
     * 店舗に既存ユーザーを紐づけます
     * @param User $user
     * @return RedirectResponse
     */
    public function belongSelected(User $user): RedirectResponse
    {
        try {
            $shopId = session()->get(SessionConst::SELECTED_SHOP)->id;
            foreach ($user->userShopAuthorizations AS $userShopAuth){
                if($userShopAuth->shop_id == $shopId){
                    throw new Exception('このユーザーは既に紐づいています $userId:' . $user->id);
                }
            }
            DB::beginTransaction();
            $this->_insertUserShopAuthorization($user);

            DB::commit();
            return redirect()->route('user.index')->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['スタッフを紐づけました']);
        } catch (Exception $e) {
            Log::error( 'msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['スタッフの紐づけに失敗しました'])->withInput();
        }

    }

    /**
     * @param User $user
     * @return RedirectResponse
     * @throws ExclusionException
     */
    public function deleteBelongShop(User $user): RedirectResponse
    {
        // 自店舗のスタッフかチェックする
        $this->_myShopUserCheck($user, ErrorCode::CL_010006);

        try {
            DB::beginTransaction();
            $user->userShopAuthorization->delete();

            DB::commit();
            return redirect()->route('user.index')->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['紐づけを解除しました']);
        } catch (Exception $e) {
            Log::error( ' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['紐づけの解除に失敗しました'])->withInput();
        }
    }

    /**
     * 自店舗に所属していないユーザーを取得します
     * @return object
     */
    private function _getUsersOtherShop(): object
    {
        // 自店舗のユーザーを取得
        $shopId = session()->get(SessionConst::SELECTED_SHOP)->id;
        $myShopUsers = UserShopAuthorization::select('*')
            ->where('shop_id', '=', $shopId)
            ->get()
            ->toArray();

        // 自店舗のユーザーのIDの配列を作成
        $myShopUsersIdList = array_column( $myShopUsers, 'user_id');

        // 自店舗に属していないユーザーを取得 TODO MODELに記載
        $otherShopUsers = User::select('*')
            ->whereNotIn('id', $myShopUsersIdList)
            ->where('display_flag', DatabaseConst::FLAG_ON)
            ->get();

        return $otherShopUsers;
    }

    /**
     * @return array
     */
    private function _setConditions(): array
    {
        $condition = [];
        $condition['shopId'] = session()->get(SessionConst::SELECTED_SHOP)->id;
        if(!empty(request('name'))){
            $condition['name'] = request('name');
        }

        if(!empty(request('email'))){
            $condition['email'] = request('email');
        }

        if(!empty(request('authority_level'))){
            $condition['authority_level'] = request('authority_level');
        }

        return $condition;
    }

    /**
     * @param Request $r
     * @return void
     */
    public function checkValid(Request $r): void
    {
        if (empty($r->name)){
            $this->errMsg['name'] = '名前は必須入力です';
        }
        if (empty($r->email)){
            $this->errMsg['email'] = 'メールアドレスは必須入力です';
        } else {
            if (!preg_match('/^[a-z0-9._+^~-]+@[a-z0-9.-]+$/i', $r->email)) {
                $this->errMsg['email'] = 'メールアドレスが正しくありません';
            }
        }
    }

    /**
     * @param Request $r
     * @return void
     */
    private function checkAuthValid(Request $r): void
    {
        if($r->user_edit > $this->userShopAuthorization->user_edit){
            $this->errMsg[] = 'あなたにはスタイリスト編集権限が無い為、編集権限があるユーザーを作成できません';
        }
        if($r->user_edit > $this->userShopAuthorization->user_delete){
            $this->errMsg[] = 'あなたにはスタイリスト削除権限が無い為、削除権限があるユーザーを作成できません';
        }
        if($r->customer_edit > $this->userShopAuthorization->customer_edit){
            $this->errMsg[] = 'あなたには顧客編集権限が無い為、編集権限があるユーザーを作成できません';
        }
        if($r->customer_delete > $this->userShopAuthorization->customer_delete){
            $this->errMsg[] = 'あなたには顧客削除権限が無い為、削除権限があるユーザーを作成できません';
        }
        if($r->reserve_edit > $this->userShopAuthorization->reserve_edit){
            $this->errMsg[] = 'あなたには予約編集権限が無い為、編集権限があるユーザーを作成できません';
        }
        if($r->reserve_delete > $this->userShopAuthorization->reserve_delete){
            $this->errMsg[] = 'あなたには予約削除権限が無い為、削除権限があるユーザーを作成できません';
        }

        if($r->user_read < $r->user_create ){
            $this->errMsg[] = 'スタイリスト閲覧権限がない場合、スタイリスト作成権限を与えられません';
        }
        if($r->user_create < $r->user_edit ){
            $this->errMsg[] = 'スタイリスト作成権限がない場合、スタイリスト編集権限を与えられません';
        }
        if($r->user_edit < $r->user_delete ){
            $this->errMsg[] = 'スタイリスト編集権限がない場合、スタイリスト削除権限を与えられません';
        }

        if($r->customer_read < $r->customer_create ){
            $this->errMsg[] = '顧客閲覧権限がない場合、顧客作成権限を与えられません';
        }
        if($r->customer_create < $r->customer_edit ){
            $this->errMsg[] = '顧客作成権限がない場合、顧客編集権限を与えられません';
        }
        if($r->customer_edit < $r->customer_delete ){
            $this->errMsg[] = '顧客編集権限がない場合、顧客削除権限を与えられません';
        }

        if($r->reserve_read < $r->reserve_create ){
            $this->errMsg[] = '予約閲覧権限がない場合、予約作成権限を与えられません';
        }
        if($r->reserve_create < $r->reserve_edit ){
            $this->errMsg[] = '予約作成権限がない場合、予約編集権限を与えられません';
        }
        if($r->reserve_edit < $r->reserve_delete ){
            $this->errMsg[] = '予約編集権限がない場合、予約削除権限を与えられません';
        }
    }

    /**
     * @param Request $r
     * @param string $randomPass
     * @return object
     */
    private function _insertUser(Request $r, string $randomPass) :object
    {
        $insertData = [
            'name' => $r->name,
            'email' => $r->email,
            'password' => Hash::make($randomPass),
            'authority_level' => $r->authority,
        ];
        return User::create($insertData);
    }

    /**
     * @param object $user
     * @param Request|null $r
     * @return object
     */
    private function _insertUserShopAuthorization(object $user, Request $r = NULL): object
    {
        $insertData = [
            'shop_id' => session()->get(SessionConst::SELECTED_SHOP)->id,
            'user_id' => $user->id,
        ];
        if (!empty($r)){
            $insertData['user_read'] = $r->user_read;
            $insertData['user_create'] = $r->user_create;
            $insertData['user_edit'] = $r->user_edit;
            $insertData['user_delete'] = $r->user_delete;
            $insertData['customer_read'] = $r->customer_read;
            $insertData['customer_read_none_mask'] = $r->customer_read_none_mask;
            $insertData['customer_create'] = $r->customer_create;
            $insertData['customer_edit'] = $r->customer_edit;
            $insertData['customer_delete'] = $r->customer_delete;
            $insertData['reserve_read'] = $r->reserve_read;
            $insertData['reserve_create'] = $r->reserve_create;
            $insertData['reserve_edit'] = $r->reserve_edit;
            $insertData['reserve_delete'] = $r->reserve_delete;
        }

        return UserShopAuthorization::create($insertData);
    }

    /**
     *
     * @return bool
     */
    private function _sendFirstUserRegistMail(object $user, string $password): bool
    {
        try {
            $this->_toFujisawa = config('mail.toFujisawa');
            $this->_toInfo = config('mail.toInfo');
            $this->_toUserEmail = $user->email;

            $data = [
                "name" => $user->name,
                "email" => $user->email,
                "password" => $password,
                "url" => url('').'/myPage'
            ];
            Mail::send('emails.userRegister', $data, function($message){
                $message->to($this->_toUserEmail)
                    ->cc($this->_toInfo)
                    ->bcc($this->_toFujisawa)
                    ->subject('スタイリストとして登録されました');
            });
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * 渡されたユーザーが選択している店舗のユーザーでなければエラーとする
     * @param User $user
     * @param string $errCode
     * @return void
     * @throws ExclusionException
     */
    private function _myShopUserCheck(User $user, string $errCode): void
    {
        if(empty($user->userShopAuthorization)){
            $this->goToExclusionErrorPage($errCode, [
                session()->get(SessionConst::SELECTED_SHOP)->id,
                $user->id,
                $this->loginUser->id,
            ]);
        }
    }
}
