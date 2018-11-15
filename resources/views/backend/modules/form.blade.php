@extends('backend.layouts.layouts')
@section('content')
    <div class="box box-{{ isset($module) ? 'primary' : 'info' }}">
        <div class="box-header">
            <h3 class="box-title">{{ isset($module) ? '编辑' : '添加' }}模块</h3>
        </div>
        {!! Form::open(['url' => route('backend.modules.store'), 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
            <!-- class include {'form-horizontal'|'form-inline'} -->
            <!-- /.box-header -->
            <div class="box-body">
                @if(isset($module))
                    <input type="hidden" name="id" value="{{ $module -> id }}">
                @endif
                <!--- Name Field --->
                <div class="form-group {{ $errors -> has('name') ? 'has-error' : '' }}">
                    <div class="col-md-offset-1 col-md-1">
                        {!! Form::label('name', '名称:', ['class' => 'control-label']) !!}
                    </div>
                    <div class="col-md-6">
                        {!! Form::text('name', isset($module) ? $module -> name : null, ['class' => 'form-control']) !!}
                    </div>
                    @if($errors -> has('name'))
                        <span class="help-block form-help-block"><strong>{{ $errors -> first('name') }}</strong></span>
                    @endif
                </div>

                <!--- Code Field --->
                <div class="form-group {{ $errors -> has('code') ? 'has-error' : '' }}">
                    <div class="col-md-offset-1 col-md-1">
                        {!! Form::label('code', '代码:', ['class' => 'control-label']) !!}
                    </div>
                    <div class="col-md-6">
                        {!! Form::text('code', isset($module) ? $module -> code : null, ['class' => 'form-control']) !!}
                    </div>
                    @if($errors -> has('code'))
                        <span class="help-block form-help-block"><strong>{{ $errors -> first('code') }}</strong></span>
                    @endif
                </div>

                <!--- Weight Field --->
                <div class="form-group {{ $errors -> has('weight') ? 'has-error' : '' }}">
                    <div class="col-md-offset-1 col-md-1">
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
                    <div class="col-md-offset-1 col-md-1">
                        {!! Form::label('desc', '描述:', ['class' => 'control-label']) !!}
                    </div>
                    <div class="col-md-6">
                        {!! Form::textarea('desc', isset($module) ? $module -> desc : null, ['class' => 'form-control']) !!}
                        @if($errors -> has('desc'))
                            <span class="help-block form-help-block"><strong>{{ $errors -> first('desc') }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-offset-1 col-md-1">
                        <button type="submit" class="btn btn-sm btn-{{ isset($module) ? 'primary' : 'info' }}"><b>保存</b></button>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('backend.modules') }}" class="btn btn-sm btn-default pull-right"><b>返回</b></a>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection