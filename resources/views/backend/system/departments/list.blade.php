@extends('backend.layouts.layouts')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">组织结构</h3>
                <div class="box-tools">
                    <a href="{{ route('backend.system.departments.add') }}" class="btn btn-primary btn-sm">添加</a>
                    <a href="{{ route('backend.system.departments.delete') }}" class="btn btn-danger btn-sm delete-department">删除</a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <div class="col-md-4">
                    <div class="departments departments-tree-menu" data-url="{{ route('backend.system.department') }}">
                        {{--List Html由 Controller 生成--}}
                        {!! $departmentsHtml !!}
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="departments">
                        {!! Form::open(['url' => route('backend.system.departments.store'), 'method' => 'POST', 'class' => 'form-horizontal departments-submit-form', 'role' => 'form']) !!}
                            <!-- class include {'form-horizontal'|'form-inline'} -->
                            <!--- name Field --->
                            <div class="form-group {{ $errors -> has('name') ? 'has-error' : '' }}">
                                {!! Form::label('name', '名称 * ：', ['class' => 'control-label col-md-4']) !!}
                                <div class="col-md-8">
                                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => '请输入名称', 'readonly']) !!}
                                    @if($errors -> has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors -> first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!--- parent_id Field --->
                            <div class="form-group {{ $errors -> has('parent_id') ? 'has-error' : '' }}">
                                {!! Form::label('parent_id', '上级部门 * ：', ['class' => 'control-label col-md-4']) !!}
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <label for="parentDepartmentName"></label>
                                        <input type="text" class="form-control" id="parentDepartmentName" placeholder="请选择上级部门" value="" readonly>
                                        <div class="input-group-btn">
                                            <button class="btn btn-success selectParentDepartmentButton" type="button" disabled="disabled" data-toggle="modal" data-target="#selectParentDepartmentModal">选择</button>
                                        </div>
                                        <input type="hidden" name="parent_id" value="">
                                    </div>
                                    @if($errors -> has('parent_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors -> first('parent_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!--- weight Field --->
                            <div class="form-group {{ $errors -> has('weight') ? 'has-error' : '' }}">
                                {!! Form::label('weight', '展示权重：', ['class' => 'control-label col-md-4']) !!}
                                <div class="col-md-8">
                                    {!! Form::number('weight', 1000, ['class' => 'form-control', 'placeholder' => '请输入0-100的数字，数字越小，展示越靠前']) !!}
                                    @if($errors -> has('weight'))
                                        <span class="help-block">
                                            <strong>{{ $errors -> first('weight') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!--- des Field --->
                            <div class="form-group {{ $errors -> has('des') ? 'has-error' : '' }}">
                                {!! Form::label('des', '描述：', ['class' => 'control-label col-md-4']) !!}
                                <div class="col-md-8">
                                    {!! Form::textarea('des', null, ['class' => 'form-control', 'rows' => 5, 'placeholder' => '请输入部门描述！']) !!}
                                    @if($errors -> has('des'))
                                        <span class="help-block">
                                            <strong>{{ $errors -> first('des') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <input type="hidden" name="id" value="-1">
                            <!--- Submit Field --->
                            <div class="form-group">
                                <div class="col-md-offset-4">
                                    <button class="btn btn-primary" type="submit">提交</button>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
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