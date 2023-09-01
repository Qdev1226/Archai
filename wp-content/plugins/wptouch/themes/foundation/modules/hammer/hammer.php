<?php

add_action( 'foundation_module_init_mobile', 'foundation_hammer_init' );

function foundation_hammer_init() {
	wp_enqueue_script(
		'foundation_hammer',
		foundation_get_base_module_url() . '/hammer/hammer.min.js',
		array( 'jquery' ),
		md5( FOUNDATION_VERSION ),
		true
	);

	wp_enqueue_script(
		'foundation_hammer_wptouch',
		foundation_get_base_module_url() . '/hammer/wptouch.hammer.min.js',
		array( 'foundation_hammer' ),
		md5( FOUNDATION_VERSION ),
		true
	);
}
