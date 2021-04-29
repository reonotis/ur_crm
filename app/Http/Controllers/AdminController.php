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
        ->where('status', '>=',5)
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
     * 
     */
    public function completeContract($id)
    {
        try {
            DB::beginTransaction();
            $CCM = CustomerCourseMapping::find($id);
            $CCM->status = 7;
            $CCM->save();
            // throw new \Exception("強制修了");
            DB::commit();
            session()->flash('msg_success', 'ステータスを契約完了にしました。');
            return redirect()->back();    // 前の画面へ戻る
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }

    }

    public function confirmedPaymentCourseFee($id)
    {
        try {
            DB::beginTransaction();
            $CCM = CustomerCourseMapping::find($id);
            $CCM->pay_confirm = 1;
            $CCM->status = 3;
            $CCM->save();
            // throw new \Exception("強制修了");
            DB::commit();
            session()->flash('msg_success', '入金確認が完了にしました。');

            return redirect()->action('CustomerController@display', ['id' => $CCM->customer_id, 'a' => 1]);
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }
}
