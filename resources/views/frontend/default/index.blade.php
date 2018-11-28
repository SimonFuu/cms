@extends('frontend.default.layouts.layouts')
@section('content')
    <div class="main-container">
        <div class="columns">
            <section class="main">
                    <div class="main-carousel">
                        <div id="carousel-generic" class="carousel slide" data-ride="carousel">
                            <!-- Indicators -->
                            <ol class="carousel-indicators">
                                @php($topNewsCount = count($topNews))
                                @php($count = $topNewsCount >= 5 ? 5 : $topNewsCount)
                                @for($i = 0; $i < $count; $i ++)
                                    <li data-target="#carousel-generic" data-slide-to="{{ $i }}" class="{{ $i == 0 ? 'active' : '' }}"></li>
                                @endfor
                            </ol>
                            <!-- Wrapper for slides -->
                            <div class="carousel-inner" role="listbox">
                                @php($i = 0)
                                @for($i = 0; $i < $count; $i++)
                                    <div class="item {{ $i == 0 ? 'active' : ''}}">
                                        <img src="{{ $topNews[$i] -> thumb }}" alt="">
                                        <div class="carousel-caption">
                                            <a href="{{ route('module.detail', ['module' => $topModule -> code, 'id' => $topNews[$i] -> id]) }}">{{ $topNews[$i] -> title }}</a>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="main-content">
                        <ul class="none-list-style">
                            @if($topNewsCount > 5)
                                @for($i = 5; $i < $topNewsCount; $i++)
                                    @if($i == 5)
                                        <li>
                                            <h2>
                                                <a class="text-black top-common-news-first-title" href="{{ route('module.detail', ['module' => $topModule -> code, 'id' => $topNews[$i] -> id]) }}">{{ $topNews[$i] -> title }}</a>
                                                @if(is_null($topNews[$i] -> abst) || $topNews[$i] -> abst == '')
                                                    <span class="pull-right">
                                                        <sub style="font-size: 14px; font-weight: normal"><a href="{{ route('module.detail', ['module' => $topModule -> code, 'id' => $topNews[$i] -> id]) }}">[详情]</a></sub>
                                                    </span>
                                                @endif
                                            </h2>

                                            <div class="main-top-abstract">
                                                @if(!is_null($topNews[$i] -> abst) && $topNews[$i] -> abst != '')
                                                    {{ mb_strlen($topNews[$i] -> abst) > 60 ? mb_substr($topNews[$i] -> abst, 0, 60) . '...' : $topNews[$i] -> abst }}
                                                    <span class="pull-right">
                                                        <a href="{{ route('module.detail', ['module' => $topModule -> code, 'id' => $topNews[$i] -> id]) }}">[详情]</a>
                                                    </span>
                                                @endif
                                            </div>
                                        </li>
                                    @else
                                        <li>
                                            <a class="text-black top-common-news-title" href="{{ route('module.detail', ['module' => config('app.top_news.code'), 'id' => $topNews[$i] -> id]) }}">
                                                {{ $topNews[$i] -> title }}
                                            </a>
                                            <span class="pull-right">{{ date('m月d日', strtotime($topNews[$i] -> created_at)) }}</span>
                                        </li>
                                    @endif
                                @endfor
                            @endif
                        </ul>
                    </div>
                <div class="main-read-more text-right">
                    <a href="{{ route('module.list', ['module' => $topModule -> code]) }}">更多>>></a>
                </div>
            </section>
            <section class="specials row">
                {!! $special !!}
            </section>
            <section class="section-blocks row">
                <div class="col-md-7">
                    @foreach($sections[0] as $left)
                        <div class="left-section-block">
                            <div class="section-header">
                                <span class="header-name">{{ $left -> name }}</span>
                                <span class="header-read-more pull-right"><a href="{{ route('module.list', ['module' => $left -> code]) }}">点击查看更多>></a></span>
                            </div>
                            <div class="section-content">
                                <ul class="none-list-style">
                                    @if(isset($contents[$left -> id]))
                                        @php($i = 0)
                                        @foreach($contents[$left -> id] as $content)
                                            <li>
                                                <span class="left-section-content-title">
                                                    @if($left -> id == 2)
                                                        <a href="{{ route('department.list', ['department' => $content -> code]) }}">{{ $content -> dep }}</a>
                                                    @endif
                                                    <a href="{{ route('module.detail', ['module' => $left -> code, 'id' => $content -> id]) }}" class="text-black">
                                                        {{ $content -> title }}
                                                    </a>
                                                </span>
                                                <span class="pull-right">{{ date('m月d日', strtotime($content -> created_at)) }}</span>
                                            </li>
                                            @if($i == 4)
                                                <hr>
                                            @endif
                                            @php($i++)
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-md-5">
                    @foreach($sections[1] as $right)
                        <div class="right-section-block">
                            <div class="section-header">
                                <span class="header-name">{{ $right -> name }}</span>
                                <span class="header-read-more pull-right"><a href="{{ route('module.list', ['module' => $right -> code]) }}">点击查看更多>></a></span>
                            </div>
                            <div class="section-content">
                                <ul class="none-list-style">
                                    @if(isset($contents[$right -> id]))
                                        @php($i = 0)
                                        @foreach($contents[$right -> id] as $content)
                                            <li>
                                                <span>
                                                    {{--<a href="" class="section-content-department">{{  }}</a>--}}
                                                    <a href="{{ route('module.detail', ['module' => $right -> code, 'id' => $content -> id]) }}" class="right-section-content-title section-content-title">
                                                        {{ $content -> title }}
                                                    </a>
                                                </span>
                                            </li>
                                            @if($i == 4)
                                                @break
                                            @endif
                                            @php($i++)
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        <section class="site-navs">
            <div class="site-navs-container">
                <div class="site-navs-header">
                    <img src="/images/site_nav/site_nav.png" style="height: 38px; margin-right: 10px" alt="">网址导航
                </div>
                <hr>
                <div class="site-navs-content">
                    <!-- Nav tabs -->
                    <ul class="site-navs-content-header nav nav-tabs" role="tablist">
                        @foreach($navigation as $key => $group)
                            <li role="presentation" class="{{ $key == 0 ? 'active' : ''}}">
                                <a href="#group-{{ $group -> id }}" aria-controls="group-{{ $group -> id }}" role="tab" data-toggle="tab" class="text-black">{{ $group -> name }}</a>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Tab panes -->
                    <div class="site-navs-content-main tab-content">
                        @foreach($navigation as $key => $group)
                            <div role="tabpanel" class="tab-pane {{ $key == 0 ? 'active' : '' }}" id="group-{{ $group -> id }}">
                                @foreach($group -> links as $link)
                                    <div class="text-center">
                                        @if($group -> id == 1)
                                            <a class="text-black" href="{{ route('department.list', ['department' => $link -> link]) }}">{{ $link -> name }}</a>
                                        @else
                                            <a class="text-black" href="{{ $link -> link }}" target="_blank">{{ $link -> name }}</a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </div>



@endsection