<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>管理后台|毕节市纪委监委内部办公网</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="/images/site/favicon.png" title="Favicon" rel="shortcut icon">
    <link href="/plugins/bootstrap-3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/backend/plugins/adminlte/css/AdminLTE.min.css" rel="stylesheet" type="text/css">
    <link href="/backend/plugins/adminlte/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css">
    <link href="/plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="/backend/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css">
    <link href="/backend/plugins/bootstrap-fileinput-4.4.2/css/fileinput.css" rel="stylesheet" type="text/css">
    <link href="/backend/plugins/Ionicons/css/ionicons.min.css" rel="stylesheet" type="text/css">
    <link href="/backend/css/style.css?v={{ config('app.versions.statics') }}" rel="stylesheet" type="text/css">
</head>
<body class="hold-transition skin-red sidebar-mini login-page" style="height: 100%">
    <div class="loading-submit">

    </div>
    <div class="login-page-main">
        <div class="login-box">
            <div class="login-box-body">
                <h4 class="login-box-msg">毕节市纪委监委内部工作网</h4>
                <div class="login-box-form">
                    <form action="{{ route('backend.sign.in.post') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group form-group-lg has-feedback {{ $errors->has('username') || $errors->has('password') ? 'has-error' : '' }}">
                            <input type="text" name="username" class="form-control form-control-lg" placeholder="用户名">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>

                        </div>
                        <div class="form-group form-group-lg has-feedback {{ $errors->has('username') || $errors->has('password') ? 'has-error' : '' }}">
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="密码">
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
                        @elseif (session('success'))
                            <div class="form-group has-success">
                                <span class="help-block">
                                    <strong>{{ session('success') }}</strong>
                                </span>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-xs-12">
                                <button type="submit" class="btn btn-lg btn-primary btn-block btn-flat"><b>登录</b></button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->
    </div>
    <script>
        var modalHasError = "{{ count($errors) }}";
    </script>
<script language="JavaScript" src="/plugins/jquery-3.2.1.min.js"></script>
<script language="JavaScript" src="/plugins/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script language="JavaScript" src="/plugins/bootstrap-3.3.7/js/bootstrap.min.js"></script>
<script language="JavaScript" src="/backend/plugins/adminlte/js/adminlte.min.js"></script>
<script language="JavaScript" src="/backend/plugins/ueditor/ueditor.config.js"></script>
<script language="JavaScript" src="/backend/plugins/ueditor/ueditor.all.min.js"></script>
<script language="JavaScript" src="/backend/plugins/ueditor/lang/zh-cn/zh-cn.js"></script>
<script language="JavaScript" src="/backend/plugins/select2/dist/js/select2.full.min.js"></script>
<script language="JavaScript" src="/backend/plugins/bootstrap-fileinput-4.4.2/js/fileinput.js"></script>
<script language="JavaScript" src="/backend/plugins/bootstrap-fileinput-4.4.2/js/plugins/sortable.js"></script>
<script language="JavaScript" src="/backend/plugins/bootstrap-fileinput-4.4.2/js/locales/zh.js"></script>
<script language="JavaScript" src="/backend/js/app.js?v={{ config('app.versions.statics') }}"></script>
</body>
</html>