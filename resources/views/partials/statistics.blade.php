<style>
    .modal .modal-header {
        border-bottom: none;
    }

    .high-charts * {
        -webkit-transition: width 0.3s;
        -moz-transition: width 0.3s;
        transition: width 0.3s;
    }
</style>
<div hidden id="statistics">
    <div id="statistics-title">
        Thống kê
        <ul class="nav nav-tabs margin-top-20" id="statistics-nav">
            <li class="active">
                <a href="#total" data-toggle="tab"> Tổng hợp </a>
            </li>
            @foreach(VciConstants::STATISTICS_CHARTS as $key => $text)
                @if (isset($statistics[$key]))
                    <li>
                        <a href="#by_{{$key}}" data-toggle="tab"> {{$text}} </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
    <div id="statistics-message">
        <div class="tab-content">
            <div class="tab-pane active" id="total">
                <ul class="list-group">
                    @foreach($statistics['total'] as $text)
                        <li class="list-group-item ">
                            <b>{{$text}}</b>
                        </li>
                    @endforeach
                </ul>
            </div>
            @foreach(array_keys(VciConstants::STATISTICS_CHARTS) as $key)
                <div class="tab-pane" id="by_{{$key}}">
                    <div class="high-charts" id="{{$key}}-chart"></div>
                    @if($key === 'years' && isset($statistics['citation_year_unknown']))
                        <div class="row text-center">Trích dẫn không rõ năm: {{$statistics['citation_year_unknown']}}</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
<script>
    var interval = {
        interval: null,

        clearInterval: function () {
            if (this.interval !== null) {
                window.clearInterval(this.interval);
            }
        },

        setInterval: function () {
            this.clearInterval();
            this.interval = window.setInterval(function () {
                window.dispatchEvent(new Event('resize'));
            }, 100);
        }
    };

    @if(isset($statistics['years']))
    function yearsChart() {
        Highcharts.chart('years-chart', {
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: '{{isset($statistics['years'][0]['count']) ? 'Thống kê số bài và trích dẫn theo năm' : 'Thống kê số trích dẫn theo năm'}}'
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
            yAxis: [
                @if(isset($statistics['years'][0]['count']))
                {
                    allowDecimals: false,
                    title: {
                        text: 'Số bài báo'
                    },
                    min: 0
                },
                @endif
                {
                    allowDecimals: false,
                    title: {
                        text: 'Số trích dẫn'
                    },
                    @if(isset($statistics['years'][0]['count']))
                    opposite: true,
                    @endif
                    min: 0
                }
            ],
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
            series: [
                @if(isset($statistics['years'][0]['count']))
                {
                    name: 'Số bài báo',
                    type: 'column',
                    data: [
                        @foreach($statistics['years'] as $year)
                            parseInt('{{$year['count']}}'),
                        @endforeach

                    ],
                    maxPointWidth: 50
                },
                @endif
                {
                    name: 'Số trích dẫn',
                    type: 'line',
                    @if(isset($statistics['years'][0]['count']))
                    yAxis: 1,
                    @endif
                    data: [
                        @foreach($statistics['years'] as $year)
                            parseInt('{{$year['citation']}}'),
                        @endforeach
                    ]
                }
            ]
        });
    }
    @endif

    @if(isset($statistics['journals']))
    function journalsChart() {
        Highcharts.chart('journals-chart', {
            title: {
                text: 'Thống kê số bài báo theo tạp chí'
            },
            xAxis: {
                categories: [
                    @foreach($statistics['journal_years'] as $year)
                        {{$year['year']}},
                    @endforeach
                ],
                title: {
                    text: 'Năm'
                },
                crosshair: true
            },
            yAxis: {
                title: {
                    text: 'Số bài báo'
                },
                allowDecimals: false,
                min: 0
            },
            legend: {
                layout: 'vertical'
            },
            credits: {
                enabled: false
            },
            tooltip: {
                shared: true
            },
            series: [
                @foreach($statistics['journals'] as $journal)
                {
                    name: '{{$journal['name']}}',
                    data: [
                        @foreach($journal['years'] as $year)
                            {{$year['count']}},
                        @endforeach
                    ]
                },
                @endforeach
            ]
        });
    }
    @endif

    @if(isset($statistics['authors']))
    function authorsChart() {
        Highcharts.chart('authors-chart', {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Thống kê theo tác giả'
            },
            xAxis: {
                categories: [
                    @foreach($statistics['authors'] as $author)
                        '{{$author['fullname']}}',
                    @endforeach
                ],
                title: {
                    text: 'Tác giả'
                },
                crosshair: true
            },
            yAxis: {
                title: {
                    text: null
                },
                allowDecimals: false,
                min: 0
            },
            credits: {
                enabled: false
            },
            tooltip: {
                shared: true
            },
            series: [
                {
                    name: 'Số bài báo',
                    data: [
                        @foreach($statistics['authors'] as $author)
                        {{$author['count']}},
                        @endforeach
                    ]
                },
                {
                    name: 'Số trích dẫn',
                    data: [
                        @foreach($statistics['authors'] as $author)
                        {{$author['citation']}},
                        @endforeach
                    ]
                }
            ]
        });
    }
    @endif

    @if(isset($statistics['organizes']))
    function organizesChart() {
        Highcharts.chart('organizes-chart', {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Thống kê theo cơ quan'
            },
            xAxis: {
                categories: [
                    @foreach($statistics['organizes'] as $organize)
                        '{{$organize['name']}}',
                    @endforeach
                ],
                title: {
                    text: 'Cơ quan'
                },
                crosshair: true
            },
            yAxis: {
                title: {
                    text: null
                },
                allowDecimals: false,
                min: 0
            },
            credits: {
                enabled: false
            },
            tooltip: {
                shared: true
            },
            series: [
                {
                    name: 'Số bài báo',
                    data: [
                        @foreach($statistics['organizes'] as $organize)
                        {{$organize['count']}},
                        @endforeach
                    ]
                },
                {
                    name: 'Số trích dẫn',
                    data: [
                        @foreach($statistics['organizes'] as $organize)
                        {{$organize['citation']}},
                        @endforeach
                    ]
                }
            ]
        });
    }
    @endif

    @if(isset($statistics['subjects']))
    function subjectsChart() {
        Highcharts.chart('subjects-chart', {
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
                            name: '{{$subject['name']}}',
                            y: parseInt('{{$subject['count']}}')
                        },
                        @endforeach
                    ]
                }
            ]
        });
    }
    @endif

    function initCharts() {
        @foreach(array_keys(VciConstants::STATISTICS_CHARTS) as $key)
            @if(isset($statistics[$key]))
                {{$key}}Chart();
        @endif
        @endforeach
    }

    var bootbox_data = {
        title: $('#statistics-title').html(),
        message: $('#statistics-message').html(),
        size: 'large',
        backdrop: true,
        onEscape: true,
        callback: function () {
            interval.clearInterval();
        }
    };
    $('#statistics').remove();

    $('#show-statistics').click(function () {
        bootbox.dialog(bootbox_data);
        initCharts();
        interval.setInterval();
    });
</script>