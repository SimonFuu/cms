<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">请选择</li>
            @foreach($menus as $key => $menu)
                @if($menu['childrenMenus'])
                    <li class="sidebar-menu-item treeview {{$menu['active'] ? 'active' : '' }}">
                        <a href="javascript:void(0)"><i class="fa {{ $menu['icon'] }}" aria-hidden="true"></i><span> {{ $menu['name'] }}</span>
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul class="treeview-menu menu-open" style="display: {{ $menu['active'] ? 'block' : 'none' }}">
                            @foreach($menu['childrenMenus'] as $childMenu)
                                <li class="{{ $childMenu['active'] ? 'active' : '' }}"><a href="/{{ $childMenu['menu_uri'] }}"><i class="fa {{ $childMenu['icon'] }}" aria-hidden="true"></i>{{ $childMenu['name'] }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li class="sidebar-menu-item {{ ( $menu['active']) ? 'active' : '' }}"><a href="/{{ $menu['menu_uri'] }}"><i class="fa {{ $menu['icon'] }}" aria-hidden="true"></i><span> {{ $menu['name'] }}</span></a></li>
                @endif
            @endforeach
            <!-- Optionally, you can add icons to the links -->
            {{--<li class=""><a href="{{ route('backend.index') }}"><i class="fa fa-dashboard"></i> <span>首页</span></a></li>--}}
            {{--<li><a href="{{ route('backend.navigation') }}"><i class="fa fa-sitemap"></i> <span>导航管理</span></a></li>--}}
            {{--<li><a href="{{ route('backend.sections') }}"><i class="fa fa-desktop"></i> <span>首页板块管理</span></a></li>--}}
            {{--<li><a href="{{ route('backend.modules') }}"><i class="fa fa-list"></i> <span>模块管理</span></a></li>--}}
            {{--<li><a href="{{ route('backend.contents') }}"><i class="fa fa-archive"></i> <span>内容管理</span></a></li>--}}
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
            {{--<li class="treeview">--}}
                {{--<a href="#">--}}
                    {{--<i class="fa fa-sliders"></i> <span>网站设置</span>--}}
                    {{--<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li><a href="{{ route('backend.settings.links') }}">导航设置</a></li>--}}
                    {{--<li><a href="#">参数设置</a></li>--}}
                    {{--<li><a href="#">生成缓存</a></li>--}}
                {{--</ul>--}}
            {{--</li>--}}
            {{--<li class="treeview">--}}
                {{--<a href="#">--}}
                    {{--<i class="fa fa-cogs"></i> <span>系统管理</span>--}}
                    {{--<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li><a href="{{ route('backend.system.users') }}">用户管理</a></li>--}}
                    {{--<li><a href="{{ route('backend.system.roles') }}">角色管理</a></li>--}}
                    {{--<li><a href="{{ route('backend.system.actions') }}">权限与菜单管理</a></li>--}}
                    {{--<li><a href="{{ route('backend.system.departments') }}">部门管理</a></li>--}}
                {{--</ul>--}}
            {{--</li>--}}
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>