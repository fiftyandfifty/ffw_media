<?php
/**
 * Scripts
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Admin Styles & Scripts
 * Add the scripts & styles.
 * @since 1.0
 * @author Alexander Zizzo
 */

  // Ajax Localize
  // wp_localize_script('ffw_media-ajax', 'ffw_media_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

  

  // Enqueue Scripts/Styles
  function ffw_media_admin_scripts()
  {

    // Register Scripts/Styles
    wp_register_style( 'ffw_media-admin-js',  FFW_MEDIA_PLUGIN_URL .'/assets/css/ffw_media-admin.css' );
    wp_register_script('ffw_media-admin-css', FFW_MEDIA_PLUGIN_URL .'/assets/js/ffw_media-admin.js', array('jquery'));

    // Styles
    wp_enqueue_style('ffw_media-admin-js');
    // Scripts
    wp_enqueue_script('ffw_media-admin-css');
    // Ajax
    // wp_enqueue_script('ffw_media-ajax');


  }
  add_action('admin_enqueue_scripts', 'ffw_media_admin_scripts' );
