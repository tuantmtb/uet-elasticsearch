@extends('layouts.page')

@section('page-level-styles')
    @parent
    {{Html::style('metronic/pages/css/login.min.css')}}
@endsection

@section('page-title')
    Đăng ký
@endsection

@section('body-class')
    @parent login
@endsection

@section('page-body')
    <div class="content">
        {{Form::open(['method' => 'post', 'class' => 'register-form', 'novalidate' => 'novalidate', 'style' => 'display: block;'])}}
        <h3 class="font-green">Đăng ký</h3>
        <div class="form-group {{$errors->has('name') ? 'has-error' : ''}}">
            <label class="control-label visible-ie8 visible-ie9">Tên</label>
            <input class="form-control placeholder-no-fix" type="text" placeholder="Tên" name="name">
            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
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

        <div class="form-group {{$errors->has('password.confirmed') ? 'has-error' : ''}}">
            <label class="control-label visible-ie8 visible-ie9">Xác nhận mật khẩu</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off"
                   placeholder="Xác nhận mật khẩu" name="password_confirmation">
            @if ($errors->has('password.confirmed'))
                <span class="help-block">
                    <strong>{{ $errors->first('password.confirmed') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-actions">
            <a href="{{route('login')}}" id="register-back-btn" class="btn btn-default">Quay lại</a>
            <button type="submit" id="register-submit-btn" class="btn btn-success uppercase pull-right">Đăng ký</button>
        </div>
        {{Form::close()}}
    </div>
@endsection