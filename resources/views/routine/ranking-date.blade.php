@extends('layouts.app')

@section('content')
<?php
if ($_GET) {
    $from_yaer = date_format(date_create($from), 'Y');
    $from_month = date_format(date_create($from), 'n');
    $from_day = date_format(date_create($from), 'j');
    $to_yaer = date_format(date_create($to), 'Y');
    $to_month = date_format(date_create($to), 'n');
    $to_day = date_format(date_create($to), 'j');
    $rank = 0;
    $count = 1;
    $lastscore = 100000;
}

?>
<div class="main-contents-rank">
    <div class="ranking">
        <div class="rank-top">
            @if($_GET && $from && $to)
                <div class="rank-fromto">{{ $from_yaer }}年{{ $from_month }}月{{ $from_day }}日から{{ $to_yaer }}年{{ $to_month }}月{{ $to_day }}日の成績表</div>
            @else
                <div class="rank-fromto">日付指定成績表</div>
            @endif
            <form action="{{ url('routine/ranking-date') }}" method="GET">
                <div class="month-search">
                    <input type="date" name="from" class="calender" value="@if(isset($from)){{ $from }}@endif">
                    <label>~</label>
                    <input type="date" name="to" class="calender" value="@if(isset($to)){{ $to }}@endif">
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
                        <?php
                        if ($result->sum < $lastscore) {
                            $rank = $rank + $count;
                            switch ($rank) {
                                case 1:
                                    echo '<td class="first">'.$rank.'st</td>';
                                    break;
                                case 2:
                                    echo '<td class="second">'.$rank.'nd</td>';
                                    break;
                                case 3:
                                    echo '<td class="third">'.$rank.'rd</td>';
                                    break;
                                default:
                                    echo '<td>'.$rank.'th</td>';
                                    break;
                            }
                            $lastscore = $result->sum;
                            $count = 1;
                        } elseif ($result->sum == $lastscore) {
                            switch ($rank) {
                                case 1:
                                     echo '<td class="first">'.$rank.'st</td>';
                                    break;
                                case 2:
                                    echo '<td class="second">'.$rank.'nd</td>';
                                    break;
                                case 3:
                                    echo '<td class="third">'.$rank.'rd</td>';
                                    break;
                                default:
                                    echo '<td>'.$rank.'th</td>';
                                    break;
                            }
                            $count++;
                        }
                        ?></td>
                        @if($result->staff == NULL)
                            <td>--</td>
                        @else
                            <td>{{$result->staff->family_name}}</td>
                        @endif
                        <td>{{$result->sum}}</td>
                    </tr>
                    @endforeach
                    <?php $rank++; ?>
                    @if(!empty($zero_family))
                        @foreach($zero_family as $family)
                        <tr>
                            @switch($rank)
                                @case(1)
                                    <td class="first">{{ $rank.'st' }}</td>
                                    @break
                                @case(2)
                                    <td class="second">{{ $rank.'nd' }}</td>
                                    @break
                                @case(3)
                                    <td class="third">{{ $rank.'rd' }}</td>
                                    @break
                                @default
                                    <td>{{ $rank.'th' }}</td>
                                    @break
                            @endswitch
                            <td>{{$family}}</td>
                            <td>0</td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        @endif
        @if ($mes !== 1)
            <div class="container mt-2">
                <div class="alert alert-success">
                    {{ $mes }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection