<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
<!--     <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1"> -->

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ルーチン管理システム</title>

    <!-- Styles -->
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <!-- <link href="{{asset('js/custom.js')}}" rel="javascript"> -->
    <!-- <link href="css/style.css" rel="stylesheet" type="text/css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/custom.js"></script>
</head>
<body>
    <header>
        <div class="main-header">
            <div class="title">ルーチン管理システム（仮）</div>
            <ul class="head-menu form-inline">
                @guest
                    <!-- <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li> -->
                @else
                    <li>{{ Auth::user()->family_name }} {{ Auth::user()->given_name }}</li>
                    <li><a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><button type="button">logout</button>
                    </a></li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        {{ csrf_field() }}
                    </form>
                @endguest
            </ul>
        </div>
    </header>
    @can('user-higher')
    <div class="side">
        <div>
            <ul class="sidebar">
                <li><a href="{{url('routine')}}">実施ルーチン登録</a></li>
                @can('admin-higher')
                <li><a href="{{url('routine/staff')}}">社員管理</a></li>
                @endcan
                <!-- <li>登録履歴</li> -->
                <li class="large-sidebar">成績表
                    <ul>
                        <li class="small-sidebar"><a href="{{url('routine/ranking')}}">月間</a></li>
                        <li class="small-sidebar"><a href="{{url('routine/ranking-year')}}">年間</a></li>
                        <li class="small-sidebar"><a href="{{url('routine/ranking-date')}}">日付指定</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    @endcan
        @yield('content')

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
