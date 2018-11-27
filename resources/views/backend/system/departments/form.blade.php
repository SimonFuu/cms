@extends('backend.layouts.layouts')
@section('content')
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">添加部门</h3>
        </div>
        {!! Form::open(['url' => route('backend.system.departments.store'), 'method' => 'POST', 'class' => 'form-horizontal departments-submit-form', 'role' => 'form']) !!}
        <!-- class include {'form-horizontal'|'form-inline'} -->
            <div class="box-body">
                <!--- name Field --->
                <div class="form-group {{ $errors -> has('name') ? 'has-error' : '' }}">
                    {!! Form::label('name', '名称 * ：', ['class' => 'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => '请输入名称']) !!}
                        @if($errors -> has('name'))
                            <span class="help-block">
                                <strong>{{ $errors -> first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <!--- parent_id Field --->
                <div class="form-group {{ $errors -> has('parent_id') ? 'has-error' : '' }}">
                    {!! Form::label('parent_id', '上级部门 * ：', ['class' => 'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        <div class="input-group">
                            <label for="parentDepartmentName"></label>
                            <input type="text" class="form-control" id="parentDepartmentName" placeholder="请选择上级部门" value="" readonly>
                            <div class="input-group-btn">
                                <button class="btn btn-success selectParentDepartmentButton" type="button" data-toggle="modal" data-target="#selectParentDepartmentModal">选择</button>
                            </div>
                            <input type="hidden" name="parent_id" value="">
                        </div>
                        @if($errors -> has('parent_id') )
                            <span class="help-block">
                                <strong>{{ $errors -> first('parent_id') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <!--- code Field --->
                <div class="form-group {{ $errors -> has('code') ? 'has-error' : '' }}">
                    {!! Form::label('code', '部门代码 *：', ['class' => 'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('code', null, ['class' => 'form-control', 'placeholder' => '请输入部门代码']) !!}
                        @if($errors -> has('code') )
                            <span class="help-block">
                                <strong>{{ $errors -> first('code') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <!--- weight Field --->
                <div class="form-group {{ $errors -> has('weight') ? 'has-error' : '' }}">
                    {!! Form::label('weight', '展示权重 *：', ['class' => 'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        {!! Form::number('weight', 1000, ['class' => 'form-control', 'placeholder' => '请输入0-1000的数字，数字越小，展示越靠前']) !!}
                        @if($errors -> has('parent_id') )
                            <span class="help-block">
                                <strong>{{ $errors -> first('weight') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <!--- desc Field --->
                <div class="form-group {{ $errors -> has('desc') ? 'has-error' : '' }}">
                    {!! Form::label('desc', '描述 *：', ['class' => 'control-label col-sm-2']) !!}
                    <div class="col-sm-10">
                        {!! Form::textarea('desc', null, ['class' => 'form-control', 'rows' => 5, 'placeholder' => '请输入部门描述！']) !!}
                        @if($errors -> has('parent_id') )
                            <span class="help-block">
                                <strong>{{ $errors -> first('desc') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <a href="{{ route('backend.system.departments') }}" class="btn btn-default">返回</a>
                <button class="btn btn-info pull-right" type="submit">提交</button>
            </div>
        {!! Form::close() !!}
    </div>
    <!-- select-parent-department-modal -->
    <div class="modal fade" id="selectParentDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="selectParentDepartmentModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="selectParentDepartmentModalLabel">请选择上级部门</h4>
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