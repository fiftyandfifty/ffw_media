<?php 
/**
 * Plugin Name: Fifty Framework Media
 * Plugin URI: http://fiftyandfifty.org
 * Description: Build media pages for your site
 * Version: 1.0
 * Author: Fifty and Fifty
 * Author URI: http://labs.fiftyandfifty.org
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'FFW_MEDIA' ) ) :


/**
 * Main FFW_MEDIA Class
 *
 * @since 1.0 */
final class FFW_MEDIA {

  /**
   * @var FFW_MEDIA Instance
   * @since 1.0
   */
  private static $instance;


  /**
   * FFW_MEDIA Instance / Constructor
   *
   * Insures only one instance of FFW_MEDIA exists in memory at any one
   * time & prevents needing to define globals all over the place. 
   * Inspired by and credit to FFW_MEDIA.
   *
   * @since 1.0
   * @static
   * @uses FFW_MEDIA::setup_globals() Setup the globals needed
   * @uses FFW_MEDIA::includes() Include the required files
   * @uses FFW_MEDIA::setup_actions() Setup the hooks and actions
   * @see FFW_MEDIA()
   * @return void
   */
  public static function instance() {
    if ( ! isset( self::$instance ) && ! ( self::$instance instanceof FFW_MEDIA ) ) {
      self::$instance = new FFW_MEDIA;
      self::$instance->setup_constants();
      self::$instance->includes();
      // self::$instance->load_textdomain();
      // use @examples from public vars defined above upon implementation
    }
    return self::$instance;
  }



  /**
   * Setup plugin constants
   * @access private
   * @since 1.0 
   * @return void
   */
  private function setup_constants() {
    // Plugin version
    if ( ! defined( 'FFW_MEDIA_VERSION' ) )
      define( 'FFW_MEDIA_VERSION', '1.0' );

    // Plugin Folder Path
    if ( ! defined( 'FFW_MEDIA_PLUGIN_DIR' ) )
      define( 'FFW_MEDIA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

    // Plugin Folder URL
    if ( ! defined( 'FFW_MEDIA_PLUGIN_URL' ) )
      define( 'FFW_MEDIA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

    // Plugin Root File
    if ( ! defined( 'FFW_MEDIA_PLUGIN_FILE' ) )
      define( 'FFW_MEDIA_PLUGIN_FILE', __FILE__ );

    if ( ! defined( 'FFW_MEDIA_DEBUG' ) )
      define ( 'FFW_MEDIA_DEBUG', true );
  }




  /**
   * Include required files
   * @access private
   * @since 1.0
   * @return void
   */
  private function includes() {
    global $ffw_media_settings, $wp_version;

    require_once FFW_MEDIA_PLUGIN_DIR . '/includes/admin/settings/register-settings.php';
    $ffw_media_settings = ffw_media_get_settings();

    // Required Plugin Files
    require_once FFW_MEDIA_PLUGIN_DIR . '/includes/functions.php';
    require_once FFW_MEDIA_PLUGIN_DIR . '/includes/admin/media/metabox.php';
    require_once FFW_MEDIA_PLUGIN_DIR . '/includes/posttypes.php';
    require_once FFW_MEDIA_PLUGIN_DIR . '/includes/scripts.php';
    require_once FFW_MEDIA_PLUGIN_DIR . '/includes/shortcodes.php';

    if( is_admin() ){
        //Admin Required Plugin Files
        require_once FFW_MEDIA_PLUGIN_DIR . '/includes/admin/admin-pages.php';
        require_once FFW_MEDIA_PLUGIN_DIR . '/includes/admin/admin-notices.php';
        require_once FFW_MEDIA_PLUGIN_DIR . '/includes/admin/settings/display-settings.php';

    }

  }

} /* end FFW_MEDIA class */
endif; // End if class_exists check


/**
 * Main function for returning FFW_MEDIA Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $sqcash = FFW_MEDIA(); ?>
 *
 * @since 1.0
 * @return object The one true FFW_MEDIA Instance
 */
function FFW_MEDIA() {
  return FFW_MEDIA::instance();
}





/**
 * Initiate
 * Run the FFW_MEDIA() function, which runs the instance of the FFW_MEDIA class.
 */
FFW_MEDIA();



/**
 * Debugging
 * @since 1.0
 */
if ( FFW_MEDIA_DEBUG ) {
  ini_set('display_errors','On');
  error_reporting(E_ALL);
}




