<?php
/**
 * Include Theme Functions
 *
 * @package Authentic Child Theme
 * @subpackage Functions
 * @version 1.0.0
 */

/**
 * Setup Child Theme
 */
function csco_setup_child_theme() {
	// Add Child Theme Text Domain.
	load_child_theme_textdomain( 'authentic', get_stylesheet_directory() . '/languages' );

	// register navigation menu
	register_nav_menus(
		array(
			'privacy-menu' => esc_html__( 'Privacy Menu', 'authentic' ),
		)
	);
}

add_action( 'after_setup_theme', 'csco_setup_child_theme', 99 );

/**
 * Enqueue Child Theme Assets
 */
function csco_child_assets() {
	if ( ! is_admin() ) {
		$version = wp_get_theme()->get( 'Version' );
		wp_enqueue_style( 'csco_child_css', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array(), $version, 'all' );
	}
	// slick.min.css
	wp_enqueue_style( 'slickcss1', trailingslashit( get_stylesheet_directory_uri() ) . '/assets/css/slick.min.css', array(), $version, 'all' );
	// slick-theme.min.css
	wp_enqueue_style( 'slickcss', trailingslashit( get_stylesheet_directory_uri() ) . '/assets/css/slick-theme.min.css', array(), $version, 'all' );
	// slick min js
	wp_enqueue_script( 'script-name', trailingslashit( get_stylesheet_directory_uri() ) . '/assets/js/slick.min.js', array(), $version, 'all');

	// ajax js
	wp_enqueue_script( 'ajax-js', trailingslashit( get_stylesheet_directory_uri() ) . '/assets/js/ajax.js', array(), $version, 'all');
    wp_localize_script('ajax-js', 'csco_post_list',
       array( 
           'ajaxurl' => admin_url('admin-ajax.php')
       )
    );
}
add_action( 'wp_enqueue_scripts', 'csco_child_assets', 99 );

/**
 * Include ACF Custom Block
 */
require get_stylesheet_directory() . '/inc/custom-blocks-registration.php';

/**
 * Include Custom Post Type
 */
require get_stylesheet_directory() . '/inc/custom-post-types.php';

/**
 * Include Custom Shortcode
 */
require get_stylesheet_directory() . '/inc/custom-shortcode.php';

/**
 * Include Common Functions
 */
require get_stylesheet_directory() . '/inc/common-functions.php';

/**
 * Template Tags.
 */
require get_stylesheet_directory() . '/inc/template-tags.php';