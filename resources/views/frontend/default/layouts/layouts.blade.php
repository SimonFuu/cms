@extends('frontend.default.layouts.common')
@section('body')
    @include('frontend.default.layouts.banner')
    @include('frontend.default.layouts.navs')
    @yield('content')
    @include('frontend.default.layouts.footer')
@endsection