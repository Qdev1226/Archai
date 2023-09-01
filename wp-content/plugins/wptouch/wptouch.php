<?php
/*
	Plugin Name: WPtouch Mobile Plugin
	Plugin URI: http://www.wptouch.com/
	Version: 4.3.53
	Description: Make a beautiful mobile-friendly version of your website with just a few clicks.
	Author: WPtouch
	Author URI: http://www.wptouch.com/
	Text Domain: wptouch-pro
	Domain Path: /lang
	License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
	Trademark: 'WPtouch' and 'WPtouch Pro' are trademarks of BraveNewCode Inc.; neither term can be re-used in conjuction with GPL v2 distributions or conveyances of this software under the license terms of the GPL v2 without express prior permission of BraveNewCode Inc.
*/

function wptouch_create_four_object() {
	if ( !defined( 'WPTOUCH_IS_PRO' ) ) {
		define( 'WPTOUCH_VERSION', '4.3.53' );

		define( 'WPTOUCH_BASE_NAME', basename( __FILE__, '.php' ) . '.php' );
		define( 'WPTOUCH_DIR', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . basename( __FILE__, '.php' ) );

		$data = explode( DIRECTORY_SEPARATOR, WPTOUCH_DIR );
		define( 'WPTOUCH_ROOT_NAME', $data[ count( (array) $data ) - 1 ] );

		define( 'WPTOUCH_PLUGIN_ACTIVATE_NAME', plugin_basename( __FILE__ ) );

		global $wptouch_pro;

		if ( !$wptouch_pro ) {
			// Load main configuration information - sets up directories and constants
			require_once( 'core/config.php' );

			// Load global functions
			require_once( 'core/globals.php' );

			// Load main compatibility file
			require_once( 'core/compat.php' );

			// Load main WPtouch Pro class
			require_once( 'core/class-wptouch-pro.php' );

			// Load main debugging class
			require_once( 'core/class-wptouch-pro-debug.php' );

			// Load right-to-left text code
			require_once( 'core/rtl.php' );

			$wptouch_pro = new WPtouchProFour;
			$wptouch_pro->initialize();

			do_action( 'wptouch_pro_loaded' );
		}
	}
}


function is_wptouch_pro_active() {
	$active_plugins = get_option( 'active_plugins', array() );
	if ( in_array( 'wptouch-pro/wptouch-pro.php', $active_plugins ) ) {
		return true;
	}

	return false;
}

function wptouch_disable_self() {
	if ( is_wptouch_pro_active() ) {
		var_dump( 'hai' );
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}
}

// Global WPtouch Pro activation hook
function wptouch_handle_activation() {
	global $wptouch_pro;
	if ( !$wptouch_pro ) {
		wptouch_create_four_object();
	}

	$wptouch_pro->handle_activation();
}

// Global WPtouch Pro deactivation hook
function wptouch_handle_deactivation() {
	global $wptouch_pro;
	if ( !$wptouch_pro ) {
		wptouch_create_four_object();
	}

	$wptouch_pro->handle_deactivation();
}

// Activation hook for some basic initialization
register_activation_hook( __FILE__,  'wptouch_handle_activation' );
register_deactivation_hook( __FILE__, 'wptouch_handle_deactivation' );

// Main WPtouch Pro activation hook
add_action( 'plugins_loaded', 'wptouch_create_four_object' );
add_action( 'admin_init', 'wptouch_disable_self' );

add_filter( 'wptouch_settings_page_before_render', 'wptouch_free_order_sections', 10, 2 );
function wptouch_free_order_sections( $page_info, $page_name ) {

	if ( $page_name == 'Theme Settings' ) {
		$weights = array();

		foreach ( $page_info->sections as $section_name => $section ) {
			$weights[ $section->weight ][ $section_name ] = $section;
		}

		ksort( $weights );

		$page_info->sections = array();

		foreach ( $weights as $weight => $sections ) {
			$page_info->sections = array_merge( $page_info->sections, $sections );
		}
	}

	return $page_info;
}

add_filter( 'wptouch_theme_title', 'wptouch_bauhaus_pro' );
function wptouch_bauhaus_pro( $name ) {
	if ( $name == 'Bauhaus' ) { $name = 'Bauhaus Pro'; }
	return $name;
}

if ( !is_wptouch_pro_active() ) {
	function wptouch_is_update_available() {
		return false;
	}
}

function wptouch_free_get_random_site( $amount ) {
	$home_url = get_home_url();

	$result = md5( $home_url, true );

	return $result % $amount;
}

/**
 * Display free newsletter notice + link to users.
 */
function wptouch_free_newsletter_notice() {
	if ( 1 === (int) get_option( 'wptouch-disable-free-newsletter-notice' ) ) {
		return;
	}
	?>
	<div class="notice notice-info js-free-newsletter-notice is-dismissible">
		<p><?php echo wp_kses_post( sprintf( __( 'Thanks for using WPtouch! If you\'d like to sign up for news and updates from the team you can %1$ssubscribe to our newsletter%2$s.', 'wptouch-pro' ), '<a href="' . admin_url( 'admin.php?page=wptouch-admin-general-settings#free-newsletter-signup' ) . '">', '</a>' ) ); ?></p>
		<button type="button" class="notice-dismiss">
			<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice (permanently).', 'wptouch-pro' ); ?></span>
		</button>
	</div>
	<?php
}
add_action( 'admin_notices', 'wptouch_free_newsletter_notice' );

function wptouch_disable_free_newsletter_signup_notice() {
	update_option( 'wptouch-disable-free-newsletter-notice', 1, false );
}
add_action( 'wp_ajax_disable_newsletter_notice', 'wptouch_disable_free_newsletter_signup_notice' );

