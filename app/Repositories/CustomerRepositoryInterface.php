<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\{ReserveInfo};
use Illuminate\Database\Eloquent\Collection;

interface CustomerRepositoryInterface
{
    /**
     * @param int $shop_id
     * @return mixed
     */
    public function getTodayVisitHistory(int $shop_id): Collection;

}
