 @extends('layouts.app')

@section('content')

@can('admin-higher')
<div class="main-contents-index">
    <div class="contents-name">ルーチン編集</div>
    @include('common.errors')
    <div class="routine-register">
        <!-- <div class="routine-name" >ルーチン登録</div> -->
        <form action="{{ url('routine/'.$routine->id) }}" method="POST" class="form-inline">
        {{ csrf_field() }}
        {{ method_field('patch') }}
            <div class="form-group form-inline">
                <input type="text" name="name" id="routine-name" class="form-control" value="{{old('name', $routine->routine_name)}}" placeholder="ルーチン名">
            </div>
            <div class="form-group">
                <label>スペース</label>
                <select name="space">
                    <option value="">未設定</option> 
                    @foreach($space as $val)
                    <option value="{{$val->id}}" @if($val->id == old('space', $routine->m_space_id))
                            selected
                        @endif>{{$val->space}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>ポイント</label>
                <select name="point">
                    <option value="">未設定</option> 
                    @foreach($point as $val)
                    <option value="{{$val->id}}" @if (old('point', $routine->point) == $val->id)
                            selected
                        @endif>{{$val->point}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>目安頻度</label>
                <select name="frequency">
                    <option value="">未設定</option> 
                    @foreach($frequency as $val)
                    <option value="{{$val->id}}" @if($val->id == old('frequency', $routine->m_frequency_id))
                            selected
                        @endif>{{$val->frequency}}</option>
                    @endforeach
                </select>
            </div>
        <!-- タスク追加ボタン -->
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-primary">更新</button>
                </div>
            </div>
        </form>
    </div>
    
</div>
@endcan

@endsection