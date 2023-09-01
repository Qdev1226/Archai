<?php

add_action( 'foundation_module_init_mobile', 'foundation_wptouch_icons_old_init' );

function foundation_wptouch_icons_old_init() {
	wp_enqueue_style(
		'foundation_wptouch_icons_old_css',
		foundation_get_base_module_url() . '/wptouch-icons-old/css/wptouch-icons.css',
		'',
		md5( FOUNDATION_VERSION ),
		'screen'
	);
}