<?php

add_action( 'foundation_module_init_mobile', 'foundation_base_init' );

function foundation_base_get_script_deps() {
	$settings = foundation_get_settings();
	$script_deps = array( 'jquery' );

	if ( defined( 'WPTOUCH_MODULE_SPINJS_INSTALLED' ) ) {
		$script_deps[] = 'foundation_spinjs_jquery';
	}

	if ( defined( 'WPTOUCH_MODULE_FEATURED_INSTALLED' ) && $settings->featured_enabled ) {
		$script_deps[] = 'foundation_featured';
	}

	if ( defined( 'WPTOUCH_MODULE_MENU_INSTALLED' ) ) {
		$script_deps[] = 'foundation_menu';
	}

	if ( defined( 'WPTOUCH_MODULE_INFINITE_SCROLL_INSTALLED' ) ) {
		$script_deps[] = 'foundation_infinite_scroll';
	}

	$wam_allowed = apply_filters( 'wptouch_allow_wam', true );

	if ( defined( 'WPTOUCH_MODULE_WEBAPP_INSTALLED' ) && ( $wam_allowed ) ) {
		$script_deps[] = 'foundation_webapp';
	}

	return $script_deps;
}

function foundation_base_init() {
	wp_enqueue_script(
		'foundation_base',
		foundation_get_base_module_url() . '/base/base.min.js',
		foundation_base_get_script_deps(),
		md5( FOUNDATION_VERSION ),
		true
	);

	wp_enqueue_script(
		'foundation__public_base',
		foundation_get_base_module_url() . '/base/base-public.min.js',
		foundation_base_get_script_deps(),
		md5( FOUNDATION_VERSION ),
		true
	);

	// Only load preview script when we are in a preview window
	if ( wptouch_in_preview() ) {
		wp_enqueue_script(
			'foundation-preview',
			foundation_get_base_module_url() . '/base/wptouch-preview.min.js',
			array( 'foundation_base' ),
			md5( FOUNDATION_VERSION ),
			true
		);
	}

	// Themes can add their own localization, but Foundation-aware modules can use this hook
	$foundation_strings = array(
		'ajaxLoading' => __( 'Loading', 'wptouch-pro' ) . '…',
		'isRTL' => ( wptouch_should_load_rtl() ? '1' : '0' )
	);

	$foundation_localized_strings = apply_filters( 'foundation_localized_strings', $foundation_strings );
	if ( count( (array) $foundation_localized_strings ) ) {
		wp_localize_script( 'foundation_base', 'wptouchFdn', $foundation_localized_strings );
	}
}
