@extends('layouts.app')

@section('content')
<div class="container">
        <!-- <a href="{{route('customer.index')}}">顧客検索</a> -->
        
    <div class="row justify-content-center">

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    
                    @yield('mail_title')
                </div>

                <div class="card-body">
                    @yield('text')

                </div>
            </div>
        </div>
    </div>

</div>
@endsection
</table>



<script>
    function confirmSendMail(){
        var result = window.confirm('メールを送信します。\n宜しいですか？');
        if( result ) return true; return false;
    }
</script>
