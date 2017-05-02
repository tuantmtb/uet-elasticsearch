@extends('vci_views.layouts.master')

@section('page-level-plugins.styles')
    @parent
    {!! Html::style('metronic/global/plugins/datatables/datatables.min.css') !!}
    {!! Html::style('metronic/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') !!}
@endsection

@section('head-more')
    @parent
    {!! Html::style('css/datatable.css') !!}
@endsection

@section('content')
    <div id="" style="min-height: 450px; ">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            Quản lý tạp chí
                        </div>
                        <div class="actions">
                            <a href="{!! route('manage.journal.new') !!}" class="btn  btn-primary">
                                <i class="fa fa-plus"></i> Thêm tạp chí </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        {!! $dataTable->table(['class' => 'table table-striped table-bordered table-hover dataTable no-footer']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-level-plugins')
    @parent
    {!! Html::script('metronic/global/scripts/datatable.js') !!}
    {!! Html::script('metronic/global/plugins/datatables/datatables.min.js') !!}
    {!! Html::script('metronic/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') !!}
@endsection

@section('page-level-scripts')
    @parent
    {!! Html::script('metronic/pages/scripts/table-datatables-managed.min.js') !!}
@endsection

@section('scripts-more')
    @parentf
    {!! Html::script('js/datatable.js') !!}
    {!! $dataTable->scripts() !!}
@endsection

