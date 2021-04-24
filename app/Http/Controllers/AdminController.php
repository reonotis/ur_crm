<?php

namespace App\Http\Controllers;

use App\Models\ApprovalComments;
use App\Models\Course;
use App\Models\InstructorCourse;
use App\Models\InstructorCourseSchedule;
use App\Models\CustomerCourseMapping;
use App\Services\CheckCouses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;

class AdminController extends Controller
{
    private $_user;
    protected $_auth_id ;
    protected $_auth_authority_id ;
    public $_backslash = '\\';
    private $_toAkemi ;
    private $_toInfo ;
    private $_toReon ;

    public function __construct(){
        $this->middleware(function ($request, $next) {
            $this->_user = \Auth::user();
            $this->_auth_id = $this->_user->id;
            $this->_auth_authority_id = $this->_user->authority_id;
            if($this->_auth_authority_id >= 5){
                dd("権限がありません。");
            }
            $this->_toAkemi = config('mail.toAkemi');
            $this->_toInfo = config('mail.toInfo');
            $this->_toReon = config('mail.toReon');
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.index');
    }

    /**
     * 
     */
    public function customer_complet_course()
    {
        $CCMs = CustomerCourseMapping::select('customer_course_mapping.*', 'customers.name')
        ->join('customers', 'customers.id', 'customer_id')
        ->where('status', 5)
        ->get();
        
        return view('admin.customer_complet_course', ['CCMs' => $CCMs, 'a' => 1]);
    }
    
    /**
     * 
     */
    public function unPayd()
    {
        $CCMs = CustomerCourseMapping::select('customer_course_mapping.*', 'customers.name', 'courses.course_name')
        ->join('customers', 'customers.id', 'customer_course_mapping.customer_id')
        ->join('instructor_courses', 'instructor_courses.id', 'customer_course_mapping.instructor_courses_id')
        ->join('courses', 'courses.id', 'instructor_courses.course_id')
        ->where('pay_confirm', 0)
        ->get();
        
        return view('admin.unpaid_customer', ['CCMs' => $CCMs, 'a' => 1]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
