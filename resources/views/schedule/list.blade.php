@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">スケジュール</div>
                <div class="card-body">
                    <table class="scheduleListTable">
                        <thead>
                            <tr>
                                <th>日時</th>
                                <th>お客様名</th>
                                <th>開催コース</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules as $schedule)
                            <!-- <?php var_dump( $schedule->date ) ?> -->
                                <tr>
                                    <td>
                                        {{ $schedule->date->format('Y年m月d日') }}
                                        {{ date('H:i', strtotime($schedule->time)) }}～
                                    </td>
                                    <td>
                                        <a href="{{route('customer.display', $schedule->customer_id  )}}">{{ $schedule->customerName }}様</a>
                                    </td>
                                    <td>
                                        {{ $schedule->course_name }}
                                        {{ $schedule->howMany}}回目
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


