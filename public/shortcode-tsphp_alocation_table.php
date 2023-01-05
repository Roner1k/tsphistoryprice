<?php
//$to_tjs = ' vrara';

$newData = $alocation_table_values[0];
$oldData = $alocation_table_values[1];


?>
<div class="allocations-wrap pie-wrap <?php echo strtolower($alert_name); ?>">

    <div class="heading">
        <h4><?php echo $alert_name; ?> Portfolio </h4>
        <div class="date"> <?php
            echo date_format(date_create($newData->alert_date), 'F dS, Y'); ?> Update
        </div>
    </div>

    <div class="buttons-row">
        <a class="print2pdf" href="javascript:;">Print</a>
        <a class="history" href="<?php echo $hist_url; ?>">Historical</a>
    </div>


    <div class="allocations-row">

        <div class="allocation-chart hchart-pie-<?php echo $alert_name; ?> highcharts-figure"
             data-pie-chart="<?php echo strtolower($alert_name); ?>">

            <div id="al-pie-container-<?php echo strtolower($alert_name); ?>"></div>
            <div id="al-pie-data-<?php echo strtolower($alert_name); ?>"
                 style="display: none;"><?php echo json_encode($alocation_table_values); ?></div>
            <div class="sh-data">
                <?php


                ?>

            </div>
        </div>
        <div class="allocation-info">

            <?php

            $g_new = $newData->g_f_per;
            $f_new = $newData->f_f_per;
            $c_new = $newData->c_f_per;
            $s_new = $newData->s_f_per;
            $i_new = $newData->i_f_per;

            $g_old = $oldData->g_f_per;
            $f_old = $oldData->f_f_per;
            $c_old = $oldData->c_f_per;
            $s_old = $oldData->s_f_per;
            $i_old = $oldData->i_f_per;

            $g_status = '<span class="hold">Holding</span>';
            $f_status = '<span class="hold">Holding</span>';
            $c_status = '<span class="hold">Holding</span>';
            $s_status = '<span class="hold">Holding</span>';
            $i_status = '<span class="hold">Holding</span>';

            if (($g_new != $g_old) && (($g_new - $g_old) > 0)) {
                $g_status = '<span class="up"> ' . ($g_new - $g_old) . '% <span>increase</span></span>';
            } else if (($g_new != $g_old) && (($g_new - $g_old) < 0)) {
                $g_status = '<span class="down"> ' . abs($g_new - $g_old) . '% <span>decrease</span></span>';
            }

            if (($f_new != $f_old) && (($f_new - $f_old) > 0)) {
                $f_status = '<span class="up"> ' . ($f_new - $f_old) . '% <span>increase</span></span>';
            } else if (($f_new != $f_old) && (($f_new - $f_old) < 0)) {
                $f_status = '<span class="down"> ' . abs($f_new - $f_old) . '% <span>decrease</span></span>';
            }

            if (($c_new != $c_old) && (($c_new - $c_old) > 0)) {
                $c_status = '<span class="up"> ' . ($c_new - $c_old) . '% <span>increase</span></span>';
            } else if (($c_new != $c_old) && (($c_new - $c_old) < 0)) {
                $c_status = '<span class="down"> ' . abs($c_new - $c_old) . '% <span>decrease</span></span>';
            }

            if (($s_new != $s_old) && (($s_new - $s_old) > 0)) {
                $s_status = '<span class="up"> ' . ($s_new - $s_old) . '% <span>increase</span></span>';
            } else if (($s_new != $s_old) && (($s_new - $s_old) < 0)) {
                $s_status = '<span class="down"> ' . abs($s_new - $s_old) . '% <span>decrease</span></span>';
            }

            if (($i_new != $i_old) && (($i_new - $i_old) > 0)) {
                $i_status = '<span class="up"> ' . ($i_new - $i_old) . '% <span>increase</span></span>';
            } else if (($i_new != $i_old) && (($i_new - $i_old) < 0)) {
                $i_status = '<span class="down"> ' . abs($i_new - $i_old) . '% <span>decrease</span></span>';
            }
            ?>


            <table>
                <tbody>
                <tr>

                    <td><span class='color'
                              style='background-color:#001560; width: 20px;height: 20px;display: inline-block;'></span><span>G fund</span>
                    </td>
                    <td><span>Government securities</span></td>
                    <td><span><?php echo $alocation_table_values[0]->g_f_per; ?>%</span></td>
                    <td class="status"><?php echo $g_status; ?></td>

                </tr>

                <tr>

                    <td><span class='color'
                              style='background-color:#1227E2; width: 20px;height: 20px;display: inline-block;'></span><span>F fund</span>
                    </td>
                    <td><span>Fixed income index</span></td>
                    <td><span><?php echo $alocation_table_values[0]->f_f_per; ?>%</span></td>
                    <td class="status"><?php echo $f_status; ?></td>

                </tr>

                <tr>

                    <td><span class='color'
                              style='background-color:#FDDA02; width: 20px;height: 20px;display: inline-block;'></span><span>C fund</span>
                    </td>
                    <td><span>Common stock index</span></td>
                    <td><span><?php echo $alocation_table_values[0]->c_f_per; ?>%</span></td>
                    <td class="status"><?php echo $c_status; ?></td>

                </tr>
                <tr>

                    <td><span class='color'
                              style='background-color:#FF9900; width: 20px;height: 20px;display: inline-block;'></span><span>S fund</span>
                    </td>
                    <td><span>Small cap stock index</span></td>
                    <td><span><?php echo $alocation_table_values[0]->s_f_per; ?>%</span></td>
                    <td class="status"><?php echo $s_status; ?></td>

                </tr>
                <tr>
                    <td><span class='color'
                              style='background-color:#EF6400; width: 20px;height: 20px;display: inline-block;'></span><span>I fund</span>
                    </td>
                    <td><span>International stock index</span></td>
                    <td><span><?php echo $alocation_table_values[0]->i_f_per; ?>%</span></td>
                    <td class="status"><?php echo $i_status; ?></td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>
    <?php


    if (strlen($newData->alert_reason) > 1) { ?>
        <div class="reason-row">
            <div class="heading">
                <h4>Our Reasoning</h4>
                <a href="<?php echo $newData->alert_post_link; ?>">Read full Market Commentary</a>
            </div>

            <p><?php echo $newData->alert_reason; ?></p>
        </div>
        <?php
    } ?>
</div>
<script>
    //var $alocation_table_name = <?php //echo json_encode(strtolower($alert_name)); ?>//;
    //var $alocation_table_values_js = <?php //echo json_encode($alocation_table_values); ?>//;
</script>








