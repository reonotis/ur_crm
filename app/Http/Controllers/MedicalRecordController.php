<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Questions;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicalRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $shop = Shop::where('delete_flag', 0)->find($id);
        if(empty($shop)) dd("URLの値が不正です。");
        $questions = Questions::where('delete_flag', 0)
        ->orderby('rank')
        ->get();
        return view('medical_record.index',compact('shop','questions'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function confirm(Request $request)
    {
        try {
            DB::beginTransaction();
            Customer::insert([[
                'f_name'      => $request->f_name,
                'l_name'      => $request->l_name,
                'f_read'      => $request->f_read,
                'l_read'      => $request->l_read,
                'sex'         => $request->sex,
                'tel'         => $request->tel,
                'email'       => $request->email,
                'birthday_year'  => $request->birthday_year,
                'birthday_month' => $request->birthday_month,
                'birthday_day'   => $request->birthday_day,
                'shop_id'     => $request->shop_id,
                'zip21'       => $request->zip21,
                'zip22'       => $request->zip22,
                'pref21'      => $request->pref21,
                'addr21'      => $request->addr21,
                'strt21'      => $request->strt21,
                'question1'   => implode(',', $request->question1),
                'comment'     => $request->comment,
                'register_flow'=> 1,
                ]
            ]);

            // throw new \Exception("強制終了");

            DB::commit();
            return redirect(route('medical_record.complete', [
                'id' => $request->shop_id,
            ]));
            // return redirect()->action('MedicalRecordController@complete',['id'=> $request->shop_id ]);
        } catch (\Throwable $e) {
            DB::rollback();
            session()->flash('msg_danger',$e->getMessage() );
            return redirect()->back();    // 前の画面へ戻る
        }
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
     *
     * @return \Illuminate\Http\Response
     */
    public function complete($id)
    {
        $shop_id = $id;
        return view('medical_record.complete',compact('shop_id'));
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
