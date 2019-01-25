@extends('frontend.default.layouts.layouts')
@section('content')
    <div class="main-container">
        <section class="main">
            <div>
                <ol class="breadcrumb">
                    <li><i class="fa fa-map-marker"></i>
                        <a href="{{ route('index') }}">首页</a>
                    </li>
                    <li class="active">下载中心</li>
                </ol>
            </div>
            <section class="main-list">
                <table class="table table-bordered text-center">
                    @foreach($items as $item)
                        <tr>
                            <td width="150" style="vertical-align: middle;">
                                {{ $item -> name }}
                            </td>
                            <td width="200" class="text-left" style="vertical-align: middle;">
                                {{ $item -> desc }}
                            </td>
                            <td width="50" style="vertical-align: middle;">
                                {{ $item -> size }}
                            </td>
                            <td width="100" style="vertical-align: middle;">
                                <a target="_blank" href="{{ $item -> src }}"> <i class="fa fa-download">点击下载</i></a>
                            </td>
                            <td width="120" style="vertical-align: middle;">
                                {{ date('Y年m月d日', strtotime($item -> created_at)) }}
                            </td>
                        </tr>
                    @endforeach
                </table>
                {{--<ul class="none-list-style">--}}
                    {{--@foreach($items as $item)--}}
                        {{--<li>--}}
                            {{--<a href="{{ $item -> src }}" class="list-title text-black">--}}
                                {{--<span class="list-title">{{ $item -> name }}</span>--}}
                            {{--</a>--}}
                            {{--<span class="list-date pull-right">{{ date('Y年m月d日', strtotime($item -> created_at)) }}</span>--}}
                        {{--</li>--}}
                    {{--@endforeach--}}
                {{--</ul>--}}
            </section>
        </section>
    </div>
@endsection