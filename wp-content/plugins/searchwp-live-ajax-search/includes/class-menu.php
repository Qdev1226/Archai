<?php

use \SearchWP_Live_Search_Utils as Utils;
use \SearchWP_Live_Search_Settings_Api as Settings_Api;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SearchWP_Live_Search_Menu.
 *
 * The SearchWP Live Ajax Search settings screen and menus.
 *
 * @since 1.7.0
 */
class SearchWP_Live_Search_Menu {

	/**
	 * Settings menu slug.
	 *
	 * @since 1.7.0
	 */
	const MENU_SLUG = 'searchwp-live-search';

	/**
	 * Hooks.
	 *
	 * @since 1.7.0
	 */
	public function hooks() {

		if ( Utils::is_searchwp_active() ) {
			return;
		}

        add_action( 'admin_menu', [ $this, 'add_menus_searchwp_disabled' ] );
        add_action( 'searchwp_live_search_options_submenu_pages', [ $this, 'add_upgrade_pro_link_searchwp_disabled' ] );
        add_action( 'admin_head', [ $this, 'style_upgrade_pro_link_searchwp_disabled' ] );
	}

	/**
	 * Get arguments to populate the submenus if SearchWP is disabled.
	 * Items are sorted by the 'position' value.
	 *
	 * @since 1.7.3
	 *
	 * @return array
	 */
	private static function get_submenu_pages_args_searchwp_disabled() {

		$submenu_pages = [
			'settings' => [
				'menu_title' => esc_html__( 'Live Search', 'searchwp-live-ajax-search' ),
				'menu_slug'  => self::MENU_SLUG,
				'position'   => 10,
			],
		];

		$submenu_pages = (array) apply_filters( 'searchwp_live_search_options_submenu_pages', $submenu_pages );

		uasort(
			$submenu_pages,
			function ( $a, $b ) {
				if ( ! isset( $a['position'] ) ) {
					return 1;
				}
				if ( ! isset( $b['position'] ) ) {
					return -1;
				}

				return ( $a['position'] < $b['position'] ) ? -1 : 1;
			}
		);

		return $submenu_pages;
	}

	/**
	 * Add menus if SearchWP is disabled.
	 *
	 * @since 1.7.0
	 */
	public function add_menus_searchwp_disabled() {

		$capability = Settings_Api::get_capability();

		if ( ! current_user_can( $capability ) ) {
			return;
		}

		$page_title = esc_html__( 'SearchWP', 'searchwp-live-ajax-search' );

		$submenu_pages = self::get_submenu_pages_args_searchwp_disabled();
		$menu_page     = reset( $submenu_pages );

		$settings = searchwp_live_search()->get( 'Settings' );

		// Default SearchWP top level menu item.
		add_menu_page(
			$page_title,
			$page_title,
			$capability,
			$menu_page['menu_slug'],
			[ $settings, 'page_searchwp_disabled' ],
			'data:image/svg+xml;base64,' . base64_encode( $this->get_dashicon() ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
			apply_filters( 'searchwp\admin_menu\position', '58.95' )
		);

		foreach ( $submenu_pages as $submenu_page ) {
			add_submenu_page(
				$menu_page['menu_slug'],
				$submenu_page['page_title'] ?? $page_title,
				$submenu_page['menu_title'],
				$submenu_page['capability'] ?? $capability,
				$submenu_page['menu_slug'],
				$submenu_page['function'] ?? [ $settings, 'page_searchwp_disabled' ]
			);
		}
	}

	/**
	 * Add "Upgrade to Pro" menu link if SearchWP is disabled.
	 *
	 * @since 1.7.2
	 *
	 * @param array $submenu_pages List of registered SearchWP submenu pages.
	 *
	 * @return array
	 */
	public function add_upgrade_pro_link_searchwp_disabled( $submenu_pages ) {

		$submenu_pages['upgrade_to_pro'] = [
			'menu_title' => esc_html__( 'Upgrade to Pro', 'searchwp-live-ajax-search' ),
			'menu_slug'  => esc_url( 'https://searchwp.com/?utm_source=WordPress&utm_medium=Admin+Menu+Upgrade+Link&utm_campaign=Live+Ajax+Search&utm_content=Upgrade+to+Pro' ),
			'position'   => 100,
			'function'   => '',
		];

		// Enqueue the menu script only if the menu is registered.
		$this->enqueues_searchwp_disabled();

		return $submenu_pages;
	}

	/**
	 * Enqueue assets if SearchWP is disabled.
	 *
	 * @since 1.7.0
	 */
	private function enqueues_searchwp_disabled() {

		wp_enqueue_script(
			'searchwp-live-search-admin-menu',
			SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/js/admin/menu.js',
			[ 'jquery' ],
			SEARCHWP_LIVE_SEARCH_VERSION,
			true
		);
	}

	/**
	 * Style "Upgrade to Pro" menu link if SearchWP is disabled.
	 *
	 * @since 1.7.0
	 */
	public function style_upgrade_pro_link_searchwp_disabled() {

		global $submenu;

		$menu_slug = '';

		if ( isset( $submenu[ self::MENU_SLUG ] ) ) {
			$menu_slug = self::MENU_SLUG;
		}

		$notifications_slug = self::MENU_SLUG . '#notifications';

		if ( isset( $submenu[ $notifications_slug ] ) ) {
			$menu_slug = $notifications_slug;
		}

		if ( empty( $menu_slug ) ) {
			return;
		}

		$menu_keys        = array_keys( $submenu[ $menu_slug ] );
		$upgrade_item_key = array_pop( $menu_keys );

		// 0 = menu_title, 1 = capability, 2 = menu_slug, 3 = page_title, 4 = classes.
		if ( strpos( $submenu[ $menu_slug ][ $upgrade_item_key ][2], 'https://searchwp.com/' ) !== 0 ) {
			return;
		}

		// Prepare a HTML class.
		// phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited
		if ( isset( $submenu[ $menu_slug ][ $upgrade_item_key ][4] ) ) {
			$submenu[ $menu_slug ][ $upgrade_item_key ][4] .= ' searchwp-sidebar-upgrade-pro';
		} else {
			$submenu[ $menu_slug ][ $upgrade_item_key ][] = 'searchwp-sidebar-upgrade-pro';
		}
		// phpcs:enable WordPress.WP.GlobalVariablesOverride.Prohibited

		echo '<style>a.searchwp-sidebar-upgrade-pro { background-color: #1da867 !important; color: #fff !important; font-weight: 600 !important; }</style>';
	}

	/**
	 * Get SearchWP dashicon SVG.
	 *
	 * @since 1.7.0
	 */
	private function get_dashicon() {

		return '<svg width="50" height="61" fill="#f0f0f1" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M9.57 13.259c-.959 0-1.782.68-1.946 1.625-.527 3.033-1.59 9.715-1.702 14.875-.114 5.288 1.134 13.417 1.712 16.864.16.952.984 1.636 1.95 1.636h30.683c.959 0 1.78-.675 1.945-1.619.584-3.339 1.823-11.12 1.71-16.381-.112-5.195-1.194-12.217-1.72-15.36a1.969 1.969 0 0 0-1.95-1.64zm2.728 5a.99.99 0 0 0-.986.873c-.237 2.012-.797 7.111-.89 11.127-.096 4.116.94 10.066 1.34 12.2.089.468.497.8.972.8h24.368a.983.983 0 0 0 .972-.799c.403-2.133 1.443-8.084 1.348-12.201-.094-4.016-.658-9.117-.897-11.128a.99.99 0 0 0-.987-.872z"/>
                  <path d="M34.564 36.765c.55-3.195.858-6.711.858-10.408a65.76 65.76 0 0 0-.09-3.416l-8.852 6.777zM24.92 31.013l-9.2 8.017a41.23 41.23 0 0 0 1.272 4.579c.978 2.835 2.141 3.732 3.34 4.021.636.154 1.327.149 2.105.096.215-.015.439-.034.668-.053.58-.048 1.198-.1 1.817-.1s1.237.052 1.816.1c.23.019.454.038.668.053.778.053 1.47.058 2.106-.096 1.198-.29 2.361-1.186 3.34-4.021.484-1.406.91-2.94 1.269-4.577zM23.363 29.716l-8.851-6.777c-.059 1.119-.09 2.259-.09 3.418 0 3.696.305 7.212.855 10.406zM31.53 11.759c-.405.004-.814.04-1.194.082l-.44.05c-.323.04-.623.076-.834.083a54.57 54.57 0 0 0-3.566.22l-.121.012a5.617 5.617 0 0 1-.453.031 1.34 1.34 0 0 1-.317-.05l-.213-.057-.117-.033a9.308 9.308 0 0 0-.97-.215c-.796-.13-1.91-.192-3.329.084-.312.06-.743.04-1.136.023l-.037-.002h-.008c-.434-.018-.886-.038-1.317-.005-.436.032-.8.117-1.072.273-.25.145-.438.36-.525.728-.548 2.32-.954 4.87-1.198 7.569l10.24 7.838 10.237-7.838c-.244-2.7-.65-5.248-1.197-7.569a1.311 1.311 0 0 0-.678-.902c-.351-.193-.818-.288-1.353-.314a6.888 6.888 0 0 0-.403-.008z"/>
                  <path d="M15.732 43.242h18.38a1.5 1.5 0 0 1 1.492 1.35l.6 6a1.5 1.5 0 0 1-1.492 1.65h-19.58a1.5 1.5 0 0 1-1.493-1.65l.6-6a1.5 1.5 0 0 1 1.493-1.35z"/>
                  <path d="M19.918 3.26c-1.087 0-2 .913-2 2v8.5a1.5 1.5 0 0 0 1.5 1.5h11a1.5 1.5 0 0 0 1.5-1.5v-8.5c0-1.087-.913-2-2-2zm1 3h8v6h-8z"/>
                  <path d="M17.918 8.759h14a1.5 1.5 0 0 1 1.5 1.5v4.5h-17v-4.5a1.5 1.5 0 0 1 1.5-1.5z"/>
                  <path d="M14.918 11.759h20a1.5 1.5 0 0 1 1.5 1.5v4.5h-23v-4.5a1.5 1.5 0 0 1 1.5-1.5zM11.43 50.759h26.983a1.5 1.5 0 0 1 1.442 1.088l.858 3a1.5 1.5 0 0 1-1.443 1.912H10.573a1.5 1.5 0 0 1-1.442-1.912l.857-3a1.5 1.5 0 0 1 1.442-1.088z"/>
                </svg>';
	}
}
