@extends('frontend.default.layouts.layouts')
@section('content')
    <div class="main-container">
        <section class="main">
            <div>
                <ol class="breadcrumb">
                    <li><i class="fa fa-map-marker"></i>
                        <a href="{{ route('index') }}">首页</a>
                    </li>
                    <li class="active">{{ $module }}</li>
                </ol>
            </div>
            <section class="main-list">
                <ul class="none-list-style">
                    @foreach($contents as $content)
                        <li>
                            <a href="{{ route('module.detail', ['module' => $content -> code, 'id' => $content -> id]) }}" class="list-title text-black">
                                <span class="list-title">{{ $content -> title }}</span>
                            </a>
                            <span class="list-date pull-right">{{ date('Y年m月d日', strtotime($content -> created_at)) }}</span>

                        </li>
                    @endforeach
                </ul>
            </section>
            <section class="main-pagination">
                {{ $contents -> appends(!isset($condition) || empty($condition) ? null : $condition) ->links() }}
            </section>
        </section>
    </div>
@endsection