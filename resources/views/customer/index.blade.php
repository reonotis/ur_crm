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

                        <div class="CenterBOX">
                            <a href="{{route('customer.search')}}">
                                <div class="button BOXin">顧客を検索する</div>
                            </a>
                            <a href="{{route('client.create')}}">
                                <div class="button BOXin">顧客を追加する</div>
                            </a>
                        </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
