@extends('backend.layouts.layouts')
@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">文章列表</h3>
            <div class="box-tools">
                <a href="{{ route('backend.contents.add') }}" class="btn btn-sm btn-info">添加</a>
            </div>
        </div>
        {{--<!-- /.box-header -->--}}
        <div class="box-body table-responsive">
            <div class="search-header">
            {!! Form::open(['url' => route('backend.contents'), 'method' => 'GET', 'class' => 'form-inline', 'role' => 'form']) !!}
            <!-- class include {'form-horizontal'|'form-inline'} -->
                <!--- Title Field --->
                <div class="form-group form-group-sm">
                    {!! Form::label('title', '标题:', ['class' => 'control-label']) !!}
                    {!! Form::text('title', isset($condition['title']) ? $condition['title'] : null, ['class' => 'form-control', 'placeholder' => '请输入标题']) !!}
                </div>
                <!--- Col ID Field --->
                <div class="form-group form-group-sm">
                    {!! Form::label('m_id', '板块:', ['class' => 'control-label']) !!}
                    {!! Form::select('m_id', $modules, isset($condition['m_id']) ? $condition['m_id'] : null, ['class' => 'form-control', 'placeholder' => '请选择模块']) !!}
                </div>
                <input type="submit" class="btn btn-info btn-sm" value="查询">
                {!! Form::close() !!}
                <hr>
            </div>
{{--            {{ dd($orderParams) }}--}}
            <table class="table table-hover contents-list text-center">
                <thead>
                <tr>
                    <th width="300">标题</th>
                    <th width="150">
                        @if(isset($orderParams['order']['source']))
                            <a class="text-black contents-order-column" href="{{ route('backend.contents', $orderParams) }}">来源
                                <i class="fa fa-sort-amount-{{ $orderParams['order']['source'] == 'asc' ? 'desc' : 'asc' }}"></i>
                            </a>
                        @else
                            @php
                                $params = $condition;
                                unset($params['order']);
                                $params['order']['source'] = 'desc';
                            @endphp
                            <a class="text-black contents-order-column" href="{{ route('backend.contents', $params) }}">来源
                                <i class="fa fa-sort"></i>
                            </a>
                        @endif</th>
                    <th width="75">
                        @if(isset($orderParams['order']['pub']))
                            <a class="text-black contents-order-column" href="{{ route('backend.contents', $orderParams) }}">发布者
                                <i class="fa fa-sort-amount-{{ $orderParams['order']['pub'] == 'asc' ? 'desc' : 'asc' }}"></i>
                            </a>
                        @else
                            @php
                                $params = $condition;
                                unset($params['order']);
                                $params['order']['pub'] = 'desc';
                            @endphp
                            <a class="text-black contents-order-column" href="{{ route('backend.contents', $params) }}">发布者
                                <i class="fa fa-sort"></i>
                            </a>
                        @endif
                    </th>
                    <th width="75">
                        @if(isset($orderParams['order']['dep']))
                            <a class="text-black contents-order-column" href="{{ route('backend.contents', $orderParams) }}">部门
                                <i class="fa fa-sort-amount-{{ $orderParams['order']['dep'] == 'asc' ? 'desc' : 'asc' }}"></i>
                            </a>
                        @else
                            @php
                                $params = $condition;
                                unset($params['order']);
                                $params['order']['dep'] = 'desc';
                            @endphp
                            <a class="text-black contents-order-column" href="{{ route('backend.contents', $params) }}">部门
                                <i class="fa fa-sort"></i>
                            </a>
                        @endif

                        {{--<a class="text-black contents-order-column" data-type="dep" href="">部门--}}
                            {{--@if(isset($condition['order]']['dep']))--}}
                                {{--<i class="fa fa-sort-amount-{{ $condition['order]']['pub'] }}"></i>--}}
                            {{--@else--}}
                                {{--<i class="fa fa-sort"></i>--}}
                            {{--@endif--}}
                        {{--</a>--}}
                    </th>
                    <th width="75">
                        @if(isset($orderParams['order']['weight']))
                            <a class="text-black contents-order-column" href="{{ route('backend.contents', $orderParams) }}">展示权重
                                <i class="fa fa-sort-amount-{{ $orderParams['order']['weight'] == 'asc' ? 'desc' : 'asc' }}"></i>
                            </a>
                        @else
                            @php
                                $params = $condition;
                                unset($params['order']);
                                $params['order']['weight'] = 'desc';
                            @endphp
                            <a class="text-black contents-order-column" href="{{ route('backend.contents', $params) }}">展示权重
                                <i class="fa fa-sort"></i>
                            </a>
                        @endif
                        {{--<a class="text-black contents-order-column" data-type="weight" href="">展示权重--}}
                            {{--@if(isset($condition['order]']['weight']))--}}
                                {{--<i class="fa fa-sort-amount-{{ $condition['order]']['pub'] }}"></i>--}}
                            {{--@else--}}
                                {{--<i class="fa fa-sort"></i>--}}
                            {{--@endif--}}
                        {{--</a>--}}
                    </th>
                    <th width="150">
                        @if(isset($orderParams['order']['ctime']))
                            <a class="text-black contents-order-column" href="{{ route('backend.contents', $orderParams) }}">创建时间
                                <i class="fa fa-sort-amount-{{ $orderParams['order']['ctime'] == 'asc' ? 'desc' : 'asc' }}"></i>
                            </a>
                        @else
                            @php
                                $params = $condition;
                                unset($params['order']);
                                $params['order']['ctime'] = 'desc';
                            @endphp
                            <a class="text-black contents-order-column" href="{{ route('backend.contents', $params) }}">创建时间
                                <i class="fa fa-sort"></i>
                            </a>
                        @endif

                        {{--<a class="text-black contents-order-column" data-type="ctime" href="">发布时间--}}
                            {{--@if(isset($condition['order]']['ctime']))--}}
                                {{--<i class="fa fa-sort-amount-{{ $condition['order]']['pub'] }}"></i>--}}
                            {{--@else--}}
                                {{--<i class="fa fa-sort"></i>--}}
                            {{--@endif--}}
                        {{--</a>--}}
                    </th>
                    <th width="100">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($contents as $content)
                    <tr class="contents-list-trs">
                        <td>{{ $content -> title }}</td>
                        <td>{{ $content -> source }}</td>
                        <td>{{ $content -> name }}</td>
                        <td>{{ $content -> dep_name }}</td>
                        <td>{{ $content -> weight }}</td>
                        <td>{{ $content -> created_at }}</td>
                        <td>
                            <a class="btn btn-xs btn-primary" href="{{ route('backend.contents.edit', ['id' => $content -> id]) }}">编辑</a>
                            <a class="btn btn-xs btn-danger" href="{{ route('backend.contents.delete', ['id' => $content -> id]) }}">删除</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
            {{ $contents -> appends(empty($condition) ? null : $condition) ->links() }}
        </div>
    </div>
@endsection