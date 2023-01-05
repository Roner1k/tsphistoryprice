<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://tsppilot.sitepreview.app/
 * @since      1.0.0
 *
 * @package    Tsphistoryprice
 * @subpackage Tsphistoryprice/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Tsphistoryprice
 * @subpackage Tsphistoryprice/public
 * @author     Next Level <alex@webzz.pro>
 */
class Tsphistoryprice_Public
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
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */

    public $tsphp_math_class;
    public $tsphp_import_class;
    public $tsphp_shortcodes_func_class;

    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->load_depth();
        $this->tsphp_math_class = new Tsphp_math_table();
        $this->tsphp_import_class = new Tsphp_import_build();
        $this->tsphp_shortcodes_func_class = new Tsphp_shortcodes();

        // add plugin shortcode to show projects loop
        add_shortcode('tsphp_allocation_aggressive', array($this->tsphp_shortcodes_func_class, 'tsphp_alocation_aggr_table'));
        add_shortcode('tsphp_allocation_conservative', array($this->tsphp_shortcodes_func_class, 'tsphp_alocation_cons_table'));

        add_shortcode('tsphp_performance_aggressive_chart', array($this->tsphp_shortcodes_func_class, 'tsphp_performance_aggr_chart'));
        add_shortcode('tsphp_performance_conservative_chart', array($this->tsphp_shortcodes_func_class, 'tsphp_performance_cons_chart'));

        add_shortcode('tsphp_past_aggressive_allocations', array($this->tsphp_shortcodes_func_class, 'tsphp_past_alocations_aggr'));
        add_shortcode('tsphp_past_conservative_allocations', array($this->tsphp_shortcodes_func_class, 'tsphp_past_alocations_cons'));

        add_shortcode('tsphp_performance_tiles', array($this->tsphp_shortcodes_func_class, 'tsphp_performance_tiles_run'));

        add_shortcode('tsphp_aggressive_heading', array($this->tsphp_shortcodes_func_class, 'tsphp_aggr_content_heading'));
        add_shortcode('tsphp_conservative_heading', array($this->tsphp_shortcodes_func_class, 'tsphp_consv_content_heading'));

    }



    public function load_depth()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-tsphp_shortcodes.php';

    }


    /**
     * Register the stylesheets for the public-facing side of the site.
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/tsphistoryprice-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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

        wp_enqueue_script("highstock2-js", plugin_dir_url(__FILE__) . 'js/highstock.js', array('jquery'), $this->version, false);

//        wp_enqueue_script("highcharts-js", plugin_dir_url(__FILE__) . 'js/highcharts.js', array('jquery'), $this->version, false);
        wp_enqueue_script("variable-pie", plugin_dir_url(__FILE__) . 'js/modules/variable-pie.js', array('jquery'), $this->version, false);
        wp_enqueue_script("highchartsdata-js", plugin_dir_url(__FILE__) . 'js/modules/data.js', array('jquery'), $this->version, false);
        wp_enqueue_script("series-label-js", plugin_dir_url(__FILE__) . 'js/modules/series-label.js', array('jquery'), $this->version, false);
        wp_enqueue_script("exporting-js", plugin_dir_url(__FILE__) . 'js/modules/exporting.js', array('jquery'), $this->version, false);
        wp_enqueue_script("export-data-js", plugin_dir_url(__FILE__) . 'js/modules/export-data.js', array('jquery'), $this->version, false);
        wp_enqueue_script("accessibility-js", plugin_dir_url(__FILE__) . 'js/modules/accessibility.js', array('jquery'), $this->version, false);
        wp_enqueue_script("fancyTable-min-js", plugin_dir_url(__FILE__) . 'js/vendor/fancyTable.min.js', array('jquery'), $this->version, false);

        wp_enqueue_script("tsphp-peformance-chart-js", plugin_dir_url(__FILE__) . 'js/tsphistoryprice-temp-chart.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/tsphistoryprice-public.js', array('jquery'), $this->version, false);wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/tsphistoryprice-public.js', array('jquery'), $this->version, false);

        // wp_enqueue_script("html2pdf-".$this->plugin_name, 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js', array('jquery'), $this->version, false);
         wp_enqueue_script("html2pdf-".$this->plugin_name, 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script("htmlcanvas-".$this->plugin_name, 'https://html2canvas.hertzen.com/dist/html2canvas.js', array('jquery','html2pdf-'.$this->plugin_name), $this->version, false);
        wp_enqueue_script("print-".$this->plugin_name, plugin_dir_url(__FILE__) . 'js/printThis.js', array('jquery','html2pdf-'.$this->plugin_name), $this->version, false);
        wp_enqueue_script("html2pdf-script-".$this->plugin_name, plugin_dir_url(__FILE__) . 'js/html2pdf.js', array('jquery'), $this->version, false);

    }

    public function tsphp_update_price_history()
    {
        $obj = new Tsphp_import_build();
        $obj->tsphp_auto_import();
    }

}
