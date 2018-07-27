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
    <div class="staff-admin">社員管理</div>
    <div class="staff">
        <div class="panel-body form-staff">
            <div>社員登録</div>
            <form class="form-horizontal" method="POST" action="{{ url('routine/create') }}">
                {{ csrf_field() }}
                <div class="form-group row">
                    <div class="form-group{{ $errors->has('family_name') ? ' has-error' : '' }}">
                        <label for="family_name" class="col-md-3 control-label">姓</label>

                        <div class="col-md-8">
                            <input id="family_name" type="text" class="form-control" name="family_name" value="{{ old('family_name') }}" required autofocus>

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
                            <input id="given_name" type="text" class="form-control" name="given_name" value="{{ old('given_name') }}" required autofocus>

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
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="col-md-4 control-label">パスワード</label>

                    <div class="col-md-8">
                        <input id="password" type="password" class="form-control" name="password" required>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="col-md-6 control-label">確認用パスワード</label>

                    <div class="col-md-8">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <div class="checkbox">
                            <label>
                                admin権限付与<input type="checkbox" name="role" {{ old('role') ? 'checked' : '' }} value="5">
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">登録</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="container staff-list">
            <table class="table table-striped staff-table">
                <thead>
                    <tr>
                        <th>氏名</th>
                        <th>メールアドレス</th>
                        <th>admin権限</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        
                    </tr>
                </thead>
                @foreach($staff as $staff)
                <tr>
                    <td>{{$staff->family_name}} {{$staff->given_name}}</td>
                    <td>{{$staff->email}}</td>
                    <td>@if($staff->role <= 5)
                            ◯
                        @else
                            ×
                        @endif</td>
                    <td>
                        <a href="{{$staff->id}}/staffEdit"><button type="button" class="btn btn-primary btn-sm">編集</button></a>
                    </td>
                    @if($staff->role > 5)
                        <td>
                            <form action="{{url('routine/staff/'.$staff->id)}}" method="POST" class="form-inline delete">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <button type="submit" class="btn btn-danger btn-sm">削除</button>
                            </form>
                        </td>
                    @else
                        <td>&nbsp;</td>
                    @endif
                    
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endcan

@endsection