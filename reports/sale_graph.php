<?php
	$currency = $new_store->get_store_info($_SESSION['store_id'], 'currency'); 
	$sale = new Sale;
?>
<div class="ui-widget ui-corner-all">
    <div class="ui-widget-header ui-corner-top">Sales</div>
    <div class="ui-widget-content ui-corner-bottom" >
        <div id="sales"></div>
    </div>
</div>
 <script class="code" type="text/javascript">
    $(document).ready(function () {
        $.jqplot._noToImageButton = true;
        var currYear = [<?php $sale->sale_graph_data(); ?>];

        var plot1 = $.jqplot("sales", [currYear], {
            seriesColors: ["rgba(78, 135, 194, 0.7)", "rgb(211, 235, 59)"],
            title: 'Weekly Sales',
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
                    label: '<?php echo date('Y'); ?>'
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
                    min: "<?php echo date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) ."-7 day")); ?>",
                    max: "<?php echo date('Y-m-d'); ?>",
                    tickInterval: "1 day",
                    drawMajorGridlines: false
                },
                yaxis: {
                    renderer: $.jqplot.LogAxisRenderer,
                    pad: 0,
                    rendererOptions: {
                        minorTicks: 1
                    },
                    tickOptions: {
                        formatString: "<?php echo $currency; ?>%'d",
                        showMark: false
                    }
                }
            }
        });
          $('.jqplot-highlighter-tooltip').addClass('ui-corner-all')
    });
</script>