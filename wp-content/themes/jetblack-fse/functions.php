<?php
/**
 * JetBlack FSE functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage JetBlack_FSE
 * @since JetBlack FSE 1.0
 */


if ( ! function_exists( 'jetblack_fse_support' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since JetBlack FSE 1.0
	 *
	 * @return void
	 */
	function jetblack_fse_support() {

		// Add support for block styles.
		add_theme_support( 'wp-block-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style.css' );

		// Experimental support for adding blocks inside nav menus
		add_theme_support( 'block-nav-menus' );

		// Add support for experimental link color control.
		add_theme_support( 'experimental-link-color' );

		// Register nav menus.
		register_nav_menus(
			array(
				'primary' => __( 'Primary Navigation', 'jetblack-fse' ),
			)
		);

	}

endif;

add_action( 'after_setup_theme', 'jetblack_fse_support' );

if ( ! function_exists( 'jetblack_fse_styles' ) ) :

	/**
	 * Enqueue styles.
	 *
	 * @since JetBlack FSE 1.0
	 *
	 * @return void
	 */
	function jetblack_fse_styles() {
		
        wp_register_style(
			'jetblack-fse-style',
			get_template_directory_uri() . '/style.css',
			array(),
			jetblack_file_version( '/style.css' )
		);

		// Enqueue theme stylesheet.
		wp_enqueue_style( 'jetblack-fse-style' );

	}

endif;

add_action( 'wp_enqueue_scripts', 'jetblack_fse_styles' );

if ( ! function_exists( 'jetblack_admin_editor_styles' ) ) :

	/**
	 * Enqueue admin editor styles.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	function jetblack_admin_editor_styles() {
		
        wp_enqueue_style(
			'jetblack-admin-style',
			get_template_directory_uri() . '/assets/css/editor.css',
			array(),
			jetblack_file_version( '/assets/css/editor.css' )
		);

	}

endif;

add_action( 'admin_enqueue_scripts', 'jetblack_admin_editor_styles' );

/**
 * Get file version with respect to file modified date.
 */
function jetblack_file_version( $file_path ) {
    return date("ymd-Gis", filemtime( get_theme_file_path( $file_path ) ) );
}

// Add block patterns
require get_template_directory() . '/inc/block-patterns.php';

/**
 * Load Theme About Page
 */
require get_parent_theme_file_path( '/inc/about-admin/theme-info.php' );
