
@extends('errors::minimal')

@section('title', __('Page Expired'))
@section('code', '419エラー')

@section('message')

<p>
    送信ボタンを2回以上押下した場合は、最初にクリックした時の処理が完了している可能性があるため、結果をご確認ください。<br>
    長時間経過してから操作を実行している場合は画面をリロードしてから改めて操作を行ってください。
</p>
@endsection

