<?php

namespace App\Http\Controllers;

use App\Consts\SessionConst;
use App\Common\CustomerCheck;
use App\Models\Customer;
use App\Models\Questions;
use App\Models\Shop;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MedicalController extends Controller
{
    public $errMsg = [];

    /**
     * Display a listing of the resource.
     *
     * @return view
     */
    public function index(Shop $shop): view
    {
        $createURL = route('medical.create', ['shop'=>$shop->id ]);
        return view('medical.index', compact('shop', 'createURL'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Shop $shop): view
    {
        return view('medical.create', compact('shop'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $customerCheck = new CustomerCheck;
        $checkResult = $customerCheck->registerCheckValidation($request);
        if(!$checkResult){
            return redirect()->back()
                ->with(SessionConst::FLASH_MESSAGE_ERROR, $customerCheck->getErrMsg())
                ->withInput();
        }

        $shopId = $request->shop_id;
        dd($shopId);
        $customerNo = $this->_makeCustomerNo($shopId);
        try {
            DB::beginTransaction();
            $customer = Customer::create([
                'customer_no' => $customerNo,
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'f_read' => $request->f_read,
                'l_read' => $request->l_read,
                'sex' => $request->sex,
                'tel' => $request->tel,
                'email' => $request->email,
                'birthday_year' => $request->birthday_year,
                'birthday_month' => $request->birthday_month,
                'birthday_day' => $request->birthday_day,
                'shop_id' => $shopId,
                'staff_id' => $request->staff_id,
                'zip21' => $request->zip21,
                'zip22' => $request->zip22,
                'pref21' => $request->pref21,
                'address21' => $request->address21,
                'street21' => $request->street21,
                'memo' => $request->memo,
            ]);

            DB::commit();
            return redirect()->route('customer.show', ['customer'=>$customer->id])->with(SessionConst::FLASH_MESSAGE_SUCCESS, ['顧客情報を登録しました']);
        } catch (\Throwable $e) {
            DB::rollback();
            Log::error( ' msg:' . $e->getMessage());
            return redirect()->back()->with(SessionConst::FLASH_MESSAGE_ERROR, ['顧客情報の登録に失敗しました'])->withInput();
        }
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function complete($id)
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
}
