@extends('layouts.app')

@section('content')
<?php
foreach ($staffs as $staff) {
    if ($staff->id == $select_staff) {
        $select_name = $staff->family_name;
        $select_id = $staff->id;
    }
}
?>
<div class="main-contents-history">

    <div class="history">
        <div class="history-top">
            @if(Auth::id() == $select_staff)
                <div class="history-label">あなたの登録履歴</div>
            @else
                <div class="history-label">{{ $select_name }}さんの登録履歴</div>
            @endif
            <form action="{{ url('routine/history') }}" method="GET" class="select_form">
                <div class="month-search">
                    <select name="id" class="select_submit">
                        @foreach($staffs as $staff)
                            <option value="{{ $staff->id }}"
                            @if($select_id == $staff->id)
                                selected
                            @endif 
                            >{{ $staff->family_name }} {{ $staff->given_name }}</option>
                        @endforeach
                    </select>
                    <!-- <button type="submit" class="btn btn-outline-dark">→表示</button> -->
                </div>
            </form>
        </div>
        @if(count($history)>0)
        <div class="container">
            <table class="table table-striped rank-table">
                <thead>
                    <tr>
                        <th>ルーチン名</th>
                        <th>スペース</th>
                        <th>ポイント</th>
                        <th>実施日</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $val)
                    <tr>
                        <td>{{ $val->routine_name }}</td>
                        <td>{{ $val->space }}</td>
                        @if($val->pt == NULL)
                            <td>--</td>
                        @else
                            <td>{{ $val->pt->point }}</td>
                        @endif
                        <td>{{ date_format($val->created_at, 'Y年n月j日') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                {{ $history->links('vendor.pagination.bootstrap-4') }}
            </table>
            {{ $history->links('vendor.pagination.bootstrap-4') }}
        </div>
        @endif
    </div>
</div>

@endsection