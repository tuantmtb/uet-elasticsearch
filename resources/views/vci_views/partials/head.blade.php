<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="DESCRIPTION" content="Vietnam Citation Index"/>
    <meta name="KEYWORDS" content=""/>
    <meta name="Author" content="FIT - UET"/>
    <meta name="copyright" content="Vietnam Citation Index"/>
    <meta property="og:type" content="blog"/>
    <meta property="og:site_name" content=""/>
    <meta property="og:title" content="Vietnam Citation Index"/>
    <meta property="og:description" content="Vietnam Citation Index"/>

    <title>{{ config('app.name', 'Chỉ số trích dẫn Việt Nam') }}</title>
    {{Html::meta(null, null, ['charset' => 'utf-8'])}}
    {{Html::meta(null, 'IE=edge', ['http-equiv' => 'X-UA-Compatible'])}}
    {{Html::meta('viewport', 'width=device-width, initial-scale=1')}}
    {{Html::meta('csrf-token', csrf_token())}}

    {{Html::favicon('img/vnu-favicon.png')}}

    {!! Html::script('metronic/global/plugins/pace/pace.min.js') !!}
    {!! Html::style('metronic/global/plugins/pace/themes/pace-theme-flash.css') !!}

    @section('global-mandatory-styles')
    {{--{{Html::style('http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all')}}--}}
    {{Html::style('metronic/global/plugins/font-awesome/css/font-awesome.min.css')}}
    {{Html::style('metronic/global/plugins/simple-line-icons/simple-line-icons.min.css')}}
    {{Html::style('metronic/global/plugins/bootstrap/css/bootstrap.min.css')}}
    {{Html::style('metronic/global/plugins/uniform/css/uniform.default.css')}}
    {{Html::style('metronic/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}
    @show

    @section('page-level-plugins.styles')
    {{Html::style('metronic/global/plugins/bootstrap-toastr/toastr.min.css')}}
    @show

    @section('theme-global-styles')
    {{Html::style('metronic/global/css/components.min.css', ['id' => 'style_components'])}}
    {{Html::style('metronic/global/css/plugins.min.css')}}
    @show

    @section('theme-layout-styles')
    {{Html::style('metronic/layouts/layout3/css/layout.min.css')}}
    {{Html::style('metronic/layouts/layout3/css/themes/default.min.css', ['id' => 'style_color'])}}
    {{Html::style('metronic/layouts/layout3/css/custom.css')}}
    @show


    {{--tuantm add more--}}
    {{Html::style('metronic/dtui/css/index.css')}}
    {{Html::style('metronic/dtui/css/main.css')}}
    {{Html::style('css/vci-scholar.css')}}
    <script>
        window.Laravel = {csrfToken: '{{csrf_token()}}'};
    </script>

    <!-- Styles -->
    {{Html::style('css/app.css')}}
<!-- Scripts -->
    {{Html::script('metronic/global/plugins/jquery.min.js')}}
    {!! Html::script('js/utils.js') !!}
    <script>
        var $pathWebsite = '{{route('main')}}';
    </script>

    <style>
        .auth-btn:hover {
            background-color: ghostwhite;
        }

        .dropdown-menu > li.active > a,
        .dropdown-menu > li.active > a:hover {
            color: #007f3e;
            border-left: 3px #007f3e solid;
            padding-left: 17px !important;
        }

        .dropdown-menu > li > a:hover {
            border-left: 3px #5bd897 solid;
            padding-left: 17px !important;
        }

        .navbar-nav > li > a:hover,
        .navbar-nav > li.open > a:focus {
            border-bottom: 3px #5bd897 solid;
            height: 38px !important;
            background-color: #E7E7E7 !important;
        }
    </style>

    @yield('head-more')

</head>