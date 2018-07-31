@extends('layouts.app')

@section('content')
<?php
if ($_GET) {
    $x = strtotime($_GET['date']);
    $year = date('Y', $x);
    // $month = date('n', $x);
}
// dd($select_date);
$rank = 0;
$count = 1;
$lastscore = 100000;
?>
<div class="main-contents-rank">

            <div class="ranking">
                <div class="rank-top">
                    @if($_GET)
                        <div class="rank-day">{{ $year }}年成績表</div>
                    @else
                        <div class="rank-day">{{date('Y')}}年成績表</div>
                    @endif
                    <form action="{{ url('routine/ranking-year') }}" method="GET" class="select_form">
                        <div class="month-search">
                            <select name="date" class="select_submit">
                            <!-- bladeテンプレートだと表示がバグる -->
                            @foreach($select_date as $date)
                                <option value="{{ $date }}"
                                <?php
                                if ($_GET && $_GET['date'] == $date) {
                                    echo 'selected';
                                } else if (!$_GET && $date == date('Y-m')) {
                                    echo 'selected';
                                }
                                ?>
                                >{{ date_format(date_create($date), 'Y') }}年
                                </option>
                            @endforeach
                            @if ($max < date('Y'))
                                <option selected="selected">{{ date('Y') }}年</option>
                            @endif
                            </select>
                            <!-- <button type="submit" class="btn btn-outline-dark">→表示</button> -->
                        </div>
                    </form>
                </div>
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
            </div>
        </div>

@endsection