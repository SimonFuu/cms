<div class="page-navs">
    <div class="div-navs">
        <div class="nav-block">
            <a href="{{ route('index') }}">首页</a>
        </div>
        @foreach($navs as $nav)
            <div class="nav-block">
                {{--TODO 外链地址模块ID 为 4--}}
                @if($nav -> type == 4)
                    <a href="{{ $nav -> code }}" target="_blank">{{ $nav -> name }}</a>
                @else
                    <a href="{{ route('module.list', ['module' => $nav -> code]) }}">{{ $nav -> name }}</a>
                @endif
            </div>
        @endforeach
    </div>
    <div class="banner-2">
        <div>
            <span class="banner-date">
                @php
                    $arr = array('天','一','二','三','四','五','六');
                @endphp
                {{ date('Y年m月d日 ') . '星期' . $arr[date('w')] }}
            </span>
            <div class="search-form pull-right">
                <span>站内搜索：</span>
                <form action="{{ route('index.search') }}" style="display: inline;">
                    <input type="text" name="word" placeholder="请输入搜索内容">
                    <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>