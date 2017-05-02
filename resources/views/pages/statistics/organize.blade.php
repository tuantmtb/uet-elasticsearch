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
    Thống kê {{$organize->name}}
@endsection

@section('page-body')
    <div class="portlet light">
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-3">
                    <ul class="list-group">
                        @if(isset($statistics))
                            @foreach($statistics['total'] as $key => $value)
                            <li class="list-group-item ">
                                <b>{{VciConstants::STATISTICS_TOTAL[$key]}}: {{$value}}</b>
                            </li>
                            @endforeach
                        @endif
                        <li class="list-group-item">
                            <b><a href="{{route('organize.articles', $organize->id)}}">Danh sách tạp chí</a></b>
                        </li>
                    </ul>
                </div>
                <div class="col-md-9">
                    <div id="year-chart"></div>
                    @if(isset($statistics['citation_year_unknown']))
                        <div class="row text-center">Trích dẫn không rõ năm: {{$statistics['citation_year_unknown']}}</div>
                    @endif
                </div>
            </div>

            <div class="row margin-top-20">
                <div class="col-md-12">
                    <div id="subject-chart"></div>
                </div>
            </div>
            <div class="row margin-top-20">
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
        Highcharts.chart('subject-chart', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Thống kê số bài báo theo lĩnh vực'
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '{point.percentage:.1f}%'
                    },
                    showInLegend: true
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            series: [
                {
                    name: 'Số bài báo',
                    data: [
                            @foreach($statistics['subjects'] as $subject)
                        {
                            name: '{{$subject->name}}',
                            y: parseInt('{{$subject->count}}'),
                        },
                        @endforeach
                    ]
                }
            ]
        });
    </script>
    @endif
@endsection