<?php
/**
 * Post Type Functions
 *
 * @package     FFW
 * @subpackage  Functions
 * @copyright   Copyright (c) 2013, Fifty and Fifty
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Registers and sets up the Downloads custom post type
 *
 * @since 1.0
 * @return void
 */
function setup_ffw_media_post_types() {
	global $ffw_media_settings;
	$archives = defined( 'FFW_MEDIA_DISABLE_ARCHIVE' ) && FFW_MEDIA_DISABLE_ARCHIVE ? false : true;

	//Check to see if anything is set in the settings area.
	if( !empty( $ffw_media_settings['media_slug'] ) ) {
	    $slug = defined( 'FFW_MEDIA_SLUG' ) ? FFW_MEDIA_SLUG : $ffw_media_settings['media_slug'];
	} else {
	    $slug = defined( 'FFW_MEDIA_SLUG' ) ? FFW_MEDIA_SLUG : 'media';
	}
	
	$rewrite  = defined( 'FFW_MEDIA_DISABLE_REWRITE' ) && FFW_MEDIA_DISABLE_REWRITE ? false : array('slug' => $slug, 'with_front' => false);

	$media_labels =  apply_filters( 'ffw_media_media_labels', array(
		'name' 				=> '%2$s',
		'singular_name' 	=> '%1$s',
		'add_new' 			=> __( 'Add New', 'FFW_media' ),
		'add_new_item' 		=> __( 'Add New %1$s', 'FFW_media' ),
		'edit_item' 		=> __( 'Edit %1$s', 'FFW_media' ),
		'new_item' 			=> __( 'New %1$s', 'FFW_media' ),
		'all_items' 		=> __( 'All %2$s', 'FFW_media' ),
		'view_item' 		=> __( 'View %1$s', 'FFW_media' ),
		'search_items' 		=> __( 'Search %2$s', 'FFW_media' ),
		'not_found' 		=> __( 'No %2$s found', 'FFW_media' ),
		'not_found_in_trash'=> __( 'No %2$s found in Trash', 'FFW_media' ),
		'parent_item_colon' => '',
		'menu_name' 		=> __( '%2$s', 'FFW_media' )
	) );

	foreach ( $media_labels as $key => $value ) {
	   $media_labels[ $key ] = sprintf( $value, ffw_media_get_label_singular(), ffw_media_get_label_plural() );
	}

	$media_args = array(
		'labels' 			=> $media_labels,
		'public' 			=> true,
		'publicly_queryable'=> true,
		'show_ui' 			=> true,
		'show_in_menu' 		=> true,
		'menu_icon'         => FFW_MEDIA_PLUGIN_URL . '/assets/images/media.png',
		'query_var' 		=> true,
		'rewrite' 			=> $rewrite,
		'map_meta_cap'      => true,
		'has_archive' 		=> $archives,
		'show_in_nav_menus'	=> true,
		'menu_icon'			=> 'dashicons-editor-video',
		'hierarchical' 		=> false,
		'supports' 			=> apply_filters( 'ffw_media_supports', array( 'title', 'editor', 'thumbnail', 'excerpt' ) ),
	);
	register_post_type( 'FFW_media', apply_filters( 'ffw_media_post_type_args', $media_args ) );
	
}
add_action( 'init', 'setup_ffw_media_post_types', 1 );

/**
 * Get Default Labels
 *
 * @since 1.0.8.3
 * @return array $defaults Default labels
 */
function ffw_media_get_default_labels() {
	global $ffw_media_settings;

	if( !empty( $ffw_media_settings['media_label_plural'] ) || !empty( $ffw_media_settings['media_label_singular'] ) ) {
	    $defaults = array(
	       'singular' => $ffw_media_settings['media_label_singular'],
	       'plural' => $ffw_media_settings['media_label_plural']
	    );
	 } else {
		$defaults = array(
		   'singular' => __( 'Fifty Media', 'FFW_media' ),
		   'plural' => __( 'Fifty Media', 'FFW_media')
		);
	}
	
	return apply_filters( 'ffw_media_default_name', $defaults );

}

/**
 * Get Singular Label
 *
 * @since 1.0.8.3
 * @return string $defaults['singular'] Singular label
 */
function ffw_media_get_label_singular( $lowercase = false ) {
	$defaults = ffw_media_get_default_labels();
	return ($lowercase) ? strtolower( $defaults['singular'] ) : $defaults['singular'];
}

/**
 * Get Plural Label
 *
 * @since 1.0.8.3
 * @return string $defaults['plural'] Plural label
 */
function ffw_media_get_label_plural( $lowercase = false ) {
	$defaults = ffw_media_get_default_labels();
	return ( $lowercase ) ? strtolower( $defaults['plural'] ) : $defaults['plural'];
}

/**
 * Change default "Enter title here" input
 *
 * @since 1.4.0.2
 * @param string $title Default title placeholder text
 * @return string $title New placeholder text
 */
function ffw_media_change_default_title( $title ) {
     $screen = get_current_screen();

     if  ( 'ffw_media' == $screen->post_type ) {
     	$label = ffw_media_get_label_singular();
        $title = sprintf( __( 'Enter %s title here', 'FFW_media' ), $label );
     }

     return $title;
}
add_filter( 'enter_title_here', 'ffw_media_change_default_title' );

/**
 * Registers the custom taxonomies for the downloads custom post type
 *
 * @since 1.0
 * @return void
*/
function ffw_media_setup_taxonomies() {

	$slug     = defined( 'FFW_MEDIA_SLUG' ) ? FFW_MEDIA_SLUG : 'media';

	/** Types */
	$type_labels = array(
		'name' 				=> __( 'Media Types', 'taxonomy general name', 'FFW_media' ),
		'singular_name' 	=> __( 'Media Type', 'taxonomy singular name', 'FFW_media' ),
		'search_items' 		=> __( 'Search Types', 'FFW_media'  ),
		'all_items' 		=> __( 'All Types', 'FFW_media'  ),
		'parent_item' 		=> __( 'Parent Type', 'FFW_media'  ),
		'parent_item_colon' => __( 'Parent Type:', 'FFW_media'  ),
		'edit_item' 		=> __( 'Edit Type', 'FFW_media'  ),
		'update_item' 		=> __( 'Update Type', 'FFW_media'  ),
		'add_new_item' 		=> __( 'Add New Type', 'FFW_media'  ),
		'new_item_name' 	=> __( 'New Type Name', 'FFW_media'  ),
		'menu_name' 		=> __( 'Types', 'FFW_media'  ),
	);

	$type_args = apply_filters( 'ffw_media_type_args', array(
			'hierarchical' 		=> true,
			'labels' 			=> apply_filters('ffw_media_type_labels', $type_labels),
			'show_ui' 			=> true,
			'query_var' 		=> 'media_type',
			'rewrite' 			=> array('slug' => $slug . '/type', 'with_front' => false, 'hierarchical' => true ),
			'capabilities'  	=> array( 'manage_terms','edit_terms', 'assign_terms', 'delete_terms' ),
			'show_admin_column'	=> true
		)
	);
	register_taxonomy( 'media_type', array('ffw_media'), $type_args );
	register_taxonomy_for_object_type( 'media_type', 'ffw_media' );

	/** Categories */
	$category_labels = array(
		'name' 				=> sprintf( _x( '%s Categories', 'taxonomy general name', 'FFW_media' ), ffw_media_get_label_singular() ),
		'singular_name' 	=> _x( 'Category', 'taxonomy singular name', 'FFW_media' ),
		'search_items' 		=> __( 'Search Categories', 'FFW_media'  ),
		'all_items' 		=> __( 'All Categories', 'FFW_media'  ),
		'parent_item' 		=> __( 'Parent Category', 'FFW_media'  ),
		'parent_item_colon' => __( 'Parent Category:', 'FFW_media'  ),
		'edit_item' 		=> __( 'Edit Category', 'FFW_media'  ),
		'update_item' 		=> __( 'Update Category', 'FFW_media'  ),
		'add_new_item' 		=> __( 'Add New Category', 'FFW_media'  ),
		'new_item_name' 	=> __( 'New Category Name', 'FFW_media'  ),
		'menu_name' 		=> __( 'Categories', 'FFW_media'  ),
	);

	$category_args = apply_filters( 'ffw_media_category_args', array(
			'hierarchical' 		=> true,
			'labels' 			=> apply_filters( 'ffw_media_category_labels', $category_labels ),
			'show_ui' 			=> true,
			'query_var' 		=> 'media_category',
			'rewrite' 			=> array('slug' => $slug . '/category', 'with_front' => false, 'hierarchical' => true ),
			'capabilities'  	=> array( 'manage_terms','edit_terms', 'assign_terms', 'delete_terms' ),
			'show_admin_column'	=> true
		)
	);
	register_taxonomy( 'media_category', array('ffw_media'), $category_args );
	register_taxonomy_for_object_type( 'media_category', 'ffw_media' );


	

}
add_action( 'init', 'ffw_media_setup_taxonomies', 0 );




/**
 * Updated Messages
 *
 * Returns an array of with all updated messages.
 *
 * @since 1.0
 * @param array $messages Post updated message
 * @return array $messages New post updated messages
 */
function ffw_media_updated_messages( $messages ) {
	global $post, $post_ID;

	$url1 = '<a href="' . get_permalink( $post_ID ) . '">';
	$url2 = ffw_media_get_label_singular();
	$url3 = '</a>';

	$messages['FFW_media'] = array(
		1 => sprintf( __( '%2$s updated. %1$sView %2$s%3$s.', 'FFW_media' ), $url1, $url2, $url3 ),
		4 => sprintf( __( '%2$s updated. %1$sView %2$s%3$s.', 'FFW_media' ), $url1, $url2, $url3 ),
		6 => sprintf( __( '%2$s published. %1$sView %2$s%3$s.', 'FFW_media' ), $url1, $url2, $url3 ),
		7 => sprintf( __( '%2$s saved. %1$sView %2$s%3$s.', 'FFW_media' ), $url1, $url2, $url3 ),
		8 => sprintf( __( '%2$s submitted. %1$sView %2$s%3$s.', 'FFW_media' ), $url1, $url2, $url3 )
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'ffw_media_updated_messages' );
