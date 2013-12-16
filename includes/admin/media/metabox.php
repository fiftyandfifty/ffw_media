<?php
/**
 * Metabox Functions
 *
 * @package     Fifty Framework Meia
 * @subpackage  Admin/Media
 * @copyright   Copyright (c) 2013, Fifty and Fifty
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/** All Downloads *****************************************************************/

/**
 * Register all the meta boxes for the Download custom post type
 *
 * @since 1.0
 * @return void
 */
function ffw_add_media_meta_box() {

    $post_types = apply_filters( 'ffw_media_metabox_post_types' , array( 'ffw_media' ) );

    foreach ( $post_types as $post_type ) {

        /** Download Configuration */
        add_meta_box( 'mediainfo', sprintf( __( '%1$s Configuration', 'ffw_media`' ), ffw_media_get_label_singular(), ffw_media_get_label_plural() ),  'ffw_render_media_meta_box', $post_type, 'normal', 'default' );

    }
}
add_action( 'add_meta_boxes', 'ffw_add_media_meta_box' );


/**
 * Sabe post meta when the save_post action is called
 *
 * @since 1.0
 * @param int $post_id Download (Post) ID
 * @global array $post All the data of the the current post
 * @return void
 */
function ffw_media_meta_box_save( $post_id) {
    global $post, $ffw_media_settings;

    if ( ! isset( $_POST['ffw_media_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['ffw_media_meta_box_nonce'], basename( __FILE__ ) ) )
        return $post_id;

    if ( ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX') && DOING_AJAX ) || isset( $_REQUEST['bulk_edit'] ) )
        return $post_id;

    if ( isset( $post->post_type ) && $post->post_type == 'revision' )
        return $post_id;


    // The default fields that get saved
    $fields = apply_filters( 'ffw_media_metabox_fields_save', array(
            'ffw_media_type',
            'ffw_media_type_url',
            'ffw_media_type_thumbnail'
        )
    );

    foreach ( $fields as $field ) {
        if ( ! empty( $_POST[ $field ] ) ) {
            $new = apply_filters( 'ffw_media_metabox_save_' . $field, $_POST[ $field ] );
            update_post_meta( $post_id, $field, $new );
        } else {
            delete_post_meta( $post_id, $field );
        }
    }
}
add_action( 'save_post', 'ffw_media_meta_box_save' );



/** Fifty Media Configuration *****************************************************************/

/**
 * Fifty Media Metabox
 *
 * Extensions (as well as the core plugin) can add items to the main download
 * configuration metabox via the `ffw_media`_meta_box_fields` action.
 *
 * @since 1.0
 * @return void
 */
function ffw_render_media_meta_box() {
    global $post, $ffw_media_settings;

    do_action( 'ffw_media_meta_box_fields', $post->ID );
    wp_nonce_field( basename( __FILE__ ), 'ffw_media_meta_box_nonce' );
}




/**
 * FFW Render Media Fields
 * @since 1.0
 */
function ffw_render_media_fields()
{
    // Needed global vars
    global $post, $ffw_media_settings;

    // Set post meta as vars
    $ffw_media_type             = get_post_meta( $post->ID, 'ffw_media_type', true );
    $ffw_media_type_url         = get_post_meta( $post->ID, 'ffw_media_type_url', true );
    $ffw_media_type_thumbnail   = get_post_meta( $post->ID, 'ffw_media_type_thumbnail', true );

    ?>
    
    <?php /* FFW_MEDIA_TYPE (select options)
    ================================================== */ ?>
    <p><strong><?php _e( 'Media Information', 'ffw_media' ); ?></strong></p>
    <p>
        <label for="ffw_media_type">
            <select name="ffw_media_type" id="ffw_media_type">
                <option value="0" disabled>-------------------</option>
                <option value="ffw_media_youtube" <?php selected( $ffw_media_type, 'ffw_media_youtube' ); ?>>Youtube Video</option>
                <option value="ffw_media_vimeo" <?php selected( $ffw_media_type, 'ffw_media_vimeo' ); ?>>Vimeo Video</option>
                <option value="ffw_media_flickr" <?php selected( $ffw_media_type, 'ffw_media_flickr' ); ?>>Flickr Gallery</option>
            </select>
            <?php _e( 'Media Type', 'ffw_media' );  ?>
        </label>
    </p>

    
    <?php /* FFW_MEDIA_TYPES (expanded/selected views)
    ================================================== */ ?>
    <div id="ffw_media_types">
        <div id="ffw_media_youtube-selected" style="display:none;">
            <p><strong><?php _e( 'Youtube Video URL', 'ffw_media' ); ?></strong></p>
        </div>
        <div id="ffw_media_vimeo-selected" style="display:none;">
            <p><strong><?php _e( 'Vimeo Video URL', 'ffw_media' ); ?></strong></p>
        </div>

        <div id="ffw_media_flickr-selected" style="display:none;">
            <p><strong><?php _e( 'Flickr Gallery URL', 'ffw_media' ); ?></strong></p>
        </div>

        <input type="text" name="ffw_media_type_url" id="ffw_media_type_url" value="<?php echo $ffw_media_type_url; ?>" style="display:none;">
    </div>


    <?php /* GET THUMBNAIL
    ================================================== */ ?>
    <script>
     // DOC READY, RUN FUNCS
     // jQuery(document).ready(function($) {
     //     $.ajax({
     //       'type'  : 'post',
     //       'url'   : ajaxurl,
     //       'data'  : {
     //         'action'  : 'ffw_media_set_thumb',
     //         'url'     : '<?php $ffw_media_type_url; ?>'
     //       },
     //       success : function(response) { console.log('Ajax function sent, response:', response); },
     //       error   : function(response) { alert('Error Saving Settings', response);}
     //     });
     // });
    </script>

    <?php 
        // If the post has just been updated (in URL there will be $message=1)
        if ( isset($ffw_media_type) ) {
            // Set some meta data (featured image), uses /fifty-framework/functions/helpers.php
            if ( preg_match('/youtube/', $ffw_media_type) || preg_match('/vimeo/', $ffw_media_type) ) {
                // Then it's a video
                $ffw_media_type_service     = get_video_service( $ffw_media_type_url );
                $ffw_media_type_id          = get_video_id( $ffw_media_type_url );

                ////////////////////////////////////////
                // Y O U T U B E
                ////////////////////////////////////////
                // If service is youtube, pass data_type param to get thumb
                if ( $ffw_media_type_service == 'youtube' ) {
                    $ffw_media_type_thumb_url   = get_video_data( $ffw_media_type_url, 'thumbnail_large' );
                    // Set the meta as the thumb URL
                    update_post_meta( $post->ID, 'ffw_media_type_thumbnail', $ffw_media_type_thumb_url );
                    // Set the attachment ID to the meta
                    update_post_meta( $post->ID, 'ffw_media_type_attach_id', get_post_thumbnail_id($post->ID) );
                    // Set featured image from URL
                    ffw_media_set_featured_image_from_url( $ffw_media_type_thumb_url );

                ////////////////////////////////////////
                // V I M E O
                ////////////////////////////////////////
                // If it's vimeo, don't pass the data_type param, as func will hit API instead of genereating URL
                } elseif ( $ffw_media_type_service == 'vimeo' ) {
                    $ffw_media_type_thumb_url   = get_video_data( $ffw_media_type_url, 'thumbnail_large' );
                    // Set the meta as the thumb URL
                    update_post_meta( $post->ID, 'ffw_media_type_thumbnail', $ffw_media_type_thumb_url );
                    // Set the attachment ID to the meta
                    update_post_meta( $post->ID, 'ffw_media_type_attach_id', get_post_thumbnail_id($post->ID) );
                    // Set featured image from URL
                    ffw_media_set_featured_image_from_url( $ffw_media_type_thumb_url );
                }

            ////////////////////////////////////////
            // F L I C K R
            ////////////////////////////////////////
            } else {
                // Then it's a flickr gallery
                $ffw_media_type_service = 'flickr';
                // @TODO get flickr thumb URL (or don't, and give fallback BG and let user set featured? )
            }
        } elseif ( !isset($_GET['message']) ) {
            // 
        }


        
        
     ?>

    


    <?php /* DEBUGGING (temp)
    ================================================== */ 
    $ffw_media_debugging = true;
    if ( $ffw_media_debugging ) : ?>

        <div id="ffw_media_debugging">
            <h4>FFW_MEDIA_DEBUGGING</h4>
            <pre>
<?php 
print '<h3> $ffw_media_type </h3>'; var_dump($ffw_media_type);
print '<h3> $ffw_media_type_url </h3>'; var_dump($ffw_media_type_url);
print '<h3> $ffw_media_type_thumbnail </h3>'; var_dump($ffw_media_type_thumbnail);
print '<h3> $ffw_media_type_service </h3>'; var_dump($ffw_media_type_service);
print '<h3> $ffw_media_type_id </h3>'; var_dump($ffw_media_type_id);

print '<h3> POST META </h3>';
var_dump(get_post_meta( $post->ID ));
?>
            </pre>
        </div>

    <?php endif; ?>

    <?php

}
add_action( 'ffw_media_meta_box_fields', 'ffw_render_media_fields', 10 );