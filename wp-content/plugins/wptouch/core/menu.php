<?php

define( 'WPTOUCH_MENU_CACHE_TRANSIENT', 'wptouch_menu_cache' );

require_once( 'menu-walkers.php' );

/* Menu Display */
function wptouch_has_menu( $menu_slug = false ) {
	global $wptouch_pro;
	$settings = $wptouch_pro->get_settings();

	foreach ( $wptouch_pro->theme_menus as $menu_info ) {
		if ( $menu_info->setting_name == $menu_slug ) {
			$can_be_disabled = $menu_info->can_be_disabled;
		}
	}

	if ( has_nav_menu( 'wptouch_' . $menu_slug ) || !$can_be_disabled ) {
		return true;
	} else {
		return false;
	}
}

function wptouch_get_menu_name_from_slug( $menu_slug ) {
	global $wptouch_pro;

	if ( is_array( $wptouch_pro->theme_menus ) && count( (array) $wptouch_pro->theme_menus ) ) {
		foreach( $wptouch_pro->theme_menus as $menu_info ) {
			$settings = wptouch_get_settings( $menu_info->settings_domain );

			$setting_value = $menu_info->setting_name;

			$menu_to_show = $settings->$setting_value;

			if ( $menu_slug == $menu_info->setting_name ) {
				return $menu_to_show;
			}
		}
	}



	return false;
}

function _wptouch_show_menu( $menu_slug, $nav_menu_walker, $page_menu_walker, $can_be_disabled = false ) {
	$nav_menu_walker = apply_filters( 'wptouch_nav_menu_walker', $nav_menu_walker, $menu_slug );
	$page_menu_walker = apply_filters( 'wptouch_page_menu_walker', $page_menu_walker, $menu_slug );

	if ( $nav_menu_walker == false ) {
		$nav_menu_walker = new WPtouchProMainNavMenuWalker;
	}

	if ( !has_nav_menu( 'wptouch_' . $menu_slug ) && !$can_be_disabled ) {
		wp_list_pages (
			array(
				'title_li' => '',
				'walker' => $page_menu_walker
			)
		);
	} else {
		// WordPress menu
		$menu_params = array(
			'theme_location' => 'wptouch_' . $menu_slug,
			'before' => '',
			'after' => '',
			'container' => '',
			'container_class' => '',
			'container_id' => '',
			'link_before' => '',
			'link_after' => '',
			'menu_class' => '',
			'items_wrap' => '%3$s',
			'walker' =>	$nav_menu_walker
		);

		wp_nav_menu(
			$menu_params
		);
	}
}

function wptouch_show_menu( $menu_slug = false, $nav_menu_walker = false, $page_menu_walker = false ) {
	$menu_html = false;
	$wptouch_menu_items = array();
	$settings = wptouch_get_settings();

	$parent_link_class = 'parent-links';

	if ( defined( 'WPTOUCH_MENU_LINK_PARENT_ITEMS' ) && WPTOUCH_MENU_LINK_PARENT_ITEMS == false ) {
		$parent_link_class = 'no-parent-links';
	}

	if ( !$settings->enable_parent_items ) {
		$parent_link_class = 'no-parent-links';
	}

	if ( $nav_menu_walker == false ) {
		$nav_menu_walker = new WPtouchProMainNavMenuWalker( $settings->enable_menu_icons );
	}

	if ( $page_menu_walker == false ) {
		$page_menu_walker = new WPtouchProMainPageMenuWalker( $settings->enable_menu_icons );
	}

	// Render the menu
	$allowed_html = array(
		'a' => array(
			'href'  => array(),
			'class'  => array(),
			'title' => array(),
			'rel' => array(),
		),
		'img' => array(
			'src'  => array(),
			'alt' => array(),
		),
		'br'     => array(),
		'ul'     => array(
			'class'  => array(),
		),
		'li'     => array(
			'class'  => array(),
		),
		'em'     => array(),
		'strong' => array()
	);

	echo wp_kses(apply_filters( 'wptouch_menu_start_html', '<ul class="menu-tree' . ' ' . $parent_link_class . '">' ), $allowed_html );

	$menu_slugs_to_show = apply_filters( 'wptouch_menu_slugs_to_show', array( $menu_slug ) );

	global $wptouch_pro;

	// Loop through all menus
	foreach( $menu_slugs_to_show as $key => $menu_slug ) {
		foreach ( $wptouch_pro->theme_menus as $menu_info ) {
			if ( $menu_info->setting_name == $menu_slug ) {
				$can_be_disabled = $menu_info->can_be_disabled;
			}
		}
		_wptouch_show_menu( $menu_slug, $nav_menu_walker, $page_menu_walker, $can_be_disabled );
	}

	echo wp_kses(apply_filters( 'wptouch_menu_end_html', '</ul>' ),array(
		'a' => array(
			'href'  => array(),
			'title' => array(),
		),
		'br'     => array(),
		'em'     => array(),
		'strong' => array()));
}

function wptouch_get_menu_icon( $page_id ) {
	global $wptouch_pro;

	if ( $page_id == false ) {
		return wptouch_get_site_default_icon();
	}

	$menu_icon = get_post_meta( $page_id, '_wptouch_pro_menu_item_icon', true );

	if ( $menu_icon ) {
		$icon = wptouch_check_url_ssl( site_url() . $menu_icon );

		if ( !defined( 'WPTOUCH_IS_FREE' ) ) {
			$wptouch_free_potential_path = DIRECTORY_SEPARATOR . 'wptouch' . DIRECTORY_SEPARATOR;

			if ( strpos( $icon, $wptouch_free_potential_path ) !== false ) {
				$icon = str_replace( $wptouch_free_potential_path, DIRECTORY_SEPARATOR . 'wptouch-pro' . DIRECTORY_SEPARATOR, $icon );
			}
			if ( strpos( $icon, 'wptouch-pro-3' ) !== false ) {
				$icon = str_replace( 'wptouch-pro-3', DIRECTORY_SEPARATOR . 'wptouch-pro' . DIRECTORY_SEPARATOR, $icon );
			}
		}

		return $icon;

	} else {
		return wptouch_get_site_default_icon();
	}
}

function wptouch_menu_is_disabled( $page_id ) {
	global $wptouch_menu_item;

	$has_been_disabled = get_post_meta( $page_id, '_wptouch_pro_menu_item_disabled', true );
	return ( $has_been_disabled == '1' );
}

function wptouch_get_site_default_icon() {
	global $wptouch_pro;

	$settings = $wptouch_pro->get_settings();
	$icon = $settings->default_menu_icon;
	if ( strpos( $icon, 'wptouch-pro-3' ) !== false ) {
		$icon = str_replace( 'wptouch-pro-3', 'wptouch-pro', $icon );
	}
	return site_url() . $icon;
}

function wptouch_the_site_default_icon() {
	echo wp_kses(wptouch_get_site_default_icon(),array(
		'a' => array(
			'href'  => array(),
			'title' => array(),
		),
		'br'     => array(),
		'em'     => array(),
		'strong' => array()));
}

function wptouch_register_theme_menu( $menu_info ) {
	$menu = new stdClass;

	$defaults = array(
		'name' => '',
		'settings_domain' => 'wptouch_pro',
		'friendly_name' => '',
		'description' => '',
		'tooltip' => '',
		'can_be_disabled' => false,
		'menu_type' => 'dropdown'
	);

	$menu_info = wp_parse_args( $menu_info, $defaults );

	$menu->settings_domain = $menu_info[ 'settings_domain' ];
	$menu->setting_name = $menu_info[ 'name' ];
	$menu->friendly_name = $menu_info[ 'friendly_name' ];
	$menu->description = $menu_info[ 'description' ];
	$menu->can_be_disabled = $menu_info[ 'can_be_disabled' ];
	$menu->tooltip = $menu_info[ 'tooltip' ];
	$menu->menu_type = $menu_info[ 'menu_type' ];

	global $wptouch_pro;
	$wptouch_pro->theme_menus[] = $menu;

	register_nav_menu( 'wptouch_' . $menu_info[ 'name' ], wptouchize_it( 'WPtouch Pro: ' ) . $menu_info[ 'friendly_name' ] );
}

function wptouch_get_registered_theme_count() {
	global $wptouch_pro;

	return count( (array) $wptouch_pro->theme_menus );
}