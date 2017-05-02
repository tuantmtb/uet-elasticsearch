@extends('layouts.page')

@section('page-level-plugins.styles')
    @parent
    {{Html::style('metronic/global/plugins/datatables/datatables.min.css')}}
    {{Html::style('metronic/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}
@endsection

@section('page-level-styles')
    @parent
    {{Html::style('metronic/pages/css/search.min.css')}}
    {{Html::style('css/datatable.css')}}
@endsection

@section('page-title')
    Thống kê theo tạp chí
@endsection

@section('page-body')
    <div class="search-page search-content-2">
        <div class="row">
            <div class="col-md-3 col-xs-12">
                <div class="portlet light">
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-12">
                                <b>Lọc theo năm</b>
                                {{Form::open(['method' => 'get', 'route' => 'statistics.journals'])}}
                                <div class="form margin-top-10">
                                    <div class="form-group">
                                        {{Form::label('start_year', 'Từ năm')}}
                                        {{Form::number('start_year', $request->get('start_year'), ['class' => 'form-control', 'min' => '0'])}}
                                    </div>
                                    <div class="form-group">
                                        {{Form::label('end_year', 'Đến năm')}}
                                        {{Form::number('end_year', $request->get('end_year'), ['class' => 'form-control', 'min' => '0'])}}
                                    </div>
                                    {{Form::submit('Áp dụng', ['class' => 'btn green'])}}
                                </div>
                                {{Form::close()}}
                            </div>

                        </div>
                        <div class="row margin-top-40">
                            <div class="col-md-12">
                                <b>Ẩn/hiện cột</b>
                                <div class="checkbox-list">
                                    @foreach($dataTable->getColumns() as $index => $column)
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" class="toggle-vis" data-column="{{$index}}" checked>
                                                {{str_replace('<br>', '', $column->title)}}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-xs-12">
                <div class="portlet light">
                    <div class="portlet-body">
                        {!! $dataTable->table(['class' => 'table table-striped table-bordered table-hover dataTable no-footer']) !!}
                    </div>
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
    <script>
        $('form').submit(function() {
            bootbox.dialog({
                message: '<p><i class="fa fa-spin fa-spinner"></i> Đang xử lý...</p>'
            });
        });
    </script>
@endsection