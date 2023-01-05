<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://tsppilot.sitepreview.app/
 * @since      1.0.0
 *
 * @package    Tsphistoryprice
 * @subpackage Tsphistoryprice/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tsphistoryprice
 * @subpackage Tsphistoryprice/admin
 * @author     Next Level <alex@webzz.pro>
 */
class Tsphistoryprice_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;


    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public $tsphp_aggressive_table;
    public $tsphp_conservative_table;

    public $tsphp_tmp_aggressive_calculation_table;
    public $tsphp_tmp_conservative_calculation_table;

    public $tsphp_aggressive_alerts_table;
    public $tsphp_conservative_alerts_table;


    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->plugin_permalink = 'tsp-price-history';
        $this->tsphp_load_admin_dep();

        $math_class = new Tsphp_math_table();

        $this->tsphp_aggressive_table = $math_class->tsphp_aggressive_table;
        $this->tsphp_conservative_table = $math_class->tsphp_conservative_table;

        $this->tsphp_tmp_aggressive_calculation_table = $math_class->tsphp_tmp_aggressive_calculation_table;
        $this->tsphp_tmp_conservative_calculation_table = $math_class->tsphp_tmp_conservative_calculation_table;

        $this->tsphp_aggressive_alerts_table = $math_class->tsphp_aggressive_alerts_table;
        $this->tsphp_conservative_alerts_table = $math_class->tsphp_conservative_alerts_table;

//        $this->tsphp_gen_calculation_table = $math_class->tsphp_gen_calculation_table;

        add_action("admin_menu", array($this, "tsphp_options_page"));

        add_action('wp_ajax_tsphp_trade_date_check', array($this, 'tsphp_trade_date_check'));
        add_action('wp_ajax_tsphp_trade_date_check', array($this, 'tsphp_trade_date_check'));

//        add_action('wp_ajax_tsphp_update_period_data', array($this, 'tsphp_update_period_data'));
//        add_action('wp_ajax_tsphp_update_period_data', array($this, 'tsphp_update_period_data'));

    }

    function tsphp_trade_date_check()
    {
        global $wpdb;

        $table_name = new Tsphp_import_build();
        $table_name = $table_name->tsphp_price_table;
        $input_date = $_POST['dateValue'];

        $imported_dates = $wpdb->get_results("SELECT date FROM $table_name");

        $res = array('exist' => 0, 'msg' => 'The selected date is not found in the <a href="/wp-admin/admin.php?page=tsp-price-history" target="_blank">imported Trade dates</a>',);

        foreach ($imported_dates as $d) {

            if ($d->date == $input_date) {
                $res['exist'] = 1;
                $res['msg'] = "ok. Selected trade date: $d->date ";
            }

        }

//        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
//        dbDelta($upd_SQL);

//        print json_encode($date_exist);
        print json_encode($res);
        exit();
    }
//
//    function tsphp_update_period_data()
//    {
//        $table_name = new Tsphp_math_table();
//        $table_name = $table_name->tsphp_aggressive_table;
//
//        $input_ID = $_POST['inputID'];
//        $input_val = $_POST['inputVal'];
//        $input_slug = $_POST['inputSlug'];
//        $upd_SQL = "UPDATE $table_name set $input_slug = '$input_val' WHERE dr_ID = $input_ID";
//        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
//        dbDelta($upd_SQL);
//        echo $upd_SQL;
//        exit();
//    }

//options
    public function tsphp_options_page()
    {
        add_menu_page(
            'Price History',
            'TSP Price History',
            'manage_options',
            $this->plugin_permalink,
            // 'import-options',
            array($this, 'render'),
            plugin_dir_url(__FILE__) . 'img/tsp-history-1.png'
        );
        add_submenu_page(
            $this->plugin_permalink,
            'Alert dates Aggressive',
            'Alert dates',
            'manage_options',
            "tsp-alert-dates-aggressive",
            array($this, 'render_alerts_aggr_table'),

        );
        add_submenu_page(
            null,
            'Alert dates Conservative',
            null,
            'manage_options',
            "tsp-alert-dates-conservative",
            array($this, 'render_alerts_cons_table'),
        );

        add_submenu_page(
            $this->plugin_permalink,
            "Calculations Aggressive",
            "Calculations",
            "manage_options",
            "tsp-calc-aggressive",
            array($this, 'render_calc_agg_table')
        );
        add_submenu_page(
            null,
            "Calculations Conservative",
            null,
            "manage_options",
            "tsp-calc-conservative",
            array($this, 'render_calc_cons_table')
        );
        add_submenu_page(
            $this->plugin_permalink,
            "Options",
            "Options",
            "manage_options",
            "tsp-import-options",
            array($this, 'render_import_page_results')
        );
        add_submenu_page(
            null,
            'Add Aggressive Alert Date',
            null,
            'manage_options',
            'tsp-add-aggressive-alert-date',
            array($this, 'tsphp_add_aggr_alert_date')
        );
        add_submenu_page(
            null,
            'Add Conservative Alert Date',
            null,
            'manage_options',
            'tsp-add-conservative-alert-date',
            array($this, 'tsphp_add_cons_alert_date')
        );
        add_submenu_page(
            null,
            'Update Aggressive Alert Date',
            null,
            'manage_options',
            'tsp-update-alert-date-aggressive',
            array($this, 'tsphp_update_aggr_alert_date')
        );
        add_submenu_page(
            null,
            'Update Conservative Alert Date',
            null,
            'manage_options',
            'tsp-update-alert-date-conservative',
            array($this, 'tsphp_update_cons_alert_date')
        );

    }

    //add  func
    public function tsphp_add_alert_date($alert_type_name)
    {
        $alert_name = '';
        if (strcmp($alert_type_name, 'tsp_aggressive_alerts_table') == 0) {
            $alert_name = 'Aggressive';
        } elseif (strcmp($alert_type_name, 'tsp_conservative_alerts_table') == 0) {
            $alert_name = 'Conservative';

        }

        global $wpdb;
        $tsphp_wrap = new Tsphp_wrapper_admin();

        echo $tsphp_wrap->tsphp_wrap('header', 'Add new Alert Date');

//        if (!isset($_GET['alert_date'])) wp_die('No alert dates');

        // process form
        if (isset($_POST['tsp-new-alert-date'])) :

            unset($_POST['tsp-new-alert-date']);

            if ($wpdb->insert($alert_type_name, $_POST)) {
                echo $tsphp_wrap->tsphp_message('Date successfully added. Go to <a href="admin.php?page=tsp-alert-dates-' . strtolower($alert_name) . '">' . $alert_name . ' Date Overview</a>');
                //recalc after adding new
                $math_class = new Tsphp_math_table();
                $math_class->tsphp_calc_math_run();
            } else {
                echo $tsphp_wrap->tsphp_message('SQL Error ');
            }

        endif;
        // form processing ends


        include_once 'partials/tsphistoryprice-admin-add-alert-date.php';

        echo $tsphp_wrap->tsphp_wrap();


    }

    public function tsphp_add_aggr_alert_date()
    {
        $new_type = new Tsphp_math_table();
        $this->tsphp_add_alert_date($new_type->tsphp_aggressive_alerts_table);
    }

    public function tsphp_add_cons_alert_date()
    {
        $new_type = new Tsphp_math_table();
        $this->tsphp_add_alert_date($new_type->tsphp_conservative_alerts_table);
    }

    //update func
    public function tsphp_update_alert_date($alert_type_name)
    {
        $alert_type_name;

        $alert_name = '';
        if (strcmp($alert_type_name, 'tsp_aggressive_alerts_table') == 0) {
            $alert_name = 'Aggressive';
        } elseif (strcmp($alert_type_name, 'tsp_conservative_alerts_table') == 0) {
            $alert_name = 'Conservative';
        }

        global $wpdb;
        $tsphp_wrap = new Tsphp_wrapper_admin();

        echo $tsphp_wrap->tsphp_wrap('header', 'Update Alert Date');

        if (!isset($_GET['alert_date'])) wp_die('No date #ID');

        // process form
        if (isset($_POST['update-current-alert-date'])) :

            unset($_POST['update-current-alert-date']);

            if ($wpdb->update($alert_type_name, $_POST, array('alert_date' => $_GET['alert_date']))) {
                echo $tsphp_wrap->tsphp_message('Date successfully updated. Go to <a href="admin.php?page=tsp-alert-dates-' . strtolower($alert_name) . '">' . $alert_name . ' Date Overview</a>');
                //recalc after upd
                $math_obj = new Tsphp_math_table();
                $math_obj->tsphp_calc_math_run();
            } else {
                echo $tsphp_wrap->tsphp_message('SQL Error ');
            }
        endif;
        // form processing ends

        $cur_alert_date = $_GET['alert_date'];

        $date_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $alert_type_name . " WHERE  alert_date = %s", $_GET['alert_date']));

        if (!$date_row) wp_die('No date with this #ID');

        include_once 'partials/tsphistoryprice-admin-update-alert-date.php';

        echo $tsphp_wrap->tsphp_wrap();
    }

    public function tsphp_update_aggr_alert_date()
    {
        $new_type = new Tsphp_math_table();
        $this->tsphp_update_alert_date($new_type->tsphp_aggressive_alerts_table);
    }

    public function tsphp_update_cons_alert_date()
    {
        $new_type = new Tsphp_math_table();
        $this->tsphp_update_alert_date($new_type->tsphp_conservative_alerts_table);
    }


    // import overview table
    function tsphp_display_imported_data()
    {
        global $wpdb;
        $tsphp_wrap = new Tsphp_wrapper_admin();
        $import_class = new Tsphp_import_build();

        echo $tsphp_wrap->tsphp_wrap('header', 'Imported Price History');

        $table_name = $import_class->tsphp_price_table;
        $the_query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table_name));

        if ($wpdb->get_var($the_query) == $table_name) {

            $existing_columns = $wpdb->get_col("DESC {$import_class->tsphp_price_table}", 0);

            $sql_cols = implode(', ', $existing_columns);

            $items_per_page = 100;
            $page = isset($_GET['cpage']) ? abs((int)$_GET['cpage']) : 1;
            $offset = ($page * $items_per_page) - $items_per_page;

            $query = "SELECT $sql_cols FROM $import_class->tsphp_price_table";

            $total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
            $total = $wpdb->get_var($total_query);

            $tsphp_prices = $wpdb->get_results($query . ' ORDER BY date DESC LIMIT ' . $offset . ', ' . $items_per_page, OBJECT);

            ?>
            <h3>Import Results</h3>

            <div class="pre_table" style="display: flex;justify-content: flex-end;">
                <div class="pagi"> <?php echo paginate_links(array(
                        'base' => add_query_arg('cpage', '%#%'),
                        'format' => '',
                        'prev_text' => __('&laquo;'),
                        'next_text' => __('&raquo;'),
                        'total' => ceil($total / $items_per_page),
                        'current' => $page
                    )); ?>
                </div>
            </div>
            <table class="widefat posts">

                <thead>
                <tr>
                    <?php
                    foreach ($existing_columns as $col) { ?>
                        <th><?php echo $col; ?></th>
                    <?php } ?>

                </tr>
                </thead>

                <tbody>
                <?php foreach ($tsphp_prices as $row) { ?>
                    <tr>
                        <?php
                        //                        var_dump($kk);
                        //                        var_dump($row);
                        foreach ($row as $k => $td) {
//                            echo $k[0];
                            echo "<td>${td}</td>";
                        } ?>


                    </tr>
                <?php }; ?>
                </tbody>
                <tfoot>
                <tr>
                    <?php
                    foreach ($existing_columns as $col) { ?>
                        <th><?php echo $col; ?></th>
                    <?php } ?>
                </tr>
                </tfoot>
            </table>
            <div class="pre_table" style="display: flex;justify-content: flex-end;">
                <div class="pagi"> <?php echo paginate_links(array(
                        'base' => add_query_arg('cpage', '%#%'),
                        'format' => '',
                        'prev_text' => __('&laquo;'),
                        'next_text' => __('&raquo;'),
                        'total' => ceil($total / $items_per_page),
                        'current' => $page
                    )); ?>
                </div>
            </div>


            <?php
        } else {
            echo $tsphp_wrap->tsphp_message('No prices. Start First Import');
            ?>
            <div class="f-import">
                <h2>First import</h2>
                <form method="post" action="" novalidate="novalidate">
                    <fieldset style="display: flex; flex-flow: row wrap; align-items: center">

                        <div style="margin-right: 1rem;">
                            <label for="">Start Date:</label>
                            <input type="date" id="start-date" name="start-date" min="2006-05-30"
                                   value="2006-05-30<?php // echo date('Y-m-d', strtotime(' - 1 months')); ?>" disabled>
                        </div>
                        <div>
                            <label for="">End Date:</label>
                            <input type="date" id="end-date" name="end-date" min="2006-05-30"
                                   value="<?php echo date('Y-m-d'); ?>" disabled>
                        </div>
                    </fieldset>

                    <div class="submit">
                        <input type="submit" name="submit" id="submit" class="button" value="Import Now">
                        <input type="hidden" name="action" id="submit" value="tsphp-import">
                    </div>
                </form>
            </div>
            <?php
        }

        echo $tsphp_wrap->tsphp_wrap();

    }

    // display log

    function tsphp_display_imp_options()
    {
        ?>
        <h3>Shortcodes</h3>
        <table border="0">
            <tbody>
            <tr>
                <td>Allocation Aggressive: </td>
                <td><input type="text" value="[tsphp_allocation_aggressive historical_url='/url_example/']" readonly="readonly" style="width:500px;">
                </td>
            </tr>
            <tr>
                <td>Allocation Conservative:</td>
                <td><input type="text" value="[tsphp_allocation_conservative historical_url='/url_example/']" readonly="readonly" style="width:500px;">
                </td>
            </tr>
            <tr>
                <td>Performance Aggressive Chart:</td>
                <td><input type="text" value="[tsphp_performance_aggressive_chart]" readonly="readonly"
                           style="width:500px;">
                </td>
            </tr>
            <tr>
                <td>Performance Conservative Chart:</td>
                <td><input type="text" value="[tsphp_performance_conservative_chart]" readonly="readonly"
                           style="width:500px;">
                </td>
            </tr>
            <tr>
                <td>Past Aggressive Allocations:</td>
                <td><input type="text" value="[tsphp_past_aggressive_allocations]" readonly="readonly"
                           style="width:500px;">
                </td>
            </tr>
            <tr>
                <td>Past Conservative Allocations:</td>
                <td><input type="text" value="[tsphp_past_conservative_allocations]" readonly="readonly"
                           style="width:500px;">
                </td>
            </tr>
            <tr>
                <td>Performance Tiles:</td>
                <td><input type="text" value="[tsphp_performance_tiles]" readonly="readonly" style="width:500px;">
                </td>
            </tr>
            <tr>
                <td>Aggressive Heading: ("title" for custom text)</td>
                <td><input type="text" value='[tsphp_aggressive_heading title=""]' readonly="readonly" style="width:500px;">
                </td>
            </tr>
            <tr>
                <td>Conservative Heading: ("title" for custom text)</td>
                <td><input type="text" value='[tsphp_conservative_heading title=""]' readonly="readonly" style="width:500px;">
                </td>
            </tr>

            </tbody>
        </table>

        <?php
    }

    function tsphp_display_log()
    {
        global $wpdb;
        $tsphp_wrap = new Tsphp_wrapper_admin();

        $log_class = new Tsphp_log_build;
        $table_name = $log_class->tsphp_import_log_tname;

        echo $tsphp_wrap->tsphp_wrap('header', 'TSP Price Options');

        $this->tsphp_display_imp_options();

        $the_query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table_name));
        ?>
        <div class="n-import" style="margin: 2rem 0 1rem;">
            <h3>Update Price history</h3>
            <form method="post" action="" novalidate="novalidate">
                <div class="submit">
                    <input type="submit" name="submit" id="tsphp-new-import" class="button" value="Import now">
                    <input type="hidden" name="action" id="tsphp-new-import" value="tsphp-new-import">
                </div>
            </form>
        </div>
        <?php

        if ($wpdb->get_var($the_query) == $table_name) {

            $existing_columns = $wpdb->get_col("DESC {$table_name}", 0);

            $sql_cols = implode(', ', $existing_columns);

            $items_per_page = 20;
            $page = isset($_GET['logpage']) ? abs((int)$_GET['logpage']) : 1;
            $offset = ($page * $items_per_page) - $items_per_page;

            $query = "SELECT $sql_cols FROM $table_name";

            $total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
            $total = $wpdb->get_var($total_query);

            $tsphp_prices = $wpdb->get_results($query . ' ORDER BY ID DESC LIMIT ' . $offset . ', ' . $items_per_page, OBJECT);

            ?>

            <h3>Import Log</h3>

            <table class="widefat posts">
                <thead>
                <tr>
                    <?php
                    foreach ($existing_columns as $col) { ?>
                        <th><?php echo $col; ?></th>
                    <?php } ?>


                </tr>
                </thead>
                <tbody>
                <?php foreach ($tsphp_prices as $row) { ?>

                    <tr>
                        <?php
                        //                        var_dump($kk);
                        //                        var_dump($row);

                        foreach ($row as $k => $td) {
//                            echo $k[0];
                            echo "<td>${td}</td>";
                        } ?>


                    </tr>
                <?php }; ?>
                </tbody>
            </table>

            <div class="pre_table" style="display: flex;justify-content: flex-end;">
                <div class="pagi"> <?php echo paginate_links(array(
                        'base' => add_query_arg('logpage', '%#%'),
                        'format' => '',
                        'prev_text' => __('&laquo;'),
                        'next_text' => __('&raquo;'),
                        'total' => ceil($total / $items_per_page),
                        'current' => $page
                    )); ?>
                </div>
            </div>


            <?php
        } else {
            echo $tsphp_wrap->tsphp_message('No data');

        }
        echo $tsphp_wrap->tsphp_wrap();

    }

    //render
    public function render()
    {
        require plugin_dir_path(dirname(__FILE__)) . 'admin/partials/tsphistoryprice-admin-display.php';
    }

    public function render_import_page_results()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/tsphistoryprice-admin-import-options-display.php';
    }

    public function render_calc_agg_table()
    {
        $calc_gen_type = $this->tsphp_tmp_aggressive_calculation_table;
        require plugin_dir_path(dirname(__FILE__)) . 'admin/partials/tsphistoryprice-admin-calc-table-display.php';
    }

    public function render_calc_cons_table()
    {
        $calc_gen_type = $this->tsphp_tmp_conservative_calculation_table;
        require plugin_dir_path(dirname(__FILE__)) . 'admin/partials/tsphistoryprice-admin-calc-table-display.php';
    }

    public function render_alerts_aggr_table()
    {
        $alert_gen_type = $this->tsphp_aggressive_alerts_table;
        require plugin_dir_path(dirname(__FILE__)) . 'admin/partials/tsphistoryprice-admin-alerts-table-display.php';
    }

    public function render_alerts_cons_table()
    {
        $alert_gen_type = $this->tsphp_conservative_alerts_table;
        require plugin_dir_path(dirname(__FILE__)) . 'admin/partials/tsphistoryprice-admin-alerts-table-display.php';
    }

    public function tsphp_load_admin_dep()
    {
        require_once plugin_dir_path(__FILE__) . '/class-tsphp_alert_dates_build.php';
        require_once plugin_dir_path(__FILE__) . '/class-tsphp_calc_values_build.php';

    }


    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Tsphistoryprice_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Tsphistoryprice_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/tsphistoryprice-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Tsphistoryprice_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Tsphistoryprice_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name . '_adm_ajx', plugin_dir_url(__FILE__) . 'js/tsphistoryprice-admin.js', array('jquery'), $this->version, false);
        // in JavaScript, object properties are accessed as ajax_object.ajax_url

        wp_localize_script('ajax_script', 'myAjax', array(
                'url' => admin_url('tsphistoryprice-admin-display.php'),
                'nonce' => wp_create_nonce("process_reservation_nonce"),
            )
        );

    }


}
