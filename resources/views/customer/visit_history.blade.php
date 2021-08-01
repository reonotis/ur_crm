@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('customer.nav', ['id' =>$customer->id ])
        <div class="col-md-8">

            <div class="card">

                <div class="card-body">
                    <table class="tableClass_002">
まだテーブルを作成していない
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
