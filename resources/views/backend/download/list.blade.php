@extends('backend.layouts.layouts')
@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">下载中心管理 </h3>
            &nbsp;
            <span>链接地址：{{ route('index.download') }}</span>
            <div class="box-tools">
                <a href="{{ route('backend.download.add') }}" class="btn btn-info btn-sm">添加</a>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table class="table table-hover items-list text-center">
                <thead>
                <tr>
                    <th width="100">名称</th>
                    <th width="200">描述</th>
                    <th width="100">权重</th>
                    <th width="100">大小</th>
                    <th width="200">创建时间</th>
                    <th width="100">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr class="items-list-trs">
                        <td>{{ $item -> name }}</td>
                        <td>{{ $item -> desc }}</td>
                        <td>{{ $item -> weight }}</td>
                        <td>{{ $item -> size }}</td>
                        <td>{{ $item -> created_at }}</td>
                        <td>
                            <a class="btn btn-xs btn-success" href="{{ $item -> src }}">下载</a>
                            <a class="btn btn-xs btn-primary" href="{{ route('backend.download.edit', ['id' => $item -> id]) }}">编辑</a>
                            <a class="btn btn-xs btn-danger" href="{{ route('backend.download.delete', ['id' => $item -> id]) }}">删除</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{--<!-- /.box-body -->--}}
        {{--<div class="box-footer clearfix">--}}
            {{--{{ $items -> links() }}--}}
        {{--</div>--}}
    </div>
@endsection