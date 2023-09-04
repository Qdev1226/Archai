<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wp_easycart_admin_details_orders extends wp_easycart_admin_details {

	public $order;
	public $item;

	public function __construct() {
		parent::__construct();
		add_action( 'wp_easycart_admin_orders_details_basic_fields', array( $this, 'basic_fields' ) );
		add_action( 'wp_easycart_admin_orders_details_shipment', array( $this, 'shipment_fields' ) );
	}

	protected function init() {
		$this->docs_link = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1&section=order-management';
		$this->id = 0;
		$this->page = 'wp-easycart-orders';
		$this->subpage = 'orders';
		$this->action = 'admin.php?page=' . $this->page . '&subpage=' . $this->subpage;
		$this->form_action = 'add-new-order';
		$this->order = (object) array(
			'order_id' => '',
			'promo_code' => '',
			'giftcard_id' => '',
			'order_date' => '',
			'orderstatus_id' => '',
			'order_notes' => '',
			'order_customer_notes' => '',
			'creditcard_digits' => '',
			'agreed_to_terms' => '',
			'order_ip_address' => '',
			'cc_exp_month' => '',
			'cc_exp_year' => '',
			'card_holder_name' => '',

			'billing_first_name' => '',
			'billing_last_name' => '',
			'billing_company_name' => '',
			'billing_address_line_1' => '',
			'billing_address_line_2' => '',
			'billing_city' => '',
			'billing_state' => '',
			'billing_country' => '',
			'billing_zip' => '',
			'billing_phone' => '',
			'user_email' => '',

			'shipping_first_name' => '',
			'shipping_last_name' => '',
			'shipping_company_name' => '',
			'shipping_address_line_1' => '',
			'shipping_address_line_2' => '',
			'shipping_city' => '',
			'shipping_state' => '',
			'shipping_country' => '',
			'shipping_zip' => '',
			'shipping_phone' => '',

			'use_expedited_shipping' => '',
			'shipping_method' => '',
			'shipping_carrier' => '',
			'tracking_number' => '',
			'order_weight' => '',

			'sub_total' => '',
			'tax_total' => '',
			'shipping_total' => '',
			'discount_total' => '',
			'vat_total' => '',
			'duty_total' => '',
			'grand_total' => '',
			'refund_total' => '',
			'gst_total' => '',
			'gst_rate' => '',
			'pst_total' => '',
			'pst_rate' => '',
			'hst_total' => '',
			'hst_rate' => '',
			'vat_rate' => '',
			'vat_registration_number' => '',
			'order_fees' => array(),
		);
	}

	protected function init_data() {
		$this->form_action = 'update-order';
		$this->order = $this->wpdb->get_row( $this->wpdb->prepare( 'SELECT ec_order.*, ec_user.first_name, ec_user.last_name, billing_country.name_cnt AS billing_country_name, shipping_country.name_cnt AS shipping_country_name, ec_orderstatus.is_approved, ec_orderstatus.order_status FROM ec_order LEFT JOIN ec_orderstatus ON ( ec_orderstatus.status_id = ec_order.orderstatus_id ) LEFT JOIN ec_country AS billing_country ON ( billing_country.iso2_cnt = ec_order.billing_country ) LEFT JOIN ec_country AS shipping_country ON ( shipping_country.iso2_cnt = ec_order.shipping_country ) LEFT JOIN ec_user ON ( ec_user.user_id = ec_order.user_id ) WHERE order_id = %d', (int) $_GET['order_id'] ) );
		$this->id = $this->order->order_id;
		$this->order->order_fees = $this->wpdb->get_results( $this->wpdb->prepare( 'SELECT * FROM ec_order_fee WHERE order_id = %d ORDER BY order_fee_id ASC', $this->id ) );
	}

	public function output( $type = 'edit' ) {
		$this->init();
		if ( $type == 'edit' ) {
			$this->init_data();
		}
		include( EC_PLUGIN_DIRECTORY . '/admin/template/orders/orders/order-details.php' );
	}

	public function basic_fields() {
		$fields = apply_filters(
			'wp_easycart_admin_orders_details_basic_fields_list',
			array(
				array(
					'name' => 'order_notes',
					'type' => 'textarea',
					'label' => __( 'Administrative Order Notes', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please enter administrative notes.', 'wp-easycart' ),
					'validation_type' => 'textarea',
					'visible' => false,
					'value' => $this->order->order_notes,
				),
				array(
					'name' => 'order_customer_notes',
					'type' => 'textarea',
					'label' => __( 'Customer Order Notes', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please enter customer order notes.', 'wp-easycart' ),
					'validation_type' => 'textarea',
					'visible' => false,
					'value' => $this->order->order_customer_notes,
				),
			)
		);
		$this->print_fields( $fields );
	}

	public function shipment_fields() {
		$fields = apply_filters(
			'wp_easycart_admin_orders_details_shipment_fields_list',
			array(
				array(
					'name' => 'order_weight',
					'type' => 'text',
					'label' => __( 'Order Weight', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please enter an order weight.', 'wp-easycart' ),
					'validation_type' => 'text',
					'value' => $this->order->order_weight,
				),
				array(
					'name' => 'giftcard_id',
					'type' => 'text',
					'label' => __( 'Gift Card Used', 'wp-easycart' ),
					'required' => false,
					'validation_type' => 'text',
					'value' => $this->order->giftcard_id,
				),
				array(
					'name' => 'promo_code',
					'type' => 'text',
					'label' => __( 'Coupon Code Used', 'wp-easycart' ),
					'required' => false,
					'validation_type' => 'text',
					'value' => $this->order->promo_code,
				),
			)
		);
		$this->print_fields( $fields );
	}
}
