<?php

/**
 * Fired during plugin activation
 *
 * @link       https://tsppilot.sitepreview.app/
 * @since      1.0.0
 *
 * @package    Tsphistoryprice
 * @subpackage Tsphistoryprice/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Tsphistoryprice
 * @subpackage Tsphistoryprice/includes
 * @author     Next Level <roner1kk@gmail.com>
 */
class Tsphistoryprice_Activator
{


    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        $log_class = new Tsphp_log_build;
        $log_class->tsphp_create_log_table();

        if (!wp_next_scheduled('tsphp_cron_price_history_auto_import')) {
            wp_schedule_event(time(), 'daily', 'tsphp_cron_price_history_auto_import');
        }
    }

}
