@section('core-plugins')
    {{--    {{Html::script('metronic/global/plugins/jquery.min.js')}}--}}
    {{Html::script('metronic/global/plugins/bootstrap/js/bootstrap.min.js')}}
    {{Html::script('metronic/global/plugins/js.cookie.min.js')}}
    {{Html::script('metronic/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')}}
    {{Html::script('metronic/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}
    {{Html::script('metronic/global/plugins/jquery.blockui.min.js')}}
    {{Html::script('metronic/global/plugins/uniform/jquery.uniform.min.js')}}
    {{Html::script('metronic/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}
@show

@section('page-level-plugins')
    {{Html::script('metronic/global/plugins/bootstrap-toastr/toastr.min.js')}}
@show

@section('theme-global-scripts')
    {{Html::script('metronic/global/scripts/app.min.js')}}

@show

@section('page-level-scripts')
    {{Html::script('metronic/pages/scripts/ui-toastr.min.js')}}
@show

@section('layout-scripts')
    {{Html::script('metronic/layouts/layout3/scripts/layout.min.js')}}
    {{--    {{Html::script('metronic/layouts/layout3/scripts/demo.min.js')}}--}}
    {{--{{Html::script('metronic/layouts/global/scripts/quick-sidebar.min.js')}}--}}
@show

@include('vendor.flash.toastr')

<script>
    App.setAssetsPath($pathWebsite + "/metronic/");
    function UI(id) {
        var target = $('#' + id);
        return {
            block: function () {
                App.blockUI({
                    target: target,
                    message: 'Đang tải...',
                    animate: true,
                    boxed: true,
                });
            },
            unblock: function () {
                App.unblockUI(target);
            }
        };
    }


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

@yield('scripts-more')