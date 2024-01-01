<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\NoticeRequest;
use App\Models\Notice;
use App\Models\NoticeStatus;
use App\Repositories\NoticeRepository;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * お知らせに関するサービスクラス
 *
 */
class NoticeService
{
    /** @var NoticeRepository $noticeRepository */
    private $noticeRepository;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->noticeRepository = app(NoticeRepository::class);
    }

    /**
     * 対象ユーザーのお知らせを取得する
     * @param int $userId
     * @return LengthAwarePaginator
     */
    public function getNoticesByUserId(int $userId): LengthAwarePaginator
    {
        return $this->noticeRepository->getNoticesByUserId($userId);
    }

    /**
     * お知らせが未読だった場合に既読にする
     * @param int $noticeId
     * @param int $userId
     * @return void
     */
    public function markRead(int $noticeId, int $userId): void
    {
        $status = $this->noticeRepository->getMyNoticeStatus($noticeId, $userId);

        // 未読だったら既読にする
        if ($status->notice_status == NoticeStatus::NOTICE_STATUS['UNREAD']) {
            $this->noticeRepository->updateMarkRead($status);
        }
    }

    /**
     * お知らせを登録する
     * @param NoticeRequest $request
     * @return Notice
     */
    public function createNotice(NoticeRequest $request): Notice
    {
        $property = [
            'title' => $request->title,
            'comment' => $request->comment,
        ];
        return $this->noticeRepository->createNotice($property);
    }

    /**
     * ユーザー分の既読ステータスを作成する
     * @param int $noticeId
     * @param array $userIds
     * @return void
     */
    public function registerNoticeStatuses(int $noticeId, array $userIds)
    {
        $params = [];
        foreach ($userIds as $userId) {
            $params[] = [
                'notice_id' => $noticeId,
                'user_id' => $userId,
                'notice_status' => NoticeStatus::NOTICE_STATUS['UNREAD'],
            ];
        }

        $this->noticeRepository->registerNoticeStatuses($params);
    }
}
