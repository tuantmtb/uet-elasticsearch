@extends('layouts.page')

@section('page-level-plugins.styles')
    @parent
    {{Html::style('metronic/global/plugins/datatables/datatables.min.css')}}
    {{Html::style('metronic/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}
@endsection

@section('page-level-styles')
    @parent
    {{Html::style('css/datatable.css')}}
@endsection

@section('page-title')
    Thống kê theo cơ quan
@endsection

@section('page-body')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light">

                <div class="portlet-body">
                    {!! $dataTable->table(['class' => 'table table-striped table-bordered table-hover dataTable no-footer']) !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-level-plugins.scripts')
    @parent
    {{Html::script('metronic/global/scripts/datatable.js')}}
    {{Html::script('metronic/global/plugins/datatables/datatables.min.js')}}
    {{Html::script('metronic/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}
@endsection

@section('page-level-scripts')
    @parent
    {{Html::script('js/datatable.js')}}
    {!! $dataTable->scripts() !!}
@endsection