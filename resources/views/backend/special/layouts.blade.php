@extends('backend.layouts.layouts')
@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">专题布局设置管理</h3>
            <div class="box-tools">
                <a class="btn btn-sm btn-default" href="{{ route('backend.special') }}"><b>返回</b></a>
                <button class="btn btn-sm btn-primary special-submit-btn" data-url="{{ route('backend.special.layouts.store') }}"><b>保存</b></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="special-layout">
                <div class="row sort-list">
                    @foreach($specials as $special)
                        <div id="special-image-container-{{ $special -> id }}" class="col-xs-{{ $special -> width }} special-layout-img-container">
                            <div class="special-item">
                                <div class="special-name text-center">
                                    {{ $special -> name }}
                                </div>
                                <img class="special-image special-image-{{ $special -> id }}"
                                     data-container="#special-image-container-{{ $special -> id }}"
                                     data-id="{{ $special -> id }}" data-width="{{ $special -> width }}"
                                     data-height="{{ is_null($special -> height) ? 'auto' : $special -> height}}"
                                     data-weight="{{ $special -> weight }}" src="{{ $special -> thumbnail }}"
                                     width="100%" height="{{ (is_null($special -> height) || $special -> height == 0) ? 'auto' : $special -> height}}" alt="">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>

    <div class="modal fade" id="set-special-module">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">设置专题板块属性</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-5 form-group">
                            <div class="col-xs-4 text-right">
                                {!! Form::label('width', '宽度:', ['class' => 'control-label']) !!}
                            </div>
                            <div class="col-xs-8">
                                {!! Form::number('width', 12, ['class' => 'form-control special-image-width-input']) !!}
                            </div>
                        </div>
                        <div class="col-xs-5 form-group">
                            <div class="col-xs-4 text-right">
                                {!! Form::label('height', '高度:', ['class' => 'control-label']) !!}
                            </div>
                            <div class="col-xs-8">
                                {!! Form::number('height', 0, ['class' => 'form-control special-image-height-input']) !!}
                            </div>
                        </div>
                        <input type="hidden" name="id" class="special-image-id" value="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary pull-left special-image-submit-btn" data-dismiss="modal">确定</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection