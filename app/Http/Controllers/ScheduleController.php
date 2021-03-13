<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
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

    /**
     * スケジュールのリストを表示します
     *
     */
    public function list()
    {
        $auths = Auth::user();
        $query = DB::table('customer_schedules')
                -> leftJoin('customers', 'customer_schedules.customer_id', '=', 'customers.id')
                -> leftJoin('courses', 'customer_schedules.course_id', '=', 'courses.id')
                -> select('customer_schedules.*', 'customers.name as customerName', 'courses.course_name' );
        $query  -> orderByRaw('customer_schedules.date desc, customer_schedules.time desc, customer_schedules.howMany desc');


        if($auths->authority_id >= 7){
            $query -> where('customer_schedules.instructor_id','=', $auths->id  );
        }

        $schedules = $query -> get();
        return view('schedule.list', ['schedules' => $schedules]);
    }





}
