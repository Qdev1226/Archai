<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wp_easycart_admin_details_pricepoint extends wp_easycart_admin_details {

	public $pricepoint;
	public $item;

	public function __construct() {
		parent::__construct();
		add_action( 'wp_easycart_admin_pricepoint_details_basic_fields', array( $this, 'basic_fields' ) );
	}

	protected function init() {
		$this->docs_link = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1&section=price-points';
		$this->id = 0;
		$this->page = 'wp-easycart-settings';
		$this->subpage = 'pricepoint';
		$this->action = 'admin.php?page=' . $this->page . '&subpage=' . $this->subpage;
		$this->form_action = 'add-new-pricepoint';
		$this->pricepoint = (object) array(
			'pricepoint_id' => '',
			'is_less_than' => '',
			'is_greater_than' => '',
			'low_point' => '',
			'high_point' => '',
			'pricepoint_order' => '',
		);
	}

	protected function init_data() {
		global $wpdb;
		$this->form_action = 'update-pricepoint';
		$this->pricepoint = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_pricepoint.* FROM ec_pricepoint WHERE pricepoint_id = %d', (int) $_GET['pricepoint_id'] ) );
		$this->id = $this->pricepoint->pricepoint_id;
	}

	public function output( $type = 'edit' ) {
		$this->init();
		if ( $type == 'edit' ) {
			$this->init_data();
		}
		include( EC_PLUGIN_DIRECTORY . '/admin/template/settings/pricepoint/pricepoint-details.php' );
	}

	public function basic_fields() {
		$fields = apply_filters(
			'wp_easycart_admin_pricepoint_details_basic_fields_list',
			array(
				array(
					'name' => 'pricepoint_id',
					'alt_name'			=> 'pricepoint_id',
					'type' => 'hidden',
					'value' => $this->pricepoint->pricepoint_id,
				),
				array(
					'name' => 'is_less_than',
					'type' => 'checkbox',
					'label' => __( 'This price point is less than the high point listed below.', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please enter a value.', 'wp-easycart' ),
					'validation_type' => 'checkbox',
					'selected' => false,
					'onclick' => 'ec_admin_pricepoint_type_change',
					'show'  			=> array(
						'name' =>'high_point',
						'value'=>'1',
					),
					'value' => $this->pricepoint->is_less_than,
				),
				array(
					'name' => 'is_between',
					'type' => 'checkbox',
					'label' => __( 'This price point is between a range of low and high values.', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please enter a value.', 'wp-easycart' ),
					'validation_type' => 'checkbox',
					'onclick' => 'ec_admin_pricepoint_type_change',
					'selected' => false,
					'value' => 0,
				),
				array(
					'name' => 'is_greater_than',
					'type' => 'checkbox',
					'label' => __( 'This price point is greater than the low point listed below.', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please enter a value.', 'wp-easycart' ),
					'validation_type' => 'checkbox',
					'onclick' => 'ec_admin_pricepoint_type_change',
					'selected' => false,
					'show'  			=> array(
						'name' =>'low_point',
						'value'=>'1'
					),
					'value' => $this->pricepoint->is_greater_than,
				),
				array(
					'name' => 'low_point',
					'type' => 'currency',
					'label' => __( 'Low Point', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please enter a value.', 'wp-easycart' ),
					'validation_type' => 'currency',
					'value' => $this->pricepoint->low_point,
				),
				array(
					'name' => 'high_point',
					'type' => 'currency',
					'label' => __( 'High Point', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please enter a value.', 'wp-easycart' ),
					'validation_type' => 'currency',
					'value' => $this->pricepoint->high_point,
				),
				array(
					'name' => 'pricepoint_order',
					'type' => 'text',
					'label' => __( 'Sort Order', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please enter a value.', 'wp-easycart' ),
					'validation_type' => 'text',
					'value' => $this->pricepoint->pricepoint_order,
				),
			)
		);
		$this->print_fields( $fields );
	}
}
