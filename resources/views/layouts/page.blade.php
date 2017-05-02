@extends('layouts.master')

@section('page')
    @hasSection('page-title')
        <div class="page-head">
            <div class="container">
                <div class="page-title">
                    {{--header--}}
                    <h1>@yield('page-title')</h1>
                </div>
            </div>
        </div>
    @endif
    <div class="page-content">
        <div class="container-fluid">
            <ul class="page-breadcrumb breadcrumb"></ul>
            <div class="page-content-inner">
                @yield('page-body')
            </div>
        </div>
    </div>
@endsection