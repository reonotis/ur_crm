<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\NoticesStatus;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Services\CheckData;

class HomeController extends Controller
{
    private $_user;                 //Auth::user()
    private $_auth_id ;             //Auth::user()->id;
    private $_auth_authority_id ;   //権限

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware(function ($request, $next) {
            $this->_user = \Auth::user();
            $this->_auth_id = $this->_user->id;
            $this->_auth_authority_id = $this->_user->authority_id;
            if($this->_auth_authority_id >= 8){
                session()->flash('msg_danger', '権限がありません');
                Auth::logout();
                return redirect()->intended('/');
            }
            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $notices = NoticesStatus::select('notices.title', 'notices_statuses.id', 'notices_statuses.notice_status', 'notices_statuses.created_at')
        ->join('notices', 'notices.id', 'notices_statuses.notice_id')
        ->where('notices_statuses.user_id', $this->_user->id)
        ->where('notices.delete_flag', 0)
        ->where('notices_statuses.delete_flag', 0)
        ->orderBy('notices.created_at', 'desc')
        ->get();

        return view('home', compact('notices'));
    }
}
