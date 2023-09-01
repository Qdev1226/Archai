<?php

global $wptouch_themes;
global $wptouch_cur_theme;

global $wptouch_theme_item;
global $wptouch_theme_iterator;

function wptouch_rewind_themes() {
	global $wptouch_themes;
	$wptouch_themes = false;
}

function wptouch_has_themes( $include_cloud = false ) {
	global $wptouch_pro;
	global $wptouch_theme_iterator;

	if ( !$wptouch_theme_iterator ) {
		$wptouch_themes = $wptouch_pro->get_available_themes( $include_cloud );
		$wptouch_theme_iterator = new WPtouchArrayIterator( $wptouch_themes );
	}

	return $wptouch_theme_iterator->have_items();
}

function wptouch_is_theme_in_cloud() {
	global $wptouch_cur_theme;

	return ( isset( $wptouch_cur_theme->location ) && ( $wptouch_cur_theme->location == 'cloud' ) );
}

function wptouch_theme_type() {
	global $wptouch_cur_theme;

	if ( isset( $wptouch_cur_theme->theme_type ) ) {
		return $wptouch_cur_theme->theme_type;
	} else {
		return false;
	}
}

function wptouch_theme_info_url() {
	global $wptouch_cur_theme;

	$url = 'http://wptouch.com/themes/' . $wptouch_cur_theme->base . '/' . '?utm_source=' . WPTOUCH_UTM_SOURCE . '&utm_campaign=theme-browser-' . $wptouch_cur_theme->base . '&utm_medium=web';

	return $url;
}

function wptouch_cloud_theme_update_available() {
	global $wptouch_cur_theme;

	return ( !wptouch_is_theme_in_cloud() && isset( $wptouch_cur_theme->theme_upgrade_available ) && $wptouch_cur_theme->theme_upgrade_available );
}

function wptouch_cloud_theme_get_update_version() {
	global $wptouch_cur_theme;

	return $wptouch_cur_theme->cloud_version;
}

function wptouch_the_theme() {
	global $wptouch_theme_iterator;
	global $wptouch_cur_theme;

	$wptouch_cur_theme = $wptouch_theme_iterator->the_item();
	return apply_filters( 'wptouch_theme', $wptouch_cur_theme );
}

function wptouch_the_theme_classes( $extra_classes = array() ) {
	echo implode( ' ', wptouch_get_theme_classes( $extra_classes ) ) ;
}

function wptouch_get_theme_classes( $extra_classes = array() ) {

	$classes = $extra_classes;

	if ( !is_network_admin() && wptouch_is_theme_active() && !defined( 'WPTOUCH_IS_FREE' ) ) {
		$classes[] = 'active';
	}

	if ( wptouch_is_theme_in_cloud() || defined( 'WPTOUCH_IS_FREE' ) ) {
		$classes[] = 'cloud';
	}

	if ( wptouch_has_theme_tags() ) {
		$tags = wptouch_get_theme_tags();
		foreach( $tags as $tag ) {
			$classes[] = $tag;
		}
	}

	$classes[] = 'name-' . wptouch_convert_to_class_name( wptouch_get_theme_title() );

	return $classes;
}

function wptouch_has_theme_tags() {
	global $wptouch_cur_theme;

	return ( isset( $wptouch_cur_theme->tags ) && count( (array) $wptouch_cur_theme->tags ) );
}

function wptouch_get_theme_tags() {
	global $wptouch_cur_theme;

	return apply_filters( 'wptouch_theme_tags', $wptouch_cur_theme->tags );
}

function wptouch_trim_trailing_slashes( $str ) {
	return trim( $str, DIRECTORY_SEPARATOR );
}

function wptouch_is_theme_active() {
	global $wptouch_pro;
	global $wptouch_cur_theme;

	$settings = $wptouch_pro->get_settings();

	$current_theme_location = $settings->current_theme_location . DIRECTORY_SEPARATOR . $settings->current_theme_name;

	return ( wptouch_trim_trailing_slashes( $wptouch_cur_theme->location ) == wptouch_trim_trailing_slashes( $current_theme_location ) );
}

function wptouch_active_theme_has_settings() {
	$menu = apply_filters( 'wptouch_theme_menu', array() );
	return count( (array) $menu );
}

function wptouch_is_theme_child() {
	global $wptouch_cur_theme;
	return ( isset( $wptouch_cur_theme->parent_theme ) && strlen( $wptouch_cur_theme->parent_theme ) );
}

function wptouch_the_theme_version() {
	echo wptouch_get_theme_version();
}

function wptouch_get_theme_parent() {
	global $wptouch_cur_theme;
	return $wptouch_cur_theme->parent_theme;
}

function wptouch_get_theme_version() {
	global $wptouch_cur_theme;
	if ( $wptouch_cur_theme ) {
		return apply_filters( 'wptouch_theme_version', $wptouch_cur_theme->version );
	}

	return false;
}

function wptouch_the_theme_title() {
	echo wptouch_get_theme_title();
}

function wptouch_get_theme_title() {
	global $wptouch_cur_theme;
	if ( $wptouch_cur_theme ) {
		return apply_filters( 'wptouch_theme_title', $wptouch_cur_theme->name );
	}

	return false;
}

function wptouch_the_theme_location() {
	echo wptouch_get_theme_location();
}

function wptouch_get_theme_location() {
	global $wptouch_cur_theme;
	if ( $wptouch_cur_theme ) {
		return apply_filters( 'wptouch_theme_location', $wptouch_cur_theme->location );
	}

	return false;
}

function wptouch_get_theme_url() {
	return content_url() . wptouch_get_theme_location();
}

function wptouch_the_theme_url() {
	echo wptouch_get_theme_url();
}

function wptouch_the_theme_features() {
	echo implode( wptouch_get_theme_features(), ', ' );
}

function wptouch_get_theme_features() {
	global $wptouch_cur_theme;
	return apply_filters( 'wptouch_theme_features', $wptouch_cur_theme->features );
}

function wptouch_theme_has_features() {
	global $wptouch_cur_theme;
	return $wptouch_cur_theme->features;
}

function wptouch_the_theme_author() {
	echo wptouch_get_theme_author();
}

function wptouch_get_theme_author() {
	global $wptouch_cur_theme;
	if ( $wptouch_cur_theme ) {
		return apply_filters( 'wptouch_theme_author', $wptouch_cur_theme->author );
	}

	return false;
}

function wptouch_the_theme_description() {
	echo wptouch_get_theme_description();
}

function wptouch_get_theme_description() {
	global $wptouch_cur_theme;
	if ( $wptouch_cur_theme ) {
		return apply_filters( 'wptouch_theme_description', $wptouch_cur_theme->description );
	}

	return false;
}

function wptouch_the_theme_icon() {
	echo wptouch_get_theme_icon();
}

function wptouch_get_theme_icon() {
	global $wptouch_cur_theme;
	if ( $wptouch_cur_theme ) {
		$wptouch_theme_icon = wptouch_check_url_ssl( $wptouch_cur_theme->screenshot );
		return apply_filters( 'wptouch_theme_screenshot', $wptouch_theme_icon );
	}

	return false;
}


function wptouch_the_theme_base() {
	echo wptouch_get_theme_base();
}

function wptouch_get_theme_base() {
	global $wptouch_cur_theme;
	if ( $wptouch_cur_theme ) {
		return apply_filters( 'wptouch_theme_base', $wptouch_cur_theme->base );
	}

	return false;
}

function wptouch_the_theme_download_url() {
	echo wptouch_get_theme_download_url();
}

function wptouch_get_theme_download_url() {
	global $wptouch_cur_theme;
	if ( $wptouch_cur_theme ) {
		return $wptouch_cur_theme->download_url;
	}

	return false;
}

function wptouch_get_theme_buy_url() {
	global $wptouch_cur_theme;

	if ( $wptouch_cur_theme && isset( $wptouch_cur_theme->buy_url ) ) {
		return $wptouch_cur_theme->buy_url;
	}

	return false;
}

function wptouch_the_theme_buy_url() {
	echo wptouch_get_theme_buy_url();
}

function wptouch_the_theme_activate_link_url() {
	echo wptouch_get_theme_activate_link_url();
}

function wptouch_get_theme_activate_link_url() {
	return esc_url( add_query_arg( array(
		'admin_command' => 'activate_theme',
		'theme_name' => urlencode( wptouch_get_theme_title() ),
		'theme_location' => urlencode( wptouch_get_theme_location() ),
		'admin_menu_nonce' => wptouch_admin_menu_get_nonce()
	) ) );
}

function wptouch_the_theme_copy_link_url() {
	echo wptouch_get_theme_copy_link_url();
}

function wptouch_get_theme_copy_link_url() {
	return esc_url( add_query_arg( array(
		'admin_command' => 'copy_theme',
		'theme_name' => urlencode( wptouch_get_theme_title() ),
		'theme_location' => urlencode( wptouch_get_theme_location() ),
		'admin_menu_nonce' => wptouch_admin_menu_get_nonce()
	) ) );
}

function wptouch_the_theme_delete_link_url() {
	echo wptouch_get_theme_delete_link_url();
}

function wptouch_get_theme_delete_link_url() {
	return esc_url( add_query_arg( array(
		'admin_command' => 'delete_theme',
		'theme_name' => urlencode( wptouch_get_theme_title() ),
		'theme_location' => urlencode( wptouch_get_theme_location() ),
		'admin_menu_nonce' => wptouch_admin_menu_get_nonce()
	) ) );
}

function wptouch_the_theme_changelog() {
	echo wptouch_get_theme_changelog();
}

function wptouch_get_theme_changelog() {
	global $wptouch_cur_theme;
	return $wptouch_cur_theme->changelog;
}

function wptouch_the_theme_long_desc() {
	echo wptouch_get_theme_long_desc();
}

function wptouch_get_theme_long_desc() {
	global $wptouch_cur_theme;
	return wpautop( $wptouch_cur_theme->long_description );
}

global $wptouch_theme_screenshots;
global $wptouch_theme_screenshot_item;
global $wptouch_theme_screenshot_iterator;

function wptouch_get_theme_screenshots() {
	global $wptouch_cur_theme;
	if ( isset( $wptouch_cur_theme->preview_images ) ) {
		return $wptouch_cur_theme->preview_images;
	} else {
		return array();
	}
}

function wptouch_has_theme_screenshots() {
	global $wptouch_theme_screenshot_iterator;
	global $wptouch_theme_screenshots;

	if ( !$wptouch_theme_screenshot_iterator ) {
		$wptouch_theme_screenshots = wptouch_get_theme_screenshots();
		$wptouch_theme_screenshot_iterator = new WPtouchArrayIterator( $wptouch_theme_screenshots );
	}

	return $wptouch_theme_screenshot_iterator->have_items();
}

function wptouch_the_theme_screenshot() {
	global $wptouch_theme_screenshot_iterator;
	global $wptouch_theme_screenshot_item;

	$wptouch_theme_screenshot_item = wptouch_check_url_ssl( $wptouch_theme_screenshot_iterator->the_item() );

	return apply_filters( 'wptouch_theme_screenshot', $wptouch_theme_screenshot_item );
}

function wptouch_get_theme_screenshot_num() {
	global $wptouch_theme_screenshot_iterator;

	return $wptouch_theme_screenshot_iterator->current_position();
}

function wptouch_is_first_theme_screenshot() {
	return ( wptouch_get_theme_screenshot_num() == 1 );
}

function wptouch_get_theme_screenshot_url() {
	global $wptouch_theme_screenshot_item;
	return $wptouch_theme_screenshot_item;
}

function wptouch_the_theme_screenshot_url() {
	echo wptouch_get_theme_screenshot_url();
}

function wptouch_reset_theme_screenshot() {
	global $wptouch_theme_screenshot_iterator;

	$wptouch_theme_screenshot_iterator = false;
}
