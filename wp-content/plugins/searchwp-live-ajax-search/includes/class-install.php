<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SearchWP_Live_Search_Install.
 *
 * The SearchWP Live Ajax Search installer.
 *
 * @since 1.7.0
 */
class SearchWP_Live_Search_Install {

	/**
	 * Initial setup.
	 *
	 * @since 1.7.0
	 */
	public function setup() {

		register_activation_hook( SEARCHWP_LIVE_SEARCH_PLUGIN_FILE, [ $this, 'install' ] );

		$this->hooks();
	}

	/**
	 * Hooks.
	 *
	 * @since 1.7.0
	 */
	public function hooks() {

		add_action( 'admin_init', [ $this, 'redirect' ], 9999 );
	}

	/**
	 * Perform certain actions on plugin activation.
	 *
	 * @since 1.7.0
	 */
	public function install() {

		$this->upgrade();

		// Abort so we only set the transient for single site installs.
		if ( isset( $_GET['activate-multi'] ) || is_network_admin() ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		// Add transient to trigger redirect to the Settings screen.
		set_transient( 'searchwp_live_search_activation_redirect', true, 30 );
	}

	/**
	 * Upgrade routine.
	 *
	 * @since 1.0
	 */
	private function upgrade() {

		$last_version = get_option( 'searchwp_live_search_version' );

		if ( $last_version === false ) {
			$last_version = 0;
		}

		if ( version_compare( $last_version, SEARCHWP_LIVE_SEARCH_VERSION, '>=' ) ) {
			return;
		}

		if ( ! empty( $last_version ) ) {
			update_option( 'searchwp_live_search_version_upgraded_from', $last_version );
		}

		update_option( 'searchwp_live_search_last_update', current_time( 'timestamp' ) );
		update_option( 'searchwp_live_search_version', SEARCHWP_LIVE_SEARCH_VERSION );
	}

	/**
	 * Settings screen redirect.
	 *
	 * This function checks if a new install or update has just occurred. If so,
	 * then we redirect the user to the appropriate page.
	 *
	 * @since 1.7.0
	 */
	public function redirect() {

		// Check if we should consider redirection.
		if ( ! get_transient( 'searchwp_live_search_activation_redirect' ) ) {
			return;
		}

		// If we are redirecting, clear the transient so it only happens once.
		delete_transient( 'searchwp_live_search_activation_redirect' );

		// Check option to disable settings redirect.
		if ( get_option( 'searchwp_live_search_activation_redirect', false ) ) {
			return;
		}

		// Only do this for single site installs.
		if ( isset( $_GET['activate-multi'] ) || is_network_admin() ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		// Check if this is an update or first install.
		$is_upgrade = get_option( 'searchwp_live_search_version_upgraded_from' );

		if ( $is_upgrade ) {
			return;
		}

		// Initial install.
		wp_safe_redirect( admin_url( 'admin.php?page=searchwp-live-search' ) );
		exit;
	}
}
