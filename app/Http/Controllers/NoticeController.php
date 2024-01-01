<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Consts\SessionConst;
use App\Http\Requests\NoticeRequest;
use App\Models\Notice;
use App\Services\NoticeService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class NoticeController extends UserAppController
{
    /** @var NoticeService $noticeService */
    private $noticeService;

    /** @var UserService $userService */
    private $userService;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
        $this->noticeService = app(NoticeService::class);
        $this->userService = app(UserService::class);
    }

    /**
     * お知らせ詳細画面を表示
     * @param Notice $notice
     * @return View
     */
    public function show(Notice $notice): View
    {
        // 既読処理
        $this->noticeService->markRead($notice->id, $this->loginUser->id);

        return view('notice.show', compact('notice'));
    }

    /**
     * お知らせ登録画面を表示
     * @return View
     */
    public function create(): View
    {
        return view('notice.create');
    }

    /**
     * お知らせを登録
     * @param NoticeRequest $request
     * @return RedirectResponse
     */
    public function register(NoticeRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            // お知らせを登録
            $notice = $this->noticeService->createNotice($request);

            // お知らせするユーザーを取得
            $users = $this->userService->getNotifyUsers();
            $userIds = $users->pluck('id')->all();

            // ユーザー分の既読ステータスを作成する
            $this->noticeService->registerNoticeStatuses($notice->id, $userIds);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['お知らせの登録に失敗しました'])->withInput();
        }
        return redirect()->route('home')->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['お知らせを登録しました']);
    }
}
