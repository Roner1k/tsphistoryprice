<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://tsppilot.sitepreview.app/
 * @since      1.0.0
 *
 * @package    Tsphistoryprice
 * @subpackage Tsphistoryprice/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Tsphistoryprice
 * @subpackage Tsphistoryprice/includes
 * @author     Next Level <alex@webzz.pro>
 */
class Tsphistoryprice
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Tsphistoryprice_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */

    public $tsphp_price_table;
    public $tsphp_price_table_exist;

    public $tsphp_aggressive_table;
    public $tsphp_aggressive_table_exist;

    public function __construct()
    {

        if (defined('TSPHISTORYPRICE_VERSION')) {
            $this->version = TSPHISTORYPRICE_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'tsphistoryprice';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

        global $wpdb;

        $this->tsphp_price_table = new Tsphp_import_build();
        $this->tsphp_price_table = $this->tsphp_price_table->tsphp_price_table;
        $this->tsphp_price_table_exist = $this->tsphp_check_tables_exist($this->tsphp_price_table);

        $this->tsphp_aggressive_table = new Tsphp_math_table();
        $this->tsphp_aggressive_table_exist = $this->tsphp_aggressive_table->tsphp_aggressive_table_exist;
        $this->tsphp_aggressive_table = $this->tsphp_aggressive_table->tsphp_aggressive_table;

    }

    public function tsphp_check_tables_exist($checked_name)
    {
        global $wpdb;
        $table_name = $checked_name;
        $the_query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table_name));
        return $wpdb->get_var($the_query) == $table_name;
    }


    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Tsphistoryprice_Loader. Orchestrates the hooks of the plugin.
     * - Tsphistoryprice_i18n. Defines internationalization functionality.
     * - Tsphistoryprice_Admin. Defines all hooks for the admin area.
     * - Tsphistoryprice_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private
    function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-tsphistoryprice-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-tsphistoryprice-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-tsphp_wrapper_admin.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-tsphistoryprice-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-tsphistoryprice-public.php';
//        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-tsphp_shortcodes.php';

        $this->loader = new Tsphistoryprice_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Tsphistoryprice_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private
    function set_locale()
    {

        $plugin_i18n = new Tsphistoryprice_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private
    function define_admin_hooks()
    {

        $plugin_admin = new Tsphistoryprice_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private
    function define_public_hooks()
    {

        $plugin_public = new Tsphistoryprice_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('tsphp_cron_price_history_auto_import', $plugin_public, 'tsphp_update_price_history');


    }


    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public
    function run()
    {
        $this->loader->run();

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->tsphp_first_import_process($_POST);
            $this->tsphp_new_import_process($_POST);
            $this->tsphp_table_math_operations($_POST);


        }
        //test run new imp
        //        $test_neww_imp = new Tsphp_import_build();
        //        $test_neww_imp->tsphp_new_imp();

    }

    //run first import
    public function tsphp_first_import_process($post)
    {
        if (isset($post['action']) && $post['action'] == "tsphp-import") {
            $this->tsphp_first_import();
        }
    }

    public function tsphp_first_import()
    {

        $import_cl = new Tsphp_import_build;
        $import_cl->tsphp_auto_import();
    }

    //new imp
    public function tsphp_new_import_process($post)
    {
        if (isset($post['action']) && $post['action'] == "tsphp-new-import") {
            $this->tsphp_new_import();
        }
    }

    public function tsphp_new_import()
    {
//        $obj = new Tsphp_new_import();
//        $obj->tsphp_new_import();

        $import_cl = new Tsphp_import_build;
        $import_cl->tsphp_auto_import();
    }


    //create table with results
    public function tsphp_table_math_operations($post)
    {
        $math_obj = new Tsphp_math_table();

        if (isset($post['action']) && $post['action'] == "tsphp-new-agg-table") {
            $math_obj->tsphp_create_math_table();
        }
        if (isset($post['action']) && $post['action'] == "tsphp-update-calc-tables") {


            if ($this->tsphp_aggressive_table_exist) {
                $math_obj->tsphp_calc_math_run();
            }
        }

    }


    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     1.0.0
     */
    public
    function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Tsphistoryprice_Loader    Orchestrates the hooks of the plugin.
     * @since     1.0.0
     */
    public
    function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public
    function get_version()
    {
        return $this->version;
    }

}
