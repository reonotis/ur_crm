<?php

namespace App\Http\Controllers;

use App\Models\CustomerCourseMapping;
use App\Models\Claim;
use App\Models\InstructorCourseSchedule;
use App\Services\CheckClaims;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class SalesInstructorController extends Controller
{
    private $_user;                 //Auth::user()
    private $_auth_id ;             //Auth::user()->id;
    private $_auth_authority_id ;   //権限

    public function __construct(){
        $this->middleware(function ($request, $next) {
            $this->_user = Auth::user();
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
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sales.index');
    }

    public function list()
    {
        // 過去12か月分のデータを作成
        $arryNo = 0;
        for($i = -1; $i >= -12; $i--){
            $months[$arryNo]['month'] = date("Y-m",strtotime($i ." month"));
            $months[$arryNo]['displayName'] = date("Y年 m月分",strtotime($i ." month"));
            $arryNo ++ ;
        }
        return view('sales.index', compact('months') );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($month)
    {

        dd($month , $this->_auth_id);
        return view('sales.show', compact('month') );
        //
    }
}
