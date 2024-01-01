@extends('layouts.app')
@section('pageTitle', 'ホーム')

<link href="{{ asset('css/notice.css') }}?<?= date('Ymdhi') ?>" rel="stylesheet">
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">お知らせ</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (!empty($noticeStatuses))
                            <div class="noticeBlock">
                                @foreach($noticeStatuses as $noticeStatus)
                                    <a href="{{ route('notice.show', ['notice'=>$noticeStatus->notice->id] ) }}">
                                        <div class="noticeRow @if($noticeStatus->notice_status == 0) unread @endif">
                                            <div
                                                class="noticeRow_date @if($noticeStatus->created_at->format('Ymd') >= date('Ymd', strtotime('-1 week', time()) )) noticeNew @endif ">
                                                {{ $noticeStatus->created_at->format('Y/m/d') }}
                                            </div>
                                            {{ $noticeStatus->notice->title }}
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif

                    </div>
                    <div class="card-footer">
                        @if($noticeCreateAuth)
                            {{ $noticeStatuses->links() }}
                            <a href="{{ route('notice.create') }}">お知らせ作成</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

