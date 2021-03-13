<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\CheckUsers;
use App\Http\Requests\updatePass;
use \InterventionImage;

class SettingController extends Controller
{
    // 定数の設定
    private $_fileExtntion = ['jpg', 'jpeg', 'png'];
    private $_resize = '300';




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
        return redirect()->action('SettingController@index');
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
            return redirect()->action('SettingController@index');
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
        return redirect()->action('SettingController@index');
    }


    /**
    * 画像の更新を行います。
    */
    public function updateImage(request $request){
        $auth = Auth::user();
        $myId = $auth->id;
        $file = $request->img;
        try {
            if(empty($file)) throw new \Exception("ファイルが指定されていません");
            // 登録可能な拡張子か確認して取得する
            $extension = $this->checkFileExtntion($file);

            // ファイル名の作成 => {日時} _ {ユーザーID(7桁に0埋め)} _ 'mainImg' . {拡張子}
            $BaseFileName =  date("ymd_His") . '_' . str_pad($myId, 7, 0, STR_PAD_LEFT) . '_' . 'mainImg' . $extension;

            // 画像サイズを横幅500の比率にして保存する。
            $this->makeImgFile($file, $BaseFileName);

            // DB更新
            DB::table('users_info')->updateOrInsert(
                ['id' => $myId],
                ['img_path'  =>  $BaseFileName,]
            );
            session()->flash('msg_success', '画像を更新しました');
            return redirect()->action('SettingController@index');

        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }


    /**
     * 渡されたファイルが登録可能な拡張子か確認するしてOKなら拡張子を返す
     */
    public function checkFileExtntion($file){
        // 渡された拡張子を取得
        $extension =  $file->extension();
        if(! in_array($extension, $this->_fileExtntion)){
            $fileExtntion = json_encode($this->_fileExtntion);
            throw new \Exception("登録できる画像の拡張子は". $fileExtntion ."のみです。");
        }
        return '.' . $file->extension();
    }

    /**
     * 渡された画像ファイルをリサイズして保存する
     */
    public function makeImgFile($file, $BaseFileName){
        if(empty($file)) throw new \Exception("ファイルがありません。");
        if(empty($BaseFileName)) throw new \Exception("ファイル名が決まっていません。");

        $image = InterventionImage::make($file)->exif();
        $width  = $image['COMPUTED']['Width'];
        $height = $image['COMPUTED']['Height'];
        if($width < $height){
            $length = $width;
        }else{
            $length = $height;
        }

        // 正方形の画像を作成
        $square_image = InterventionImage::make($file)
                        ->crop($length, $length);

        // リサイズして保存
        $image = InterventionImage::make($square_image)
                        ->resize($this->_resize, null, function ($constraint) {$constraint->aspectRatio();})
                        ->save(public_path('../storage/app/public/mainImages/' . $BaseFileName ) );
    }


}
