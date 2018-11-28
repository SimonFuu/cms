@extends('backend.layouts.layouts')
@section('content')
    <div class="box box-{{ is_null($content) ? 'info' : 'primary' }}">
        <div class="box-header with-border">
            <h3 class="box-title">{{ is_null($content) ? '添加' : '编辑'}}内容</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        {!! Form::open(['url' => route('backend.contents.store'), 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
        <!-- class include {'form-horizontal'|'form-inline'} -->
            @if(!is_null($content))
                <input type="hidden" name="id" value="{{ $content -> id }}">
            @endif

            <div class="box-body">
                <!--- Title Field --->
                <div class="form-group {{ $errors -> has('title') ? 'has-error' : '' }}">
                    {!! Form::label('title', '标题(*):', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::text('title', is_null($content) ? null : $content -> title, ['class' => 'form-control', 'placeholder' => '请输入标题！']) !!}
                        @if($errors -> has('title'))
                            <span class="help-block form-help-block"><strong>{{ $errors -> first('title') }}</strong></span>
                        @endif
                    </div>
                </div>

                @if($navigation)
                    <!--- navigation Field --->
                    <div class="form-group {{ $errors -> has('navigation') ? 'has-error' : '' }}">
                        {!! Form::label('navigation', '栏目推送', ['class' => 'col-sm-2 control-label']) !!}
                        @php
                            $default = is_null($content) ? [] : $content -> nav_ids;
                        @endphp
                        <div class="col-sm-8">
                            {!! Form::select('navigation[]', $navigation,null, [
                                'class' => 'form-control select2',
                                'multiple'=> 'multiple',
                                'data-placeholder'=> '请选择推送栏目',
                                'style' => 'width:100%',
                                'placeholder' => '请选择推送栏目',
                                'data-default' => json_encode($default),
                            ]) !!}
                            @if($errors -> has('navigation'))
                                <span class="help-block form-help-block"><strong>{{ $errors -> first('navigation') }}</strong></span>
                            @endif
                        </div>
                    </div>
                @endif

                @if($sections)
                    <!--- Section Field --->
                    <div class="form-group {{ $errors -> has('section') ? 'has-error' : '' }}">
                        {!! Form::label('section', '首页推送板块:', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::select('section', $sections, is_null($content) ? null : $content -> sec_id, ['class' => 'form-control', 'placeholder' => '请选择首页推送板块']) !!}
                            @if($errors -> has('section'))
                                <span class="help-block form-help-block"><strong>{{ $errors -> first('section') }}</strong></span>
                            @endif
                        </div>
                    </div>
                @endif

                <!--- Source Field --->
                <div class="form-group {{ $errors -> has('source') ? 'has-error' : '' }}">
                    {!! Form::label('source', '来源(*):', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::text('source', is_null($content) ? '毕节市纪委监委' : $content -> source, ['class' => 'form-control', 'placeholder' => '请输入文章来源！']) !!}
                        @if($errors -> has('source'))
                            <span class="help-block form-help-block"><strong>{{ $errors -> first('source') }}</strong></span>
                        @endif
                    </div>
                </div>

                {{--<!--- is_cus Field --->--}}
                {{--<div class="form-group {{ $errors -> has('is_cus') ? 'has-error' : '' }}">--}}
                    {{--{!! Form::label('is_cus', '首页轮播头条(*):', ['class' => 'col-sm-2 control-label']) !!}--}}
                    {{--<div class="col-sm-3 radio">--}}
                        {{--@if(is_null($content))--}}
                            {{--<label><input type="radio" name="is_cus" value="0" checked>否</label>--}}
                            {{--&nbsp;--}}
                            {{--<label><input type="radio" name="is_cus" value="1">是</label>--}}
                        {{--@else--}}
                            {{--<label><input type="radio" name="is_cus" value="0" {{ $content -> is_cus == 0 ? 'checked' : '' }}>否</label>--}}
                            {{--&nbsp;--}}
                            {{--<label><input type="radio" name="is_cus" value="1" {{ $content -> is_cus == 1 ? 'checked' : '' }}>是</label>--}}
                        {{--@endif--}}
                        {{--@if($errors -> has('is_cus'))--}}
                            {{--<span class="help-block form-help-block"><strong>{{ $errors -> first('is_cus') }}</strong></span>--}}
                        {{--@endif--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<!--- is_top Field --->--}}
                {{--<div class="form-group {{ $errors -> has('is_top') ? 'has-error' : '' }}">--}}
                    {{--{!! Form::label('is_top', '首页普通头条(*):', ['class' => 'col-sm-2 control-label']) !!}--}}
                    {{--<div class="col-sm-3 radio">--}}
                        {{--@if(is_null($content))--}}
                            {{--<label><input type="radio" name="is_top" value="0" checked>否</label>--}}
                            {{--&nbsp;--}}
                            {{--<label><input type="radio" name="is_top" value="1">是</label>--}}
                        {{--@else--}}
                            {{--<label><input type="radio" name="is_top" value="0" {{ $content -> is_top == 0 ? 'checked' : '' }}>否</label>--}}
                            {{--&nbsp;--}}
                            {{--<label><input type="radio" name="is_top" value="1" {{ $content -> is_top == 1 ? 'checked' : '' }}>是</label>--}}
                        {{--@endif--}}
                        {{--@if($errors -> has('is_top'))--}}
                            {{--<span class="help-block form-help-block"><strong>{{ $errors -> first('is_top') }}</strong></span>--}}
                        {{--@endif--}}
                    {{--</div>--}}
                {{--</div>--}}

                <!--- Weight Field --->
                <div class="form-group {{ $errors -> has('weight') ? 'has-error' : '' }}">
                    {!! Form::label('weight', '展示权重(*):', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::number('weight', is_null($content) ? 1000 : $content -> weight, ['class' => 'form-control', 'placeholder' => '请输入标题！']) !!}
                        @if($errors -> has('weight'))
                            <span class="help-block form-help-block"><strong>{{ $errors -> first('weight') }}</strong></span>
                        @endif
                    </div>
                </div>

                <!--- Thumbnail Field --->
                <div class="form-group {{ $errors -> has('thumbnail') ? 'has-error' : '' }}">
                    {!! Form::label('thumbnail', '缩略图(*):', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-8">
                        <input id="thumbnail-container" name="thumbnail-container" data-url="{{ route('backend.upload.thumbnail') }}" multiple type="file" accept="image/*">
                        <input type="hidden" id="thumbnail" name="thumbnail" value="{{ is_null($content) ? '' : $content -> thumb }}">
                        @if($errors -> has('thumbnail'))
                            <span class="help-block form-help-block"><strong>{{ $errors -> first('thumbnail') }}</strong></span>
                        @endif
                    </div>
                </div>

                <!--- Abs Field --->
                <div class="form-group {{ $errors -> has('abst') ? 'has-error' : '' }}">
                    {!! Form::label('abst', '摘要:', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::textarea('abst', is_null($content) ? null : $content -> abst, ['class' => 'form-control', 'rows' => 3, 'placeholder' => '请输入摘要！']) !!}
                        @if($errors -> has('abst'))
                            <span class="help-block form-help-block"><strong>{{ $errors -> first('abst') }}</strong></span>
                        @endif
                    </div>
                </div>

                <!--- Content Field --->
                <div class="form-group {{ $errors -> has('content') ? 'has-error' : '' }}">
                    <span id="ue-upload-url" data-url="{{ route('backend.upload.ue') }}" class="hide"></span>
                    <div class="col-md-12">
                        <script id="ue-container" type="text/plain"></script>
                    </div>

                    @if($errors -> has('title'))
                        <span class="help-block form-help-block"><strong>{{ $errors -> first('title') }}</strong></span>
                    @endif
                </div>
            </div>

            <!-- /.box-body -->
            <div class="box-footer">
                <div class="row">
                    <div class="col-md-offset-1 col-md-1">
                        <button type="submit" class="btn btn-sm btn-{{ is_null($content) ? 'primary' : 'info' }}"><b>保存</b></button>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('backend.contents') }}" class="btn btn-sm btn-default pull-right"><b>返回</b></a>
                    </div>
                </div>
            </div>
            <!-- /.box-footer -->
        {!! Form::close() !!}
    </div>
    <script>
        var thumbnail = "{{ is_null($content) ? '' : $content -> thumb }}";
        var initContent = '{!! is_null($content) ? (old('content') ? old('content') : '') : $content -> content !!}'
    </script>
@endsection