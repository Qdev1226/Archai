<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_fee' ) ) :

	final class wp_easycart_admin_fee {

		protected static $_instance = null;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {}

		public function load_fee_list() {
			if ( isset( $_GET['ec_admin_form_action'] ) && ( ( isset( $_GET['fee_id'] ) && 'edit' == $_GET['ec_admin_form_action'] ) || 'add-new' == $_GET['ec_admin_form_action'] ) ) {
				do_action( 'wp_easycart_admin_fee_details' );
			} else {
				do_action( 'wp_easycart_admin_fee_list' );
			}
		}
	}
endif;

function wp_easycart_admin_fee() {
	return wp_easycart_admin_fee::instance();
}
wp_easycart_admin_fee();
