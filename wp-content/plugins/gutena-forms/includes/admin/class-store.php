<?php
/**
* Form data tables
* common functions realted to datatables 
* https://developer.wordpress.org/reference/classes/wpdb/
*/

 defined( 'ABSPATH' ) || exit;

 /**
 * Abort if the class is already exists.
 */
 if ( ! class_exists( 'Gutena_Forms_Store' ) && class_exists( 'Gutena_Forms_Admin' ) ) {

	class Gutena_Forms_Store extends Gutena_Forms_Admin {

		//Table to store forms
        public $table_gutenaforms = 'gutenaforms';

		//Table users
		public $table_users = 'users';

        //Table to store form enteries
        public $table_gutenaforms_entries = 'gutenaforms_entries';

        //Table to store form field value
        public $table_gutenaforms_field_value = 'gutenaforms_field_value';

        //Table to store data related to forms and form enteries table
        public $table_gutenaforms_meta = 'gutenaforms_meta';

		public function __construct() {	
			global $wpdb; 
			$this->table_gutenaforms = $wpdb->prefix .''. $this->table_gutenaforms;
			$this->table_users = $wpdb->prefix .''. $this->table_users;
            $this->table_gutenaforms_entries = $wpdb->prefix .''. $this->table_gutenaforms_entries;
            $this->table_gutenaforms_field_value = $wpdb->prefix .''. $this->table_gutenaforms_field_value;
            $this->table_gutenaforms_meta = $wpdb->prefix .''. $this->table_gutenaforms_meta;
		}

		public function include_db_upgrade_file() {
			if ( file_exists( ABSPATH . 'wp-admin/includes/upgrade.php' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				return true;
			}
			return false;
		}

		/**
		 * @return array associative array of form block_form_id and form_id
		 */
		public function get_block_id_form_id_array(){
			global $wpdb; 
			$forms = array();
			if ( $this->is_forms_store_exists() ) {
				$form_rows = $this->get_form_list();
				if ( ! empty( $form_rows ) ) {
					foreach ($form_rows as $key => $form) {
						$forms[ $form->block_form_id ] = $form->form_id;
					}
				}
			}
			return $forms;
		}

		public function get_form_name( $form_schema ) {
			$form_schema = $this->maybe_unserialize( $form_schema );
			return ( empty( $form_schema ) || empty( $form_schema['form_attrs'] ) || empty( $form_schema['form_attrs']['formName'] ) ) ? __( 'Contact Form', 'gutena-forms' ) : sanitize_text_field( $form_schema['form_attrs']['formName'] );
		}


		public function get_form_list() {
			global $wpdb;
			return  empty( $wpdb ) ? '': $wpdb->get_results(
				"SELECT form_id, form_name, block_form_id FROM {$this->table_gutenaforms} WHERE form_id IN (SELECT  DISTINCT form_id  FROM {$this->table_gutenaforms_entries} WHERE trash = 0)"
			);
		}

		/**
		 * Update entry status
		 * 
		 * @param string $action read, unread, trash 
		 * 
		 * @param number|array $form_entry_ids entry id
		 * 
		 */
		public function update_entries_status( $action, $form_entry_id ) {
			//check for valid action
			global $wpdb;
			if ( ! empty( $action ) && ! empty( $wpdb ) && ! empty( $form_entry_id ) && $this->is_gfadmin( 'delete_posts' ) ) {
				$form_entry_ids = array();
				if ( is_array( $form_entry_id ) ) {
					foreach ( $form_entry_id as $id) {
						if ( ! empty( $id ) && is_numeric( $id ) ) {
							$form_entry_ids[] = absint( $id );
						}
					}
				} else if ( ! empty( $form_entry_id ) && is_numeric( $form_entry_id ) ) {
					$form_entry_ids[] = absint( $form_entry_id );
				}
				//comma separated string id1,id2,...
				$form_entry_ids = implode( ",", $form_entry_ids );
				//Update status
				//Wpdb add single quotes for string 
				$action_query = '';
				
				switch ( $action ) {
					case 'read':
						$action_query = "UPDATE {$this->table_gutenaforms_entries} SET entry_status = 'read' WHERE entry_id IN ({$form_entry_ids})";
					break;
					case 'unread':
						$action_query = "UPDATE {$this->table_gutenaforms_entries} SET entry_status = 'unread' WHERE entry_id IN ({$form_entry_ids})";
					break;
					case 'trash':
						if ( apply_filters( 'gutena_forms_check_user_access', $this->is_gfadmin(), 'delete_entries' ) ) {
							$action_query = "UPDATE {$this->table_gutenaforms_entries} SET trash = 1 WHERE entry_id IN ({$form_entry_ids})";
						}
					break;
					default:
					break;
				}

				if ( ! empty( $action_query ) && 25 < strlen( $action_query ) ) {
					$wpdb->query( $action_query );
					return true;
				}
				
			}

			return false;
		}

		/***
		 * Get form details 
		 * 
		 */
		protected function get_form_details( $block_form_id = '' ) {
			global $wpdb; 
			if ( empty( $wpdb ) || empty( $block_form_id ) ) {
				return false;
			}
			
			//form table 
			$table_forms = $this->table_gutenaforms;
			//get form details
			$fom_schema_row = $wpdb->get_results(
				$wpdb->prepare(
					"
					SELECT * FROM {$table_forms}
					WHERE block_form_id = %s
					AND published = %d
					",
					sanitize_key( $block_form_id ),
					1
				)
			);
			if ( $wpdb->last_error ) {
				return false;
			} else if ( ! empty( $fom_schema_row ) &&  ! empty( $fom_schema_row[0]->form_id ) &&  ! empty( $fom_schema_row[0]->form_schema ) ) {
				return $fom_schema_row[0];
			}
		}

		/**
		 * Prepares the list of items for displaying.
		 * 
		 * @param number $id entry_id 
		 * 
		 * @return array entry details for admin view
		 */
		public function get_entry_details( $id ) {
			$entry_data = array();
			if ( ! empty( $id ) && is_numeric( $id ) ) {
				global $wpdb;
				//get entries details
				$entry_data = $wpdb->get_row(
					$wpdb->prepare(
						"SELECT e.*,(SELECT GROUP_CONCAT(entries.entry_id SEPARATOR ',') from {$this->table_gutenaforms_entries} entries WHERE entries.trash = 0 AND entries.form_id = e.form_id GROUP BY entries.trash) AS all_entries, f.form_name, u.display_name as user_display_name, u.user_email FROM {$this->table_gutenaforms_entries} e LEFT JOIN {$this->table_gutenaforms} f ON e.form_id = f.form_id LEFT JOIN {$this->table_users} u ON u.ID = e.user_id  WHERE e.entry_id= %d AND e.trash = %d",
						$id,
						0,
					),
					ARRAY_A
				);
				
				if ( empty( $entry_data ) || empty( $entry_data['entry_data'] ) ) {
					return $entry_data;
				}

				//unserialize form data
				$entry_data['entry_data'] = maybe_unserialize( $entry_data['entry_data'] );
				if ( empty( $this->form_id ) ) {
					$this->form_id = empty( $entry_data['form_id'] ) ? 0: $entry_data['form_id'];
				}
				//filter field values
				if ( is_array( $entry_data['entry_data'] ) ) {
					foreach ( $entry_data['entry_data'] as $field_slug => $fieldData) {
						$fieldData['value'] = $this->view_field_value( $fieldData );
						$entry_data['entry_data'][ $field_slug ] = $fieldData;
					}
				} 
				//Update entry status to read from unread
				if ( ! empty( $entry_data['entry_status'] ) && 'unread' === $entry_data['entry_status'] ) {
					$this->update_entries_status( 'read', absint( $entry_data['entry_id'] ) );
				}

				//Processs data for entry navigation: i.e. next or previous
				if ( ! empty( $entry_data['all_entries'] ) ) {
					//Array of all entry id
					$entry_data['all_entries'] = explode( ',', $entry_data['all_entries'] );
					
					//Total entries
					$entry_data['total_entries'] = count( $entry_data['all_entries'] );
					
					//Current entry serial number
					$entry_data['current_entry_sno'] = array_search( $id , $entry_data['all_entries'] );
					
					//Next entry id
					if ( $entry_data['current_entry_sno'] < ( $entry_data['total_entries'] - 1 ) ) {
						$entry_data['next_entry_id'] = $entry_data['current_entry_sno'] + 1;
						$entry_data['next_entry_id'] = empty( $entry_data['all_entries'][ $entry_data['next_entry_id'] ] ) ? '': $entry_data['all_entries'][ $entry_data['next_entry_id'] ];
					}					
					
					//Previous entry id
					if ( 1 <= $entry_data['current_entry_sno'] ) { 
						$entry_data['previous_entry_id'] = $entry_data['current_entry_sno'] - 1;
						$entry_data['previous_entry_id'] = empty( $entry_data['all_entries'][ $entry_data['previous_entry_id'] ] ) ? '': $entry_data['all_entries'][ $entry_data['previous_entry_id'] ];
					}

					$entry_data['current_entry_sno'] += 1; 
				}

				//related_entry
				if ( ! empty( $entry_data['user_id'] ) && is_numeric( $entry_data['user_id'] )) {
					$entry_data['related_entry'] = $wpdb->get_var(
						$wpdb->prepare(
							"SELECT count(entry_id) FROM {$this->table_gutenaforms_entries} WHERE trash = 0 AND user_id=%d ",
							$entry_data['user_id'],
						)
					);
				}
				
			}
			
			return $entry_data;
		}

		public function view_field_value( $fieldData, $fieldvalue = '' ) { 
			return ( empty( $fieldData ) || empty( $fieldData['value'] ) ) ? '': wp_kses( apply_filters( 
				'gutena_forms_view_field_value', 
				empty( $fieldvalue ) ? $fieldData['value'] : $fieldvalue,
				$fieldData
			), array_merge(
				wp_kses_allowed_html( 'post' ),
				array(
					'a' => array(
						'href'    => 1,
						'class'	  => 1,
						'target'  => 1,
						'modalid' => 1
					),
				)
			) );
		}

		//save new form
		protected function save_new_form( $form_id, $form_schema ) {
			//return if form id or data not available
			global $wpdb; 
			if ( empty( $wpdb ) || empty( $form_id ) || empty( $form_schema ) || ! is_array( $form_schema ) || empty( $form_schema['form_attrs'] ) ) {
				return;
			}
			//$wpdb->insert( $table_name, $data, $data_format );
			//Insert query
			$wpdb->insert(
				$this->table_gutenaforms,
				array(
					'user_id' => $this->current_user_id(),
					'block_form_id' => sanitize_key( $form_id ),
					'form_name' => $this->get_form_name( $form_schema ),
					'form_schema' => $this->sanitize_serialize_data( $form_schema ) ,
				),
				array(
					'%d',
					'%s',
					'%s',
					'%s'
				)
			);

			return $wpdb->insert_id;
		}

		/**
		 * Admin dashboard header
		 * @param array $form_list list of form name with respective id
		 * 
		 * @return HTML 
		 */
		public function get_dashboard_header() {
			//logo title
			return 
				'<div class="gf-header">
				<div id="gf-header-left-section" class="gf-logo-title">
				<a href="'.esc_url( admin_url( 'admin.php?page=gutena-forms' ) ).'" >
				<img src="'.GUTENA_FORMS_PLUGIN_URL . 'assets/img/logo.png'.'" />
				</a>
				<div id="gfp-dashboard-navigation" class="gfp-dashboard-navigation"></div>
				</div>
				
				<div id="gf-header-right-section" >
				' .( is_gutena_forms_pro( false ) ? '': '<a href="' . esc_url( admin_url( 'admin.php?page=gutena-forms&pagetype=introduction#gutena-forms-pricing' ) ) . '" class="gf-btn gf-pro-btn"  > <span class="gf-btn-text">'.__( 'Go Premium - 14 Day Free Trial', 'gutena-forms' ).'</span> </a>' ). '
				</div>
				</div>';
		}

	}
 }
