@extends('vci_views.layouts.master')

@section('page-level-plugins.styles')
    @parent
    {!! Html::style('metronic/global/plugins/jstree/dist/themes/default/style.min.css') !!}
@endsection

@section('menu.search_organize', 'active')

@section('content')
    <div id="center-content" style="min-height: 450px; ">
        <div class="row" id="portlet-search">
            <div class="col-md-12 col-xs-12 col-sm-12">
                <div class="portlet light">
                    <div class="portlet-body">
                        <div class="row">

                            <div class="form-group col-md-10" style="display: inline-block">
                                <input type="text" placeholder="Nội dung tìm kiếm" class="form-control" id="search">
                            </div>

                            <div class=" col-md-2">
                                <button type="button" class="btn btn-primary" id="search-submit" style="width: 100%">
                                    Tìm kiếm
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            Danh sách cơ quan
                        </div>
                    </div>
                    <div class="portlet-body" id="portlet-body" style="overflow-x: hidden;">
                        {{--<div class="scroller" style="min-height: 400px;" data-rail-visible="1" data-always-visible="1">--}}
                        <div id="tree" class="jstree jstree-default"></div>
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-level-plugins')
    @parent
    {!! Html::script('metronic/global/plugins/jstree/dist/jstree.min.js') !!}
@endsection

@section('page-level-scripts')
    @parent
    @if($selected)
        <script>
            window.jstree_data = {
                selected: '{{$selected}}',
                opened: '{!! json_encode($opened) !!}'
            };
        </script>
    @endif
    <script>
        function onError(e) {
            toastr['error']('Vui lòng thử lại', 'Đã có lỗi xảy ra');
            UI('center-content').unblock();

            // $('html').html(e.responseText);
        }

        $(document).ready(function () {
            var tree = $('#tree');
            var search = $('#search');
            var fullscreen = $('.fullscreen');
            UI('center-content').block();

            tree.on('ready.jstree', function (e, data) {
                if (window.jstree_data) {
                    var selected = window.jstree_data.selected;
                    var opened = JSON.parse(window.jstree_data.opened).reverse();
                    if (opened.length > 0) {
                        var callback = function () {
                            opened = opened.splice(1);
                            if (opened.length > 0) {
                                data.instance.open_node(opened[0] + '', callback);
                            } else {
                                data.instance.select_node(selected);
                                Scroll(selected).toTop();
                            }
                        };
                        data.instance.open_node(opened[0] + '', callback);
                    } else {
                        data.instance.select_node(selected);
                        Scroll(selected).toVisible();
                    }
                }
                UI('center-content').unblock();
            }).on('search.jstree', function (e, data) {
                if (data.nodes.length > 0) {
                    const first = data.nodes[0];
                    Scroll(first.id).toVisible();
                }
            }).jstree({
                plugins: ['contextmenu'],
                core: {
                    strings: {
                        'Loading ...': 'Đang tải ...'
                    },
                    check_callback: true,
                    expand_selected_onload: true,
                    multiple: true,
                    force_text: true,
                    dblclick_toggle: true,
                    themes: {
                        variant: "large",
                        dots: true,
                        icons: false,
                        responsive: true
                    },
                    data: {
                        url: function (node) {
                            return node.id === '#' ? $pathWebsite + '/api/organizes/roots' : $pathWebsite + '/api/organizes/' + node.id + '/children';
                        },
                        data: function (node) {
                            return $pathWebsite + '/api/organizes/' + node.id;
                        }
                    }
                },
                contextmenu: {
                    show_at_node: false,
                    items: function () { // Could be an object directly
                        return {
                            copy_id: {
                                _disabled: function (data) {
                                    var inst = $.jstree.reference(data.reference);
                                    return inst.get_selected().length !== 1;
                                },
                                label: 'Sao chép mã cơ quan',
                                action: function (data) {
                                    const inst = $.jstree.reference(data.reference);
                                    const obj = inst.get_node(data.reference);
                                    copyToClipboard(obj.id);
                                    toastr['info']('Đã sao chép mã ' + obj.id, 'Sao chép mã cơ quan');
                                }
                            }
                        };
                    }
                }
            });

            search.keyup(function (e) {
                var code = e.which | e.code;
                if (code === 13) {
                    $('#search-submit').click();
                }
            });

            $('#search-submit').click(function() {
                doSearch(search.val());
            });
        });

        function doSearch(q) {
            UI('center-content').block();
            if (q.trim() === '') {
                const inst = $.jstree.reference(tree);
                inst.show_all();
                UI('center-content').unblock();
            } else {
                $.ajax({
                    url: "{{route('api.organize.search.jstree')}}?q=" + q.trim(),
                    method: 'GET',
                    success: function (response) {
                        //console.log(response);
                        var inst = $.jstree.reference(tree);
                        var opened = response.opened;
                        var result = response.result;
                        var hidden = response.hidden;
                        inst.show_all();
                        var callback = function () {
                            hidden.forEach(function (id) {
                                $('#' + id).addClass('jstree-hidden');
                            });
                            result.forEach(function (id) {
                                $('#' + id + '_anchor').addClass('jstree-search');
                            });
                            UI('center-content').unblock();
                        };
                        if (opened.length > 0) {
                            var recursive = function () {
                                opened = opened.splice(1);
                                if (opened.length > 0) {
                                    inst.open_node(opened[0] + '', recursive);
                                } else {
                                    callback();
                                }
                            };
                            inst.open_node(opened[0] + '', recursive);
                        } else {
                            callback();
                        }
                    },
                    error: onError
                })
            }
        }
    </script>
@endsection