
<div class="sign-in-form">
    @if(Auth::guest())
        {!! Form::open(['url' => route('backend.sign.in.post'), 'method' => 'post', 'class' => 'form-inline', 'role' => 'form']) !!}
            <div class="form-inputs">
                <!--- Username Field --->
                {!! Form::label('username', '用户名:', ['class' => 'control-label']) !!}
                {!! Form::text('username', null, ['class' => 'form-control']) !!}
                <!--- Password Field --->
                {!! Form::label('password', '密码:', ['class' => 'control-label']) !!}
                {!! Form::password('password', ['class' => 'form-control']) !!}
                <button class="btn btn-danger btn-xs" type="submit">登录</button>
            </div>
        {!! Form::close() !!}
        <script>
            @if($errors -> isNotEmpty())
                alert("{{ $errors -> first() }}");
            @endif
        </script>
    @else
        <div class="user-notice">
            欢迎您，{{ Auth::user() -> name }}！<a href="{{ route('backend.index') }}">打开管理后台</a>
        </div>
    @endif
</div>
<div class="banner">
    <div class="top text-center">
        <img src="/images/banner/banner.png" alt="">
    </div>
</div>