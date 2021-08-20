<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\VisitHistory;
use App\Models\VisitHistoryImage;
use App\Models\Menu;
use InterventionImage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitHistoryController extends Controller
{

    private $_fileExtntion = ['jpg', 'jpeg', 'png'];
    private $_resize = '300';
    private $_user;                 //Auth::user()
    private $_auth_authority_id ;   //権限

    public function __construct(){
        $this->middleware(function ($request, $next) {
            $this->_user = \Auth::user();
            $this->_auth_authority_id = $this->_user->authority_id;
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     *
     */
    public function register($id)
    {
        try {
            DB::beginTransaction();

            $customer = Customer::find($id);
            // 顧客が存在するかcheckする
            if(empty($customer)){
                throw new \Exception('不正なパラメータが渡されました。');
            }
            if(empty($customer->staff_id)){
                throw new \Exception('スタイリストが設定されていません');
            }
            // 本日の来店履歴が既に登録されていないかcheckする
            $visitHistory = VisitHistory::where('customer_id', $id)
            ->where('vis_date', date('Y-m-d'))
            ->where('delete_flag', 0)
            ->first();
            if(!empty($visitHistory)){
                throw new \Exception("既に本日の来店履歴が登録されています。");
            }

            // 来店履歴を登録する
            VisitHistory::insert([[
                'vis_date'      => date('Y-m-d'),
                'vis_time'      => date('H:i:s'),
                'customer_id'   => $id,
                'shop_id'       => $this->_user->shop_id,
                'staff_id'      => $this->_user->id,
            ]]);
            DB::commit();

            session()->flash('msg_success', '本日の来店履歴を登録しました');
            // リダイレクトする。
            return redirect()->action('CustomerController@show',['id'=> $id ]);
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back()->withInput();    // 前の画面へ戻る
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $visit_histories = VisitHistory::select('visit_histories.*', 'customers.f_name', 'customers.l_name' )
        ->where('visit_histories.shop_id', $this->_user->shop_id)
        ->where('vis_date', date('Y-m-d'))
        ->join('customers', 'customers.id', '=', 'visit_histories.customer_id' )
        ->get();

        $users = User::where('shop_id', $this->_user->shop_id )->where('authority_id', '<=', '7')->where('authority_id', '>=', '3')->get();
        $menus = Menu::where('delete_flag', 0)->where('hidden_flag', 0)->orderBy('rank')->get();

        return view('report.edit',compact('visit_histories', 'users', 'menus' ));

        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function single_edit($id)
    {
        try {
            // データ取得
            $VisitHistory = VisitHistory::select('visit_histories.*', 'customers.member_number', 'customers.f_name', 'customers.l_name', 'shops.shop_name', 'angle1.img_pass as img_pass1', 'angle2.img_pass as img_pass2', 'angle3.img_pass as img_pass3' )
            ->join('customers', 'customers.id', '=', 'visit_histories.customer_id' )
            ->join('shops', 'shops.id', '=', 'visit_histories.shop_id' )
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
            ->find($id);

            // 編集して良いデータか確認する
            if(!$VisitHistory){
                throw new \Exception("来店データがありません");
            }
            if($VisitHistory->vis_date->format('Y-m-d') <> date('Y-m-d')){
                throw new \Exception("本日のデータではない為編集できません");
            }
            // 権限のない他店舗staffは編集できないようにチェックする
            if( $this->_user->authority_id >= 7 ){
                if($VisitHistory->shop_id <> $this->_user->shop_id ){
                    throw new \Exception("他店舗の来店情報を編集する権限がありません");
                }
            }


            $users = User::where('shop_id', $this->_user->shop_id )->where('authority_id', '<=', '7')->where('authority_id', '>=', '3')->get();
            $menus = Menu::where('delete_flag', 0)->where('hidden_flag', 0)->orderBy('rank')->get();
            return view('customer.single_edit',compact('VisitHistory', 'users', 'menus' ));
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     *
     */
    public function single_update(Request $request, $id)
    {
        try {
            $customer_id = $request->customer_id;
            if($request->image1) $this->_fileUpload($customer_id, $id, $request->image1, 1 );
            if($request->image2) $this->_fileUpload($customer_id, $id, $request->image2, 2 );
            if($request->image3) $this->_fileUpload($customer_id, $id, $request->image3, 3 );

            // ここからDB更新
            DB::beginTransaction();

            // VisitHistory
            $visitHistory = VisitHistory::find($id);
            $visitHistory->vis_time = $request->vis_time;
            $visitHistory->staff_id = $request->staff_id;
            $visitHistory->menu_id = $request->menu_id;
            $visitHistory->memo = $request->memo;
            $visitHistory->save();

            $visit_histories = VisitHistory::find($id);
            DB::commit();
            session()->flash('msg_success', '更新完了しました');
            return redirect()->action('CustomerController@show', ['id' => $visit_histories->customer_id]);
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     *
     * アングル毎に画像をアップロードする
     */
    public function _fileUpload($customer_id, $id, $file, $angle)
    {
        if(!$file)throw new \Exception("ファイルが指定されていません");

        // 登録可能な拡張子か確認して取得する
        $extension = $this->checkFileExtntion($file);

        // ファイル名の作成 => {日時} _ {来店履歴ID(7桁に0埋め)} _ {ユーザーID(5桁に0埋め)} _ 'ユニーク文字列' . {拡張子}
        $this->BaseFileName = sprintf(
            '%s_%s_%s_%s.%s',
            time(),
            str_pad($id, 7, 0, STR_PAD_LEFT),
            str_pad($this->_user->id, 5, 0, STR_PAD_LEFT),
            sha1(uniqid(mt_rand(), true)),
            $extension
        );

        // 画像を保存する
        $file->storeAs('public/customer_img', $this->BaseFileName);

        // VisitHistoryImage
        VisitHistoryImage::updateOrInsert(
            [
                'customer_id' => $customer_id,
                'visit_history_id' => $id,
                'angle' => $angle,
                'delete_flag' => 0,
            ],[
                'img_pass' => $this->BaseFileName,
            ]
        );

        // TODO 画像サイズを横幅500の比率にして保存する。
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
        return $file->extension();
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

        dd(1);
        // 正方形の画像を作成
        $square_image = InterventionImage::make($file)
            ->crop($length, $length);

        // リサイズして保存
        $image = InterventionImage::make($square_image)
            ->resize($this->_resize, null, function ($constraint) {$constraint->aspectRatio();})
            ->save(public_path('../storage/app/public/mainImages/' . $BaseFileName ) );
    }













    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updates(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $visitHistory = VisitHistory::find($id);
            // 更新して良いデータか確認する
            if(empty($visitHistory)) throw new \Exception("来店データが存在しません");
            if($visitHistory->vis_date->format('Y-m-d') <> date('Y-m-d') ) throw new \Exception("本日のデータではないため、更新できません。");

            // throw new \Exception("強制終了");
            // 更新処理
            $visitHistory->vis_time = $_GET['time'];
            $visitHistory->staff_id = $_GET['user'];
            $visitHistory->menu_id = $_GET['menu'];
            $visitHistory->save();
            DB::commit();

            $return = "成功";
            return response()->json($return);

        } catch (\Throwable $e) {
            DB::rollback();
            $return = [
                'fail',
                response()->json($e->getMessage()),
            ];
            return response()->json($return);
        }
    }

    /**
     * 来店履歴に紐づく画像（角度指定あり）を削除する
     *
     * @param [type] $id
     * @param [type] $angle
     * @return void
     */
    public function delete($id, $angle)
    {
        try {
            DB::beginTransaction();
            if(!$id || !$angle) throw new \Exception("パラメータがありません");

            $visitHistoryImage = VisitHistoryImage::where('visit_history_id', $id )
            ->where('angle', $angle)
            ->where('delete_flag', 0)
            ->first();
            if(!$visitHistoryImage) throw new \Exception("画像データがありません");

            $visitHistoryImage->delete_flag = 1;
            $visitHistoryImage->save();

            DB::commit();
            session()->flash('msg_success', '削除完了しました');
            return redirect()->action('VisitHistoryController@single_edit', ['id' => $id]);
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
        try {
            DB::beginTransaction();
            if(!$id) throw new \Exception("パラメータがありません");

            $visitHistory = VisitHistory::find($id);
            if(!$visitHistory) throw new \Exception("来店情報がありません");
            if($visitHistory->vis_date->format('Y-m-d') <> date('Y-m-d') ) throw new \Exception("本日のデータではありません");

            // 権限がないユーザーの場合
            if($this->_user->authority_id >= 8){
                // 多店舗の顧客を削除しようとしていないか
                if($this->_user->shop_id <> $visitHistory->shop_id){
                    throw new \Exception('多店舗の来店情報を削除する権限がありません');
                }
            }

            $visitHistory->delete_flag = 1;
            $visitHistory->save();

            DB::commit();
            session()->flash('msg_success', '削除完了しました');
            return redirect()->action('ReportController@index');
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }


}
