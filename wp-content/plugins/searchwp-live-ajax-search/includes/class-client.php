<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SearchWP_Live_Search_Client.
 *
 * The SearchWP Live Ajax Search client that performs searches
 *
 * @since 1.0
 */
class SearchWP_Live_Search_Client {

	/**
	 * Found posts count.
	 *
	 * @since 1.7.0
	 *
	 * @var int
	 */
	private $found_posts = 0;

	/**
	 * Equivalent of __construct() — implement our hooks.
	 *
	 * @since 1.0
	 *
	 * @uses add_action() to utilize WordPress Ajax functionality
	 */
	public function setup() {

		add_action( 'wp_ajax_searchwp_live_search', [ $this, 'search' ] );
		add_action( 'wp_ajax_nopriv_searchwp_live_search', [ $this, 'search' ] );

		add_filter( 'option_active_plugins', [ $this, 'control_active_plugins' ] );
		add_filter( 'site_option_active_sitewide_plugins', [ $this, 'control_active_plugins' ] );
	}

	/**
	 * Perform a search.
	 *
	 * @since 1.0
	 */
	public function search() {

		if ( ! empty( $_REQUEST['swpquery'] ) ) {
			$this->show_results( $this->search_get_args() );
		}

		// Short circuit to keep the overhead of an admin-ajax.php call to a minimum.
		die();
	}

	/**
	 * Get search arguments.
	 *
	 * @since 1.7.0
	 *
	 * @uses sanitize_text_field() to sanitize input
	 * @uses SearchWP_Live_Search_Client::get_posts_per_page() to retrieve the number of results to return
	 *
	 * @return array
	 */
	private function search_get_args() {

		$query = sanitize_text_field( wp_unslash( $_REQUEST['swpquery'] ) );

		if ( class_exists( 'SearchWP' ) ) {
			$args = $this->search_get_args_searchwp( $query );
		} else {
			$args = $this->search_get_args_native( $query );
		}

		$args['posts_per_page'] = ( isset( $_REQUEST['posts_per_page'] )
			? intval( $_REQUEST['posts_per_page'] )
			: $this->get_posts_per_page() );

		return apply_filters( 'searchwp_live_search_query_args', $args );
	}

	/**
	 * Get search arguments for SearchWP.
	 *
	 * @since 1.7.0
	 *
	 * @param string $query The search query.
	 *
	 * @return array
	 */
	private function search_get_args_searchwp( $query ) {

		return [
			'post_type'        => $this->search_get_args_post_type_searchwp(),
			'post_status'      => 'any', // We're limiting to a pre-set array of post IDs.
			'post__in'         => $this->search_get_args_post__in_searchwp( $query ),
			'orderby'          => 'post__in',
			'suppress_filters' => true,
		];
	}

	/**
	 * Get search arguments for native WordPress search.
	 *
	 * @since 1.7.0
	 *
	 * @param string $query The search query.
	 *
	 * @return array
	 */
	private function search_get_args_native( $query ) {

		return [
			's'           => $query,
			'post_status' => 'publish',
			'post_type'   => get_post_types(
				[
					'public'              => true,
					'exclude_from_search' => false,
				]
			),
		];
	}

	/**
	 * Get allowed post types for SearchWP search.
	 *
	 * @since 1.7.0
	 *
	 * @return array
	 */
	private function search_get_args_post_type_searchwp() {

		// Normally we could use 'any' post type because we've already found our IDs
		// but if you use 'any' WP_Query will still take into consideration exclude_from_search
		// when we eventually run our query_posts() in $this->show_results() so we're
		// going to rebuild our array from the engine configuration post types and use that.

		if ( function_exists( 'SWP' ) ) {
			return SWP()->get_enabled_post_types_across_all_engines();
		}

		if ( class_exists( '\\SearchWP\\Utils' ) ) {
			// SearchWP 4.0+.
			$global_engine_sources = \SearchWP\Utils::get_global_engine_source_names();

			$post_types = [];

			foreach ( $global_engine_sources as $global_engine_source ) {
				$indicator = 'post' . SEARCHWP_SEPARATOR;
				if ( $indicator === substr( $global_engine_source, 0, strlen( $indicator ) ) ) {
					$post_types[] = substr( $global_engine_source, strlen( $indicator ) );
				}
			}

			return $post_types;
		}

		return [];
	}

	/**
	 * Get post__in post ids using SearchWP.
	 *
	 * @since 1.7.0
	 *
	 * @param string $query The search query.
	 *
	 * @return array Search results comprised of Post IDs.
	 */
	private function search_get_args_post__in_searchwp( $query = '' ) {

		if ( defined( 'SEARCHWP_VERSION' ) && version_compare( SEARCHWP_VERSION, '3.99.0', '>=' ) ) {
			return $this->search_get_args_post__in_searchwp_v4( $query );
		}

		if ( class_exists( 'SearchWP' ) && method_exists( 'SearchWP', 'instance' ) ) {
			return $this->search_get_args_post__in_searchwp_v3( $query );
		}

		return [ 0 ];
	}

	/**
	 * Get post__in post ids using SearchWP v3.
	 *
	 * @since 1.7.0
	 *
	 * @param string $query The search query.
	 *
	 * @return array Search results comprised of Post IDs.
	 */
	private function search_get_args_post__in_searchwp_v3( $query ) {

		$searchwp = SearchWP::instance();

		// Set up custom posts per page.
		add_filter( 'searchwp_posts_per_page', [ $this, 'get_posts_per_page' ] );

		// Prevent loading Post objects, we only want IDs.
		add_filter( 'searchwp_load_posts', '__return_false' );

		$engine = isset( $_REQUEST['swpengine'] ) ? sanitize_key( $_REQUEST['swpengine'] ) : 'default';

		// Grab our post IDs.
		$results = $searchwp->search( $engine, $query );

		$this->found_posts = $searchwp->foundPosts;

		// If no results were found we need to force our impossible array.
		if ( empty( $results ) ) {
			return [ 0 ];
		}

		return $results;
	}

	/**
	 * Get post__in post ids using SearchWP v4.
	 *
	 * @since 1.7.0
	 *
	 * @param string $query The search query.
	 *
	 * @return array Search results comprised of Post IDs.
	 */
	private function search_get_args_post__in_searchwp_v4( $query ) {

		$results = new \SWP_Query( [
			's'              => $query,
			'engine'         => isset( $_REQUEST['swpengine'] ) ? sanitize_key( $_REQUEST['swpengine'] ) : 'default',
			'fields'         => 'ids',
			'posts_per_page' => $this->get_posts_per_page(),
		] );

		$this->found_posts = $results->found_posts;

		// If no results were found we need to force our impossible array.
		if ( empty( $results->posts ) ) {
			return [ 0 ];
		}

		return $results->posts;
	}

	/**
	 * Fire the results query and trigger the template loader.
	 *
	 * @since 1.0
	 *
	 * @param array $args WP_Query arguments array.
	 *
	 * @uses query_posts() to prep the WordPress environment in it's entirety for the template loader
	 * @uses sanitize_key() to sanitize input
	 * @uses SearchWP_Live_Search_Template
	 * @uses SearchWP_Live_Search_Template::get_template_part() to load the proper results template
	 */
	private function show_results( $args = [] ) {

		global $wp_query;

		// We're using query_posts() here because we want to prep the entire environment
		// for our template loader, allowing the developer to utilize everything they
		// normally would in a theme template (and reducing support requests).
		query_posts( $args ); // phpcs:ignore WordPress.WP.DiscouragedFunctions.query_posts_query_posts

		// Ensure a proper found_posts count for $wp_query.
		if ( class_exists( 'SearchWP' ) && ! empty( $this->found_posts ) ) {
			$wp_query->found_posts = $this->found_posts;
		}

		do_action( 'searchwp_live_search_alter_results', $args );

		// Optionally pass along the SearchWP engine if applicable.
		$engine = isset( $_REQUEST['swpengine'] ) ? sanitize_key( $_REQUEST['swpengine'] ) : '';

		// Output the results using the results template.
		$results = searchwp_live_search()->get( 'Template' );

		$results->get_template_part( 'search-results', $engine );
	}

	/**
	 * Retrieve the number of items to display.
	 *
	 * @since 1.0
	 *
	 * @uses apply_filters to ensure the posts per page can be filterable via searchwp_live_search_posts_per_page
	 * @uses absint()
	 *
	 * @return int $per_page the number of items to display
	 */
	public function get_posts_per_page() {

		// The default is 7 posts, but that can be filtered.
		return absint( apply_filters( 'searchwp_live_search_posts_per_page', 7 ) );
	}

	/**
	 * Potential (opt-in) performance tweak: skip any plugin that's not SearchWP-related.
	 *
	 * @since 1.0
	 *
	 * @param array $plugins Active plugins list.
	 *
	 * @return array
	 */
	public function control_active_plugins( $plugins ) {

		$applicable = apply_filters( 'searchwp_live_search_control_plugins_during_search', false );

		if ( ! $applicable || ! is_array( $plugins ) || empty( $plugins ) ) {
			return $plugins;
		}

		if ( ! isset( $_REQUEST['swpquery'] ) || empty( $_REQUEST['swpquery'] ) ) {
			return $plugins;
		}

		// The default plugin whitelist is anything SearchWP-related.
		$plugin_whitelist = [];
		foreach ( $plugins as $plugin_slug ) {
			if ( 0 === strpos( $plugin_slug, 'searchwp' ) ) {
				$plugin_whitelist[] = $plugin_slug;
			}
		}

		$active_plugins = (array) apply_filters( 'searchwp_live_search_plugin_whitelist', $plugin_whitelist );

		return array_values( $active_plugins );
	}
}
