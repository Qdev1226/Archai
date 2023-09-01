<?php

add_action( 'foundation_module_init_mobile', 'foundation_infinite_scroll_init' );

function foundation_infinite_scroll_init() {
	// Load JS
	wp_enqueue_script(
		'foundation_infinite_scroll',
		foundation_get_base_module_url() . '/infinite-scroll/infinite-scroll.min.js',
		array( 'jquery' ),
		md5( FOUNDATION_VERSION ),
		true
	);
}
