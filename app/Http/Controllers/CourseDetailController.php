<?php

namespace App\Http\Controllers;

use App\Models\InstructorCourse;
use App\Models\InstructorCourseSchedule;
use App\Models\CustomerSchedule;
use Illuminate\Http\Request;


class CourseDetailController extends Controller
{

    /**
     * ユーザーリポジトリインスタンス
     */
    protected $users;
    private $_user;                 //Auth::user()
    private $_auth_id ;             //Auth::user()->id;
    private $_auth_authority_id ;   //権限

    public function __construct(){
        $this->middleware(function ($request, $next) {
            $this->_user = \Auth::user();
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

    public function completCustomerSchedule($id){
        try{
            $CS =CustomerSchedule::find($id);

            // 権限が無ければ自分のScheduleか確認
            if( $this->_auth_authority_id >= 5 ){
                if($CS->instructor_id <> $this->_auth_id)throw new \Exception("このスケジュールは更新できません");
            }
            //
            
            // 受講済みに更新
            $CS->status = 1;
            $CS->save();

            // 顧客がコースのスケジュールを全て受講済みになったらmappingを完了に更新する

            throw new \Exception("メール機能がまだ作成されていません");
            // 元の画面へリダイレクトする
            session()->flash('msg_success', '受講済みに更新しました。');
            $previousUrl = app('url')->previous();
            return redirect($previousUrl);
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }


}
