<?php

namespace App\Http\Controllers;

use App\Models\InstructorCourse;
use App\Models\InstructorCourseSchedule;
use App\Models\CustomerSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Mail;



class CourseDetailController extends Controller
{

    protected $users;
    private $_user;                 //Auth::user()
    private $_auth_id ;             //Auth::user()->id;
    private $_auth_authority_id ;   //権限
    private $_toInfo ;
    private $_toReon ;
    private $_instructor_courses ;
    private $_customerCourseMapping ;

    public function __construct(){
        $this->middleware(function ($request, $next) {
            $this->_user = \Auth::user();
            $this->_auth_id = $this->_user->id;
            $this->_auth_authority_id = $this->_user->authority_id;
            if($this->_auth_authority_id >= 8){
                dd("権限がありません。");
            }
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
        dd('更新画面作成中です');
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

    public function display($id){
        try{
            $IC = InstructorCourse::select('instructor_courses.*', 'courses.course_name')
                ->join('courses', 'courses.id', '=', 'instructor_courses.course_id' )
                ->find($id);

                // 管理者以下の権限の場合、自分のコースかを確認
            if( $this->_auth_authority_id >= 5 && $IC->instructor_id <> $this->_auth_id ) throw new \Exception("このスケジュールは更新できません");

            $ICS = InstructorCourseSchedule::where('instructor_courses_id', $id)->get();
            foreach($ICS as $data){
                $ID[] = $data->id;
            }
            $idLists =  explode(",", implode(",", $ID));

            $customer_schedules = CustomerSchedule::select('customer_schedules.*', 'instructor_course_schedules.howMany', 'customers.name')
            ->join('instructor_course_schedules', 'instructor_course_schedules.id', 'customer_schedules.course_schedules_id')
            ->join('customers', 'customers.id', 'customer_schedules.customer_id')
            ->whereIn('course_schedules_id', $idLists )
            ->orderBy('customer_schedules.date_time')
            ->orderBy('customer_schedules.customer_id')
            ->get();

            return view('course_detail.display', [ 'IC'=>$IC, 'ICS'=>$ICS, 'customer_schedules'=>$customer_schedules ]);

        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * 顧客のスケジュールを受講済みに更新する
     */
    public function completCustomerSchedule($id){
        try{
            $CusS =CustomerSchedule::find($id);

            // 権限が無ければ自分のScheduleか確認
            if( $this->_auth_authority_id >= 5 ){
                if($CusS->instructor_id <> $this->_auth_id)throw new \Exception("このスケジュールは更新できません");
            }

            // 受講済みに更新
            $CusS->status = 1;
            $CusS->save();

            // 顧客がコースのスケジュールを全て受講済みか確認
            $result = $this->check_scheduleComplete($CusS);

            // 全て受講済みの場合
            if(!$result){
                $id = $this->_customerCourseMapping->instructor_courses_id;
                $customer_id = $this->_customerCourseMapping->customer_id;
                // マッピングのstatusを5に更新する
                $update_result  = DB::table('customer_course_mapping')
                    ->where('customer_id', $customer_id )
                    ->where('instructor_courses_id', $id )
                    ->update(['status' => 5]);
                if(!$update_result) throw new \Exception("顧客のSchedule更新に失敗しました。管理者にご連絡ください");

                // レポートメールを送信する
                $data = [
                    "course"     => $this->_instructor_courses,
                    "mapping"     => $this->_customerCourseMapping,
                    "url"        => url('').'/home'
                ];
                Mail::send('emails.reportMail_completeIntrCourse', $data, function($message){
                    $message->to($this->_toInfo)
                    ->bcc($this->_toReon)
                    ->subject('お客様がコースを終了しました。');
                });
            }

            // 元の画面へリダイレクトする
            session()->flash('msg_success', '受講済みに更新しました。');
            $previousUrl = app('url')->previous();
            return redirect($previousUrl);
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * 渡されたカスタマースケジュールからその顧客のスケジュールが全て終了したか確認する
     */
    public function check_scheduleComplete($CusS){

        $customer_id = $CusS->customer_id;     //顧客ID
        $ICS         = InstructorCourseSchedule::find( $CusS->course_schedules_id ); // instructor_course_schedules
        $IC_id       = $ICS->instructor_courses_id;  //instructor_courses.id
        $ICS_List    = InstructorCourseSchedule::where('instructor_courses_id', $IC_id)->get(); // instructor_course_schedulesのリストを取得
        $this->_instructor_courses = InstructorCourse::select('instructor_courses.*', 'users.name', 'courses.course_name' )
                                    ->join('users', 'users.id', 'instructor_courses.instructor_id')
                                    ->join('courses', 'courses.id', 'instructor_courses.course_id')
                                    ->find($IC_id);

        $this->_customerCourseMapping =  DB::table('customer_course_mapping')->select('customer_course_mapping.*', 'customers.name')
                                    ->where('customer_id',  $customer_id )
                                    ->where('instructor_courses_id',  $IC_id )
                                    ->join('customers', 'customers.id', 'customer_course_mapping.customer_id' )
                                    ->first();

        foreach($ICS_List as $data){   //取得したinstructor_course_schedulesのIDだけ取得
            $ID[] = $data->id;
        }
        $idLists =  explode(",", implode(",", $ID)); // IDを配列に変換

        // instructor_courses → instructor_course_schedules に紐づいた顧客のcustomer_schedulesを取得
        $status_List = CustomerSchedule::select('status')
            ->where('customer_id', $customer_id)
            ->whereIn('course_schedules_id', $idLists )->get()->toArray();

        foreach($status_List as $data){    //customer_schedulesのステータスを取得
            $status[] = $data['status'];
        }

        // statusに未受講があるか確認
        $result = in_array(0, $status);
        return $result;
    }

}
