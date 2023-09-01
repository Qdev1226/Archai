<?php

add_action( 'foundation_module_init_mobile', 'foundation_pushit_init' );

function foundation_pushit_init() {

	// PushIt CSS
	wp_register_style( 'foundation_pushit', foundation_get_base_module_url() . '/pushit/pushit.min.css' );

	wp_enqueue_style(
		'foundation_pushit',
		foundation_get_base_module_url() . '/pushit/pushit.min.css',
		'',
		md5( FOUNDATION_VERSION )
	);

	// PushIt JS
	wp_enqueue_script(
		'foundation_pushit',
		foundation_get_base_module_url() . '/pushit/pushit.min.js',
		array( 'jquery' ),
		md5( FOUNDATION_VERSION ),
		true
	);
}
