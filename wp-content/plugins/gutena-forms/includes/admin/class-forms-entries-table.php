<?php
/**
 * List table : forms entries or submission
 * 
 *
 */

 defined( 'ABSPATH' ) || exit;

 /**
  * Abort if the class is already exists.
  */
 if ( ! class_exists( 'Gutena_Forms_Entries_Table' ) && class_exists( 'WP_List_Table' ) ) {
 
   
	class Gutena_Forms_Entries_Table extends WP_List_Table {

		protected $form_id = 0;

		protected $total_rows = 0;
		protected $total_unread_rows = 0;

		protected $entry_columns = array();

		protected $store;

		public function __construct(  ) {
			parent::__construct(
				array(
					'plural'   => 'entry',
					'singular' => 'entries',
					'ajax'     => false
				)
			);
			
			if ( ! empty( $_GET['formid'] ) && is_numeric( $_GET['formid'] ) ) {
				$this->form_id = sanitize_key( $_GET['formid'] );
			}

			//process_bulk_action : in Gutena_Forms_Manage_Store
			
			if ( ! is_gutena_forms_pro() ) {
				//Extra controls to be displayed between bulk actions and pagination.
				add_action( 'gutena_forms_dashboard_entries_table_topbar', array( $this, 'table_topbar_filters' ), 10, 1 );
			}
			
		}

		/**
		 * Prepares the list of items for displaying.
		 *
		 */
		public function prepare_items() {
			if ( empty( $this->form_id ) ) {
				return;
			}
			global $wpdb;
		
			$this->total_unread_rows = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT( entry_id ) FROM {$this->store->table_gutenaforms_entries} WHERE  form_id = %d AND trash = 0 AND entry_status = %s",
					$this->form_id,
					'unread'
				)
			);

			//get total rows count
			$this->total_rows = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT( entry_id ) FROM {$this->store->table_gutenaforms_entries} WHERE  form_id = %d AND trash = 0",
					$this->form_id
				)
			);

			$table_data = apply_filters( 'gutena_forms_entries_table_list_prepare', array(
				'current_page' => $this->get_pagenum(),
				'total_unread_rows' => $this->total_unread_rows,
			) );
			
			if ( ! isset( $table_data['total_rows'] ) && ! isset( $table_data['form_rows'] ) ) {
				
				$table_data['orderby'] = ( empty( $_GET['orderby'] ) || ! in_array( $_GET['orderby'], array( 'entry_id', 'added_time' ) ) )? 'entry_id' : sanitize_text_field( $_GET['orderby'] )  ;
				$table_data['order'] = ( empty( $_GET['order'] ) || 'desc' === $_GET['order'] ) ? 'DESC' : 'ASC' ;
				
				//get total rows count
				$table_data['total_rows'] = $this->total_rows;

				//per page 
				$table_data['per_page'] = absint( apply_filters( 'gutena_forms_tables_per_page', 20 ) ) ;
				
				//current page
				$table_data['current_page'] = $this->get_pagenum();
				$query = "SELECT * FROM {$this->store->table_gutenaforms_entries} WHERE form_id = %d AND trash = 0 ";

				//unread entries
				if ( ! empty( $_GET['entry_view'] ) && 'unread' === sanitize_key( wp_unslash( $_GET['entry_view'] ) ) ) {
					$table_data['total_rows'] = $this->total_unread_rows;
					$query .= " AND entry_status = 'unread'";
				}
				$query .= " ORDER BY ".$table_data['orderby']." {$table_data['order']} LIMIT %d OFFSET %d"; 
				//get form details
				$table_data['form_rows'] = $wpdb->get_results(
					$wpdb->prepare(
						$query,
						$this->form_id,
						$table_data['per_page'],
						( $table_data['current_page'] - 1 ) * $table_data['per_page']
					) 
				);
				
				$table_data['table_columns'] = array( );
				$table_data['primary_column'] = 'cb';
				//Convert group concat string into array 
				foreach ( $table_data['form_rows'] as $key => $value ) {
					if ( ! empty( $table_data['form_rows'][ $key ]->entry_data ) ) {
						$table_data['form_rows'][ $key ]->entry_data = maybe_unserialize( $table_data['form_rows'][ $key ]->entry_data );
						if ( empty( $table_data['table_columns'] ) && is_array( $table_data['form_rows'][ $key ]->entry_data ) ) {
							//checkbox column
							$table_data['table_columns']['cb'] = '<input type="checkbox" />';
							$index = 0;
							//Dynamic column
							foreach ( $table_data['form_rows'][ $key ]->entry_data as $name_attr => $form_entry) {
								if ( $index < 3 ) {
									if ( 0 === $index) {
										$table_data['primary_column'] = $name_attr;
									}
									$table_data['table_columns'][$name_attr] = empty( $form_entry['label'] ) ? $form_entry['label'] : substr( $form_entry['label'], 0, 15 );
									$index++;
								}
							}
						}
					}
				}
			}
			
			if ( isset( $table_data['total_rows'] ) && isset( $table_data['form_rows'] ) && ! empty( $table_data['per_page'] ) && ! empty( $table_data['table_columns'] ) && ! empty( $table_data['primary_column'] ) ) {
				$this->set_pagination_args(
					array(
						'total_items' => $table_data['total_rows'],
						'per_page'    => $table_data['per_page'],
					)
				);
	
				$this->items = $table_data['form_rows'];
	
				//$this->_column_headers = array( $columns, $hidden, $sortable, $primary );
				//Add $primary column name to make table responsive
				$this->_column_headers = array( 
					array_merge( $table_data['table_columns'], $this->get_columns() ),
					$this->get_hidden_columns(),
					$this->get_sortable_columns(),
					$table_data['primary_column']
				);
			}
		}

		/**
		 * Gets a list of columns.
		 *
		 * The format is:
		 * - `'internal-name' => 'Title'`
		 *
		 *
		 * @return array
		 */
		public function get_columns() {
			return apply_filters( 'gutena_forms_entries_table_list_get_columns', array(
				"entry_id" => __( 'ID', 'gutena-forms' ),
				"added_time" => __( 'Date', 'gutena-forms' ),
				"status"	=> __( 'Status', 'gutena-forms' ) .'  '. $this->lock_svg(),
				"entry_action" => __( 'Action', 'gutena-forms' ),
			) );
		}

		/**
		 * Gets the list of views available on this table.
		 *
		 * The format is an associative array:
		 * - `'id' => 'link'`
		 *
		 *
		 * @return array
		 */
		protected function get_views() { 
			$entry_view = empty( $_GET['entry_view'] ) ? 'all': sanitize_key( wp_unslash( $_GET['entry_view'] ) );

			$views = apply_filters( 
				'gutena_forms_entries_get_views', 
				array(
					'all' => array(
						'label' => __( 'All', 'gutena-forms' ),
						'total' => $this->total_rows
					),
					'unread' => array(
						'label' => __( 'Unread', 'gutena-forms' ),
						'total' => $this->total_unread_rows
					),
				) 
			);

			$view_links = array();
			
			foreach ( $views as $view_id => $view ) {
				$view_links[ $view_id ] = '<a href="'. esc_url( admin_url( 'admin.php?page=gutena-forms&entry_view='. esc_attr( $view_id ) .'&formid='.esc_attr( $this->form_id ) )).'" class="'.( ( $view_id === $entry_view ) ? 'current':'' ).'" >'. esc_html( $view['label'] ) .' <span class="count">('. esc_html( $view['total'] ) .')</span></a>';
			}

			return $view_links;
		}

		/**
		 * Gets a list of hidden columns.
		 * 
		 * @return array
		 */
		public function get_hidden_columns() {
			return array(
				'entry_id'
			);
		}


		/**
		 * Gets a list of sortable columns.
		 *
		 * The format is:
		 * - `'internal-name' => 'orderby'`
		 * - `'internal-name' => array( 'orderby', 'asc' )` - The second element sets the initial sorting order.
		 * - `'internal-name' => array( 'orderby', true )`  - The second element makes the initial order descending.
		 *
		 *
		 * @return array
		 */
		public function get_sortable_columns() {
			return array( 
				'added_time' => array( 'added_time', true )
			);
		}

		/**
		 * Generates content for a single row of the table.
		 *
		 * @since 3.1.0
		 *
		 * @param object|array $item The current item
		 */
		public function single_row( $form_entry ) {
			echo '<tr class="'.esc_attr( $form_entry->entry_status ).'" currentstatus="'.esc_attr( $form_entry->entry_status ).'" entryid="'.esc_attr( $form_entry->entry_id ).'" >';
			$this->single_row_columns( $form_entry );
			echo '</tr>';
		}

		public function column_cb( $form_entry ) {
			return apply_filters( 'gutena_forms_entries_table_list_column_cb', sprintf(
				'<input type="checkbox" name="form_entry_id[]" value="%d" /> ',
				$form_entry->entry_id
			), $form_entry );
		}

		public function column_default( $form_entry, $column_name ) {
			$column_value = '';
			switch ( $column_name ) {
				case 'entry_id':
					$column_value = $form_entry->entry_id;
				break;
				case 'added_time':
					$column_value = date_format( date_create( $form_entry->added_time ),"M d, Y");
					//.' '.__( 'at', 'gutena-forms' ).' '.date_format( date_create( $form_entry->added_time ),"g:i a");
				break;
				case 'status':
					$column_value = '<div class="gfp-entries-status">
					<a modalid="gutena-forms-go-pro-modal" href="#" class="gutena-forms-modal-btn"  >
					<span class="status-title-icon-wrapper" style="background-color:#7b68ee33;">
						<span class="status-title">'.__( 'Hot', 'gutena-forms' ).'</span>
						<span class="status-change-icon"></span>
					</span>
					</a>
					</div>';
				break;
				case 'entry_action':
					
					//Quick view entries
					$column_value = $this->form_entry_view( $form_entry );
					//delete entries
					if ( apply_filters( 'gutena_forms_check_user_access', $this->store->is_gfadmin(), 'delete_entries' ) ) {
						$column_value .= ' | <a href="'. esc_url( admin_url( 'admin.php?page=gutena-forms&formid='.esc_attr( $form_entry->form_id ).'&action=trash&gfnonce='.wp_create_nonce( 'gutena_Forms' ).'&form_entry_id='.$form_entry->entry_id ) ) .'" class="gf-delete" >'.__( 'Trash', 'gutena-forms' ).'</a>';
					}
				break;
				default:
					if ( ! empty( $form_entry->entry_data[$column_name] ) &&  ! empty( $form_entry->entry_data[$column_name]['value'] ) ) {
						$column_value = $this->store->view_field_value( $form_entry->entry_data[$column_name], substr( $form_entry->entry_data[$column_name]['value'] , 0, 20 ) );
					}
				break;
			}

			return apply_filters( 'gutena_forms_entries_table_list_column_default', $column_value, $form_entry, $column_name );
		}

		//Quick Entry View
		public function form_entry_view( $form_entry ) {
			if ( empty( $form_entry->entry_data ) ) {
				return '';
			}
			//Action Html
			$actionHtml = '<img src="'.GUTENA_FORMS_PLUGIN_URL . 'assets/img/view-actions.png'.'" alt="'.__( 'Print, Export, Resend Notifications', 'gutena-forms' ).'" />';
			$actionHtml = apply_filters( 'gutena_forms_entries_quick_view_actions_html', $actionHtml, $form_entry );

			$class_id = 'gutena-form-entry-'. $form_entry->entry_id;
			$html = '
			<div id="' . esc_attr( $class_id ) . '"  class="gutena-forms-modal" >
			<div class="gutena-forms-modal-content" >
			<span class="gf-close-btn">&times;</span>
			<div class="gf-header" > 
				<div class="gf-title" >'.__( 'Entry', 'gutena-forms' ).'</div> 
				<div class="gf-action" > 
					<div class="gf-action-btn" >'.__( 'Actions', 'gutena-forms' ).' </div>
					<div class="gf-action-content" style="display:none" >
					'.$actionHtml.'
					</div>
				</div>
			</div>
			
			<div class="gf-body" >
			';
			foreach ($form_entry->entry_data as $name_attr => $fieldData) {
				$html .='<div class="gf-row" >
				<div class="gf-field-label">'.esc_html( $fieldData['label'] ).'</div>
				<div  >:</div>
				<div class="gf-field-value">'.$this->store->view_field_value( $fieldData ).'</div>
				</div>';
			}
			$html .= '</div><div class="gf-footer" ></div></div></div><a modalid="' . esc_attr( $class_id ) . '" href="#" class="gutena-forms-modal-btn quick-view-form-entry-'.esc_attr( $form_entry->entry_status ).'" entryid="'.esc_attr( $form_entry->entry_id ).'" > '.__( 'Quick View', 'gutena-forms' ).'</a>';
			return $html;
		}

		public function get_bulk_actions() {
			return apply_filters( 'gutena_forms_entries_table_list_bulk_actions', array(
				"read" => __( 'Mark Read', 'gutena-forms' ),
				"unread" => __( 'Mark Unread', 'gutena-forms' ),
				"trash" => __( 'Delete', 'gutena-forms' ),
			) );
		}

		/**
		 * Render page with this table
		 * 
		 * @param class $store
		 */
		public function render_list_table( $store = '' ) {
			if ( empty( $store ) && function_exists('wp_kses_post') ) {
				return;
			}
			$this->store = $store;
			$this->prepare_items();
			//header
			echo '<div class="entries">';
			
			//body
			echo '<div class="gf-body">';
			
			echo "<form method='post' name='search_form' action='" . esc_url( admin_url( 'admin.php?page=gutena-forms&formid='.$this->form_id ) ) . "'>";

			echo '<div class="entries-filter-wrapper" >';
			$this->views();
			do_action( 'gutena_forms_dashboard_entries_table_topbar', array() );
			echo '</div>';

			echo '<input type="hidden" name="gutena_forms_formid" value="'.esc_attr( $this->form_id ).'" />
			<input type="hidden" name="gfnonce" value="'.wp_create_nonce( 'gutena_Forms' ).'" />';
			$this->display();
			echo "</form>";
			echo '</div>';
			echo '</div>';
			
		}

		/**
		 * Entries filters
		 */
		public function table_topbar_filters( $args ) {
			
			echo '<div class="gfp-entries-table-filter">
			<span class="title title-with-icon "> '.  __( 'Filter by', 'gutena-forms' )  . ' ' . $this->lock_svg() .' </span>
			<a modalid="gutena-forms-go-pro-modal" href="#" class="gutena-forms-modal-btn"  >
			<input type="date" name="date_search" class="gfp-filter date-filter" disabled  >
			<select name="tag_search" class="gfp-filter tag-filter" disabled >
				<option value="">'.  __( 'Tag', 'gutena-forms' )  . '</option>
			</select>
			<select name="entry_status" class="gfp-filter status-filter" disabled>
			<option value="">'.  __( 'Status', 'gutena-forms' )  . '</option>
			</select>
			<input type="submit" id="filter-submit" class="button" value="'.  __( 'Filter', 'gutena-forms' )  . '" disabled >
			</a>
			<a href="#" class="button" disabled >'.  __( 'Reset', 'gutena-forms' )  . '</a></div>
			<p class="search-box">
				<label class="screen-reader-text">'.  __( 'Search', 'gutena-forms' )  . ':</label>
				<a modalid="gutena-forms-go-pro-modal" href="#" class="gutena-forms-modal-btn"  >
				<input type="search" name="s" value="" placeholder="'.  __( 'Search', 'gutena-forms' )  . '" disabled>
					<input type="submit" id="search-submit" class="button" value="'.  __( 'Search', 'gutena-forms' )  . '" disabled>
				</a>
			</p> ';
		}

		public function lock_svg() {
			return '<svg width="11" height="12" viewBox="0 0 11 12" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M8.57143 4H9.71428C10.0299 4 10.2857 4.25584 10.2857 4.57143V11.4286C10.2857 11.7442 10.0299 12 9.71428 12H0.571429C0.25584 12 0 11.7442 0 11.4286V4.57143C0 4.25584 0.25584 4 0.571429 4H1.71429V3.42857C1.71429 1.53502 3.24931 0 5.14286 0C7.0364 0 8.57143 1.53502 8.57143 3.42857V4ZM4.57143 8.41851V9.71429H5.71429V8.41851C6.05589 8.22091 6.28571 7.8516 6.28571 7.42857C6.28571 6.79737 5.77406 6.28571 5.14286 6.28571C4.51166 6.28571 4 6.79737 4 7.42857C4 7.8516 4.22983 8.22091 4.57143 8.41851ZM7.42857 4V3.42857C7.42857 2.16621 6.4052 1.14286 5.14286 1.14286C3.88049 1.14286 2.85714 2.16621 2.85714 3.42857V4H7.42857Z" fill="#606060"/>
			</svg>';
		}
		
		/**
		 * Extra controls to be displayed between bulk actions and pagination.
		 *
		 *
		 * @param string $which postion of navigation
		 */
		protected function extra_tablenav( $which ) {
			do_action( 'gutena_forms_dashboard_extra_tablenav', $which );
		}
	}
}