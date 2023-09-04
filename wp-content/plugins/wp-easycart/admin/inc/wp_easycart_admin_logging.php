<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_logging' ) ) :

	final class wp_easycart_admin_logging {

		protected static $_instance = null;

		public $log_list_file;
		public $log_details_file;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			$this->log_list_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/logging/log-list.php';
			$this->log_details_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/logging/log-details.php';
		}

		public function load_log_list() {
			if ( isset( $_GET['ec_admin_form_action'] ) && ( ( isset( $_GET['response_id'] ) && 'edit' == $_GET['ec_admin_form_action'] ) || 'add-new' == $_GET['ec_admin_form_action'] ) ) {
				include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_details_logging.php' );
				$details = new wp_easycart_admin_details_logging();
				$details->output( sanitize_key( $_GET['ec_admin_form_action'] ) );
			} else {
				include( $this->log_list_file );
			}
		}
	}
endif;

function wp_easycart_admin_logging() {
	return wp_easycart_admin_logging::instance();
}
wp_easycart_admin_logging();
