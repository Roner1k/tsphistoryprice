<?php
?>

<div class="tsphp-past-allocations" id="tsphp-past-allocations-<?php echo strtolower($alert_name); ?>">
    <!--    <h4 class="print_hide">--><?php //echo $alert_name; ?><!-- past allocations</h4>-->
    <div class="past-allocation_graph" data-alloc-graph="<?php echo strtolower($alert_name); ?>">
        <div class="heading">
            <h4>Graph view</h4>
            <ul class="legend">
                <li><span class="color" style="background-color: #1227E2"></span> <span>G Fund</span></li>
                <li><span class="color" style="background-color: #001561"></span> <span>F Fund</span></li>
                <li><span class="color" style="background-color: #FDDA02"></span> <span>C Fund</span></li>
                <li><span class="color" style="background-color: #FF9900"></span> <span>S Fund</span></li>
                <li><span class="color" style="background-color: #EF6400"></span> <span>I Fund</span></li>
            </ul>
        </div>

        <?php
        echo "<div id='hc-data-past-alloc-" . strtolower($alert_name) . "'style='display: none;'>" . json_encode($all_graph_data) . "</div>";
        echo "<div id='hc-container-past-alloc-" . strtolower($alert_name) . "'></div>";
        ?>
    </div>
    <div class="past-allocation_table">
        <div class="heading">
            <h4>Table view</h4>
            <div class="button-rows print_hide">
                <a class="getPDF" href="javascript:;" title="Download PDF File">Download</a>
            </div>
        </div>
        <div class="table p-allocations-<?php echo strtolower($alert_name); ?>">
            <table>
                <thead>
                <tr class="table_head">
                    <td colspan="5">Alert date</td>
                    <td colspan="2">G</td>
                    <td colspan="2">F</td>
                    <td colspan="2">C</td>
                    <td colspan="2">S</td>
                    <td colspan="2">I</td>
                    <td colspan="3">Performance</td>

                    <td colspan="1"></td>
                </tr>
                </thead>
                <?php
                /*loop*/

                /*
                * required  for toggle tabs:
                *  attr with val `data-alert_date_trg`,
                *  class for main shortcode wrap `past-allocation_table`,
                *  id for toggled element `id='tab_$alert_utc`
                *  */


                foreach ($alloc_results as $alc) {
                    $alert_exist_full = ($alc->alert_reason && $alc->alert_post_link) ? '' : 'disabled';

                    echo "<tr class='alert_main'>";
                    foreach ($alc as $k => $td) :
                        if ($k == 'trade_date' || $k == 'alert_reason' || $k == 'alert_post_link' || $k == 'total_val') continue;

                        $alert_utc = strtotime($alc->alert_date);
                        if ($k !== 'alert_date') {
                            echo "<td colspan='2'>$td%</td>";
                        } elseif ($k == 'alert_date') {
                            echo "<td colspan='5' class='date'>" . date_format(date_create($td), 'M dS, Y') . "</td>";
                        } else {
                            echo "<td colspan='2' class=''>$td</td>";

                        }
                    endforeach;
                    echo "<td class='perf-td' colspan='3'>$alc->total_val</td>";


                    echo "<td class='button " . ((strlen($alc->alert_post_link) > 2 || strlen($alc->alert_reason) > 2) ? "" : "button-disable") . "' colspan='2' data-alert_date_trg='tab_$alert_utc' style='cursor: pointer;'><input type='button'  value= '&lt;' style='pointer-events: none;' /></td>
                        </tr>                   
                        <tr class='alert_main_reason' id='tab_$alert_utc' style='display:none !important;'>
                        <td colspan='20'> 
                            <div class='heading'>"
                        . ((strlen($alc->alert_reason) > 2) ? "<h4>Our Reasoning</h4>" : "<h4></h4>") .
                        (($alc->alert_post_link && strlen($alc->alert_post_link) > 2) ? "<a href='$alc->alert_post_link'>Read full Market Commentary</a>" : "") .
                        "</div>
                            <div class='reason'>
                            " . ((strlen($alc->alert_reason) > 2) ? "$alc->alert_reason" : "") . "                           
                            </div>
                        </td>
                        </tr>";

                    //   echo "
                    //     <div class='alert_main_reason' id='tab_$alert_utc'  style='display: none;'>
                    //         <b>Our reasoning</b><br>$alc->alert_reason
                    //         <a href='$alc->alert_post_link'>Read Full</a>
                    //     </div>";

                    echo "</tr>";


                } ?>
            </table>
        </div>

        <div class="pagination print_hide">
            <?php
            echo paginate_links(array(
                'base' => add_query_arg($cpage_alert_type, '%#%'),
                'format' => '',
                'prev_text' => __('&laquo;'),
                'next_text' => __('&raquo;'),
                'total' => ceil($total / $alloc_items_per_page),
                'current' => $alloc_page
            )); ?>
        </div>
    </div>
</div>
<script>
    //var past_allocation_data_<?php //echo strtolower($alert_name); ?>// = <?php // echo json_encode($fund_data); ?>//;
    // console.log($tsphp_gdata_js);
</script>







