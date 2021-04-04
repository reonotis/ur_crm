<?php

namespace App\Http\Controllers;

use App\Models\ApprovalComments;
use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\CourseScheduleList;
use App\Services\CheckCouses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;

class ApprovalController extends Controller
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
            if($this->_auth_authority_id >= 7){
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
    public function index(){
        $CourseSchedule = CourseSchedule::select('course_schedules.*', 'courses.course_name', 'users.name')
        ->join('courses','courses.id','=','course_schedules.course_id')
        ->join('users','users.id','=','course_schedules.instructor_id')
        ->where('course_schedules.delete_flag',NULL)
        ->where('course_schedules.approval_flg', '>=', 2 )
        ->get();

        $CourseSchedule = CheckCouses::setApprovalNames($CourseSchedule);

        return view('approval.index', ['courseSchedules' => $CourseSchedule]);
        

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
            if(empty($PCS))throw new \Exception("対象のデータがありません");
            $ApprovalComments = ApprovalComments::where('course_schedules_id', $id)->get();
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
        $select = CourseSchedule::select('course_schedules.*','courses.course_name','users.name')
        ->join('courses','courses.id','=','course_schedules.course_id')
        ->join('users','users.id','=','course_schedules.instructor_id')
        ->find($id);
        $select = CheckCouses::setApprovalName($select);
        return $select ;
    }

    /**
     * 養成講座申請画面を表示します。
     */
    public function confilmIntrCourse($id){
        list($select, $CSL) = $this->getIntrCouse($id);
        $ApprovalComments = ApprovalComments::where('course_schedules_id', $id)->get();
        if(empty($CSL))throw new \Exception("対象のデータがありません");
        return view('approval.intrShow', ['courseSchedule' => $select,'courseScheduleList' => $CSL, 'ApprovalComments'=> $ApprovalComments]);
    }

    /**
     * 養成コースの詳細を取得
     */
    public function getIntrCouse($id){
        $select = CourseSchedule::select('course_schedules.*','courses.course_name','users.name')
        ->join('courses','courses.id','=','course_schedules.course_id')
        ->join('users','users.id','=','course_schedules.instructor_id')
        ->find($id);
        $select = CheckCouses::setApprovalName($select);

        $CSL = CourseScheduleList::find($id);
        return [$select, $CSL] ;
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
            $course = Course::find($course_schedules->course_id);
            $intr = DB::table('users')->find($course_schedules->instructor_id);

            if($request->NG){
                $course_schedules->approval_flg = 1 ;
                $course_schedules->save() ;
                session()->flash('msg_success', '申請を差し戻しました');
                $data = [
                    "instructor"  => $intr->name,
                    "result"      => '差し戻されました',
                    "result2"     => 'システムにログインした後、削除・もしくは再申請を行ってください。',
                    "course"      => $course->course_name,
                    "comment"     => $request->appComment,
                    "url"         => url('').'/courseSchedule/index'
                ];
                Mail::send('emails.scheduleApplicationResult', $data, function($message){
                    $message->to($this->_toReon)
                    ->cc($this->_toInfo)
                    ->bcc($this->_toReon)
                    ->subject('スケジュールが差し戻されました');
                });
            }
            if($request->OK){
                $course_schedules->approval_flg = 5 ;
                // $course_schedules->save() ;
                session()->flash('msg_success', '承認しました');
                $data = [
                    "instructor"  => $intr->name,
                    "result"      => '承認されました',
                    "result2"     => 'パラリンビクスHPの開催日程一覧に、あなたのスケジュールが掲載されました'."\n".'引き続き集客にご尽力ください',
                    "course"      => $course->course_name,
                    "comment"     => $request->appComment,
                    "url"         => 'https://paralymbics.jp/schedules/'
                ];
                Mail::send('emails.scheduleApplicationResult', $data, function($message){
                    $message->to($this->_toReon)
                    ->cc($this->_toInfo)
                    ->bcc($this->_toReon)
                    ->subject('スケジュールが承認されました');
                });
            }
            return redirect()->action('ApprovalController@index');
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
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
        $select = DB::connection('mysql_2')->table('my_schedule')->select('*')->get();
        dd($select);
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
