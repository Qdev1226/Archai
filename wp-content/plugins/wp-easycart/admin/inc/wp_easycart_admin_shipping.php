<?php
class wp_easycart_admin_shipping {
	public $shipping_rates_file;
	public $shipping_setup_file;
	public $shipping_rate_options_file;
	public $packing_slip_file;
	public $price_triggers_file;
	public $weight_triggers_file;
	public $quantity_triggers_file;
	public $percentage_based_file;
	public $static_rates_file;
	public $fraktjakt_file;

	public $country_list_file;
	public $state_list_file;
	public $shipping_zones_list_file;
	public $basic_shipping_options_file;

	public $edit_zone;
	public $edit_zone_item;

	public function __construct() {
		global $wpdb;

		$this->shipping_rates_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/shipping/shipping-rates.php';
		$this->shipping_setup_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/shipping/shipping-settings.php';
		$this->shipping_rate_options_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/shipping/shipping-rate-options.php';
		$this->packing_slip_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/shipping/packing-slip.php';
		$this->price_triggers_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/shipping/price-based.php';
		$this->weight_triggers_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/shipping/weight-based.php';
		$this->quantity_triggers_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/shipping/quantity-based.php';
		$this->percentage_based_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/shipping/percentage-rates.php';
		$this->static_rates_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/shipping/static-rates.php';
		$this->fraktjakt_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/shipping/fraktjakt.php';

		$this->country_list_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/shipping/country-list.php';
		$this->state_list_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/shipping/state-list.php';
		$this->shipping_zones_list_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/shipping/shipping-zones.php';
		$this->basic_shipping_options_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/shipping/shipping-basic-options.php';

		$this->edit_zone = null;
		$this->edit_zone_item = null;
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'edit-zone' ) {
			$this->edit_zone = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_zone.* FROM ec_zone WHERE zone_id = %d', (int) $_GET['zone_id'] ) );
		}
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'edit-zone-item' ) {
			$this->edit_zone = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_zone_to_location.* FROM ec_zone_to_location WHERE zone_to_location = %d', (int) $_GET['zone_to_location_id'] ) );
		}

		add_action( 'wpeasycart_admin_shipping_rates_methods', array( $this, 'load_shipping_methods' ) );
		add_action( 'wpeasycart_admin_shipping_rates', array( $this, 'load_price_triggers' ) );
		add_action( 'wpeasycart_admin_shipping_rates', array( $this, 'load_weight_triggers' ) );
		add_action( 'wpeasycart_admin_shipping_rates', array( $this, 'load_quantity_triggers' ) );
		add_action( 'wpeasycart_admin_shipping_rates', array( $this, 'load_percentage_based' ) );
		add_action( 'wpeasycart_admin_shipping_rates', array( $this, 'load_static_rates' ) );
		add_action( 'wpeasycart_admin_shipping_rates', array( $this, 'load_fraktjakt' ) );

		add_action( 'wpeasycart_admin_shipping_setup', array( $this, 'load_basic_shipping_options' ), 1 );
		add_action( 'wpeasycart_admin_shipping_setup', array( $this, 'load_packing_slip_settings' ) );
		add_action( 'wpeasycart_admin_shipping_setup', array( $this, 'load_country_list' ) );
		add_action( 'wpeasycart_admin_shipping_setup', array( $this, 'load_state_list' ) );
		add_action( 'wpeasycart_admin_shipping_setup', array( $this, 'load_shipping_zones_list' ) );
	}

	public function load_shipping_rates() {
		include( $this->shipping_rates_file );
	}

	public function load_shipping_setup() {
		include( $this->shipping_setup_file );
	}

	public function load_shipping_methods() {
		include( $this->shipping_rate_options_file );
	}

	public function load_price_triggers() {
		include( $this->price_triggers_file );
	}

	public function load_weight_triggers() {
		include( $this->weight_triggers_file );
	}

	public function load_quantity_triggers() {
		include( $this->quantity_triggers_file );
	}

	public function load_percentage_based() {
		include( $this->percentage_based_file );
	}

	public function load_static_rates() {
		include( $this->static_rates_file );
	}

	public function load_fraktjakt() {
		include( $this->fraktjakt_file );
	}

	public function load_country_list() {
		include( $this->country_list_file );
	}

	public function load_state_list() {
		include( $this->state_list_file );
	}

	public function load_shipping_zones_list() {
		include( $this->shipping_zones_list_file );
	}

	public function load_basic_shipping_options() {
		include( $this->basic_shipping_options_file );
	}

	public function load_packing_slip_settings() {
		include( $this->packing_slip_file );
	}

	public function update_shipping_method() {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( 'UPDATE ec_setting SET shipping_method = %s', sanitize_text_field( wp_unslash( $_POST['ec_option_shipping_method'] ) ) ) );
	}

	public function delete_shipping_rate( $shippingrate_id ) {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_shippingrate WHERE shippingrate_id = %d', $shippingrate_id ) );
	}

	public function add_shipping_price_trigger() {
		global $wpdb;
		$wpdb->query(
			$wpdb->prepare(
				'INSERT INTO ec_shippingrate( is_price_based, trigger_rate, shipping_rate, zone_id ) VALUES( 1, %s, %s, %d )',
				sanitize_text_field( wp_unslash( $_POST['ec_admin_new_price_trigger'] ) ),
				wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_admin_new_price_trigger_rate'] ) ) ),
				(int) $_POST['ec_admin_new_price_trigger_zone_id']
			)
		);
		return $wpdb->insert_id;
	}

	public function update_shipping_price_triggers() {
		global $wpdb;
		$shipping_rates = $wpdb->get_results( 'SELECT * FROM ec_shippingrate WHERE is_price_based = 1' );
		foreach ( $shipping_rates as $trigger ) {
			$wpdb->query(
				$wpdb->prepare(
					'UPDATE ec_shippingrate SET trigger_rate = %s, shipping_rate = %s, zone_id = %d WHERE shippingrate_id = %d',
					sanitize_text_field( wp_unslash( $_POST[ 'ec_admin_price_trigger_' . (int) $trigger->shippingrate_id ] ) ),
					wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST[ 'ec_admin_price_trigger_rate_' . (int) $trigger->shippingrate_id ] ) ) ),
					(int) $_POST['ec_admin_price_trigger_zone_id_'.$trigger->shippingrate_id],
					(int) $trigger->shippingrate_id
				)
			);
		}
	}

	public function add_shipping_weight_trigger() {
		global $wpdb;
		$wpdb->query(
			$wpdb->prepare(
				'INSERT INTO ec_shippingrate( is_weight_based, trigger_rate, shipping_rate, zone_id ) VALUES( 1, %s, %s, %d )',
				sanitize_text_field( wp_unslash( $_POST['ec_admin_new_weight_trigger'] ) ),
				wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_admin_new_weight_trigger_rate'] ) ) ),
				(int) $_POST['ec_admin_new_weight_trigger_zone_id']
			)
		);
		return $wpdb->insert_id;
	}

	public function update_shipping_weight_triggers() {
		global $wpdb;
		$shipping_rates = $wpdb->get_results( 'SELECT * FROM ec_shippingrate WHERE is_weight_based = 1' );
		foreach ( $shipping_rates as $trigger ) {
			$wpdb->query(
				$wpdb->prepare(
					'UPDATE ec_shippingrate SET trigger_rate = %s, shipping_rate = %s, zone_id = %d WHERE shippingrate_id = %d',
					sanitize_text_field( wp_unslash( $_POST[ 'ec_admin_weight_trigger_' . (int) $trigger->shippingrate_id ] ) ),
					wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST[ 'ec_admin_weight_trigger_rate_' . (int) $trigger->shippingrate_id ] ) ) ),
					(int) $_POST[ 'ec_admin_weight_trigger_zone_id_' . (int) $trigger->shippingrate_id ],
					(int) $trigger->shippingrate_id
				)
			);
		}
	}

	public function add_shipping_quantity_trigger() {
		global $wpdb;
		$wpdb->query(
			$wpdb->prepare(
				'INSERT INTO ec_shippingrate( is_quantity_based, trigger_rate, shipping_rate, zone_id ) VALUES( 1, %s, %s, %d )',
				sanitize_text_field( wp_unslash( $_POST['ec_admin_new_quantity_trigger'] ) ),
				wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_admin_new_quantity_trigger_rate'] ) ) ),
				(int) $_POST['ec_admin_new_quantity_trigger_zone_id']
			)
		);
		return $wpdb->insert_id;
	}

	public function update_shipping_quantity_triggers() {
		global $wpdb;
		$shipping_rates = $wpdb->get_results( 'SELECT * FROM ec_shippingrate WHERE is_quantity_based = 1' );
		foreach ( $shipping_rates as $trigger ) {
			$wpdb->query(
				$wpdb->prepare(
					'UPDATE ec_shippingrate SET trigger_rate = %s, shipping_rate = %s, zone_id = %d WHERE shippingrate_id = %d',
					sanitize_text_field( wp_unslash( $_POST[ 'ec_admin_quantity_trigger_' . (int) $trigger->shippingrate_id ] ) ),
					wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST[ 'ec_admin_quantity_trigger_rate_' . (int) $trigger->shippingrate_id ] ) ) ),
					(int) $_POST['ec_admin_quantity_trigger_zone_id_'.$trigger->shippingrate_id],
					(int) $trigger->shippingrate_id
				)
			);
		}
	}

	public function add_shipping_percentage_trigger() {
		global $wpdb;
		$wpdb->query(
			$wpdb->prepare(
				'INSERT INTO ec_shippingrate( is_percentage_based, trigger_rate, shipping_rate, zone_id ) VALUES( 1, %s, %s, %d )',
				sanitize_text_field( wp_unslash( $_POST['ec_admin_new_percentage_trigger'] ) ),
				wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_admin_new_percentage_trigger_rate'] ) ) ),
				(int) $_POST['ec_admin_new_percentage_trigger_zone_id']
			)
		);
		return $wpdb->insert_id;
	}

	public function update_shipping_percentage_triggers() {
		global $wpdb;
		$shipping_rates = $wpdb->get_results( 'SELECT * FROM ec_shippingrate WHERE is_percentage_based = 1' );
		foreach ( $shipping_rates as $trigger ) {
			$wpdb->query(
				$wpdb->prepare(
					'UPDATE ec_shippingrate SET trigger_rate = %s, shipping_rate = %s, zone_id = %d WHERE shippingrate_id = %d',
					sanitize_text_field( wp_unslash( $_POST[ 'ec_admin_percentage_trigger_' . (int) $trigger->shippingrate_id ] ) ),
					wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST[ 'ec_admin_percentage_trigger_rate_' . (int) $trigger->shippingrate_id ] ) ) ),
					(int) $_POST['ec_admin_percentage_trigger_zone_id_'.$trigger->shippingrate_id],
					(int) $trigger->shippingrate_id
				)
			);
		}
	}

	public function add_shipping_static_method() {
		global $wpdb;
		$free_shipping_at = '-1.000';
		if ( $_POST['ec_admin_new_method_trigger_free_shipping_at'] != '' ) {
			$free_shipping_at = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_admin_new_method_trigger_free_shipping_at'] ) ) );
		}
		$wpdb->query(
			$wpdb->prepare(
				'INSERT INTO ec_shippingrate( is_method_based, shipping_label, shipping_rate, zone_id, free_shipping_at, shipping_order ) VALUES( 1, %s, %s, %d, %s, %d )',
				sanitize_text_field( wp_unslash( $_POST['ec_admin_new_method_label'] ) ),
				wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_admin_new_method_trigger_rate'] ) ) ),
				(int) $_POST['ec_admin_new_method_trigger_zone_id'],
				$free_shipping_at,
				(int) $_POST['ec_admin_new_method_trigger_shipping_order']
			)
		);
		return $wpdb->insert_id;
	}

	public function update_shipping_method_triggers() {
		global $wpdb;
		$shipping_rates = $wpdb->get_results( 'SELECT * FROM ec_shippingrate WHERE is_method_based = 1' );
		foreach ( $shipping_rates as $trigger ) {
			$free_shipping_at = '-1.000';
			if ( $_POST['ec_admin_method_trigger_free_shipping_at_'.$trigger->shippingrate_id] != '' ) {
				$free_shipping_at = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST[ 'ec_admin_method_trigger_free_shipping_at_' . (int) $trigger->shippingrate_id ] ) ) );
			}
			$wpdb->query(
				$wpdb->prepare(
					'UPDATE ec_shippingrate SET shipping_label = %s, shipping_rate = %s, zone_id = %d, free_shipping_at = %s, shipping_order = %d WHERE shippingrate_id = %d',
					sanitize_text_field( wp_unslash( $_POST[ 'ec_admin_method_label_' . (int) $trigger->shippingrate_id ] ) ),
					wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST[ 'ec_admin_method_trigger_rate_' . (int) $trigger->shippingrate_id ] ) ) ),
					(int) $_POST['ec_admin_method_trigger_zone_id_'.$trigger->shippingrate_id],
					$free_shipping_at,
					(int) $_POST['ec_admin_method_trigger_shipping_order_'.$trigger->shippingrate_id],
					(int) $trigger->shippingrate_id
				)
			);
		}
	}

	public function update_fraktjakt_settings() {
		global $wpdb;
		$wpdb->query(
			$wpdb->prepare(
				'UPDATE ec_setting SET fraktjakt_customer_id = %s, fraktjakt_login_key = %s, fraktjakt_conversion_rate = %s, fraktjakt_test_mode = %s, fraktjakt_address = %s, fraktjakt_city = %s, fraktjakt_state = %s, fraktjakt_zip =  %s, fraktjakt_country = %s',
				sanitize_text_field( wp_unslash( $_POST['fraktjakt_customer_id'] ) ),
				sanitize_text_field( wp_unslash( $_POST['fraktjakt_login_key'] ) ),
				sanitize_text_field( wp_unslash( $_POST['fraktjakt_conversion_rate'] ) ),
				sanitize_text_field( wp_unslash( $_POST['fraktjakt_test_mode'] ) ),
				sanitize_text_field( wp_unslash( $_POST['fraktjakt_address'] ) ),
				sanitize_text_field( wp_unslash( $_POST['fraktjakt_city'] ) ),
				sanitize_text_field( wp_unslash( $_POST['fraktjakt_state'] ) ),
				sanitize_text_field( wp_unslash( $_POST['fraktjakt_zip'] ) ),
				sanitize_text_field( wp_unslash( $_POST['fraktjakt_country'] ) )
			)
		);
	}

	public function update_country_list() {
		global $wpdb;
		$id_cnt = (int) $_POST['id'];
		$ship_to_active = ( isset( $_POST['ship_to_active'] ) && $_POST['ship_to_active'] == '1' ) ? 1 : 0;
		$wpdb->query( $wpdb->prepare( 'UPDATE ec_country SET ship_to_active = %d WHERE id_cnt = %d', $ship_to_active, $id_cnt ) );
		$wpdb->query( $wpdb->prepare( 'UPDATE ec_state SET ship_to_active = %d WHERE idcnt_sta = %d', $ship_to_active, $id_cnt ) );
	}

	public function update_state_list() {
		global $wpdb;
		$id_sta = (int) $_POST['id'];
		$ship_to_active = ( isset( $_POST['ship_to_active'] ) && $_POST['ship_to_active'] == '1' ) ? 1 : 0;
		$wpdb->query( $wpdb->prepare( 'UPDATE ec_state SET ship_to_active = %d WHERE id_sta = %d', $ship_to_active, $id_sta ) );
	}

	public function add_zone() {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_zone( zone_name ) VALUES( %s )', sanitize_text_field( wp_unslash( $_POST['zone_name'] ) ) ) );
		return $wpdb->insert_id;
	}

	public function edit_zone() {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( 'UPDATE ec_zone SET zone_name = %s WHERE zone_id = %d', sanitize_text_field( wp_unslash( $_POST['zone_name'] ) ), (int) $_POST['id'] ) );
	}

	public function delete_zone( $zone_id ) {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_zone WHERE zone_id = %d', $zone_id ) );
		$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_zone_to_location WHERE zone_id = %d', $zone_id ) );
	}

	public function add_zone_item() {
		global $wpdb;
		$state_code = $wpdb->get_var( $wpdb->prepare( 'SELECT ec_state.code_sta FROM ec_state WHERE id_sta = %d', (int) $_POST['id_sta'] ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_zone_to_location( zone_id, iso2_cnt, code_sta ) VALUES( %s, %s, %s )', (int) $_POST['zone_id'], sanitize_text_field( wp_unslash( $_POST['iso2_cnt'] ) ), $state_code ) );
		return $wpdb->insert_id;
	}

	public function update_zone_item() {
		global $wpdb;
		$state_code = $wpdb->get_var( $wpdb->prepare( 'SELECT ec_state.code_sta FROM ec_state WHERE id_sta = %d', (int) $_POST['id_sta'] ) );
		$wpdb->query( $wpdb->prepare( 'UPDATE ec_zone_to_location SET zone_id = %d, iso2_cnt = %s, code_sta = %s WHERE zone_to_location_id = %d', (int) $_POST['zone_id'], sanitize_text_field( wp_unslash( $_POST['iso2_cnt'] ) ), $state_code, (int) $_POST['id'] ) );
	}

	public function delete_zone_item( $zone_item_id ) {
		global $wpdb;
		$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_zone_to_location WHERE zone_to_location_id = %d', $zone_item_id ) );
	}

	public function save_shipping_settings() {
		global $wpdb;
		$options = array( 'ec_option_use_shipping', 'ec_option_hide_shipping_rate_page1', 'ec_option_add_local_pickup', 'ec_option_collect_tax_on_shipping', 'ec_option_show_delivery_days_live_shipping', 'ec_option_show_delivery_days_live_shipping', 'ec_option_collect_shipping_for_subscriptions', 'ec_option_ship_items_seperately', 'ec_option_static_ship_items_seperately', 'ec_option_fedex_use_net_charge', 'ec_option_live_override_always', 'ec_option_ship_to_billing_global', 'ec_option_packing_slip_show_logo', 'ec_option_packing_slip_show_order_id', 'ec_option_packing_slip_show_order_date', 'ec_option_packing_slip_show_billing', 'ec_option_packing_slip_show_shipping', 'ec_option_packing_slip_show_phone', 'ec_option_packing_slip_show_email', 'ec_option_packing_slip_show_product_image', 'ec_option_packing_slip_show_product_title', 'ec_option_packing_slip_show_model_number', 'ec_option_packing_slip_show_options', 'ec_option_packing_slip_show_pricing', 'ec_option_packing_slip_show_subtotal', 'ec_option_packing_slip_show_tiptotal', 'ec_option_packing_slip_show_shippingtotal', 'ec_option_packing_slip_show_discounttotal', 'ec_option_packing_slip_show_taxtotal', 'ec_option_packing_slip_show_grandtotal', 'ec_option_packing_slip_show_order_notes' );
		$options_text = array( 'ec_option_enable_metric_unit_display' );

		if ( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $options ) ) {
			$val = ( isset( $_POST['val'] ) && $_POST['val'] == '1' ) ? 1 : 0;
			update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), $val );

		} else if ( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $options_text ) ) {
			update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), sanitize_text_field( wp_unslash( $_POST['val'] ) ) );

		} else if ( isset( $_POST['update_var'] ) && sanitize_text_field( wp_unslash( $_POST['update_var'] ) ) == 'shipping_handling_rate' ) {
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_setting SET shipping_handling_rate = %s', wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['val'] ) ) ) ) );

		} else if ( isset( $_POST['update_var'] ) && sanitize_text_field( wp_unslash( $_POST['update_var'] ) ) == 'shipping_expedite_rate' ) {
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_setting SET shipping_expedite_rate = %s', wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['val'] ) ) ) ) );

		}
	}

}

/******************/
/* Shipping Hooks */
/******************/
add_action( 'wp_ajax_ec_admin_ajax_update_shipping_select', 'ec_admin_ajax_update_shipping_select' );
function ec_admin_ajax_update_shipping_select() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-rates' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	$shipping->update_shipping_method();
	die();
}

/* Shipping Hooks - Price Triggers */
add_action( 'wp_ajax_ec_admin_ajax_add_price_trigger', 'ec_admin_ajax_add_price_trigger' );
function ec_admin_ajax_add_price_trigger() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-rates' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	global $wpdb;
	$currency = new ec_currency();
	$shippingrate_id = $shipping->add_shipping_price_trigger();
	$trigger = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_shippingrate WHERE shippingrate_id = %d', $shippingrate_id ) );
	$shipping_zones = $wpdb->get_results( 'SELECT * FROM ec_zone ORDER BY zone_name ASC' );
	echo '<div class="ec_admin_tax_row ec_admin_shipping_price_trigger_row" id="ec_admin_price_trigger_row_' . esc_attr( $trigger->shippingrate_id ) . '">
		<div class="ec_admin_shipping_trigger"><span>' . esc_attr__( 'Price Trigger', 'wp-easycart' ) . ': ' . esc_attr( $currency->symbol ) . '</span><input type="number" class="ec_admin_price_trigger_input" step=".01" value="' . esc_attr( $currency->get_number_safe( $trigger->trigger_rate ) ) . '" name="ec_admin_price_trigger_' . esc_attr( $trigger->shippingrate_id ) . '" id="ec_admin_new_price_trigger_' . esc_attr( $trigger->shippingrate_id ) . '" /></div>
		<div class="ec_admin_shipping_rate"><span>' . esc_attr__( 'Shipping Rate', 'wp-easycart' ) . ': ' . esc_attr( $currency->symbol ) . '</span><input type="number" class="ec_admin_price_trigger_rate_input" step=".01" value="' . esc_attr( $currency->get_number_safe( $trigger->shipping_rate ) ) . '" name="ec_admin_price_trigger_rate_' . esc_attr( $trigger->shippingrate_id ) . '" id="ec_admin_new_price_trigger_rate_' . esc_attr( $trigger->shippingrate_id ) . '" /></div>
		<div class="ec_admin_shipping_rate"><span>' . esc_attr__( 'Shipping Zone', 'wp-easycart' ) . ': </span><select class="ec_admin_price_trigger_zone_id_input" name="ec_admin_price_trigger_zone_id_' . esc_attr( $trigger->shippingrate_id ) . '" id="ec_admin_price_trigger_zone_id_' . esc_attr( $trigger->shippingrate_id ) . '">
			<option value="0">' . esc_attr__( 'No Zone', 'wp-easycart' ) . '</option>';
			foreach ( $shipping_zones as $zone ) {
			echo '<option value="' . esc_attr( $zone->zone_id ) . '"';
			if ( $zone->zone_id == $trigger->zone_id ) {
				echo ' selected="selected"';
			}
			echo '>' . esc_attr( $zone->zone_name ) . '</option>';
			}
		echo '</select></div>
		<span class="ec_admin_shipping_rate_delete"><div class="dashicons-before dashicons-trash" onclick="ec_admin_delete_price_trigger( \'' . esc_attr( $trigger->shippingrate_id ) . '\' );"></div></span>
	</div>';
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_delete_price_trigger', 'ec_admin_ajax_delete_price_trigger' );
function ec_admin_ajax_delete_price_trigger() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-rates' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	global $wpdb;
	$shipping->delete_shipping_rate( (int) $_POST['shippingrate_id'] );
	$rows = $wpdb->get_results( 'SELECT * FROM ec_shippingrate WHERE is_price_based = 1' );
	echo esc_attr( count( $rows ) );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_update_shipping_price_triggers', 'ec_admin_ajax_update_shipping_price_triggers' );
function ec_admin_ajax_update_shipping_price_triggers() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-rates' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	$shipping->update_shipping_price_triggers();
	die();
}

/* Shipping Hooks - Weight Triggers */
add_action( 'wp_ajax_ec_admin_ajax_add_weight_trigger', 'ec_admin_ajax_add_weight_trigger' );
function ec_admin_ajax_add_weight_trigger() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-rates' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	global $wpdb;
	$currency = new ec_currency();
	$shippingrate_id = $shipping->add_shipping_weight_trigger();
	$trigger = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_shippingrate WHERE shippingrate_id = %d', $shippingrate_id ) );
	$shipping_zones = $wpdb->get_results( 'SELECT * FROM ec_zone ORDER BY zone_name ASC' );
	echo '<div class="ec_admin_tax_row ec_admin_shipping_weight_trigger_row" id="ec_admin_weight_trigger_row_' . esc_attr( $trigger->shippingrate_id ) . '">
		<div class="ec_admin_shipping_trigger"><span>' . esc_attr__( 'Weight Trigger', 'wp-easycart' ) . ': </span><input type="number" class="ec_admin_weight_trigger_input" step=".01" value="' . esc_attr( $trigger->trigger_rate ) . '" name="ec_admin_weight_trigger_' . esc_attr( $trigger->shippingrate_id ) . '" id="ec_admin_new_weight_trigger_' . esc_attr( $trigger->shippingrate_id ) . '" /></div>
		<div class="ec_admin_shipping_rate"><span>' . esc_attr__( 'Shipping Rate', 'wp-easycart' ) . ': ' . esc_attr( $currency->symbol ) . '</span><input type="number" class="ec_admin_weight_trigger_rate_input" step=".01" value="' . esc_attr( $currency->get_number_safe( $trigger->shipping_rate ) ) . '" name="ec_admin_weight_trigger_rate_' . esc_attr( $trigger->shippingrate_id ) . '" id="ec_admin_new_weight_trigger_rate_' . esc_attr( $trigger->shippingrate_id ) . '" /></div>
		<div class="ec_admin_shipping_rate"><span>' . esc_attr__( 'Shipping Zone', 'wp-easycart' ) . ': </span><select class="ec_admin_weight_trigger_zone_id_input" name="ec_admin_weight_trigger_zone_id_' . esc_attr( $trigger->shippingrate_id ) . '" id="ec_admin_weight_trigger_zone_id_' . esc_attr( $trigger->shippingrate_id ) . '">
			<option value="0">' . esc_attr__( 'No Zone', 'wp-easycart' ) . '</option>';
			foreach ( $shipping_zones as $zone ) {
			echo '<option value="' . esc_attr( $zone->zone_id ) . '"';
			if ( $zone->zone_id == $trigger->zone_id ) {
				echo ' selected="selected"';
			}
			echo '>' . esc_attr( $zone->zone_name ) . '</option>';
			}
		echo '</select></div>
		<span class="ec_admin_shipping_rate_delete"><div class="dashicons-before dashicons-trash" onclick="ec_admin_delete_weight_trigger( \'' . esc_attr( $trigger->shippingrate_id ) . '\' );"></div></span>
	</div>';
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_delete_weight_trigger', 'ec_admin_ajax_delete_weight_trigger' );
function ec_admin_ajax_delete_weight_trigger() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-rates' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	global $wpdb;
	$shipping->delete_shipping_rate( (int) $_POST['shippingrate_id'] );
	$rows = $wpdb->get_results( 'SELECT * FROM ec_shippingrate WHERE is_weight_based = 1' );
	echo esc_attr( count( $rows ) );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_update_shipping_weight_triggers', 'ec_admin_ajax_update_shipping_weight_triggers' );
function ec_admin_ajax_update_shipping_weight_triggers() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-rates' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	$shipping->update_shipping_weight_triggers();
	die();
}

/* Shipping Hooks - Quantity Triggers */
add_action( 'wp_ajax_ec_admin_ajax_add_quantity_trigger', 'ec_admin_ajax_add_quantity_trigger' );
function ec_admin_ajax_add_quantity_trigger() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-rates' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	global $wpdb;
	$currency = new ec_currency();
	$shippingrate_id = $shipping->add_shipping_quantity_trigger();
	$trigger = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_shippingrate WHERE shippingrate_id = %d', $shippingrate_id ) );
	$shipping_zones = $wpdb->get_results( 'SELECT * FROM ec_zone ORDER BY zone_name ASC' );
	echo '<div class="ec_admin_tax_row ec_admin_shipping_quantity_trigger_row" id="ec_admin_quantity_trigger_row_' . esc_attr( $trigger->shippingrate_id ) . '">
		<div class="ec_admin_shipping_trigger"><span>' . esc_attr__( 'Quantity Trigger', 'wp-easycart' ) . ': </span><input type="number" class="ec_admin_quantity_trigger_input" step="1" value="' . esc_attr( number_format( $trigger->trigger_rate, 0, '', '' ) ) . '" name="ec_admin_quantity_trigger_' . esc_attr( $trigger->shippingrate_id ) . '" id="ec_admin_new_quantity_trigger_' . esc_attr( $trigger->shippingrate_id ) . '" /></div>
		<div class="ec_admin_shipping_rate"><span>' . esc_attr__( 'Shipping Rate', 'wp-easycart' ) . ': ' . esc_attr( $currency->symbol ) . '</span><input type="number" class="ec_admin_quantity_trigger_rate_input" step=".01" value="' . esc_attr( $currency->get_number_safe( $trigger->shipping_rate ) ) . '" name="ec_admin_quantity_trigger_rate_' . esc_attr( $trigger->shippingrate_id ) . '" id="ec_admin_new_quantity_trigger_rate_' . esc_attr( $trigger->shippingrate_id ) . '" /></div>
		<div class="ec_admin_shipping_rate"><span>' . esc_attr__( 'Shipping Zone', 'wp-easycart' ) . ': </span><select class="ec_admin_quantity_trigger_zone_id_input" name="ec_admin_quantity_trigger_zone_id_' . esc_attr( $trigger->shippingrate_id ) . '" id="ec_admin_quantity_trigger_zone_id_' . esc_attr( $trigger->shippingrate_id ) . '">
			<option value="0">' . esc_attr__( 'No Zone', 'wp-easycart' ) . '</option>';
			foreach ( $shipping_zones as $zone ) {
			echo '<option value="' . esc_attr( $zone->zone_id ) . '"';
			if ( $zone->zone_id == $trigger->zone_id ) {
				echo ' selected="selected"';
			}
			echo '>' . esc_attr( $zone->zone_name ) . '</option>';
			}
		echo '</select></div>
		<span class="ec_admin_shipping_rate_delete"><div class="dashicons-before dashicons-trash" onclick="ec_admin_delete_quantity_trigger( \'' . esc_attr( $trigger->shippingrate_id ) . '\' );"></div></span>
	</div>';
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_delete_quantity_trigger', 'ec_admin_ajax_delete_quantity_trigger' );
function ec_admin_ajax_delete_quantity_trigger() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-rates' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	global $wpdb;
	$shipping->delete_shipping_rate( (int) $_POST['shippingrate_id'] );
	$rows = $wpdb->get_results( 'SELECT * FROM ec_shippingrate WHERE is_quantity_based = 1' );
	echo esc_attr( count( $rows ) );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_update_shipping_quantity_triggers', 'ec_admin_ajax_update_shipping_quantity_triggers' );
function ec_admin_ajax_update_shipping_quantity_triggers() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-rates' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	$shipping->update_shipping_quantity_triggers();
	die();
}

/* Shipping Hooks - Percentage Triggers */
add_action( 'wp_ajax_ec_admin_ajax_add_percentage_trigger', 'ec_admin_ajax_add_percentage_trigger' );
function ec_admin_ajax_add_percentage_trigger() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-rates' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	global $wpdb;
	$currency = new ec_currency();
	$shippingrate_id = $shipping->add_shipping_percentage_trigger();
	$trigger = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_shippingrate WHERE shippingrate_id = %d', $shippingrate_id ) );
	$shipping_zones = $wpdb->get_results( 'SELECT * FROM ec_zone ORDER BY zone_name ASC' );
	echo '<div class="ec_admin_tax_row ec_admin_shipping_percentage_trigger_row" id="ec_admin_percentage_trigger_row_' . esc_attr( $trigger->shippingrate_id ) . '">
		<div class="ec_admin_shipping_trigger"><span>' . esc_attr__( 'Price Trigger', 'wp-easycart' ) . ': ' . esc_attr( $currency->symbol ) . '</span><input type="number" class="ec_admin_percentage_trigger_input" step=".01" value="' . esc_attr( $currency->get_number_safe( $trigger->trigger_rate ) ) . '" name="ec_admin_percentage_trigger_' . esc_attr( $trigger->shippingrate_id ) . '" id="ec_admin_new_percentage_trigger_' . esc_attr( $trigger->shippingrate_id ) . '" /></div>
		<div class="ec_admin_shipping_rate"><span>' . esc_attr__( 'Shipping Rate', 'wp-easycart' ) . ': </span><input type="number" class="ec_admin_percentage_trigger_rate_input" step=".01" value="' . esc_attr( $currency->get_number_safe( $trigger->shipping_rate ) ) . '" name="ec_admin_percentage_trigger_rate_' . esc_attr( $trigger->shippingrate_id ) . '" id="ec_admin_new_percentage_trigger_rate_' . esc_attr( $trigger->shippingrate_id ) . '" />%</div>
		<div class="ec_admin_shipping_rate"><span>' . esc_attr__( 'Shipping Zone', 'wp-easycart' ) . ': </span><select class="ec_admin_percentage_trigger_zone_id_input" name="ec_admin_percentage_trigger_zone_id_' . esc_attr( $trigger->shippingrate_id ) . '" id="ec_admin_percentage_trigger_zone_id_' . esc_attr( $trigger->shippingrate_id ) . '">
			<option value="0">' . esc_attr__( 'No Zone', 'wp-easycart' ) . '</option>';
			foreach ( $shipping_zones as $zone ) {
			echo '<option value="' . esc_attr( $zone->zone_id ) . '"';
			if ( $zone->zone_id == $trigger->zone_id ) {
				echo ' selected="selected"';
			}
			echo '>' . esc_attr( $zone->zone_name ) . '</option>';
			}
		echo '</select></div>
		<span class="ec_admin_shipping_rate_delete"><div class="dashicons-before dashicons-trash" onclick="ec_admin_delete_percentage_trigger( \'' . esc_attr( $trigger->shippingrate_id ) . '\' );"></div></span>
	</div>';
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_delete_percentage_trigger', 'ec_admin_ajax_delete_percentage_trigger' );
function ec_admin_ajax_delete_percentage_trigger() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-rates' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	global $wpdb;
	$shipping->delete_shipping_rate( (int) $_POST['shippingrate_id'] );
	$rows = $wpdb->get_results( 'SELECT * FROM ec_shippingrate WHERE is_percentage_based = 1' );
	echo esc_attr( count( $rows ) );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_update_shipping_percentage_triggers', 'ec_admin_ajax_update_shipping_percentage_triggers' );
function ec_admin_ajax_update_shipping_percentage_triggers() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-rates' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	$shipping->update_shipping_percentage_triggers();
	die();
}

/* Shipping Hooks - Static Method */
add_action( 'wp_ajax_ec_admin_ajax_add_method_trigger', 'ec_admin_ajax_add_method_trigger' );
function ec_admin_ajax_add_method_trigger() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-rates' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	global $wpdb;
	$currency = new ec_currency();
	$shippingrate_id = $shipping->add_shipping_static_method();
	$trigger = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_shippingrate WHERE shippingrate_id = %d', $shippingrate_id ) );
	$shipping_zones = $wpdb->get_results( 'SELECT * FROM ec_zone ORDER BY zone_name ASC' );
	echo '<div class="ec_admin_tax_row ec_admin_static_shipping_row" id="ec_admin_method_trigger_row_' . esc_attr( $trigger->shippingrate_id ) . '">
			<div class="ec_admin_shipping_trigger"><span>' . esc_attr__( 'Shipping Label', 'wp-easycart' ) . ':</span><input type="text" class="ec_admin_method_label_input" value="' . esc_attr( $trigger->shipping_label ) . '" name="ec_admin_method_label_' . esc_attr( $trigger->shippingrate_id ) . '" id="ec_admin_method_label_' . esc_attr( $trigger->shippingrate_id ) . '" /></div>
			<div class="ec_admin_shipping_rate"><span>' . esc_attr__( 'Shipping Rate', 'wp-easycart' ) . ': ' . esc_attr( $currency->symbol ) . '</span><input type="number" class="ec_admin_method_trigger_rate_input" step=".01" value="' . esc_attr( $currency->get_number_safe( $trigger->shipping_rate ) ) . '" name="ec_admin_method_trigger_rate_' . esc_attr( $trigger->shippingrate_id ) . '" id="ec_admin_new_method_trigger_rate_' . esc_attr( $trigger->shippingrate_id ) . '" /></div>
			<div class="ec_admin_shipping_rate"><span>' . esc_attr__( 'Shipping Zone', 'wp-easycart' ) . ': </span><select class="ec_admin_method_trigger_zone_id_input" name="ec_admin_method_trigger_zone_id_' . esc_attr( $trigger->shippingrate_id ) . '" id="ec_admin_method_trigger_zone_id_' . esc_attr( $trigger->shippingrate_id ) . '">
			<option value="0">' . esc_attr__( 'No Zone', 'wp-easycart' ) . '</option>';
			foreach ( $shipping_zones as $zone ) {
			echo '<option value="' . esc_attr( $zone->zone_id ) . '"';
			if ( $zone->zone_id == $trigger->zone_id ) {
				echo ' selected="selected"';
			}
			echo '>' . esc_attr( $zone->zone_name ) . '</option>';
			}
		echo '</select></div>
			<div class="ec_admin_shipping_rate"><span>' . esc_attr__( 'Free Shipping @', 'wp-easycart' ) . ':</span><input type="number" step=".01" class="ec_admin_method_trigger_free_shipping_at_input" value="';
			if ( $trigger->free_shipping_at != -1 ) { 
				echo esc_attr( $currency->get_number_safe( $trigger->free_shipping_at ) );
			}
			echo '" name="ec_admin_method_trigger_free_shipping_at_' . esc_attr( $trigger->shippingrate_id ) . '" id="ec_admin_method_trigger_free_shipping_at_' . esc_attr( $trigger->shippingrate_id ) . '" /></div>
			<div class="ec_admin_shipping_rate"><span>' . esc_attr__( 'Rate Order', 'wp-easycart' ) . ':</span><input type="number" step="1" class="ec_admin_method_trigger_shipping_order_input" value="' . esc_attr( $trigger->shipping_order ) . '" name="ec_admin_method_trigger_shipping_order_' . esc_attr( $trigger->shippingrate_id ) . '" id="ec_admin_method_trigger_shipping_order_' . esc_attr( $trigger->shippingrate_id ) . '" /></div>
			<div><span class="ec_admin_shipping_rate_delete"><div class="dashicons-before dashicons-trash" onclick="ec_admin_delete_method_trigger( \'' . esc_attr( $trigger->shippingrate_id ) . '\' );"></div></span></div>
		  </div>';
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_delete_method_trigger', 'ec_admin_ajax_delete_method_trigger' );
function ec_admin_ajax_delete_method_trigger() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-rates' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	global $wpdb;
	$shipping->delete_shipping_rate( (int) $_POST['shippingrate_id'] );
	$rows = $wpdb->get_results( 'SELECT * FROM ec_shippingrate WHERE is_method_based = 1' );
	echo esc_attr( count( $rows ) );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_update_shipping_method_triggers', 'ec_admin_ajax_update_shipping_method_triggers' );
function ec_admin_ajax_update_shipping_method_triggers() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-rates' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	$shipping->update_shipping_method_triggers();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_fraktjakt_settings', 'ec_admin_ajax_save_fraktjakt_settings' );
function ec_admin_ajax_save_fraktjakt_settings() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-rates' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	$shipping->update_fraktjakt_settings();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_country_list', 'ec_admin_ajax_save_country_list' );
function ec_admin_ajax_save_country_list() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-settings' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	$shipping->update_country_list();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_state_list', 'ec_admin_ajax_save_state_list' );
function ec_admin_ajax_save_state_list() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-settings' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	$shipping->update_state_list();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_add_shipping_zone', 'ec_admin_ajax_add_shipping_zone' );
function ec_admin_ajax_add_shipping_zone() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-settings' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	$zone_id = $shipping->add_zone();
	echo esc_attr( $zone_id );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_edit_shipping_zone', 'ec_admin_ajax_edit_shipping_zone' );
function ec_admin_ajax_edit_shipping_zone() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-settings' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	$shipping->edit_zone();
	echo esc_attr( (int) $_POST['id'] );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_delete_shipping_zone', 'ec_admin_ajax_delete_shipping_zone' );
function ec_admin_ajax_delete_shipping_zone() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-settings' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	if ( is_array( $_POST['id'] ) ) {
		foreach ( (array) $_POST['id'] as $zone_id ) { // XSS OK. Forced array and each item sanitized.
			$shipping->delete_zone( (int) $zone_id );
		}
	} else {
		$shipping->delete_zone( (int) $_POST['id'] );
	}
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_add_shipping_zone_item', 'ec_admin_ajax_add_shipping_zone_item' );
function ec_admin_ajax_add_shipping_zone_item() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-settings' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	$id = $shipping->add_zone_item();
	echo esc_attr( $id );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_update_shipping_zone_item', 'ec_admin_ajax_update_shipping_zone_item' );
function ec_admin_ajax_update_shipping_zone_item() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-settings' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	$shipping->update_zone_item();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_delete_shipping_zone_item', 'ec_admin_ajax_delete_shipping_zone_item' );
function ec_admin_ajax_delete_shipping_zone_item() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-settings' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	if ( is_array( $_POST['id'] ) ) {
		foreach ( (array) $_POST['id'] as $zoneitem_id ) { // XSS OK. Forced array and each item sanitized.
			$shipping->delete_zone_item( (int) $zoneitem_id );
		}

	} else {
		$shipping->delete_zone_item( (int) $_POST['id'] );

	}
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_shipping_settings', 'ec_admin_ajax_save_shipping_settings' );
function ec_admin_ajax_save_shipping_settings() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-shipping-settings' ) ) {
		return false;
	}

	$shipping = new wp_easycart_admin_shipping();
	$shipping->save_shipping_settings();
	die();
}