<?php
include_once 'utils2.php';
$less_than_one_giga = $chart["query"]["less_than_one_giga"];
$one_giga_to_ten_giga = $chart["query"]["one_giga_to_ten_giga"];
$ten_to_hundred_giga = $chart["query"]["ten_to_hundred_giga"];
$hundred_to_tera = $chart["query"]["hundred_to_tera"];
$more_than_tera = $chart["query"]["more_than_tera"];
$avg_bytes = $chart["query"]["median_bytes"];
$max_bytes = $chart["query"]["max_bytes"];



var_dump($less_than_one_giga);

// if (isset($_POST["application"]) && strlen($_POST["application"]) > 0) {
//     $q = Jobs::filter($q, "appname", $_POST["application"]);
// }
// $orderby = "start_time";
// if (isset($_POST["sort-level1"])) {
//     $orderby = $_POST["sort-level1"];
// }
// //if ($orderby != "nprocs" && $orderby != "total_bytes") {
// //    $orderby = $orderby . "/(runtime + shared_time_by_cumul_io_only - shared_time_by_cumul_meta_only)";
// //}
// $mode1 = "desc";
// if (isset($_POST["mode-level1"])) {
// //    echo "set to ". $_POST["mode-level1"];
//     $mode1 = $_POST["mode-level1"];
// }
// //$orderby = "notio" . "/(runtime + shared_time_by_cumul_io_only - shared_time_by_cumul_meta_only) asc";
// //$orderby .= ", globalio";
// $q = Jobs::OrderBy($q, $orderby, $mode1);
// $q = Jobs::Limit($q, 5000);
// if (isset($_POST["sort-level2"])) {
//     $sortlevel2 = $_POST["sort-level2"];
//     $mode2 = "desc";
//     if (isset($_POST["mode-level2"])) {
//         $mode1 = $_POST["mode-level2"];
//     }
//     $q = Jobs::addSortingLevel($q, $sortlevel2, $mode2);
// }
// if (isset($_POST["sort-level3"])) {
//     $sortlevel3 = $_POST["sort-level3"];
//     $mode3 = "desc";
//     if (isset($_POST["mode-level3"])) {
//         $mode1 = $_POST["mode-level3"];
//     }
//     $q = Jobs::addSortingLevel($q, $sortlevel3, $mode3);
// }
// if (isset($_POST["user"]) && strlen($_POST["user"]) > 0) {
//     $q = Jobs::filter($q, "uid", $_POST["user"]);
// }
//var_dump($q) ;
$data[1] = Jobs::execSQLQuery($less_than_one_giga);
$data[2] = Jobs::execSQLQuery($one_giga_to_ten_giga);
$data[3] = Jobs::execSQLQuery($ten_to_hundred_giga);
$data[4] = Jobs::execSQLQuery($hundred_to_tera);
$data[5] = Jobs::execSQLQuery($more_than_tera);
$data[6] = Jobs::execSQLQuery($avg_bytes);
$data[7] = Jobs::execSQLQuery($max_bytes);






//print_r($data);
//$cats_str = "";
//var_dump($data[1]);
$series_str = array();


$attr_count = 7;
//initialize strings for the attribute series
for ($i = 1; $i <= $attr_count; $i++) {
    // var_dump($chart["series"][0]["attr" . $i]);
    if (!isset($series_str[$i])) {
        $series_str[$i] = "";
    }
    if (!isset($cat_str[$i])) {
        $cat_str[$i] = "";
    }
}

for ($i = 1; $i <= $attr_count; $i++) {
    $index = 1;
    //var_dump($data[$i]);
    foreach ($data[$i] as $each_data) {
        //echo $i.'\n';
        //echo $chart["series"][0]["attr".$i];
        if ($each_data[$chart["series"][0]["attr" . $i]] == null)
            $each_data[$chart["series"][0]["attr" . $i]] = '0';
        $cat_str[$i] .= '\'' . $index . '\'' . ',';
        $series_str[$i] .= $each_data[$chart["series"][0]["attr" . $i]] . ',';
        $index++;
    }
    $cat_str[$i] = rtrim($cat_str[$i], ",");
    $series_str[$i] = rtrim($series_str[$i], ",");
}



$categories = array();
$idx = 1;
foreach ($data[1] as $each_data) {

    $categories[] = $each_data["appname"];
    $index++;
}
//$categories = rtrim($categories, ",");
//$categories .= ']';
$chart["highchart-confs"]["xAxis"]["categories"] = $categories;
//var_dump($chart["highchart-confs"]["xAxis"]);
//    $cats_str .= '\'' . $d[$chart["xAxis"]["attribute"]] . '\'' . ',';
//    $series_str .= '[' . $d[$chart["series"][0]["min"]] . ',' . ($d[$chart["series"][0]["q1"]] * 2) . ',' . $d[$chart["series"][0]["median"]] . ',' . ($d[$chart["series"][0]["q3"]] * 0.5) . ',' . $d[$chart["series"][0]["max"]] . '],';
//$cats_str = rtrim($cats_str, ",");
// for ($i = 1; $i <= $attr_count; $i++) {
//     $series_str[$chart["series"][0]["attr" . $i]] = rtrim($series_str[$chart["series"][0]["attr" . $i]], ",");
// }
//var_dump($series_str);
?>

<script type="text/javascript">

    $(function () {
        $('#tooltip1').tooltip({
            title:
                    'Non-global data I/O: The amount of time this job spent in function calls to read/write its files not accessed by all processes.'
        });
        $('#tooltip2').tooltip({
            title:
                    'Non-global Metadata: The amount of time this job spent in metadata function calls (open, close, seek, etc.) for non-global files, i.e., files that one or more but not all processes opened.'
        });
        $('#tooltip3').tooltip({
            title:
                    'Global data I/O: The amount of time this job spent in function calls to read/write global files, i.e., files that all processes opened.'
        });
        $('#tooltip4').tooltip({
            title:
                    'Global Metadata: The amount of time this job spent in metadata function calls (open, close, seek, etc.) for global files, i.e., files that all processes opened.'
        });
        $('#tooltip5').tooltip({
            title:
                    'Not I/O: The amount of time this job spent outside of I/O function calls (data and metadata).'
        });
        $('#tooltip6').tooltip({
            title:
                    '# of Processes: The number of processes this job had.'
        });
        $('#tooltip7').tooltip({
            title:
                    'Total Bytes Read/Written: The total number of bytes this job read and wrote.'
        });
//        $("#tooltip1").tooltip('show');
//        $('[data-toggle="tooltip"]').tooltip();
        var globalCallback = function (chart) {
            // Specific event listener
            Highcharts.addEvent(chart.container, 'click', function (e) {
                e = chart.pointer.normalize();
                console.log('Clicked chart at ' + e.chartX + ', ' + e.chartY);
            });
            // Specific event listener
            Highcharts.addEvent(chart.xAxis[0], 'afterSetExtremes', function (e) {
                console.log('Set extremes to ' + e.min + ', ' + e.max);
            });
            Highcharts.addEvent(chart, 'load', function (e) {
//                e = chart.pointer.normalize();
                console.log('loaded');
                var chart = this,
                        legend = chart.legend;
                for (var i = 0, len = legend.allItems.length; i < len; i++) {
                    (function (i) {
                        var item = legend.allItems[i].legendItem;
                        item.on('mouseover', function (e) {
                            //show custom tooltip here
                            console.log("mouseover" + i);
//                            $('#tooltips').tooltip();
                            $("#tooltip" + (i + 1)).tooltip('show');
                        }).on('mouseout', function (e) {
                            //hide tooltip
                            console.log("mouseout" + i);
                            $("#tooltip" + (i + 1)).tooltip('hide');
                        });
                    })(i);
                }
            });
        }

// Add `globalCallback` to the list of highcharts callbacks
        Highcharts.Chart.prototype.callbacks.push(globalCallback);
        $('#chart-container').highcharts({
<?php echo getHighchartSafeJson($chart["highchart-confs"]); ?>



            series: [{
                    name: '<?php echo $chart["series"][0]["title1"] ?>',
                    type: 'column',
                    color: '#00CCFF',
                    stacking: 'percent',
                    index: 4,
//                    yAxis: 1,
                    data: [<?php echo nullSafe($series_str[1]); ?>]
                },
                {
                    name: '<?php echo $chart["series"][0]["title2"] ?>',
                    type: 'column',
                    color: '#33CC00',
                    stacking: 'percent',
                    index: 3,
//                    yAxis: 1,
                    data: [<?php echo nullSafe($series_str[2]); ?>]
//                    tooltip: {
//                        valueSuffix: ' mm'
//                    }
                },
                {
                    name: '<?php echo $chart["series"][0]["title3"] ?>',
                    type: 'column',
                    color: '#FFFF00',
                    stacking: 'percent',
                    index: 2,
//                    yAxis: 1,
                    data: [<?php echo nullSafe($series_str[3]); ?>]
                },
                {
                    name: '<?php echo $chart["series"][0]["title4"] ?>',
                    type: 'column',
                    color: '#FF6600',
                    stacking: 'percent',
                    index: 1,
//                    yAxis: 1,
                    data: [<?php echo nullSafe($series_str[4]); ?>]
                },
                {
                    name: '<?php echo $chart["series"][0]["title5"] ?>',
                    type: 'column',
                    color: '#F00000',
                    stacking: 'percent',
                    index: 0,
//                    yAxis: 1,
                    data: [<?php echo nullSafe($series_str[1]); ?>]
                },
                {
                    name: '<?php echo $chart["series"][0]["title6"] ?>',
                    type: 'scatter',
                    yAxis: 1,
                    data: [<?php echo $series_str[6]; ?>],
                    lineWidth: 0,
                    visible: true,
                    marker: {
                        symbol: 'diamond',
                        enabled: true,
                        radius: 3,
                        fillColor: '#0033FF'
                    },
                    dashStyle: 'shortdot'
                },
                {
                    name: '<?php echo $chart["series"][0]["title7"] ?>',
                    type: 'scatter',
                    lineWidth: 0,
                    visible: true,
                    marker: {
                        symbol: 'circle',
                        enabled: true,
                        fillColor: '#000033',
                        radius: 3
                    },
                    yAxis: 1,
                    data: [<?php echo $series_str[7] ?>],
                }, ]
        });

        var chart = $('#chart-container').highcharts();
        var color = false;
        var stacking = false;
        chart.yAxis[0].setExtremes(0, 100);
        console.log(">>>>>>>>>>>");
        console.log(chart);


        var category = {
            "1KB/s": 1024,
            "32KB/s": 32768,
            "1MB/s": 1048576,
            "32MB/s": 33554432,
            "1GB/s": 1073741824,
            "32GB/s": 34359738368,
            "1TB/s": 1099511627776
        };

        chart.yAxis[1].labels.formatter = function () {

            return categoryLinks[this.value];
        }






// Toggle abs/%
        $('#toggle-percentage').click(function () {

            for (var i = 0; i < 5; i++) {
                chart.series[i].update({
                    stacking: stacking ? "normal" : "percent"
                });
            }

//            chart.yAxis[0].labels.update({
//                format: stacking ? "{value}" : "{value}%"
//            });

            chart.yAxis[0].axisTitle.attr({
                text: stacking ? "Distribution of time (s)" : "Percentage of time (%)"
            });
            if (!stacking) {
                chart.yAxis[0].setExtremes(0, 100);
            } else {
                chart.yAxis[0].setExtremes(null, null);
            }
            stacking = !stacking;
//            chart.series[0].update({
//                color: color ? null : Highcharts.getOptions().colors[1]
//            });
//            color = !color;

        });

    });
</script>
