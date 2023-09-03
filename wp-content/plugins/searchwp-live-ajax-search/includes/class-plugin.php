<?php

use \SearchWP_Live_Search_Utils as Utils;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SearchWP_Live_Search.
 *
 * The main SearchWP Live Ajax Search Class properly routes searches and all other requests/utilization
 *
 * @since 1.0
 */
class SearchWP_Live_Search {

	/**
	 * Initial setup.
	 *
	 * @since 1.7.0
	 */
	public function setup() {

		searchwp_live_search()
			->incl( 'class-install.php' )
			->register( 'Install' )
			->setup();

		$this->hooks();
	}

	/**
	 * Run hooks.
	 *
	 * @since 1.7.0
	 */
	public function hooks() {

		add_action( 'init', [ $this, 'init' ] );
		add_action( 'admin_init', [ $this, 'register_improve_your_search_notice' ] );
		add_action( 'widgets_init', [ $this, 'register_widget' ] );

		add_filter( 'plugin_action_links_' . plugin_basename( SEARCHWP_LIVE_SEARCH_PLUGIN_FILE ), [ $this, 'settings_link' ], 10, 4 );
	}

	/**
	 * Init hook callback.
	 *
	 * @since 1.7.0
	 */
	public function init() {

		$this->load_textdomain();

		searchwp_live_search()
			->incl( 'class-utils.php' );

		searchwp_live_search()
			->incl( 'class-settings.php' )
			->register( 'Settings' );

		searchwp_live_search()
			->incl( 'class-notice.php' )
			->register( 'Notice' );

		searchwp_live_search()
			->incl( 'class-settings-api.php' )
			->register( 'Settings_Api' )
			->init();

		searchwp_live_search()
			->get( 'Notice' )
			->hooks();

		searchwp_live_search()
			->incl( 'class-notifications.php' )
			->register( 'Notifications' )
			->init();

		// if an AJAX request is taking place, it's potentially a search, so we'll want to
		// prepare for that else we'll prep the environment for the search form itself.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$this->setup_search_client();
		} else {
			$this->setup_search_form();
		}
	}

	/**
	 * Load text domain.
	 *
	 * @since 1.7.0
	 */
	private function load_textdomain() {

		load_plugin_textdomain( 'searchwp-live-ajax-search', false, dirname( plugin_basename( SEARCHWP_LIVE_SEARCH_PLUGIN_FILE ) ) . '/languages/' );
	}

	/**
	 * Bootstrap an environment for an AJAX request.
	 *
	 * @since 1.7.0
	 */
	public function setup_search_client() {

		searchwp_live_search()
			->incl( 'class-template.php' )
			->register( 'Template' );

		searchwp_live_search()
			->incl( 'class-relevanssi-bridge.php' )
			->register( 'Relevanssi_Bridge' )
			->hooks();

		searchwp_live_search()
			->incl( 'class-client.php' )
			->register( 'Client' )
			->setup();
	}

	/**
	 * Bootstrap an environment for a search form.
	 *
	 * @since 1.7.0
	 */
	public function setup_search_form() {

		searchwp_live_search()
			->get( 'Settings' )
			->hooks();

		searchwp_live_search()
			->incl( 'class-menu.php' )
			->register( 'Menu' )
			->hooks();

		searchwp_live_search()
			->incl( 'class-form.php' )
			->register( 'Form' )
			->setup();
	}

	/**
	 * Register "Improve your search" call to action notice.
	 *
	 * @since 1.7.0
	 */
	public function register_improve_your_search_notice() {

		// If SearchWP is installed, bail out.
		if ( Utils::is_searchwp_active() ) {
			return;
		}

		$last_update = get_option( 'searchwp_live_search_last_update' );
		if ( empty( $last_update ) ) {
			return;
		}

		// If it's been less than 3 days since the last update, bail out.
		if ( current_time( 'timestamp' ) < absint( $last_update ) + ( DAY_IN_SECONDS * 3 ) ) {
			return;
		}

		$message =
			sprintf(
				wp_kses( /* translators: %1$s - SearchWP.com URL, %2$s - SearchWP.com URL. */
					__( '<strong>SearchWP Live Ajax Search</strong><br><a href="%1$s" target="_blank" rel="noopener noreferrer">Personalize your search results</a> and discover what your visitors are searching for at the same time with <a href="%2$s" target="_blank" rel="noopener noreferrer">SearchWP Pro!</a>', 'searchwp-live-ajax-search' ),
					[
						'a'      => [
							'href'   => [],
							'target' => [],
							'rel'    => [],
						],
						'strong' => [],
						'br'     => [],
					]
				),
				'https://searchwp.com/?utm_source=WordPress&utm_medium=Global+Notice+Impove+Search+Link&utm_content=Improve+your+search+results&utm_campaign=Live+Ajax+Search',
				'https://searchwp.com/?utm_source=WordPress&utm_medium=Global+Notice+Informational+Link&utm_content=SearchWP+Pro&utm_campaign=Live+Ajax+Search'
			);

		SearchWP_Live_Search_Notice::info(
			$message,
			[
				'dismiss' => \SearchWP_Live_Search_Notice::DISMISS['global'],
				'slug'    => 'improve_your_search_cta',
			]
		);
	}

	/**
	 * Register a search form widget.
	 *
	 * @since 1.7.0
	 */
	public function register_widget() {

		searchwp_live_search()
			->incl( 'class-widget.php' );

		register_widget( 'SearchWP_Live_Search_Widget' );
	}

	/**
	 * Add settings link to the Plugins page.
	 *
	 * @since 1.7.0
	 *
	 * @param array  $links       Plugin row links.
	 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
	 * @param array  $plugin_data An array of plugin data. See `get_plugin_data()`.
	 * @param string $context     The plugin context.
	 *
	 * @return array $links
	 */
	public function settings_link( $links, $plugin_file, $plugin_data, $context ) {

		if ( ! Utils::is_searchwp_active() ) {
			$custom['pro'] = sprintf(
				'<a href="%1$s" aria-label="%2$s" target="_blank" rel="noopener noreferrer"
				style="color: #1da867; font-weight: 700;"
				onmouseover="this.style.color=\'#008a20\';"
				onmouseout="this.style.color=\'#00a32a\';"
				>%3$s</a>',
				esc_url(
					add_query_arg(
						[
							'utm_content'  => 'Get+SearchWP+Pro',
							'utm_campaign' => 'Live+Ajax+Search',
							'utm_medium'   => 'Plugins+Table+Upgrade+Link',
							'utm_source'   => 'WordPress',
						],
						'https://searchwp.com/'
					)
				),
				esc_attr__( 'Upgrade to SearchWP Pro', 'searchwp-live-ajax-search' ),
				esc_html__( 'Get SearchWP Pro', 'searchwp-live-ajax-search' )
			);
		}

		$custom['settings'] = sprintf(
			'<a href="%s" aria-label="%s">%s</a>',
			esc_url(
				add_query_arg(
					[ 'page' => 'searchwp-live-search' ],
					admin_url( 'admin.php' )
				)
			),
			esc_attr__( 'Go to SearchWP Settings page', 'searchwp-live-ajax-search' ),
			esc_html__( 'Settings', 'searchwp-live-ajax-search' )
		);

		$custom['docs'] = sprintf(
			'<a href="%1$s" aria-label="%2$s" target="_blank" rel="noopener noreferrer">%3$s</a>',
			esc_url(
				add_query_arg(
					[
						'utm_content'  => 'Docs',
						'utm_campaign' => 'Live+Ajax+Search',
						'utm_medium'   => 'Plugins+Table+Docs+Link',
						'utm_source'   => 'WordPress',
					],
					'https://searchwp.com/extensions/live-search/'
				)
			),
			esc_attr__( 'Read the documentation', 'searchwp-live-ajax-search' ),
			esc_html__( 'Docs', 'searchwp-live-ajax-search' )
		);

		return array_merge( $custom, (array) $links );
	}
}
