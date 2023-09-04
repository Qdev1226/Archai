<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wp_easycart_admin_details_review extends wp_easycart_admin_details {

	public $review;
	public $item;

	public function __construct() {
		parent::__construct();
		add_action( 'wp_easycart_admin_reviews_details_basic_fields', array( $this, 'basic_fields' ) );
		add_action( 'wp_easycart_admin_reviews_details_review_info', array( $this, 'review_fields' ) );
		add_action( 'wp_easycart_admin_reviews_details_product_info', array( $this, 'product_fields' ) );
	}

	protected function init() {
		$this->docs_link = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1&section=product-reviews';
		$this->id = 0;
		$this->page = 'wp-easycart-products';
		$this->subpage = 'reviews';
		$this->action = 'admin.php?page=' . $this->page . '&subpage=' . $this->subpage;
		$this->form_action = 'add-new-review';
		$this->review = (object) array(
			'review_id' => '',
			'product_id' => '',
			'approved' => '',
			'rating' => '',
			'reviewer_name' => '',
			'title' => '',
			'description' => '',
			'date_submitted' => '',
			'user_id' => '',
		);
	}

	protected function init_data() {
		global $wpdb;
		$this->form_action = 'update-review';
		$this->review = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_review.* FROM ec_review WHERE review_id = %d', (int) $_GET['review_id'] ) );
		$this->id = $this->review->review_id;
	}

	public function output( $type = 'edit' ) {
		$this->init();
		if ( $type == 'edit' ) {
			$this->init_data();
		}
		include( EC_PLUGIN_DIRECTORY . '/admin/template/products/reviews/review-details.php' );
	}

	public function basic_fields() {
		$fields = apply_filters(
			'wp_easycart_admin_reviews_details_basic_fields_list',
			array(
				array(
					'name' => 'review_id',
					'alt_name' => 'review_id',
					'type' => 'hidden',
					'value' => $this->review->review_id,
				),
				array(
					'name' => 'product_id',
					'alt_name' => 'product_id',
					'type' => 'hidden',
					'value' => $this->review->product_id,
				),
				array(
					'name' => 'approved',
					'type' => 'checkbox',
					'label' => __( 'Is review approved for display on store product?', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please select if review is approved.', 'wp-easycart' ),
					'validation_type' => 'checkbox',
					'value' => $this->review->approved,
				),
			)
		);
		$this->print_fields( $fields );
	}

	public function review_fields() {
		global $wpdb;
		$user_email = $wpdb->get_var( $wpdb->prepare( 'SELECT ec_user.email FROM ec_user WHERE ec_user.user_id = %d', $this->review->user_id )  );
		$users = $wpdb->get_results( "SELECT ec_user.user_id AS id, CONCAT( ec_user.last_name, ', ', ec_user.first_name ) AS value FROM ec_user ORDER BY ec_user.last_name ASC, ec_user.first_name ASC" );

		$fields = apply_filters(
			'wp_easycart_admin_reviews_details_review_fields_list',
			array(
				array(
					'name' => 'date_submitted',
					'type' => 'date',
					'label' => __( 'Date Submitted', 'wp-easycart' ),
					'required' => true,
					'message' => __( 'Please enter a date this review occured.', 'wp-easycart' ),
					'validation_type' => 'date',
					'value' => $this->review->date_submitted,
				),
				array(
					'name' => 'user_id',
					'type' => 'select',
					'select2' => 'basic',
					'label' => __( 'Submitted By', 'wp-easycart' ),
					'data' => $users,
					'data_label' => __( 'Select One', 'wp-easycart' ),
					'required' => false,
					'validation_type' => 'select',
					'visible' => true,
					'value' => $this->review->user_id,
				),
				array(
					'name' => 'rating',
					'type' => 'star_rating',
					'label' => __( 'Rating ', 'wp-easycart' ),
					'max' => '5',
					'min' => '1',
					'step' => '1',
					'required' => true,
					'message' => __( 'Please enter a customer rating.', 'wp-easycart' ),
					'validation_type' => 'number',
					'value' => $this->review->rating,
				),
				array(
					'name'				=> 'reviewer_name',
					'type'				=> 'text',
					'label'				=> __( 'Reviewer Name', 'wp-easycart' ),
					'required' 			=> false,
					'validation_type' 	=> 'text',
					'value'				=> ( $this->review->reviewer_name != '' ) ? $this->review->reviewer_name : ( ( isset( $this->review->first_name) && isset( $this->review->last_name ) ) ? $this->review->first_name . ' ' . $this->review->last_name : '' ),
				),
				array(
					'name' => 'title',
					'type' => 'text',
					'label' => __( 'Review Title', 'wp-easycart' ),
					'required' => true,
					'message' => __( 'Please enter a product title.', 'wp-easycart' ),
					'validation_type' => 'text',
					'value' => $this->review->title,
				),
				array(
					'name' => 'description',
					'type' => 'textarea',
					'label' => __( 'Customer Comments', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please enter a customer comment.', 'wp-easycart' ),
					'validation_type' => 'textarea',
					'value' => $this->review->description,
				),
			)
		);
		$this->print_fields( $fields );
	}

	public function product_fields() {
		global $wpdb;
		$product = $wpdb->get_results( 'SELECT ec_product.model_number, ec_product.title, ec_product.price, ec_product.activate_in_store, ec_product.image1, ec_product.list_price, ec_product.description FROM ec_product WHERE ec_product.product_id = ' . $this->review->product_id . '' );
		$fields = apply_filters(
			'wp_easycart_admin_reviews_details_product_fields_list',
			array(
				array(
					'name' => 'product_title',
					'type' => 'text',
					'label' => __( 'Product Title', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please enter a product title.', 'wp-easycart' ),
					'validation_type' => 'text',
					'read-only' => true,
					'value' => $product[0]->title,
				),
				array(
					'name' => 'model_number',
					'type' => 'text',
					'label' => __( 'Model Number', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please enter a model number.', 'wp-easycart' ),
					'validation_type' => 'text',
					'read-only' => true,
					'value' => $product[0]->model_number,
				),
				array(
					'name' => 'product_price',
					'type' => 'currency',
					'label' => __( 'Product Price', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please enter a product price.', 'wp-easycart' ),
					'validation_type' => 'currency',
					'read-only' => true,
					'value' => $product[0]->price,
				),
				array(
					'name' => 'activate_in_store',
					'type' => 'checkbox',
					'label' => __( 'Product is active on store?', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please select if active in store.', 'wp-easycart' ),
					'validation_type' => 'checkbox',
					'read-only' => true,
					'value' => $product[0]->activate_in_store,
				),
				array(
					'name' => 'product_description',
					'type' => 'textarea',
					'label' => __( 'Product Description', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please enter a product description.', 'wp-easycart' ),
					'validation_type' => 'textarea',
					'read-only' => true,
					'value' => $product[0]->description,
				),
			)
		);
		$this->print_fields( $fields );
	}
}
