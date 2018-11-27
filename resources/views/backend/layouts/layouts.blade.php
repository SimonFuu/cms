@extends('backend.layouts.common')
@section('body')
    <div class="wrapper">
        @include('backend.layouts.header')
        @include('backend.layouts.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
{{--            @include('backend.layouts.page_header')--}}
            <!-- Main content -->
            <section class="content container-fluid">
                @if(session('success'))
                    <div class="callout callout-success">
                        <h4>操作成功</h4>
                        <p>{{ session('success') }}</p>
                    </div>
                @elseif(session('error'))
                    <div class="callout callout-danger">
                        <h4>操作失败</h4>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                @yield('content')
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        @include('backend.layouts.footer')
    </div>
    @include('backend.layouts.modals')

    <script>
        var modalHasError = "{{ count($errors) }}";
    </script>
@endsection