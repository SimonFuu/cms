@extends('frontend.default.layouts.layouts')
@section('content')
    <div class="main-container">
        <section class="main">
            <div>
                <ol class="breadcrumb">
                    <li><i class="fa fa-map-marker"></i>
                        <a href="{{ route('index') }}">首页</a>
                    </li>
                    <li class="active"><a href="{{ route('department.list', ['module' => $code]) }}">{{ $module }}</a></li>
                </ol>
            </div>
            <section class="main-detail">
                <div class="content-header">
                    <div class="content-tile text-center">
                        <h3>{{ $content -> title }}</h3>
                    </div>
                    <div class="content-info text-center">
                        <span class="content-source">来源：{{ $content -> source }}</span>
                        <span class="content-publish-at">发布时间：{{ date('Y年m月d日 H:i', strtotime($content -> created_at)) }}</span>

                    </div>
                </div>
                <hr>
                <div class="content-detail">
                    {!! $content -> content !!}
                </div>
            </section>

        </section>
    </div>
@endsection