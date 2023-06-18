<?php

namespace App\Http\Controllers;

use App\Consts\{ErrorCode, DataAnalyze, SessionConst};
use App\Exceptions\ExclusionException;
use App\Models\{Customer, UserShopAuthorization, VisitHistory};
use App\Services\{DateCheckService, VisitHistoryService};
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class DataController extends UserAppController
{
    /**
     * @var DateCheckService $DateCheckService
     * @var VisitHistoryService $VisitHistoryService
     */
    public $DateCheckService, $VisitHistoryService;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
        $this->DateCheckService = new DateCheckService();
        $this->VisitHistoryService = new VisitHistoryService();
    }

    /**
     * @param string $Ymd
     * @param Request $request
     * @return \Illuminate\View\View
     * @throws ExclusionException
     */
    public function data(string $Ymd, Request $request): View
    {
        $date = $this->DateCheckService->validateYMD($Ymd);
        $isLess = $this->DateCheckService->checkLess($date);
        if (!$isLess) {
            $this->goToExclusionErrorPage(ErrorCode::INVALID_DATE, [$Ymd]);
        }

        if($request->back){
            $dataSearch = [
                'fromDate'=> Cache::get('data_search')['fromDate'],
                'endDate'=> Cache::get('data_search')['endDate'],
                'type'=> Cache::get('data_search')['type'],
            ];
        }else{
            $dataSearch = [
                'fromDate'=> $date->format('Y-m-d'),
                'endDate'=> $date->format('Y-m-d'),
                'type'=> 1,
            ];
        }


        return view('data.index', compact('dataSearch'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAnalyzed(Request $request)
    {
        $fromDate = $request->fromDate;
        $endDate = $request->endDate;
        $type = $request->type;
        if (isset(DataAnalyze::ANALYZE_TYPE_LIST[$type])) {
            $analyzeType = DataAnalyze::ANALYZE_TYPE_LIST[$type];
        } else {
            Log::error('データ分析時に不正な値が渡されました。type:' . $type . 'userId:' . $this->loginUser->id);
            return '';
        }

        // cashに登録しておく
        $search = [
            'fromDate' => $fromDate,
            'endDate' => $endDate,
            'type' => $type,
        ];
        Cache::put('data_search', $search);

        $shop = session(SessionConst::SELECTED_SHOP)->toArray();
        $shopId = $shop['id'];

        if (empty($fromDate) || empty($endDate) || empty($type)) {
            Log::error('getAnalyzed : データの受け取りに失敗しました。');
            return '';
        }

        Log::info('ajaxにより、' . $analyzeType . 'で' . $fromDate . '～' . $endDate . 'の' . $shop['shop_name'] . 'のデータを取得');

        $data = '';
        switch ($type) {
            case DataAnalyze::ANALYZE_TYPE_VISIT_HISTORY:
                return $this->VisitHistoryService->getByTargetPeriod($fromDate, $endDate, $shopId);
            case DataAnalyze::ANALYZE_TYPE_STYLIST:
                $tmlHistoryList = $this->VisitHistoryService->getByTargetPeriod($fromDate, $endDate, $shopId);
                return $this->VisitHistoryService->convert($tmlHistoryList);
            case DataAnalyze::ANALYZE_TYPE_MENU:
                break;
        }
        return $data;
    }

}

