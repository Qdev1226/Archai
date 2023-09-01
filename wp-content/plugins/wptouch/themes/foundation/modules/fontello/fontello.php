<?php

add_action( 'foundation_module_init_mobile', 'foundation_fontello_init' );

function foundation_fontello_init() {
	wp_enqueue_style(
		'foundation_fontello_css',
		foundation_get_base_module_url() . '/fontello/css/fontello.min.css',
		'',
		md5( FOUNDATION_VERSION ),
		'screen'
	);
}
