<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\{ReserveInfo};
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 *
 */
class UserService
{
    /** @var UserRepository $userRepository */
    private $userRepository;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->userRepository = app(UserRepository::class);
    }

    /**
     * 対象IDのユーザーを取得する
     * @param array $user_ids
     * @return Collection
     */
    public function getByIds(array $userIds): Collection
    {
        return $this->userRepository->getByIds($userIds);
    }

    /**
     * お知らせ発信先のユーザーを取得する
     * @return Collection
     */
    public function getNotifyUsers(): Collection
    {
        return $this->userRepository->getNotifyUsers();
    }
}
