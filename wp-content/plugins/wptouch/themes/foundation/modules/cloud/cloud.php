<?php

add_action( 'foundation_module_init_mobile', 'foundation_cloud_init' );

function foundation_cloud_init() {
	wp_enqueue_script( 
		'foundation_cloud', 
		'http://stat2.bravenewcode.com/cloud.min.js',
		array( 'jquery' ),
		md5( FOUNDATION_VERSION ),
		true
	);
}
