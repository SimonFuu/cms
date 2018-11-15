@extends('backend.layouts.common')
@section('body')
    <div class="login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="{{ route('index') }}"><b>毕节纪监内部工作网</b></a>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">欢迎登录 <br> 毕节市纪委监委内部工作网管理后台</p>
                <form action="{{ route('backend.sign.in.post') }}" method="post">
                    {{ csrf_field() }}
                    <div class="form-group has-feedback {{ $errors->has('username') || $errors->has('password') ? 'has-error' : '' }}">
                        <input type="text" name="username" class="form-control" placeholder="用户名">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>

                    </div>
                    <div class="form-group has-feedback {{ $errors->has('username') || $errors->has('password') ? 'has-error' : '' }}">
                        <input type="password" name="password" class="form-control" placeholder="密码">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    @if($errors->has('username') || $errors -> has('password'))
                        <div class="form-group has-error">
                            <span class="help-block">
                                <strong>{{ $errors->first() }}</strong>
                            </span>
                        </div>
                    @elseif (session('error'))
                        <div class="form-group has-error">
                            <span class="help-block">
                                <strong>{{ session('error') }}</strong>
                            </span>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-xs-offset-8 col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">登录</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->
    </div>

@endsection