<?php

use \SearchWP_Live_Search_Utils as Utils;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SearchWP_Live_Search_Notifications.
 *
 * The SearchWP Live Ajax Search settings.
 *
 * @since 1.7.3
 */
class SearchWP_Live_Search_Notifications {

	/**
	 * URL for fetching remote notifications.
	 *
	 * @since 1.7.3
	 */
	private const SOURCE_URL = 'https://plugin.searchwp.com/wp-content/notifications.json';

	/**
	 * Name of the WP option to save the notifications data to.
	 *
	 * @since 1.7.3
	 */
	private const OPTION_NAME = 'searchwp_lite_admin_notifications';

	/**
	 * Internal constant to populate the Notifications panel bypassing all checks.
	 * Change false to true to enable.
	 *
	 * @since 1.7.5
	 */
	private const TEST_MODE = false;

	/**
	 * Init.
	 *
	 * @since 1.7.3
	 */
	public static function init() {

		add_filter( 'searchwp_live_search_settings_defaults', [ __CLASS__, 'hide_opt_out_setting' ] );

        if ( Utils::is_searchwp_active() ) {
            return;
        }

		if ( ! self::has_access() ) {
			return;
		}

		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'assets_global' ] );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'assets' ] );

		add_filter( 'searchwp_live_search_options_submenu_pages', [ __CLASS__, 'add_submenu_page' ] );

		add_action( 'searchwp_live_search_settings_header_actions', [ __CLASS__, 'output_header_button' ] );
		add_action( 'searchwp_live_search_settings_header_after', [ __CLASS__, 'output_panel' ] );

		add_action( 'wp_ajax_searchwp_live_search_notification_dismiss', [ __CLASS__, 'dismiss' ] );
    }

	/**
	 * Check if user has access.
	 *
	 * @since 1.7.3
	 *
	 * @return bool
	 */
	public static function has_access() {

        $settings_api = searchwp_live_search()->get( 'Settings_Api' );

		return current_user_can( $settings_api::get_capability() ) && ! $settings_api->get( 'hide-announcements' );
	}

	/**
	 * Register global assets.
	 *
	 * @since 1.7.3
	 */
	public static function assets_global() {

		if ( ! self::has_access() ) {
			return;
		}

		if ( empty( self::get_count() ) ) {
			return;
		}

		wp_enqueue_style(
			'searchwp-live-search-admin-notifications-global',
			SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/styles/admin/notifications-global.css',
			[],
			SEARCHWP_LIVE_SEARCH_VERSION
		);
	}

	/**
	 * Register assets.
	 *
	 * @since 1.7.3
	 */
	public static function assets() {

		if ( ! self::has_access() ) {
			return;
		}

		if ( ! Utils::is_settings_page() ) {
			return;
		}

		wp_enqueue_style(
			'searchwp-live-search-admin-notifications',
			SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/styles/admin/notifications.css',
			[],
			SEARCHWP_LIVE_SEARCH_VERSION
		);

		wp_enqueue_script(
			'searchwp-live-search-admin-notifications',
			SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/js/admin/notifications.js',
			[],
			SEARCHWP_LIVE_SEARCH_VERSION
		);

		Utils::localize_script( 'searchwp-live-search-admin-notifications' );
	}

	/**
	 * Add Notifications pseudo submenu item to the SearchWP admin menu.
	 *
	 * @since 1.7.3
	 *
	 * @param array $submenu_pages List of registered SearchWP submenu pages.
	 *
	 * @return array
	 */
	public static function add_submenu_page( $submenu_pages ) {

		if ( ! self::has_access() ) {
			return $submenu_pages;
		}

		if ( empty( self::get_count() ) ) {
			return $submenu_pages;
		}

		$submenu_pages['notifications'] = [
			'menu_title' => esc_html__( 'Notifications', 'searchwp-live-ajax-search' ) . '<span style="margin-top: 6px;" class="searchwp-admin-menu-notification-indicator"></span>',
			'menu_slug'  => SearchWP_Live_Search_Menu::MENU_SLUG . '#notifications',
			'position'   => 0,
		];

		return $submenu_pages;
	}

	/**
	 * Hide a plugin setting to opt out of plugin notifications if SearchWP is active.
	 *
	 * @since 1.7.3
	 *
	 * @param array $settings List of registered plugin settings.
	 *
	 * @return array
	 */
	public static function hide_opt_out_setting( $settings ) {

		if ( Utils::is_searchwp_active() ) {
			unset( $settings['misc-heading'], $settings['hide-announcements'] );
		}

		return $settings;
    }

	/**
	 * Output header action button.
	 *
	 * @since 1.7.3
	 */
	public static function output_header_button() {

		$notifications = self::get();

		?>
        <div id="swp-notifications-page-header-button" class="swp-header-menu--item swp-relative" title="<?php esc_html_e( 'Notifications', 'searchwp-live-ajax-search' ); ?>">
	        <svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
		        <path fill-rule="evenodd" clip-rule="evenodd" d="M13.3333 0.5H1.66667C0.75 0.5 0 1.25 0 2.16667V13.8333C0 14.75 0.741667 15.5 1.66667 15.5H13.3333C14.25 15.5 15 14.75 15 13.8333V2.16667C15 1.25 14.25 0.5 13.3333 0.5ZM13.3333 13.8333H1.66667V11.3333H4.63333C5.20833 12.325 6.275 13 7.50833 13C8.74167 13 9.8 12.325 10.3833 11.3333H13.3333V13.8333ZM9.175 9.66667H13.3333V2.16667H1.66667V9.66667H5.84167C5.84167 10.5833 6.59167 11.3333 7.50833 11.3333C8.425 11.3333 9.175 10.5833 9.175 9.66667Z" fill="#0E2121" fill-opacity="0.6"/>
	        </svg>

	        <?php if ( ! empty( $notifications ) ) : ?>
		        <div class="swp-badge">
			        <span><?php echo count( $notifications ); ?></span>
		        </div>
	        <?php endif; ?>
        </div>
		<?php
	}

	/**
	 * Output main notifications panel.
	 *
	 * @since 1.7.3
	 */
	public static function output_panel() {

		$notifications = self::get();

		?>
        <div class="searchwp-notifications-panel-wrapper" style="display: none;">
            <div class="searchwp-notifications-panel components-animate__slide-in is-from-left">

                <div class="searchwp-notifications-panel__header">
                    <span><span><?php echo count( $notifications ); ?></span> Unread Notifications</span>
                    <button type="button" class="components-button has-icon searchwp-notifications-panel__close"
                            aria-label="Close notifications">
                        <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                             aria-hidden="true" focusable="false">
                            <path d="M13 11.8l6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z"></path>
                        </svg>
                    </button>
                </div>

                <div class="searchwp-notifications-panel__notifications">
					<?php
					foreach ( $notifications as $notification ) {
						self::output_panel_notification_single( $notification );
					}
					?>
                </div>

            </div>
            <div class="searchwp-notifications-backdrop"></div>
        </div>
		<?php
	}

	/**
	 * Output single notification in the main notifications panel.
	 *
	 * @since 1.7.3
	 *
	 * @param array $notification Single notification data.
	 */
	private static function output_panel_notification_single( $notification ) {

		?>
        <div class="searchwp-notifications-notification" style="background-color: transparent;">
            <div class="searchwp-notifications-notification__icon searchwp-notifications-notification__icon-success">
                <span class="dashicons dashicons-yes-alt"></span>
            </div>
            <div class="searchwp-notifications-notification__body">
                <div class="searchwp-notifications-notification__header">
                    <div class="searchwp-notifications-notification__title"><?php echo esc_html( $notification['title'] ); ?></div>
                    <div class="searchwp-notifications-notification__date"><?php echo esc_html( human_time_diff( strtotime( $notification['start'] ), strtotime( current_time( 'mysql' ) ) ) ); ?>
                        ago
                    </div>
                </div>
                <div class="searchwp-notifications-notification__content">
                    <p><?php echo wp_kses_post( $notification['content'] ); ?></p>
                </div>
                <div class="searchwp-notifications-notification__actions">
					<?php foreach ( $notification['actions'] as $notification_action ) : ?>
                        <a href="<?php echo esc_url( $notification_action['url'] ); ?>" target="_blank" class="components-button is-<?php echo esc_attr( $notification_action['type'] ); ?>">
							<?php echo esc_html( $notification_action['text'] ); ?>
                        </a>
					<?php endforeach; ?>
                    <button type="button" class="searchwp-notification-dismiss components-button is-link" data-id="<?php echo absint( $notification['remote_id'] ); ?>">
						<?php esc_html_e( 'Dismiss', 'searchwp-live-ajax-search' ); ?>
                    </button>
                </div>
            </div>
        </div>
		<?php
	}

	/**
	 * Get available notifications.
	 *
	 * @since 1.7.3
	 *
	 * @return array
	 */
	public static function get() {

		if ( ! self::has_access() ) {
			return [];
		}

		$option = self::get_option();

		// Fetch remote notifications every 12 hours.
		if ( self::TEST_MODE || empty( $option['updated_at'] ) || time() > $option['updated_at'] + ( 12 * HOUR_IN_SECONDS ) ) {
			self::save( self::fetch() );
			$option = self::get_option( [ 'cache' => false ] ); // Make sure the notifications are available right away.
		}

		return ! empty( $option['notifications'] ) ? self::filter_active( $option['notifications'] ) : [];
	}

	/**
	 * Get available notifications count.
	 *
	 * @since 1.7.3
	 *
	 * @return int
	 */
	public static function get_count() {

		if ( ! self::has_access() ) {
			return 0;
		}

		return count( self::get() );
	}

	/**
	 * Fetch notifications from the remote server.
	 *
	 * @since 1.7.3
	 *
	 * @return array
	 */
	private static function fetch() {

		$request = wp_remote_get(
			self::SOURCE_URL,
			[ 'sslverify' => false ]
		);

		if ( is_wp_error( $request ) ) {
			return [];
		}

		$response      = wp_remote_retrieve_body( $request );
		$notifications = ! empty( $response ) ? json_decode( $response, true ) : [];

		if ( ! is_array( $notifications ) ) {
			return [];
		}

		return self::filter_fetched( $notifications );
	}

	/**
	 * Parse single notification data.
	 *
	 * @since 1.7.3
	 *
	 * @param array $notification Raw data to parse.
	 *
	 * @return array
	 */
	private static function parse_notification( $notification ) {

		$remote_id  = ! empty( $notification['id'] ) ? $notification['id'] : '0';
		$type       = ! empty( $notification['notification_type'] ) ? $notification['notification_type'] : 'info';
		$title      = ! empty( $notification['title'] ) ? $notification['title'] : '';
		$slug       = ! empty( $notification['slug'] ) ? $notification['slug'] : $title;
		$content    = ! empty( $notification['content'] ) ? $notification['content'] : '';
		$buttons    = ! empty( $notification['btns'] ) && is_array( $notification['btns'] ) ? $notification['btns'] : [];
		$conditions = ! empty( $notification['type'] ) && is_array( $notification['type'] ) ? $notification['type'] : [];
		$start      = ! empty( $notification['start'] ) ? $notification['start'] : date( 'Y-m-d H:i:s' );
		$end        = ! empty( $notification['end'] ) ? $notification['end'] : date( 'Y-m-d H:i:s', time() + ( YEAR_IN_SECONDS * 1 ) );

		return [
			'remote_id'  => sanitize_text_field( $remote_id ),
			'type'       => sanitize_text_field( $type ),
			'title'      => esc_html( $title ),
			'slug'       => sanitize_title( $slug ),
			'content'    => wp_kses_post( $content ),
			'actions'    => self::parse_notification_actions( $buttons ),
			'conditions' => array_map( 'sanitize_text_field', $conditions ),
			'start'      => sanitize_text_field( $start ),
			'end'        => sanitize_text_field( $end ),
		];
	}

	/**
	 * Parse single notification actions data.
	 *
	 * @since 1.7.3
	 *
	 * @param array $buttons Raw data to parse.
	 *
	 * @return array
	 */
	private static function parse_notification_actions( $buttons ) {

		$actions = [];

		foreach ( $buttons as $type => $btn ) {

			$button_type = $type === 'main' ? 'primary' : 'secondary';

			$actions[] = [
				'type' => sanitize_text_field( $button_type ),
				'url'  => esc_url_raw( $btn['url'] ),
				'text' => esc_html( $btn['text'] ),
			];
		}

		return $actions;
	}

	/**
	 * Filter fetched notifications before saving.
	 *
	 * @since 1.7.3
	 *
	 * @param array $notifications Array of notifications items to verify.
	 *
	 * @return array
	 */
	private static function filter_fetched( $notifications ) {

		$data = [];

		if ( ! is_array( $notifications ) || empty( $notifications ) ) {
			return $data;
		}

		foreach ( $notifications as $notification ) {
			if ( self::verify_single( $notification ) ) {
				$data[] = self::parse_notification( $notification );
			}
		}

		return $data;
	}

	/**
	 * Filter active notifications and remove outdated ones.
	 *
	 * @since 1.7.3
	 *
	 * @param array $notifications Array of notifications items to filter.
	 *
	 * @return array
	 */
	private static function filter_active( $notifications ) {

		if ( ! is_array( $notifications ) || empty( $notifications ) ) {
			return [];
		}

		if ( self::TEST_MODE ) {
			return $notifications;
		}

		// Remove notifications that are not active.
		foreach ( $notifications as $key => $notification ) {
			if (
				( ! empty( $notification['start'] ) && time() < strtotime( $notification['start'] ) ) ||
				( ! empty( $notification['end'] ) && time() > strtotime( $notification['end'] ) )
			) {
				unset( $notifications[ $key ] );
			}
		}

		return $notifications;
	}

	/**
	 * Verify a single notification data.
	 *
	 * @since 1.7.3
	 *
	 * @param array $notification Notification data to verify.
	 *
	 * @return bool
	 */
	private static function verify_single( $notification ) {

		if ( self::TEST_MODE ) {
			return true;
		}

		$option = self::get_option();

		// The message and license should never be empty, if they are, ignore.
		if ( empty( $notification['content'] ) || empty( $notification['type'] ) ) {
			return false;
		}

		$license_type = 'lite';

		// Ignore if license type does not match.
		if ( ! in_array( $license_type, $notification['type'], true ) ) {
			return false;
		}

		// Ignore if expired.
		if ( ! empty( $notification['end'] ) && time() > strtotime( $notification['end'] ) ) {
			return false;
		}

		// Ignore if notification has already been dismissed.
		if ( ! empty( $option['dismissed_ids'] ) && in_array( $notification['id'], $option['dismissed_ids'] ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			return false;
		}

		return true;
	}

	/**
	 * Get option value.
	 *
	 * @since 1.7.3
	 *
	 * @param array $args Method arguments.
	 *
	 * @return array
	 */
	private static function get_option( $args = [] ) {

		static $option_cache;

		if ( ! isset( $args['cache'] ) ) {
			$args['cache'] = true;
		}

		if ( $option_cache && ! empty( $args['cache'] ) ) {
			return $option_cache;
		}

		$option = get_option( self::OPTION_NAME, [] );

		if ( empty( $args['cache'] ) ) {
			return $option;
		}

		$option_cache = [
			'updated_at'    => ! empty( $option['updated_at'] ) ? $option['updated_at'] : 0,
			'dismissed_ids' => ! empty( $option['dismissed_ids'] ) ? $option['dismissed_ids'] : [],
			'notifications' => ! empty( $option['notifications'] ) ? $option['notifications'] : [],
		];

		return $option_cache;
	}

	/**
	 * Save notifications data in the database.
	 *
	 * @param array $notifications Array of notifications data to save.
	 *
	 * @since 1.7.3
	 */
	private static function save( $notifications ) {

		$option = self::get_option();

		update_option(
			self::OPTION_NAME,
			[
				'updated_at'    => time(),
				'dismissed_ids' => $option['dismissed_ids'],
				'notifications' => $notifications,
			]
		);
	}

	/**
	 * Dismiss notification via AJAX.
	 *
	 * @since 1.7.3
	 */
	public static function dismiss() {

		Utils::check_ajax_permissions();

		if ( searchwp_live_search()->get( 'Settings_Api' )->get( 'hide-announcements' ) ) {
			wp_send_json_error();
		}

		if ( empty( $_POST['id'] ) ) {
			wp_send_json_error();
		}

		$id     = sanitize_text_field( wp_unslash( $_POST['id'] ) );
		$option = self::get_option();

		$option['dismissed_ids'][] = $id;
		$option['dismissed_ids']   = array_unique( $option['dismissed_ids'] );

		// Remove notification.
		if ( is_array( $option['notifications'] ) && ! empty( $option['notifications'] ) ) {
			foreach ( $option['notifications'] as $key => $notification ) {
				if ( (int) $notification['remote_id'] === (int) $id ) {
					unset( $option['notifications'][ $key ] );
					break;
				}
			}
		}

		update_option( self::OPTION_NAME, $option );

		wp_send_json_success();
	}
}
