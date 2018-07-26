@extends('layouts.app')

@section('content')
<?php
 $rest_month = 3 - $score_month->sum->sum;
 $rest_year = 50 - $score_year->sum->sum;
?>
<div class="main-contents-home">
    <div class="score">
        <div class="score-month">
            <div class="contents month">
                <div class="rest-pt">今月のノルマ達成まで</div>
                @if($rest_month > 0)
                    <div class="pt-month out">{{ $rest_month }}pt</div>
                @else
                    <div class="pt-month clear">0pt</div>
                @endif
            </div>
        </div>
        <div class="score-year">
            <div class="contents year">
                <div class="rest-pt">今年のノルマ達成まで</div>
                @if($rest_year > 0)
                    <div class="pt-year out">{{ $rest_year }}pt</div>
                @else
                    <div class="pt-year clear">0pt</div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection