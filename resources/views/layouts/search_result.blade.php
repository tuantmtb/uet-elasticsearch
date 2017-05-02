@extends('layouts.page')

@section('page-level-styles')
    @parent
    {{Html::style('metronic/pages/css/search.min.css')}}
    {{Html::style('css/highcharts.css')}}
    <style>
        .bs-select {
            display: inline;
        }

        .caption.search-label {
            color: #a0a9b4;
            font-size: 11px !important;
            letter-spacing: 1px;
            font-weight: 700;
        }

        .search-page .search-filter {
            padding: 0;
        }

        .search-content-2 .search-container > ul .search-item > .search-content .search-desc > a {
            color: #0A9C52;
        }

        .search-content-2 .search-container > ul .search-item > .search-content .search-desc > a:hover {
            color: #004823;
        }

        .search-content-2 .search-container > ul .search-item > .search-content .search-title > a {
            color: #004823;
        }

        .search-content-2 .search-container > ul .search-item > .search-content .search-title > a:hover {
            color: #0A9C52;
        }

        .search-content-2 .search-container > ul > .search-item-header h3 {
            font-size: 24px;
            color: #333;
        }

        .input-small {
            width: auto !important;
        }

        /**
            Highlight search content
         */
        .search-content-2 .search-container > ul .search-item > .search-content .search-title > a > b,
        .search-content-2 .search-container > ul .search-item > .search-content .search-desc > a > b {
            -webkit-transition: all 0.3s;
            -moz-transition: all 0.3s;
            transition: all 0.3s;
            color: #ff9632;
        }

        .search-content-2 .search-container > ul .search-item > .search-content .search-desc > a:hover > b {
            color: #B56400;
        }

        .search-content-2 .search-container > ul .search-item > .search-content .search-title > a:hover > b {
            color: #F0A03F;
        }

        /* Small Devices, Tablets */
        @media only screen and (min-width : 992px) {
            .collapse.filter-toggler,
            .expand.filter-toggler {
                -webkit-transform: rotate(-90deg);
                -moz-transform: rotate(-90deg);
                -ms-transform: rotate(-90deg);
                -o-transform: rotate(-90deg);
                transform: rotate(-90deg);
            }
        }

        .portlet.light > .portlet-title.filter-toggler-wrapper {
            border-bottom: none;
            min-height: 0;
            margin-bottom: 0;
        }

        .portlet.light > .portlet-title.filter-toggler-wrapper > .tools {
            width: 100%;
            text-align: center;
            padding-bottom: 10px;
        }

        .portlet.light.filter-toggler-container {
            padding-bottom: 12px;
        }
    </style>
@endsection

@section('page-body')
    {{Form::open(['method' => 'get', 'id' => 'search-form'])}}
    @yield('search-hidden-fields')
    <div class="search-page search-content-2">
        <div class="row">
            <div class="col-md-4 animation-all" id="left-col">
                <div class="portlet light filter-toggler-container">
                    <div class="portlet-title filter-toggler-wrapper">
                        <div class="tools">
                            <a href="javascript:" class="collapse filter-toggler" data-original-title="Ẩn/Hiện bộ lọc">
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="search-filter ">
                            <div class="search-label uppercase">Kết quả</div>
                            <ul class="list-unstyled">
                                @foreach($total as $text)
                                    <li>{!! $text !!}</li>
                                @endforeach
                            </ul>
                            @if($statistics != null)
                                <a id="show-statistics" style="font-size: 12pt;">
                                    <i class="fa fa-line-chart"></i> Thống kê kết quả
                                </a>
                            @endif

                            @if($filter != null)
                                @foreach($filter as $key => $elements)
                                    <div class="portlet margin-top-40">
                                        <div class="portlet-title">
                                            <div class="caption search-label uppercase">
                                                Lọc theo {{VciConstants::FILTER_NAMES[$key]}}
                                            </div>
                                            <div class="tools">
                                                <a href="javascript:" class="collapse" data-original-title="Ẩn/Hiện"> </a>
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <div class="checkbox-list">
                                                @foreach($elements as $index => $element)
                                                    @if($index == 5)
                                                        <a href="javascript:" class="see-more" data-target="{{$key}}">
                                                            Hiện
                                                            {{count($filter[$key]) - 5}} {{VciConstants::FILTER_NAMES[$key]}}
                                                            nữa
                                                        </a>
                                                    @endif
                                                    <label @if($index > 4) class="hidden-{{$key}}" style="display: none;" @endif>
                                                        <input class="filter" data-filter="{{$key}}" type="checkbox" name="filter_{{$key}}[]"
                                                               value="{{$element['value']}}"
                                                               @if(in_array($element['value'], $request->get("filter_$key", [])))
                                                               checked data-original-checked="true"
                                                               @else
                                                               data-original-checked="false"
                                                                @endif
                                                        >
                                                        {{$element['text']}} ({{$element['count']}} bài)
                                                    </label>
                                                @endforeach
                                            </div>
                                            <button type="submit" class="btn green bold uppercase btn-block" style="display:none;" id="filter-{{$key}}">Áp dụng</button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="row margin-top-40">&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 animation-all" id="right-col">
                <div class="search-container ">
                    <ul>
                        <li class="search-item-header">
                            <div class="row">
                                <div class="col-sm-4 margin-bottom-10">
                                    Xem
                                    {{Form::select('page_size', VciConstants::PAGE_SIZES, $request->get('page_size'), ['class' => 'bs-select form-control input-small auto-submit'])}}
                                    mục
                                </div>
                                @if($sortFields != null)
                                    <div class="col-sm-8 margin-bottom-10">
                                        <div class="pull-right-not-xs">
                                            Sắp xếp theo
                                            {{Form::select('sort_by', $sortFields, $request->get('sort_by'), ['class' => 'bs-select form-control input-small auto-submit'])}}
                                            {{Form::select('sort_dir', VciConstants::SORT_DIRS, $request->get('sort_dir'), ['class' => 'bs-select form-control input-small auto-submit'])}}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </li>
                        @yield('results')
                        @if ($pagingMeta['has_pages'])
                            <li class="search-item text-center">
                                <div class="pagination-panel">
                                    <a href="javascript:" onclick="previousPage()"
                                       class="btn btn-sm green prev tooltips @if($pagingMeta['on_first_page']) disabled @endif"
                                       data-original-title="Trang trước">
                                        <i class="fa fa-angle-left"></i>
                                    </a>
                                    <input type="number"
                                           class="pagination-panel-input form-control input-sm input-inline tooltips"
                                           style="text-align:center; min-width: 70px !important;"
                                           value="{{$pagingMeta['current_page']}}"
                                           data-original-title="Enter để chuyển trang" min="1"
                                           max="{{$pagingMeta['num_pages']}}"
                                           name="page" id="page"
                                    >
                                    / {{$pagingMeta['num_pages']}}
                                    <a href="javascript:" onclick="nextPage()"
                                       class="btn btn-sm green next tooltips @unless($pagingMeta['has_more_pages']) disabled @endunless"
                                       data-original-title="Trang tiếp">
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    {{Form::close()}}
@endsection

@section('page-level-plugins.scripts')
    @parent
    {{Html::script('js/highcharts.js')}}
@endsection

@section('page-level-scripts')
    @parent
    @if($statistics != null)
        @include('partials.statistics')
    @endif
    <script>
        var should_reset_page = true;

        function previousPage() {
            $('#page').val({{$pagingMeta['current_page'] - 1}});
            should_reset_page = false;
            submitSearch();
        }

        function nextPage() {
            $('#page').val({{$pagingMeta['current_page'] + 1}});
            should_reset_page = false;
            submitSearch();
        }

        function submitSearch() {
            $('#search-form').submit();
        }

        function handleFilterCollapse(filterToggler) {
            filterToggler.removeClass("collapse").addClass("expand");
            filterToggler.closest(".portlet").children(".portlet-body").slideUp(200, function() {
                $('#left-col').removeClass('col-md-4').addClass('col-md-1');
                $('#right-col').removeClass('col-md-8').addClass('col-md-11');
            });
        }

        function handleFilterExpand(filterToggler) {
            filterToggler.removeClass("expand").addClass("collapse");
            $('#left-col').removeClass('col-md-1').addClass('col-md-4');
            $('#right-col').removeClass('col-md-11').addClass('col-md-8');

            if (App.getViewPort().width >= App.getResponsiveBreakpoint('md')) {
                filterToggler.closest(".portlet").children(".portlet-body").delay(300).slideDown(200);
            } else {
                filterToggler.closest(".portlet").children(".portlet-body").slideDown(200);
            }
        }

        $(function () {
            $('#search-form').submit(function() {
                if (should_reset_page) {
                    $('#page').val(1);
                }
                bootbox.dialog({
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Đang xử lý...</p>'
                });
            });


            $('.auto-submit').change(submitSearch);
            $('#page')
                .change(function () {
                    should_reset_page = false;
                })
                .keydown(function (e) {
                    var code = e.which | e.code;
                    if (code === 13) {
                        should_reset_page = false;
                    }
                });

            $('.see-more').click(function () {
                var target = $(this).data('target');
                $(this).remove();
                $('.hidden-' + target).slideDown("fast");
            });

            $('.filter').change(function() {
                var field = $(this).data('filter');
                var anyChange = $('.filter[data-filter="' + field + '"]')
                    .toArray()
                    .map(function (input) {
                        return $(input).is(":checked") !== $(input).data('original-checked');
                    })
                    .reduce(function(total, one) {
                        return total || one;
                    });
                if (anyChange) {
                    $('#filter-' + field).fadeIn("fast");
                } else {
                    $('#filter-' + field).fadeOut("fast");
                }
            });

            if ($.cookie && $.cookie('filter_closed') === '1') {
                handleFilterCollapse($('.filter-toggler'));
            }

            var _default = $._data(document.querySelector('body'), "events").click[4].handler;
            $('body')
                .off('click', '.portlet > .portlet-title > .tools > .collapse, .portlet .portlet-title > .tools > .expand')
                .on('click', '.portlet > .portlet-title > .tools > .collapse:not(.filter-toggler), .portlet .portlet-title > .tools > .expand:not(.filter-toggler)', _default)
                .on('click', '.portlet > .portlet-title > .tools > .collapse.filter-toggler, .portlet .portlet-title > .tools > .expand.filter-toggler', function (e) {
                    e.preventDefault();
                    if ($(this).hasClass('collapse')) {
                        handleFilterCollapse($(this));
                        if ($.cookie) {
                            $.cookie('filter_closed', '1');
                        }
                    } else {
                        handleFilterExpand($(this));
                        if ($.cookie) {
                            $.cookie('filter_closed', '0');
                        }
                    }
                });
        });
    </script>
@endsection