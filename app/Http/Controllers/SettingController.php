<?php

namespace App\Http\Controllers;

use App\Services\CheckData;
use Illuminate\Http\Request;
use Hash;

class SettingController extends Controller
{

    /**
     * アカウント情報を表示する
     *
     * @return view
     */
    public function index()
    {
        $user_id = \Auth::user()->id;
        $user = \DB::table('users')->select('users.*', 'shops.shop_name')
        ->where('users.id', $user_id)
        ->join('shops', 'shops.id', '=', 'users.shop_id')
        ->first();

        $user = CheckData::set_authority_name($user);
        return view('setting.index',compact('user'));
    }

    /**
     * Emailを表示する
     * @return \Illuminate\Http\Response
     */
    public function Email()
    {
        $user = \Auth::user();
        return view('setting.email',compact('user'));
    }

    /**
     * Email編集画面を表示する
     * @return \Illuminate\Http\Response
     */
    public function ChangeEmail()
    {
        $user = \Auth::user();
        return view('setting.changeEmail',compact('user'));
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function updateEmail(Request $request)
    {

        try {
            \DB::beginTransaction();

            $user = \Auth::user();
            if(empty($request->email) || empty($request->new_email1) || empty($request->new_email2) )throw new \Exception("入力項目は全て必須です");
            if($request->email <> $user->email ) throw new \Exception("現在のメールアドレスが合っていません");
            if($request->new_email1 <> $request->new_email2 ) throw new \Exception("新しいメールアドレスが一致していません");
            if($request->new_email1 == $user->email ) throw new \Exception("新しいメールアドレスが現在のメールアドレスと変わっていません");
            $user->email = $request->new_email1;
            $user->save();

            \DB::commit();
            session()->flash('msg_success', 'メールアドレスを更新しました');
            return redirect()->action('SettingController@index');
        } catch (\Throwable $e) {
            \DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back()->withInput();    // 前の画面へ戻る
        }
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
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function EditPassword()
    {
        $user = \Auth::user();

        return view('setting.editPassword',compact('user'));
    }

    /**
     * パスワードをアップデートする
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {

        try {
            \DB::beginTransaction();
            $user = \Auth::user();
            if(empty($request->password) || empty($request->new_password1) || empty($request->new_password2) )throw new \Exception("入力項目は全て必須です");

            // 現在のパスワードが合っているかcheck
            if (!Hash::check($request->password, $user->password)) {
                throw new \Exception("現在のパスワードが合っていません");
            }
            if($request->new_password1 <> $request->new_password2 ) throw new \Exception("新しいパスワードが一致していません");

            $user->password = Hash::make($request->new_password1);
            $user->save();

            \DB::commit();
            session()->flash('msg_success', 'パスワードを更新しました');
            return redirect()->action('SettingController@index');
        } catch (\Throwable $e) {
            \DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back()->withInput();    // 前の画面へ戻る
        }
    }

    /**
     *
     */
    public function lecture()
    {
        return view('setting.lecture');
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
     *
     */
    public function notice()
    {
        //
        return view('setting.notice');
    }
}
