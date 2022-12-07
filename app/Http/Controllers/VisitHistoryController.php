<?php

namespace App\Http\Controllers;

use App\Consts\ErrorCode;
use App\Consts\Common;
use App\Consts\SessionConst;
use App\Models\Customer;
use App\Models\Shop;
use App\Models\UserShopAuthorization;
use App\Models\VisitType;
use App\Models\VisitHistory;
use App\Models\VisitHistoryImage;
use App\Models\Menu;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InterventionImage;

class VisitHistoryController extends UserAppController
{
    private $_fileExtension = ['jpg', 'jpeg', 'png'];
    private $_resize_maxWidth = '300';
    private $_resize_maxHeight = '400';

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
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
     * 本日の来店履歴を登録する
     * @param Customer $customer
     * @return RedirectResponse
     */
    public function register(Customer $customer): RedirectResponse
    {
        if(count(VisitHistory::getTodayVisitHistoryByCustomerId($customer->id)->get())){
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['既に本日の来店履歴が登録されています']);
        }

        try {
            DB::beginTransaction();
            // 来店履歴を登録する
            VisitHistory::insert([[
                'vis_date' => Carbon::now()->format('Y-m-d'),
                'vis_time' => Carbon::now()->format('H:i:s'),
                'customer_id' => $customer->id,
                'shop_id' => $this->shopId,
                'user_id' => $this->loginUser->id,
            ]]);
            DB::commit();
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['本日の来店履歴を登録しました']);
        } catch (Exception $e) {
            DB::rollback();
            Log::error( ' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['来店履歴の登録に失敗しました']);
        }
    }

    /**
     * 編集画面を表示する
     *
     * @param VisitHistory $visitHistory
     * @return View
     */
    public function edit(VisitHistory $visitHistory): View
    {
        // 編集して良いかチェック
        $this->_checkEditing($visitHistory);

        $menus = Menu::where('hidden_flag', 0)->orderBy('rank')->get();
        $users = UserShopAuthorization::getSelectableUsers($this->shopId)->get();

        return View('visitHistory.edit', compact('visitHistory', 'users', 'menus'));
    }

    /**
     * 来店履歴を更新する
     * @param Request $request
     * @param visitHistory $visitHistory
     * @return RedirectResponse
     */
    public function update(Request $request, visitHistory $visitHistory)
    {
        // 編集して良いかチェック
        $this->_checkEditing($visitHistory);

        try {
            // TODO
            // $customer_id = $request->customer_id;
            // if($request->image1) $this->_fileUpload($customer_id, $id, $request->file('image1'), 1 );
            // if($request->image2) $this->_fileUpload($customer_id, $id, $request->image2, 2 );
            // if($request->image3) $this->_fileUpload($customer_id, $id, $request->image3, 3 );

            // ここからDB更新
            DB::beginTransaction();

            $visitHistory->vis_time = $request->vis_time;
            $visitHistory->user_id = $request->user_id;
            $visitHistory->menu_id = $request->menu_id;
            $visitHistory->memo = $request->memo;
            $visitHistory->save();

            DB::commit();
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['来店履歴を更新しました']);
        } catch (Exception $e) {
            DB::rollback();
            Log::error( ' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['来店履歴の更新に失敗しました'])->withInput();
        }
    }

    /**
     * @param VisitHistory $visitHistory
     * @return RedirectResponse
     */
    public function destroy(visitHistory $visitHistory): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $visitHistory->delete();
            DB::commit();
            return redirect()->route('report.index')->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['来店履歴を削除しました']);
        } catch (Exception $e) {
            Log::error( ' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['来店履歴の削除に失敗しました'])->withInput();
        }

    }

    /**
     * 渡されたファイルが登録可能な拡張子か確認するしてOKなら拡張子を返す
     */
    public function checkFileExtension($file){
        // 渡された拡張子を取得
        $extension =  $file->extension();
        if(! in_array($extension, $this->_fileExtension)){
            $fileExtension = json_encode($this->_fileExtension);
            throw new \Exception("登録できる画像の拡張子は". $fileExtension ."のみです。");
        }
        return $file->extension();
    }

    /**
     * 編集して良いかチェックする
     * @return void
     */
    private function _checkEditing(VisitHistory $visitHistory): void
    {
        // 本日の履歴ではない場合
        if(!Carbon::parse($visitHistory->vis_date)->isToday()){
            $this->goToExclusionErrorPage(ErrorCode::CL_040004, [
                $visitHistory->id,
                $this->loginUser->id,
            ]);
        }

        // TODO 対象の店舗でない場合



    }

    /**
     * アングル毎に画像をアップロードする
     *
     */
    private function _fileUpload($customer_id, $id, $file, $angle)
    {
        if(!$file)throw new \Exception("ファイルが指定されていません");

        // 登録可能な拡張子か確認して取得する
        $extension = $this->checkFileExtension($file);

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
        $file->storeAs(Common::CUSTOMER_IMG_RESIZE_DIR, $this->BaseFileName);

        // リサイズして保存する
        $resizeImg = InterventionImage::make($file)
            ->resize($this->_resize_maxWidth, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->orientate()
            ->save(storage_path('app/public/customer_img_resize/') . $this->BaseFileName);

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
    }

}
