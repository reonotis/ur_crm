<?php

namespace App\Http\Controllers;

use App\Models\ApprovalComments;
use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\CourseScheduleList;
use App\Models\WPMySchedule;
use App\Services\CheckCouses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{

    private $_user;
    protected $_auth_id ;
    protected $_auth_authority_id ;
    public $_backslash = '\\';

    public function __construct(){
        $this->middleware(function ($request, $next) {
            $this->_user = \Auth::user();
            $this->_auth_id = $this->_user->id;
            $this->_auth_authority_id = $this->_user->authority_id;
            if($this->_auth_authority_id >= 7){
                dd("権限がありません。");
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $courseSchedules = CourseSchedule::select('course_schedules.*','courses.course_name','users.name','course_schedule_lists.date1','course_schedule_lists.time1')
        ->where('course_schedules.approval_flg', '<', '5' )
        ->where('course_schedules.delete_flag', '0' )
        ->join('courses','courses.id','=','course_schedules.course_id')
        ->join('users','users.id','=','course_schedules.instructor_id')
        ->leftJoin('course_schedule_lists','course_schedule_lists.id','=','course_schedules.id')
        ->orderBy('course_schedules.date', 'asc')
        ->get();
        $courseSchedules = $this->getApprovalNames($courseSchedules);
        $courseSchedules = $this->setStartDate($courseSchedules);

        return view('approval.index', ['courseSchedules' => $courseSchedules]);
    }

    /**
     *承認状態を確認して承認名を付与する
     */
    public function getApprovalNames($datas){
        if(empty($datas))throw new \Exception("コースが取得できていません。");
        foreach($datas as $data ){
            $this->getApprovalName($data);
        }
        return $datas;
    }

    /**
     *承認状態を確認して承認名を付与する
     */
    public function getApprovalName($data){
        if(empty($data))throw new \Exception("コースが取得できていません。");
            switch ($data->approval_flg) {
                case '0':
                    $data->approval_name = '申請中';
                    break;
                case '1':
                    $data->approval_name = '差し戻し';
                    break;
                case '2':
                    $data->approval_name = '申請中';
                    break;
                case '5':
                    $data->approval_name = '受理済み';
                    break;
                default:
                    $data->approval_name = '--';
                    break;
            }
        return $data;
    }

    /**
     * 養成講座の開講日を初日に設定する
     */
    public function setStartDate($courseSchedules){
        foreach($courseSchedules as $courseSchedule){
            if($courseSchedule->course_id==6){
                $courseSchedule->date=$courseSchedule->date1;
                $courseSchedule->time=$courseSchedule->time1;
            }
        }
        return $courseSchedules;
    }

    /**
     *渡されたIDのスケジュールを確認し、パラコースか、養成コースの確認画面を表示する
     */
    public function confilm($id){
        try {
            $PCS = CourseSchedule::find($id);
            if(empty($PCS))throw new \Exception("対象のデータがありません");
            if($PCS->course_id == 6){
                return redirect()->action('ApprovalController@confilmIntrCourse', ['id' => $id ]);
            }else{
                return redirect()->action('ApprovalController@confilmParaCourse', ['id' => $id ]);
            }
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * パラリンビクス講座申請画面を表示します。
     */
    public function confilmParaCourse($id){
        try {
            $PCS = $this->getParaCouse($id);
            $ApprovalComments = ApprovalComments::where('course_schedules_id', $id )->get();
            if(empty($PCS))throw new \Exception("対象のデータがありません");
            return view('approval.paraShow', ['courseSchedules' => $PCS, 'ApprovalComments'=> $ApprovalComments]);
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * パラリンコースの詳細を取得
     */
    public function getParaCouse($id){
        $courseSchedules = CourseSchedule::select('course_schedules.*','courses.course_name','users.name')
        ->where('course_schedules.delete_flag', '0' )
        ->where('course_schedules.id', $id )
        ->join('courses','courses.id','=','course_schedules.course_id')
        ->join('users','users.id','=','course_schedules.instructor_id')
        ->first();

        $courseSchedules = CheckCouses::setApprovalName($courseSchedules);
        return $courseSchedules ;
    }

    /**
     * 養成講座申請画面を表示します。
     */
    public function confilmIntrCourse($id){
        dd('ページ未作成');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        try {
            $ApprovalComment = ApprovalComments::where('course_schedules_id', $id )->get();
            if(empty($request->appComment))throw new \Exception("コメントが入力されていません");
            $IAC = new ApprovalComments;
            $IAC->course_schedules_id = $id;
            $IAC->comment = $request->appComment;
            $IAC->save();
            $course_schedules = CourseSchedule::find($id );

            if($request->NG){
                session()->flash('msg_success', '申請を差し戻しました');
                $course_schedules->approval_flg = 1 ;
                $course_schedules->save() ;
            }

            if($request->OK){

                $this->register_WP_courseSchedules($id,$course_schedules);
                $course_schedules->approval_flg = 5 ;
                $course_schedules->save() ;
                session()->flash('msg_success', '承認しました');
            }
            return redirect()->action('ApprovalController@index');
        } catch (\Throwable $e) {
            // session()->flash('msg_danger',$e->getMessage() );
            // return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        //
    }

    /**
     *
     */
    public function register_WP_courseSchedules($id,$course_schedules){
        $lastID = DB::connection('mysql_2')->table('my_schedule')->insertGetId([
            'date' => $course_schedules->date,
            'open_time' => $course_schedules->time,
            'course_id' => $course_schedules->course_id,
            'price' => $course_schedules->price,
            'instructor_id' => $course_schedules->instructor_id,
            'open_flg' => 0
        ]);
        dd( $lastID);

    }
}
