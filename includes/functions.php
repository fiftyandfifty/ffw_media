<?php
/**
 * Functions
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Insert Default Terms
 * @return [type] [description]
 */


/**
 * set_featured_image_from_url()
 * Set Featured Image From URl
 * @author Alexander Zizzo
 * @since 1.3
 */
function ffw_media_set_featured_image_from_url( $img_url = NULL, $debug = true )
{
  // Needed global vars
  global $post;
  
  // Add Featured Image to Post
  $image_url                = $img_url;                               // Define the image URL here
  $upload_dir               = wp_upload_dir();                        // Set upload folder
  $image_data               = file_get_contents($image_url);          // Get image data
  $filename                 = basename($image_url);                   // Create image file name
  $post_id                  = $post->ID;                              // Alt var for $post->ID
  $attach_id_orig           = get_post_thumbnail_id( $post->ID );     // Get the attachment ID
  $attachment_metadata      = get_post_meta( $attach_id_orig, '_wp_attachment_metadata', $single = false );
  $attachment_metadata_file = substr( $attachment_metadata[0]['file'], 8 );

  // Check folder permission and define file location
  if( wp_mkdir_p( $upload_dir['path'] ) ) {
      $file = $upload_dir['path'] . '/' . $filename;
  } else {
      $file = $upload_dir['basedir'] . '/' . $filename;
  }

    // Debug - console log some variables as needed
    if ( $debug ) {
      ?><script>console.log('$filename: <?php echo $filename; ?>, $attach_id_orig: <?php echo $attach_id_orig; ?> $attachment_metadata_file: <?php echo $attachment_metadata_file; ?> ');</script><?php
    }


    if ( !has_post_thumbnail( $post->ID ) && ( $attachment_metadata_file != $filename ) ) {

        // Create the image file on the server
        file_put_contents( $file, $image_data );

        // Check image file type
        $wp_filetype = wp_check_filetype( $filename, null );

        // Set attachment data
        $attachment = array(
            'guid'           => $upload_dir['url'] . '/' . basename( $filename ), 
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => sanitize_file_name( $filename ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        // $file_nonce  = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 8);
        // $file_nonced = $file_nonce . $file;

        // Create the attachment
        $attach_id = wp_insert_attachment( $attachment, $file, $post_id );

        // Include image.php
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // Define attachment metadata
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

        // Assign metadata to attachment
        wp_update_attachment_metadata( $attach_id, $attach_data );

        // And finally assign featured image to post
        set_post_thumbnail( $post_id, $attach_id );
    } elseif( has_post_thumbnail( $post->ID ) ) {
        // do nothing
    }
}


/**
 * Get the slud to return on the front end for themes
 * @return [type] [description]
 */
function ffw_get_media_slug()
{
  global $ffw_media_settings;

  $ffw_media_slug = defined( 'FFW_MEDIA_SLUG' ) ? FFW_MEDIA_SLUG : $ffw_media_settings['media_slug'];

  return $ffw_media_slug;
}




