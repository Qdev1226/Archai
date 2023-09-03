<?php

use \SearchWP_Live_Search_Utils as Utils;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SearchWP_Live_Search_Settings_Api.
 *
 * The SearchWP Live Ajax Search settings API.
 *
 * @since 1.7.0
 */
class SearchWP_Live_Search_Settings_Api {

	/**
	 * WP option name to save settings.
	 *
	 * @since 1.7.0
	 */
	const OPTION_NAME = 'searchwp_live_search_settings';

	/**
	 * Capability requirement for managing settings.
	 *
	 * @since 1.7.0
	 */
	const CAPABILITY = 'manage_options';

	/**
	 * Init hook callback.
	 *
	 * @since 1.7.0
	 */
	public function init() {

		if ( Utils::is_settings_page() ) {
			$this->save_settings();
		}
	}

	/**
	 * Save settings.
	 *
	 * @since 1.7.0
	 */
	private function save_settings() {

		if ( ! $this->current_user_can_save() ) {
			return;
		}

		$fields   = $this->get_registered_settings();
		$settings = get_option( self::OPTION_NAME, [] );

		foreach ( $fields as $slug => $field ) {

			if ( empty( $field['type'] ) || $field['type'] === 'content' ) {
				continue;
			}

			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing
			$value = isset( $_POST[ $slug ] ) ? trim( wp_unslash( $_POST[ $slug ] ) ) : false;

			$value = $this->sanitize_setting( $value, $field['type'] );

			// Add to settings.
			$settings[ $slug ] = $value;
		}

        update_option( self::OPTION_NAME, $settings );

		SearchWP_Live_Search_Notice::success( esc_html__( 'Settings were successfully saved.', 'searchwp-live-ajax-search' ) );
	}

	/**
	 * Get settings capability.
	 *
	 * @since 1.7.0
	 *
	 * @return string
	 */
	public static function get_capability() {

		return (string) apply_filters( 'searchwp_live_search_settings_capability', self::CAPABILITY );
	}

	/**
	 * Check if the current user can save settings.
	 *
	 * @since 1.7.0
	 *
	 * @return bool
	 */
	private function current_user_can_save() {

		// Check nonce and other various security checks.
		if ( ! isset( $_POST['searchwp-live-search-settings-submit'] ) ) {
			return false;
		}

		if ( empty( $_POST['nonce'] ) ) {
			return false;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'searchwp-live-search-settings-nonce' ) ) {
			return false;
		}

		$capability = self::get_capability();

		if ( ! current_user_can( $capability ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get the value of a specific SearchWP setting.
	 *
	 * @since 1.7.0
	 *
	 * @param string $slug    Setting slug.
	 * @param mixed  $default Default setting value.
	 *
	 * @return mixed
	 */
	public function get( $slug, $default = null ) {

		$slug     = sanitize_key( $slug );
		$settings = get_option( self::OPTION_NAME );

		if ( $default === null ) {
			$registered = $this->get_registered_settings();
			$default    = isset( $registered[ $slug ]['default'] ) ? $registered[ $slug ]['default'] : $default;
		}

		return isset( $settings[ $slug ] ) ? wp_unslash( $settings[ $slug ] ) : $default;
	}

	/**
	 * Return all the default registered settings fields.
	 *
	 * @since 1.7.0
	 *
	 * @return array
	 */
	public function get_registered_settings() {

		$defaults = [
			'general-heading'         => [
				'slug'    => 'general-heading',
				'content' => '<h3>' . esc_html__( 'General', 'searchwp-live-ajax-search' ) . '</h3>',
				'type'    => 'content',
				'class'   => [ 'section-heading' ],
			],
			'enable-live-search'      => [
				'slug'       => 'enable-live-search',
				'name'       => esc_html__( 'Enable Live Search', 'searchwp-live-ajax-search' ),
				'desc'       => esc_html__( 'Check this option to automatically enhance your search forms with Live Ajax Search.', 'searchwp-live-ajax-search' ),
				'type'       => 'checkbox',
				'default'    => true,
				'desc_after' => SearchWP_Live_Search_Settings::get_dyk_block_output(),
			],
			'results-heading'         => [
				'slug'    => 'results-heading',
				'content' => '<h3>' . esc_html__( 'Results', 'searchwp-live-ajax-search' ) . '</h3>',
				'type'    => 'content',
				'class'   => [ 'section-heading' ],
			],
			'include-frontend-css'    => [
				'slug'    => 'include-frontend-css',
				'name'    => esc_html__( 'Include Styling', 'searchwp-live-ajax-search' ),
				'desc'    => esc_html__( 'Determines which CSS files to load and use for the site. "Positioning and visual styling" is recommended, unless you are experienced with CSS or instructed by support to change settings.', 'searchwp-live-ajax-search' ),
				'type'    => 'select',
				'default' => 'all',
				'options' => [
					'all'      => esc_html__( 'Positioning and visual styling', 'searchwp-live-ajax-search' ),
					'position' => esc_html__( 'Positioning styling only', 'searchwp-live-ajax-search' ),
					'none'     => esc_html__( 'No styling', 'searchwp-live-ajax-search' ),
				],
			],
			'results-pane-position'   => [
				'slug'    => 'results-pane-position',
				'name'    => esc_html__( 'Positioning', 'searchwp-live-ajax-search' ),
				'desc'    => esc_html__( 'Selects where to position the results pane relative to the search form.', 'searchwp-live-ajax-search' ),
				'type'    => 'select',
				'default' => 'bottom',
				'options' => [
					'bottom' => esc_html__( 'Below the search form', 'searchwp-live-ajax-search' ),
					'top'    => esc_html__( 'Above the search form', 'searchwp-live-ajax-search' ),
				],
			],
			'results-pane-auto-width' => [
				'slug'    => 'results-pane-auto-width',
				'name'    => esc_html__( 'Auto Width', 'searchwp-live-ajax-search' ),
				'desc'    => esc_html__( 'Check this option to align the results pane width with the search form width.', 'searchwp-live-ajax-search' ),
				'type'    => 'checkbox',
				'default' => true,
			],
			'misc-heading'            => [
				'slug'    => 'misc-heading',
				'content' => '<h3>' . esc_html__( 'Misc', 'searchwp-live-ajax-search' ) . '</h3>',
				'type'    => 'content',
				'class'   => [ 'section-heading' ],
			],
			'hide-announcements'      => [
				'slug' => 'hide-announcements',
				'name' => esc_html__( 'Hide Announcements', 'searchwp-live-ajax-search' ),
				'desc' => esc_html__( 'Check this option to hide plugin announcements and update details.', 'searchwp-live-ajax-search' ),
				'type' => 'checkbox',
			],
		];

		return apply_filters( 'searchwp_live_search_settings_defaults', $defaults );
	}

	/**
	 * Save settings.
	 *
	 * @since 1.7.0
	 *
	 * @param mixed  $value      Value to sanitize.
	 * @param string $field_type Field type.
	 *
	 * @return bool|string
	 */
	private function sanitize_setting( $value, $field_type ) {

        switch ( $field_type ) {
            case 'checkbox':
                $value = (bool) $value;
                break;

            case 'select':
            default:
                $value = sanitize_text_field( $value );
                break;
        }

        return $value;
	}
}
