<?php
/**
 * Plugin Name: One Click Course Creation
 * Plugin URI: http://writerscentre.com.au
 * Description: Lorem ipsum dolor sit amet
 * Author: AWC Debtim
 * Version 1.0.0
 * Text Domain: one-click-course-creation
 *
 * @package One_Click_Course_Creation
 */


if( ! defined( 'WPINC' ) ) die;

final class OneClickCourseCreation {

    /**
     * Current's Plugin Version
     *
     * @var string
     */
    protected $version = "1.0.0";

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

        $installer->install();

    }

    public function scripts()
    {

        echo "test";
        wp_enqueue_style(
            'custom-scss',
            get_template_directory_uri() . '/assets/css/app.css'
        );
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

add_action('admin_enqueue_scripts', array(OneClickCourseCreation::init(), 'scripts'));
