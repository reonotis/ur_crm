@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a href="{{route('client.index')}}">顧客</a>
                </div>


                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <a href="{{route('client.create')}}">新規顧客を追加する</a>
                    <br>
                    <a href="{{route('client.search')}}">検索する</a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
