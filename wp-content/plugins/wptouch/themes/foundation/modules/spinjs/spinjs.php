<?php

add_action( 'foundation_module_init_mobile', 'foundation_spinjs_init' );

function foundation_spinjs_init() {
	// Load JS
	wp_enqueue_script(
		'foundation_spinjs',
		foundation_get_base_module_url() . '/spinjs/spin.min.js',
		array( 'jquery' ),
		md5( FOUNDATION_VERSION ),
		true
	);

	wp_enqueue_script(
		'foundation_spinjs_jquery',
		foundation_get_base_module_url() . '/spinjs/spin-jquery.min.js',
		array( 'foundation_spinjs' ),
		md5( FOUNDATION_VERSION ),
		true
	);
}
