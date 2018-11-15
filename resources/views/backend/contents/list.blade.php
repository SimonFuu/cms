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
            <table class="table table-hover contents-list text-center">
                <thead>
                <tr>
                    <th width="300">标题</th>
                    <th width="150">来源</th>
                    {{--<th width="150">板块</th>--}}
                    <th width="75">展示权重</th>
                    <th width="75">发布者</th>
                    <th width="150">发布时间</th>
                    <th width="100">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($contents as $content)
                    <tr class="contents-list-trs">
                        <td>{{ $content -> title }}</td>
                        <td>{{ $content -> source }}</td>
                        {{--<td></td>--}}
                        <td>{{ $content -> weight }}</td>
                        <td>{{ $content -> name }}</td>
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