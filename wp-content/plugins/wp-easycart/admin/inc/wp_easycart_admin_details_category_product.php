<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wp_easycart_admin_details_category_product extends wp_easycart_admin_details {

	public $categoryitem;
	public $item;

	public function __construct() {
		parent::__construct();
		add_action( 'wp_easycart_admin_category_product_details_basic_fields', array( $this, 'basic_fields' ) );
	}

	protected function init() {
		$this->docs_link = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1&section=category';
		$this->id = 0;
		$this->page = 'wp-easycart-products';
		$this->subpage = 'category';
		$this->action = 'admin.php?page=' . $this->page . '&subpage=' . $this->subpage;
		$this->form_action = 'add-new-category-product';
		$this->categoryitem = (object) array(
			'categoryitem_id' => '',
			'product_id' => '',
		);
	}

	protected function init_data() {
		$this->form_action = 'update-category-product';
		$this->categoryitem = $this->wpdb->get_row( $this->wpdb->prepare( 'SELECT ec_categoryitem.* FROM ec_categoryitem WHERE categoryitem_id = %d', (int) $_GET['categoryitem_id'] ) );
		$this->id = $this->categoryitem->categoryitem_id;
	}

	public function output( $type = 'edit' ) {
		$this->init();
		if ( $type == 'edit' ) {
			$this->init_data();
		}
		include( EC_PLUGIN_DIRECTORY . '/admin/template/products/categories/product-details.php' );
	}

	public function basic_fields() {
		global $wpdb;
		$products = $wpdb->get_results( 'SELECT ec_product.product_id AS id, ec_product.title AS value FROM ec_product ORDER BY title ASC' );

		$fields = apply_filters( 'wp_easycart_admin_category_product_details_basic_fields_list', array(
			array(
				'name'				=> 'categoryitem_id',
				'alt_name'			=> 'categoryitem_id',
				'type'				=> 'hidden',
				'value'				=> $this->categoryitem->categoryitem_id,
			),
			array(
				'name'				=> 'product_id',
				'type'				=> 'select',
				'data'				=> $products,
				'data_label' 		=> __( 'Select Product', 'wp-easycart' ),
				'label' 			=> __( 'Add to Category', 'wp-easycart' ),
				'required' 			=> true,
				'message' 			=> __( 'Please select a product to add to category.', 'wp-easycart' ),
				'value' 			=> $this->categoryitem->product_id,
			)
		) );
		$this->print_fields( $fields );
	}
}
