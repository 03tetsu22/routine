@extends('layouts.app')

@section('content')
<?php
$from_yaer = date_format(date_create($from), 'Y');
$from_month = date_format(date_create($from), 'n');
$from_day = date_format(date_create($from), 'd');
$to_yaer = date_format(date_create($to), 'Y');
$to_month = date_format(date_create($to), 'n');
$to_day = date_format(date_create($to), 'd');
?>
<div class="main-contents-rank">
    <div class="ranking">
        <div class="rank-top">
            @if($_GET)
                <div class="rank-fromto">{{ $from_yaer }}年{{ $from_month }}月{{ $from_day }}日から{{ $to_yaer }}年{{ $to_month }}月{{ $to_day }}日の成績表</div>
            @else
                <div class="rank-fromto">日付指定成績表</div>
            @endif
            <form action="{{ url('routine/ranking-date') }}" method="GET">
                <div class="month-search">
                    <input type="date" name="from" class="calender" value="{{ old('from', $from) }}">
                    <label>~</label>
                    <input type="date" name="to" class="calender" value="{{ old('to', $to) }}">
                    <button type="submit" class="btn btn-outline-dark">→表示</button>
                </div>
            </form>
        </div>
        @if(isset($done_routine))
        <div class="container">
            <table class="table table-striped rank-table">
                <tbody>
                    @foreach($done_routine as $result)
                    <tr>
                        <td>{{$result->staff->family_name}}</td>
                        <td>{{$result->sum}}</td>
                    </tr>
                    @endforeach
                    @if(!empty($zero_family))
                        @foreach($zero_family as $family)
                        <tr>
                            <td>{{$family}}</td>
                            <td>0</td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        @endif
        @if (session('message'))
            <div class="container mt-2">
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            </div>
        @endif
    </div>
</div>

@endsection