@extends('layouts.app')

@section('content')

<div class="main-contents-index">
    @include('common.errors')
    @if (session('message'))
        <div class="container mt-2">
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        </div>
    @endif
    @can('admin-higher')
    <div class="routine-register">
        <div class="routine-name" >ルーチン登録</div>
        <form action="{{ url('routine_store') }}" method="POST" class="form-inline">
        {{ csrf_field() }}
            <div class="form-group form-inline">
                <input type="text" name="name" id="routine-name" class="form-control" value="{{old('name')}}" placeholder="ルーチン名">
            </div>
            <div class="form-group">
                <label>スペース</label>
                <select name="space">
                    <option value="">未設定</option> 
                    @foreach($space as $val)
                    <option value="{{$val->id}}" @if (old('space') == $val->id)
                            selected
                        @endif>{{$val->space}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>ポイント</label>
                <select name="point">
                    <option value="">未設定</option> 
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
            <div class="form-group">
                <label>目安頻度</label>
                <select name="frequency">
                    <option value="">未設定</option> 
                    @foreach($frequency as $val)
                    <option value="{{$val->id}}" @if (old('frequency') == $val->id)
                            selected
                        @endif>{{$val->frequency}}</option>
                    @endforeach
                </select>
            </div>
        <!-- タスク追加ボタン -->
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-primary">追加</button>
                </div>
            </div>
        </form>
    </div>
    @endcan
    <div>
        <div class="contents-name">実施ルーチン登録</div>
        <div class="routine-space">
            <form action="{{ url('routine') }}" method="GET" class="form-inline" id="form0">
                <div class="area 
                @if(isset($_GET['select']))
                    @if($_GET['select'] == 0)
                        background
                    @endif
                @endif" id="0">
                    <div class="space-name">全て</div>
                    <div>></div>
                </div>
                <input type="hidden" name="select" value="0">
            </form>
            @foreach($space as $val)
            <form action="{{ url('routine') }}" method="GET" class="form-inline" id="form{{$val->id}}">
                <div class="area 
                @if(isset($_GET['select']))
                    @if($_GET['select'] == $val->id)
                        echo background
                    @endif
                @endif" id="{{$val->id}}">
                    <div class="space-name">{{$val->space}}</div>
                    <div>></div>
                </div>
                <input type="hidden" name="select" value="{{$val->id}}">
            </form>
            @endforeach
        </div>
        @if(count($routines)>0)
        <div class="routine">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ルーチン名</th>
                        <th>ポイント</th>
                        <th>目安頻度</th>
                        <th>&nbsp;</th>
                        @can('admin-higher')
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach($routines as $routine)
                    <tr>
                        <td>{{$routine->routine_name}}</td>
                        <td>{{$routine->point}}</td>
                        <td>{{$routine->frequency->frequency}}</td>
                        <td><form action="{{ url('routine/register') }}" method="POST" class="form-inline">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-primary btn-sm">登録</button>
                            <input type="hidden" name="routine_name" value="{{$routine->routine_name}}">
                            <input type="hidden" name="point" value="{{$routine->point}}">
                            </form>
                        </td>
                        @can('admin-higher')
                        <td>
                            <a href="routine/{{$routine->id}}/edit"><button type="button" class="btn btn-primary btn-sm">編集</button></a>
                        </td>
                        <td><form action="{{url('routine/'.$routine->id)}}" method="POST" class="form-inline delete">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <button type="submit" class="btn btn-danger btn-sm">削除</button>
                            </form>
                        </td>
                        @endcan
                    </tr>
                    
                   @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>


@endsection