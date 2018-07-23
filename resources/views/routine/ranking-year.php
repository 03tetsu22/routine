@extends('layouts.app')

@section('content')
<?php
if ($_GET) {
    $x = strtotime($_GET['date']);
    $year = date('Y', $x);
    // $month = date('n', $x);
}
// dd($select_date);
?>
<div class="main-contents-rank">

            <div class="ranking">
                <div class="rank-top">
                    @if($_GET)
                        <div class="rank-day">{{ $year }}年成績表</div>
                    @else
                        <div class="rank-day">{{date('Y')}}年成績表</div>
                    @endif
                    <form action="{{ url('routine/ranking') }}" method="GET">
                        <div class="month-search">
                            <select name="date">
                            <!-- bladeテンプレートだと表示がバグる -->
                            @foreach($select_date as $date)
                                <option value="{{ $date }}"
                                
                                >{{ date_format(date_create($date), 'Y') }}年
                                </option>
                            @endforeach
                            @if ($max < date('Y'))
                                <option selected="selected">{{ date('Y') }}年</option>
                            @endif
                            </select>
                            <button type="submit" class="btn btn-outline-dark">→表示</button>
                        </div>
                    </form>
                </div>
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
            </div>
        </div>

@endsection