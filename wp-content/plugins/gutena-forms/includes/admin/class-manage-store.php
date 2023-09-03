<?php
/**
* Manage form data tables
* create, read, update and delete rows
* https://developer.wordpress.org/reference/classes/wpdb/
*/

 defined( 'ABSPATH' ) || exit;

 /**
 * Abort if the class is already exists.
 */
 if ( ! class_exists( 'Gutena_Forms_Manage_Store' ) && class_exists( 'Gutena_Forms_Store' ) ) {

	class Gutena_Forms_Manage_Store extends Gutena_Forms_Store {

        // The instance of this class
		private static $instance = null;

		// Returns the instance of this class.
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function __construct() {	
            parent::__construct();
			//Filter gutena forms render form
			add_filter('gutena_forms_save_form_schema', array( $this, 'save_form_schema' ), 10, 3 );
			//save submiited gutena forms
			add_action( 'gutena_forms_submitted_data', array( $this, 'save_form_entry' ), 10, 3 );

            //Update form entry status to read
			add_action( 'wp_ajax_gutena_forms_entries_read', array( $this, 'entries_read_status_update' ) );

			add_action( 'wp_loaded', array($this, 'process_bulk_action') );
			
		}

		//form entries table bulk action
		public function process_bulk_action() { 

			if ( ! empty( $_REQUEST['action'] ) && ! empty( $_REQUEST['form_entry_id'] ) && function_exists( 'absint' ) && ! empty( $_GET['formid'] ) && is_numeric( $_GET['formid'] ) ) {

				//check nonce
				check_ajax_referer( 'gutena_Forms', 'gfnonce' );
				$form_id = sanitize_key( $_GET['formid'] );

				//check user access 
				if ( ( ! has_filter( 'gutena_forms_check_user_access' ) && ! $this->is_gfadmin() )  || ! apply_filters( 'gutena_forms_check_user_access', true, 'edit_entries' ) ) {
					wp_redirect( esc_url( admin_url( 'admin.php?page=gutena-forms&formid='.$form_id ) ) );
				}

				$form_entry_id = wp_unslash( $_REQUEST['form_entry_id'] );
				
				global $wpdb;
				$action = sanitize_text_field( wp_unslash( $_REQUEST['action'] ) );
				//Admin Action 
				do_action( 'gutena_forms_entries_admin_action', $form_id, $action, $form_entry_id );
				$this->update_entries_status( $action, $form_entry_id );
				
				wp_safe_redirect( add_query_arg( 
					array( 
						'page' => 'gutena-forms',
						'formid' => $form_id,
					), admin_url( 'admin.php' ) ) 
				);
			}
		}

        //Update form entry status to read  mb
		public function entries_read_status_update() {
			check_ajax_referer( 'gutena_Forms', 'gfnonce' );
            global $wpdb;
            //Update status
            if ( ! empty( $wpdb ) && ! empty( $_POST['form_entry_id'] ) && is_numeric( $_POST['form_entry_id'] ) && function_exists( 'absint' ) ) {
				
				$this->update_entries_status( 'read', absint( wp_unslash( $_POST['form_entry_id'] ) ) );

				wp_send_json_success( array(
                    'status'  => 'success',
                    'message' => __( 'Status updated successfully', 'gutena-forms' ),
                ) );
			}
		}

	
		/**
		 * add new meta for form or entries
		 * Required at least : 'form_id', 'data_type', 'metadata'
		 * 
		 */
		protected function add_form_or_enries_meta( $meta = array() ) {
			//return if form id or data not available
			global $wpdb; 
			if ( empty( $wpdb ) || $this->is_empty( $meta, array( 'form_id', 'data_type', 'metadata'  ) ) ) {
				return;
			}
			
			//$wpdb->insert( $table_name, $data, $data_format );
			//Insert query
			$wpdb->insert(
				$this->table_gutenaforms_meta,
				array(
					'form_id' => sanitize_key( $meta['form_id'] ),
					'entry_id' => empty( $meta['entry_id'] ) ? 0 : $meta['entry_id'],
					'user_id' => empty( $meta['user_id'] ) ? $this->current_user_id() : $meta['user_id'] ,
					'data_type' => sanitize_key( $meta['data_type'] ),
					'metadata' => $this->sanitize_serialize_data( $meta['metadata'] ),
				),
				array(
					'%d',
					'%d',
					'%d',
					'%s',
					'%s'
				)
			);
		}

		/**
		 * Add or Update form schema in gutena forms table
		 * Add or update row
		 * 
		 * @param array $form_schema form details in array format
		 * @param string $form_id
		 * @param array $gutena_form_ids array of existing forms in tables
		 * 
		 */
		public function save_form_schema( $form_schema, $block_form_id, $gutena_form_ids ) {
			global $wpdb; 
			if ( empty( $wpdb ) || empty( $block_form_id ) || ! $this->is_forms_store_exists() ) {
				return $form_schema;
			}
			$block_form_id = sanitize_key( $block_form_id );
			$error = '';
			//getting existing schema 
			$fom_schema_row = $this->get_form_details( $block_form_id );
			
			$table_forms = $this->table_gutenaforms;
			if (  empty( $fom_schema_row ) ) {
				$this->save_new_form( $block_form_id, $form_schema );
			} else if ( ! empty( $fom_schema_row->form_id ) ) {
				/**
				 * Update table
				 * step1: Backup of existing schema in gutenaforms_meta table
				 * step2: Update gutenaforms table row
				 */
				//step1:Creating backup of existing schema 
				$form_schema_serialize = $this->sanitize_serialize_data( $form_schema );
				//take backup if form schema is different
				if ( $form_schema_serialize !==  $fom_schema_row->form_schema ) {
					$this->add_form_or_enries_meta( array(
						'form_id' => $fom_schema_row->form_id,
						'user_id' => $fom_schema_row->user_id,
						'data_type' => 'form_schema_backup',
						'metadata' => $fom_schema_row->form_schema,
					) );
				}
				
				//step2: Update gutenaforms table row
				//$wpdb->update( $table_name, $data, $where, $data_format, $where_format );
				$wpdb->update(
					$table_forms,
					array(
						'user_id' => $this->current_user_id(),
						'form_name' => $this->get_form_name( $form_schema ) ,
						'form_schema' => $form_schema_serialize
					),
					array(
						'form_id' => $fom_schema_row->form_id
					),
					array( '%d', '%s', '%s' ), 
					array( '%d' )
				);
			} 
			
			return $form_schema;
		}

		/**
		 * Save form submitted data
		 * 
		 * @param array $form_data submitted data
		 * @param array $fieldSchema form schema
		 * 
		 */
		public function save_form_entry( $form_data, $block_form_id, $fieldSchema ) {
			global $wpdb; 
			if ( empty( $wpdb ) || empty( $form_data ) || ! is_array( $form_data ) || empty( $block_form_id ) || empty( $fieldSchema ) || ! $this->is_forms_store_exists() ) {
				return false;
			}
			
			/**
			 * Step1: check if schema existing
			 * Step2: Create form entry in table_gutenaforms_entries
			 * Step3: Step3: Create meta entry for submiited data for record in table_gutenaforms_meta
			 * Step4: Create field name values entries for each form fields
			 */
			//step1: check if schema existing 
			$fom_schema_row = $this->get_form_details( $block_form_id );
			
			if ( ! empty( $fom_schema_row ) ) {
				
				$fieldSchema['form_id'] = sanitize_key( $fom_schema_row->form_id );
				$fieldSchema['user_id'] = $this->current_user_id();
				
				//Step2: Create form entry in table_gutenaforms_entries
				$wpdb->insert(
					$this->table_gutenaforms_entries,
					array(
						'form_id' => $fieldSchema['form_id'],
						'user_id' => $fieldSchema['user_id'],
						'modified_by' => $fieldSchema['user_id'],
						'entry_data' => $this->sanitize_serialize_data( $form_data ),
					),
					array(
						'%d',
						'%d',
						'%d',
						'%s'
					)
				);
				//Get inserted id
				$fieldSchema['entry_id'] = $wpdb->insert_id;
				$fieldSchema['entry_id'] = sanitize_key( $fieldSchema['entry_id'] );
				//return if id not exist
				if ( empty( $fieldSchema['entry_id'] )  ) {
					return;
				}
				
				//Step3: Create meta entry for submiited data for record in table_gutenaforms_meta
				$this->add_form_or_enries_meta( array(
					'form_id' => $fieldSchema['form_id'],
					'entry_id' => $fieldSchema['entry_id'],
					'user_id' => $fieldSchema['user_id'],
					'data_type' => 'submit_entry_data',
					'metadata' => $this->sanitize_serialize_data( $form_data ),
				) );

				//Step4: Create field name values entries for each form fields
				foreach ( $form_data as $name_attr => $data ) {
					$name_attr   = sanitize_key( wp_unslash( $name_attr ) );
					if ( empty( $fieldSchema[ $name_attr ] ) ) {
						continue;
					}
					$field_value = $data['raw_value'];
					if ( is_array( $field_value ) ) {
						$field_value =	$this->sanitize_array( wp_unslash( $field_value ), true );
						$field_value = implode(", ", $field_value );
					} else {
						$field_value = sanitize_textarea_field( wp_unslash( $field_value ) );
					}
					//Insert query
					$wpdb->insert(
					    $this->table_gutenaforms_field_value,
						array(
							'entry_id' => $fieldSchema['entry_id'],
							'field_name' => $name_attr,
							'field_value' => $field_value,
						),
						array(
							'%d',
							'%s',
							'%s'
						)
					);
				}

				return true;
			} 

			return false;
		}
	}

	Gutena_Forms_Manage_Store::get_instance();
 }
