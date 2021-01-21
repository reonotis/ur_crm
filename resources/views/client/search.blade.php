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



                    <form mothod="GET" action="{{route('client.searching')}}" class="form-inline my-2 my-lg-0">
                        <input class="form-control mr-sm-2" type="search" name="name" placeholder="会社名" aria-label="Search">
                        <input class="form-control mr-sm-2" type="search" name="read" placeholder="ヨミ" aria-label="Search">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">検索する</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
