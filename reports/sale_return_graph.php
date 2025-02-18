<div class="ui-widget ui-corner-all">
    <div class="ui-widget-header ui-corner-top">Sales</div>
    <div class="ui-widget-content ui-corner-bottom" >
        <div id="sales"></div>
    </div>
</div>
 <script class="code" type="text/javascript">
    $(document).ready(function () {
        $.jqplot._noToImageButton = true;
        var currYear = [["2014-10-01",796.01], ["2014-10-02",510.5], ["2014-10-03",527.8], ["2014-10-04",308.48], 
        ["2014-10-05",420.36], ["2014-10-06",219.47], ["2014-10-07",333.82], ["2014-10-08",660.55], ["2014-10-09",1093.19], 
        ["2014-10-10",521], ["2014-10-11",660.68], ["2014-10-12",928.65], ["2014-10-13",864.26], ["2014-10-14",395.55], 
        ["2014-10-15",623.86], ["2014-10-16",1300.05], ["2014-10-17",972.25], ["2014-10-18",661.98], ["2014-10-19",1008.67], 
        ["2014-10-20",1546.23], ["2014-10-21",593], ["2014-10-22",560.25], ["2014-10-23",857.8], ["2014-10-24",939.5], 
        ["2014-10-25",1256.14], ["2014-10-26",1033.01], ["2014-10-27",811.63], ["2014-10-28",735.01], ["2014-10-29",985.35], 
        ["2014-10-30",1401.58], ["2014-10-31",1177], ["2014-11-01",1023.66], ["2014-11-02",1442.31], ["2014-11-03",1299.24], 
        ["2014-11-04",1306.29], ["2014-11-06",1800.62], ["2014-11-07",1607.18], ["2014-11-08",1702.38], 
        ["2014-11-09",4118.48], ["2014-11-10",1988.11], ["2014-11-11",1485.89], ["2014-11-12",2681.97], 
        ["2014-11-13",1679.56], ["2014-11-14",3538.43], ["2014-11-15",3118.01], ["2014-11-16",4198.97], 
        ["2014-11-17",3020.44], ["2014-11-18",3383.45], ["2014-11-19",2148.91], ["2014-11-20",3058.82], 
        ["2014-11-21",3752.88], ["2014-11-22",3972.03], ["2014-11-23",2923.82], ["2014-11-24",9000.59], 
        ["2014-11-25",2785.93], ["2014-11-26",4329.7], ["2014-11-27",3493.72], ["2014-11-28",4440.55], 
        ["2014-11-29",5235.81], ["2014-11-30",6473.25]];

        var plot1 = $.jqplot("sales", [currYear], {
            seriesColors: ["rgba(78, 135, 194, 0.7)", "rgb(211, 235, 59)"],
            title: 'Monthly Sales',
            highlighter: {
                show: true,
                sizeAdjust: 1,
                tooltipOffset: 9
            },
            grid: {
                background: 'rgba(57,57,57,0.0)',
                drawBorder: false,
                shadow: false,
                gridLineColor: '#666666',
                gridLineWidth: 2
            },
            legend: {
                show: false,
                placement: 'inside'
            },
            seriesDefaults: {
                rendererOptions: {
                    smooth: true,
                    animation: {
                        show: true
                    }
                },
                showMarker: false
            },
            series: [
                {
                    fill: true,
                    label: '2014'
                }
            ],
            axesDefaults: {
                rendererOptions: {
                    baselineWidth: 1.5,
                    baselineColor: '#CCCCCC',
                    drawBaseline: false
                }
            },
            axes: {
                xaxis: {
                    renderer: $.jqplot.DateAxisRenderer,
                    tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                    tickOptions: {
                        formatString: "%b %e",
                        angle: -30,
                        textColor: '#dddddd'
                    },
                    min: "2014-10-01",
                    max: "2014-11-30",
                    tickInterval: "7 days",
                    drawMajorGridlines: false
                },
                yaxis: {
                    renderer: $.jqplot.LogAxisRenderer,
                    pad: 0,
                    rendererOptions: {
                        minorTicks: 1
                    },
                    tickOptions: {
                        formatString: "PKR%'d",
                        showMark: false
                    }
                }
            }
        });
          $('.jqplot-highlighter-tooltip').addClass('ui-corner-all')
    });
</script>