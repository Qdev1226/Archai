<?php

do_action( 'wptouch_functions_start' );

add_filter( 'wp_title', 'foundation_set_title' );

function foundation_set_title( $title ) {
	global $wptouch_pro;
	if ( $wptouch_pro->showing_mobile_theme ) {
		return $title . ' ' . wptouch_get_bloginfo( 'site_title' );
	} else {
		return $title;
	}
}


