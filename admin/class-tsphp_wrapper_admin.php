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
 * @author     Next Level <roner1kk@gmail.com>
 */
class Tsphp_wrapper_admin
{
    public function __construct()
    {


    }

    //
    public function tsphp_check_tables_exist($checked_name)
    {
        global $wpdb;
        $table_name = $checked_name;
        $the_query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table_name));
        return $wpdb->get_var($the_query) == $table_name;
    }


    // a message div
    public function tsphp_message($message)
    {
        return '<div class="updated below-h2">' . $message . '</div>';
    }

    // create HTML wrap structure
    public function tsphp_wrap($type = 'header', $title = '')
    {

        if ($type == 'header') {
            $return = '<div class="wrap">';
            $return .= sprintf('<h2>%s</h2>', $title);

        } elseif ($type == 'footer') {
            $return = '</div>';
        }

        return $return;
    }


//    function tsphp_update_new_period_row()
//    {
//        $table_name = new Tsphp_math_table();
//        $table_name = $table_name->tsphp_aggressive_table;
//        $row_ID = $_POST['rowId'];
//        $is_period = $_POST['periodVal'];
//        $upd_SQL = "UPDATE $table_name set alert_date = '$is_period' WHERE dr_ID = $row_ID";
//        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
//        dbDelta($upd_SQL);
//        echo $upd_SQL;
//        exit();
//    }
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


}
