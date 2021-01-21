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


                    <table class="table">
                        <thaed>
                            <tr>
                                <th>ID</th>
                                <th>会社名</th>
                            </tr>
                        </thaed>
                        <tbody>
                            @foreach($clients as $client)
                                <tr>
                                    <td><a href="{{route('client.show', ['id' => $client->id ] )}}">{{$client->id}}</a></td>
                                    <td>{{$client->name}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $clients->appends(request()->input())->links() }}


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
