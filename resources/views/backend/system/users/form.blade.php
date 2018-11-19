@extends('backend.layouts.layouts')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="box box-{{ is_null($user) ? 'info' : 'primary' }}">
            <div class="box-header with-border">
                <h3 class="box-title">{{ is_null($user) ? '添加' : '编辑'}}人员</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
        {!! Form::open(['url' => route('backend.system.users.store'), 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
        <!-- class include {'form-horizontal'|'form-inline'} -->
            <div class="box-body">
                <div class="form-group">
                    <!--- Name Field --->
                    <div class="user-form-group user-bottom-form-group {{ $errors -> has('name') ? 'has-error' : '' }}">
                        {!! Form::label('name', '姓名(*):', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            {!! Form::text('name', is_null($user) ? null : $user -> name, ['class' => 'form-control', 'placeholder' => '请输入用户姓名！']) !!}
                            @if($errors -> has('name'))
                                <span class="help-block form-help-block"><strong>{{ $errors -> first('name') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <!--- Telephone Field --->
                    <div class="user-form-group user-bottom-form-group {{ $errors -> has('username') ? 'has-error' : '' }}">
                        {!! Form::label('username', '用户名(*):', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            {!! Form::text('username', is_null($user) ? null : $user -> username, ['class' => 'form-control', 'placeholder' => '请输入用户名！']) !!}
                            @if($errors -> has('username'))
                                <span class="help-block form-help-block"><strong>{{ $errors -> first('username') }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>

                <!--- Password Field --->
                <div class="form-group">
                    <div class="user-form-group {{ $errors -> has('password') ? 'has-error' : '' }}">
                        {!! Form::label('password', '密码' . (is_null($user) ? '(*):' : ':'), ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => '请输入密码！']) !!}
                            @if($errors -> has('password'))
                                <span class="help-block form-help-block"><strong>{{ $errors -> first('password') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="user-form-group user-bottom-form-group {{ $errors -> has('password') ? 'has-error' : '' }}">
                        {!! Form::label('password', '确认密码' . (is_null($user) ? '(*):' : ':'), ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => '请重新输入密码！']) !!}
                            @if($errors -> has('password'))
                                <span class="help-block form-help-block"><strong>{{ $errors -> first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <!--- UserDepartment Field --->
                    <div class="user-form-group {{ $errors -> has('dep_id') ? 'has-error' : '' }}">
                        {!! Form::label('dep_id', '所属部门(*):', ['class' => 'control-label col-sm-2']) !!}
                        <div class="col-sm-3">
                            <div class="input-group">
                                <label for="departmentName"></label>
                                <input type="text" class="form-control" id="departmentName" placeholder="请选择部门" value="{{ is_null($user) ? '' : $user -> dep_name }}" readonly>
                                <div class="input-group-btn">
                                    <button class="btn btn-success selectUserDepartmentButton" type="button" data-toggle="modal" data-target="#selectUserDepartmentModal">选择</button>
                                </div>
                                <input type="hidden" name="dep_id" value="{{ is_null($user) ? '' : $user -> dep_id }}">
                            </div>
                            @if($errors -> has('dep_id'))
                                <span class="help-block form-help-block"><strong>{{ $errors -> first('dep_id') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <!--- office_tel Field --->
                    <div class="user-form-group {{ $errors -> has('weight') ? 'has-error' : '' }}">
                        {!! Form::label('weight', '显示权重(*):', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            {!! Form::number('weight', is_null($user) ? 1000 : $user -> weight, ['class' => 'form-control',]) !!}
                            <small>人员在部门内的展示权重</small>
                            @if($errors -> has('weight'))
                                <span class="help-block form-help-block"><strong>{{ $errors -> first('weight') }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>

                <!--- UserRoles Field --->
                <div class="form-group admin-roles {{ $errors -> has('roles') ? 'has-error' : '' }}">
                    {!! Form::label('roles', '用户角色:', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-offset-1 col-sm-10">
                        <hr>
                        <div class="user-roles-list">
                            @foreach($roles as $role)
                                <div class="col-sm-2">
                                    <table class="table table-bordered table-condensed user-roles-list-table">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <label>
                                                    @if(is_null($user))
                                                        <input type="checkbox" name="roles[]" value="{{ $role -> id }}">&nbsp;&nbsp;{{ $role -> name }}
                                                    @else
                                                        <input type="checkbox" name="roles[]" value="{{ $role -> id }}" {{ in_array($role -> id, $user -> roles) ? 'checked' : ''}}>&nbsp;&nbsp;{{ $role -> name }}
                                                    @endif
                                                </label>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                        @if($errors -> has('roles'))
                            <span class="help-block form-help-block"><strong>{{ $errors -> first('roles') }}</strong></span>
                        @endif
                    </div>
                </div>
                @if(!is_null($user))
                    <input type="hidden" name="id" value="{{ $user -> id }}">
                @endif
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <a href="{{ route('backend.system.users') }}" class="btn btn-default">返回</a>
                <button type="submit" class="btn btn-{{ is_null($user) ? 'info' : 'primary' }} pull-right">提交</button>
            </div>
            <!-- /.box-footer -->
            {!! Form::close() !!}
        </div>
    </section>
    <!-- /.content -->
    <!-- select-parent-department-modal -->
    <div class="modal fade" id="selectUserDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="selectUserDepartmentModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="selectUserDepartmentModalLabel">请选择所属部门</h4>
                </div>
                <div class="modal-body">
                    <div class="departments-list-on-modal">
                        <ul class="tree-menu">
                            <li>
                                {!! $departmentsHtml !!}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.select-parent-department-modal -->
@endsection