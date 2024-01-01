<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Notice;
use App\Models\NoticeStatus;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * お知らせに対するレポジトリクラスです
 */
class NoticeRepository implements NoticeRepositoryInterface
{
    /**
     * 対象ユーザーのお知らせを取得する
     * @param int $user_id
     * @return LengthAwarePaginator
     * @see NoticeRepositoryInterface::getNoticesByUserId
     */
    public function getNoticesByUserId(int $user_id): LengthAwarePaginator
    {
        return NoticeStatus::where('notice_statuses.user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    /**
     * 対象ユーザー × 対象お知らせのステータスを取得する
     * @param int $noticeId
     * @param int $userId
     * @return NoticeStatus
     * @see NoticeRepositoryInterface::getMyNoticeStatus
     */
    public function getMyNoticeStatus(int $noticeId, int $userId): NoticeStatus
    {
        return NoticeStatus::where('notice_id', $noticeId)
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * 既読に更新する
     * @param NoticeStatus $status
     * @return void
     * @see NoticeRepositoryInterface::updateMarkRead
     */
    public function updateMarkRead(NoticeStatus $status): void
    {
        $status->notice_status = NoticeStatus::NOTICE_STATUS['ALREADY_READ'];
        $status->read_at = new Carbon();
        $status->save();
    }

    /**
     * お知らせを登録する
     * @param array $property
     * @return Notice
     */
    public function createNotice(array $property): Notice
    {
        return Notice::create($property);
    }

    /**
     * ユーザー分の既読ステータスを作成する
     * @param array $params
     * @return void
     */
    public function registerNoticeStatuses(array $params): void
    {
        NoticeStatus::insert($params);
    }
}
