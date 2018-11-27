@extends('backend.layouts.layouts')
@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ $group -> name }} - 导航列表</h3>
            <div class="box-tools">
                <a class="btn btn-sm btn-default" href="{{ route('backend.settings.links') }}">返回</a>
                <button data-toggle="modal" data-target="#link-form-modal" class="btn btn-info btn-sm add-link-btn">添加</button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table class="table table-hover links-list text-center">
                <thead>
                <tr>
                    <th width="200">名称</th>
                    <th width="100">链接地址</th>
                    <th width="100">权重</th>
                    <th width="100">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($links as $link)
                    <tr class="links-list-trs">
                        <td>{{ $link -> name }}</td>
                        <td><a href="{{ $link -> link }}" target="_blank">{{ $link -> link }}</a></td>
                        <td>{{ $link -> weight }}</td>
                        <td>
                            <button class="btn btn-xs btn-primary edit-link-btn" data-id="{{ $link -> id }}" data-name="{{ $link -> name }}" data-desc="{{ $link -> desc }}" data-weight="{{ $link -> weight }}" data-link="{{ $link -> link }}" data-toggle="modal" data-target="#link-form-modal">编辑</button>
                            <a class="btn btn-xs btn-danger" href="{{ route('backend.settings.links.delete', ['id' => $link -> id]) }}">删除</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade common-form-modal" id="link-form-modal">
        <div class="modal-dialog">
        {!! Form::open(['url' => route('backend.settings.links.edit.store'), 'method' => 'post', 'class' => 'form-horizontal', 'role' => 'form']) !!}
        <!-- class include {'form-horizontal'|'form-inline'} -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ $group -> name }}-导航设置</h4>
                </div>
                <div class="modal-body">
                    <!--- Name Field --->
                    <div class="form-group {{ $errors -> has('name') ? 'has-error' : '' }}">
                        <div class="col-xs-2 text-right">
                            {!! Form::label('name', '名称(*):', ['class' => 'control-label']) !!}
                        </div>
                        <div class="col-xs-8">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                            @if($errors -> has('name'))
                                <span class="help-block form-help-block">
                                    <strong>{{ $errors -> first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <!--- desc Field --->
                    <div class="form-group {{ $errors -> has('desc') ? 'has-error' : '' }}">
                        <div class="col-xs-2 text-right">
                            {!! Form::label('desc', '描述(*):', ['class' => 'control-label']) !!}
                        </div>
                        <div class="col-xs-8">
                            {!! Form::textarea('desc', null, ['class' => 'form-control', 'rows' => 3]) !!}
                            @if($errors -> has('desc'))
                                <span class="help-block form-help-block">
                                    <strong>{{ $errors -> first('desc') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <!--- Link Field --->
                    <div class="form-group {{ $errors -> has('link') ? 'has-error' : '' }}">
                        <div class="col-xs-2 text-right">
                            {!! Form::label('link', '连接(*):', ['class' => 'control-label']) !!}
                        </div>
                        <div class="col-xs-8">
                            {!! Form::text('link', null, ['class' => 'form-control']) !!}
                            @if($errors -> has('link'))
                                <span class="help-block form-help-block">
                                    <strong>{{ $errors -> first('link') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <!--- weight Field --->
                    <div class="form-group {{ $errors -> has('weight') ? 'has-error' : '' }}">
                        <div class="col-xs-2 text-right">
                            {!! Form::label('weight', '权重(*):', ['class' => 'control-label']) !!}
                        </div>
                        <div class="col-xs-8">
                            {!! Form::number('weight', 1000, ['class' => 'form-control']) !!}
                            @if($errors -> has('weight'))
                                <span class="help-block form-help-block">
                                    <strong>{{ $errors -> first('weight') }}</strong>
                                </span>
                            @endif
                            @if($errors -> has('parent_id'))
                                <span class="help-block form-help-block">
                                    <strong>{{ $errors -> first('parent_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="parent_id" value="{{ $group -> id }}">
                    <input type="hidden" name="id" value="">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary pull-left">保存</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
            <!-- /.modal-content -->
            {!! Form::close() !!}
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection