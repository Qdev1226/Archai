<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_extensions' ) ) :

	final class wp_easycart_admin_extensions {

		protected static $_instance = null;

		public $extensions_display_file;
		public $extensions_dashboard_file;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			$this->extensions_display_file = EC_PLUGIN_DIRECTORY . '/admin/template/extensions/extensions-display.php';
			$this->extensions_dashboard_file = EC_PLUGIN_DIRECTORY . '/admin/template/extensions/extensions-dashboard.php';
			add_action( 'wpeasycart_admin_extensions_display', array( $this, 'load_dashboard' ) );
		}

		public function load_extensions() {
			include( $this->extensions_display_file );
		}

		public function load_dashboard() {
			if ( ! isset( $_GET['subpage'] ) ) {
				include( $this->extensions_dashboard_file );
			}
		}
	}
endif; // End if class_exists check

function wp_easycart_admin_extensions() {
	return wp_easycart_admin_extensions::instance();
}
wp_easycart_admin_extensions();
