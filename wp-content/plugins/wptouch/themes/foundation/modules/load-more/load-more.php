<?php

add_action( 'foundation_module_init_mobile', 'foundation_load_more_init' );

function foundation_load_more_init() {
	// Load JS
	wp_enqueue_script(
		'foundation_load_more',
		foundation_get_base_module_url() . '/load-more/load-more.min.js',
		array( 'foundation_spinjs_jquery' ),
		md5( FOUNDATION_VERSION ),
		true
	);
}
