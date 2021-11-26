@extends('layouts.app')

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

                    <table class="tableClass_012" >
                        <tr>
                            <th>お知らせタイトル</th>
                            <td>{{ $notice->title }}</td>
                        </tr>
                        <tr>
                            <th>内容</th>
                            <td>{!! nl2br(e($notice['comment'])) !!}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<script>
    function confirmDeleteNotice(){
        var result = confirm('このお知らせを削除しますか？\nこの操作は取り消せません ');
        return result;
    }
</script>
