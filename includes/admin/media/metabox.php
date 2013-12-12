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
            'ffw_media_type'
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



/** Campaign Configuration *****************************************************************/

/**
 * Campaign Metabox
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



function ffw_render_media_fields()
{
    global $post, $ffw_media_settings;

    $ffw_media_type = get_post_meta( $post->ID, 'ffw_media_type', true );

    ?>
    
    <p><strong><?php _e( 'Media Information', 'ffw_media' ); ?></strong></p>
    <p>
        <label for="ffw_media_type">
            <input type="text" name="ffw_media_type" id="ffw_media_type" value="<?php echo $ffw_media_type; ?>" style="width:80px;" />
            <?php _e( 'Media Type', 'ffw_media' );  ?>
        </label>
    </p>


    <?php

}
add_action( 'ffw_media_meta_box_fields', 'ffw_render_media_fields', 10 );