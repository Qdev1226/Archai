<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wp_easycart_admin_details_user_role extends wp_easycart_admin_details {

	public $user_role;
	public $item;

	public function __construct() {
		parent::__construct();
		add_action( 'wp_easycart_admin_user_role_details_basic_fields', array( $this, 'basic_fields' ) );
		add_action( 'wp_easycart_admin_user_role_details_remote_access_fields', array( $this, 'remote_access_fields' ) );
	}

	protected function init() {
		$this->docs_link = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1&section=user-roles';
		$this->id = 0;
		$this->page = 'wp-easycart-users';
		$this->subpage = 'user-roles';
		$this->action = 'admin.php?page=' . $this->page . '&subpage=' . $this->subpage;
		$this->form_action = 'add-new-user-role';
		$this->user_role = (object) array(
			'role_id' => '',
			'role_label' => '',
			'admin_access' => '',
		);
	}

	protected function init_data() {
		global $wpdb;
		$this->form_action = 'update-user-role';
		$this->item = $this->user_role = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_role.* FROM ec_role WHERE role_id = %d', ( ( isset( $_GET['role_id'] ) ) ? (int) $_GET['role_id'] : 0 ) ) );
		$this->id = $this->user_role->role_id;
	}

	public function output( $type = 'edit' ) {
		$this->init();
		if ( 'edit' == $type ) {
			$this->init_data();
		}
		include( EC_PLUGIN_DIRECTORY . '/admin/template/users/user-roles/user-role-details.php' );
	}

	public function basic_fields() {
		$fields = apply_filters(
			'wp_easycart_admin_user_details_basic_fields_list',
			array(
				array(
					'name' => 'role_id',
					'alt_name' => 'role_id',
					'type' => 'hidden',
					'value' => $this->user_role->role_id,
				),
				array(
					'name' => 'old_role_label',
					'alt_name' => 'old_role_label',
					'type' => 'hidden',
					'value' => $this->user_role->role_label,
				),
				array(
					'name' => 'role_label',
					'type' => 'text',
					'label' => __( 'User Role', 'wp-easycart' ),
					'required' => true,
					'message' => __( 'Please enter a user role label.', 'wp-easycart' ),
					'validation_type' => 'text',
					'value' => $this->user_role->role_label,
				),
			)
		);
		$this->print_fields( $fields );
	}

	public function remote_access_fields() {
		$fields = apply_filters(
			'wp_easycart_admin_user_details_remote_access_fields_list',
			array(
				array(
					'name' => 'admin_access',
					'type' => 'checkbox',
					'label' => __( 'Allow Remote Admin Access', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please select whether to allow remote admin access or not.', 'wp-easycart' ),
					'validation_type' => 'checkbox',
					'onclick' => 'show_pro_required',
					'value' => $this->user_role->admin_access,
				),
			),
			$this->user_role->role_id
		);
		$this->print_fields( $fields );
	}
}
