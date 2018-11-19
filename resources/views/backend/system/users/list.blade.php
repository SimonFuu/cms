@extends('backend.layouts.layouts')
@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">人员列表</h3>
            <div class="box-tools">
                <a href="{{ route('backend.system.users.add') }}" class="btn btn-info btn-sm">添加</a>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <div class="search-header">
            {!! Form::open(['url' => route('backend.system.users'), 'method' => 'GET', 'class' => 'form-inline', 'role' => 'form']) !!}
            <!-- class include {'form-horizontal'|'form-inline'} -->
                <!--- Name Field --->
                <div class="form-group form-group-sm">
                    {!! Form::label('name', '姓名:', ['class' => 'control-label']) !!}
                    {!! Form::text('name', isset($condition['name']) ? $condition['name'] : null, ['class' => 'form-control', 'placeholder' => '请输入姓名！']) !!}
                </div>
                <!--- Telephone Field --->
                <div class="form-group form-group-sm">
                    {!! Form::label('username', '用户名:', ['class' => 'control-label']) !!}
                    {!! Form::text('username', isset($condition['username']) ? $condition['username'] : null, ['class' => 'form-control', 'placeholder' => '请输入用户名！']) !!}
                </div>
                <!--- Gender Field --->
                <div class="form-group form-group-sm">
                    {!! Form::label('department', '部门:', ['class' => 'control-label']) !!}
                    {!! Form::select('department', $departments, isset($condition['department']) ? $condition['department'] : null, ['class' => 'form-control', 'placeholder' => '请选择']) !!}
                </div>
                <input type="submit" class="btn btn-info btn-sm" value="查询">
                {!! Form::close() !!}
                <hr>
            </div>
            <table class="table table-hover actions-list">
                <thead>
                <tr>
                    <th width="100">用户名</th>
                    <th width="100">姓名</th>
                    <th width="100">部门</th>
                    <th width="50">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user -> username }}</td>
                        <td>{{ $user -> name }}</td>
                        <td>{{ $user -> dep_name }}</td>
                        <td>
                            <a class="btn btn-xs btn-primary" href="{{ route('backend.system.users.edit', ['id' => $user -> id]) }}">编辑</a>
                            <a class="btn btn-xs btn-danger" href="{{ route('backend.system.users.delete', ['id' => $user -> id]) }}">删除</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
            {{ $users -> appends(empty($condition) ? null : $condition) ->links() }}
        </div>
    </div>
@endsection