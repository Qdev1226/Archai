<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wp_easycart_admin_details_perpage extends wp_easycart_admin_details {

	public $perpage;
	public $item;

	public function __construct() {
		parent::__construct();
		add_action( 'wp_easycart_admin_perpage_details_basic_fields', array( $this, 'basic_fields' ) );
	}

	protected function init() {
		$this->docs_link = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1&section=perpage';
		$this->id = 0;
		$this->page = 'wp-easycart-settings';
		$this->subpage = 'perpage';
		$this->action = 'admin.php?page=' . $this->page . '&subpage=' . $this->subpage;
		$this->form_action = 'add-new-perpage';
		$this->perpage = (object) array(
			'perpage_id' => '',
			'perpage' => '',
		);
	}

	protected function init_data() {
		$this->form_action = 'update-perpage';
		$this->perpage = $this->wpdb->get_row( $this->wpdb->prepare( 'SELECT ec_perpage.* FROM ec_perpage WHERE perpage_id = %d', (int) $_GET['perpage_id'] ) );
		$this->id = $this->perpage->perpage_id;
	}

	public function output( $type = 'edit' ) {
		$this->init();
		if ( $type == 'edit' ) {
			$this->init_data();
		}
		include( EC_PLUGIN_DIRECTORY . '/admin/template/settings/perpage/perpage-details.php' );
	}

	public function basic_fields() {
		$fields = apply_filters(
			'wp_easycart_admin_perpage_details_basic_fields_list',
			array(
				array(
					'name' => 'perpage_id',
					'alt_name' => 'perpage_id',
					'type' => 'hidden',
					'value' => $this->perpage->perpage_id,
				),
				array(
					'name' => 'perpage',
					'type' => 'text',
					'label' => __( 'Per-Page Value', 'wp-easycart' ),
					'required' => true,
					'message' => __( 'Please enter a unique per-page name.', 'wp-easycart' ),
					'validation_type' => 'text',
					'value' => $this->perpage->perpage,
				),
			)
		);
		$this->print_fields( $fields );
	}
}
