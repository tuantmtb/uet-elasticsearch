@extends('vci_views.layouts.master')

@section('vci_views.manage.title')
    Danh sách cơ quan
@endsection

@section('page-level-plugins.styles')
    @parent
    {!! Html::style('metronic/global/plugins/jstree/dist/themes/default/style.min.css') !!}
@endsection

@section('head-more')
    @parent
    <style>
        .portlet-body.fullscreen {
            height: 85vh !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="note note-info">
                <h4 class="block">Hướng dẫn</h4>
                <p> Bấm chuột phải để xem thêm các hành động. <br>
                    Kéo thả cơ quan để di chuyển cơ quan.
                </p>
            </div>
        </div>
    </div>
    <div id="" style="min-height: 450px; ">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            @yield('vci_views.manage.title')
                        </div>
                        <div class="actions">
                            <a href="{!! route('manage.organize.new') !!}" class="btn btn-circle btn-default">
                                <i class="fa fa-plus"></i> Tạo </a>
                            <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;"
                               data-original-title="" title="Toàn màn hình"> </a>
                        </div>
                        <div class="inputs">
                            <div class="portlet-input input-inline input-small">
                                <div class="input-icon right tooltips" data-container="body" data-placement="top" data-original-title="Enter để tìm kiếm">
                                    <i class="icon-magnifier"></i>
                                    <input type="text" class="form-control input-circle" placeholder="Tìm kiếm"
                                           id="search"></div>
                            </div>
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
    {!! Html::script('js/jstree.js') !!}
@endsection