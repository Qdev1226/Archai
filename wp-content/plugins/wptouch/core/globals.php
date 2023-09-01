<?php

if ( !defined( 'WPTOUCH_IS_FREE' ) ) {
	require_once( WPTOUCH_DIR . '/pro/professional.php' );
}

define( 'WPTOUCH_PRO_DESKTOP_FCN_CACHE_TIME', 3600 );
define( 'WPTOUCH_PRO_LIVE_PREVIEW_SETTING', 1 );

add_filter( 'wptouch_modify_setting__compat__enabled_plugins', 'wptouch_modify_enabled_plugins' );

require_once( WPTOUCH_DIR . '/core/class-array-iterator.php' );
require_once( WPTOUCH_DIR . '/core/multisite.php' );

function wptouch_is_mobile_theme_showing() {
	global $wptouch_pro;

	return ( $wptouch_pro->is_mobile_device && $wptouch_pro->showing_mobile_theme );
}

function wptouch_locate_template( $param1, $param2, $param3, $param4 = false, $param5 = false ) {
	$template_path = false;
	$current_path = false;
	$require_once = true;

	if ( $param4 ) {
		if ( $param5 ) {
			// 5 parameters
			$template_path = $param4;
			$current_path = $param5;
			$require_once = $param3;
		} else {
			// 4 parameters
			$template_path = $param3;
			$current_path = $param4;
		}
	} else {
		// 3 parameters
		$template_path = $param2;
		$current_path = $param3;
	}

	$template_file = $template_path . '/' . $param1;
	if ( !file_exists( $template_file ) ) {
		$template_file = $current_path . '/' . $param1;
	}

	if ( file_exists( $template_path ) ) {
		global $wptouch_pro;

		require_once( WPTOUCH_DIR . '/core/desktop-functions.php' );

		$current_path = dirname( $template_file );
		if ( $require_once ) {
			wptouch_include_functions_file( $wptouch_pro, $template_file, $template_path, $current_path, 'require_once' );
		} else {
			wptouch_include_functions_file( $wptouch_pro, $template_file, $template_path, $current_path, 'require' );
		}
	}
}

function wptouch_strip_dashes( $str ) {
	return str_replace( '-', '_', $str );
}

function wptouch_convert_to_class_name( $class_to_convert ) {
	return str_replace( array( ' ', '"', '.', '\'', '#', '(', ')' ), array( '-', '', '-', '', '', '-', '-' ), strtolower( $class_to_convert ) );
}

function wptouch_do_template( $template_name ) {
	global $wptouch_pro;
	$template_path = $wptouch_pro->get_current_theme_directory() . '/' . $wptouch_pro->get_active_device_class() . '/' . $template_name;
	$directories = array( TEMPLATEPATH );
	if ( $wptouch_pro->is_child_theme() ) {
		array_unshift( $directories, STYLESHEETPATH );
	}

	foreach( $directories as $dir ) {
		if ( file_exists( $dir . '/' . $template_name ) ) {
			include( $dir . '/' . $template_name );
			return true;
		}
	}

	return false;
}

function wptouch_modify_enabled_plugins( $enabled_list ) {
	$new_list = array();

	foreach( $enabled_list as $key => $value ) {
		$new_list[ $value ] = 1;
	}

	$settings = wptouch_get_settings( 'compat' );
	if ( isset( $settings->plugin_hooks ) ) {
		foreach( $settings->plugin_hooks as $name => $value ) {
			if ( !array_key_exists( $name, $new_list ) ) {
				$new_list[ $name ] = 0;
			}
		}
	}

	return $new_list;
}

function wptouch_is_device_real_ipad() {
	if ( isset($_SERVER[ 'HTTP_USER_AGENT' ])) {
		return ( stripos( sanitize_text_field(wp_unslash($_SERVER[ 'HTTP_USER_AGENT' ])), 'ipad' ) !== false );
	}
}

function wptouch_capture_template_part( $file_name ) {
	ob_start();
	get_template_part( $file_name );
	$contents = ob_get_contents();
	ob_end_clean();

	return $contents;
}

function wptouch_capture_include_file( $file_name ) {
	ob_start();
	require( $file_name );
	$contents = ob_get_contents();
	ob_end_clean();

	return $contents;
}

function wptouch_get_supported_user_agents() {
	global $wptouch_pro;
	return $wptouch_pro->get_supported_user_agents();
}

function wptouch_is_showing_mobile_theme_on_mobile_device() {
	global $wptouch_pro;

	return $wptouch_pro->is_showing_mobile_theme_on_mobile_device();
}

function wptouch_save_settings( $settings, $domain = 'wptouch_pro' ) {
	global $wptouch_pro;

	$wptouch_pro->save_settings( $settings, $domain );
}

function wptouch_get_settings( $domain = 'wptouch_pro', $clone_it = true ) {
	global $wptouch_pro;

	return $wptouch_pro->get_settings( $domain, $clone_it );
}

function wptouch_get_quick_setting_value( $domain, $name ) {
	global $wptouch_pro;

	// Check to see if we have the object already loaded
	if ( !isset( $wptouch_pro->settings_objects[ $domain ] ) ) {
		wptouch_get_settings( $domain, false );
	}

	return $wptouch_pro->settings_objects[ $domain ]->$name;
}

function wptouch_cron_backup_settings() {
	require_once( WPTOUCH_DIR . '/core/admin-backup-restore.php' );

	wptouch_backup_settings();
}

function wptouch_show_desktop_switch_link() {
	$switch_html = wptouch_capture_include_file( WPTOUCH_DIR . '/include/html/desktop-switch.php' );
	echo wp_kses_post(apply_filters( 'wptouch_desktop_switch_html', $switch_html ));
}

function wptouch_split_string( $str, $chars ) {
	return substr( $str, 0, strrpos( substr( $str, 0, $chars ), ' ') );
}

function wptouch_rss_date( $rss_date ) {
	$date_time = strtotime( $rss_date );

	echo esc_html(date( 'F jS, Y', $date_time ));
}

function wptouch_in_preview() {
	return ( isset( $_GET['wptouch_preview_theme'] ) );
}

function wptouch_get_translated_device_type( $tag ) {
	if ( $tag == 'smartphone' ) {
		return esc_html(__( 'smartphone', 'wptouch-pro' ));
	} else if ( $tag == 'tablet' ) {
		return esc_html(__( 'tablet', 'wptouch-pro' ));
	}
}

function wptouch_desktop_switch_link( $echo_result = true ) {
	$link = wptouch_get_desktop_switch_link();

	if ( $echo_result ) {
		echo esc_url($link);
	} else {
		return $link;
	}
}

function wptouch_should_show_desktop_switch_link() {
	global $wptouch_pro;
	return ( $wptouch_pro->is_mobile_device && !$wptouch_pro->showing_mobile_theme );
}

function wptouch_the_desktop_switch_link() {
	echo esc_url(wptouch_get_desktop_switch_link());
}

function wptouch_get_desktop_switch_link() {
	global $wptouch_pro;
	return apply_filters( 'wptouch_desktop_switch_link', '?wptouch_switch=mobile' );
}

if ( defined( 'WPTOUCH_IS_FREE' ) ) {
	function wptouch_can_show_license_menu() {
		return false;
	}

	function wptouch_should_show_license_nag() {
		return false;
	}

	function wptouch_show_renewal_notice() {
		return false;
	}
}

function wptouch_admin_url( $url ) {
	if ( is_network_admin() ) {
		return network_admin_url( $url );
	} else {
		return admin_url( $url );
	}
}

function wptouch_is_site_licensed() {
	$settings = wptouch_get_settings( 'bncid' );
	return $settings->license_accepted;
}

function wptouch_should_show_activation_nag() {
	return wptouch_should_show_license_nag();
}

function wptouch_bloginfo( $setting_name ) {
	echo esc_html(wptouch_get_bloginfo( $setting_name ));
}

function wptouch_get_bloginfo( $setting_name ) {
	global $wptouch_pro;
	$settings = $wptouch_pro->get_settings();

	$setting = false;

	switch( $setting_name ) {
		case 'foundation_directory':
			$setting = WPTOUCH_DIR . '/themes/foundation';
			break;
		case 'foundation_url':
			$setting = WPTOUCH_URL . '/themes/foundation';
			break;
		case 'template_directory':
		case 'template_url':
			$setting = $wptouch_pro->get_template_directory_uri( false );
			break;
		case 'child_theme_directory_uri':
			$setting = $wptouch_pro->get_stylesheet_directory_uri( false );
			break;
		case 'theme_root_directory':
			$setting = $wptouch_pro->get_current_theme_directory();
			break;
		case 'theme_root_url':
			$setting = esc_url( $wptouch_pro->get_current_theme_uri() );
			break;
		case 'theme_parent_url':
			$setting = esc_url( $wptouch_pro->change_dir_to_url( $wptouch_pro->get_current_parent_location() ) );
			break;
		case 'site_title':
			if ( $settings->site_title != '' ) {
				$setting = esc_html( $settings->site_title );
			} else {
				$setting = esc_html( get_bloginfo( 'name' ) );
			}
			break;
		case 'wptouch_directory':
			$setting = WPTOUCH_DIR;
			break;
		case 'wptouch_url':
			$setting = WPTOUCH_URL;
			break;
		case 'version':
			$setting = WPTOUCH_VERSION;
			break;
		case 'theme_count':
			$themes = $wptouch_pro->get_available_themes();
			$setting = esc_html( count( (array) $themes ) );
			break;
		case 'icon_set_count':
			$icon_sets = $wptouch_pro->get_available_icon_packs();
			// Remove the custom icon count
			$setting = esc_html( count( (array) $icon_sets ) - 1 );
			break;
		case 'icon_count':
			$icon_sets = $wptouch_pro->get_available_icon_packs();
			$total_icons = 0;
			foreach( $icon_sets as $setname => $set ) {
				if ( $setname == "Custom Icons" ) continue;

				$icons = $wptouch_pro->get_icons_from_packs( $setname );
				$total_icons += count( (array) $icons );
			}
			$setting = esc_html( $total_icons );
			break;
		case 'support_licenses_remaining':
			$licenses = $wptouch_pro->bnc_api->user_list_licenses();
			if ( $licenses ) {
				$setting = esc_html( $licenses['remaining'] );
			} else {
				$setting = 0;
			}
			break;
		case 'support_licenses_total':
			$licenses = $wptouch_pro->bnc_api->get_total_licenses();
			if ( $licenses ) {
				$setting = esc_html( $licenses );
			} else {
				$setting = 0;
			}
			break;
		case 'active_theme_friendly_name':
			$theme_info = $wptouch_pro->get_current_theme_info();
			if ( $theme_info ) {
				$setting = esc_html( $theme_info->name );
			}
			break;
		case 'rss_url':
			if ( $settings->menu_custom_rss_url ) {
				$setting = esc_url( $settings->menu_custom_rss_url );
			} else {
				$setting = esc_url( get_bloginfo( 'rss2_url' ) );
			}
			break;
		case 'warnings':
			$setting = esc_url( wptouch_get_plugin_warning_count() );
			break;
		case 'url':
			if ( $settings->homepage_landing != 'none' ) {
				if ( $settings->homepage_landing == 'custom' ) {
					$setting = esc_url( $settings->homepage_redirect_custom_target );
				} else {
					$redirect_target = $settings->homepage_redirect_wp_target;
					if ( function_exists( 'icl_object_id' ) ) {
						$redirect_target = icl_object_id( $redirect_target, 'page', true );
					}
					$setting = esc_url( get_permalink( $redirect_target ) );
				}
			} else {
				$setting = esc_url( home_url() );
			}
			break;
		case 'search_url':
			if ( function_exists( 'home_url' ) ) {
				$setting = esc_url( home_url() );
			} else {
				$setting = esc_url( get_bloginfo( 'home' ) );
			}

			if ( $wptouch_pro->is_previewing_mobile_theme() ) {
				$setting = $setting . '?wptouch_preview_theme=enabled';
			}

			break;
		default:
			// proxy other values to the original get_bloginfo function
			$setting = get_bloginfo( $setting_name );
			break;
	}

	return $setting;
}

function wptouch_get_locale() {
	global $wptouch_pro;

	return $wptouch_pro->locale;
}

function wptouch_get_desktop_bloginfo( $param ) {
		switch( $param ) {
				case 'stylesheet_directory':
				case 'template_url':
				case 'template_directory':
					return content_url() . '/themes/' . get_option( 'template' );
				default:
					return get_bloginfo( $param );
		}
}

function wptouch_desktop_bloginfo( $param ) {
	echo wp_kses_post(wptouch_get_desktop_bloginfo( $param ));
}

function wptouch_can_cloud_install( $theme = true ) {
	global $wptouch_pro;
	return $wptouch_pro->can_perform_cloud_install( $theme );
//	return false; // for testing
}

function wptouchize_it( $str ) {
	if ( defined( 'WPTOUCH_IS_FREE' ) ) {
		return str_replace( 'WPtouch Pro', 'WPtouch', $str );
	} else {
		return $str;
	}
}

function wptouch_load_framework( $version = 2 ) {
	require_once( WPTOUCH_DIR . '/themes/foundation/root-functions.php' );
	require_once( WPTOUCH_DIR . '/themes/foundation/default/functions.php' );

	add_action( 'wp_enqueue_scripts', 'wptouch_foundation_load_framework_styles', 1 );
}

function wptouch_foundation_load_framework_styles() {
	wp_enqueue_style( 'foundation-framework-style', WPTOUCH_URL . '/themes/foundation/default/style.css', false, md5( WPTOUCH_VERSION ) );
}

function wptouch_return_false() {
	return false;
}

function wptouch_theme_version_compare( $required_version, $operator ) {
	// Example: wptouch_theme_version_compare( '4.0', '>=' ); will return true if current theme requires 4.0 or higher

	global $wptouch_pro;
	$current_theme = $wptouch_pro->get_current_theme_info();
	if ( isset( $current_theme->plugin_version ) && version_compare( $current_theme->plugin_version, $required_version, $operator ) ) {
		return true;
	} else {
		return false;
	}
}

function wptouch_admin_get_languages() {
	$languages = array(
		'auto' => __( 'Auto-detect', 'wptouch-pro' ),
		'en_US' => 'English',
		'fr_FR' => 'Français',
		'it_IT' => 'Italiano',
		'es_ES' => 'Español',
		'sv_SE' => 'Svenska',
		'de_DE' => 'Deutsch',
		'el' => 'ελληνικά',
		'da_DK' => 'Dansk',
		'pt' => 'Português',
		'nl_NL' => 'Nederlands',
		'hu' => 'Magyar',
		'id_ID' => 'Bahasa Indonesia',
		'he_IL' => 'עִבְרִית',
		'vi' => 'Tiếng Việt',
		'tr' => 'Türkçe',
		'ru_RU' => 'русский',
		'th' => 'ภาษาไทย',
		'ja_JP' => '日本語',
		'zh_CN' => '简体字',
		'zh_HK' => '繁體字',
		'ko_KR' => '한국어,조선말',
		'hi_IN' => 'मानक हिन्दी',
		'ar' => 'العربية/عربي'
	);

	return apply_filters( 'wptouch_admin_languages', $languages );
}

function wptouch_free_go_pro() {
	// Apply license
	global $wptouch_pro;

	$settings = $wptouch_pro->get_settings();
	$settings->upgrade_from_free = true;
	$settings->current_theme_friendly_name = false;
	$settings->save();

	$result = wptouch_free_upgrade_plugin();

	return $result;
}

function wptouch_free_upgrade_plugin() {
	global $wptouch_pro;
	$wptouch_pro->bnc_api = false;

	$settings = wptouch_get_settings( 'bncid' );
	$wptouch_pro->setup_bncapi( $settings->bncid, $settings->wptouch_license_key, true );
	$bnc_api = $wptouch_pro->get_bnc_api();

	$plugin_name = 'wptouch/wptouch.php';

	// Check for WordPress 3.0 function
	if ( function_exists( 'is_super_admin' ) ) {
		$option = get_site_transient( 'update_plugins' );
	} else {
		$option = function_exists( 'get_transient' ) ? get_transient( 'update_plugins' ) : get_option( 'update_plugins' );
	}

	$version_available = false;

	$latest_info = $bnc_api->get_product_version();

	if ( $latest_info ) {
		WPTOUCH_DEBUG( WPTOUCH_INFO, 'A new product update is available [' . $latest_info['version'] . ']' );

		if ( isset( $latest_info[ 'upgrade_url' ] ) && wptouch_has_license() ) {

			if ( !isset( $option->response[ $plugin_name ] ) ) {
				$option->response[ $plugin_name ] = new stdClass();
			}

			// Update upgrade options
			$option->response[ $plugin_name ]->url = 'http://www.wptouch.com/';
			$option->response[ $plugin_name ]->package = $latest_info[ 'upgrade_url' ];
			$option->response[ $plugin_name ]->new_version = $latest_info['version'];
			$option->response[ $plugin_name ]->id = '0';
			$option->response[ $plugin_name ]->slug = WPTOUCH_ROOT_NAME;
		} else {
			if ( is_object( $option ) && isset( $option->response ) ) {
				unset( $option->response[ $plugin_name ] );
			}
		}

		$wptouch_pro->latest_version_info = $latest_info;
		$upgrade_available = $latest_info[ 'version' ];
	} else {
		if ( is_object( $option ) && isset( $option->response ) ) {
			unset( $option->response[ $plugin_name ] );
		}
	}

	if ( isset( $option->response[ $plugin_name ] ) ) {
		// WordPress 3.0 changed some stuff, so we check for a WP 3.0 function
		if ( function_exists( 'is_super_admin' ) ) {
			set_site_transient( 'update_plugins', $option );
		} else if ( function_exists( 'set_transient' ) ) {
			set_transient( 'update_plugins', $option );
		}

		// Do Upgrade
		include_once( ABSPATH . 'wp-admin/includes/admin.php' );
		include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
		$upgrader = new Plugin_Upgrader( new Automatic_Upgrader_Skin() );
		$upgrader->upgrade( 'wptouch/wptouch.php' );

		if ( is_array( $upgrader->skin->result ) ) {
			deactivate_plugins( 'wptouch/wptouch.php' );
			$new_plugin_identifier = 'wptouch-pro/wptouch-pro.php';

			$active_plugins = get_option( 'active_plugins', array() );
			if ( !in_array( $new_plugin_identifier, $active_plugins ) ) {
				$active_plugins[] = $new_plugin_identifier;
				update_option( 'active_plugins', $active_plugins );
			}

			return '1';
		} else {
			return '0';
		}
	} else {
		return '0';
	}
}

function wptouch_can_show_page( $page_name ) {
	return apply_filters( 'wptouch_can_show_page', true, $page_name );
}

function wptouch_hex_to_rgb($hexStr, $returnAsString = false, $seperator = ',') {
	$hexStr = preg_replace( "/[^0-9A-Fa-f]/", '', $hexStr ); // Gets a proper hex string
	$rgbArray = array();
	if ( strlen( $hexStr ) == 6 ) { //If a proper hex code, convert using bitwise operation. No overhead... faster
		$colorVal = hexdec( $hexStr );
		$rgbArray[ 'red' ] = 0xFF & ( $colorVal >> 0x10 );
		$rgbArray[ 'green' ] = 0xFF & ( $colorVal >> 0x8 );
		$rgbArray[ 'blue' ] = 0xFF & $colorVal;
	} elseif ( strlen( $hexStr ) == 3 ) { //if shorthand notation, need some string manipulations
		$rgbArray[ 'red' ] = hexdec( str_repeat( substr( $hexStr, 0, 1 ), 2 ) );
		$rgbArray[ 'green' ] = hexdec( str_repeat( substr( $hexStr, 1, 1 ), 2 ) );
		$rgbArray[ 'blue' ] = hexdec( str_repeat( substr( $hexStr, 2, 1 ), 2 ) );
	} else {
		return false; //Invalid hex color code
	}
	return $returnAsString ? implode( $seperator, $rgbArray ) : $rgbArray; // returns the rgb string or the associative array
}

function wptouch_admin_use_customizer() {
	return !( defined( 'WPTOUCH_USE_CUSTOMIZER' ) && WPTOUCH_USE_CUSTOMIZER == false );
}