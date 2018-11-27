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
    <link href="/backend/css/style.css?v={{ time() }}" rel="stylesheet" type="text/css">
</head>
<body class="hold-transition skin-red sidebar-mini login-page">
<div class="loading-submit">

</div>
@yield('body')
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
<script language="JavaScript" src="/backend/js/app.js?v={{ time() }}"></script>
</body>
</html>