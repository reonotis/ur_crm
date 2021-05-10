@extends('errors::minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message', __('Page Expired'))
トークンが無効です。<br>
下記不正なアクセスが行われた可能性があります。<br>
一度トップ画面へ戻ってください。<br>
・有効期限が切れている<br>
・送信ボタンを2度押した<br>
・操作が完了したが、前の画面に戻ってボタンを押した<br>

