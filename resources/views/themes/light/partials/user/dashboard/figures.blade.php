<div class="row mb-2">
    <div class="col-md-4">
        <div class="card mt-50" id="exchangeFigures">
            <div class="card-body">
                <div id="chart"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mt-50" id="topupFigures">
            <div class="card-body">
                <div id="chart1"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mt-50" id="payoutFigures">
            <div class="card-body">
                <div id="chart2"></div>
            </div>
        </div>
    </div>
</div>
@push('extra_scripts')
    <script>
        Notiflix.Block.standard('#exchangeFigures', {
            backgroundColor: loaderColor,
        });
        var isDarkMode = false;

        if (localStorage.getItem('dark-theme') == null) {
             isDarkMode =   "{{basicControl()->default_mode == 1 ?true :false}}";
        }else{
             isDarkMode = localStorage.getItem("dark-theme") == 1 ? true:false;
        }


        const baseColor = isDarkMode ? '#ffffff' : '#000000';
        const gridColors = isDarkMode ? ['#333333', 'transparent'] : ['#f3f3f3', 'transparent'];
        const labelColor = isDarkMode ? '#cccccc' : '#504e4e';


        var options = {
            series: [{
                data: [],
            }],
            chart: {
                type: 'bar',
                height: 200,
                background: isDarkMode ? '#1e1e1e' : '#ffffff' // Chart background
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            title: {
                text: "{{trans('Exchange Crypto Figures')}}",
                align: 'left',
                style: {
                    color: labelColor, // Set your desired color here
                }
            },
            xaxis: {
                categories: ['Total', 'Pending', 'Active', 'Complete', 'Refund'
                ],
                labels: {
                    style: {
                        colors: labelColor
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: labelColor
                    }
                }
            },
            colors: ['{{$baseColor}}'],
        };
        updateExchangeFiguresGraph();

        async function updateExchangeFiguresGraph() {
            let $url = "{{ route('user.chartExchangeFigures') }}"
            await axios.get($url)
                .then(function (res) {
                    options.series[0].data = res.data.exchangeFigures.horizontalBarChatExchange;
                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();
                    Notiflix.Block.remove('#exchangeFigures');
                })
                .catch(function (error) {
                });
        }
    </script>

    <script>
        Notiflix.Block.standard('#topupFigures', {
            backgroundColor: loaderColor,
        });
        var options1 = {
            series: [{
                data: [],
            }],
            chart: {
                type: 'bar',
                height: 200,
                background: isDarkMode ? '#1e1e1e' : '#ffffff' // Chart background
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            title: {
                text: "Top Up Figures",
                align: 'left',
                style: {
                    color: labelColor, // Set your desired color here
                }
            },
            xaxis: {
                categories: ['Total', 'Pending', 'Complete', 'Cancel'
                ],
                labels: {
                    style: {
                        colors: '#7d8791'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#7d8791'
                    }
                }
            },
            colors: ['{{$baseColor}}'],
        };

        updateTopUpFiguresGraph();

        async function updateTopUpFiguresGraph() {
            let $url = "{{ route('user.chartTopUpFigures') }}"
            await axios.get($url)
                .then(function (res) {

                    options1.series[0].data = res.data.topupFigures.horizontalBarChatTopUp;
                    var chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
                    chart1.render();
                    Notiflix.Block.remove('#topupFigures');
                })
                .catch(function (error) {
                });
        }

    </script>

    <script>
        Notiflix.Block.standard('#payoutFigures', {
            backgroundColor: loaderColor,
        });
        var options2 = {
            series: [{
                data: [],
            }],
            chart: {
                type: 'bar',
                height: 200,
                background: isDarkMode ? '#1e1e1e' : '#ffffff' // Chart background

            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            title: {
                text: "Payout Figures",
                align: 'left',
                style: {
                    color: labelColor, // Set your desired color here
                }
            },
            xaxis: {
                categories: ['Total', 'Pending', 'Complete', 'Cancel'
                ],
                labels: {
                    style: {
                        colors: labelColor
                    }
                }
            },

            yaxis: {
                labels: {
                    style: {
                        colors: labelColor
                    }
                }
            },
            colors: ['{{$baseColor}}'],
        };

        updatePayoutFiguresGraph();

        async function updatePayoutFiguresGraph() {
            let $url = "{{ route('user.chartPayoutFigures') }}"
            await axios.get($url)
                .then(function (res) {

                    options2.series[0].data = res.data.payoutFigures.horizontalBarChatPayout;
                    var chart2 = new ApexCharts(document.querySelector("#chart2"), options2);
                    chart2.render();
                    Notiflix.Block.remove('#payoutFigures');
                })
                .catch(function (error) {
                });
        }

    </script>
@endpush
