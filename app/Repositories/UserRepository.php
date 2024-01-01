<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * スタイリスト設定に関するレポジトリクラスです
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * 対象IDのユーザーを取得する
     * @param array $userIds
     * @see UserRepositoryInterface::getByIds
     */
    public function getByIds(array $userIds): Collection
    {
        return User::select('*')->whereIn('id', $userIds)->get();
    }

    /**
     * お知らせ発信先のユーザーを取得する
     * @return Collection
     */
    public function getNotifyUsers(): Collection
    {
        $authorityLevel = [
            User::AUTHORITY_LEVEL['FUTURE_JOIN'],
            User::AUTHORITY_LEVEL['ENROLLMENT'],
            User::AUTHORITY_LEVEL['VACATION'],
        ];

        return User::whereIn('authority_level', $authorityLevel)
            ->get();
    }
}
