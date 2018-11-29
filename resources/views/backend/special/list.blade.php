@extends('backend.layouts.layouts')
@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">专题管理</h3>
            <div class="box-tools">
                <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#set-layout-position" href=""><b>展示位置</b></button>
                <button class="btn btn-sm btn-info" {{ empty($special) ? 'disabled' : '' }} data-toggle="modal" data-target="#add-special"><b>添加</b></button>
                <a class="btn btn-sm btn-warning" href="{{ route('backend.special.layouts') }}"><b>设置布局</b></a>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th width="200">名称</th>
                        <th width="50">权重</th>
                        <th width="200">描述</th>
                        <th width="200">缩略图</th>
                        <th width="100">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exists as $item)
                        <tr >
                            <td>{{ $item -> name }}</td>
                            <td>{{ $item -> weight }}</td>
                            <td>{{ $item -> desc }}</td>
                            <td><img style="max-width: 200px; min-height: 50px" src="{{ $item -> thumbnail }}" alt=""></td>
                            <td><a href="{{ route('backend.special.delete', ['id' => $item -> id]) }}" class="btn btn-danger btn-xs">删除</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>

    <div class="modal fade common-form-modal" id="add-special">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">添加专题</h4>
                </div>

                {!! Form::open(['url' => route('backend.special.store'), 'method' => 'post', 'class' => 'form-horizontal', 'role' => 'form']) !!}
                <!-- class include {'form-horizontal'|'form-inline'} -->
                    <div class="modal-body">
                        <!--- Special_id Field --->
                        <div class="form-group {{ $errors -> has('mid') ? 'has-error' : '' }}">
                            <div class="col-xs-2 text-right">
                                {!! Form::label('mid', '专题名:', ['class' => 'control-label']) !!}
                            </div>
                            <div class="col-xs-8">
                                {!! Form::select('mid', $special, null, ['class' => 'form-control']) !!}
                                @if($errors -> has('mid'))
                                    <span class="help-block form-help-block">
                                        <strong>{{ $errors -> first('mid') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!--- Weight Field --->
                        <div class="form-group {{ $errors -> has('weight') ? 'has-error' : '' }}">
                            <div class="col-xs-2 text-right">
                                {!! Form::label('weight', '权重:', ['class' => 'control-label']) !!}
                            </div>
                            <div class="col-xs-8">
                                {!! Form::number('weight', 1000, ['class' => 'form-control']) !!}
                                @if($errors -> has('weight'))
                                    <span class="help-block form-help-block">
                                        <strong>{{ $errors -> first('weight') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        {{--<!--- Weight Field --->--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="col-xs-2 text-right">--}}
                                {{--{!! Form::label('weight', '宽度(px):', ['class' => 'control-label']) !!}--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-3">--}}
                                {{--{!! Form::number('weight', 1024, ['class' => 'form-control']) !!}--}}
                                {{--@if($errors -> has('weight'))--}}
                                    {{--<span class="help-block form-help-block">--}}
                                        {{--<strong>{{ $errors -> first('weight') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-2 text-right">--}}
                                {{--{!! Form::label('weight', '高度(px):', ['class' => 'control-label']) !!}--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-3">--}}
                                {{--{!! Form::number('weight', 200, ['class' => 'form-control']) !!}--}}
                                {{--@if($errors -> has('weight'))--}}
                                    {{--<span class="help-block form-help-block">--}}
                                        {{--<strong>{{ $errors -> first('weight') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<!--- Weight Field --->--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="col-xs-2 text-right">--}}
                                {{--{!! Form::label('display_name', '模块名:', ['class' => 'control-label']) !!}--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-8">--}}
                                {{--<div class="radio">--}}
                                    {{--<label>--}}
                                        {{--<input type="radio" name="optionsRadios" id="optionsRadios2" value="option2"> 显示--}}
                                    {{--</label>--}}
                                {{--</div>--}}
                                {{--<div class="radio">--}}
                                    {{--<label>--}}
                                        {{--<input type="radio" name="optionsRadios" id="optionsRadios2" value="option2"> --}}
                                    {{--</label>--}}
                                {{--</div>--}}
                                {{--@if($errors -> has('weight'))--}}
                                    {{--<span class="help-block form-help-block">--}}
                                        {{--<strong>{{ $errors -> first('weight') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary pull-left">保存</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                {!! Form::close() !!}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="set-layout-position">
        <div class="modal-dialog">
            <div class="modal-content">
                {{--<div class="modal-header">--}}
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                        {{--<span aria-hidden="true">&times;</span></button>--}}
                    {{--<h4 class="modal-title">设置展示位置</h4>--}}
                {{--</div>--}}
                <div class="modal-body">
                    <div class="radio">
                        <label>
                            <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2"> 不展示
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2"> 导航栏展示
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2"> 首页展示
                        </label>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection