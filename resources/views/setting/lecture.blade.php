@extends('layouts.app')

@section('content')

@include('setting.navigation')

<div class="lectureSection" >
    <div class="lectureArea" >
        <div class="lectureContent " >
            <a href="{{route('pdf.show_pdfFile', ['file_name' => 'test1.pdf' ])}}" class="lectureLink" >
                顧客来店時の登録方法 PDF作成中
            </a>
        </div>
        <div class="lectureContent " >
            <a href="{{route('pdf.show_pdfFile', ['file_name' => 'test1.pdf' ])}}" class="lectureLink" >
                顧客来店情報の登録方法 PDF作成中
            </a>
        </div>
    </div>
</div>

@endsection
