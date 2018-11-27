@extends('backend.layouts.layouts')
@section('content')
    <div class="box box-{{ is_null($action) ? 'info' : 'primary' }}">
        <div class="box-header with-border">
            <h3 class="box-title">{{ is_null($action) ? '添加' : '编辑'}}权限</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        {!! Form::open(['url' => route('backend.system.actions.store'), 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
        <!-- class include {'form-horizontal'|'form-inline'} -->
            <div class="box-body">
                <!--- name Field --->
                <div class="form-group {{ $errors -> has('name') ? 'has-error' : '' }}">
                    {!! Form::label('name', '权限名称:', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('name', is_null($action) ? null : $action -> name, ['class' => 'form-control', 'placeholder' => '请输入权限名称！']) !!}
                        @if($errors -> has('name'))
                            <span class="help-block form-help-block">
                                <strong>{{ $errors -> first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <!--- menu_uri Field --->
                <div class="form-group {{ $errors -> has('menu_uri') ? 'has-error' : '' }}">
                    {!! Form::label('menu_uri', '菜单URI:', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('menu_uri', is_null($action) ? null : $action -> menu_uri, ['class' => 'form-control', 'placeholder' => '菜单URI！']) !!}
                        @if($errors -> has('menu_uri'))
                            <span class="help-block form-help-block">
                                <strong>{{ $errors -> first('menu_uri') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <!--- Icon Field --->
                <div class="form-group {{ $errors -> has('icon') ? 'has-error' : '' }}">
                    {!! Form::label('icon', '菜单图标:', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        <button class="btn btn-{{ $errors -> has('icon') ? 'danger' : 'default' }} set-action-icon" type="button" data-toggle="modal" data-target="#setActionIconModal">
                            <i class="fa {{is_null($action) ? 'fa-circle-o' : $action -> icon}}" aria-hidden="true"></i></button>
                        &nbsp;&nbsp;&nbsp;<i class="fa fa-hand-o-left" aria-hidden="true"></i> 请点击选择图标
                        <input class="set-action-icon-value" type="hidden" name="icon" value="{{is_null($action) ? 'fa-circle-o' : $action -> icon}}">
                        @if($errors -> has('icon'))
                            <span class="help-block form-help-block">
                                <strong>{{ $errors -> first('icon') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <!--- Urls Field --->
                <div class="form-group {{ $errors -> has('sub_uris') ? 'has-error' : '' }}">
                    {!! Form::label('sub_uris', 'URIs:', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        @if(!is_null($action))
                            @php
                                $urls = json_decode($action -> sub_uris, true);
                                $urls = (implode("\r\n", ($urls ? $urls : [])));
                            @endphp
                        @endif
                        {!! Form::textarea('sub_uris', is_null($action) ? null : $urls, ['class' => 'form-control', 'rows' => 5, 'placeholder' => '权限所有URI，一行一个！']) !!}
                        @if($errors -> has('sub_uris'))
                            <span class="help-block form-help-block">
                                <strong>{{ $errors -> first('sub_uris') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <!--- Weight Field --->
                <div class="form-group {{ $errors -> has('weight') ? 'has-error' : '' }}">
                    {!! Form::label('weight', '展示权重:', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::number('weight', is_null($action) ? 1000 : $action -> weight, ['class' => 'form-control']) !!}
                        @if($errors -> has('weight'))
                            <span class="help-block form-help-block">
                                <strong>{{ $errors -> first('weight') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <!--- desc Field --->
                <div class="form-group {{ $errors -> has('desc') ? 'has-error' : '' }}">
                    {!! Form::label('desc', '描述:', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('desc', is_null($action) ? null : $action -> desc, ['class' => 'form-control']) !!}
                        @if($errors -> has('desc'))
                            <span class="help-block form-help-block">
                                <strong>{{ $errors -> first('desc') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <!--- parent_id Field --->
                <div class="form-group {{ $errors -> has('parent_id') ? 'has-error' : '' }}">
                    {!! Form::label('parent_id', '父级菜单:', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::select('parent_id', $p_actions, is_null($action) ? null : $action -> parent_id, ['class' => 'form-control']) !!}
                        @if($errors -> has('parent_id'))
                            <span class="help-block form-help-block">
                                <strong>{{ $errors -> first('parent_id') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                @if(!is_null($action))
                    <input type="hidden" name="id" value="{{ $action -> id }}">
                @endif
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <a href="{{ route('backend.system.actions') }}" class="btn btn-default">返回</a>
                <button type="submit" class="btn btn-{{ is_null($action) ? 'info' : 'primary' }} pull-right">提交</button>
            </div>
            <!-- /.box-footer -->
        {!! Form::close() !!}
    </div>
    <script>
        var actionIcons = '{!! $icons !!}';
    </script>
    <!-- Modals -->
    <!-- set-actions-modal -->
    <div class="modal fade" id="setActionIconModal" tabindex="-1" role="dialog" aria-labelledby="setActionIconModalLabel">
        <div class="modal-dialog  modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="setActionIconModalLabel">Font Awesome Icons</h4>
                </div>
                <div class="modal-body set-actions-icons-list-modal">
                    <table class="set-actions-icons-list">
                        <thead>
                        <tr>
                            <td class="text-center">
                                <button type="button" class="btn btn-primary disabled set-actions-previous" data-previous="-1">
                                    <i class="fa fa-arrow-left"></i>
                                </button>
                            </td>
                            <td class="text-center set-actions-page-info" colspan="5"> 1 / 0 </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-primary set-actions-next" data-next="-1">
                                    <i class="fa fa-arrow-right"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7">
                                <label class="set-actions-icon-label">
                                    <input type="text" class="form-control set-actions-icon-name" placeholder="search icon">
                                </label>
                            </td>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.set-actions-modal -->
@endsection