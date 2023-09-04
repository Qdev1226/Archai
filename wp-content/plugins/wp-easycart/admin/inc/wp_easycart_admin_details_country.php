<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wp_easycart_admin_details_country extends wp_easycart_admin_details {

	public $country;
	public $item;

	public function __construct() {
		parent::__construct();
		add_action( 'wp_easycart_admin_country_details_basic_fields', array( $this, 'basic_fields' ) );
	}

	protected function init() {
		$this->docs_link = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1&section=countries';
		$this->id = 0;
		$this->page = 'wp-easycart-settings';
		$this->subpage = 'country';
		$this->action = 'admin.php?page=' . $this->page . '&subpage=' . $this->subpage;
		$this->form_action = 'add-new-country';
		$this->country = (object) array(
			'id_cnt' => '',
			'name_cnt' => '',
			'iso2_cnt' => '',
			'iso3_cnt' => '',
			'sort_order' => '',
			'vat_rate_cnt' => '',
			'ship_to_active' => '',
		);
	}

	protected function init_data() {
		global $wpdb;
		$this->form_action = 'update-country';
		$this->country = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_country.* FROM ec_country WHERE id_cnt = %d', (int) $_GET['id_cnt'] ) );
		$this->id = $this->country->id_cnt;
	}

	public function output( $type = 'edit' ) {
		$this->init();
		if ( $type == 'edit' ) {
			$this->init_data();
		}
		include( EC_PLUGIN_DIRECTORY . '/admin/template/settings/country-state/country-details.php' );
	}

	public function basic_fields() {
		$fields = apply_filters(
			'wp_easycart_admin_country_details_basic_fields_list',
			array(
				array(
					'name' => 'id_cnt',
					'alt_name' => 'id_cnt',
					'type' => 'hidden',
					'value' => $this->country->id_cnt,
				),
				array(
					'name' => 'ship_to_active',
					'type' => 'checkbox',
					'label' => __( ' Enable this Country?', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please select if country is enabled.', 'wp-easycart' ),
					'validation_type' => 'checkbox',
					'value' => $this->country->ship_to_active,
				),
				array(
					'name' => 'name_cnt',
					'type' => 'text',
					'label' => __( 'Country Name', 'wp-easycart' ),
					'required' => true,
					'message' => __( 'Please enter a unique country name.', 'wp-easycart' ),
					'validation_type' => 'text',
					'value' => $this->country->name_cnt,
				),
				array(
					'name' => 'iso2_cnt',
					'type' => 'text',
					'label' => __( '2 Digit Abbreviation', 'wp-easycart' ),
					'maxlength' => '2',
					'required' => true,
					'message' => __( 'Please enter an ISO 2 digit abbreviation.', 'wp-easycart' ),
					'validation_type' => 'text',
					'value' => $this->country->iso2_cnt,
				),
				array(
					'name' => 'iso3_cnt',
					'type' => 'text',
					'label' => __( '3 Digit Abbreviation', 'wp-easycart' ),
					'maxlength' => '3',
					'required' => true,
					'message' => __( 'Please enter an ISO 3 digit abbreviation.', 'wp-easycart' ),
					'validation_type' => 'text',
					'value' => $this->country->iso3_cnt,
				),
				array(
					'name' => 'sort_order',
					'type' => 'number',
					'label' => __( 'Sort Order', 'wp-easycart' ),
					'required' => true,
					'message' => __( 'Please enter a sort order value.', 'wp-easycart' ),
					'validation_type' => 'number',
					'value' => $this->country->sort_order,
				),
				array(
					'name' => 'vat_rate_cnt',
					'type' => 'number',
					'label' => __( 'VAT Rate for Country', 'wp-easycart' ),
					'min' => '0',
					'max' => '100',
					'step' => '.0001',
					'required' => false,
					'message' => __( 'Please enter an optional VAT rate for this country.', 'wp-easycart' ),
					'validation_type' => 'number',
					'value' => $this->country->vat_rate_cnt,
				),
			)
		);
		$this->print_fields( $fields );
	}
}
