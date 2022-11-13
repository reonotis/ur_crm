<?php

namespace App\Http\Controllers;

use App\Consts\SessionConst;
use App\Models\Notice;
use App\Models\NoticesStatus;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Services\CheckData;

class HomeController extends UserAppController
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     */
    public function index()
    {

        $notices = NoticesStatus::select('notices.title', 'notices_statuses.id', 'notices_statuses.notice_status', 'notices_statuses.created_at')
        ->join('notices', 'notices.id', 'notices_statuses.notice_id')
        ->where('notices_statuses.user_id', $this->loginUser->id)
        ->orderBy('notices.created_at', 'desc')
        ->get();

        return view('home', compact('notices'));
    }

}
