<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Authority;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    //
    public function index(){
        $auths = Auth::user();
        $query = DB::table('users');
        $users = $query -> get();
        // if($auths->authority === 1){
            // return view('user.index');
            return view('user.index', compact('users'));
        // }
    }


    public function create(){
        // 権限のリストを取得
        $query = DB::table('authoritys')
                    -> select('id', 'authority_name')
                    -> where('delete_flag','=', '0')
                    -> orderBy('id', 'DESC');
        $authoritys = $query -> get();
        return view('user.create', compact('authoritys'));
    }

    public function store(Request $request){
        $User = new User;
        $User -> name = $request->input('name');
        $User -> email = $request->input('email');
        // 12文字のランダムなパスワードを生成
        $password = base_convert(mt_rand(pow(36, 12 - 1), pow(36, 12) - 1), 10, 36);
        $User -> password = Hash::make($password);
        $User -> authority = $request->input('authority');
        $User -> enrollment = $request->input('enrollment');
        $User -> save();
        return redirect('user/index');
    }

    public function display($id){
        $query = DB::table('users');
        $query -> where('id','=',$id);
        $user = $query -> get();
        dd($user);


        dd();
        // return view('client.display', compact('clients','Contacts'));

    }
}
