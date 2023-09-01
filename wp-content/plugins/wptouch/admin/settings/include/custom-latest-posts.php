<?php
	global $latest_post_options;
	$settings = foundation_get_settings();

	ob_start();
	wp_dropdown_pages();
	$latest_post_options = ob_get_contents();
	ob_end_clean();

	$is_custom = ( $settings->latest_posts_page == 'none' ? ' selected' : '' );
	$latest_post_options = str_replace( "id='page_id'>", 'id="page_id"><option class="level-0" value="none"' . $is_custom . '>' . __( 'WordPress Reading Settings', 'wptouch-pro' ) . '</option>', $latest_post_options );
	$latest_post_options = str_replace( 'page_id', wptouch_admin_get_manual_encoded_setting_name( 'foundation', 'latest_posts_page' ), $latest_post_options );
	$value_string = 'value="' . $settings->latest_posts_page . '"';
	$latest_post_options = str_replace( $value_string, $value_string . ' selected', $latest_post_options );