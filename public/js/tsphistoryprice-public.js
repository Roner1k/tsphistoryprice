(function ($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */


    $(window).load(function () {


        const monthNames = ["Jan", "Feb", "Mar", "Apr",
            "May", "Jun", "Jul", "Aug",
            "Sep", "Oct", "Nov", "Dec"];

        //pie portfolio
        let $al_pie_charts = $(`.highcharts-figure[data-pie-chart]`);

        if ($al_pie_charts.length > 0) {

            let pChartCount = 0;
            $al_pie_charts.each(function () {

                let pieWrap = $('.pie-wrap');

                let al_pie_container = "al-pie-container-" + $(this).attr('data-pie-chart');
                let al_pie_data = JSON.parse($(`#al-pie-data-${$(this).attr('data-pie-chart')}`).text());

                // Build the chart
                let pieChartArr = [{
                    name: 'G fund',
                    y: parseInt(al_pie_data.at(0).g_f_per),
                    color: '#001560',
                    showCheckbox: true
                }, {
                    name: 'F fund',
                    y: parseInt(al_pie_data.at(0).f_f_per),
                    color: '#1227E2',
                    description: 'lorem ipsum',
                    showCheckbox: true
                }, {
                    name: 'C fund',
                    y: parseInt(al_pie_data.at(0).c_f_per),
                    color: '#FDDA02',
                    showCheckbox: true
                }, {
                    name: 'S fund',
                    y: parseInt(al_pie_data.at(0).s_f_per),
                    color: '#FF9900',
                    showCheckbox: true
                }, {
                    name: 'I fund',
                    y: parseInt(al_pie_data.at(0).i_f_per),
                    color: '#EF6400',
                    showCheckbox: true
                }]

                let pie = Highcharts.chart(al_pie_container, {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie',
                            height: 290,
                            width: 280,
                        },
                        title: {
                            text: ''
                        },
                        tooltip: {
                            enabled: false,
                            // pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        accessibility: {
                            point: {
                                valueSuffix: '%'
                            }
                        },

                        plotOptions: {
                            pie: {
                                borderWidth: 0,
                                startAngle: 90,
                                innerSize: '90%',
                                size: '100%',
                                // shadow: true,
                                dataLabels: false,
                                stickyTracking: true,
                                states: {
                                    hover: {
                                        enabled: true,
                                        animation: {
                                            duration: 60,
                                            enabled: true
                                        }
                                    }
                                },
                                point: {
                                    events: {
                                        mouseOver: async function () {

                                            console.log(this);

                                            let chartID = $(this.graphic.element).attr('chart-id');
                                            let itemID = $(this.graphic.element).attr('chart-item-id');
                                            let pieWrap = $('.pie-wrap');

                                            let pieContainer = $(pieWrap[chartID]).find('.allocation-chart');
                                            pieContainer = $(pieContainer[0]).find('div[role="region"]');

                                            let rows = $(pieWrap[chartID]).find('tr');

                                            let tableRows = $(pieWrap[chartID]).find('tr.active');
                                            $(tableRows).removeClass('active');
                                            $(rows[itemID]).addClass('active');

                                            const x_center = (350 / 2) - ((await getLabelWidth(this.y + '%', 32)) / 2);

                                            this.series.chart.innerText.attr({
                                                text: this.y + '%' + '<br> <tspan y="180" x="138" class="item_name">' + this.name + '</tspan>'
                                            });
                                        },
                                        mouseOut: function () {

                                            let chartID = $(this.graphic.element).attr('chart-id');
                                            let pieWrap = $('.pie-wrap');
                                            let tableRows = $(pieWrap[chartID]).find('tr.active');
                                            $(tableRows).removeClass('active');

                                            this.series.chart.innerText.attr({
                                                text: '100%'
                                            });
                                        }
                                    }
                                },

                            }
                        },
                        series: [{
                            minPointSize: 10,
                            innerSize: '70%',
                            zMin: 0,
                            // name: 'Value',
                            data: pieChartArr,
                            showCheckbox: true
                        }]
                    },
                    function (chart) { // on complete
                        var newX = 260 / 2 + chart.plotLeft,
                            newY = 290 / 2 + chart.plotTop;

                        // Render the text
                        chart.innerText = chart.renderer.text('100%', newX, newY).css({
                            width: 240,
                            color: '#000',
                            fontWeight: 700,
                            fontSize: '32px',
                            textAlign: 'center'
                        }).attr({
                            zIndex: 5,
                            align: 'center'

                        }).add();


                    });


                $(pieWrap[pChartCount]).find('.print2pdf').attr('print-id', pChartCount);
                $(pieWrap[pChartCount]).find('.print2pdf').attr('print-class', 'pie-wrap');

                let tableRows = $(pieWrap[pChartCount]).find('tr')

                for (var i = 0; i <= tableRows.length - 1; i++) {
                    $(tableRows[i]).attr('chart-id', pChartCount);
                    $(tableRows[i]).attr('chart-item-id', i);

                    $(tableRows[i]).click(async function () {
                        let chartID = $(this).attr('chart-id');
                        let itemID = $(this).attr('chart-item-id');

                        let pieWrap = $('.pie-wrap');

                        let pieContainer = $(pieWrap[chartID]).find('.allocation-chart');
                        pieContainer = $(pieContainer[0]).find('div[role="region"]');

                        let chart = jQuery(pieContainer).highcharts();

                        let tableRows = $(pieWrap[$(this).attr('chart-id')]).find('tr.active');
                        $(tableRows).removeClass('active');


                        $(this).addClass('active');

                        for (let d = 0; d <= chart.series[0].data.length - 1; d++) {

                            if (d != itemID) {
                                chart.series[0].data[d].select(false);
                                chart.innerText.attr({
                                    text: '100%'
                                });

                                $(chart.series[0].data[d].graphic.element).css('opacity', 0.2);
                            } else {
                                $(chart.series[0].data[d].graphic.element).css('opacity', 1);
                                $(chart.series[0].data[d].graphic.element).attr('style', 'outline: none;')
                            }
                        }

                        // chart.series[0].data[itemID].select(true);
                        console.log(chart.series[0].data[itemID]);


                        chart.series[0].data[itemID].setState('hover');
                        chart.innerText.attr({
                            text: chart.series[0].data[itemID].y + '% <br> <tspan y="180" x="138" class="item_name">' + chart.series[0].data[itemID].name + '</tspan>'
                        });

                        if ($(tableRows).attr('chart-item-id') == $(this).attr('chart-item-id')) {
                            // chart.series[0].data[itemID].select(false);
                            chart.series[0].data[itemID].setState('');
                            // $(chart.series[0].data[itemID].tracker.element).trigger('mouseout');
                            chart.innerText.attr({
                                text: '100%'
                            });

                            $(this).removeClass('active');


                            $(chart.series[0].data).each(function (e) {
                                $(this.graphic.element).css('opacity', 1);
                                $(this.graphic.element).attr('style', 'outline: none;')
                            })

                        }
                    });
                }


                let chartID = pChartCount;
                let pieContainer = $(pieWrap[chartID]).find('.allocation-chart');
                pieContainer = $(pieContainer[0]).find('div[role="region"]');

                var chart = jQuery(pieContainer).highcharts();

                var iter = 0;
                $(chart.series[0].data).each(function (e) {

                    $(this.graphic.element).attr('chart-id', chartID);
                    $(this.graphic.element).attr('chart-item-id', iter);


                    $(this.graphic.element).on('mouseenter', function (enter) {
                        let pieWrap = $('.pie-wrap');

                        let chartID = $(this).attr('chart-id');
                        let chartItemID = $(this).attr('chart-item-id');

                        let pieContainer = $(pieWrap[chartID]).find('.allocation-chart');
                        pieContainer = $(pieContainer[0]).find('div[role="region"]');

                        let chart = jQuery(pieContainer).highcharts();

                        $(chart.series[0].data).each(function (item) {
                            let itemID = $(this.graphic.element).attr('chart-item-id');
                            // console.log(itemID + ' | ' + chartItemID);

                            if (itemID != chartItemID) {
                                $(this.graphic.element).css('opacity', 0.2);
                                let activeRow = $(pieWrap[chartID]).find('tr.active')[0];

                                $(activeRow).removeClass('active');
                                $(this.graphic.element).attr('style', 'outline: none;')

                            } else {
                                $(this.graphic.element).css('opacity', 1);
                                // $(this.graphic.element).attr('style', 'outline: none;')
                            }
                        })
                    });

                    iter++;
                });


                pChartCount++;

            });
        }
        //pie portfolio end


        //performance chart
        let $tsp_performance = $(`.tsp-performance_chart[data-performance-chart]`);

        var performanceOnClicked = false;
        var performanceCurrentChart = null;
        var xAxisDataValues = null;
        var performanceHighlightLabel = null;

        if ($tsp_performance.length > 0) {

            $tsp_performance.each(function () {
                let tsp_performance_container = "hc-container-performance-" + $(this).attr('data-performance-chart');

                //get value from shortcode php file
                let tsp_performance_data = 0;
                switch ($(this).attr('data-performance-chart')) {
                    case 'aggressive':
                        if (typeof $tsp_js_perf_data_aggressive !== 'undefined') tsp_performance_data = $tsp_js_perf_data_aggressive;
                        break;
                    case 'conservative':
                        if (typeof $tsp_js_perf_data_conservative !== 'undefined') tsp_performance_data = $tsp_js_perf_data_conservative;
                        break;
                    default:
                        console.log('No data from shortcode');

                }


                $('#' + tsp_performance_container).mousedown(function () {
                    performanceOnClicked = true;
                    performanceCurrentChart = '#' + tsp_performance_container;
                    // console.log('~1')

                    // var chart = $(performanceCurrentChart).highcharts();
                    //     chart.update({
                    //         series: {
                    //             fillColor: 'rgba(0,0,0,0)'
                    //         }
                    //     })

                    $(performanceCurrentChart).find('.highcharts-series path').toggleClass('transparent');

                })

                $('#' + tsp_performance_container).mouseup(function () {
                    // console.log('~2')

                    $(performanceCurrentChart).find('.highcharts-series path').toggleClass('transparent');
                    var chart = $(performanceCurrentChart).highcharts();

                    if (typeof chart.customRect != 'undefined') {
                        if (chart.customRect.added == true) {
                            chart.customRect.destroy();
                            performanceHighlightLabel.destroy();
                            performanceHighlightLabel = null;


                            if (chart.highlightLabelBox.added == true) {
                                chart.highlightLabelBox.destroy();
                            }
                        }
                    }

                    performanceOnClicked = false;
                    performanceCurrentChart = null;
                    xAxisDataValues = null;

                })

                Highcharts.stockChart(tsp_performance_container, {
                    rangeSelector: {
                        enabled: false
                    },

                    navigator: {
                        enabled: false
                    },
                    srcollbar: {
                        enabled: false
                    },
                    title: {
                        text: 'Past performance'
                    },
                    chart: {
                        type: 'area',
                        allowSelect: true,
                        _zoomType: 'x',
                        panning: {
                            enabled: false
                        }

                    },
                    tooltip: {
                        style: {
                            fontSize: '16px',
                            color: '#1227E2'
                        },
                        split: true,
                        // formatter: function () {
                        //     let points = this.points,
                        //         tooltipArray = ['<span>' + new Date(this).toLocaleDateString() + '</span>']
                        //
                        //     points.forEach(function (point, index) {
                        //         tooltipArray.push('<b>●\t : ' + point.y.toFixed(2) + '</b>');
                        //     });
                        //
                        //     return tooltipArray;
                        // }
                    },
                    plotOptions: {
                        series: {
                            fillOpacity: 0.1,
                            allowPointSelect: true,
                            point: {
                                events: {
                                    mouseOver: async function () {
                                        if (performanceOnClicked) {
                                            console.log(this.category)
                                            // let date = this.key;
                                            let date = new Date(this.category).toLocaleDateString();
                                            let chart = this.series.chart;
                                            console.log(new Date(this.category).toLocaleDateString("en-US"))


                                            console.log(date)

                                            let arr = '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="14" viewBox="0 0 11 14" fill="none"><path d="M4.99639 13.0156L4.99638 1.01562M4.99638 1.01562L0.796875 4.78978M4.99638 1.01562L9.25315 4.76599" stroke="#1227E2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                                            let arr_down = '<svg style="transform: rotate(180);"  xmlns="http://www.w3.org/2000/svg" width="11" height="14" viewBox="0 0 11 14" fill="none"><path d="M4.99639 13.0156L4.99638 1.01562M4.99638 1.01562L0.796875 4.78978M4.99638 1.01562L9.25315 4.76599" stroke="#1227E2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';

                                            // console.log('test ' + performanceOnClicked)
                                            if (date != null) {
                                                date = new Date(date)
                                                console.log('~10')
                                                if (typeof chart.customRect != 'undefined') {
                                                    if (chart.customRect.added == true) {
                                                        // console.log(chart.customRect.added)
                                                        chart.customRect.destroy();
                                                        console.log('~12')

                                                    }
                                                }

                                                if (typeof chart.highlightLabelBox != 'undefined') {
                                                    if (chart.highlightLabelBox.added == true) {
                                                        chart.highlightLabelBox.destroy();
                                                        performanceHighlightLabel.destroy();
                                                        performanceHighlightLabel = null;
                                                        console.log('~13')

                                                    }
                                                }


                                                let day = date.getDate();
                                                let month = monthNames[date.getMonth()];
                                                let year = date.getFullYear();

                                                const dataDate = month + ' ' + day + ', ' + year;

                                                if (xAxisDataValues == null) {
                                                    xAxisDataValues = {
                                                        Y: this.y,
                                                        X: this.chartX,
                                                        date: dataDate,
                                                        unixDate: this.category
                                                    };
                                                }


                                                if (xAxisDataValues != null) {
                                                    let X_1 = xAxisDataValues.X;
                                                    let Y_1 = xAxisDataValues.Y;

                                                    let X_2 = this.chartX;
                                                    let Y_2 = this.y

                                                    let firstDate = xAxisDataValues.date;
                                                    firstDate = new Date(firstDate);

                                                    firstDate = firstDate.getTime();

                                                    if (firstDate < date.getTime()) {
                                                        chart.customRect = chart.renderer.rect(X_1, 10, X_2 - X_1, 500).attr({
                                                            fill: {
                                                                linearGradient: [0, 1, 0, 200],
                                                                stops: [
                                                                    [0, 'rgba(135,154,255,0.09)'],
                                                                    [1, '#fff']
                                                                ]
                                                            },
                                                            'stroke-width': 3
                                                        }).add();

                                                    } else if (firstDate > date.getTime()) {
                                                        chart.customRect = chart.renderer.rect(X_2, 10, X_1 - X_2, 500).attr({
                                                            fill: {
                                                                linearGradient: [0, 1, 0, 200],
                                                                stops: [
                                                                    [0, 'rgba(135,154,255,0.06)'],
                                                                    [1, '#fff']
                                                                ]
                                                            },
                                                            'stroke-width': 3
                                                        }).add();

                                                    }


                                                    let r = Math.abs((Y_1 - Y_2) / (Y_1 / 100));


                                                    if (xAxisDataValues.unixDate > this.category) {
                                                        r = await Math.abs((Y_2 - Y_1) / (Y_2 / 100));

                                                        let y_1 = Y_1;
                                                        let y_2 = Y_2;

                                                        Y_1 = y_2;
                                                        Y_2 = y_1;
                                                    }

                                                    r = await trunc(r);

                                                    console.log(r)

                                                    const boxStyle = {
                                                        fill: '#fff',
                                                        color: 'black',
                                                        class: 'labelBox',
                                                        zIndex: 50
                                                    }

                                                    // 
                                                    // r = 8.46 + 7.75 + (17.8 * 2) + r;

                                                    let res = await getLabelWidth(r, 16) + 11 + 35.6 + 7.75;

                                                    console.log(res);

                                                    chart.highlightLabelBox = chart.renderer.rect(((X_1 + ((X_2 - X_1) / 2)) - ((res / 2 - 2))), 5, res, 52, 12)
                                                        .attr(boxStyle)
                                                        .add().shadow(true);


                                                    if (Y_1 > Y_2) {

                                                        if (performanceHighlightLabel == null) {
                                                            performanceHighlightLabel = chart.renderer.text(
                                                                '<span class="down" >' + arr_down + '</span>' + r + '%',
                                                                (X_1 + ((X_2 - X_1) / 2) - (res / 2 - 15)),
                                                                32, true
                                                            ).attr({
                                                                fill: 'red',
                                                                r: 5,
                                                                padding: 14,
                                                                color: 'black',
                                                                class: 'down',
                                                                zIndex: 55
                                                            }).add().css({
                                                                color: 'black',
                                                                'font-size': '16px'
                                                            }).toFront();


                                                            if (performanceHighlightLabel.added == true) {
                                                                // console.log('width: ' + performanceHighlightLabel.getComputedTextLength())

                                                            }

                                                        }

                                                    } else if (Y_1 < Y_2) {
                                                        // console.log('Up ' + r + '%');
                                                        if (performanceHighlightLabel == null) {

                                                            performanceHighlightLabel = chart.renderer.text(
                                                                '<span class="up">' + arr + '</span>' + r + '%',
                                                                (X_1 + ((X_2 - X_1) / 2) - (res / 2 - 15)),
                                                                32, true
                                                            ).attr({
                                                                fill: '#FFFFFF',
                                                                r: 5,
                                                                padding: 14,
                                                                color: '#000',
                                                                class: 'up',
                                                                zIndex: 55
                                                            }).add().css({color: 'black'}).toFront();


                                                        }
                                                    } else if (Y_1 === Y_2) {
                                                        // console.log('Hold ' + r + '%');
                                                        if (performanceHighlightLabel == null) {
                                                            performanceHighlightLabel = chart.renderer.text(
                                                                '<span class="hold">● </span>' + r + '%',
                                                                (X_1 + ((X_2 - X_1) / 2) - (res / 2 - 15)),
                                                                32, true
                                                            ).attr({
                                                                fill: '#FFFFFF',
                                                                r: 5,
                                                                padding: 14,
                                                                color: '#000',
                                                                class: 'hold',
                                                                zIndex: 55
                                                            }).add().css({color: 'black'}).toFront();

                                                        }
                                                    }

                                                }

                                            }
                                        }
                                    }
                                }
                            }
                        }
                    },
                    // tooltip: {
                    //     style: {
                    //         fontSize: '16px',
                    //         color: '#1227E2'
                    //     },
                    //     // formatter: function () {
                    //     //     return 'The value for <b>' + this.x +
                    //     //         '</b> is <b>' + this.y + '</b>';
                    //     // }
                    // },


                    xAxis: {
                        startOfWeek: 1,
                        type: 'datetime',
                        // ordinal: false,
                        // startOnTick: true,
                        // startOnTick: false,
                        // endOnTick: false,
                        showLastTickLabel: true,
                        labels: {
                            format: '{value:%Y}',
                            step: 1,

                        },
                        dateTimeLabelFormats: {
                            millisecond: '%b %e',
                            second: '%b %e',
                            minute: '%b %e',
                            hour: '%b %e',
                            day: '%b %e',
                            week: '%b %e',
                            month: '%b %e',
                            year: '%b %e'
                        }
                    },

                    series: [{
                        name: '',
                        data: tsp_performance_data,
                        tooltip: {
                            valueDecimals: 2
                        },
                        color: '#2546FF',
                        fillColor: {
                            linearGradient: [0, 1, 0, 200],
                            stops: [
                                [0, 'rgba(135,154,255,0.09)'],
                                [1, '#fff']
                            ]
                        },
                        // pointIntervalUnit: 'day',
                        // pointStart: Date.UTC(2004, 9, 3),
                        pointInterval: 100000,
                        dataGrouping: {
                            approximation: 'high',
                            enabled: true,
                            forced: true,
                            firstAnchor: 'start',
                            groupPixelWidth: 1,
                            units: [
                                ['day', [1]]
                            ]
                        }
                    }],

                }, function (chart) {

                })

            });

            performanceChartRangeButtons(); // range buttons init
        }
        //performance chart end

        //past allocations
        let $tsp_past_allocations = $(` .past-allocation_graph[data-alloc-graph]`);

        if ($tsp_past_allocations.length > 0) {
            let alloc_iter = 0;

            $tsp_past_allocations.each(function () {

                let tsp_past_alloc_container = "hc-container-past-alloc-" + $(this).attr('data-alloc-graph');

                let tsp_past_alloc_data = JSON.parse($(`#hc-data-past-alloc-${$(this).attr('data-alloc-graph')}`).text());
                // console.log(tsp_past_alloc_container)
                // console.log(tsp_past_alloc_data['c_f_dat'])

                var alloc_chart = new Highcharts.Chart({
                    chart: {
                        renderTo: tsp_past_alloc_container,
                        type: 'area'
                    },
                    xAxis: {
                        type: 'datetime',

                    },
                    yAxis: {
                        labels: {
                            formatter: function () {
                                let pr = this.value + '%';
                                return pr;
                            }
                        }
                    },

                    plotOptions: {
                        series: {
                            // pointStart: past_allocation_data_aggressive['g_f_dat'][0],
                            // pointInterval: 24 * 3600 * 1000 // one day
                        },

                        area: {
                            stacking: 'percent',
                            lineColor: '#ffffff',
                            lineWidth: 1,
                            marker: {
                                lineWidth: 1,
                                lineColor: '#ffffff'
                            }
                        }
                    },
                    tooltip: {
                        pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.percentage:.1f}%</b> <!--({point.y:,.0f} percentages)<br/>-->',
                        split: true,
                        xDateFormat: '%d %B %Y',
                        shared: true
                    },

                    series: [{
                        name: 'G Fund',
                        data: tsp_past_alloc_data['g_f_dat'],
                        color: '#1227E2'
                    }, {
                        name: 'F Fund',
                        data: tsp_past_alloc_data['f_f_dat'],
                        color: '#001561'
                    }, {
                        name: 'C Fund',
                        data: tsp_past_alloc_data['c_f_dat'],
                        color: '#FDDA02'
                    }, {
                        name: 'S Fund',
                        data: tsp_past_alloc_data['s_f_dat'],
                        color: '#FF9900'
                    }, {
                        name: 'I Fund',
                        data: tsp_past_alloc_data['i_f_dat'],
                        color: '#EF6400'
                    }]
                });

                let allocWrap = $('.tsphp-past-allocations');
                allocWrap = allocWrap[alloc_iter];

                let id = $(allocWrap).attr('id');
                let pagination = $(allocWrap).find('.pagination');
                pagination = $(pagination).find('a');

                $(pagination).each(function (e) {
                    $(this).attr('href', $(this).attr('href') + '#' + id);
                })

                $(allocWrap).find('.getPDF').attr('pdf-id', alloc_iter);
                $(allocWrap).find('.getPDF').attr('pdf-class', 'tsphp-past-allocations');


                alloc_iter++;

            })
        }

        //dropdown2
        $('.past-allocation_table').each(function () {
            $(this).click(function (ev) {
                if (ev.target.hasAttribute('data-alert_date_trg')) {
                    let tID = '#' + $(ev.target).attr('data-alert_date_trg');
                    // console.log($(ev.target).closest('.past-allocation_table').find(tID));
                    // $(ev.target).toggleClass('opened');
                    $(ev.target).closest('.alert_main').toggleClass('res_opened');
                    $(ev.target).closest('.past-allocation_table').find(tID).fadeToggle().toggleClass('res_opened');

                }
            })
        })

        $("#tsphp-past-allocations-aggressive table").fancyTable({
            sortable: false,
            perPage: 10,
            pagination: true,// default: false
            paginationElement: '#tsphp-past-allocations-aggressive .pagination',
            paginationClassActive: 'current',
            paginationClass: 'btn-prevdef',
            searchable: false,
            globalSearch: false,
            onUpdate: function () {
                element: this.find('tr').removeClass('res_opened');
                // console.log({
                //     element: this
                // });
            }

        });
        $("#tsphp-past-allocations-conservative table").fancyTable({
            sortable: false,
            perPage: 10,
            pagination: true,// default: false
            paginationElement: '#tsphp-past-allocations-conservative .pagination',
            paginationClassActive: 'current',
            paginationClass: 'btn-prevdef',
            searchable: false,
            globalSearch: false,
            onUpdate: function () {
                element: this.find('tr').removeClass('res_opened');
                // console.log({
                //     element: this
                // });
            }

        });
        $('.past-allocation_table .btn-prevdef ').click(function (e) {
            e.preventDefault();
        })

        //past allocations dropdown
        // $('.past-allocation_table').each(function () {
        //     $(this).click(function (ev) {
        //         if (ev.target.hasAttribute('data-alert_date_trg')) {
        //             let tID = '#' + $(ev.target).attr('data-alert_date_trg');
        //             // console.log($(ev.target).closest('.past-allocation_table').find(tID));
        //             // $(ev.target).toggleClass('opened');
        //             $(ev.target).closest('.alert_main').toggleClass('res_opened');
        //             $(ev.target).closest('.past-allocation_table').find(tID).fadeToggle().toggleClass('res_opened');
        //
        //         }
        //     })
        // })

        // paging
        // let $pagi_tables = $('.tsphp-past-allocations .past-allocation_table table');
        // if ($pagi_tables.length > 0) {
        //     $pagi_tables.each(function () {
        //         $(this).paging({
        //             limit: 10,
        //             rowDisplayStyle: 'table-row',
        //             activePage: 0,
        //             rows: []


        //         });
        //     })


        // }

        // if ($('.tsphp-past-allocations .past-allocation_table tbody').length > 0) {
        //     console.log('121')
        //
        //     let myPagination = new purePajinate({
        //         containerSelector: '.tsphp-past-allocations .past-allocation_table tbody',
        //         itemSelector: 'tr',
        //         navigationSelector: '.tsphp-past-allocations .pagination',
        //         itemsPerPage: 10,
        //         pageLinksToDisplay: 5,
        //         showPrevNext: false,
        //
        //
        //
        //     });
        //
        // }


        //past allocations end

        //performance chart
        let $tsp_performance_tiles = $(`.performance-tile[data-tsp_pt]`);

        if ($tsp_performance_tiles.length > 0) {
            // let the_gradients = [
            //     [0, 'rgba(135,154,255,0.15)'],
            //     [0, 'rgba(0,68,180,0.07)'],
            //     [0, 'rgba(253,218,2,0.07)'],
            //     [0, 'rgba(255,153,0,0.07)'],
            //     [0, 'rgba(239,100,0,0.07)']
            // ];

            $tsp_performance_tiles.each(function (i) {
                console.log(i)
                let tsp_performance_tile_data = JSON.parse($(`#pt-graph-data-${$(this).attr('data-tsp_pt')}`).text());
                // console.log(tsp_performance_tile_data)

                let tsp_performance_tile_container = "pt-graph-container-" + $(this).attr('data-tsp_pt');
                // console.log(tsp_performance_tile_container)

                Highcharts.stockChart(tsp_performance_tile_container, {
                    rangeSelector: {
                        enabled: false,

                    },

                    title: {
                        text: ''
                    },
                    chart: {
                        type: 'area',
                        zoomType: false

                    },
                    scrollbar: {
                        enabled: false
                    },
                    navigator: {
                        enabled: false
                    },
                    plotOptions: {
                        series: {
                            fillOpacity: 0.1
                        }
                    },

                    tooltip: {
                        useHTML: true,
                        formatter: function () {
                            // console.log(Math.abs(this.y))
                            return Highcharts.dateFormat('%e %b %Y', new Date(this.x)) + '<br><b style="color: #1227E2";>● $' +
                                this.y.toFixed(2) + '</b>';
                        }
                    },

                    series: [{
                        name: '',
                        data: tsp_performance_tile_data,
                        tooltip: {
                            valueDecimals: 2
                        },
                        color: '#2546FF',
                        fillColor: {
                            linearGradient: [0, 1, 0, 200],
                            stops: [
                                [0, 'rgba(135,154,255,0.15)'],
                                [1, 'rgba(255,255,255,0.02)']
                            ]
                        }

                    }]
                });

            });
        }

        //performance chart end

        const trunc = (n, decimalPlaces) => {
            const decimals = decimalPlaces ? decimalPlaces : 2;
            const asString = n.toString();
            const pos = asString.indexOf('.') != -1 ? asString.indexOf('.') + decimals + 1 : asString.length;
            return parseFloat(n.toString().substring(0, pos));
        };


        function getLabelWidth(r, fs) {
            return new Promise(async function (resolve, reject) {
                let test = document.createElement('div');
                test.style.fontSize = fs;
                test.classList.add('confLabelWidth');
                test.innerHTML = " " + r;

                let w = $('.tsp-performance_perc').append(test);
                await $('.confLabelWidth').css('width', 'fit-content');

                let width = await $('.confLabelWidth').width();
                await $('.tsp-performance_perc .confLabelWidth').remove();

                resolve(width);

            })
        }


        function performanceChartRangeButtons() {
            let rangeWrap = $('.performance-wrap .range-buttons');
            let rangeButtons = $('.performance-wrap .range-buttons ul li a');

            for (var i = 0; i <= rangeWrap.length - 1; i++) {
                let buttons = $(rangeWrap[i]).find('ul a');

                for (var b = 0; b <= buttons.length - 1; b++) {
                    $(buttons[b]).attr('wrap-id', i);
                }

                $(rangeWrap[i]).find('.options').slideToggle(500);


                let optionButton = $(rangeWrap[i]).find('.custom-options-button')[0];
                $(rangeWrap[i]).find('.start-date').attr('input-wrap', i);
                $(rangeWrap[i]).find('.end-date').attr('input-wrap', i);
                // $(rangeWrap[i]).find('.submit').attr('sub-id', i);

                $(optionButton).attr('option-id', i);


                let submit = $(rangeWrap[i]).find('.submit');
                let close = $(rangeWrap[i]).find('.close');

                $(submit).attr('sub-id', i);
                $(close).attr('sub-id', i);


                $(submit).click(function (e) {
                    let rangeWrap = $('.performance-wrap .range-buttons');
                    const wID = $(this).attr('sub-id');
                    const startDate = $(rangeWrap[wID]).find('.start-date').val();
                    const endDate = $(rangeWrap[wID]).find('.end-date').val();


                    if (startDate.length > 1 && endDate.length > 1) {
                        let chartSelector = $('.performance-container');
                        chartSelector = chartSelector[wID];

                        let now = new Date(endDate);
                        let min = new Date(startDate);

                        let chart = jQuery(chartSelector).highcharts();
                        chart.xAxis[0].setExtremes(min.getTime(), now.getTime());
                        $(rangeWrap[wID]).find('.options').slideToggle(500);

                        let performanceWrapSelector = $('.performance-wrap');
                        let btns = $(performanceWrapSelector[wID]).children('.range-buttons').find('.active')

                        $(btns).toggleClass('active');
                        $(rangeWrap[wID]).find('.custom-options-button').addClass('active');

                        console.log($(rangeWrap[wID]).find('.custom-options-button'))
                    }
                })

                $(close).click(function () {
                    let rangeWrap = $('.performance-wrap .range-buttons');
                    const wID = $(this).attr('sub-id');

                    $(rangeWrap[wID]).find('.options').slideToggle(500);
                });

                // $(submit).attr('sub-id', i);

                $(optionButton).click(function () {
                    let oID = $(this).attr('option-id'); // Option Modal ID
                    let rangeWrap = $('.performance-wrap .range-buttons');
                    $(this).next().slideToggle(500);
                });
            }

            for (var i = 0; i <= rangeButtons.length - 1; i++) {
                $(rangeButtons[i]).click(function (e) {
                    let wrapID = $(this).attr('wrap-id');
                    let date = $(this).attr('data-date');

                    let now = new Date(); // Today
                    let min = new Date();

                    if (date != 'all' || date != 'ytd') {
                        min.setDate(now.getDate() - date);
                    }

                    if (date == 'all') {
                        min = new Date('1/1/2000');
                    }

                    if (date == 'ytd') {
                        min = new Date(new Date().getFullYear(), 1, 1); // Current Year
                    }

                    let chartSelector = $('.performance-container');
                    chartSelector = chartSelector[wrapID];

                    let chart = jQuery(chartSelector).highcharts();
                    chart.xAxis[0].setExtremes(min.getTime(), now.getTime());

                    if (date != 'all' && date <= 365 || date == 'ytd') {

                        chart.update({
                            xAxis: {
                                labels: {
                                    format: '{value: %b, %e}',
                                    step: 0,
                                },
                            },
                        })
                    } else if (date == 'all' || date >= 1080) {
                        chart.update({
                            xAxis: {
                                labels: {
                                    format: (date == 1080) ? '{value:%b,%Y}' : '{value:%Y}',
                                    step: (date == 1080) ? 2 : 1,

                                },
                            },
                        })
                    }

                    let performanceWrapSelector = $('.performance-wrap');
                    let btns = $(performanceWrapSelector[wrapID]).children('.range-buttons').find('.active')
                    console.log(btns)
                    $(btns).toggleClass('active');
                    $(this).toggleClass('active');

                });
            }
        }


    });

})(jQuery);
