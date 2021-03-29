<?php

namespace App\Http\Controllers;

use App\Models\ApprovalComments;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseScheduleTransactions;
use App\Models\CourseScheduleListTransactions;
use App\Models\CourseSchedule;
use App\Models\CourseScheduleList;
use App\Models\WPMySchedule;
use App\Models\WPMyScheduleIntrCourse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CourseScheduleController extends Controller
{

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
     *一覧表示(Read)
     */
    public function index(){
        // パラリンコースを取得
        $para_course_schedules = WPMySchedule::select('my_schedule.*','my_instructor.f_name','my_instructor.l_name','my_courses.short_name')
            ->where('my_schedule.delete_wp', NULL)
            ->where('my_schedule.course_id', '<>' , 6)
            ->join('my_instructor', 'my_instructor.id', '=', 'my_schedule.instructor_id')
            ->join('my_courses', 'my_courses.id', '=', 'my_schedule.course_id')
            ->get();

        // 養成コースを取得course_name
        if($para_course_schedules){
            $para_course_schedules = $this->getApprovalNames($para_course_schedules);
        }
        // 養成コースを取得
        $intr_course_schedules = WPMySchedule::select(
                'my_schedule.*',
                'my_instructor.f_name',
                'my_instructor.l_name',
                'my_schedule_intr_courses.course_title'
            )
            ->where('my_schedule.delete_wp', NULL)
            ->where('my_schedule.course_id' , 6)
            ->join('my_instructor', 'my_instructor.id', '=', 'my_schedule.instructor_id')
            ->join('my_courses', 'my_courses.id', '=', 'my_schedule.course_id')
            ->join('my_schedule_intr_courses', 'my_schedule_intr_courses.id', '=', 'my_schedule.id')
            ->get();

        // 養成コースを取得course_name
        if($intr_course_schedules){
            $intr_course_schedules = $this->getApprovalNames($intr_course_schedules);
        }
        return view('course_schedule.index', compact('para_course_schedules', 'intr_course_schedules'));
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
                    $data->approval_name = '未申請';
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
     *パラリンビクス講座申請画面のフォーム表示(Create)
     */
    public function paraCreate (){
        // コース一覧を取得する
        $coursesQuery = Course::query();
        $coursesQuery -> where('courses.delete_flag','=','0');
        $coursesQuery -> where('courses.parent_id','=','1');
        $courses = $coursesQuery -> get();
        return view('course_schedule.paraCreate', compact( 'courses'));
    }

    /**
     *新規作成のためのフォーム表示(Create)
     */
    public function intrCreate(){
        return view('course_schedule.intrCreate');
    }

    /**
     *イントラ養成コースの登録確認画面
     */
    public function intrConfilm(Request $request){
        try{
            $auth = Auth::user();
            $instructor_id = $auth->id;
            // トランザクションに残っている自分のレコードをを物理削除する
            $this->deleteTransactions();

            // リクエストをトランザクションに登録
            list($CST , $last_insert_id) = $this->insert_CourseScheduleTransactions(6, $request);
            $CSLT = $this->insert_CourseScheduleListTransactions($last_insert_id, $request);

            return view('course_schedule.intrConfilm', ['CST' => $CST, 'CSLT' => $CSLT ]);
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return view('course_schedule.intrCreate');
        }
    }

    /**
     * リクエストをトランザクションに登録
     * return トランザクション
     * return トランID
     */
    public function insert_CourseScheduleTransactions($course_id, $request){
        $CST = new CourseScheduleTransactions;
        $CST -> instructor_id = $this->_auth_id ;
        $CST -> course_id     = 6;
        $CST -> date          = date("Y-m-d", strtotime($request->date1));
        $CST -> time          = date("H:i:00", strtotime($request->date1));
        $CST -> price         = $request->input('price');
        $CST -> erea          = $request->input('erea');
        $CST -> venue         = $request->input('venue');
        $CST -> notices       = $request->input('notices');
        $CST -> comment       = $request->input('comment');
        if($request->open_start_day){
            $CST -> open_start_day  = date("Y-m-d H:i:00", strtotime($request->open_start_day));
        }
        if($request->open_finish_day){
            $CST -> open_finish_day = date("Y-m-d H:i:00", strtotime($request->open_finish_day));
        }
        $CST -> save();
        $last_insert_id = $CST->id;
        return [$CST , $last_insert_id];


    }

    /**
     * リクエストをイントラスケジュールトランザクションに登録
     * return トランザクション
     */
    public function insert_CourseScheduleListTransactions($last_insert_id, $request){
        $CSLT = new CourseScheduleListTransactions;
        $CSLT->id   = $last_insert_id;
        $CSLT->course_title=$request->course_title ;
        $CSLT->date1  = date("Y-m-d H:i:00", strtotime($request->date1));
        $CSLT->date2  = date("Y-m-d H:i:00", strtotime($request->date2));
        $CSLT->date3  = date("Y-m-d H:i:00", strtotime($request->date3));
        $CSLT->date4  = date("Y-m-d H:i:00", strtotime($request->date4));
        $CSLT->date5  = date("Y-m-d H:i:00", strtotime($request->date5));
        if($request->date6){
            $CSLT->date6  = date("Y-m-d H:i:00", strtotime($request->date6));
        }
        if($request->date7){
            $CSLT->date7  = date("Y-m-d H:i:00", strtotime($request->date7));
        }
        if($request->date8){
            $CSLT->date8  = date("Y-m-d H:i:00", strtotime($request->date8));
        }
        if($request->date9){
            $CSLT->date9  = date("Y-m-d H:i:00", strtotime($request->date9));
        }
        if($request->date10){
            $CSLT->date10  = date("Y-m-d H:i:00", strtotime($request->date10));
        }
        $CSLT->save();

        return $CSLT;

    }

    /**
     *イントラ養成コースの登録
     */
    public function intrStore($id){
        try{
            // イントラスケジュールリストを取得
            list($CST, $CSLT) = $this->getIntrCourseScheduleListTransactions();
            // WPDBのコーステーブルに登録
            $lastID = DB::connection('mysql_2')->table('my_schedule')->insertGetId([
                'date'      => $CST->date,
                'open_time' => $CST->time,
                'course_id' => $CST->course_id,
                'price'     => $CST->price,
                'instructor_id' => $CST->instructor_id,
                'erea'      => $CST->erea,
                'venue'     => $CST->venue,
                'notices'   => $CST->notices,
                'comment'   => $CST->comment,
                'approval_flg'  => 2,
                'AcceptedStartDateTime' => $CST->open_start_day,
                'AcceptedEndDateTime' => $CST->open_finish_day
                ]);

            // WPDBのイントラコーステーブルに登録
            DB::connection('mysql_2')->table('my_schedule_intr_courses')->insertGetId([
                'id'     => $lastID,
                'course_title' => $CSLT->course_title,
                'date1'     => $CSLT->date1,
                'date2'     => $CSLT->date2,
                'date3'     => $CSLT->date3,
                'date4'     => $CSLT->date4,
                'date5'     => $CSLT->date5,
                'date6'     => $CSLT->date6,
                'date7'     => $CSLT->date7,
                'date8'     => $CSLT->date8,
                'date9'     => $CSLT->date9,
                'date10'     => $CSLT->date10,
            ]);

            // トランザクションを削除
            $this->deleteTransactions();

            session()->flash('msg_success', '申請が完了しました');
            return redirect()->action('CourseScheduleController@index');
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->action('CourseScheduleController@index');
        }
    }

    /**
     * ログインユーザーの[イントラコーススケジュールリスト]トランザクションを返す
     */
    public function getIntrCourseScheduleListTransactions(){
        $CST = CourseScheduleTransactions::where('instructor_id', '=', $this->_auth_id )->first();
        if(empty($CST))throw new \Exception("この操作は禁止されています");
        $CSLT = CourseScheduleListTransactions::find($CST ->id );
        if(empty($CSLT))throw new \Exception("この操作は禁止されています");
        return [$CST, $CSLT];
    }

    /**
     *パラリンビクス講座スケジュール申請確認画面
     *
     */
    public function paraConfilm(Request $request){
        try{
            $instructor_id = $this->_auth_id;
            // 一度、トランザクションテーブルを物理削除する
            $this->deleteTransactions();

            // リクエストをトランザクションに登録
            $CSTran = new CourseScheduleTransactions;
            $CSTran -> instructor_id = $instructor_id ;
            $CSTran -> course_id     = $request->input('course_id');
            $CSTran -> date          = $request->input('date');
            $CSTran -> time          = $request->input('time');
            $CSTran -> price         = $request->input('price');
            $CSTran -> erea          = $request->input('erea');
            $CSTran -> venue         = $request->input('venue');
            $CSTran -> notices       = $request->input('notices');
            $CSTran -> comment       = $request->input('comment');
            $CSTran -> open_start_day  = date("Y-m-d H:i:00", strtotime($request->open_start_day));
            $CSTran -> open_finish_day = date("Y-m-d H:i:00", strtotime($request->open_finish_day));
            $CSTran -> save();

            $CSTQuery = CourseScheduleTransactions::select('course_schedule_transactions.*','courses.course_name')
            ->where('instructor_id', '=', $instructor_id )
            ->join('courses','courses.id','=','course_schedule_transactions.course_id');
            $CST = $CSTQuery -> first();
            return view('course_schedule.paraConfilm', ['CST' => $CST]);

        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->action('CourseScheduleController@index');
        }
    }

    /**
     *トランザクションから、渡されたユーザーidで登録されているレコードを削除する
     *
     */
    public function deleteTransactions(){
        $data = DB::table('course_schedule_transactions')->where('instructor_id', '=', $this->_auth_id )->first();
        if(!empty($data)){
            $tran_id = $data->id;
            DB::table('course_schedule_transactions')->where('id', $tran_id )->delete();
            DB::table('course_schedule_list_transactions')->where('id', $tran_id )->delete();
        }
    }

    /**
     * コースとスケジュールのデータを作成
     */
    public function showTransaction($id){
        $CSTQuery = CourseScheduleTransactions::select('course_schedule_transactions.*','courses.course_name')
                    ->where('course_schedule_transactions.id', '=', $id )
                    ->join('courses','courses.id','=','course_schedule_transactions.course_id');
        $CST = $CSTQuery -> first();
        $CSTL = CourseScheduleListTransactions::where('id', '=', $id )-> first();
        return [$CST, $CSTL];
    }

    /**
     * transactionのコース内容をコーススケジュールテーブルに申請中で登録する
     */
    public function intrRegister(){
        try{
            $auth_id = Auth::user()->id;
            // transactionに灯篭されているコーススケジュールを取得
            $CST = CourseScheduleTransactions::where('instructor_id', '=', $auth_id )->first();
            $CST_id = $CST->id;
            if($CST->course_id == 6){    // 登録するコースが養成コースだったら
                // transactionからコーススケジュールに登録してlastインサートIDを取得
                $course_id = $this->registerIntrCourseSchedule($CST);
                $course_id = $this->registerIntrCourseScheduleList($CST_id, $course_id);

            }else{

            }
            // トランザクションを削除する
            CourseScheduleTransactions::where('id', $CST_id)->delete();
            CourseScheduleListTransactions::where('id', $CST_id)->delete();

            session()->flash('msg_success', '申請が完了しました');
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
        return redirect()->action('CourseScheduleController@index');
    }

    /**
     * 渡されたtransactionの内容をコーススケジュールテーブルに申請中で登録する
     */
    public function registerIntrCourseSchedule($CST){
        if(empty($CST))throw new \Exception("異常が発生しました。");
        $CourseSchedule = new CourseSchedule;
        $CourseSchedule->date = NULL;
        $CourseSchedule->instructor_id = $CST->instructor_id ;
        $CourseSchedule->course_id = $CST->course_id ;
        $CourseSchedule->erea = $CST->erea ;
        $CourseSchedule->venue = $CST->venue ;
        $CourseSchedule->price = $CST->price ;
        $CourseSchedule->notices = $CST->notices ;
        $CourseSchedule->comment = $CST->comment ;
        $CourseSchedule->save();
        return $CourseSchedule->id;

    }

    /**
     * 
     */
    public function registerIntrCourseScheduleList($CST_id,$course_id){
        if(empty($CST_id) ||empty($course_id)  )throw new \Exception("異常が発生しました。");

        $CST = CourseScheduleListTransactions::where('id', '=', $CST_id )->first();
        if(empty($CST)) throw new \Exception("異常が発生しました。");
        $CourseScheduleList = new CourseScheduleList;
        $CourseScheduleList->id   = $course_id;
        $CourseScheduleList->course_title=$CST->course_title ;
        $CourseScheduleList->date1  = $CST->date1;
        $CourseScheduleList->time1  = $CST->time1;
        $CourseScheduleList->date2  = $CST->date2;
        $CourseScheduleList->time2  = $CST->time2;
        $CourseScheduleList->date3  = $CST->date3;
        $CourseScheduleList->time3  = $CST->time3;
        $CourseScheduleList->date4  = $CST->date4;
        $CourseScheduleList->time4  = $CST->time4;
        $CourseScheduleList->date5  = $CST->date5;
        $CourseScheduleList->time5  = $CST->time5;
        $CourseScheduleList->date6  = $CST->date6;
        $CourseScheduleList->time6  = $CST->time6;
        $CourseScheduleList->date7  = $CST->date7;
        $CourseScheduleList->time7  = $CST->time7;
        $CourseScheduleList->date8  = $CST->date8;
        $CourseScheduleList->time8  = $CST->time8;
        $CourseScheduleList->date9  = $CST->date9;
        $CourseScheduleList->time9  = $CST->time9;
        $CourseScheduleList->date10 = $CST->date10;
        $CourseScheduleList->time10 = $CST->time10;
        $CourseScheduleList->save();
        return true;

    }

    /**
     * パラリンビクス講座を登録する
     */
    public function paraStore(){
        try {
            $CST = CourseScheduleTransactions::where('instructor_id', '=', $this->_auth_id )->first();
            if(empty($CST))throw new \Exception("不正なaccessです");
            // WPDBのコーステーブルに登録
            $lastID = DB::connection('mysql_2')->table('my_schedule')->insertGetId([
                'date'      => $CST->date,
                'open_time' => $CST->time,
                'course_id' => $CST->course_id,
                'price'     => $CST->price,
                'instructor_id' => $CST->instructor_id,
                'erea'      => $CST->erea,
                'venue'     => $CST->venue,
                'notices'   => $CST->notices,
                'comment'   => $CST->comment,
                'approval_flg'  => 2,
                'AcceptedStartDateTime'  => $CST->open_start_day,
                'AcceptedEndDateTime'  => $CST->open_finish_day
            ]);


            session()->flash('msg_success', '申請が完了しました');


        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
        return redirect()->action('CourseScheduleController@index');
    }

    /**
     * show	パラリンコース1件の詳細表示(Read)
     */
    public function paraShow($id){
        try {
            // パラリンコースを取得
            $intr_course = CourseSchedule::select('course_schedules.*','courses.course_name')
            ->join('courses','courses.id','=','course_schedules.course_id')
            ->where('course_schedules.id', '=', $id)
            ->first($id);
            // dd($intr_course);
            $auth_id = Auth::user()->id;
            // ユーザーのScheduleじゃなければ前の画面へ戻る
            if($intr_course->instructor_id <> $auth_id )throw new \Exception("あなたのスケジュールではありません");
            if($intr_course->course_id == 6 )throw new \Exception("表示しようとしているスケジュールはパラリンビクス講座ではありません");

            $intr_course = $this->getApprovalName($intr_course);
            $ApprovalComments = ApprovalComments::where('course_schedules_id', $id )->get();
            return view('course_schedule.paraShow', ['intr_course' => $intr_course, 'ApprovalComments'=> $ApprovalComments]);
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * show	養成コース1件の詳細表示
     */
    public function intrShow($id){
        try {
            // 養成コースを取得
            $intr_course = WPMySchedule::join('my_schedule_intr_courses','my_schedule_intr_courses.id','=','my_schedule.id')->find($id);
            // ユーザーのScheduleじゃなければ前の画面へ戻る
            if($intr_course->instructor_id <> $this->_auth_id )throw new \Exception("あなたのスケジュールではありません");
            if($intr_course->course_id <> 6 )throw new \Exception("表示しようとしているスケジュールは養成講座ではありません");
            $WPMyScheduleIntrCourses = WPMyScheduleIntrCourse::find($id);
            $intr_course = $this->getApprovalName($intr_course);
            return view('course_schedule.intrShow', ['intr_course' => $intr_course, 'WPMyScheduleIntrCourses' => $WPMyScheduleIntrCourses ]);
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * show	パラリンビクスコースの編集画面表示
     */
    public function paraEdit($id){
        try {
            $auth_id = Auth::user()->id;
            $intr_course = CourseSchedule::find($id);
            // 受理済みかの確認
            if($intr_course->approval_flg == 5 )throw new \Exception("既に受領済みの為編集できません");
            if($intr_course->instructor_id <> $auth_id )throw new \Exception("あなたのスケジュールではありません");
            $courses = Course::where('parent_id',1)->get();
            return view('course_schedule.paraEdit', ['intr_course' => $intr_course, 'courses' => $courses]);
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * show	養成コースの編集画面表示
     */
    public function intrEdit($id){
        try {
            $auth_id = Auth::user()->id;
            $intr_course = CourseSchedule::find($id);
            $intr_schedule = CourseScheduleList::find($id);
            // 受理済みかの確認
            if($intr_course->approval_flg == 5 )throw new \Exception("既に受領済みの為編集できません");

            return view('course_schedule.intrEdit', ['intr_course' => $intr_course, 'intr_schedule' => $intr_schedule]);
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * update	パラリンビクスコースの更新
     */
    public function paraUpdate(Request $request, $id){
        try {
            $auth_id = Auth::user()->id;
            $intr_course = CourseSchedule::find($id);
            // 自分のスケジュールを更新しようとしているか確認
            if($intr_course->instructor_id <> $auth_id)throw new \Exception("不正な更新accessです");
            // 受理済みか削除されたデータじゃないか確認
            if($intr_course->approval_flg > 5 || $intr_course->delete_flag == 1 )throw new \Exception("このデータは更新できません");


            $intr_course->course_id= $request->course_id ;
            $intr_course->price= $request->price ;
            $intr_course->erea= $request->erea ;
            $intr_course->venue= $request->venue ;
            $intr_course->notices= $request->notices ;
            $intr_course->comment= $request->comment ;
            $intr_course->approval_flg= 2 ;
            $intr_course->save();

            session()->flash('msg_success', '更新が完了しました');
            return redirect()->action('CourseScheduleController@index');
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * update	養成コースの更新
     */
    public function intrUpdate(Request $request, $id){
        try {
            $auth_id = Auth::user()->id;
            $intr_course = CourseSchedule::find($id);
            $intr_schedule = CourseScheduleList::find($id);
            // 自分のスケジュールを更新しようとしているか確認
            if($intr_course->instructor_id <> $auth_id)throw new \Exception("不正な更新accessです");
            // 受理済みか削除されたデータじゃないか確認
            if($intr_course->approval_flg > 5 || $intr_course->delete_flag == 1 )throw new \Exception("このデータは更新できません");

            $intr_course->price= $request->price ;
            $intr_course->erea= $request->erea ;
            $intr_course->venue= $request->venue ;
            $intr_course->notices= $request->notices ;
            $intr_course->comment= $request->comment ;
            $intr_course->save();

            $intr_schedule->course_title = $request->course_title ;
            $intr_schedule->save();

            session()->flash('msg_success', '更新が完了しました');
            return redirect()->action('CourseScheduleController@index');
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
    }

    /**
     * delete 申請済みのイントラコースを削除
     */
    public function intrDelete($id){
        try {
            $auth_id = Auth::user()->id;
            $intr_course = CourseSchedule::find($id);
            // ユーザーのコースか確認
            if( empty($intr_course) || $intr_course->instructor_id <> $auth_id )throw new \Exception("不正なaccessです");

            // コースのIDを取得
            $intr_course_id = $intr_course->id;

            // 取得したコースを論理削除
            CourseSchedule::where('id', $intr_course_id)
            ->update([
                'delete_flag' => 1
            ]);

            // トランザクションを論理削除
            CourseScheduleList::where('id', $intr_course_id)
            ->update([
                'delete_flag' => 1
            ]);
            session()->flash('msg_success', '削除が完了しました');
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
        return redirect()->action('CourseScheduleController@index');
    }

    // store	新規作成のためのデータ保存(Create)
    // destroy	既存レコードの削除(Delete)

    public function getParaCourses(){

        $PCS = CourseSchedule::select('course_schedules.*','courses.course_name');
        // if($this->_auth_authority_id >= 7){
            $PCS = $PCS->where('instructor_id','=', $this->_auth_id );
        // }
        $PCS = $PCS->where('course_id', '<>', '6' );
        $PCS = $PCS->where('course_schedules.delete_flag', '0' );
        $PCS = $PCS->leftJoin('courses','courses.id','=','course_schedules.course_id');
        $PCS = $PCS->get();
        if($PCS){
            $PCS = $this->getApprovalNames($PCS);
        }

        return $PCS;

    }

}
