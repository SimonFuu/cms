@extends('backend.layouts.layouts')
@section('content')
    <div class="box box-{{ is_null($item) ? 'info' : 'primary' }}">
        <div class="box-header with-border">
            <h3 class="box-title">{{ is_null($item) ? '添加' : '编辑'}}下载</h3>
            &nbsp;
            <span class="text-red"><b>请勿上传、发布涉密内容！</b></span>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        {!! Form::open(['url' => route('backend.download.store'), 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
        <!-- class include {'form-horizontal'|'form-inline'} -->
            <div class="box-body">
                <!--- Name Field --->
                <div class="form-group {{ $errors -> has('name') ? 'has-error' : '' }}">
                    <div class=" col-xs-2 text-right">
                        {!! Form::label('name', '名称:', ['class' => 'control-label']) !!}
                    </div>
                    <div class="col-md-6">
                        {!! Form::text('name', is_null($item) ? null : $item -> name, ['class' => 'form-control']) !!}
                        @if($errors -> has('name'))
                            <span class="help-block form-help-block"><strong>{{ $errors -> first('name') }}</strong></span>
                        @endif
                    </div>
                </div>

                <!--- Desc Field --->
                <div class="form-group {{ $errors -> has('desc') ? 'has-error' : '' }}">
                    <div class="col-xs-2 text-right">
                        {!! Form::label('desc', '描述:', ['class' => 'control-label']) !!}
                    </div>
                    <div class="col-md-6">
                        {!! Form::textarea('desc', is_null($item) ? null : $item -> desc, ['class' => 'form-control', 'rows' => 3]) !!}
                        @if($errors -> has('desc'))
                            <span class="help-block form-help-block"><strong>{{ $errors -> first('desc') }}</strong></span>
                        @endif
                    </div>
                </div>

                <!--- Weight Field --->
                <div class="form-group {{ $errors -> has('weight') ? 'has-error' : '' }}">
                    <div class=" col-xs-2 text-right">
                        {!! Form::label('weight', '权重:', ['class' => 'control-label']) !!}
                    </div>
                    <div class="col-md-6">
                        {!! Form::number('weight', is_null($item) ? 1000 : $item -> weight, ['class' => 'form-control']) !!}
                        @if($errors -> has('weight'))
                            <span class="help-block form-help-block"><strong>{{ $errors -> first('weight') }}</strong></span>
                        @endif
                    </div>
                </div>
                <!--- Item Field --->
                <div class="form-group item-group {{ $errors -> has('item') ? 'has-error' : '' }}">
                    <div class=" col-xs-2 text-right">
                        {!! Form::label('item', '附件:', ['class' => 'control-label']) !!}
                    </div>
                    <div class="col-md-6">
                        <input id="item-container" name="itemContainer" data-url="{{ route('backend.upload.software') }}" multiple type="file">
                        <input type="hidden" id="item" name="item" value="{{ is_null($item) ? (old('item') ? old('item') : '') : $item -> src }}">
                        <input type="hidden" id="size" name="size" value="{{ is_null($item) ? (old('size') ? old('size') : '') : $item -> size }}">

                        @if($errors -> has('item'))
                            <span class="help-block form-help-block"><strong>{{ $errors -> first('item') }}</strong></span>
                        @endif
                    </div>
                </div>
                @if(!is_null($item))
                    <input type="hidden" name="id" value="{{ $item -> id }}">
                @endif
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <a href="{{ route('backend.download') }}" class="btn btn-default">返回</a>
                <button type="submit" class="btn btn-{{ is_null($item) ? 'info' : 'primary' }} pull-right">提交</button>
            </div>
            <!-- /.box-footer -->
        {!! Form::close() !!}
    </div>
    <script>
        var itemSrc = "{{ is_null($item) ? (old('item') ? old('item') : '') : $item -> src }}";
    </script>
@endsection