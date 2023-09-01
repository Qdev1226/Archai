<?php

function wptouch_should_mobile_cache_page() {
	global $wptouch_pro;

	return $wptouch_pro->is_mobile_device;
}

function wptouch_cache_get_key() {
	$cache_key = 'wptouch_pro';

	// Add the active device class
	$cache_key = $cache_key . '_device_class_' . $wptouch_pro->$active_device_class;

	// Add the value of the user's cookie
	if ( isset( $_COOKIE[ WPTOUCH_COOKIE ] ) ) {
		$cache_key = $cache_key . '_cookie_' .  sanitize_key( wp_unslash( $_COOKIE[ WPTOUCH_COOKIE ] ) );
	}

	return md5( $cache_key );
}

function wptouch_cache_get_mobile_user_agents() {
	global $wptouch_pro;

	$user_agents = $wptouch_pro->get_supported_user_agents();
}

/**
 * Checks for a WPtouch option regarding whether or not the mobile site should be cached.
 *
 * If the page isn't supposed to be cached, add headers to the request that signal to the caching system
 * not to cache this page.
 */
function wptouch_maybe_add_no_cache_headers_to_request() {
	$settings = wptouch_get_settings();

	// Check to be sure this hasn't been disabled.
	if ( 1 === $settings->disable_no_cache_request_headers ) {
		return;
	}

	wptouch_request_no_caching_when_mobile_theme_showing();
}
add_action( 'wptouch_mobile_theme_showing', 'wptouch_maybe_add_no_cache_headers_to_request' );

/**
 * Adds headers to the request which should prevent systems from caching this page.
 */
function wptouch_request_no_caching_when_mobile_theme_showing() {
	$ts = gmdate('D, d M Y H:i:s') . ' GMT';
	header("Expires: $ts");
	header("Last-Modified: $ts");
	header('Pragma: no-cache');
	header('Cache-Control: no-cache, must-revalidate, maxage=0');
}
