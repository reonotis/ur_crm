<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\AuthHelpers;
use App\Services\NoticeService;

class HomeController extends UserAppController
{
    /** @var NoticeService $noticeService */
    private $noticeService;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
        $this->noticeService = app(NoticeService::class);
    }

    /**
     * ホーム画面を表示する
     */
    public function index()
    {
        // お知らせを取得
        $noticeStatuses = $this->noticeService->getNoticesByUserId($this->loginUser->id);

        // お知らせを作成する権限があるか確認する
        $noticeCreateAuth = AuthHelpers::checkHavePermissions('notice');
        return view('home', compact('noticeStatuses', 'noticeCreateAuth'));
    }
}
