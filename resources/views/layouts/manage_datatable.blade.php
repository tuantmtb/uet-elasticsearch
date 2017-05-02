@extends('layouts.manage')

@section('page-level-plugins.styles')
    @parent
    {{Html::style('metronic/global/plugins/datatables/datatables.min.css')}}
    {{Html::style('metronic/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}
@endsection

@section('page-level-styles')
    @parent
    {{Html::style('css/datatable.css')}}
@endsection

@section('dashboard-body')
    <div class="portlet light">
        @yield('portlet-title')
        <div class="portlet-body">
            {!! $dataTable->table(['class' => 'table table-striped table-bordered table-hover dataTable no-footer']) !!}
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

