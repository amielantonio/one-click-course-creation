<?php
/**
 * Plugin Name: One Click Course Creation
 * Plugin URI: http://writerscentre.com.au
 * Description: Lorem ipsum dolor sit amet
 * Author: AWC Debtim
 * Version 1.2.10
 * Text Domain: one-click-course-creation
 *
 * @package One_Click_Course_Creation
 */


if( ! defined( 'WPINC' ) ) die;
ob_clean();
ob_start();
final class OneClickCourseCreation {

    /**
     * Current's Plugin Version
     *
     * @var string
     */
    protected $version = "1.2.10";

    /**
     * Minimum required PHP version
     *
     * @var string
     */
    protected $php_version = '5.6';

    /**
     * Singleton instance for the plugin
     *
     * @var OneClickCourseCreation
     */
    private static $instance;

    /**
     * Creates a single instance of the plugin
     *
     * @since 1.0.0
     * @return OneClickCourseCreation
     */
    public static function init()
    {
        if( ! isset( self::$instance ) && !( self::$instance instanceof OneClickCourseCreation) ){
            self::$instance = new OneClickCourseCreation();
            self::$instance->start();
        }

        return self::$instance;
    }


    /**
     * Run Plugin Setup
     */
    private function start()
    {
        //Check if the current PHP version
//        register_activation_hook( __FILE__, array( $this, 'auto_deactivate') );
        $this->defineConstants();
        $this->registerAutoload();
        $this->loadDependencies();
        add_action('admin_enqueue_scripts', array(__CLASS__, 'scripts'));


        // Once all dependencies and services has been loaded, install database
        // and boot up the entire plugin
        $this->boot();
    }

    /**
     * Define Class constants
     */
    private function defineConstants()
    {
        //Libraries
        define( 'S_BASEPATH'    , dirname( __FILE__ ) );
        define( 'S_LIBPATH'     , dirname( __FILE__) . "/lib" );
        define( 'S_ASSETSPATH'  , dirname(__FILE__ ) . "/assets" );
        define( 'S_CONFIGPATH'  , dirname(__FILE__ ) . "/config" );
        define( 'S_DBPATH'      , dirname( __FILE__) . "/database" );
        define( 'S_INCPATH'     , dirname( __FILE__) . "/includes" );
        define( 'S_VIEWPATH'    , dirname( __FILE__) . "/templates" );


        //Plugin
        define( 'S_BASE_DIR', plugin_dir_path( __DIR__ ));


        //Misc
        define( 'S_VERSION', $this->version );
        define( 'S_PHP_VERSION', $this->php_version );
    }

    /**
     * Auto Deactivates the plugin when the PHP Version is lower than required,
     * Then show the error on the front end
     */
    private function auto_deactivate()
    {
        if( version_compare( PHP_VERSION, $this->php_version, '<=') ) {
            return;
        }

        deactivate_plugins( basename(__FILE__) );
    }

    /**
     * Load all helper functions
     */
    private function registerAutoload()
    {
        //Require Vendor Autoload
        require __DIR__ . '/vendor/autoload.php';
    }

    /**
     * Loads all dependencies
     */
    private function loadDependencies()
    {
        //Load dependency classes such as custom post types,
        //roles, databases and other wordpress instantiation before adding
        //it to any navigation.

        $installer = new \AWC\Helpers\Installer;

        include_once S_LIBPATH . "/Helpers/Functions/helpers.php";

        $installer->install();

    }

    public function scripts()
    {
        wp_enqueue_style('custom-scss', plugins_url('one-click-course-creation') . '/assets/css/one-click-creation.css');
        wp_enqueue_script('custom-js-admin', plugins_url('one-click-course-creation') . '/assets/js/one-click-creation.js');

        wp_enqueue_style('select2-admin', 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css', '', '', '');
        wp_enqueue_script('select2-admin', 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js', '', '', '');

        wp_enqueue_style('airdatepicker-admin', 'https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/css/datepicker.min.css', '', '', '');
        wp_enqueue_script('airdatepicker-admin', 'https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/datepicker.min.js', '', '', '');
        wp_enqueue_script('airdatepicker-admin-en', 'https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.en.min.js', '', '', '');
        wp_enqueue_script('moment-admin', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js', '', '', '');

        wp_enqueue_script('ckeditor-admin', '//cdn.ckeditor.com/4.14.0/standard/ckeditor.js', '', '', '');

        wp_enqueue_style('datatables-admin', '//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css', '', '', '');
        wp_enqueue_script('datatables-admin', '//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js', '', '', '');
    }



    /**
     *
     */
    private function boot()
    {
        Kernel::run();
        Router::start( (new PageCreator) );
    }
}

/**
 * Start the App
 */
OneClickCourseCreation::init();

