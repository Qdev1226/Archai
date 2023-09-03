<?php
/*
Plugin Name: SearchWP Live Ajax Search
Plugin URI: https://searchwp.com/
Description: Enhance your search forms with live search, powered by SearchWP (if installed)
Version: 1.7.6
Requires PHP: 5.6
Author: SearchWP, LLC
Author URI: https://searchwp.com/
Text Domain: searchwp-live-ajax-search
Tested up to: 6.2

Copyright 2014-2023 SearchWP, LLC

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, see <http://www.gnu.org/licenses/>.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'SEARCHWP_LIVE_SEARCH_VERSION' ) ) {
	/**
	 * Plugin version.
	 *
	 * @since 1.7.0
	 */
	define( 'SEARCHWP_LIVE_SEARCH_VERSION', '1.7.6' );
}

if ( ! defined( 'SEARCHWP_LIVE_SEARCH_PLUGIN_DIR' ) ) {
	/**
	 * Plugin dir.
	 *
	 * @since 1.7.0
	 */
	define( 'SEARCHWP_LIVE_SEARCH_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'SEARCHWP_LIVE_SEARCH_PLUGIN_URL' ) ) {
	/**
	 * Plugin URL.
	 *
	 * @since 1.7.0
	 */
	define( 'SEARCHWP_LIVE_SEARCH_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'SEARCHWP_LIVE_SEARCH_PLUGIN_FILE' ) ) {
	/**
	 * Plugin file.
	 *
	 * @since 1.7.0
	 */
	define( 'SEARCHWP_LIVE_SEARCH_PLUGIN_FILE', __FILE__ );
}

/**
 * Returns an instance of the classes' container.
 *
 * @since 1.7.0
 *
 * @return SearchWP_Live_Search_Container
 */
function searchwp_live_search() {

	static $instance;

	if ( ! ( $instance instanceof SearchWP_Live_Search_Container ) ) {
		require_once SEARCHWP_LIVE_SEARCH_PLUGIN_DIR . 'includes/class-container.php';
		$instance = new SearchWP_Live_Search_Container();
	}

	return $instance;
}

searchwp_live_search()
	->incl( 'class-plugin.php' )
	->register( 'SearchWP_Live_Search' )
	->setup();
