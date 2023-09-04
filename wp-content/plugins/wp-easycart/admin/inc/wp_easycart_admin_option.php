<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_option' ) ) :

	final class wp_easycart_admin_option {

		protected static $_instance = null;

		public $option_list_file;
		public $optionitem_list_file;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			$this->option_list_file = EC_PLUGIN_DIRECTORY . '/admin/template/products/options/option-list.php';
			$this->optionitem_list_file = EC_PLUGIN_DIRECTORY . '/admin/template/products/options/optionitem-list.php';

			/* Process Admin Messages */
			add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );
			add_filter( 'wp_easycart_admin_error_messages', array( $this, 'add_failure_messages' ) );

			/* Process Form Actions */
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_add_new_optionitem' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_update_optionitem' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_add_new_option' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_update_option' ) );

			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_duplicate_option' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_duplicate_optionitem' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_optionitem' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_optionitem' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_option' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_option' ) );
		}

		public function process_add_new_optionitem() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_POST['ec_admin_form_action'] ) && 'add-new-optionitem' == $_POST['ec_admin_form_action'] ) {
				$result = $this->insert_optionitem();
				wp_easycart_admin()->redirect( 'wp-easycart-products', 'optionitems', $result );
			}
		}

		public function process_update_optionitem() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_POST['ec_admin_form_action'] ) && 'update-optionitem' == $_POST['ec_admin_form_action'] ) {
				$result = $this->update_optionitem();
				wp_easycart_admin()->redirect( 'wp-easycart-products', 'optionitems', $result );
			}
		}

		public function process_add_new_option() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_POST['ec_admin_form_action'] ) && 'add-new-option' == $_POST['ec_admin_form_action'] ) {
				$result = $this->insert_option();
				wp_easycart_admin()->redirect( 'wp-easycart-products', 'option', $result );
			}
		}

		public function process_update_option() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_POST['ec_admin_form_action'] ) && 'update-option' == $_POST['ec_admin_form_action'] ) {
				$result = $this->update_option();
				wp_easycart_admin()->redirect( 'wp-easycart-products', 'option', $result );
			}
		}

		public function process_duplicate_option() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['subpage'] ) && 'option' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'duplicate-option' == $_GET['ec_admin_form_action'] && isset( $_GET['option_id'] ) && ! isset( $_GET['bulk'] ) ) {
				$result = $this->duplicate_option();
				wp_easycart_admin()->redirect( 'wp-easycart-products', 'option', $result );
			}
		}

		public function process_duplicate_optionitem() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['subpage'] ) && 'optionitems' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'duplicate-optionitem' == $_GET['ec_admin_form_action'] && isset( $_GET['optionitem_id'] ) && ! isset( $_GET['bulk'] ) ) {
				$result = $this->duplicate_optionitem();
				wp_easycart_admin()->redirect( 'wp-easycart-products', 'optionitems', $result );
			}
		}

		public function process_delete_optionitem() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['subpage'] ) && 'optionitems' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'delete-optionitem' == $_GET['ec_admin_form_action'] && isset( $_GET['optionitem_id'] ) && ! isset( $_GET['bulk'] ) ) {
				$result = $this->delete_optionitem();
				wp_easycart_admin()->redirect( 'wp-easycart-products', 'optionitems', $result );
			}
		}

		public function process_bulk_delete_optionitem() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['subpage'] ) && 'optionitems' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'delete-optionitem' == $_GET['ec_admin_form_action'] && ! isset( $_GET['optionitem_id'] ) && isset( $_GET['bulk'] ) ) {
				$result = $this->bulk_delete_optionitem();
				wp_easycart_admin()->redirect( 'wp-easycart-products', 'optionitems', $result );
			}
		}

		public function process_delete_option() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['subpage'] ) && 'option' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'delete-option' == $_GET['ec_admin_form_action'] && isset( $_GET['option_id'] ) && ! isset( $_GET['bulk'] ) ) {
				$result = $this->delete_option();
				wp_easycart_admin()->redirect( 'wp-easycart-products', 'option', $result );
			}
		}

		public function process_bulk_delete_option() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['subpage'] ) && 'option' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'delete-option' == $_GET['ec_admin_form_action'] && ! isset( $_GET['option_id'] ) && isset( $_GET['bulk'] ) ) {
				$result = $this->bulk_delete_option();
				wp_easycart_admin()->redirect( 'wp-easycart-products', 'option', $result );
			}
		}

		public function add_success_messages( $messages ) {
			if ( isset( $_GET['success'] ) && 'option-inserted' == $_GET['success'] ) {
				$messages[] = __( 'Option successfully created', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'option-updated' == $_GET['success'] ) {
				$messages[] = __( 'Option successfully updated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'option-deleted' == $_GET['success'] ) {
				$messages[] = __( 'Option successfully deleted', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'option-item-inserted' == $_GET['success'] ) {
				$messages[] = __( 'Option item successfully created', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'option-item-updated' == $_GET['success'] ) {
				$messages[] = __( 'Option item successfully updated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'option-item-deleted' == $_GET['success'] ) {
				$messages[] = __( 'Option item successfully deleted', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'option-item-duplicated' == $_GET['success'] ) {
				$messages[] = __( 'Option item successfully duplicated', 'wp-easycart' );
			}
			return $messages;
		}

		public function add_failure_messages( $messages ) {
			if ( isset( $_GET['error'] ) && 'option-inserted-error' == $_GET['error'] ) {
				$messages[] = __( 'Option failed to create', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && 'option-updated-error' == $_GET['error'] ) {
				$messages[] = __( 'Option failed to update', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && 'option-deleted-error' == $_GET['error'] ) {
				$messages[] = __( 'Option failed to delete', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && 'option-duplicate' == $_GET['error'] ) {
				$messages[] = __( 'Option failed to create due to duplicate', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && 'option-item-duplicate' == $_GET['error'] ) {
				$messages[] = __( 'Option item failed to create due to duplicate', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && 'option-item-inserted-error' == $_GET['error'] ) {
				$messages[] = __( 'Option item failed to create', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && 'option-item-deleted-error' == $_GET['error'] ) {
				$messages[] = __( 'Option item failed to delete', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && 'option-item-duplicate-error' == $_GET['error'] ) {
				$messages[] = __( 'Option item failed to duplicate', 'wp-easycart' );
			}
			return $messages;
		}

		public function load_option_list() {
			if ( isset( $_GET['ec_admin_form_action'] ) && ( ( isset( $_GET['option_id'] ) && 'edit' == $_GET['ec_admin_form_action'] ) || 'add-new-option' == $_GET['ec_admin_form_action'] ) ) {
				include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_details_option.php' );
				$details = new wp_easycart_admin_details_option();
				$details->output( sanitize_key( $_GET['ec_admin_form_action'] ) );
			} else {
				include( $this->option_list_file );
			}
		}

		public function load_optionitem_list() {
			if ( isset( $_GET['ec_admin_form_action'] ) && ( ( isset( $_GET['optionitem_id'] ) && 'edit' == $_GET['ec_admin_form_action'] ) || 'add-new-optionitem' == $_GET['ec_admin_form_action'] ) ) {
				include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_details_optionitem.php' );
				$details = new wp_easycart_admin_details_optionitem();
				$details->output( sanitize_key( $_GET['ec_admin_form_action'] ) );
			} else {
				include( $this->optionitem_list_file );
			}
		}

		public function insert_option() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-option-details' ) ) {
				return false;
			}

			global $wpdb;

			$option_name = ( isset( $_POST['option_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['option_name'] ) ) : '';
			$option_label = ( isset( $_POST['option_label'] ) ) ? wp_easycart_escape_html( wp_unslash( $_POST['option_label'] ) ) : '';
			$option_type = ( isset( $_POST['option_type'] ) ) ? sanitize_text_field( wp_unslash( $_POST['option_type'] ) ) : '';
			$option_error_text = ( isset( $_POST['option_error_text'] ) ) ? wp_easycart_escape_html( wp_unslash( $_POST['option_error_text'] ) ) : '';
			$url_var = ( isset( $_POST['option_meta_url_var'] ) ) ? preg_replace( '/[^a-zA-Z0-9\_]+/', '', sanitize_text_field( wp_unslash( $_POST['option_meta_url_var'] ) ) ) : '';
			$option_meta = array(
				'min' => ( isset( $_POST['option_meta_min'] ) ) ? sanitize_text_field( wp_unslash( $_POST['option_meta_min'] ) ) : '',
				'max' => ( isset( $_POST['option_meta_max'] ) ) ? sanitize_text_field( wp_unslash( $_POST['option_meta_max'] ) ) : '',
				'step' => ( isset( $_POST['option_meta_step'] ) ) ? sanitize_text_field( wp_unslash( $_POST['option_meta_step'] ) ) : '',
				'url_var' => $url_var,
				'swatch_size' => ( isset( $_POST['option_meta_swatch_size'] ) ) ? sanitize_text_field( wp_unslash( $_POST['option_meta_swatch_size'] ) ) : 30,
			);
			$option_required = 0;
			if ( isset( $_POST['option_required'] ) || 'basic-swatch' == $option_type || 'basic-combo' == $option_type ) {
				$option_required = 1;
			}

			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_option( option_name, option_label, option_type, option_required, option_error_text, option_meta ) VALUES( %s, %s, %s, %d, %s, %s )', $option_name, $option_label, $option_type, $option_required, $option_error_text, maybe_serialize( $option_meta ) ) );
			$option_id = $wpdb->insert_id;

			if ( 'file' == $option_type || 'text' == $option_type || 'number' == $option_type || 'textarea' == $option_type || 'date' == $option_type || 'dimensions1' == $option_type || 'dimensions2' == $option_type ) {
				if ( 'file' == $option_type ) {
					$option_name = 'File Field';
				}
				if ( 'text' == $option_type ) {
					$option_name = 'Text Box Input';
				}
				if ( 'number' == $option_type ) {
					$option_name = 'Number Box Input';
				}
				if ( 'textarea' == $option_type ) {
					$option_name = 'Text Area Input';
				}
				if ( 'date' == $option_type ) {
					$option_name = 'Date Field';
				}
				if ( 'dimensions1' == $option_type ) {
					$option_name = 'DimensionType1';
				}
				if ( 'dimensions2' == $option_type ) {
					$option_name = 'DimensionType2';
				}

				$wpdb->query( $wpdb->prepare( "INSERT INTO ec_optionitem( option_id, optionitem_name, optionitem_price, optionitem_price_onetime, optionitem_price_override, optionitem_weight, optionitem_weight_onetime, optionitem_weight_override, optionitem_order, optionitem_icon, optionitem_initial_value ) VALUES( %d, %s, '0.00', '0.00', '-1', '0.00', '0.00', '-1.00', 1, '', '' )", $option_id, $option_name ) );
			}

			do_action( 'wp_easycart_optionset_created', $option_id );

			return array( 'success' => 'option-inserted' );
		}


		public function update_option() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-option-details' ) ) {
				return false;
			}

			if ( ! isset( $_POST['option_id'] ) ) {
				return false;
			}

			global $wpdb;

			$option_id = ( isset( $_POST['option_id'] ) ) ? (int) $_POST['option_id'] : 0;
			$option_name = ( isset( $_POST['option_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['option_name'] ) ) : 0;
			$option_label = ( isset( $_POST['option_label'] ) ) ? wp_easycart_escape_html( wp_unslash( $_POST['option_label'] ) ) : 0;
			$option_type = ( isset( $_POST['option_type'] ) ) ? sanitize_text_field( wp_unslash( $_POST['option_type'] ) ) : 0;
			$option_error_text = ( isset( $_POST['option_error_text'] ) ) ? wp_easycart_escape_html( wp_unslash( $_POST['option_error_text'] ) ) : 0;
			$url_var = ( isset( $_POST['option_meta_url_var'] ) ) ? preg_replace( '/[^a-zA-Z0-9\_]+/', '', sanitize_text_field( wp_unslash( $_POST['option_meta_url_var'] ) ) ) : 0;
			$option_meta = array(
				'min' => ( isset( $_POST['option_meta_min'] ) ) ? sanitize_text_field( wp_unslash( $_POST['option_meta_min'] ) ) : 0,
				'max' => ( isset( $_POST['option_meta_max'] ) ) ? sanitize_text_field( wp_unslash( $_POST['option_meta_max'] ) ) : 0,
				'step' => ( isset( $_POST['option_meta_step'] ) ) ? sanitize_text_field( wp_unslash( $_POST['option_meta_step'] ) ) : 0,
				'url_var' => $url_var,
				'swatch_size' => ( isset( $_POST['option_meta_swatch_size'] ) ) ? sanitize_text_field( wp_unslash( $_POST['option_meta_swatch_size'] ) ) : 30,
			);
			$option_required = 0;
			if ( isset( $_POST['option_required'] ) || 'basic-swatch' == $option_type || 'basic-combo' == $option_type ) {
				$option_required = 1;
			}

			$wpdb->query( $wpdb->prepare( 'UPDATE ec_option SET option_id = %s, option_name = %s, option_label = %s, option_type = %s, option_required = %s, option_error_text = %s, option_meta = %s WHERE option_id = %s', $option_id, $option_name, $option_label, $option_type, $option_required, $option_error_text, maybe_serialize( $option_meta ), $option_id ) );

			if ( 'file' == $option_type || 'text' == $option_type || 'number' == $option_type || 'textarea' == $option_type || 'date' == $option_type || 'dimensions1' == $option_type || 'dimensions2' == $option_type ) {
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_optionitem WHERE option_id = %d', $option_id ) );
				if ( 'file' == $option_type ) {
					$option_name = 'File Field';
				}
				if ( 'text' == $option_type ) {
					$option_name = 'Text Box Input';
				}
				if ( 'number' == $option_type ) {
					$option_name = 'Number Box Input';
				}
				if ( 'textarea' == $option_type ) {
					$option_name = 'Text Area Input';
				}
				if ( 'date' == $option_type ) {
					$option_name = 'Date Field';
				}
				if ( 'dimensions1' == $option_type ) {
					$option_name = 'DimensionType1';
				}
				if ( 'dimensions2' == $option_type ) {
					$option_name = 'DimensionType2';
				}
				$wpdb->query( $wpdb->prepare( "INSERT INTO ec_optionitem( option_id, optionitem_name, optionitem_price, optionitem_price_onetime, optionitem_price_override, optionitem_weight, optionitem_weight_onetime, optionitem_weight_override, optionitem_order, optionitem_icon, optionitem_initial_value ) VALUES( %d, %s, '0.00', '0.00', '-1', '0.00', '0.00', '-1.00', 1, '', '' )", $option_id, $option_name ) );
			}

			do_action( 'wp_easycart_optionset_updated', $option_id );

			return array( 'success' => 'option-updated' );
		}

		public function duplicate_option() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-duplicate-option' ) ) {
				return false;
			}

			if ( ! isset( $_GET['option_id'] ) ) {
				return false;
			}

			global $wpdb;
			$option_id = (int) $_GET['option_id'];

			$original_record = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_option.* FROM ec_option WHERE option_id = %d', $option_id ) );
			$optionitems = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', $option_id ) );
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_option() VALUES()' ) );
			$new_option_id = $wpdb->insert_id;

			$sql = 'UPDATE ec_option SET ';
			foreach ( $original_record as $key => $value ) {
				if ( 'option_id' != $key && 'square_id' != $key ) {
					$sql .= '`'.$key.'` = ' . $wpdb->prepare( '%s', $value ) .', ';
				}
			}

			$sql = substr( $sql, 0, strlen( $sql ) - 2 );
			$wpdb->query( $sql . $wpdb->prepare( ' WHERE option_id = %d', $new_option_id ) );

			foreach ( $optionitems as $optionitem ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_optionitem( option_id ) VALUES( %d )', $new_option_id ) );
				$new_optionitem_id = $wpdb->insert_id;
				$sql = 'UPDATE ec_optionitem SET ';
				foreach ( $optionitem as $key => $value ) {
					if ( $key != 'optionitem_id' && $key != 'option_id' && $key != 'square_id' ) {
						$sql .= '`' . $key . '` = ' . $wpdb->prepare( '%s', $value ) . ', ';
					}
				}
				$sql = substr( $sql, 0, strlen( $sql ) - 2 );
				$wpdb->query( $sql . $wpdb->prepare( ' WHERE optionitem_id = %d', $new_optionitem_id ) );
			}

			do_action( 'wp_easycart_optionset_created', $new_optionitem_id );

			$args = array( 'success' => 'option-duplicated' );

			if ( isset( $_GET['pagenum'] ) ) {
				$args['pagenum'] = (int) $_GET['pagenum'];
			}
			$valid_orderby = array( 'option_name', 'option_type', 'option_id', 'option_required', 'optionitem_name', 'optionitem_price', 'optionitem_weight' );
			if ( isset( $_GET['orderby'] ) && in_array( $_GET['orderby'], $valid_orderby ) ) {
				$args['orderby'] = sanitize_text_field( wp_unslash( $_GET['orderby'] ) );
			}
			if ( isset( $_GET['order'] ) && 'desc' == strtolower( $_GET['order'] ) ) {
				$args['order'] = 'desc';
			} else {
				$args['order'] = 'asc';
			}
			return $args;
		}

		public function delete_option() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-option' ) ) {
				return false;
			}

			if ( ! isset( $_GET['option_id'] ) ) {
				return false;
			}

			global $wpdb;

			$option_id = (int) $_GET['option_id'];

			do_action( 'wp_easycart_optionset_deleting', $option_id );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_option WHERE ec_option.option_id = %d', $option_id ) );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_optionitem WHERE ec_optionitem.option_id = %d', $option_id ) );
			do_action( 'wp_easycart_optionset_deleted', $option_id );

			$args = array( 'success' => 'option-deleted' );

			if ( isset( $_GET['pagenum'] ) ) {
				$args['pagenum'] = (int) $_GET['pagenum'];
			}
			$valid_orderby = array( 'option_name', 'option_type', 'option_id', 'option_required', 'optionitem_name', 'optionitem_price', 'optionitem_weight' );
			if ( isset( $_GET['orderby'] ) && in_array( $_GET['orderby'], $valid_orderby ) ) {
				$args['orderby'] = sanitize_text_field( wp_unslash( $_GET['orderby'] ) );
			}
			if ( isset( $_GET['order'] ) && 'desc' == strtolower( $_GET['order'] ) ) {
				$args['order'] = 'desc';
			} else {
				$args['order'] = 'asc';
			}

			return $args;
		}

		public function bulk_delete_option() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-option' ) ) {
				return false;
			}

			if ( ! isset( $_GET['bulk'] ) ) {
				return false;
			}

			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				do_action( 'wp_easycart_optionset_deleting', (int) $bulk_id );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_option WHERE option_id = %d', (int) $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_optionitem WHERE option_id = %d', (int) $bulk_id ) );
				do_action( 'wp_easycart_optionset_deleted', (int) $bulk_id );
			}

			$args = array( 'success' => 'option-deleted' );

			if ( isset( $_GET['pagenum'] ) ) {
				$args['pagenum'] = (int) $_GET['pagenum'];
			}
			$valid_orderby = array( 'option_name', 'option_type', 'option_id', 'option_required', 'optionitem_name', 'optionitem_price', 'optionitem_weight' );
			if ( isset( $_GET['orderby'] ) && in_array( $_GET['orderby'], $valid_orderby ) ) {
				$args['orderby'] = sanitize_text_field( $_GET['orderby'] );
			}
			if ( isset( $_GET['order'] ) && 'desc' == strtolower( $_GET['order'] ) ) {
				$args['order'] = 'desc';
			} else {
				$args['order'] = 'asc';
			}
			return $args;
		}

		public function duplicate_optionitem() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-duplicate-optionitem' ) ) {
				return false;
			}

			if ( ! isset( $_GET['optionitem_id'] ) ) {
				return false;
			}

			global $wpdb;

			$optionitem_id = (int) $_GET['optionitem_id'];
			$args = array();

			$optionitem = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE optionitem_id = %d', $optionitem_id ) );
			$option_id = (int) $optionitem->option_id;
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_optionitem( option_id ) VALUES( %d )', $option_id ) );
			$new_optionitem_id = $wpdb->insert_id;
			$sql = 'UPDATE ec_optionitem SET ';
			foreach ( $optionitem as $key => $value ) {
				if ( 'optionitem_id' != $key && 'option_id' != $key && 'square_id' != $key ) {
					$sql .= '`' . $key . '` = ' . $wpdb->prepare( '%s', $value ) . ', ';
				}
			}
			$sql = substr( $sql, 0, strlen( $sql ) - 2 );
			$wpdb->query( $sql . $wpdb->prepare( ' WHERE optionitem_id = %d', $new_optionitem_id ) );
			do_action( 'wp_easycart_optionitem_created', $new_optionitem_id, $option_id );

			$args['option_id'] = (int) $option_id;
			$args['ec_admin_form_action'] = 'edit-optionitem';
			$args['success'] = 'option-item-duplicated';

			if ( isset( $_GET['pagenum'] ) ) {
				$args['pagenum'] = (int) $_GET['pagenum'];
			}
			$valid_orderby = array( 'option_name', 'option_type', 'option_id', 'option_required', 'optionitem_name', 'optionitem_price', 'optionitem_weight' );
			if ( isset( $_GET['orderby'] ) && in_array( $_GET['orderby'], $valid_orderby ) ) {
				$args['orderby'] = sanitize_text_field( wp_unslash( $_GET['orderby'] ) );
			}
			if ( isset( $_GET['order'] ) && 'desc' == strtolower( $_GET['order'] ) ) {
				$args['order'] = 'desc';
			} else {
				$args['order'] = 'asc';
			}
			return $args;
		}

		public function insert_optionitem() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-optionitem-details' ) ) {
				return false;
			}

			if ( ! isset( $_POST['option_id'] ) ) {
				return false;
			}

			global $wpdb;

			$option_id = (int) $_POST['option_id'];
			$option = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_option WHERE option_id = %d', $option_id ) );
			$optionitem_name = ( isset( $_POST['optionitem_name'] ) ) ? wp_easycart_escape_html( wp_unslash( $_POST['optionitem_name'] ) ) : '';
			$optionitem_enable_custom_price_label = ( isset( $_POST['optionitem_enable_custom_price_label'] ) ) ? (int) $_POST['optionitem_enable_custom_price_label'] : 0;
			$optionitem_custom_price_label = ( isset( $_POST['optionitem_custom_price_label'] ) ) ? wp_easycart_escape_html( wp_unslash( $_POST['optionitem_custom_price_label'] ) ) : '';
			$optionitem_price = ( isset( $_POST['optionitem_price'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_price'] ) ) : '';
			$optionitem_price_onetime = ( isset( $_POST['optionitem_price_onetime'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_price_onetime'] ) ) : '';
			$optionitem_price_override = ( isset( $_POST['optionitem_price_override'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_price_override'] ) ) : '-1';
			$optionitem_price_multiplier = ( isset( $_POST['optionitem_price_multiplier'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_price_multiplier'] ) ) : '';
			$optionitem_price_per_character = ( isset( $_POST['optionitem_price_per_character'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_price_per_character'] ) ) : '';
			$optionitem_weight = ( isset( $_POST['optionitem_weight'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_weight'] ) ) : '';
			$optionitem_weight_onetime = ( isset( $_POST['optionitem_weight_onetime'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_weight_onetime'] ) ) : '';
			$optionitem_weight_override = ( isset( $_POST['optionitem_weight_override'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_weight_override'] ) ) : '-1';
			$optionitem_weight_multiplier = ( isset( $_POST['optionitem_weight_multiplier'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_weight_multiplier'] ) ) : '';
			$optionitem_order = ( isset( $_POST['optionitem_order'] ) ) ? (int) $_POST['optionitem_order'] : 0;
			$optionitem_icon = ( isset( $_POST['optionitem_icon'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_icon'] ) ) : '';
			$optionitem_initial_value = ( isset( $_POST['optionitem_initial_value'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_initial_value'] ) ) : '';
			$optionitem_model_number = ( isset( $_POST['optionitem_model_number'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_model_number'] ) ) : '';
			$optionitem_allow_download = ( isset( $_POST['optionitem_allow_download'] ) ) ? 1 : 0;
			$optionitem_disallow_shipping = ( isset( $_POST['optionitem_disallow_shipping'] ) ) ? 1 : 0;
			$optionitem_initially_selected = ( isset( $_POST['optionitem_initially_selected'] ) ) ? 1 : 0;
			$optionitem_download_override_file = ( isset( $_POST['optionitem_download_override_file'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_download_override_file'] ) ) : '';
			$optionitem_download_addition_file = ( isset( $_POST['optionitem_download_addition_file'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_download_addition_file'] ) ) : '';
			
			if ( $optionitem_initially_selected && $option && ( 'swatch' == $option->option_type || 'combo' == $option->option_type || 'radio' == $option->option_type ) ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_optionitem SET optionitem_initially_selected = 0 WHERE option_id = %d', $option_id ) );
			}

			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_optionitem( option_id, optionitem_name, optionitem_enable_custom_price_label, optionitem_custom_price_label, optionitem_price, optionitem_price_onetime, optionitem_price_override, optionitem_price_multiplier, optionitem_price_per_character, optionitem_weight, optionitem_weight_onetime, optionitem_weight_override, optionitem_weight_multiplier, optionitem_order, optionitem_icon, optionitem_initial_value, optionitem_model_number, optionitem_allow_download, optionitem_disallow_shipping, optionitem_initially_selected, optionitem_download_override_file, optionitem_download_addition_file ) VALUES( %d, %s, %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %s, %s, %s, %d, %d, %d, %s, %s )', $option_id, $optionitem_name, $optionitem_enable_custom_price_label, $optionitem_custom_price_label, $optionitem_price, $optionitem_price_onetime, $optionitem_price_override, $optionitem_price_multiplier, $optionitem_price_per_character, $optionitem_weight, $optionitem_weight_onetime, $optionitem_weight_override, $optionitem_weight_multiplier, $optionitem_order, $optionitem_icon, $optionitem_initial_value, $optionitem_model_number, $optionitem_allow_download, $optionitem_disallow_shipping, $optionitem_initially_selected, $optionitem_download_override_file, $optionitem_download_addition_file ) );
			$optionitem_id = $wpdb->insert_id;
			do_action( 'wp_easycart_optionitem_created', $optionitem_id, $option_id );

			$args = array(
				'success' => 'option-item-inserted',
				'option_id' => (int) $option_id,
			);

			if ( isset( $_GET['pagenum'] ) ) {
				$args['pagenum'] = (int) $_GET['pagenum'];
			}
			$valid_orderby = array( 'option_name', 'option_type', 'option_id', 'option_required', 'optionitem_name', 'optionitem_price', 'optionitem_weight' );
			if ( isset( $_GET['orderby'] ) && in_array( $_GET['orderby'], $valid_orderby ) ) {
				$args['orderby'] = sanitize_text_field( wp_unslash( $_GET['orderby'] ) );
			}
			if ( isset( $_GET['order'] ) && 'desc' == strtolower( $_GET['order'] ) ) {
				$args['order'] = 'desc';
			} else {
				$args['order'] = 'asc';
			}
			return $args;
		}

		public function update_optionitem() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-optionitem-details' ) ) {
				return false;
			}

			if ( ! isset( $_POST['optionitem_id'] ) ) {
				return false;
			}

			if ( ! isset( $_POST['option_id'] ) ) {
				return false;
			}

			global $wpdb;

			$optionitem_id = (int) $_POST['optionitem_id'];
			$option_id = (int) $_POST['option_id'];
			$option = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_option WHERE option_id = %d', $option_id ) );
			$optionitem_name = ( isset( $_POST['optionitem_name'] ) ) ? wp_easycart_escape_html( wp_unslash( $_POST['optionitem_name'] ) ) : '';
			$optionitem_enable_custom_price_label = ( isset( $_POST['optionitem_enable_custom_price_label'] ) ) ? (int) $_POST['optionitem_enable_custom_price_label'] : 0;
			$optionitem_custom_price_label = ( isset( $_POST['optionitem_custom_price_label'] ) ) ? wp_easycart_escape_html( wp_unslash( $_POST['optionitem_custom_price_label'] ) ) : '';
			$optionitem_price = ( isset( $_POST['optionitem_price'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_price'] ) ) : '';
			$optionitem_price_onetime = ( isset( $_POST['optionitem_price_onetime'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_price_onetime'] ) ) : '';
			$optionitem_price_override = ( isset( $_POST['optionitem_price_override'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_price_override'] ) ) : '-1';
			$optionitem_price_multiplier = ( isset( $_POST['optionitem_price_multiplier'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_price_multiplier'] ) ) : '';
			$optionitem_price_per_character = ( isset( $_POST['optionitem_price_per_character'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_price_per_character'] ) ) : '';
			$optionitem_weight = ( isset( $_POST['optionitem_weight'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_weight'] ) ) : '';
			$optionitem_weight_onetime = ( isset( $_POST['optionitem_weight_onetime'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_weight_onetime'] ) ) : '';
			$optionitem_weight_override = ( isset( $_POST['optionitem_weight_override'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_weight_override'] ) ) : '-1';
			$optionitem_weight_multiplier = ( isset( $_POST['optionitem_weight_multiplier'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_weight_multiplier'] ) ) : '';
			$optionitem_order = ( isset( $_POST['optionitem_order'] ) ) ? (int) $_POST['optionitem_order'] : 0;
			$optionitem_icon = ( isset( $_POST['optionitem_icon'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_icon'] ) ) : '';
			$optionitem_initial_value = ( isset( $_POST['optionitem_initial_value'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_initial_value'] ) ) : '';
			$optionitem_model_number = ( isset( $_POST['optionitem_model_number'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_model_number'] ) ) : '';
			$optionitem_allow_download = ( isset( $_POST['optionitem_allow_download'] ) ) ? 1 : 0;
			$optionitem_disallow_shipping = ( isset( $_POST['optionitem_disallow_shipping'] ) ) ? 1 : 0;
			$optionitem_initially_selected = ( isset( $_POST['optionitem_initially_selected'] ) ) ? 1 : 0;
			$optionitem_download_override_file = ( isset( $_POST['optionitem_download_override_file'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_download_override_file'] ) ) : '';
			$optionitem_download_addition_file = ( isset( $_POST['optionitem_download_addition_file'] ) ) ? sanitize_text_field( wp_unslash( $_POST['optionitem_download_addition_file'] ) ) : '';

			if ( $optionitem_initially_selected && $option && ( 'swatch' == $option->option_type || 'combo' == $option->option_type || 'radio' == $option->option_type ) ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_optionitem SET optionitem_initially_selected = 0 WHERE option_id = %d', $option_id ) );
			}

			$wpdb->query( $wpdb->prepare( 'UPDATE ec_optionitem SET optionitem_id = %d, option_id = %d, optionitem_name = %s, optionitem_enable_custom_price_label = %d, optionitem_custom_price_label = %s, optionitem_price = %s, optionitem_price_onetime = %s, optionitem_price_override = %s, optionitem_price_multiplier = %s, optionitem_price_per_character = %s, optionitem_weight = %s, optionitem_weight_onetime = %s, optionitem_weight_override = %s, optionitem_weight_multiplier = %s, optionitem_order = %d, optionitem_icon = %s, optionitem_initial_value = %s, optionitem_model_number = %s, optionitem_allow_download = %d, optionitem_disallow_shipping = %d, optionitem_initially_selected = %d, optionitem_download_override_file = %s, optionitem_download_addition_file = %s WHERE optionitem_id = %d', $optionitem_id, $option_id, $optionitem_name, $optionitem_enable_custom_price_label, $optionitem_custom_price_label, $optionitem_price, $optionitem_price_onetime, $optionitem_price_override, $optionitem_price_multiplier, $optionitem_price_per_character, $optionitem_weight, $optionitem_weight_onetime, $optionitem_weight_override, $optionitem_weight_multiplier, $optionitem_order, $optionitem_icon, $optionitem_initial_value, $optionitem_model_number, $optionitem_allow_download, $optionitem_disallow_shipping, $optionitem_initially_selected, $optionitem_download_override_file, $optionitem_download_addition_file, $optionitem_id ) );
			do_action( 'wp_easycart_optionitem_updated', $optionitem_id, $option_id );

			$args = array(
				'success' => 'option-item-updated',
				'option_id' => (int) $option_id,
			);
			if ( isset( $_GET['pagenum'] ) ) {
				$args['pagenum'] = (int) $_GET['pagenum'];
			}
			$valid_orderby = array( 'option_name', 'option_type', 'option_id', 'option_required', 'optionitem_name', 'optionitem_price', 'optionitem_weight' );
			if ( isset( $_GET['orderby'] ) && in_array( $_GET['orderby'], $valid_orderby ) ) {
				$args['orderby'] = sanitize_text_field( wp_unslash( $_GET['orderby'] ) );
			}
			if ( isset( $_GET['order'] ) && 'desc' == strtolower( $_GET['order'] ) ) {
				$args['order'] = 'desc';
			} else {
				$args['order'] = 'asc';
			}
			return $args;
		}

		public function delete_optionitem() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-optionitem' ) ) {
				return false;
			}

			if ( ! isset( $_GET['optionitem_id'] ) ) {
				return false;
			}

			global $wpdb;

			$optionitem_id = (int) $_GET['optionitem_id'];
			$option_id = $wpdb->get_var( $wpdb->prepare( 'SELECT option_id FROM ec_optionitem WHERE optionitem_id = %d', $optionitem_id ) );
			do_action( 'wp_easycart_optionitem_deleting', $optionitem_id, $option_id );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_optionitem WHERE optionitem_id = %d', $optionitem_id ) );
			do_action( 'wp_easycart_optionitem_deleted', $optionitem_id, $option_id );

			$args = array(
				'success' => 'option-item-deleted',
				'option_id' => (int) $option_id,
			);

			if ( isset( $_GET['pagenum'] ) ) {
				$args['pagenum'] = (int) $_GET['pagenum'];
			}
			$valid_orderby = array( 'option_name', 'option_type', 'option_id', 'option_required', 'optionitem_name', 'optionitem_price', 'optionitem_weight' );
			if ( isset( $_GET['orderby'] ) && in_array( $_GET['orderby'], $valid_orderby ) ) {
				$args['orderby'] = sanitize_text_field( wp_unslash( $_GET['orderby'] ) );
			}
			if ( isset( $_GET['order'] ) && 'desc' == strtolower( $_GET['order'] ) ) {
				$args['order'] = 'desc';
			} else {
				$args['order'] = 'asc';
			}
			return $args;
		}

		public function bulk_delete_optionitem() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-optionitems' ) ) {
				return false;
			}

			if ( ! isset( $_GET['bulk'] ) ) {
				return false;
			}

			global $wpdb;

			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.
			$query_vars = array();

			if ( count( $bulk_ids ) > 0 ) {
				$option_id = $wpdb->get_var( $wpdb->prepare( 'SELECT option_id FROM ec_optionitem WHERE optionitem_id = %d', (int) $bulk_ids[0] ) );
			}

			foreach ( $bulk_ids as $bulk_id ) {
				do_action( 'wp_easycart_optionitem_deleting', (int) $bulk_id, $option_id );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_optionitem WHERE optionitem_id = %d', (int) $bulk_id ) );
				do_action( 'wp_easycart_optionitem_deleted', (int) $bulk_id, $option_id );
			}

			$args = array(
				'success' => 'option-item-deleted',
				'option_id' => (int) $option_id,
			);

			if ( isset( $_GET['pagenum'] ) ) {
				$args['pagenum'] = (int) $_GET['pagenum'];
			}
			$valid_orderby = array( 'option_name', 'option_type', 'option_id', 'option_required', 'optionitem_name', 'optionitem_price', 'optionitem_weight' );
			if ( isset( $_GET['orderby'] ) && in_array( $_GET['orderby'], $valid_orderby ) ) {
				$args['orderby'] = sanitize_text_field( wp_unslash( $_GET['orderby'] ) );
			}
			if ( isset( $_GET['order'] ) && 'desc' == strtolower( $_GET['order'] ) ) {
				$args['order'] = 'desc';
			} else {
				$args['order'] = 'asc';
			}
			return $args;
		}

		public function save_optionitem_order() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( ! isset( $_POST['sort_order'] ) ) {
				return false;
			}

			if ( ! isset( $_POST['option_id'] ) ) {
				return false;
			}

			global $wpdb;
			$sort_order = (array) $_POST['sort_order']; // XSS OK. Forced array and each item sanitized.
			$option_id = (int) $_POST['option_id'];

			foreach ( $sort_order as $sort_item ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_optionitem SET optionitem_order = %d WHERE optionitem_id = %d AND option_id = %d', (int) $sort_item['order'], (int) $sort_item['id'], $option_id ) );
			}

			do_action( 'wp_easycart_optionitem_sort_saved', $option_id );
		}
	}
endif;

function wp_easycart_admin_option() {
	return wp_easycart_admin_option::instance();
}
wp_easycart_admin_option();

add_action( 'wp_ajax_ec_admin_ajax_save_optionitem_order', 'ec_admin_ajax_save_optionitem_order' );
function ec_admin_ajax_save_optionitem_order() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-table-sort' ) ) {
		return false;
	}

	wp_easycart_admin_option()->save_optionitem_order();
	die();
}
