@extends('backend.layouts.layouts')
@section('content')
    {!! Form::open(['url' => route('backend.modules.store'), 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
        <!-- class include {'form-horizontal'|'form-inline'} -->

        <div class="box box-{{ isset($module) ? 'primary' : 'info' }}">
            <div class="box-header">
                <h3 class="box-title">{{ isset($module) ? '编辑' : '添加' }}模块</h3>
                &nbsp;
                <span class="text-red"><b>请勿上传、发布涉密内容！</b></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if(isset($module))
                    <input type="hidden" name="id" value="{{ $module -> id }}">
                @endif
                <!--- Name Field --->
                <div class="form-group {{ $errors -> has('name') ? 'has-error' : '' }}">
                    <div class=" col-xs-2 text-right">
                        {!! Form::label('name', '名称:', ['class' => 'control-label']) !!}
                    </div>
                    <div class="col-md-6">
                        {!! Form::text('name', isset($module) ? $module -> name : null, ['class' => 'form-control']) !!}
                        @if($errors -> has('name'))
                            <span class="help-block form-help-block"><strong>{{ $errors -> first('name') }}</strong></span>
                        @endif
                    </div>
                </div>

                <!--- type Field --->
                <div class="form-group">
                    <div class=" col-xs-2 text-right">
                        {!! Form::label('type', '类型:', ['class' => 'control-label']) !!}
                    </div>
                    <div class="col-md-6">
                        {!! Form::select('type', $types, isset($module) ? $module -> type : null, ['class' => 'form-control']) !!}
                        @if($errors -> has('type'))
                            <span class="help-block form-help-block"><strong>{{ $errors -> first('type') }}</strong></span>
                        @endif
                    </div>

                </div>

                <!--- Code Field --->
                <div class="form-group {{ $errors -> has('code') ? 'has-error' : '' }}">
                    <div class=" col-xs-2 text-right">
                        {!! Form::label('code', '板块代码:', ['class' => 'control-label']) !!}
                    </div>
                    <div class="col-md-6">
                        {!! Form::text('code', isset($module) ? $module -> code : null, ['class' => 'form-control']) !!}

                        @if($errors -> has('code'))
                            <span class="help-block form-help-block"><strong>{{ $errors -> first('code') }}</strong></span>
                        @endif
                    </div>
                </div>

                <!--- Thumbnail Field --->
                <div class="form-group thumbnail-group hide {{ $errors -> has('thumbnail') ? 'has-error' : '' }}">
                    <div class=" col-xs-2 text-right">
                        {!! Form::label('thumbnail', '专题图:', ['class' => 'control-label']) !!}
                    </div>
                    <div class="col-md-6">
                        <input id="thumbnail-container" name="thumbnail-container" data-url="{{ route('backend.upload.thumbnail') }}" multiple type="file" accept="image/*">
                        <input type="hidden" id="thumbnail" name="thumbnail" value="{{ isset($module) ? $module -> thumbnail : '' }}">
                        @if($errors -> has('thumbnail'))
                            <span class="help-block form-help-block"><strong>{{ $errors -> first('thumbnail') }}</strong></span>
                        @endif
                    </div>
                </div>

                <!--- Weight Field --->
                <div class="form-group {{ $errors -> has('weight') ? 'has-error' : '' }}">
                    <div class=" col-xs-2 text-right">
                        {!! Form::label('weight', '权重:', ['class' => 'control-label']) !!}
                    </div>
                    <div class="col-md-6">
                        {!! Form::number('weight', isset($module) ? $module -> weight : 1000, ['class' => 'form-control']) !!}
                        @if($errors -> has('weight'))
                            <span class="help-block form-help-block"><strong>{{ $errors -> first('weight') }}</strong></span>
                        @endif
                    </div>
                </div>

                <!--- Desc Field --->
                <div class="form-group {{ $errors -> has('desc') ? 'has-error' : '' }}">
                    <div class="col-xs-2 text-right">
                        {!! Form::label('desc', '描述:', ['class' => 'control-label']) !!}
                    </div>
                    <div class="col-md-6">
                        {!! Form::textarea('desc', isset($module) ? $module -> desc : null, ['class' => 'form-control', 'rows' => 3]) !!}
                        @if($errors -> has('desc'))
                            <span class="help-block form-help-block"><strong>{{ $errors -> first('desc') }}</strong></span>
                        @endif
                    </div>
                </div>

                <!--- auth Field --->
                <div class="form-group {{ $errors -> has('departments') ? 'has-error' : '' }}">
                    <div class="col-xs-2 text-right">
                        {!! Form::label('dep', '授权部门:', ['class' => 'control-label']) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <label for="dep"></label>
                            <textarea class="form-control" rows="5" id="dep" name="dep" cols="50" readonly>{{ isset($module) ? $module -> auth_deps['names'] : '' }}</textarea>
                            <div class="input-group-btn select-module-departments">
                                <button class="btn btn-success select-module-departments" type="button" data-toggle="modal" data-target="#select-module-departments">选择</button>
                            </div>
                        </div>
                        @if($errors -> has('departments'))
                            <span class="help-block form-help-block"><strong>{{ $errors -> first('departments') }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="row">
                    <div class=" col-xs-2 text-right">
                        <button type="submit" class="btn btn-sm btn-{{ isset($module) ? 'primary' : 'info' }}"><b>保存</b></button>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('backend.modules') }}" class="btn btn-sm btn-default pull-right"><b>返回</b></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="select-module-departments">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">选择部门</h4>
                    </div>
                    <div class="modal-body" style="max-height: 70vh;overflow: auto">
                        @foreach($departments as $department)
                            <div class="department-group">
                                <div class="checkbox">
                                    <label>
                                        @if(isset($module))
                                            <input class="parent-checkbox" type="checkbox" name="departments[]" {{ in_array($department -> id, $module -> auth_deps['ids']) ? 'checked' : '' }} data-name="{{ $department -> name }}" value="{{ $department -> id }}">
                                        @else
                                            <input class="parent-checkbox" type="checkbox" name="departments[]" data-name="{{ $department -> name }}" value="{{ $department -> id }}">
                                        @endif
                                        {{ $department -> name }}
                                    </label>
                                </div>
                                @if($department -> children)
                                    <hr>
                                    @foreach($department -> children as $child)
                                    <div class="checkbox">
                                        <label>
                                            @if(isset($module))
                                                <input class="department-checkbox" type="checkbox" name="departments[]" {{ in_array($child -> id, $module -> auth_deps['ids']) ? 'checked' : '' }}  data-name="{{ $child -> name }}" value="{{ $child -> id }}">
                                            @else
                                                <input class="department-checkbox" type="checkbox" name="departments[]" data-name="{{ $child -> name }}" value="{{ $child -> id }}">
                                            @endif
                                            {{ $child -> name }}
                                        </label>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary pull-left module-select-deps" data-dismiss="modal">确定</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    {!! Form::close() !!}
    <script>
        var thumbnail = '{{ isset($module) ? $module -> thumbnail : '' }}';
    </script>


@endsection