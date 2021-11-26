<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\NoticesStatus;
use App\User;
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
     * 過去のお知らせ一覧を取得してリストを表示させます。
     */
    public function noticeList()
    {
        // 権限が無ければTOP画面に遷移
        if( \Auth::user()->authority_id > config('ur.authorityList')[3]['authorityId']){
            session()->flash('msg_danger', 'お知らせ登録をする権限がありません' );
            return view('home');
        }

        $notices = Notice::select()
        ->where('delete_flag', 0)
        ->orderBy('created_at', 'desc')
        ->get();

        return view('setting.noticeList', compact('notices'));
    }

    /**
     * 過去のお知らせ一覧を取得してリストを表示させます。
     */
    public function noticeCreate()
    {
        //
        return view('setting.noticeCreate');
    }

    /**
     * お知らせ内容をセッションに格納して確認画面を表示する
     */
    public function noticeRegisterConfirm(Request $request)
    {
        try {
            // バリデーションチェック
            $notice = $this->noticeValidationCheck($request);

            // お知らせ内容をセッションに保存
            $request->session()->put('notice.title', $notice['title']);
            $request->session()->put('notice.comment', $notice['comment']);

            return view('setting.noticeRegisterConfirm', compact('notice'));
        } catch (\Throwable $e) {
            \DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->action('SettingController@noticeList');
        }

    }

    /**
     * 過去のお知らせ一覧を取得してリストを表示させます。
     */
    public function noticeRegister(Request $request)
    {
        try {

            $notice = $request->session()->get('notice', array());
            if(empty($notice)){
                throw new \Exception("セッションが無効です。やり直してください。");
            }

            \DB::beginTransaction();

            // お知らせを登録
            Notice::insert([[
                'user_id'     => \Auth::user()->id,
                'title'       => $notice['title'],
                'comment'     => $notice['comment'],
                'hidden_flag' => 0,
                ]
            ]);
            $notices_id = \DB::getPdo()->lastInsertId();

            // お知らせを通知するユーザーを取得
            $users = User::select()
            ->where('authority_id', '>=', 2)
            ->where('authority_id', '<=', 7)
            ->get();

            // 各ユーザー用に未読状態で登録する
            foreach($users as $user){
                NoticesStatus::insert([[
                    'notice_id' => $notices_id,
                    'user_id' => $user->id,
                    'notice_status' => 0,
                    'hidden_flag' => 0,
                ]]);
            }

            // セッションを削除
            $notice = $request->session()->forget('notice');

            \DB::commit();
            session()->flash('msg_success', 'お知らせを登録しました');
            return redirect()->action('SettingController@noticeList');
        } catch (\Throwable $e) {
            \DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            // return redirect()->back()->withInput();    // 前の画面へ戻る
            return redirect()->action('SettingController@noticeList');
        }

    }

    public function noticeValidationCheck($request){
        // タイトルのバリデーションチェック
        if(empty($request->title)){
            throw new \Exception("タイトルが入力されていません。");
        }

        // コメントのバリデーションチェック
        if(empty($request->comment)){
            throw new \Exception("コメントが入力されていません。");
        }

        $notice = [
            'title' => $request->title,
            'comment' => $request->comment,
        ];

        return $notice;
    }

    /**
     * 対象のお知らせを確認する。
     */
    public function noticeConfirm($id)
    {
        try {
            $notice = Notice::select()
            ->where('delete_flag', 0)
            ->find($id);

            if(empty($notice)){
                throw new \Exception("対象のお知らせはありません");
            }

            return view('setting.noticeConfirm', compact('notice'));
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->action('SettingController@noticeList');
        }
    }

    /**
     * 対象のお知らせを削除する
     */
    public function noticeDelete($id)
    {
        try {
            \DB::beginTransaction();

            $notice = Notice::select()
            ->where('delete_flag', 0)
            ->find($id);

            if(empty($notice)){
                throw new \Exception("対象のお知らせはありません");
            }
            $notice->delete_flag = 1;
            $notice->save();

            $dateTime = new \DateTime();
            NoticesStatus::where('notice_id', $id)
            ->update([
                'notice_status' => 9,
                'del_user_id' => \Auth::user()->id,
                'del_at' => $dateTime->format('Y-m-d H:i:s'),
                'delete_flag' => 1,
            ]);

            \DB::commit();
            session()->flash('msg_success', 'お知らせを削除しました。');
            return redirect()->action('SettingController@noticeList');
        } catch (\Throwable $e) {
            \DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->action('SettingController@noticeList');
        }
    }

    /**
     * 対象のお知らせを表示する
     */
    public function noticeShow($id)
    {
        try {
            \DB::beginTransaction();
            $notice = NoticesStatus::select()
            ->join('notices', 'notices.id', '=', 'notices_statuses.notice_id')
            ->where('notices_statuses.delete_flag', 0)
            ->where('notices_statuses.user_id', \Auth::user()->id)
            ->find($id);

            if(empty($notice)){
                throw new \Exception("対象のお知らせはありません。不正な画面遷移です。");
            }

            // 未読だった場合は既読にする
            $noticesStatus = NoticesStatus::find($id);
            if($noticesStatus->notice_status == 0){
                $noticesStatus->notice_status = 1;
                $noticesStatus->read_at = date('Y-m-d H:i:s');
                $noticesStatus->save();
            }

            \DB::commit();
            return view('setting.noticeShow', compact('notice'));
        } catch (\Throwable $e) {
            \DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->action('HomeController@index');
        }
    }

}
