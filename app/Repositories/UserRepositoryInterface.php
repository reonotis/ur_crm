<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\{ShopConfig, User};

interface UserRepositoryInterface
{
    /**
     * @param array $user_ids
     * @return Collection
     */
    public function getByIds(array $user_ids): Collection;
}
