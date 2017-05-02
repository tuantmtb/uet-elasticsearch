@extends('vci_views.layouts.master')

@section('page-level-plugins.styles')
    @parent
    {!! Html::style('metronic/global/plugins/jstree/dist/themes/default/style.min.css') !!}
@endsection

@section('menu.search_journal', 'active')

@section('content')
    <div id="scroll"></div>
    <div id="center-content" style="min-height: 450px">
        {!! Form::open(['method' => 'POST', 'role' => 'form', 'id' => 'search-form']) !!}
        <div class="row">
            <div class="col-md-12 col-xs-12 col-sm-12">
                <div class="portlet light">
                    <div class="portlet-body">
                        <div class="row">

                            <div class="form-group col-md-10" style="display: inline-block">
                                {!! Form::text('text', $request->get('text', null), ['class' => 'form-control', 'placeholder'=>'Nội dung tìm kiếm ...', 'id' => 'text']) !!}
                            </div>

                            {{--<div class="form-group col-md-3" style="display: inline-block">--}}
                            {{--<select class="form-control" name="field" id="field">--}}
                            {{--@foreach(['name' => 'Tên'] as $key => $value)--}}
                            {{--<option value="{{$key}}"--}}
                            {{--@if($request->get('field') === $key) selected @endif>{{$value}}</option>--}}
                            {{--@endforeach--}}
                            {{--</select>--}}
                            {{--</div>--}}

                            <div class=" col-md-2">
                                <button type="submit" class="btn btn-primary" id="search-submit" style="width: 100%">
                                    Tìm kiếm
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix columns-widget columns6-6">
            <div class="col-left col-xs-12 col-md-4 col-sm-6">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            Lọc theo chủ đề
                        </div>
                    </div>
                    <div class="portlet-body" style="overflow-x: hidden">
                        <div id="tree" class="jstree jstree-default">Đang tải...</div>
                    </div>
                </div>
            </div>
            <div class="col-right col-xs-12 col-md-8 col-sm-6" id="search-result">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            Kết quả
                        </div>
                    </div>
                    <div class="portlet-body">
                        @foreach($journals as $journal)
                            @if($journal->id != 1)
                                <div>
                                    <h5 style="margin-bottom: 10px">
                                        <a href="{{route('journal.articles', $journal->id)}}">{{$journal->name}}</a>
                                    </h5>
                                </div>
                            @endif
                        @endforeach
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

@section('scripts-more')
    @parent
    <script>
        var $api_roots = '{!! route('api.subjects.roots') !!}?count_journals=true';
        var $api_children = function (id) {
            return '{!! route('api.subjects.children', ['id' => '_ID']) !!}?count_journals=true'.replace('_ID', id);
        };
        var $api_show = function(id) {
            return '{!! route('api.subjects.show', ['id' => '_ID']) !!}?count_journals=true'.replace('_ID', id);
        };

        function serialize(ids) {
            return ids.map(function (id) {
                return 'subject_ids[]=' + id;
            }).join('&');
        }

        function form_serilize() {
            var form = $('#search-form');
            return form.serializeArray().splice(1)
                .filter(function (serial) {
                    return serial.value.trim() !== "";
                })
                .map(function (serial) {
                    return serial.name + "=" + encodeURI(serial.value);
                })
                .join("&");
        }

        function applyFilter() {
            var subject_ids = $('#tree').jstree(true).get_checked(true);
            if (subject_ids !== null) {
                subject_ids = subject_ids.map(function (node) {
                    return node.id
                });
            } else {
                subject_ids = [];
            }
            UI('center-content').block();
            window.history.pushState('', '', '{!! route('search.journals.advance') !!}?' + serialize(subject_ids) + '&' + form_serilize());
            $.ajax({
                method: 'POST',
                data: {
                    _token: Laravel.crsfToken,
                    subject_ids: subject_ids,
                    field: $('#field').val(),
                    text: $('#text').val()
                },
                url: '{!! route('api.subjects.search_journals') !!}',
                success: function(response) {
                    $('#search-result').html(response);
                    UI('center-content').unblock();
                },
                error: function(error) {
                    //console.log(error);
                    toastr['error']('Đã có lỗi xảy ra, vui lòng thử lại sau.', 'Lỗi không xác định');

                    // Debug mode
                    // $('html').html(error.responseText);
                    UI('center-content').unblock();
                }
            });
            Scroll('scroll').toTop();
        }

        $('#search-form').submit(function (event) {
            event.preventDefault();
            applyFilter();
        });

        $(document).ready(function () {
            const tree = $('#tree');

            tree.on('changed.jstree', applyFilter).on('ready.jstree', function (e, data) {
                        @if(count($request->all()) > 0)
                var tree = data.instance;
                var opened = JSON.parse('{!! json_encode($opened) !!}');
                var subject_ids = JSON.parse('{!! json_encode($request->get('subject_ids')) !!}');

                var dolast = function() {
                    $(subject_ids).each(function (i, id) {
                        tree.select_node(id, true);
                    });
                    applyFilter();
                };
                var callback = function (){
                    if (opened.length > 0) {
                        const first = opened[0];
                        opened = opened.splice(1);
                        tree.open_node(first, callback);
                    } else {
                        dolast();
                    }
                };
                callback();
                @endif
            }).jstree({
                plugins: ['checkbox'],
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
                        url: function(node) {
                            return node.id === '#' ? $api_roots : $api_children(node.id);
                        },
                        data: function(node) {
                            return $api_show(node.id);
                        }
                    }
                }
            });
        });
    </script>
@endsection