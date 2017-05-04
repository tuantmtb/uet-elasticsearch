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
    </div>
    <div id="statistics-message">
        <div class="tab-content">
            <div class="tab-pane active" id="by_years">
                <div class="high-charts" id="years-chart"></div>
            </div>
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

    function yearsChart() {
        Highcharts.chart('years-chart', {
            title: {
                text: 'Thống kê số phim theo năm'
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
            yAxis: {
                allowDecimals: false,
                title: {
                    text: 'Số bộ phim'
                },
                min: 0
            },
            credits: {
                enabled: false
            },
            legend: {
                enabled: false
            },
            tooltip: {
                shared: true
            },
            series: [
                {
                    name: 'Số bộ phim',
                    type: 'column',
                    data: [
                        @foreach($statistics['years'] as $year)
                            parseInt('{{$year['count']}}'),
                        @endforeach

                    ],
                    maxPointWidth: 50
                },
            ]
        });
    }

    function initCharts() {
        yearsChart();
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