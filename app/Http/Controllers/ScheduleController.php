<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\InstructorCourseSchedule;
use App\Models\CustomerSchedule;
// use DateTime;


class ScheduleController extends Controller
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
     * リストメソッドに現在の年月を渡す
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $DATE = date('Y-m-d');
        return redirect()->action('ScheduleController@list' );
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

    /**
     * スケジュールのリストを表示します
     *
     */
    public function list()
    {
        $DATE = date('Y-m-d');
        $month = substr($DATE, 0, 7);
        if(isset($_GET['month']))$month = $_GET['month'];
        $NISSUU = (date('t', strtotime($month)));

        // customer_schedulesのサブquery作成

        $CSQuery = CustomerSchedule::select('customer_id', 'course_schedules_id', 'instructor_id','date_time', 'howMany')
            ->where('date_time', 'LIKE', "$month%")
            ->where('delete_flag', 0);
        if($this->_auth_authority_id >= 5 ) $CSQuery->where('instructor_id', $this->_auth_id);
        // $CS = $CSQuery->get();

        // InstructorCourseScheduleのサブqueryと各テーブルを結合
        $ICSQuery = InstructorCourseSchedule::select(DB::raw('count(CS.customer_id) as NINZUU') ,
                                                    'instructor_courses.id',
                                                    'instructor_course_schedules.date',
                                                    'CS.date_time',
                                                    'instructor_course_schedules.howMany',
                                                    'courses.id AS courses_id',
                                                    'courses.course_name',
                                                    'users.name'
                                                    )
        ->leftJoinSub($CSQuery, 'CS', 'CS.course_schedules_id', 'instructor_course_schedules.id')
        ->join('instructor_courses', 'instructor_courses.id', 'instructor_course_schedules.instructor_courses_id')
        ->join('courses', 'courses.id', 'instructor_courses.course_id')
        ->join('users', 'users.id', 'instructor_course_schedules.instructor_id')
        ->where('instructor_course_schedules.date', 'LIKE', "$month%")
        ->where('instructor_course_schedules.delete_flag', 0)
        ->groupBy('course_schedules_id', 'date_time','instructor_course_schedules.id')
        ;
        if($this->_auth_authority_id >= 5 ) $ICSQuery->where('instructor_course_schedules.instructor_id', $this->_auth_id);
        $results = $ICSQuery->get();

        $schedules = $this->correct_schedules($results);

        $monthData = [];
        $day = 1;
        for($i = 0; $i < $NISSUU; $i++){
            $monthData[$i]['date'] = date('Y年m月d日', strtotime( $month ."-" . sprintf('%02d', $day)));
            $monthData[$i]['week'] = date('D', strtotime( $month ."-" . sprintf('%02d', $day)));
            if( $schedules ){
                foreach( $schedules as $schedule){
                    if( $schedule['date'] == date('Y年m月d日', strtotime( $month ."-" . sprintf('%02d', $day))) ){
                        $monthData[$i]['schedules'][] = $schedule;
                    }
                }
            }
            $day ++;
        }
        $monthList[0] =  date('Y-m', strtotime(date($month.'-1') . '-1 month'));
        $monthList[1] =  $month;
        $monthList[2] =  date('Y-m', strtotime(date($month.'-1') . '+1 month'));

        return view('schedule.list', [ 'monthList' => $monthList, 'monthData' => $monthData]);
    }

    /**
     * 取得したスケジュールを分かりやすく更新する
     */
    public function correct_schedules($results){
        $count = 0;
        foreach($results as $result){
            $schedules[$count]["id"]        = $result->id;
            $schedules[$count]["NINZUU"]    = $result->NINZUU . "人";

            // 養成講座だったらcourse_nameに回数を付ける
            if($result->courses_id == 6){
                $schedules[$count]["course_name"] = $result->course_name . " " . $result->howMany . "回目" ;
            }else{
                $schedules[$count]["course_name"] = $result->course_name;
            }

            // customer_schedules.date_time が紐づいている場合と、紐づいていない場合で日時のフォーマットを合わせる
            if($result->date_time){
                $schedules[$count]["date"]      = date('Y年m月d日', strtotime($result->date_time)) ;
                $schedules[$count]["time"]      = date('H:i', strtotime($result->date_time)) ;
            }else{
                $schedules[$count]["date"]      = $result->date->format('Y年m月d日');
                $schedules[$count]["time"]      = $result->date->format('H:i');
            }

            $schedules[$count]["text"] = $schedules[$count]["time"] ."～　";
            // 権限があればインストラクター名を付与する
            if($this->_auth_authority_id <= 5){
                $schedules[$count]["text"] .= $schedules[$count]["course_name"] ."　".  $result->name."　" ;
            }else{
                $schedules[$count]["text"] = $schedules[$count]["course_name"] ."　" ;
            }
            $count ++;
        }
        if(!isset($schedules)) $schedules = false ;
        return $schedules;
    }



}
