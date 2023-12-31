<?php

namespace App\Services;

use App\Models\{ReserveInfo};
use App\Repositories\UserRepository;
use Carbon\Carbon;
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
     * @param object $list
     * @return Collection
     */
    public function getByIds(array $user_ids): Collection
    {
        return $this->userRepository->getByIds($user_ids);
    }
}
