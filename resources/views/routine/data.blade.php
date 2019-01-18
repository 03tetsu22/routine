@extends('layouts.app')

@section('content')

@can('admin-higher')
<div class="main-contents-index">
    @include('common.errors')
    @if (session('message'))
        <div class="container mt-2">
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        </div>
   @endif
    <div class="staff-admin">データ管理</div>
    <div class="edit-area">
        <div class="space-area">
            <div class="create-logo">ルーチンスペース</div>
            <form class="form-horizontal" method="POST" action="{{ url('routine/space/create') }}">
                {{ csrf_field() }}
                <div class="new-form">
                    <input type="text" name="space" class="new-create" value="{{old('space')}}" maxlength="10">
                    <button type="submit" class="btn btn-primary">追加</button>
                </div>
            </form>
            <div class="container scroll">
                <table class="table table-striped data-table">
                    <tbody>
                        @foreach($space as $space)
                        <tr>
                            <td>{{ $space->space }}</td>
                            <td><form action="{{url('routine/space/'.$space->id)}}" method="POST" class="form-inline delete">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}

                                <button type="submit" class="btn btn-danger btn-sm">削除</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="point-area">
            <div class="create-logo">ポイント</div>
            <form class="form-horizontal" method="POST" action="{{ url('routine/point/create') }}">
                {{ csrf_field() }}
                <div class="new-form">
                    <input type="text" name="point" class=" new-create" value="{{old('point')}}">
                    <button type="submit" class="btn btn-primary">追加</button>
                </div>
            </form>
            <div class="container scroll">
                <table class="table table-striped data-table">
                    <tbody>
                        @foreach($point as $point)
                        <tr>
                            <td>{{ $point->point }}</td>
                            <td><form action="{{url('routine/point/'.$point->id)}}" method="POST" class="form-inline delete">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}

                                <button type="submit" class="btn btn-danger btn-sm">削除</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="frequency-area">
            <div class="create-logo">目安頻度</div>
            <form class="form-horizontal" method="POST" action="{{ url('routine/frequency/create') }}">
                {{ csrf_field() }}
                <div class="new-form">
                    <input type="text" name="frequency" class=" new-create" value="{{old('frequency')}}">
                    <button type="submit" class="btn btn-primary">追加</button>
                </div>
            </form>
            <div class="container scroll">
                <table class="table table-striped data-table">
                    <tbody>
                        @foreach($frequency as $frequency)
                        <tr>
                            <td>{{ $frequency->frequency }}</td>
                            <td><form action="{{url('routine/frequency/'.$frequency->id)}}" method="POST" class="form-inline delete">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}

                                <button type="submit" class="btn btn-danger btn-sm">削除</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endcan

@endsection