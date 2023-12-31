<?php

namespace App\Repositories;

use App\Consts\DatabaseConst;
use App\Models\User;
use App\Consts\ShopSettingConst;
use Illuminate\Database\Eloquent\Collection;

/**
 * スタイリスト設定に関するレポジトリクラスです
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * @param array $user_ids
     * @see UserRepositoryInterface::getByIds
     */
    public function getByIds(array $user_ids): Collection
    {
        return User::select('*')->whereIn('id', $user_ids)->get();
    }
}
