<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseScheduleTransactions;
use App\Models\CourseScheduleListTransactions;
use App\Models\CourseSchedule;
use App\Models\CourseScheduleList;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CourseScheduleController extends Controller
{
    /**
     *一覧表示(Read)
     */
    public function index(){
        $auth_id = Auth::user()->id;
        // パラリンコースを取得
        $para_course_schedules = CourseSchedule::select('course_schedules.*','courses.course_name')
        ->where('instructor_id','=', $auth_id )
        ->leftJoin('courses','courses.id','=','course_schedules.course_id')
        ->where('course_id', '<>', '6' )
        ->where('course_schedules.delete_flag', '0' )
        ->get();
        if($para_course_schedules){
            $para_course_schedules = $this->getApprovalNames($para_course_schedules);
        }

        // 養成コースを取得course_name
        $intr_course_schedules = CourseSchedule::where('instructor_id', '=', $auth_id )
        ->where('course_id', '6' )
        ->where('course_schedules.delete_flag', '0' )
        ->leftJoin('course_schedule_lists','course_schedule_lists.id','=','course_schedules.id')
        ->get();
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
                    $data->approval_name = '申請中';
                    break;
                case '1':
                    $data->approval_name = '差し戻し';
                    break;
                default:
                    $data->approval_name = '--';
                    break;
            }
        return $data;
    }

    /**
     *新規作成のためのフォーム表示(Create)
     */
    public function create(){

        // コース一覧を取得する
        $coursesQuery = Course::query();
        $coursesQuery -> where('courses.delete_flag','=','0');
        $coursesQuery -> where('courses.hierarchy','=','2');
        $courses = $coursesQuery -> get();
        // $auths = Auth::user();

        return view('course_schedule.create', compact( 'courses'));
    }

    /**
     *新規作成のためのフォーム表示2
     *
     */
    public function create2(Request $request){
        try{
            $auth = Auth::user();
            $instructor_id = $auth->id;
            // 一度、トランザクションテーブルを物理削除する
            $this->deleteTransactions($instructor_id);

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
            $CSTran -> save();

            $CSTQuery = CourseScheduleTransactions::select('course_schedule_transactions.*','courses.course_name')
            ->where('instructor_id', '=', $instructor_id )
            ->join('courses','courses.id','=','course_schedule_transactions.course_id');
            $CST = $CSTQuery -> first();
            // dd($CST);

            // イントラ養成コースだったら
            if($request->course_id == 6){

                return view('course_schedule.create2', ['CST' => $CST]);
            }else{
                // イントラ養成コースじゃなければ確認画面を表示
                return view('course_schedule.register_confirm', ['CST' => $CST]);
            }
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->action('CourseScheduleController@index');
        }
    }


    /**
     *トランザクションから、渡されたユーザーidで登録されているレコードを削除する
     *
     */
    public function deleteTransactions($instructor_id){
        $data = DB::table('course_schedule_transactions')->where('instructor_id', '=',$instructor_id )->first();
        if(!empty($data)){
            $tran_id = $data->id;
            DB::table('course_schedule_transactions')->where('id', $tran_id )->delete();
            DB::table('course_schedule_list_transactions')->where('id', $tran_id )->delete();
        }
    }

    /**
     *入力されたスケジュールをトランザクションに登録して確認画面を表示する
     *
     */
    public function create3(Request $request){
        try{
            $auth = Auth::user();
            $instructor_id = $auth->id;
            // 紐づける為のコーススケジュールトランザクションのIDを取得
            $CST = CourseScheduleTransactions::select('course_schedule_transactions.*','courses.course_name')
                    ->where('instructor_id', '=', $instructor_id )
                    ->join('courses','courses.id','=','course_schedule_transactions.course_id')-> first();
            if(empty($CST))throw new \Exception("この操作は禁止されています");
            $tranCourse_id = $CST ->id;

            // リクエストをスケジュールリストトランザクションに登録
            DB::table('course_schedule_list_transactions')
            ->updateOrInsert(
                ['id' => $tranCourse_id],
                [
                    'course_title' => $request->input('course_title'),
                    'date1'   => $request->input('date1'),
                    'date2'   => $request->input('date2'),
                    'date3'   => $request->input('date3'),
                    'date4'   => $request->input('date4'),
                    'date5'   => $request->input('date5'),
                    'date6'   => $request->input('date6'),
                    'date7'   => $request->input('date7'),
                    'date8'   => $request->input('date8'),
                    'date9'   => $request->input('date9'),
                    'date10'  => $request->input('date10'),
                    'time1'   => $request->input('time1'),
                    'time2'   => $request->input('time2'),
                    'time3'   => $request->input('time3'),
                    'time4'   => $request->input('time4'),
                    'time5'   => $request->input('time5'),
                    'time6'   => $request->input('time6'),
                    'time7'   => $request->input('time7'),
                    'time8'   => $request->input('time8'),
                    'time9'   => $request->input('time9'),
                    'time10'  => $request->input('time10')
                ]
            );
            list($CST, $CSTL) = $this->showTransaction($tranCourse_id);
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->action('CourseScheduleController@index');
        }
        //  養成講座Schedule登録確認画面を表示
        return view('course_schedule.register_training_course_confirm', ['CST' => $CST, 'CSTL' => $CSTL]);
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
    public function register(){
        try {
            // dd($request);
            $auth_id = Auth::user()->id;
            $CST = CourseScheduleTransactions::where('instructor_id', '=', $auth_id )->first();
            if(empty($CST))throw new \Exception("不正なaccessです");

            // dd( $CST->date);
            $CourseSchedule = new CourseSchedule;
            $CourseSchedule->date = $CST->date;
            $CourseSchedule->time = $CST->time;
            $CourseSchedule->instructor_id = $CST->instructor_id ;
            $CourseSchedule->course_id = $CST->course_id ;
            $CourseSchedule->erea = $CST->erea ;
            $CourseSchedule->venue = $CST->venue ;
            $CourseSchedule->price = $CST->price ;
            $CourseSchedule->notices = $CST->notices ;
            $CourseSchedule->comment = $CST->comment ;
            $CourseSchedule->save();


            // throw new \Exception("強制終了");
        } catch (\Throwable $e) {
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
        return redirect()->action('CourseScheduleController@index');
    }


    /**
     * show	養成コース1件の詳細表示(Read)
     */
    public function intrShow($id){
        // 養成コースを取得
        $intr_course = CourseSchedule::find($id);
        if($intr_course->course_id <> 6 ){
            return redirect()->back();    // 前の画面へ戻る
        }
        $intr_schedule = CourseScheduleList::find($id);
        $intr_course = $this->getApprovalName($intr_course);
        return view('course_schedule.intrShow', ['intr_course' => $intr_course, 'intr_schedule' => $intr_schedule]);
    }


    /**
     * show	パラリンコース1件の詳細表示(Read)
     */
    public function paraShow($id){
        // パラリンコースを取得
        dd($id);
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
    // edit	既存レコードの変更のためのフォーム表示(Update)
    // update	既存レコードの変更のためのデータ保存(Update)
    // destroy	既存レコードの削除(Delete)


}
