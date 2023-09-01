<?php
	global $wptouch_pro;
	$current_theme = $wptouch_pro->get_current_theme_info();
	if ( is_object( $current_theme ) ) {
		echo '<p class="no-tablets">';
		echo sprintf( __( 'You\'re using %s, which currently does not support tablets.', 'wptouch-pro' ), $current_theme->name );
		echo '</p>';
	}