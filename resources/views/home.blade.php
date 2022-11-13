@extends('layouts.app')
@section('pageTitle', 'マイページ')

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

                    @if (!empty($notices))
                        <div class="noticeBlock" >
                            @foreach($notices as $notice)
                                <a href="{{ route('setting.noticeShow', ['id'=>$notice->id] ) }}">
                                    <div class="noticeRow <?php if( $notice->notice_status == 0 ) echo " unread"; ?>">
                                        <div class="noticeRow_date <?php if($notice->created_at->format('Ymd') >= date('Ymd', strtotime('-1 week', time()) )) echo "noticeNew" ?> ">
                                            {{ $notice->created_at->format('m月d日 H:i') }}
                                        </div>
                                        {{ $notice->title }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

