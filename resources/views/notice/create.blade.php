@extends('layouts.app')
@section('breadcrumb')
    <ol>
        <li><a href="{{ route('home') }}">ホーム</a></li>
    </ol>
@endsection
<link href="{{ asset('css/notice.css') }}?<?= date('Ymdh') ?>" rel="stylesheet">
@section('pageTitle', 'お知らせ詳細')

@section('content')
    <form action="{{ route('notice.register') }}" method="post">
        @csrf
        <div class="">
            {{-- タイトル --}}
            <div class="flex-start-middle mb-4" style="border-bottom: solid 1px var(--mainColor); ">
                <div class="flex">
                    <div class="form-label">
                        <label for="title">タイトル</label>
                    </div>
                    <div style="width: 500px;">
                        @component('components.input-type-text',
                            [
                                'name' => 'title',
                                'id' => 'title',
                                'class' => 'form-control w-full mx-auto',
                                'value' => old('title'),
                            ])
                        @endcomponent
                        @if ($errors->first('title'))
                            <span class="error-message">
                                @php
                                    echo $errors->first('title');
                                @endphp
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 本文 --}}
            <div class="flex-start-middle mb-4" style="border-bottom: solid 1px var(--mainColor)">
                <div class="flex">
                    <div class="form-label">
                        <label for="title">本文</label>
                    </div>
                    <div style="width: 500px;">
                        @component('components.input-type-textarea',
                            [
                                'name' => 'comment',
                                'id' => 'comment',
                                'class' => 'form-control w-full mx-auto ',
                                'value' => old('comment'),
                            ])
                        @endcomponent
                        @if ($errors->first('comment'))
                            <span class="error-message">
                            @php
                                echo $errors->first('comment');
                            @endphp
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <input type="submit" name="" value="登録する" class="register-btn">
        </div>
    </form>
@endsection


