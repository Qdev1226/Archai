<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SearchWP_Live_Search_Template.
 *
 * Template loader class based on Pippin Williamson's guide
 * http://pippinsplugins.com/template-file-loaders-plugins/
 *
 * @since 1.0
 */
class SearchWP_Live_Search_Template {

	/**
	 * Set up the proper template part array and locate it.
	 *
	 * @since 1.0
	 *
	 * @param string $slug The template slug (without file extension).
	 * @param null   $name The template name (appended to $slug if provided).
	 * @param bool   $load Whether to load the template part.
	 *
	 * @return bool|string The location of the applicable template file
	 */
	public function get_template_part( $slug, $name = null, $load = true ) {

		do_action( 'get_template_part_' . $slug, $slug, $name );

		$templates = [];

		if ( isset( $name ) ) {
			$templates[] = $slug . '-' . $name . '.php';
		}

		$templates[] = $slug . '.php';

		// Allow filtration of template parts.
		$templates = apply_filters( 'searchwp_live_search_get_template_part', $templates, $slug, $name );

		return $this->locate_template( $templates, $load, false );
	}

	/**
	 * Retrieve the template directory within this plugin.
	 *
	 * @since 1.0
	 *
	 * @return string The template directory within this plugin
	 */
	private function get_template_directory() {

		return SEARCHWP_LIVE_SEARCH_PLUGIN_DIR . 'templates';
	}

	/**
	 * Check for the applicable template in the child theme, then parent theme,
	 * and in the plugin dir as a last resort and output it if it was located.
	 *
	 * @since 1.0
	 *
	 * @param array $template_names The potential template names in order of precedence.
	 * @param bool  $load           Whether to load the template file.
	 * @param bool  $require_once   Whether to require the template file once.
	 *
	 * @return bool|string The location of the applicable template file
	 */
	private function locate_template( $template_names, $load = false, $require_once = true ) {

		// Default to not found.
		$located = false;

		$template_dir = apply_filters( 'searchwp_live_search_template_dir', 'searchwp-live-ajax-search' );

		// Try to find the template file.
		foreach ( (array) $template_names as $template_name ) {
			$located = $this->locate_template_single( $template_dir, $template_name );
			if ( $located ) {
				break;
			}
		}

		$located = apply_filters( 'searchwp_live_search_results_template', $located, $this );

		if ( $load && ! empty( $located ) ) {
			load_template( $located, $require_once );
		}

		return $located;
	}

	/**
	 * Check for the applicable template for a single template name.
	 *
	 * @since 1.7.0
	 *
	 * @param string $template_dir  Theme template dir.
	 * @param string $template_name Template name.
	 *
	 * @return false|string
	 */
	private function locate_template_single( $template_dir, $template_name ) {

		if ( empty( $template_name ) ) {
			return false;
		}

		$template_name = ltrim( $template_name, '/' );

		// Check the child theme first.
		$maybe_child_theme = trailingslashit( get_stylesheet_directory() ) . trailingslashit( $template_dir ) . $template_name;
		if ( file_exists( $maybe_child_theme ) ) {
			return $maybe_child_theme;
		}

		// Check parent theme.
		$maybe_parent_theme = trailingslashit( get_template_directory() ) . trailingslashit( $template_dir ) . $template_name;
		if ( file_exists( $maybe_parent_theme ) ) {
			return $maybe_parent_theme;
		}

		// Check theme compat.
		$maybe_theme_compat = trailingslashit( $this->get_template_directory() ) . $template_name;
		if ( file_exists( $maybe_theme_compat ) ) {
			return $maybe_theme_compat;
		}

		return false;
	}
}
