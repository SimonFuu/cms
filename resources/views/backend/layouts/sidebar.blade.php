<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">请选择</li>
            <!-- Optionally, you can add icons to the links -->
            <li class=""><a href="{{ route('backend.index') }}"><i class="fa fa-dashboard"></i> <span>首页</span></a></li>
            <li><a href="{{ route('backend.navigation') }}"><i class="fa fa-sitemap"></i> <span>导航管理</span></a></li>
            <li><a href="{{ route('backend.sections') }}"><i class="fa fa-desktop"></i> <span>首页板块管理</span></a></li>
            <li><a href="{{ route('backend.modules') }}"><i class="fa fa-list"></i> <span>模块管理</span></a></li>
            <li><a href="{{ route('backend.contents') }}"><i class="fa fa-archive"></i> <span>内容管理</span></a></li>
            {{--<li class="treeview">--}}
                {{--<a href="#">--}}
                    {{--<i class="fa fa-link"></i> <span>内容管理</span>--}}
                    {{--<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li><a href="#">Link in level 2</a></li>--}}
                    {{--<li><a href="#">Link in level 2</a></li>--}}
                {{--</ul>--}}
            {{--</li>--}}
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-sliders"></i> <span>网站设置</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#">Link in level 2</a></li>
                    <li><a href="#">Link in level 2</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-cogs"></i> <span>系统管理</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#">Link in level 2</a></li>
                    <li><a href="#">Link in level 2</a></li>
                </ul>
            </li>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>