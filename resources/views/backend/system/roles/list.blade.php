@extends('backend.layouts.layouts')
@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">角色列表</h3>
            <div class="box-tools">
                <a href="{{ route("backend.system.roles.add") }}" class="btn btn-info btn-sm">添加</a>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table class="table table-hover actions-list">
                <thead>
                <tr>
                    <th width="50">#</th>
                    <th width="100">角色</th>
                    <th width="200">描述</th>
                    <th width="50">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($roles as $role)
                    <tr class="actions-list-trs">
                        <td>{{ $role -> id }}</td>
                        <td>{{ $role -> name }}</td>
                        <td>{{ $role -> desc }}</td>
                        <td>
                            <a class="btn btn-xs btn-primary" href="{{ route('backend.system.roles.edit', ['id' => $role -> id]) }}">编辑</a>
                            &nbsp;
                            <a class="btn btn-xs btn-danger" href="{{ route('backend.system.roles.delete', ['id' => $role -> id]) }}">删除</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
            {{ $roles -> links() }}
        </div>
    </div>
@endsection