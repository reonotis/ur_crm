<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller,
    Session;




class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $auths = Auth::user();
        // if($auths->authority === 1){
            return view('client.index');
        // }
    }

    public function searching(Request $request){
        // 検索結果のIDをセッションに入れる
        $name = $request->input('name');
        $query = DB::table('clients');
        if($name !== null){
            $name_split = mb_convert_kana($name, 's');    //全角スペースを半角にする
            $name_split2 = preg_split('/[\s]+/', $name_split, -1, PREG_SPLIT_NO_EMPTY);    //半角スペースで区切る
            foreach($name_split2 as $value){
                $query -> where('name','like','%'.$value.'%');
            }
        }
        $query -> select('id');
        $query -> orderby('id','asc');
        $clients = $query -> get();
        Session::put('search_client_id', $clients);

        $client_id = $clients[0]->id;

        return redirect()->action('ClientController@display', ['id' => $client_id]);
    }

    public function display($client_id){
        // 渡されたIDの顧客情報を取得する
        $query = DB::table('clients');
        $query -> where('id','=',$client_id);
        $clients = $query -> get();

        // 業種のリストを取得
        $industrieQuery = DB::table('industries')
                    -> select('id', 'name')
                    -> where('delete_flag','=', '0')
                    -> orderBy('id', 'asc');
        $industries = $industrieQuery -> get();

        // 角度のリストを取得
        $anglesQuery = DB::table('angles')
                    -> select('id', 'angle_name')
                    -> where('delete_flag','=', '0')
                    -> orderBy('id', 'asc');
        $angles = $anglesQuery -> get();

        // 社員のリストを取得
        $usersQuery = DB::table('users')
                    -> select('id', 'name')
                    -> where('enrollment','=', '1')
                    -> where('authority','=', '1')
                    -> orderBy('id', 'asc');
        $users = $usersQuery -> get();

        if ($clients->isEmpty()) {
            dd('検索結果がありません。');
        }else{
            return view('client.display', compact('clients','industries','angles','users'));
        }
    }

    public function newCall($client_id){
        $auths = Auth::user();
        // dd($auths->id);

        $contact = new Contact;
        $contact -> client_id = $client_id;
        $contact -> staff = $auths->id;

        $contact -> save();



        return redirect()->action('ClientController@display', ['id' => $client_id]);
    }


    public function search(){
        return view('client.search');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('client.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $client = new Client;
        $client -> name = $request->input('name');
        $client -> read = $request->input('read');
        $client -> save();
        return redirect('client/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $Client = Client::find($id);
        // dd($Client);
        return view('client.show',compact('Client'));
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
        //

        $Client = Client::find($id);
        $Client -> angle = $_POST['angle'];
        $Client -> industry_id = $_POST['industry'];
        $Client -> recall = str_replace('T', ' ', $_POST['recall']).":00";
        $Client -> user = $_POST['user'];
        $Client -> memo = $_POST['memo'];
        $Client -> save();
        // return $id;
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function aj_history_id($id){
        $Contact = Contact::find($id);

        // 架電手段のリストを取得
        $meansQuery = DB::table('means')
                    -> select('id', 'mean_name')
                    -> where('delete_flag','=', '0')
                    -> orderBy('id', 'asc');
        $means = $meansQuery -> get();

        // 結果のリストを取得
        $resultQuery = DB::table('results')
                    -> select('id', 'result_name')
                    -> where('delete_flag','=', '0')
                    -> orderBy('id', 'asc');
        $results = $resultQuery -> get();

        return view('client.ajax_history_display',compact('Contact', 'means', 'results'));
    }

    public function aj_contactList($client_id){
        // contact履歴を取得
        $ContactQuery = DB::table('contacts');
        $ContactQuery -> where('client_id','=',$client_id);
        $ContactQuery -> leftJoin('users', 'users.id', '=', 'contacts.staff');
        $ContactQuery -> leftJoin('means', 'means.id', '=', 'contacts.means_id');
        $ContactQuery -> leftJoin('results', 'results.id', '=', 'contacts.result_id');
        $ContactQuery -> select('contacts.*', 'users.name', 'means.mean_name', 'results.result_name');
        $ContactQuery -> orderBy('history_datetime', 'desc');
        $Contacts = $ContactQuery -> get();
        // dd($Contacts);


        return view('client.ajax_contact_list',compact('Contacts'));
    }

    public function aj_orderList($client_id){
        // order履歴を取得
        $orderQuery = DB::table('orders');
        $orderQuery -> where('client_id','=',$client_id);
        $orderQuery -> leftJoin('users', 'users.id', '=', 'orders.user');
        $orderQuery -> select('orders.*', 'users.name');
        $orderQuery -> orderBy('date', 'desc');
        $orders = $orderQuery -> get();
        // dd($orders);

        return view('client.ajax_order_list',compact('orders'));
    }

    public function aj_contact_update(Request $request, $id){
        $Contact = Contact::find($id);
        $Contact->history_datetime = date('Y-m-d H:i', strtotime( $_POST['history_datetime'] )).":00";
        $Contact->recipient_name = $_POST['recipient_name'];
        $Contact->recipient_role = $_POST['recipient_role'];
        $Contact->recipient_sex  = $_POST['recipient_sex'];
        $Contact->means_id       = $_POST['means_id'];
        $Contact->result_id      = $_POST['result_id'];
        $Contact->history_detail = $_POST['history_detail'];

        $Contact->save();
        return $Contact->client_id;
    }


    public function aj_history_detail($id){
        // return ($id);
        // dd($id);

        // contact履歴を取得
        $ContactQuery = DB::table('contacts');
        $ContactQuery -> where('id','=',$id);
        $Contacts = $ContactQuery -> get();


        $query = DB::table('contacts');
        $query -> where('id', '=', $id );
        $query -> select('history_detail');
        $clients = $query -> get();

        $history_detail = $clients[0]->history_detail;
        return (nl2br($history_detail));

    }
}
