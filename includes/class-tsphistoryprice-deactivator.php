<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://tsppilot.sitepreview.app/
 * @since      1.0.0
 *
 * @package    Tsphistoryprice
 * @subpackage Tsphistoryprice/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Tsphistoryprice
 * @subpackage Tsphistoryprice/includes
 * @author     Next Level <roner1kk@gmail.com>
 */
class Tsphistoryprice_Deactivator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function deactivate()
    {
        wp_clear_scheduled_hook('tsphp_cron_price_history_auto_import');

//        tsphp_unistall_DB
//        $clear_db = new Tsphp_import_build();
//        $clear_db->tsphp_unistall_DB();

    }

}
