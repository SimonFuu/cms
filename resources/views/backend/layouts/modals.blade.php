{{--修改密码 modal--}}
<div class="modal fade form-modal" id="change-password-modal">
    <div class="modal-dialog">
    {!! Form::open(['url' => route('backend.reset.password'), 'method' => 'post', 'class' => 'form-horizontal', 'role' => 'form']) !!}
    <!-- class include {'form-horizontal'|'form-inline'} -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">修改密码</h4>
            </div>
            <div class="modal-body">
                <!--- Name Field --->
                <div class="form-group {{ $errors -> has('old_password') ? 'has-error' : '' }}">
                    <div class="col-xs-3 text-right">
                        {!! Form::label('old_password', '原密码(*):', ['class' => 'control-label']) !!}
                    </div>
                    <div class="col-xs-8">
                        {!! Form::password('old_password', ['class' => 'form-control']) !!}
                        @if($errors -> has('old_password'))
                            <span class="help-block form-help-block">
                                <strong>{{ $errors -> first('old_password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <!--- desc Field --->
                <div class="form-group {{ $errors -> has('password') ? 'has-error' : '' }}">
                    <div class="col-xs-3 text-right">
                        {!! Form::label('password', '新密码(*):', ['class' => 'control-label']) !!}
                    </div>
                    <div class="col-xs-8">
                        {!! Form::password('password', ['class' => 'form-control']) !!}
                    </div>
                </div>

                <!--- weight Field --->
                <div class="form-group {{ $errors -> has('password') ? 'has-error' : '' }}">
                    <div class="col-xs-3 text-right">
                        {!! Form::label('password_confirmation', '确认新密码(*):', ['class' => 'control-label']) !!}
                    </div>
                    <div class="col-xs-8">
                        {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                        @if($errors -> has('password'))
                            <span class="help-block form-help-block">
                                <strong>{{ $errors -> first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
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


