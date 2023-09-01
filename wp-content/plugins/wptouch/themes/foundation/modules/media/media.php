<?php

add_action( 'foundation_module_init_mobile', 'foundation_media_init' );
add_action( 'wptouch_admin_page_render_wptouch-admin-theme-settings', 'foundation_media_settings' );

function foundation_media_init() {

	$settings = foundation_get_settings();
	if ( $settings->new_video_handling ) {

		// Load FitVids
		wp_enqueue_script(
			'foundation_media_fitvids',
			foundation_get_base_module_url() . '/media/fitvids.min.js',
			array( 'foundation_base' ),
			md5( FOUNDATION_VERSION ),
			true
		);

		wp_enqueue_script(
			'foundation_media_handling',
			foundation_get_base_module_url() . '/media/media.min.js',
			false,
			md5( FOUNDATION_VERSION ),
			true
		);

	}
}

function foundation_media_settings( $page_options ){
	wptouch_add_page_section(
		WPTOUCH_ADMIN_SETUP_COMPAT,
		__( 'Video Handling', 'wptouch-pro' ),
		'foundation-media-settings',
		array(
			wptouch_add_pro_setting(
				'checkbox',
				'new_video_handling',
				__( 'Use FitVids to automatically scale videos', 'wptouch-pro' ),
				__( 'FitVids is a small JavaScript helper that will detect videos from a variety of web sources and automatically scale them to fill the container and format them with the correct aspect ratio.', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC,
				'4.0'
			)
		),
		$page_options,
		FOUNDATION_SETTING_DOMAIN
	);

	return $page_options;
}
