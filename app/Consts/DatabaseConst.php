<?php

namespace App\Consts;

class DatabaseConst
{
    // フラグ
    public const FLAG_OFF = 0;
    public const FLAG_ON = 1;

    // visit_reserves
    public const VISIT_RESERVES_STATUS_RESERVE = 0;
    public const VISIT_RESERVES_STATUS_VISIT = 1;
    public const VISIT_RESERVES_STATUS_DELETE = 9;

    // visit_histories
    public const VISIT_HISTORY_STATUS_VISITED = 1;
    public const VISIT_HISTORY_STATUS_CANCEL = 9;


}
