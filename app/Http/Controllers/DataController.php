<?php

namespace App\Http\Controllers;

use App\Consts\{ErrorCode, DataAnalyze, SessionConst};
use App\Exceptions\ExclusionException;
use App\Models\{Customer, UserShopAuthorization, VisitHistory};
use App\Services\DateCheckService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class DataController extends UserAppController
{
    /**
     * @var DateCheckService $DateCheckService
     */
    public $DateCheckService;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
        $this->DateCheckService = new DateCheckService();
    }

    /**
     * @param string $Ymd
     * @return \Illuminate\View\View
     * @throws ExclusionException
     */
    public function data(string $Ymd): View
    {
        $date = $this->DateCheckService->validateYMD($Ymd);
        $isLess = $this->DateCheckService->checkLess($date);
        if (!$isLess) {
            $this->goToExclusionErrorPage(ErrorCode::INVALID_DATE, [$Ymd]);
        }

        return view('data.index');
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
        $analyzeType = DataAnalyze::ANALYZE_TYPE_LIST[$type];
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
                return VisitHistory::getByTargetPeriod($fromDate, $endDate, $shopId)->get();
            case DataAnalyze::ANALYZE_TYPE_STYLIST:
            case DataAnalyze::ANALYZE_TYPE_MENU:
                break;
            default:
                Log::error('不正な値が渡されました' . $type);
        }
        return $data;
    }

}
