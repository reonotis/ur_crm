<?php

namespace App\Repositories;

use App\Models\Notice;
use App\Models\NoticeStatus;
use Illuminate\Pagination\LengthAwarePaginator;

interface NoticeRepositoryInterface
{
    /**
     * 対象ユーザーのお知らせを取得する
     * @param int $user_id
     * @return LengthAwarePaginator
     */
    public function getNoticesByUserId(int $user_id): LengthAwarePaginator;

    /**
     * 対象ユーザー × 対象お知らせのステータスを取得する
     * @param int $noticeId
     * @param int $userId
     * @return NoticeStatus
     */
    public function getMyNoticeStatus(int $noticeId, int $userId): NoticeStatus;

    /**
     * 既読に更新する
     * @param NoticeStatus $status
     * @return void
     */
    public function updateMarkRead(NoticeStatus $status): void;

    /**
     * お知らせを登録する
     * @param array $property
     * @return Notice
     */
    public function createNotice(array $property): Notice;

    /**
     * ユーザー分の既読ステータスを作成する
     * @param array $params
     * @return void
     */
    public function registerNoticeStatuses(array $params): void;
}
