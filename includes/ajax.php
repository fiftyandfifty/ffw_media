<?php 

/**
 * FFW_MEDIA Ajax Methods
 *
 * @since 0.1
 * @package ffw_media
 * @author Alexander Zizzo (Fifty and Fifty, LLC)
 * @todo 
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


add_action( 'init', 'ffw_media_ajax_methods' );

function ffw_media_ajax_methods()
{

  /**
   * AJAX TEST
   * @since 0.1
   */
  function ffw_media_ajax_test()
  {
    print 'Ajax success';
  }
  add_action( 'wp_ajax_ffw_media_ajax_test', 'ffw_media_ajax_test' );



  /**
   * Update Media Thumb (Featured Image)
   * @since 0.1
   */
  function ffw_media_set_thumb()
  {
    
    print 'ffw_media_set_thumb function running, returning: ';

  }
  add_action( 'wp_ajax_ffw_media_set_thumb', 'ffw_media_set_thumb' );


  /**
   * Lookup a Person
   * @since 0.1
   */
  // function dntly_lookup_person()
  // {
  //   $dntly = new DNTLY_API;
  //   $dntly->lookup_person();
  // }
  // add_action('wp_ajax_dntly_lookup_person','dntly_lookup_person');
  // add_action('wp_ajax_nopriv_dntly_lookup_person','dntly_lookup_person');

}