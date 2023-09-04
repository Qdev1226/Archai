<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_verification' ) ) :

	final class wp_easycart_admin_verification {

		protected static $_instance = null;

		private $user_roles;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function print_nonce_field( $id, $action ) {
			echo '<input type="hidden" name="' . esc_attr( $id ) . '" id="' . esc_attr( $id ) . '" value="' . esc_attr( wp_create_nonce( $action ) ) . '" />';
		}

		public function verify_access( $action ) {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_manager' ) ) {
				return false;
			}

			if ( ! $this->verify_nonce( $action ) ) {
				return false;
			}

			return true;
		}

		public function verify_nonce( $action ) {
			if ( isset( $_POST['wp_easycart_nonce'] ) ) {
				if ( false === wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wp_easycart_nonce'] ) ), $action ) ) {
					return false;
				}
			} else if ( isset( $_GET['wp_easycart_nonce'] ) ) {
				if ( false === wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['wp_easycart_nonce'] ) ), $action ) ) {
					return false;
				}
			} else {
				return false;
			}

			return true;
		}

		public function filter_int( $value ) {
			 return (int) preg_replace( '/[^0-9]/', '', trim( $value ) );
		}

		public function filter_bool_int( $value ) {
			 return (int) substr( preg_replace( '/[^0-1]/', '', trim( $value ) ), 0, 1 );
		}

		public function filter_chars( $value, $length ) {
			return (string) substr( preg_replace( '/[^A-Za-z0-9]/', '', trim( $value ) ), 0, (int) $length );
		}

		public function filter_float( $value ) {
			return preg_replace( '/[^0-9\.\,]/', '', trim( $value ) );
		}

		public function filter_length( $value, $length ) {
			return substr( trim( stripslashes_deep( $value ) ), 0, $length );
		}

		public function filter_list( $value, $list ) {
			return ( in_array( $value, $list ) ) ? $value : '';
		}

		public function filter_checkbox( $var ) {
			return ( isset( $_POST[ $var ] ) && $_POST[ $var ] == '1' ) ? 1 : 0;
		}

		public function filter_url( $value ) {
			return ( filter_var( $value, FILTER_VALIDATE_URL ) ) ? $value : '';
		}

		public function min_filter( $value ) {
			return strip_tags( stripslashes_deep( $value ) );
		}

		public function valid_user_role( $role ) {
			$this->init_user_roles();
			if ( ! in_array( $role, $this->user_roles ) ) {
				return false;
			}

			return true;
		}

		private function init_user_roles() {
			if ( isset( $this->user_roles ) ) {
				return;
			}

			global $wpdb;
			$user_roles = $wpdb->get_results( 'SELECT role_label FROM ec_role ORDER BY role_label' );
			$this->user_roles = array();
			foreach ( $user_roles as $user_role ) {
				$this->user_roles[] = $user_role->role_label;
			}
		}
	}
endif;

function wp_easycart_admin_verification() {
	return wp_easycart_admin_verification::instance();
}
wp_easycart_admin_verification();
