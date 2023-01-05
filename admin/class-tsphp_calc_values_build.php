<?php

class Tsphp_calc_values_build
{
//    public $tsphp_aggressive_table;
    public $tsphp_aggressive_table_exist;

//    public $tsphp_conservative_table;
    public $tsphp_conservative_alerts_table_exist;


    public function __construct()
    {
        $tsphp_math_table = new Tsphp_math_table();
//        $this->tsphp_aggressive_table = $tsphp_math_table->tsphp_aggressive_table;
        $this->tsphp_aggressive_table_exist = $tsphp_math_table->tsphp_aggressive_table_exist;
        $this->tsphp_conservative_alerts_table_exist = $tsphp_math_table->tsphp_conservative_alerts_table_exist;
//        $this->tsphp_aggressive_alerts_table = $tsphp_math_table->tsphp_aggressive_alerts_table;
//        $this->tsphp_aggressive_alerts_table_exist = $tsphp_math_table->tsphp_aggressive_alerts_table_exist;

    }

    public function tsphp_calculate_values_build($alert_type)
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

            $items_per_page = 200;
            $page = isset($_GET['cpage']) ? abs((int)$_GET['cpage']) : 1;
            $offset = ($page * $items_per_page) - $items_per_page;

            $query = 'SELECT * FROM ' . $alert_type;

            $total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
            $total = $wpdb->get_var($total_query);

            $results = $wpdb->get_results($query . ' ORDER BY trade_date DESC LIMIT ' . $offset . ', ' . $items_per_page, OBJECT);
//            var_dump($results);


            $existing_columns = $wpdb->get_col("DESC {$alert_type}", 0);

//            if (isset($_GET['remove'])) {
//                if ($wpdb->delete($alert_type, array('alert_date' => $_GET['remove']))) {
//                    echo $tsp_service->tsphp_message('Date successfully removed.');
//                }
//            }
            if (isset($_GET['page'])) {
                $active_tab = $_GET['page'];
            }

            //loop
            // $sql_cols = implode(', ', $existing_columns);
            //            $tsphp_prices = $wpdb->get_results('SELECT $sql_cols FROM " . $tsphp_math_table->tsphp_aggressive_table . " ORDER BY dr_ID DESC");
            ?>

            <div class="tsphp_pre_table">
                <div>
                    <div class="tabs nav-tab-wrapper">
                        <a href="admin.php?page=tsp-calc-aggressive"
                           class="nav-tab <?php echo $active_tab == 'tsp-calc-aggressive' ? 'nav-tab-active' : ''; ?>">Aggressive</a></li>
                        <a href="admin.php?page=tsp-calc-conservative"
                           class="nav-tab <?php echo $active_tab == 'tsp-calc-conservative' ? 'nav-tab-active' : ''; ?>">Conservative</a></li>
                    </div>
                </div>

                <div class="pre_table_bottom">
                    <form method="POST">
                        <input type="submit" name="submit" id="tsphp-update-calc-tables" value="Force Calc"
                               class="button button-primary"/>
                        <input type="hidden" name="action" id="tsphp-update-calc-tables"
                               value="tsphp-update-calc-tables">
                    </form>


                    <div class="tsphp_pagi"> <?php echo paginate_links(array(
                            'base' => add_query_arg('cpage', '%#%'),
                            'format' => '',
                            'prev_text' => __('&laquo;'),
                            'next_text' => __('&raquo;'),
                            'total' => ceil($total / $items_per_page),
                            'current' => $page
                        )); ?>
                    </div>
                </div>
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
                <?php foreach ($results as $row) : ?>
                    <tr class="<?php if (strlen($row->alert_date) > 1) echo 'active next-alert'; ?>">
                        <?php foreach ($row as $k => $td) {
                            if (strcasecmp($k, 'dr_ID') == 0) {
                                echo "<td class='$k'>$td</td>";

                            } elseif (strcasecmp($k, 'alert_date') == 0 || strcasecmp($k, 'trade_date') == 0) {
                                echo "<td class='$k'>" . (strlen($td) > 1 ? "<input type='date' value='$td' data-next-alert='$td' disabled>" : "-") . "</td>";

                            } elseif (strcasecmp($k, 'g_f_per') == 0 ||
                                strcasecmp($k, 'f_f_per') == 0 ||
                                strcasecmp($k, 'c_f_per') == 0 ||
                                strcasecmp($k, 's_f_per') == 0 ||
                                strcasecmp($k, 'i_f_per') == 0) {
                                echo "<td class='perc-col $k'>$td<span class='sign'>%</span></td>";

                            } elseif (strcasecmp($k, 'g_f_shr') == 0 ||
                                strcasecmp($k, 'f_f_shr') == 0 ||
                                strcasecmp($k, 'c_f_shr') == 0 ||
                                strcasecmp($k, 's_f_shr') == 0 ||
                                strcasecmp($k, 'i_f_shr') == 0) {
                                echo "<td class='$k'>$td </td>";

                            } else {
                                echo "<td class='$k'><span class='sign'>$</span>" . round($td, 2) . "</td>";
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
            <div class="pre_table_bottom">
                <div></div>
                <div class="tsphp_pagi">
                    <?php echo paginate_links(array(
                        'base' => add_query_arg('cpage', '%#%'),
                        'format' => '',
                        'prev_text' => __('&laquo;'),
                        'next_text' => __('&raquo;'),
                        'total' => ceil($total / $items_per_page),
                        'current' => $page
                    )); ?>
                </div>
            </div>


        <?php } ?>

        <?php

    }

}