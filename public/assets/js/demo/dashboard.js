
// Dashboard.js
// ====================================================================
// This file should not be included in your project.
// This is just a sample how to initialize plugins or components.
//
// - ThemeOn.net -


$(document).on('nifty.ready', function() {

    // NETWORK CHART
    // =================================================================
    // Require Flot Charts
    // -----------------------------------------------------------------
    // http://www.flotcharts.org/
    // =================================================================

    var plot = $.plot('#demo-chart-network', [
        {
            label: 'Visiteurs',
            data: dataVisiteursRecurrent,
            lines: {
                show: true,
                lineWidth: 0,
                fill: true,
                fillColor: {
                    colors: [{
                        opacity: 0.8
                    }, {
                        opacity: 0.8
                    }]
                }
            },
            points: {
                show: false
            }
        },
        {
            label: 'Nouveaux visiteurs',
            data: dataVisiteursUnique,
            lines: {
                show: true,
                lineWidth: 0,
                fill: true,
                fillColor: {
                    colors: [{
                        opacity: 0.9
                    }, {
                        opacity: 0.9
                    }]
                }
            },
            points: {
                show: false
            }
        }
        ], {
        series: {
            lines: {
                show: true
            },
            points: {
                show: true
            },
            shadowSize: 0 // Drawing is faster without shadows
        },
        colors: ['#b5bfc5','#25476a'],
        legend: {
            show: true,
            position: 'nw',
            margin: [0, 0]
        },
        grid: {
            borderWidth: 0,
            hoverable: true,
            clickable: true
        },
        yaxis: {
            show: true,
            ticks: 5,
            tickColor: 'rgba(0,0,0,.1)'
        },
        xaxis: {
            show: true,
            mode: 'time',
            timeformat: '%d/%m'
        },
        tooltip: {
            show: true,
            content: "<div class='flot-tooltip text-center'><h5 class='text-main'>%s</h5>%y.0 Mbps</div>"
        }
    });






    // HDD USAGE - SPARKLINE LINE AREA CHART
    // =================================================================
    // Require sparkline
    // -----------------------------------------------------------------
    // http://omnipotent.net/jquery.sparkline/#s-about
    // =================================================================
    var hddSparkline = function() {
        $("#demo-sparkline-area").sparkline([57,69,70,62,73,79,76,77,73,52,57,50,60,55,70,68,57,62,53,69,59,67,69,58,50,47,65], {
            type: 'line',
            width: '100%',
            height: '60',
            spotRadius: 4,
            lineWidth: 2,
            lineColor:'rgba(255,255,255,.85)',
            fillColor: 'rgba(0,0,0,0.1)',
            spotColor: 'rgba(255,255,255,.5)',
            minSpotColor: 'rgba(255,255,255,.5)',
            maxSpotColor: 'rgba(255,255,255,.5)',
            highlightLineColor : '#ffffff',
            highlightSpotColor: '#ffffff',
            tooltipChartTitle: 'Usage',
            tooltipSuffix:' %'

        });
    }



    // EARNING - SPARKLINE LINE CHART
    // =================================================================
    // Require sparkline
    // -----------------------------------------------------------------
    // http://omnipotent.net/jquery.sparkline/#s-about
    // =================================================================
    var earningSparkline = function(){
        $("#demo-sparkline-line").sparkline([945, 754, 805, 855, 678, 987, 1026, 885, 878, 922, 875, ], {
            type: 'line',
            width: '100%',
            height: '60',
            spotRadius: 0,
            lineWidth: 2,
            lineColor: '#ffffff',
            fillColor: false,
            minSpotColor: false,
            maxSpotColor: false,
            highlightLineColor: '#ffffff',
            highlightSpotColor: '#ffffff',
            tooltipChartTitle: 'Earning',
            tooltipPrefix: '$ ',
            spotColor: '#ffffff',
            valueSpots: {
                '0:': '#ffffff'
            }
        });
    }



    // SALES - SPARKLINE BAR CHART
    // =================================================================
    // Require sparkline
    // -----------------------------------------------------------------
    // http://omnipotent.net/jquery.sparkline/#s-about
    // =================================================================

    var barEl = $("#demo-sparkline-bar");
    var barValues = [40,32,65,53,62,55,24,67,45,70,45,56,34,67,76,32,65,53,62,55,24,67,45,70,45,56,70,45,56,34,67,76,32,65,53];
    var barValueCount = barValues.length;
    var barSpacing = 1;
    var salesSparkline = function(){
         barEl.sparkline(barValues, {
            type: 'bar',
            height: 78,
            barWidth: Math.round((barEl.parent().width() - ( barValueCount - 1 ) * barSpacing ) / barValueCount),
            barSpacing: barSpacing,
            zeroAxis: false,
            tooltipChartTitle: 'Daily Sales',
            tooltipSuffix: ' Sales',
            barColor: 'rgba(0,0,0,.15)'
        });
    }


    $(window).on('resizeEnd', function(){
        hddSparkline();
        earningSparkline();
        salesSparkline();
    })
    hddSparkline();
    earningSparkline();
    salesSparkline();





    // PANEL OVERLAY
    // =================================================================
    // Require Nifty js
    // -----------------------------------------------------------------
    // http://www.themeon.net
    // =================================================================
    $('#demo-panel-network-refresh').niftyOverlay({
        iconClass : 'demo-psi-repeat-2 spin-anim icon-2x'
    }).on('click', function(){
        var $el = $(this), relTime;

        $el.niftyOverlay('show');


        relTime = setInterval(function(){
            $el.niftyOverlay('hide');
            clearInterval(relTime);
        },2000);
    });


});
