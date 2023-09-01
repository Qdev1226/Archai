<?php
add_action( 'admin_menu', 'wptouch_menu_redirects', 1 );
function wptouch_menu_redirects() {
	$settings = wptouch_get_settings();

	if ( defined( 'WPTOUCH_IS_FREE' ) ) {
		$show_wizard = $settings->show_free_wizard;
	} else {
		$show_wizard = $settings->show_wizard;
	}

	if ( $show_wizard && ( strstr( $_SERVER[ 'REQUEST_URI' ], '?page=wptouch-admin-general-settings' ) || strstr( $_SERVER[ 'REQUEST_URI' ], '?page=wptouch-admin-license' ) ) ) {
		wp_redirect( admin_url( 'admin.php?page=wptouch-admin-wizard' ) );
		die();
	} elseif ( !$show_wizard && !defined( 'WPTOUCH_SHOW_WIZARD' ) && strstr( $_SERVER[ 'REQUEST_URI' ], '?page=wptouch-admin-wizard' ) ) {
		wp_redirect( admin_url( 'admin.php?page=wptouch-admin-general-settings' ) );
		die();
	}
}

// All available built-in WPtouch Pro menu items go here
define( 'WPTOUCH_PRO_ADMIN_WIZARD', 'wptouch-admin-wizard' );
define( 'WPTOUCH_PRO_ADMIN_GENERAL_SETTINGS', 'wptouch-admin-general-settings' );
define( 'WPTOUCH_PRO_ADMIN_MULTISITE_SETTINGS', 'wptouch-multisite' );
define( 'WPTOUCH_PRO_ADMIN_LICENSE', 'wptouch-admin-license' );
define( 'WPTOUCH_PRO_ADMIN_GO_PRO', 'wptouch-admin-go-pro' );

function wptouch_admin_create_menu( $id, $friendly_name, $menu_type = WPTOUCH_PRO_ADMIN_SETTINGS_PAGE, $display_name = false ) {
	$menu = new stdClass;

	$menu->slug = $id;
	$menu->friendly_name = $friendly_name;
	$menu->menu_type = $menu_type;
	$menu->display_name = $display_name;

	return $menu;
}

function wptouch_admin_get_predefined_menus( $network_admin = false ) {
	$available_menus = array();
	$settings = wptouch_get_settings();
	if ( defined( 'WPTOUCH_IS_FREE' ) ) {
		$show_wizard = $settings->show_free_wizard;
	} else {
		$show_wizard = $settings->show_wizard;
	}
	$show_network_wizard = $settings->show_network_wizard;
	$wizard_title = __( 'Setup Wizard', 'wptouch-pro' );
	$settings_title = __( 'Settings', 'wptouch-pro' );
	$license_title = sprintf( __( 'License %s Support', 'wptouch-pro' ), '&amp;' );
	$network_admin = is_network_admin();
	if ( $network_admin && !defined( 'WPTOUCH_IS_FREE' ) ) {
		if ( $show_network_wizard ) {
			$available_menus = array(
				WPTOUCH_PRO_ADMIN_WIZARD => wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_WIZARD, $wizard_title, WPTOUCH_PRO_ADMIN_CUSTOM_PAGE ),
			);
		} elseif ( defined( 'WPTOUCH_SHOW_NETWORK_WIZARD' ) ) {
			$available_menus = array(
				WPTOUCH_PRO_ADMIN_WIZARD => wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_WIZARD, $wizard_title, WPTOUCH_PRO_ADMIN_CUSTOM_PAGE ),
				WPTOUCH_PRO_ADMIN_GENERAL_SETTINGS => wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_GENERAL_SETTINGS, $settings_title ),
			);
		} else {
			$available_menus = array(
				WPTOUCH_PRO_ADMIN_GENERAL_SETTINGS => wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_GENERAL_SETTINGS, $settings_title ),
			);
		}

		if ( !$show_network_wizard ) {
			$available_menus[ WPTOUCH_PRO_ADMIN_MULTISITE_SETTINGS ] = wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_MULTISITE_SETTINGS, __( 'Multisite Setup', 'wptouch-pro' ) );
			$available_menus[ WPTOUCH_PRO_ADMIN_LICENSE ] = wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_LICENSE, $license_title, WPTOUCH_PRO_ADMIN_CUSTOM_PAGE );
		}
	} else {
		if ( $show_wizard ) {
			$available_menus = array(
				WPTOUCH_PRO_ADMIN_WIZARD => wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_WIZARD, $wizard_title, WPTOUCH_PRO_ADMIN_CUSTOM_PAGE ),
			);
		} elseif ( defined( 'WPTOUCH_SHOW_WIZARD' ) ) {
			$available_menus = array(
				WPTOUCH_PRO_ADMIN_WIZARD => wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_WIZARD, $wizard_title, WPTOUCH_PRO_ADMIN_CUSTOM_PAGE ),
				WPTOUCH_PRO_ADMIN_GENERAL_SETTINGS => wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_GENERAL_SETTINGS, $settings_title )
			);
		} else {
			$available_menus = array(
				WPTOUCH_PRO_ADMIN_GENERAL_SETTINGS => wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_GENERAL_SETTINGS, $settings_title ),
			);
		}

		if ( !defined( 'WPTOUCH_IS_FREE' ) && !$show_wizard && ( current_user_can( 'activate_plugins' ) || current_user_can( 'manage_network' ) ) && !is_plugin_active_for_network( WPTOUCH_PLUGIN_SLUG ) ) {
			$available_menus[ WPTOUCH_PRO_ADMIN_LICENSE ] = wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_LICENSE, $license_title, WPTOUCH_PRO_ADMIN_CUSTOM_PAGE );
		}
	}

	if ( defined( 'WPTOUCH_CLIENT_MODE' ) && WPTOUCH_CLIENT_MODE === true ) {
		unset( $available_menus[ WPTOUCH_PRO_ADMIN_LICENSE ] );
	}

	if ( defined( 'WPTOUCH_IS_FREE' ) ) {
		$available_menus[ WPTOUCH_PRO_ADMIN_GO_PRO ] = wptouch_admin_create_menu( WPTOUCH_PRO_ADMIN_GO_PRO, 'Upgrade to Pro', WPTOUCH_PRO_ADMIN_CUSTOM_PAGE );
	}

	return apply_filters( 'wptouch_available_menus', $available_menus );
}

function wptouch_admin_get_root_slug( $network_admin = false ) {
	$menu = wptouch_admin_get_predefined_menus();
	$settings = wptouch_get_settings();
	if ( defined( 'WPTOUCH_IS_FREE' ) ) {
		$show_wizard = $settings->show_free_wizard;
	} else {
		$show_wizard = $settings->show_wizard;
	}
	$show_network_wizard = $settings->show_network_wizard;
	$network_admin = is_network_admin();

	if ( $network_admin ) {
		if ( $show_network_wizard ) {
			return $menu[ WPTOUCH_PRO_ADMIN_WIZARD ]->slug;
		} else {
			return $menu[ WPTOUCH_PRO_ADMIN_GENERAL_SETTINGS ]->slug;
		}
	} else {
		if ( $show_wizard ) {
			return $menu[ WPTOUCH_PRO_ADMIN_WIZARD ]->slug;
		} else {
			if ( defined( 'WPTOUCH_SHOW_WIZARD' ) ) {
				return $menu[ WPTOUCH_PRO_ADMIN_WIZARD ]->slug;
			}
			return $menu[ WPTOUCH_PRO_ADMIN_GENERAL_SETTINGS ]->slug;
		}
	}
}

