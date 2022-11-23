@extends('layouts.setting')
@section('pageTitle', 'メールアドレス')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @include('setting.navigation')
                <div class="setting-basic-contents" >
                    <div class="card-body">
                        <table class="tableClass_007">
                            <tbody>
                                <tr>
                                    <th>メールアドレス</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <a href="{{ route('setting.ChangeEmail') }}" class="button" >変更する</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
