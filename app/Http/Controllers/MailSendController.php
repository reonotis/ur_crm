<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class MailSendController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $data = ["message_A" => "こんにちは！"];
        Mail::send('emails.mailsend', $data, function($message){
            $message->to('fujisawareon@yahoo.co.jp', 'Test')
            ->subject('題名が入ります');
            });

        

    }
}
