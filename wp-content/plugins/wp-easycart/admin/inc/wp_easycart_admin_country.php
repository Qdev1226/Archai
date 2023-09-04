<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_country' ) ) :

	final class wp_easycart_admin_country {

		protected static $_instance = null;

		public $country_list_file;
		public $country_details_file;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			$this->country_list_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/country-state/country-list.php';
			$this->country_details_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/country-state/country-details.php';

			/* Process Admin Messages */
			add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );

			/* Process Form Actions */
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_add_new_country' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_update_country' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_country' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_country' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_disable_country' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_enable_country' ) );
		}

		public function process_add_new_country() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return;
			}

			if ( isset( $_POST['ec_admin_form_action'] ) && 'add-new-country' == $_POST['ec_admin_form_action'] ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-country-details' ) ) {
					$result = $this->insert_country();
					wp_easycart_admin()->redirect( 'wp-easycart-settings', 'country', $result );
				}
			}
		}

		public function process_update_country() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return;
			}

			if ( isset( $_POST['ec_admin_form_action'] ) && 'update-country' == $_POST['ec_admin_form_action'] ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-country-details' ) ) {
					$result = $this->update_country();
					wp_easycart_admin()->redirect( 'wp-easycart-settings', 'country', $result );
				}
			}
		}

		public function process_delete_country() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return;
			}

			if ( isset( $_GET['subpage'] ) && 'country' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'delete-country' == $_GET['ec_admin_form_action'] && isset( $_GET['id_cnt'] ) && ! isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-country' ) ) {
					$result = $this->delete_country();
					wp_easycart_admin()->redirect( 'wp-easycart-settings', 'country', $result );
				}
			}
		}
		public function process_bulk_delete_country() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return;
			}

			if ( isset( $_GET['subpage'] ) && 'country' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'delete-country' == $_GET['ec_admin_form_action'] && ! isset( $_GET['id_cnt'] ) && isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-country' ) ) {
					$result = $this->bulk_delete_country();
					wp_easycart_admin()->redirect( 'wp-easycart-settings', 'country', $result );
				}
			}
		}

		public function process_bulk_disable_country() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return;
			}

			if ( isset( $_GET['subpage'] ) && 'country' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'bulk-enable-country' == $_GET['ec_admin_form_action'] && ! isset( $_GET['id_cnt'] ) && isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-country' ) ) {
					$result = $this->bulk_enable_country();
					wp_easycart_admin()->redirect( 'wp-easycart-settings', 'country', $result );
				}
			}
		}

		public function process_bulk_enable_country() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return;
			}

			if ( isset( $_GET['subpage'] ) && 'country' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'bulk-disable-country' == $_GET['ec_admin_form_action'] && ! isset( $_GET['id_cnt'] ) && isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-country' ) ) {
					$result = $this->bulk_disable_country();
					wp_easycart_admin()->redirect( 'wp-easycart-settings', 'country', $result );
				}
			}
		}

		public function add_success_messages( $messages ) {
			if ( isset( $_GET['success'] ) && 'country-inserted' == $_GET['success'] ) {
				$messages[] = __( 'Country successfully created', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'country-updated' == $_GET['success'] ) {
				$messages[] = __( 'Country successfully updated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'country-deleted' == $_GET['success'] ) {
				$messages[] = __( 'Country successfully deleted', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'country-bulk-enabled' == $_GET['success'] ) {
				$messages[] = __( 'Countries successfully enabled', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'country-bulk-disabled' == $_GET['success'] ) {
				$messages[] = __( 'Countries successfully disabled', 'wp-easycart' );
			}
			return $messages;
		}

		public function load_country_list() {
			if ( isset( $_GET['ec_admin_form_action'] ) && ( ( isset( $_GET['id_cnt'] ) && 'edit' == $_GET['ec_admin_form_action'] ) || 'add-new' == $_GET['ec_admin_form_action'] ) ) {
				include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_details_country.php' );
				$details = new wp_easycart_admin_details_country();
				$details->output( sanitize_key( $_GET['ec_admin_form_action'] ) );
			} else {
				include( $this->country_list_file );
			}
		}

		public function insert_country() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-country-details' ) ) {
				return false;
			}

			global $wpdb;

			$name_cnt = ( isset( $_POST['name_cnt'] ) ) ? sanitize_text_field( wp_unslash( $_POST['name_cnt'] ) ) : '';
			$iso2_cnt = ( isset( $_POST['iso2_cnt'] ) ) ? wp_easycart_admin_verification()->filter_chars( strtoupper( sanitize_text_field( wp_unslash( $_POST['iso2_cnt'] ) ) ), 2 ) : '';
			$iso3_cnt = ( isset( $_POST['iso3_cnt'] ) ) ? wp_easycart_admin_verification()->filter_chars( strtoupper( sanitize_text_field( wp_unslash( $_POST['iso3_cnt'] ) ) ), 2 ) : '';
			$sort_order = ( isset( $_POST['sort_order'] ) ) ? (int) $_POST['sort_order'] : 0;
			$vat_rate_cnt = ( isset( $_POST['vat_rate_cnt'] ) ) ? wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['vat_rate_cnt'] ) ) ) : '';
			$ship_to_active = 0;
			if ( isset( $_POST['ship_to_active'] ) ) {
				$ship_to_active = 1;
			}

			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_country( name_cnt, iso2_cnt, iso3_cnt, sort_order, vat_rate_cnt, ship_to_active ) VALUES( %s, %s, %s, %d, %s, %d )', $name_cnt, $iso2_cnt, $iso3_cnt, $sort_order, $vat_rate_cnt, $ship_to_active ) );
			$id_cnt = $wpdb->insert_id;
			do_action( 'wpeasycart_country_added', $id_cnt );

			return array( 'success' => 'country-inserted' );
		}

		public function update_country() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-country-details' ) ) {
				return false;
			}

			global $wpdb;

			$id_cnt = ( isset( $_POST['id_cnt'] ) ) ? (int) $_POST['id_cnt'] : 0;
			$name_cnt = ( isset( $_POST['name_cnt'] ) ) ? sanitize_text_field( wp_unslash( $_POST['name_cnt'] ) ) : '';
			$iso2_cnt = ( isset( $_POST['iso2_cnt'] ) ) ? wp_easycart_admin_verification()->filter_chars( strtoupper( sanitize_text_field( wp_unslash( $_POST['iso2_cnt'] ) ) ), 2 ) : '';
			$iso3_cnt = ( isset( $_POST['iso3_cnt'] ) ) ? wp_easycart_admin_verification()->filter_chars( strtoupper( sanitize_text_field( wp_unslash( $_POST['iso3_cnt'] ) ) ), 3 ) : '';
			$sort_order = ( isset( $_POST['sort_order'] ) ) ? (int) $_POST['sort_order'] : 0;
			$vat_rate_cnt = ( isset( $_POST['vat_rate_cnt'] ) ) ? wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['vat_rate_cnt'] ) ) ) : '';
			$ship_to_active = 0;
			if ( isset( $_POST['ship_to_active'] ) ) {
				$ship_to_active = 1;
			}

			$wpdb->query( $wpdb->prepare( 'UPDATE ec_country SET name_cnt = %s, iso2_cnt = %s, iso3_cnt = %s, sort_order = %d, vat_rate_cnt = %s, ship_to_active = %d WHERE id_cnt = %d', $name_cnt, $iso2_cnt, $iso3_cnt, $sort_order, $vat_rate_cnt, $ship_to_active, $id_cnt ) );
			do_action( 'wpeasycart_country_updated', $id_cnt );

			return array( 'success' => 'country-updated' );
		}

		public function delete_country() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-country' ) ) {
				return false;
			}

			global $wpdb;
			$id_cnt = ( isset( $_GET['id_cnt'] ) ) ? (int) $_GET['id_cnt'] : 0;
			do_action( 'wpeasycart_country_deleting', $id_cnt );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_country WHERE id_cnt = %d', $id_cnt ) );
			do_action( 'wpeasycart_country_deleted', $id_cnt );
			return array( 'success' => 'country-deleted' );
		}

		public function bulk_delete_country() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-country' ) ) {
				return false;
			}

			if ( ! isset( $_GET['bulk'] ) ) {
				return false;
			}

			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.
			foreach ( $bulk_ids as $bulk_id ) {
				do_action( 'wpeasycart_country_deleting', (int) $bulk_id );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_country WHERE id_cnt = %d', (int) $bulk_id ) );
				do_action( 'wpeasycart_country_deleted', (int) $bulk_id );
			}
			return array( 'success' => 'country-deleted' );
		}

		public function bulk_enable_country() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-country' ) ) {
				return false;
			}

			if ( ! isset( $_GET['bulk'] ) ) {
				return false;
			}

			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.
			foreach ( $bulk_ids as $bulk_id ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_country SET ship_to_active = 1 WHERE ec_country.id_cnt = %d', (int) $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_state SET ship_to_active = 1 WHERE ec_state.idcnt_sta = %d', (int) $bulk_id ) );
				do_action( 'wpeasycart_country_updated', (int) $bulk_id );
			}
			return array( 'success' => 'country-bulk-enabled' );
		}

		public function bulk_disable_country() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-country' ) ) {
				return false;
			}

			if ( ! isset( $_GET['bulk'] ) ) {
				return false;
			}

			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_country SET ship_to_active = 0 WHERE ec_country.id_cnt = %d', (int) $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_state SET ship_to_active = 0 WHERE ec_state.idcnt_sta = %d', (int) $bulk_id ) );
				do_action( 'wpeasycart_country_updated', (int) $bulk_id );
			}
			return array( 'success' => 'country-bulk-disabled' );
		}
	}
endif;

function wp_easycart_admin_country() {
	return wp_easycart_admin_country::instance();
}
wp_easycart_admin_country();
