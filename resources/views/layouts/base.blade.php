<!doctype html>
<html lang="vi">
<head>
    @section('meta')
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Vietnam Citation Gateway"/>
        <meta name="keywords" content=""/>
        <meta name="author" content="FIT - UET"/>
        <meta name="copyright" content="Vietnam Citation Gateway"/>
        <meta property="og:type" content="blog"/>
        <meta property="og:site_name" content=""/>
        <meta property="og:title" content="Vietnam Citation Gateway"/>
        <meta property="og:description" content="Vietnam Citation Gateway"/>
    @show

    <title>@yield('title', config('app.name'))</title>

    {{Html::favicon('https://www.elastic.co/favicon.ico')}}

    {!! Html::script('metronic/global/plugins/pace/pace.min.js') !!}
    {!! Html::style('metronic/global/plugins/pace/themes/pace-theme-flash.css') !!}

    @section('global-mandatory-styles')
        {{Html::style('http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all')}}
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
        {{Html::style('metronic/global/css/components-vci.css', ['id' => 'style_components'])}}
        {{Html::style('metronic/global/css/plugins.min.css')}}
    @show

    @yield('page-level-styles')

    @section('theme-layout-styles')
        {{Html::style('metronic/layouts/layout3/css/layout.min.css')}}
        {{Html::style('metronic/layouts/layout3/css/themes/vci.css', ['id' => 'style_color'])}}
    @show

    @section('scripts-top')
        <script>
            window.Laravel = {csrfToken: '{{csrf_token()}}'};
        </script>
    @show
</head>
<body class="@yield('body-class', 'page-container-bg-solid page-boxed')">

@yield('body')

@section('core-plugins')
    {{Html::script('metronic/global/plugins/jquery.min.js')}}
    {{Html::script('metronic/global/plugins/bootstrap/js/bootstrap.min.js')}}
    {{Html::script('metronic/global/plugins/js.cookie.min.js')}}
    {{Html::script('metronic/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')}}
    {{Html::script('metronic/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}
    {{Html::script('metronic/global/plugins/jquery.blockui.min.js')}}
    {{Html::script('metronic/global/plugins/uniform/jquery.uniform.min.js')}}
    {{Html::script('metronic/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}
    {{Html::script('js/jquery-cookie.js')}}
@show

@section('page-level-plugins.scripts')
    {{Html::script('metronic/global/plugins/bootstrap-toastr/toastr.min.js')}}
    {{Html::script('metronic/global/plugins/bootbox/bootbox.min.js')}}
@show

@section('theme-global-scripts')
    {{Html::script('metronic/global/scripts/app.min.js')}}
@show

@section('page-level-scripts')
    <script>
        String.prototype.replaceAll = function(search, replacement) {
            var target = this;
            return target.replace(new RegExp(search, 'g'), replacement);
        };

        App.setAssetsPath('{{route('main')}}/metronic');

        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        @if (session()->has('toastr'))
                @foreach(session('toastr') as $toastr)
            toastr['{{$toastr['level']}}']('{{$toastr['message']}}', '{{$toastr['title']}}');
        @endforeach
        @endif

    </script>

    <script>

        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-76613177-1', 'auto');
        ga('send', 'pageview');

    </script>
@show

@section('theme-layout-scripts')
    {{Html::script('metronic/layouts/layout3/scripts/layout.min.js')}}
@show

</body>
</html>