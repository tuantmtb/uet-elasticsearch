@extends('layouts.page')

@section('page-level-styles')
    @parent
    {{Html::style('metronic/pages/css/login.min.css')}}
    <style>
        .login .content .create-account {
            background-color: #0A9C52;
            color: white;
            -webkit-transition: all 0.3s;
            -moz-transition: all 0.3s;
            transition: all 0.3s;
        }

        .login .content .create-account:hover {
            background-color: #007F3E;
        }

        .login .content .create-account:focus {
            background-color: #006733;
        }
    </style>
@endsection

@section('page-title')
    Đăng nhập
@endsection

@section('body-class')
    @parent login
@endsection

@section('page-body')
    <div class="content">
        {{Form::open(['method' => 'post', 'class' => 'login-form', 'novalidate' => 'novalidate'])}}
        <h3 class="form-title font-green">Đăng nhập</h3>
        <div class="form-group {{$errors->has('email') ? 'has-error' : ''}}">
            <label class="control-label visible-ie8 visible-ie9">Địa chỉ email</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off"
                   placeholder="Địa chỉ email" name="email">
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group {{$errors->has('password') ? 'has-error' : ''}}">
            <label class="control-label visible-ie8 visible-ie9">Mật khẩu</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off"
                   placeholder="Mật khẩu" name="password">
            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-actions">
            <button type="submit" class="btn green uppercase">Đăng nhập</button>
            <label class="rememberme check">
                <input type="checkbox" name="remember" value="1" checked="checked"/>Nhớ phiên đăng nhập </label>
        </div>
        <a href="{{route('register')}}" id="register-btn" class="uppercase">
            <div class="create-account">
                <p>
                    Tạo tài khoản
                </p>
            </div>
        </a>
        {{Form::close()}}
    </div>
@endsection