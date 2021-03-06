<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{{ route('backend.index') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>纪监</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>毕节纪监内部工作网</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                {{--<!-- Messages: style can be found in dropdown.less-->--}}
                <li class="dropdown messages-menu">
                    <!-- Menu toggle button -->
                    <a href="{{ route('index') }}" target="_blank">
                        网站首页 <i class="fa fa-windows" aria-hidden="true"></i>
                    </a>
                </li>
                <!-- /.messages-menu -->

                {{--<!-- Notifications Menu -->--}}
                {{--<li class="dropdown notifications-menu">--}}
                    {{--<!-- Menu toggle button -->--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                        {{--<i class="fa fa-bell-o"></i>--}}
                        {{--<span class="label label-warning">10</span>--}}
                    {{--</a>--}}
                    {{--<ul class="dropdown-menu">--}}
                        {{--<li class="header">You have 10 notifications</li>--}}
                        {{--<li>--}}
                            {{--<!-- Inner Menu: contains the notifications -->--}}
                            {{--<ul class="menu">--}}
                                {{--<li><!-- start notification -->--}}
                                    {{--<a href="#">--}}
                                        {{--<i class="fa fa-users text-aqua"></i> 5 new members joined today--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<!-- end notification -->--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li class="footer"><a href="#">View all</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                {{--<!-- Tasks Menu -->--}}
                {{--<li class="dropdown tasks-menu">--}}
                    {{--<!-- Menu Toggle Button -->--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                        {{--<i class="fa fa-flag-o"></i>--}}
                        {{--<span class="label label-danger">9</span>--}}
                    {{--</a>--}}
                    {{--<ul class="dropdown-menu">--}}
                        {{--<li class="header">You have 9 tasks</li>--}}
                        {{--<li>--}}
                            {{--<!-- Inner menu: contains the tasks -->--}}
                            {{--<ul class="menu">--}}
                                {{--<li><!-- Task item -->--}}
                                    {{--<a href="#">--}}
                                        {{--<!-- Task title and progress text -->--}}
                                        {{--<h3>--}}
                                            {{--Design some buttons--}}
                                            {{--<small class="pull-right">20%</small>--}}
                                        {{--</h3>--}}
                                        {{--<!-- The progress bar -->--}}
                                        {{--<div class="progress xs">--}}
                                            {{--<!-- Change the css width attribute to simulate progress -->--}}
                                            {{--<div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"--}}
                                                 {{--aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">--}}
                                                {{--<span class="sr-only">20% Complete</span>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<!-- end task item -->--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li class="footer">--}}
                            {{--<a href="#">View all tasks</a>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{ Auth::user() -> name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        {{--<li class="user-header">--}}

                            {{--<p>--}}
                                {{--Auth - Web Developer--}}
                                {{--<small>Member since Nov. 2012</small>--}}
                            {{--</p>--}}
                        {{--</li>--}}
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <button  data-toggle="modal" data-target="#change-password-modal" class="btn btn-warning btn-flat change-password-btn">修改密码</button>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('backend.sign.out') }}" class="btn btn-danger btn-flat">退出登录</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
