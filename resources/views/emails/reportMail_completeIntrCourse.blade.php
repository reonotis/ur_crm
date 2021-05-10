インストラクター養成コースを完了しました。
<br>
---------------------------<br>
【担当インストラクター】{{ $course->name }}<br>
【終了コース】 {{ $course->course_name }}<br>
【顧客名】     {{ $mapping->name }} 様<br>
<br>
下記よりご確認ください<br>
【URL】 <a href="{{$url}}">{{$url}}</a>
<br>
<br>
@include('emails.footer')