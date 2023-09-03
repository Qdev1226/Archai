<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SearchWP_Live_Search_Relevanssi_Bridge.
 * Written by https://wordpress.org/plugins/daves-wordpress-live-search/.
 *
 * Relevanssi "bridge" class.
 *
 * @since 1.0
 */
class SearchWP_Live_Search_Relevanssi_Bridge {

	/**
	 * Hooks.
	 *
	 * @since 1.7.0
	 */
	public function hooks() {

		add_action( 'searchwp_live_search_alter_results', [ __CLASS__, 'alter_results' ] );
	}

	/**
	 * Alter Live Ajax Search results.
	 *
	 * @since 1.0
	 */
	public static function alter_results() {

		if ( ! function_exists( 'relevanssi_do_query' ) ) {
			return;
		}

		global $wp_query;

		relevanssi_do_query( $wp_query );
	}
}
