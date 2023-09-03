<?php

use \SearchWP_Live_Search_Settings_Api as Settings_Api;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SearchWP_Live_Search_Utils.
 *
 * @since 1.7.0
 */
class SearchWP_Live_Search_Utils {

	/**
	 * Plugin general prefix.
	 *
	 * @since 1.7.3
	 */
	const SEARCHWP_LIVE_SEARCH_PREFIX = 'searchwp_live_search_';

	/**
	 * Check if SearchWP plugin is active.
	 *
	 * @since 1.7.0
	 */
	public static function is_searchwp_active() {

		return class_exists( 'SearchWP' );
	}

	/**
	 * Helper function to determine if loading a Live Ajax Search admin settings page.
	 *
	 * @since 1.7.0
	 *
	 * @return bool
	 */
	public static function is_settings_page() {

		if ( ! is_admin() ) {
			return false;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$page = isset( $_REQUEST['page'] ) ? sanitize_key( $_REQUEST['page'] ) : '';

		if ( ! in_array( $page, [ 'searchwp-live-search', 'searchwp-forms' ], true ) ) {
			return false;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$view = isset( $_REQUEST['tab'] ) ? sanitize_key( $_REQUEST['tab'] ) : '';

		if ( $page === 'searchwp-forms' && $view !== 'live-search' ) {
			return false;
		}

		return true;
	}

	/**
	 * Helper function to determine if loading a parent Live Ajax Search admin settings page.
	 *
	 * @since 1.7.6
	 *
	 * @return bool
	 */
	public static function is_parent_settings_page() {

		if ( ! is_admin() ) {
			return false;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$page = isset( $_REQUEST['page'] ) ? sanitize_key( $_REQUEST['page'] ) : '';

		if ( empty( $page ) ) {
			return false;
		}

		return $page === 'searchwp-forms';
	}

	/**
	 * Sanitize array/string of CSS classes.
	 *
	 * @since 1.7.0
	 *
	 * @param array|string $classes
	 * @param array        $args {
	 *     Optional arguments.
	 *
	 *     @type bool       $convert Whether to suppress filters. Default true.
	 * }
	 *
	 * @return string|array
	 */
	public static function sanitize_classes( $classes, $args = [] ) {

		$is_array = is_array( $classes );
		$convert  = ! empty( $args['convert'] );
		$css      = [];

		if ( ! empty( $classes ) ) {
			$classes = $is_array ? $classes : explode( ' ', trim( $classes ) );
			foreach ( $classes as $class ) {
				if ( ! empty( $class ) ) {
					$css[] = sanitize_html_class( $class );
				}
			}
		}

		if ( $is_array ) {
			return $convert ? implode( ' ', $css ) : $css;
		}

		return $convert ? $css : implode( ' ', $css );
	}

	/**
	 * Localizes a script using a standard set of variables.
	 *
	 * @since 1.7.3
	 *
	 * @param string $handle   The script handle to localize.
	 * @param array  $settings Additional settings to localize.
	 */
	public static function localize_script( string $handle, array $settings = [] ) {

		$capability = Settings_Api::get_capability();

		$l10n = [
			'nonce'  => current_user_can( $capability ) ? wp_create_nonce( self::SEARCHWP_LIVE_SEARCH_PREFIX . 'settings' ) : '',
			'prefix' => self::SEARCHWP_LIVE_SEARCH_PREFIX,
		];

		if ( ! empty( $settings ) && is_array( $settings ) ) {
			$l10n = array_merge( $l10n , $settings );
		}

		wp_localize_script( $handle, '_SEARCHWP', $l10n );
	}

	/**
	 * Check if the AJAX call has all the necessary permissions (nonce and capability).
	 *
	 * @since 1.7.3
	 *
	 * @param array $args Arguments to change method's behaviour.
	 *
	 * @return bool
	 */
	public static function check_ajax_permissions( $args = [] ) {

		$defaults = [
			'capability' => Settings_Api::get_capability(),
			'query_arg'  => false,
			'die'        => true,
		];

		$args = wp_parse_args( $args, $defaults );

		$result = check_ajax_referer( self::SEARCHWP_LIVE_SEARCH_PREFIX . 'settings', $args['query_arg'], $args['die'] );

		if ( $result === false ) {
			return false;
		}

		if ( ! current_user_can( $args['capability'] ) ) {
			$result = false;
		}

		if ( $result === false && $args['die'] ) {
			wp_die( -1, 403 );
		}

		return (bool) $result;
	}
}
