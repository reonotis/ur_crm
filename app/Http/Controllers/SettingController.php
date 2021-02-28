<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\CheckUsers;
use App\Http\Requests\updatePass;

class SettingController extends Controller
{
    // 定数の設定
    private $_fileExtntion = ['jpg', 'jpeg', 'png'];




    //
    public function index(){
        $auth = Auth::user();
        $myId = $auth->id;
        $query = DB::table('users')
                -> leftJoin('users_info', 'users.id', '=', 'users_info.id');
        $auth = $query->first();

        return view('setting.index', compact('auth'));
    }


    /**
    * パスワードの編集画面を表示します。
    */
    public function editPassword(){
        $auth = Auth::user();
        return view('setting.editPassword', compact('auth'));
    }


    /**
    * 電話番号の編集画面を表示します。
    */
    public function editTell(){
        $auth = Auth::user();
        $myId = $auth->id;

        $query = DB::table('users')
                -> leftJoin('users_info', 'users.id', '=', 'users_info.id');
        $auth = $query->first();
        return view('setting.editTell', compact('auth'));
    }

    /**
    * 住所の編集画面を表示します。
    */
    public function editAddress(){
        $auth = Auth::user();
        $myId = $auth->id;

        $query = DB::table('users')
                -> leftJoin('users_info', 'users.id', '=', 'users_info.id');
        $auth = $query->first();
        return view('setting.editAddress', compact('auth'));
    }

    /**
    * 画像の更新画面を表示します。
    */
    public function editImage(){
        $auth = Auth::user();
        $myId = $auth->id;

        $query = DB::table('users')
                -> leftJoin('users_info', 'users.id', '=', 'users_info.id');
        $auth = $query->first();
        return view('setting.editImg', compact('auth'));
    }






    /**
    * パスワードの更新を行います。
    */
    public function updatePassword(updatePass $request){
        $auth = Auth::user();
        $hashPass = $auth->password;

        // ハッシュ化済みパスワードのソルトを使って、受け取ったパスワードをハッシュ化後に比較
        if(!Hash::check($request->input('old_pass'), $hashPass)){
            session()->flash('msg_danger', 'パスワードが間違っています。');
            return back()->withInput();
        }elseif($request->input('new_pass1') <> $request->input('new_pass2')){ // 新しいパスワードが合っているか確認
            session()->flash('msg_danger', '新しいパスワードが確認用と一致していません。');
            return back()->withInput();
        }

        // dd( $request->input('new_pass1'), $request->input('new_pass2') );
        $auth->password = Hash::make($request->input('new_pass1'));
        $auth->save();
        session()->flash('msg_success', 'パスワードを更新しました');
        return redirect()->action('settingController@index');
    }


    /**
    * 電話番号の更新を行います。
    */
    public function updateTell(request $request){
        $newTell = $request->input('tell');

        //バリデーションcheck
        if(empty($newTell)){
            session()->flash('msg_danger', '電話番号を入力してください');
            return back();
        }
        if (preg_match('/\A\d{2,4}+-\d{2,4}+-\d{4}\z/', $newTell)) {
            $auths = Auth::user();
            $myId = $auths->id;
            DB::table('users_info')->updateOrInsert(
                ['id' => $myId],
                ['tel' =>  $newTell]
            );
            session()->flash('msg_success', '電話番号を更新しました');
            return redirect()->action('settingController@index');
        }else{
            session()->flash('msg_danger', '入力規則に一致していません。');
            return back();
        }
    }



    /**
    * 住所の更新を行います。
    */
    public function updateAddress(request $request){
        $zip21 = $request->input('zip21');
        $zip22 = $request->input('zip22');
        $pref21 = $request->input('pref21');
        $addr21 = $request->input('addr21');
        $strt21 = $request->input('strt21');

        //バリデーションcheck
        if( strlen($zip21) <> 3 || strlen($zip22) <> 4 ){
            session()->flash('msg_danger', '郵便番号は3桁-4桁で入力してください');
            return back();
        }
        if( empty($pref21) || empty($addr21) || empty($strt21) ){
            session()->flash('msg_danger', '住所は全て入力してください');
            return back();
        }

        $auth = Auth::user();
        $myId = $auth->id;
        DB::table('users_info')->updateOrInsert(
            ['id' => $myId],
            [   'zip21'  =>  $zip21,
                'zip22'  =>  $zip22,
                'pref21' =>  $pref21,
                'addr21' =>  $addr21,
                'strt21' =>  $strt21,
            ]
        );
        session()->flash('msg_success', '住所を更新しました');
        return redirect()->action('settingController@index');
    }


    /**
    * 画像の更新を行います。
    */
    public function updateImage(request $request){
        $auth = Auth::user();
        $myId = $auth->id;

        try {
            // 登録可能な拡張子か確認して取得する
            $extension = $this->checkFileExtntion($request);

            // ファイル名は、 {日時} _ {ユーザーID(7桁に0埋め)} _ 'mainImg' . {拡張子}
            $BaseFileName =  date("ymd_His") . '_' . str_pad($myId, 7, 0, STR_PAD_LEFT) . '_' . 'mainImg' . $extension;

            $path = $request->img->storeAs('public/images', $BaseFileName);  //画像をリネームして保存する
            $filename = basename($path); // パスから、最後の「ファイル名.拡張子」の部分だけ取得します 例)sample.jpg
            $filename = basename($path); // パスから、最後の「ファイル名.拡張子」の部分だけ取得します 例)sample.jpg

            DB::table('users_info')->updateOrInsert(
                ['id' => $myId],
                ['img_path'  =>  $filename,]
            );
            session()->flash('msg_success', '画像を更新しました');
            return redirect()->action('settingController@index');

        } catch (\Throwable $e) {
            dd($e->getMessage());
            //throw $th;
        }
    }


    /**
    * 登録可能な拡張子か確認するしてOKなら拡張子を返す
    */
    public function checkFileExtntion($request){
        // 渡された拡張子を取得
        $extension =  $request->img->extension();
        if(! in_array($extension, $this->_fileExtntion)){
            $fileExtntion = json_encode($this->_fileExtntion);
            throw new \Exception("登録できる画像の拡張子は". $fileExtntion ."のみです。");
        }
        return '.' . $request->img->extension();
    }






}
