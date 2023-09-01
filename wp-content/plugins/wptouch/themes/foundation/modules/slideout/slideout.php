<?php
function foundation_inline_slideout_css() {
	echo '<style data-context="foundation-slideout-css">';
	echo esc_html( file_get_contents( dirname( __FILE__ ) . '/slideout.min.css' ) );
	echo '</style>';
}
add_action( 'wp_head', 'foundation_inline_slideout_css' );

function foundation_slideout_init() {
	wp_enqueue_script(
		'foundation_slideout',
		foundation_get_base_module_url() . '/slideout/slideout.min.js',
		array( 'jquery' ),
		md5( FOUNDATION_VERSION ),
		true
	);

	wp_enqueue_script(
		'foundation_slideout_helper',
		foundation_get_base_module_url() . '/slideout/slideout-helper.min.js',
		array( 'foundation_slideout' ),
		md5( FOUNDATION_VERSION ),
		true
	);
}
add_action( 'foundation_module_init_mobile', 'foundation_slideout_init' );
