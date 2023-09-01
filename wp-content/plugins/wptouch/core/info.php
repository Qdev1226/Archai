<?php

function wptouch_update_info() {
	global $wptouch_pro;

	if ( false === ( $value = get_transient( 'wptouch_update_info' ) ) ) {
		$url = false;
		$theme_info = $wptouch_pro->get_current_theme_info();

		if ( is_object( $theme_info ) ) {
			if ( defined( 'WPTOUCH_IS_FREE' ) && isset($_SERVER[ 'HTTP_HOST' ]) ) {
				$url = 'http://info.wptouch.com/?f=1&v=' . WPTOUCH_VERSION . '&t=' . $theme_info->base . '&tv=' . $theme_info->version . '&h=' . md5( sanitize_text_field(wp_unslash($_SERVER[ 'HTTP_HOST' ])) );
			} else {
				$url = 'http://info.wptouch.com/?f=0&v=' . WPTOUCH_VERSION . '&t=' . $theme_info->base . '&tv=' . $theme_info->version . '&h=' . md5( sanitize_text_field(wp_unslash($_SERVER[ 'HTTP_HOST' ])) );
			}

			wp_remote_get( $url );

			set_transient( 'wptouch_update_info', '1', 60*60*12 );
		}
	}
}