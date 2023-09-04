<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wp_easycart_admin_details_subscribers extends wp_easycart_admin_details {

	public $subscriber;
	public $item;

	public function __construct() {
		parent::__construct();
		add_action( 'wp_easycart_admin_subscribers_details_basic_fields', array( $this, 'basic_fields' ) );
	}

	protected function init() {
		$this->docs_link = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1&section=subscribers';
		$this->id = 0;
		$this->page = 'wp-easycart-users';
		$this->subpage = 'subscribers';
		$this->action = 'admin.php?page=' . $this->page . '&subpage=' . $this->subpage;
		$this->form_action = 'add-new-subscriber';
		$this->subscriber = (object) array(
			'subscriber_id' => '',
			'email' => '',
			'first_name' => '',
			'last_name' => '',
		);
	}

	protected function init_data() {
		global $wpdb;
		$this->form_action = 'update-subscriber';
		$this->subscriber = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_subscriber.* FROM ec_subscriber WHERE subscriber_id = %d', (int) $_GET['subscriber_id'] ) );
		$this->id = $this->subscriber->subscriber_id;
	}

	public function output( $type = 'edit' ) {
		$this->init();
		if ( $type == 'edit' ) {
			$this->init_data();
		}
		include( EC_PLUGIN_DIRECTORY . '/admin/template/users/subscribers/subscribers-details.php' );
	}

	public function basic_fields() {
		$fields = apply_filters(
			'wp_easycart_admin_subscribers_details_basic_fields_list',
			array(
				array(
					'name' => 'subscriber_id',
					'alt_name' => 'subscriber_id',
					'type' => 'hidden',
					'value' => $this->subscriber->subscriber_id,
				),
				array(
					'name' => 'email',
					'type' => 'text',
					'label' => __( 'Subscriber Email', 'wp-easycart' ),
					'required' => true,
					'message' => __( 'Please enter an email address.', 'wp-easycart' ),
					'validation_type' => 'email',
					'value' => $this->subscriber->email,
				),
				array(
					'name' => 'first_name',
					'type' => 'text',
					'label' => __( 'First Name', 'wp-easycart' ),
					'required' => true,
					'message' => __( 'Please enter a subscriber first name.', 'wp-easycart' ),
					'validation_type' => 'text',
					'value' => $this->subscriber->first_name,
				),
				array(
					'name' => 'last_name',
					'type' => 'text',
					'label' => __( 'Last Name', 'wp-easycart' ),
					'required' => true,
					'message' => __( 'Please enter a subscriber last name.', 'wp-easycart' ),
					'validation_type' => 'text',
					'value' => $this->subscriber->last_name,
				),
			)
		);
		$this->print_fields( $fields );
	}
}
