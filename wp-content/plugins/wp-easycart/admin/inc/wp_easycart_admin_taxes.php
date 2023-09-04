<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_taxes' ) ) :

	final class wp_easycart_admin_taxes {

		protected static $_instance = null;

		public $tax_setup_file;
		public $success_messages_file;
		public $tax_by_state_setup_file;
		public $tax_by_country_setup_file;
		public $global_tax_setup_file;
		public $duty_setup_file;
		public $vat_setup_file;
		public $canada_tax_setup_file;
		public $upgrade_file;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			global $wpdb;
			$this->tax_setup_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/taxes/tax-setup.php';
			$this->success_messages_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/taxes/success-messages.php';
			$this->tax_by_state_setup_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/taxes/tax-by-state-setup.php';
			$this->tax_by_country_setup_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/taxes/tax-by-country-setup.php';
			$this->global_tax_setup_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/taxes/global-tax-setup.php';
			$this->duty_setup_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/taxes/duty-tax-setup.php';
			$this->vat_setup_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/taxes/vat-setup.php';
			$this->canada_tax_setup_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/taxes/canada-tax-setup.php';
			$this->upgrade_file = EC_PLUGIN_DIRECTORY . '/admin/template/upgrade/upgrade-simple.php';
			add_action( 'wpeasycart_admin_taxes_success', array( $this, 'load_success_messages' ) );
			add_action( 'wpeasycart_admin_tax_setup', array( $this, 'load_tax_by_state_setup' ) );
			add_action( 'wpeasycart_admin_tax_setup', array( $this, 'load_tax_by_country_setup' ) );
			add_action( 'wpeasycart_admin_tax_setup', array( $this, 'load_global_tax_setup' ) );
			add_action( 'wpeasycart_admin_tax_setup', array( $this, 'load_duty_setup' ) );
			add_action( 'wpeasycart_admin_tax_setup', array( $this, 'load_vat_setup' ) );
			add_action( 'wpeasycart_admin_tax_setup', array( $this, 'load_tax_cloud_setup' ) );
			add_action( 'wpeasycart_admin_tax_setup', array( $this, 'load_tax_jar_setup' ) );
			add_action( 'wpeasycart_admin_tax_setup', array( $this, 'load_canada_tax_setup' ) );
		}

		public function load_tax_setup() {
			include( $this->tax_setup_file );
		}

		public function load_success_messages() {
			include( $this->success_messages_file );
		}

		public function load_tax_by_state_setup() {
			include( $this->tax_by_state_setup_file );
		}

		public function load_tax_by_country_setup() {
			include( $this->tax_by_country_setup_file );
		}

		public function load_global_tax_setup() {
			include( $this->global_tax_setup_file );
		}

		public function load_duty_setup() {
			include( $this->duty_setup_file );
		}

		public function load_vat_setup() {
			include( $this->vat_setup_file );
		}

		public function load_canada_tax_setup() {
			include( $this->canada_tax_setup_file );
		}

		public function load_tax_cloud_setup() {
			$upgrade_icon = 'dashicons-cloud';
			$upgrade_title = __( 'Tax Cloud for USA', 'wp-easycart' );
			$upgrade_subtitle = __( 'TaxCloud API Information', 'wp-easycart' );
			$upgrade_checkbox_label = apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:5px;"></span>' ) . ' ' . __( 'Enable TaxCloud', 'wp-easycart' );
			$upgrade_button_label = __( 'Save Setup', 'wp-easycart' );
			include( $this->upgrade_file );
		}

		public function load_tax_jar_setup() {
			$upgrade_icon = 'dashicons-cloud';
			$upgrade_title = __( 'TaxJar', 'wp-easycart' );
			$upgrade_subtitle = __( 'TaxJar API Information', 'wp-easycart' );
			$upgrade_checkbox_label = apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:5px;"></span>' ) . ' ' . __( 'Enable TaxJar', 'wp-easycart' );
			$upgrade_button_label = __( 'Save Setup', 'wp-easycart' );
			include( $this->upgrade_file );
		}

		public function save_state_tax_rate( $taxrate_id, $state_id, $rate ) {
			global $wpdb;
			$state = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_state WHERE id_sta = %d', $state_id ) );
			$country = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_country WHERE id_cnt = %d', $state->idcnt_sta ) );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_taxrate SET state_code = %s, country_code = %s, state_rate = %s WHERE taxrate_id = %d', $state->code_sta, $country->iso2_cnt, $rate, $taxrate_id ) );
		}

		public function add_state_tax_rate( $state_id, $rate ) {
			global $wpdb;
			$state = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_state WHERE id_sta = %d', $state_id ) );
			$country = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_country WHERE id_cnt = %d', $state->idcnt_sta ) );
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_taxrate( tax_by_state, state_code, country_code, state_rate ) VALUES( 1, %s, %s, %s )', $state->code_sta, $country->iso2_cnt, $rate ) );
			return $wpdb->insert_id;
		}

		public function delete_state_tax_rate( $taxrate_id ) {
			global $wpdb;
			if ( is_array( $taxrate_id ) ) {
				foreach ( $taxrate_id as $id ) {
					$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_taxrate WHERE taxrate_id = %d', $id ) );
				}
			} else {
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_taxrate WHERE taxrate_id = %d', $taxrate_id ) );
			}
			$rates = $wpdb->get_results( 'SELECT * FROM ec_taxrate WHERE tax_by_state = 1' );
			return count( $rates );
		}

		public function save_country_tax_rate( $taxrate_id, $country_id, $rate ) {
			global $wpdb;
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_taxrate SET country_code = %s, country_rate = %s WHERE taxrate_id = %d', $country_id, $rate, $taxrate_id ) );
		}

		public function add_country_tax_rate( $country_id, $rate ) {
			global $wpdb;
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_taxrate( tax_by_country, country_code, country_rate ) VALUES( 1, %s, %s )', $country_id, $rate ) );
			return $wpdb->insert_id;
		}

		public function delete_country_tax_rate( $taxrate_id ) {
			global $wpdb;
			if ( is_array( $taxrate_id ) ) {
				foreach ( $taxrate_id as $id ) {
					$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_taxrate WHERE taxrate_id = %d', $id ) );
				}
			} else {
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_taxrate WHERE taxrate_id = %d', $taxrate_id ) );
			}
			$rates = $wpdb->get_results( 'SELECT * FROM ec_taxrate WHERE tax_by_country = 1' );
			return count( $rates );
		}

		public function save_global_tax_rate() {
			global $wpdb;
			if ( ! isset( $_POST['ec_option_use_global_tax'] ) || $_POST['ec_option_use_global_tax'] == '0' ) {
				$wpdb->query( 'DELETE FROM ec_taxrate WHERE tax_by_all = 1' );
			} else {
				$global_tax_row = $wpdb->get_row( 'SELECT ec_taxrate.* FROM ec_taxrate WHERE tax_by_all = 1' );
				if ( $global_tax_row ) {
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_taxrate SET all_rate = %s WHERE taxrate_id = %d',
						wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_global_tax_rate'] ) ) ),
						$global_tax_row->taxrate_id
					) );
					return wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_global_tax_rate'] ) ) );
				} else {
					$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_taxrate( tax_by_all, all_rate ) VALUES( 1, %s )', wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_global_tax_rate'] ) ) ) ) );
				}
			}
		}

		public function save_duty_tax_rate() {
			global $wpdb;
			if ( ! isset( $_POST['ec_option_use_duty_tax'] ) || 0 == $_POST['ec_option_use_duty_tax'] ) {
				$wpdb->query( 'DELETE FROM ec_taxrate WHERE tax_by_duty = 1' );
			} else {
				$duty_tax_row = $wpdb->get_row( 'SELECT ec_taxrate.* FROM ec_taxrate WHERE tax_by_duty = 1' );
				if ( $duty_tax_row ) {
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_taxrate SET duty_rate = %s, duty_exempt_country_code = %s WHERE taxrate_id = %d', 
						wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_duty_tax_rate'] ) ) ), 
						wp_easycart_admin_verification()->filter_chars( sanitize_text_field( wp_unslash( $_POST['ec_duty_exempt_country_code'] ) ), 2 ),
						(int) $duty_tax_row->taxrate_id
					) );
				} else {
					$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_taxrate( tax_by_duty, duty_rate, duty_exempt_country_code ) VALUES( 1, %s, %s )', 
						wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_duty_tax_rate'] ) ) ),
						wp_easycart_admin_verification()->filter_chars( sanitize_text_field( wp_unslash( $_POST['ec_duty_exempt_country_code'] ) ), 2 )
					) );
				}
			}
		}

		public function save_vat_tax_settings() {
			global $wpdb;
			$vat_tax_row = $wpdb->get_row( 'SELECT ec_taxrate.* FROM ec_taxrate WHERE tax_by_vat = 1 OR tax_by_single_vat = 1' );
			$options = array( 'ec_option_validate_vat_registration_number' );
			$options_text = array( 'ec_option_vat_custom_rate', 'ec_option_vat_rounding', 'ec_option_vatlayer_api_key' );

			if ( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $options ) ) {
				$val = ( isset( $_POST['val'] ) && $_POST['val'] == '1' ) ? 1 : 0;
				update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), $val );

			} else if ( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $options_text ) ) {
				update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), sanitize_text_field( wp_unslash( $_POST['val'] ) ) );

			} else if ( isset( $_POST['update_var'] ) && $_POST['update_var'] == 'vat_type' ) {
				if ( '0' == $_POST['val'] ) {
					$wpdb->query( 'DELETE FROM ec_taxrate WHERE tax_by_vat = 1 OR tax_by_single_vat = 1' );

				} else {
					$tax_by_vat = ( $_POST['val'] == 'tax_by_vat' ) ? 1 : 0;
					$tax_by_single_vat = ( $_POST['val'] == 'tax_by_single_vat' ) ? 1 : 0;
					if ( $vat_tax_row ) {
						$wpdb->query( $wpdb->prepare( 'UPDATE ec_taxrate SET tax_by_vat = %d, tax_by_single_vat = %d WHERE taxrate_id = %d', $tax_by_vat, $tax_by_single_vat, (int) $vat_tax_row->taxrate_id ) );

					} else {
						$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_taxrate( tax_by_vat, tax_by_single_vat ) VALUES( %d, %d )', $tax_by_vat, $tax_by_single_vat ) );
					}
				}

			} else if ( isset( $_POST['update_var'] ) && 'ec_vat_pricing_method' == $_POST['update_var'] ) {
				$vat_included = ( (int) $_POST['val'] ) ? 1 : 0;
				$vat_added = ( ! (int) $_POST['val'] ) ? 1 : 0;
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_taxrate SET vat_included = %d, vat_added = %d WHERE taxrate_id = %d', $vat_included, $vat_added, (int) $vat_tax_row->taxrate_id ) );

			} else if ( isset( $_POST['update_var'] ) && 'ec_default_vat_rate' == $_POST['update_var'] ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_taxrate SET vat_rate = %s WHERE taxrate_id = %d', sanitize_text_field( wp_unslash( $_POST['val'] ) ), (int) $vat_tax_row->taxrate_id ) );

			} else if ( isset( $_POST['update_var'] ) && 'ec_option_no_vat_on_shipping' == $_POST['update_var'] ) {
				$val = ( isset( $_POST['val'] ) && $_POST['val'] == '0' ) ? 1 : 0;
				update_option( 'ec_option_no_vat_on_shipping', $val );

			}
		}

		public function save_vat_country_tax_rate( $country_id, $rate, $b2b_enabled ) {
			global $wpdb;
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_country SET vat_rate_cnt = %s, vat_b2b_enabled = %d WHERE id_cnt = %s', $rate, $b2b_enabled, $country_id ) );
			return $country_id;
		}

		public function save_canada_tax_rate() {
			global $wpdb;
			update_option( 'ec_option_enable_easy_canada_tax', (int) $_POST['ec_option_enable_easy_canada_tax'] );
			$canada_tax_options = array();
			$provinces = array( 'alberta', 'british_columbia', 'manitoba', 'new_brunswick', 'newfoundland', 'northwest_territories', 'nova_scotia', 'nunavut', 'ontario', 'prince_edward_island', 'quebec', 'saskatchewan', 'yukon' );
			$user_roles = $wpdb->get_results( 'SELECT * FROM ec_role WHERE role_label != "admin"' );
			foreach ( $provinces as $province ) {
				foreach ( $user_roles as $user_role ) {
					if ( isset( $_POST['ec_canada_tax'] ) && isset( $_POST['ec_canada_tax'][ 'ec_option_collect_' . $province . '_tax_' . $user_role->role_label ] ) ) {
						$canada_tax_options[ 'ec_option_collect_' . $province . '_tax_' . $user_role->role_label ] = wp_easycart_admin_verification()->filter_bool_int( sanitize_text_field( wp_unslash( $_POST['ec_canada_tax']['ec_option_collect_' . $province . '_tax_' . $user_role->role_label] ) ) );
					}
					if ( isset( $_POST['ec_canada_tax'] ) && isset( $_POST['ec_canada_tax'][ 'ec_option_' . $province . '_tax_' . $user_role->role_label . '_gst' ] ) ) {
						$canada_tax_options[ 'ec_option_' . $province . '_tax_' . $user_role->role_label . '_gst' ] = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_canada_tax'][ 'ec_option_' . $province . '_tax_' . $user_role->role_label . '_gst' ] ) ) );
					}
					if ( isset( $_POST['ec_canada_tax'] ) && isset( $_POST['ec_canada_tax'][ 'ec_option_' . $province . '_tax_' . $user_role->role_label . '_pst' ] ) ) {
						$canada_tax_options[ 'ec_option_' . $province . '_tax_' . $user_role->role_label . '_pst' ] = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_canada_tax'][ 'ec_option_' . $province . '_tax_' . $user_role->role_label . '_pst' ] ) ) );
					}
					if ( isset( $_POST['ec_canada_tax'] ) && isset( $_POST['ec_canada_tax'][ 'ec_option_' . $province . '_tax_' . $user_role->role_label . '_hst' ] ) ) {
						$canada_tax_options[ 'ec_option_' . $province . '_tax_' . $user_role->role_label . '_hst' ] = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_canada_tax'][ 'ec_option_' . $province . '_tax_' . $user_role->role_label . '_hst' ] ) ) );
					}
				}
			}
			update_option( 'ec_option_canada_tax_options', $canada_tax_options );
		}

		public function save_tax_cloud() {
			update_option( 'ec_option_tax_cloud_api_id', sanitize_text_field( wp_unslash( $_POST['ec_option_tax_cloud_api_id'] ) ) );
			update_option( 'ec_option_tax_cloud_api_key', sanitize_text_field( wp_unslash( $_POST['ec_option_tax_cloud_api_key'] ) ) );
			update_option( 'ec_option_tax_cloud_address', sanitize_text_field( wp_unslash( $_POST['ec_option_tax_cloud_address'] ) ) );
			update_option( 'ec_option_tax_cloud_city', sanitize_text_field( wp_unslash( $_POST['ec_option_tax_cloud_city'] ) ) );
			update_option( 'ec_option_tax_cloud_state', sanitize_text_field( wp_unslash( $_POST['ec_option_tax_cloud_state'] ) ) );
			update_option( 'ec_option_tax_cloud_zip', sanitize_text_field( wp_unslash( $_POST['ec_option_tax_cloud_zip'] ) ) );
		}
	}
endif;

function wp_easycart_admin_taxes() {
	return wp_easycart_admin_taxes::instance();
}
wp_easycart_admin_taxes();

add_action( 'wp_ajax_ec_admin_ajax_save_state_tax_rate', 'ec_admin_ajax_save_state_tax_rate' );
function ec_admin_ajax_save_state_tax_rate() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-tax-settings' ) ) {
		return false;
	}

	wp_easycart_admin_taxes()->save_state_tax_rate( (int) $_POST['id'], wp_easycart_admin_verification()->filter_chars( sanitize_text_field( wp_unslash( $_POST['ec_state_code'] ) ), 2 ), wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['state_rate'] ) ) ) );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_delete_state_tax_rate', 'ec_admin_ajax_delete_state_tax_rate' );
function ec_admin_ajax_delete_state_tax_rate() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-tax-settings' ) ) {
		return false;
	}

	$rate_count = wp_easycart_admin_taxes()->delete_state_tax_rate( (int) $_POST['id'] );
	echo esc_attr( $rate_count );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_insert_state_tax_rate', 'ec_admin_ajax_insert_state_tax_rate' );
function ec_admin_ajax_insert_state_tax_rate() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-tax-settings' ) ) {
		return false;
	}

	global $wpdb;
	$taxrate_id = wp_easycart_admin_taxes()->add_state_tax_rate( wp_easycart_admin_verification()->filter_chars( sanitize_text_field( wp_unslash( $_POST['ec_state_code'] ) ), 2 ), wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['state_rate'] ) ) ) );
	if ( $taxrate_id ) {
		echo esc_attr( $taxrate_id );
	} else {
		echo "error";
	}
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_country_tax_rate', 'ec_admin_ajax_save_country_tax_rate' );
function ec_admin_ajax_save_country_tax_rate() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-tax-settings' ) ) {
		return false;
	}

	wp_easycart_admin_taxes()->save_country_tax_rate( (int) $_POST['id'], sanitize_text_field( wp_unslash( $_POST['ec_country_code'] ) ), wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['country_rate'] ) ) ) );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_delete_country_tax_rate', 'ec_admin_ajax_delete_country_tax_rate' );
function ec_admin_ajax_delete_country_tax_rate() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-tax-settings' ) ) {
		return false;
	}

	$rate_count = wp_easycart_admin_taxes()->delete_country_tax_rate( (int) $_POST['id'] );
	echo esc_attr( $rate_count );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_insert_country_tax_rate', 'ec_admin_ajax_insert_country_tax_rate' );
function ec_admin_ajax_insert_country_tax_rate() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-tax-settings' ) ) {
		return false;
	}

	global $wpdb;
	$taxrate_id = wp_easycart_admin_taxes()->add_country_tax_rate( sanitize_text_field( wp_unslash( $_POST['ec_country_code'] ) ), wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['country_rate'] ) ) ) );
	echo esc_attr( $taxrate_id );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_update_global_tax_rate', 'ec_admin_ajax_update_global_tax_rate' );
function ec_admin_ajax_update_global_tax_rate() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-tax-settings' ) ) {
		return false;
	}

	$taxrate_id = wp_easycart_admin_taxes()->save_global_tax_rate();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_update_duty_tax_rate', 'ec_admin_ajax_update_duty_tax_rate' );
function ec_admin_ajax_update_duty_tax_rate() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-tax-settings' ) ) {
		return false;
	}

	$taxrate_id = wp_easycart_admin_taxes()->save_duty_tax_rate();
	echo esc_attr( $taxrate_id );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_vat_tax_settings', 'ec_admin_ajax_save_vat_tax_settings' );
function ec_admin_ajax_save_vat_tax_settings() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-tax-settings' ) ) {
		return false;
	}

	wp_easycart_admin_taxes()->save_vat_tax_settings();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_update_vat_tax_rate', 'ec_admin_ajax_update_vat_tax_rate' );
function ec_admin_ajax_update_vat_tax_rate() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-tax-settings' ) ) {
		return false;
	}

	$taxrate_id = wp_easycart_admin_taxes()->save_vat_tax_rate();
	echo esc_attr( $taxrate_id );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_insert_vat_country_tax_rate', 'ec_admin_ajax_insert_vat_country_tax_rate' );
function ec_admin_ajax_insert_vat_country_tax_rate() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-tax-settings' ) ) {
		return false;
	}

	global $wpdb;
	$id = wp_easycart_admin_taxes()->save_vat_country_tax_rate( sanitize_text_field( wp_unslash( $_POST['ec_country_code'] ) ), wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['country_rate'] ) ) ), (int) $_POST['vat_b2b_enabled'] );
	echo esc_attr( $id );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_vat_country_tax_rate', 'ec_admin_ajax_save_vat_country_tax_rate' );
function ec_admin_ajax_save_vat_country_tax_rate() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-tax-settings' ) ) {
		return false;
	}

	wp_easycart_admin_taxes()->save_vat_country_tax_rate( sanitize_text_field( wp_unslash( $_POST['ec_country_code'] ) ), wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['country_rate'] ) ) ), (int) $_POST['vat_b2b_enabled'] );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_delete_vat_country_tax_rate', 'ec_admin_ajax_delete_vat_country_tax_rate' );
function ec_admin_ajax_delete_vat_country_tax_rate() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-tax-settings' ) ) {
		return false;
	}

	global $wpdb;
	if ( is_array( $_POST['id'] ) ) {
		foreach ( (array) $_POST['id'] as $id ) { // XSS OK. Forced array and each item sanitized.
			wp_easycart_admin_taxes()->save_vat_country_tax_rate( (int) $id, 0, 0 );
		}
	} else {
		wp_easycart_admin_taxes()->save_vat_country_tax_rate( (int) $_POST['id'], 0, 0 );
	}
	$rows = $wpdb->get_results( 'SELECT * FROM ec_country WHERE vat_rate_cnt > 0' );
	echo esc_attr( count( $rows ) );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_update_canada_country_tax_rate', 'ec_admin_ajax_update_canada_country_tax_rate' );
function ec_admin_ajax_update_canada_country_tax_rate() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-tax-settings' ) ) {
		return false;
	}

	wp_easycart_admin_taxes()->save_canada_tax_rate();
	die();
}
