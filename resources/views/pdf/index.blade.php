<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="{{ public_path('/css/pdf.css') }}">

        <title>pdf_file_name</title>
        <style>
            body {
                font-family: ipag;
            }
        </style>
    </head>
    <body>

        <h1>こんにちは</h1>
        <table class="pdf_table_001" >
            <tr>
                <th>スタイリスト</th>
                <th>対応人数</th>
                <th>S_指名</th>
                <th>SH_紹介</th>
                <th>K_交代</th>
                <th>F_フリー</th>
                <th>D_代理</th>
            </tr>
            @foreach( $visitHistorys as $visitHistory)
                <tr>
                    <td>{{ $visitHistory->name }}</td>
                    <td>{{ $visitHistory->total_NINNZUU }}名</td>
                    <td>{{ $visitHistory->VHT1_NINNZUU }}名</td>
                    <td>{{ $visitHistory->VHT2_NINNZUU }}名</td>
                    <td>{{ $visitHistory->VHT3_NINNZUU }}名</td>
                    <td>{{ $visitHistory->VHT4_NINNZUU }}名</td>
                    <td>{{ $visitHistory->VHT5_NINNZUU }}名</td>
                </tr>
            @endforeach
        </table>
    </body>
</html>