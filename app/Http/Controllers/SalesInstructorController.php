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
                dd("権限がありません。");
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
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function courseMappingShow($id)
    {
        $CCMs = CustomerCourseMapping::select('customer_course_mapping.*', 'customers.name', 'courses.course_name', 'users.name AS intr_name')
        ->join('customers', 'customers.id', 'customer_course_mapping.customer_id')
        ->join('instructor_courses', 'instructor_courses.id', 'customer_course_mapping.instructor_courses_id')
        ->join('courses', 'courses.id', 'instructor_courses.course_id')
        ->join('users', 'users.id', 'customer_course_mapping.instructor_id')
        ->find($id);
        $ICS = InstructorCourseSchedule::where('instructor_courses_id',$CCMs->instructor_courses_id )->orderBy('date')->first();
        $firstDate = $ICS->date;

        $claim_ID = $CCMs->claim_id;
        // claimsのデータがなければ作成する
        if(!$claim_ID){
            $claim = new Claim;
            $claim->user_type = 1 ;
            $claim->user_id = $CCMs->customer_id;
            $claim->price = $CCMs->price;
            $claim->save();
            $claim_ID = $claim->id;
            CustomerCourseMapping::where('id', $id)->update(['claim_id' => $claim_ID]);
        }
        $claim = Claim::find($claim_ID);
        $claim = CheckClaims::setStatus($claim);

        return view('admin.courseMappingShow', compact('CCMs','claim','firstDate') );
        //
    }

}
