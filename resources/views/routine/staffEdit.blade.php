@extends('layouts.app')

@section('content')

@can('admin-higher')
<div class="main-contents-index">
    @if (session('message'))
        <div class="container mt-2">
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        </div>
   @endif
    <div class="staff-admin">社員編集</div>
    <div class="staff">
        <div class="panel-body form-staff">
            <form class="form-horizontal" method="POST" action="{{ url('routine/'.$staff->id.'/staff') }}">
                {{ csrf_field() }}
                {{ method_field('patch') }}
                <div class="form-group row">
                    <div class="form-group{{ $errors->has('family_name') ? ' has-error' : '' }}">
                        <label for="family_name" class="col-md-3 control-label">姓</label>

                        <div class="col-md-8">
                            <input id="family_name" type="text" class="form-control" name="family_name" value="{{ old('family_name', $staff->family_name) }}" required autofocus>

                            @if ($errors->has('family_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('family_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('given_name') ? ' has-error' : '' }}">
                        <label for="given_name" class="col-md-4 control-label">名</label>

                        <div class="col-md-8">
                            <input id="given_name" type="text" class="form-control" name="given_name" value="{{ old('given_name', $staff->given_name) }}" required autofocus>

                            @if ($errors->has('given_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('given_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-4 control-label">メールアドレス</label>

                    <div class="col-md-8">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email', $staff->email) }}" required>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <div class="checkbox">
                            <label>
                                admin権限付与<input type="checkbox" name="role" {{ old('role') ? 'checked' : '' }} value="5" 
                                @if($staff->role <= 5)
                                    checked
                                @endif>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group text-center">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">更新</button>
                        <a href="{{url('routine/staff')}}"><button type="button" class="btn btn-secondary">戻る</button></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

@endsection