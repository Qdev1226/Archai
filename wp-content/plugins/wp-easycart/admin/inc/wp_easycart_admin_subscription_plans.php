<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_subscription_plans' ) ) :

	final class wp_easycart_admin_subscription_plans {

		protected static $_instance = null;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function load_subscription_plans_list() {
			if ( isset( $_GET['ec_admin_form_action'] ) && ( ( isset( $_GET['subscription_plan_id'] ) && 'edit' == $_GET['ec_admin_form_action'] ) || 'add-new' == $_GET['ec_admin_form_action'] ) ) {
				do_action( 'wp_easycart_admin_subscription_plans_details' );
			} else {
				do_action( 'wp_easycart_admin_subscription_plans_list' );
			}
		}
	}
endif;

function wp_easycart_admin_subscription_plans() {
	return wp_easycart_admin_subscription_plans::instance();
}
wp_easycart_admin_subscription_plans();