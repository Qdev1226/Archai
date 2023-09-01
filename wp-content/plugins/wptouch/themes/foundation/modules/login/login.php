<?php

// Actions
add_filter( 'wptouch_foundation_menu_options', 'foundation_login_settings' );

function foundation_login_settings( $menu_settings ) {
	$menu_settings[] = wptouch_add_setting(
		'checkbox',
		'show_login_box',
		__( 'Show login in menu', 'wptouch-pro' ),
		__( 'Will add login/out links', 'wptouch-pro' ),
		WPTOUCH_SETTING_BASIC,
		'2.0'
	);

	return $menu_settings;
}

// Can be used in themes (public)
function wptouch_fdn_show_login(){
	$settings = wptouch_get_settings();
	if ( $settings->show_login_box ) {
		return true;
	} else {
		return false;
	}
}

function wptouch_fdn_show_login_links(){
	$settings = foundation_get_settings();
	if ( apply_filters( 'wptouch_show_login_links', $settings->show_login_links ) ) {
		return true;
	} else {
		return false;
	}
}
