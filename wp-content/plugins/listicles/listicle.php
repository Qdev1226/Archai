<?php
/**
 * Plugin Name: Listicles by LezWatch.TV
 * Plugin URI: https://github.com/lezwatch/listicles
 * Description: Create listicle articles in posts.
 * Author: LezWatch.TV
 * Author URI: https://lezwatchtv.com
 * Version: 2.2.0
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function listicles_block_assets() {
	$build_css = '/build/style-index.css';
	wp_enqueue_style(
		'listicles',
		plugins_url( $build_css, __FILE__ ),
		array(),
		filemtime( dirname( __FILE__ ) . $build_css )
	);
}

// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'listicles_block_assets' );

function listicles_editor_assets() {
	$build_js = '/build/index.js';
	wp_enqueue_script(
		'listicles',
		plugins_url( $build_js, __FILE__ ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
		filemtime( dirname( __FILE__ ) . $build_js ),
		true
	);

	// Styles.
	$editor_css = '/build/index.css';
	wp_enqueue_style(
		'listicles-editor',
		plugins_url( $editor_css, __FILE__ ),
		array( 'wp-edit-blocks' ),
		filemtime( dirname( __FILE__ ) . $editor_css )
	);
}

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'listicles_editor_assets' );
