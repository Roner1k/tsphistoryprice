<?php

class Tsphp_alert_dates_build
{
    public $tsphp_aggressive_table;
    public $tsphp_aggressive_table_exist;

    public $tsphp_aggressive_alerts_table;
    public $tsphp_conservative_alerts_table_exist;

    public function __construct()
    {
        $tsphp_math_table = new Tsphp_math_table();
        $this->tsphp_aggressive_table = $tsphp_math_table->tsphp_aggressive_table;
        $this->tsphp_aggressive_table_exist = $tsphp_math_table->tsphp_aggressive_table_exist;
        $this->tsphp_aggressive_alerts_table = $tsphp_math_table->tsphp_aggressive_alerts_table;
        $this->tsphp_conservative_alerts_table_exist = $tsphp_math_table->tsphp_conservative_alerts_table_exist;

    }

    public function tsphp_alert_dates_buid($alert_type)
    {
        $alert_name = '';
        if (strcmp($alert_type, 'tsp_aggressive_alerts_table') == 0) {
            $alert_name = 'Aggressive';
        } elseif (strcmp($alert_type, 'tsp_conservative_alerts_table') == 0) {
            $alert_name = 'Conservative';

        }

        $tsphp_imported_table = new Tsphistoryprice();

        //imported values exists
        if (!$tsphp_imported_table->tsphp_price_table_exist) {
            echo 'Imported values not exists.  <a href="admin.php?page=tsp-import-options">Import First</a>';

            //create table if not exists
        } elseif (!$this->tsphp_aggressive_table_exist || !$this->tsphp_conservative_alerts_table_exist) {
            $tsphp_create_tables = new Tsphp_math_table();
            $tsphp_create_tables->tsphp_create_aggr_conservative_tables();
            echo '<h3>Setup Tables</h3><br><button type="submit"  onClick="window.location.reload()">Create Table</button>';


            //table content
        } else {
            // $manage_build = new Tsphp_import_build();
            $tsp_service = new Tsphp_wrapper_admin();

            global $wpdb;

            $items_per_page = 50;
            $page = isset($_GET['cpage']) ? abs((int)$_GET['cpage']) : 1;
            $offset = ($page * $items_per_page) - $items_per_page;

            $query = 'SELECT * FROM ' . $alert_type;

            $total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
            $total = $wpdb->get_var($total_query);

            $results = $wpdb->get_results($query . ' ORDER BY alert_date DESC LIMIT ' . $offset . ', ' . $items_per_page, OBJECT);
//            var_dump($results);


            $existing_columns = $wpdb->get_col("DESC {$alert_type}", 0);

            if (isset($_GET['remove'])) {
                if ($wpdb->delete($alert_type, array('alert_date' => $_GET['remove']))) {
                    echo $tsp_service->tsphp_message('Date successfully removed.');
                    //recalc after del
                    $math_class = new Tsphp_math_table();
                    $math_class->tsphp_calc_math_run();
                    //reload page after remove
                    echo '<script type="text/JavaScript"> location.reload(); </script>';

                }
            }
            if (isset($_GET['page'])) {
                $active_tab = $_GET['page'];
            } // end if

            //loop
            $sql_cols = implode(', ', $existing_columns);

            //            $tsphp_prices = $wpdb->get_results('SELECT $sql_cols FROM " . $tsphp_math_table->tsphp_aggressive_table . " ORDER BY dr_ID DESC");
            ?>

            <div class="pre_table">
                <div>
                    <div class="tabs nav-tab-wrapper">
                        <a href="admin.php?page=tsp-alert-dates-aggressive"
                           class="nav-tab <?php echo $active_tab == 'tsp-alert-dates-aggressive' ? 'nav-tab-active' : ''; ?>">Aggressive</a></li>
                        <a href="admin.php?page=tsp-alert-dates-conservative"
                           class="nav-tab <?php echo $active_tab == 'tsp-alert-dates-conservative' ? 'nav-tab-active' : ''; ?>">Conservative</a></li>
                    </div>
                </div>

                <br>
                <div>

                    <a class="button" type="button"
                       href="admin.php?page=tsp-add-<?php echo strtolower($alert_name); ?>-alert-date">Add
                        New <?php echo $alert_name; ?> Alert</a>
                </div>
                <div class="pagi"> <?php echo paginate_links(array(
                        'base' => add_query_arg('cpage', '%#%'),
                        'format' => '',
                        'prev_text' => __('&laquo;'),
                        'next_text' => __('&raquo;'),
                        'total' => ceil($total / $items_per_page),
                        'current' => $page
                    )); ?></div>
            </div>

            <table class="tsphs_table widefat posts">
                <thead>
                <tr>
                    <?php foreach ($existing_columns as $col) {
                        echo "<th>$col</th>";
                    } ?>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($results as $row) :
//                    $editable = strlen($row->alert_date) > 1;
                    ?>

                    <tr>

                        <?php foreach ($row as $k => $td) {


                            if (strcasecmp($k, 'alert_date') == 0) {
                                echo "<td class='$k'><input type='date' value='$td' disabled><a  class='row_edit' href='admin.php?page=tsp-update-alert-date-" . strtolower($alert_name) . "&amp;alert_date=$row->alert_date'><svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24'><path d='M7.127 22.562l-7.127 1.438 1.438-7.128 5.689 5.69zm1.414-1.414l11.228-11.225-5.69-5.692-11.227 11.227 5.689 5.69zm9.768-21.148l-2.816 2.817 5.691 5.691 2.816-2.819-5.691-5.689z'/></svg>edit</a> <a href='admin.php?page=tsp-alert-dates-" . strtolower($alert_name) . "&amp;remove=$row->alert_date' onclick='" . 'return confirm("Are you sure you want to remove this Date?")' . "'>[x]</a></td>";

                            } elseif (strcasecmp($k, 'trade_date') == 0) {
                                echo "<td class='$k'><input type='date' value='$td' disabled></td>";
                            } elseif (strcasecmp($k, 'g_f_per') == 0 ||
                                strcasecmp($k, 'f_f_per') == 0 ||
                                strcasecmp($k, 'c_f_per') == 0 ||
                                strcasecmp($k, 's_f_per') == 0 ||
                                strcasecmp($k, 'i_f_per') == 0) {
                                echo "<td class='perc-col $k'>$td<span>%</span></td>";

                            } else {
                                echo "<td class='$k'>$td</td>";
                            }
                        } ?>

                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <?php
                    foreach ($existing_columns as $col) {
                        echo "<th>$col</th>";
                    } ?>
                </tr>
                </tfoot>
            </table>


            <?php echo paginate_links(array(
                'base' => add_query_arg('cpage', '%#%'),
                'format' => '',
                'prev_text' => __('&laquo;'),
                'next_text' => __('&raquo;'),
                'total' => ceil($total / $items_per_page),
                'current' => $page
            ));


        } ?>

        <!--        </div>-->
        <?php

    }

}