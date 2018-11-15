@extends('frontend.default.layouts.layouts')
@section('content')
    <div class="main-container">
        <div class="columns">
            <section class="main">

                    <div class="main-carousel">
                        <div id="carousel-generic" class="carousel slide" data-ride="carousel">
                            <!-- Indicators -->
                            <ol class="carousel-indicators">
                                @php($count = count($topCarouselNews))
                                @for($i = 0; $i < $count; $i ++)
                                    <li data-target="#carousel-generic" data-slide-to="{{ $i }}" class="{{ $i == 0 ? 'active' : '' }}"></li>
                                @endfor
                            </ol>
                            <!-- Wrapper for slides -->
                            <div class="carousel-inner" role="listbox">
                                @php($i = 0)
                                @foreach($topCarouselNews as $news)
                                    <div class="item {{ $i == 0 ? 'active' : ''}}">
                                        <img src="{{ $news -> thumb }}" alt="">
                                        <div class="carousel-caption">
                                            <a href="{{ route('module.detail', ['module' => config('app.top_news.code'), 'id' => $news -> id]) }}">{{ $news -> title }}</a>
                                        </div>
                                    </div>
                                    @php($i++)
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="main-content">
                        <ul class="none-list-style">
                            @foreach($topCommonNews as $key => $news)
                                @if($key == 0)
                                    <li>
                                        <h3><a class="text-black" href="{{ route('module.detail', ['module' => config('app.top_news.code'), 'id' => $news -> id]) }}">{{ $news -> title }}</a></h3>
                                        <div class="main-top-abstract">
                                            {{ mb_strlen($news -> abst) > 60 ? mb_substr($news -> abst, 0, 60) . '...' : $news -> abst }}
                                            <span class="pull-right">
                                                <a href="{{ route('module.detail', ['module' => config('app.top_news.code'), 'id' => $news -> id]) }}">[详情]</a>
                                            </span>
                                        </div>
                                    </li>
                                @else
                                    <li>
                                        <a class="text-black top-common-news-title" href="{{ route('module.detail', ['module' => config('app.top_news.code'), 'id' => $news -> id]) }}">
                                            {{ $news -> title }}
                                        </a>
                                        <span class="pull-right">{{ date('m月d日', strtotime($news -> created_at)) }}</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>

                    </div>
                <div class="main-read-more text-right">
                    <a href="{{ route('module.list', ['module' => config('app.top_news.code')]) }}">更多>>></a>
                </div>
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
                                                <span>
                                                        {{--<a href="" class="section-content-department">{{ $content -> dep }}</a>--}}
                                                    <a href="{{ route('module.detail', ['module' => $left -> code, 'id' => $content -> id]) }}" class="left-section-content-title section-content-title">
                                                        {{ $content -> dep }} {{ $content -> title }}
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
                                    {{--<li>--}}
                                        {{--<span>--}}
                                            {{--<a href="" class="section-content-title">"扶贫济困 已购代捐"倡议书</a>--}}
                                        {{--</span>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<span>--}}
                                            {{--<a href="" class="section-content-title">"扶贫济困 已购代捐"倡议书</a>--}}
                                        {{--</span>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<span>--}}
                                            {{--<a href="" class="section-content-title">"扶贫济困 已购代捐"倡议书</a>--}}
                                        {{--</span>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<span>--}}
                                            {{--<a href="" class="section-content-title">"扶贫济困 已购代捐"倡议书</a>--}}
                                        {{--</span>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<span>--}}
                                            {{--<a href="" class="section-content-title">"扶贫济困 已购代捐"倡议书</a>--}}
                                        {{--</span>--}}
                                    {{--</li>--}}
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
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
                    <li role="presentation" class="active">
                        <a href="#zjw" aria-controls="zjw" role="tab" data-toggle="tab" class="text-black">中央纪委监委</a>
                    </li>
                    <li role="presentation">
                        <a href="#swxsjg" aria-controls="swxsjg" role="tab" data-toggle="tab" class="text-black">省委巡视结构</a>
                    </li>
                    <li role="presentation">
                        <a href="#swpcjg" aria-controls="swpcjg" role="tab" data-toggle="tab" class="text-black">省委派出机构</a>
                    </li>
                    <li role="presentation">
                        <a href="#szjw" aria-controls="szjw" role="tab" data-toggle="tab" class="text-black">市州纪委</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="site-navs-content-main tab-content">
                    <div role="tabpanel" class="tab-pane active" id="zjw">
                        <div class="text-center">
                            <a class="text-black" href="http://www.ccdi.gov.cn/" target="_blank">中央纪委监委</a>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="swxsjg">
                        <div class="text-center">
                            <a class="text-black" href="http://www.ccdi.gov.cn/" target="_blank">中央纪委监委</a>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="swpcjg">
                        <div class="text-center">
                            <a class="text-black" href="http://www.ccdi.gov.cn/" target="_blank">中央纪委监委</a>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="szjw">
                        <div class="text-center">
                            <a class="text-black" href="http://www.gysjw.gov.cn/index.html" target="_blank">贵阳市纪委监委</a>
                        </div>
                        <div class="text-center">
                            <a class="text-black" href="http://jiwei.zunyi.gov.cn/" target="_blank">遵义市纪委监委</a>
                        </div>
                        <div class="text-center">
                            <a class="text-black" href="http://www.trjw.gov.cn/" target="_blank">铜仁市纪委监委</a>
                        </div>
                        <div class="text-center">
                            <a class="text-black" href="http://sjw.gzlps.gov.cn/" target="_blank">六盘水市纪委监委</a>
                        </div>
                        <div class="text-center">
                            <a class="text-black" href="http://jcj.anshun.gov.cn/" target="_blank">安顺市纪委监委</a>
                        </div>
                        <div class="text-center">
                            <a class="text-black" href="http://www.qxnlz.gov.cn/" target="_blank">黔西南纪委监委</a>
                        </div>
                        <div class="text-center">
                            <a class="text-black" href="http://www.qnzjwjcj.gov.cn/" target="_blank">黔南纪委监委</a>
                        </div>
                        <div class="text-center">
                            <a class="text-black" href="http://www.qdnzjw.gov.cn/" target="_blank">黔东南纪委监委</a>
                        </div>
                        <div class="text-center">
                            <a class="text-black" href="http://www.gadis.gov.cn/" target="_blank">贵安新区纪委监委</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection