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
    Tạp chí {{$journal->name}}
@endsection

@section('page-body')
    <div class="row row-eq-height">
        <div class="col-md-3 col-sm-12">
            <div class="portlet light" style="height: 100%;">
                <div class="portlet-title">
                    <div class="caption">
                        <div class="caption-subject bold">Thông tin chung</div>
                    </div>
                </div>
                <div class="portlet-body">
                    <ul class="list-group">
                        <li class="list-group-item ">
                            <b>ISSN: {{$journal->issn}}</b>
                        </li>
                        <li class="list-group-item ">
                            <b>Đơn vị chủ quản: {{$journal->proprietor}}</b>
                        </li>
                        <li class="list-group-item ">
                            <b><a href="{{$journal->website}}" target="_blank">Website tạp chí</a></b>
                        </li>

                        <li class="list-group-item ">
                            <b><a href="{{route('journal.articles', $journal->id)}}">Danh sách bài báo</a></b>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-sm-12">
            <div class="portlet light" style="height: 100%">
                <div class="portlet-title">
                    <div class="caption">
                        <div class="caption-subject bold">Thống kê</div>
                    </div>
                </div>

                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <ul class="list-group" style="margin-bottom: 0">
                                @if(isset($statistics))
                                    @foreach($statistics['total'] as $key => $value)
                                        <li class="list-group-item ">
                                            <b>
                                                @if($key == 'citation_scopus_isi')
                                                    <a href="{{route('journal.articles', ['id' => $journal->id, 'must_scopus' => true])}}">
                                                        {{VciConstants::STATISTICS_TOTAL[$key]}}: {{$value}}
                                                    </a>
                                                @else
                                                    {{VciConstants::STATISTICS_TOTAL[$key]}}: {{$value}}
                                                @endif
                                            </b>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        <div class="col-md-9 col-sm-12">
                            <div id="year-chart"></div>
                            @if(isset($statistics['citation_year_unknown']))
                                <div class="row text-right">
                                    <div class="col-md-12">
                                        <i>(Trích dẫn không rõ năm: {{$statistics['citation_year_unknown']}})</i>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row margin-top-20" hidden>
        <div class="col-md-12">
            <div class="portlet box blue-hoki">
                <div class="portlet-title">
                    <div class="caption">
                        <div class="caption-subject">Số bài trong các năm gần đây</div>
                    </div>
                </div>
                <div class="portlet-body">
                    {!! $dataTable->table(['table table-striped table-bordered table-hover']) !!}
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
    {{--{{Html::script('js/datatable.js')}}--}}
    {{--{!! $dataTable->scripts() !!}--}}
    {{Html::script('js/highcharts.js')}}
    @if(isset($statistics))
        <script>
            Highcharts.chart('year-chart', {
                chart: {
                    zoomType: 'xy'
                },
                title: {
                    text: 'Thống kê số bài và trích dẫn theo năm'
                },
                xAxis: [{
                    categories: [
                        @foreach($statistics['years'] as $year)
                            '{{$year['year']}}',
                        @endforeach
                    ],
                    title: {
                        text: 'Năm'
                    },
                    crosshair: true
                }],
                yAxis: [{
                    allowDecimals: false,
                    title: {
                        text: 'Số bài báo'
                    }
                }, {
                    allowDecimals: false,
                    title: {
                        text: 'Số trích dẫn'
                    },
                    opposite: true
                }],
                credits: {
                    enabled: false
                },
                legend: {
                    layout: 'horizontal',
                    align: 'left',
                    x: 50,
                    verticalAlign: 'top',
                    y: 50,
                    floating: true,
                    backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
                },
                tooltip: {
                    shared: true
                },
                series: [{
                    name: 'Số bài báo',
                    type: 'column',
                    yAxis: 0,
                    data: [
                        @foreach($statistics['years'] as $year)
                            parseInt('{{$year['count']}}'),
                        @endforeach
                    ],
                    maxPointWidth: 50
                }, {
                    name: 'Số trích dẫn',
                    type: 'line',
                    yAxis: 1,
                    data: [
                        @foreach($statistics['years'] as $year)
                            parseInt('{{$year['citation']}}'),
                        @endforeach
                    ]
                }]
            });
        </script>
    @endif
@endsection

