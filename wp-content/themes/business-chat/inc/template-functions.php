<?php

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package business_chat
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function business_chat_body_classes($classes)
{
	// Adds a class of hfeed to non-singular pages.
	if (!is_singular()) {
		$classes[] = 'hfeed';
	}

	if (get_header_image()) {
		$classes[] = 'header-image';
	} elseif (!in_array($GLOBALS['pagenow'], array('wp-activate.php', 'wp-signup.php'))) {
		$classes[] = 'masthead-fixed';
	}

	return $classes;
}
add_filter('body_class', 'business_chat_body_classes');

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function business_chat_pingback_header()
{
	if (is_singular() && pings_open()) {
		echo '<link rel="pingback" href="', esc_url(get_bloginfo('pingback_url')), '">';
	}
}
add_action('wp_head', 'business_chat_pingback_header');
