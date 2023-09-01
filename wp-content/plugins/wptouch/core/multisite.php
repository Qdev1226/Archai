<?php

if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

function wptouch_is_controlled_network() {
	$bncid_info = wptouch_get_settings( 'bncid' );

	return ( is_plugin_active_for_network( WPTOUCH_PLUGIN_SLUG ) && $bncid_info->multisite_control && !defined( 'WPTOUCH_IS_FREE' ) );
}

function wptouch_is_multisite_enabled() {
	return is_multisite();
}

function wptouch_is_multisite_primary() {
	global $blog_id;
	return ( $blog_id == 1 );
}

function wptouch_is_multisite_secondary() {
	if ( wptouch_is_multisite_enabled() ) {
		global $blog_id;

		return ( $blog_id > 1 );
	} else {
		return false;
	}
}

function wptouch_get_multsite_aware_install_path( $suffix ) {
	if ( wptouch_is_controlled_network() ) {
		return WPTOUCH_BASE_CONTENT_MS_DIR . '/' . $suffix;
	} else {
		return WPTOUCH_BASE_CONTENT_DIR . '/' . $suffix;
	}
}

function wptouch_get_multsite_aware_content_url( $suffix ) {
	if ( wptouch_is_controlled_network() ) {
		return WPTOUCH_BASE_CONTENT_MS_URL . '/' . $suffix;
	} else {
		return WPTOUCH_BASE_CONTENT_URL . '/' . $suffix;
	}
}

function wptouch_is_network_available( $type, $check_product = false ) {
	if ( $type == 'theme' && !$check_product ) {
		global $wptouch_cur_theme;
		$check_product = $wptouch_cur_theme;
	} elseif ( !$check_product ) {
		global $wptouch_cur_addon;
		$check_product = $wptouch_cur_addon;
	}

	if ( $check_product ) {
		if ( !is_multisite() ) {
			return true;
		} else {
			if ( $check_product->location != 'cloud' ) {
				return true;
			} elseif ( !wptouch_is_controlled_network() || ( wptouch_is_controlled_network() && is_network_admin() ) ) {
				return true;
			}

			return false;
		}
	}

	return false;
}

define( 'ADDON_MULTISITE_OPTIONS_PAGENAME', 'Multisite' );
define( 'ADDON_MULTISITE_DEPLOYMENT_PAGENAME', 'Deployment' );
define( 'WPTOUCH_MULTISITE_DOMAIN', 'multisite' );

if ( !defined( 'WPTOUCH_MULTISITE_LIMIT' ) ) {
	define( 'WPTOUCH_MULTISITE_LIMIT', 1000 );
}

if ( is_multisite() ) {
	add_filter( 'wptouch_admin_page_render_wptouch-multisite', 'wptouch_addon_multisite_render_settings' );
	add_filter( 'wptouch_can_show_page', 'wptouch_multisite_can_show_page', 10, 2 );
}

function wptouch_multisite_page_classes( $page ) {
	$value = wptouch_multisite_can_show_page_internal( $page, false );

	if ( !$value ) {
		echo 'should-hide';
	}
}

function wptouch_multisite_can_show_page_internal( $page_name, $default ) {
	$value = $default;

	$settings = wptouch_get_settings( 'multisite' );

	switch( $page_name ) {
		case 'general':
			$value = $settings->multisite_show_general_settings;
			break;
		case 'site-compat':
			$value = $settings->multisite_show_site_compat;
			break;
		case 'devices':
			$value = $settings->multisite_show_devices;
			break;
		case 'menu':
			$value = $settings->multisite_show_menus;
			break;
		case 'themes':
			$value = $settings->multisite_show_themes;
			break;
		case 'extensions':
			$value = $settings->multisite_show_extensions;
			break;
		case 'colors':
			$value = $settings->multisite_show_colors;
			break;
		case 'social_sharing':
			$value = $settings->multisite_show_social_sharing;
			break;
		case 'social_media':
			$value = $settings->multisite_show_social_media;
			break;
	}

	return $value;
}

function wptouch_multisite_can_show_page( $current_value, $page_name ) {
	$value = $current_value;

	if ( is_network_admin() ) {
		return true;
	}

	$value = wptouch_multisite_can_show_page_internal( $page_name, $value );

	return $value || current_user_can( 'manage_network' );
}

function wptouch_multisite_get_site_array() {
	$sites = array();

	if ( false === ( $sites = get_transient( 'wptouch_multisite_site_info' ) ) ) {
		$wp_sites = wp_get_sites( array( 'limit' => WPTOUCH_MULTISITE_LIMIT ) );
		foreach( $wp_sites as $site ) {
			$site_details = get_blog_details( $site[ 'blog_id' ] );
			$sites[ $site[ 'blog_id' ] ] = $site_details->blogname;
		}

		set_transient( 'wptouch_multisite_site_info', $sites, 300 );
	}

	return $sites;
}

function wptouch_has_multisite_support_licensed() {
	if ( defined( 'WPTOUCH_IS_FREE' ) ) {
		return false;
	} else {
		$bncid_settings = wptouch_get_settings( 'bncid' );

		return ( in_array( 'multisite-support', $bncid_settings->license_support_packages ) );
	}
}

function wptouch_addon_multisite_render_settings( $page_options ) {
	wptouch_add_sub_page(
		ADDON_MULTISITE_OPTIONS_PAGENAME,
		'wptouch-addon-multisite',
		$page_options
	);

	wptouch_add_page_section(
		ADDON_MULTISITE_OPTIONS_PAGENAME,
		__( 'General Options', 'wptouch-pro' ),
		'mutisite-setup',
		array(
			wptouch_add_setting(
				'list',
				'force_network_locale',
				__( 'Admin Language', 'wptouch-pro' ),
				false,
				WPTOUCH_SETTING_BASIC,
				'3.0',
				wptouch_admin_get_languages(),
				'wptouch_pro'
			),
			wptouch_add_setting(
				'checkbox',
				'multisite_control',
				__( 'Control themes and extension downloads and updates', 'wptouch-pro' ),
				__( 'If enabled, sub-sites can only activate/deactivate themes and extensions that have been downloaded in the network admin.', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC,
				'4.0',
				false,
				'bncid'
			)
		),
		$page_options
	);

	if ( wptouch_has_multisite_support_licensed() ) {
		wptouch_add_sub_page(
			ADDON_MULTISITE_DEPLOYMENT_PAGENAME,
			'wptouch-addon-deployment',
			$page_options
		);

		wptouch_add_page_section(
			ADDON_MULTISITE_OPTIONS_PAGENAME,
			__( 'Network Defaults', 'wptouch-pro' ),
			'multisite-options',
			array(
				wptouch_add_pro_setting(
					'checkbox',
					'multisite_use_master_settings',
					__( 'Use my settings for new sites', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0'
				),
				wptouch_add_pro_setting(
					'list',
					'multisite_master_site',
					__( 'Source site', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0',
					wptouch_multisite_get_site_array()
				),
			),
			$page_options,
			WPTOUCH_MULTISITE_DOMAIN,
			false,
			__( 'Configure WPtouch to use a site for defaults on new network sites instead of its defaults', 'wptouch-pro' )
		);

		wptouch_add_page_section(
			ADDON_MULTISITE_OPTIONS_PAGENAME,
			__( 'Subsite Customizable Settings', 'wptouch-pro' ),
			'multisite-options',
			array(
				wptouch_add_pro_setting(
					'checkbox',
					'multisite_show_general_settings',
					__( 'General Settings', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'multisite_show_site_compat',
					__( 'Site Compatibility', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'multisite_show_devices',
					__( 'Devices', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'multisite_show_menus',
					__( 'Menu Settings', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'multisite_show_themes',
					__( 'Themes', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'multisite_show_extensions',
					__( 'Extensions', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'multisite_show_colors',
					__( 'Colors', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'multisite_show_social_media',
					__( 'Social Media Links', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'multisite_show_social_sharing',
					__( 'Social Sharing', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0'
				),
			),
			$page_options,
			WPTOUCH_MULTISITE_DOMAIN,
			false,
			__( 'Select which settings sections are available to users in network subsites (network admins always see settings).', 'wptouch-pro' )
		);

		wptouch_add_page_section(
			ADDON_MULTISITE_DEPLOYMENT_PAGENAME,
			__( 'Source Site', 'wptouch-pro' ),
			'multisite-deployment-source',
			array(
				wptouch_add_pro_setting(
					'list',
					'multisite_deployment_source',
					__( 'Source site', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0',
					wptouch_multisite_get_site_array()
				),
				wptouch_add_pro_setting(
					'checkbox',
					'multisite_deploy_general_settings',
					__( 'General Settings', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'multisite_deploy_site_compat',
					__( 'Site Compatibility', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'multisite_deploy_devices',
					__( 'Devices', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'multisite_deploy_menus',
					__( 'Menu Settings', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'multisite_deploy_themes',
					__( 'Themes', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'multisite_deploy_extensions',
					__( 'Extensions', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'multisite_deploy_colors',
					__( 'Colors', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'multisite_deploy_social_media',
					__( 'Social Media Links', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0'
				),
				wptouch_add_pro_setting(
					'checkbox',
					'multisite_deploy_social_sharing',
					__( 'Social Sharing', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0'
				),
			),
			$page_options,
			WPTOUCH_MULTISITE_DOMAIN
		);

		wptouch_add_page_section(
			ADDON_MULTISITE_DEPLOYMENT_PAGENAME,
			__( 'Destination Sites', 'wptouch-pro' ),
			'multisite-deployment-sites',
			array(
				wptouch_add_pro_setting(
					'sites',
					'multisite_sites',
					'',
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0.0'
				)
			),
			$page_options,
			WPTOUCH_MULTISITE_DOMAIN
		);
	}

	return $page_options;
}



