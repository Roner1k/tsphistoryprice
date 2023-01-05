<?php

?>
<div class="performance-wrap <?php echo strtolower($alert_name); ?>">

    <div class="chart-desc">
        <h4><?php echo $alert_name; ?> Portfolio Performance </h4>
        <p>Click and drag to view performance</p>
    </div>


    <div class="range-buttons print_hide">
        <ul>
            <li><a href="javascript:;" data-date="ytd">YTD</a></li>
            <li><a href="javascript:;" data-date="30">1M</a></li>
            <li><a href="javascript:;" data-date="90">3M</a></li>
            <li><a href="javascript:;" data-date="180">6M</a></li>
            <li><a href="javascript:;" data-date="365">1Y</a></li>
            <li><a href="javascript:;" data-date="1080">3Y</a></li>
            <li><a href="javascript:;" data-date="3650">10Y</a></li>
            <li><a class="active" href="javascript:;" data-date="all">Max</a></li>
        </ul>
        <div class="custom-options">
            <a class="custom-options-button" href="javascript:;">Custom</a>
            <div class="options">

                <label>Start Date<br>
                    <input min="2000-01-01" class="start-date" type="date" placeholder="Select Date">
                </label>

                <label>End Date<br>
                    <input min="2000-01-01" class="end-date" type="date" placeholder="Select Date">
                </label>

                <a class="submit" href="javascript:;">Ok</a>
                <a class="close" href="javascript:;">Close</a>
            </div>
        </div>
    </div>


    <div class="tsp-performance_chart" data-performance-chart="<?php echo strtolower($alert_name); ?>">
        <?php

        echo "<div class='performance-container' id='hc-container-performance-" . strtolower($alert_name) . "'></div>";
        ?>
    </div>
    <div id="aggr-performance-container"></div>
    <div class="tsp-performance_perc">

        <ul class="performance-percentages desktop">
            <li class="perf-p">
                <div class="item">
                    <b>YTD</b>
                    <?php echo $this->tsphp_perf_per($ytd_end, $ytd_start, 0); ?>
                </div>

                <div class="item">
                    <b>1 Year</b>
                    <?php echo $this->tsphp_perf_per($ytd_end, $prev_year_end, 0); ?>
                </div>

            </li>
            <li class="perf-p">
                <div class="item">
                    <b>3 Year</b>
                    <?php echo $this->tsphp_perf_per($ytd_end, $prev_3year_end, 0); ?>
                </div>

                <div class="item">
                    <b>5 Year</b>
                    <?php echo $this->tsphp_perf_per($ytd_end, $prev_5year_end, 0); ?>
                </div>
            </li>
            <li class="perf-p">
                <div class="item">
                    <b>10 Year</b>
                    <?php echo $this->tsphp_perf_per($ytd_end, $prev_10year_end, 0); ?>
                </div>

                <div class="item">
                    <b>Max</b>
                    <?php echo $this->tsphp_perf_per($ytd_end, $prev_max_year_end, 0); ?>
                </div>
            </li>
        </ul>

        <ul class="performance-percentages mobile">
            <li class="perf-p">
                <div class="item">
                    <b>YTD</b>
                    <?php echo $this->tsphp_perf_per($ytd_end, $ytd_start, 0); ?>
                </div>

                <div class="item">
                    <b>1 Year</b>
                    <?php echo $this->tsphp_perf_per($ytd_end, $prev_year_end, 0); ?>
                </div>

                <div class="item">
                    <b>10 Year</b>
                    <?php echo $this->tsphp_perf_per($ytd_end, $prev_10year_end, 0); ?>
                </div>

            </li>
            <li class="perf-p">
                <div class="item">
                    <b>3 Year</b>
                    <?php echo $this->tsphp_perf_per($ytd_end, $prev_3year_end, 0); ?>
                </div>

                <div class="item">
                    <b>5 Year</b>
                    <?php echo $this->tsphp_perf_per($ytd_end, $prev_5year_end, 0); ?>
                </div>

                <div class="item">
                    <b>Max</b>
                    <?php echo $this->tsphp_perf_per($ytd_end, $prev_max_year_end, 0); ?>
                </div>
            </li>
        </ul>

    </div>


    <script>
        //send arr to js
        var $tsp_js_perf_data_<?php echo strtolower($alert_name);?> = <?php echo json_encode($tsphp_graph_data); ?>//;
        //console.log($tsp_js_perf_data_<?php //echo strtolower($alert_name);?>//);
    </script>

</div>







