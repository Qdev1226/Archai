<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_products' ) ) :

	final class wp_easycart_admin_products {

		protected static $_instance = null;

		public $products_setup_file;
		public $product_list_setup_file;
		public $product_store_defaults_file;
		public $product_details_setup_file;
		public $customer_review_setup_file;
		public $product_settings_file;
		public $price_display_options_file;
		public $inventory_options_file;
		public $product_list_file;
		public $product_details_edit_file;
		public $export_products_csv;
		public $upgrade_file;

		private $db;
		private $error_list;
		private $product_id_index;
		private $post_id_index;
		private $model_number_index;
		private $title_index;
		private $price_index;
		private $list_price_index;
		private $activate_in_store_index;
		private $is_subscription_index;
		private $bill_period_index;
		private $bill_length_index;
		private $trial_period_index;
		private $use_advanced_optionset_index;
		private $use_both_option_types_index;
		private $advanced_option_ids_index;
		private $categories_index;
		private $price_tiers_index;
		private $b2b_prices_index;
		private $headers;
		private $limit;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			$this->products_setup_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/products/products-setup.php';
			$this->product_list_setup_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/products/product-list.php';
			$this->product_store_defaults_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/products/product-store-defaults.php';
			$this->product_details_setup_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/products/product-details.php';
			$this->customer_review_setup_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/products/customer-review.php';
			$this->product_settings_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/products/products-settings.php';
			$this->price_display_options_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/products/price-display-options.php';
			$this->inventory_options_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/products/inventory-options.php';
			$this->product_list_file = EC_PLUGIN_DIRECTORY . '/admin/template/products/products/product-list.php';
			$this->product_details_edit_file = EC_PLUGIN_DIRECTORY . '/admin/template/products/products/product-details.php';
			$this->export_products_csv = EC_PLUGIN_DIRECTORY . '/admin/template/exporters/export-products-csv.php';
			$this->upgrade_file = EC_PLUGIN_DIRECTORY . '/admin/template/upgrade/upgrade-simple.php';

			add_action( 'wpeasycart_admin_products_setup', array( $this, 'load_product_settings' ) );
			add_action( 'wpeasycart_admin_products_setup', array( $this, 'load_product_list_setup' ) );
			add_action( 'wpeasycart_admin_products_setup', array( $this, 'load_product_store_defaults' ) );
			add_action( 'wpeasycart_admin_products_setup', array( $this, 'load_customer_review_setup' ) );
			add_action( 'wpeasycart_admin_products_setup', array( $this, 'load_product_details_setup' ) );
			add_action( 'wpeasycart_admin_products_setup', array( $this, 'load_price_display_options' ) );
			add_action( 'wpeasycart_admin_products_setup', array( $this, 'load_inventory_options' ) );
			add_action( 'wp_easycart_admin_settings_product_inventory_end', array( $this, 'add_inventory_notification_setting' ) );
			add_action( 'admin_head', array( $this, 'add_menu_js' ) );

			add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );
			add_filter( 'wp_easycart_admin_error_messages', array( $this, 'add_failure_messages' ) );

			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_deactivate_product' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_deactivate_product' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_activate_product' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_duplicate_product' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_product' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_product' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_export_products_csv' ) );
			add_action( 'wp_easycart_admin_product_details_googlemerchant_fields', array( $this, 'google_merchant_fields' ) );
		}

		public function process_deactivate_product() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['product_id'] ) && isset( $_GET['ec_admin_form_action'] ) && 'deactivate-product' == $_GET['ec_admin_form_action'] && ! isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-deactivate-product' ) ) {
					$result = $this->deactivate_product();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'products', $result );
				}
			}
		}

		public function process_bulk_deactivate_product() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && 'deactivate-product' == $_GET['ec_admin_form_action'] && ! isset( $_GET['product_id'] ) && isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-' . ( ( isset( $_GET['subpage'] ) ) ? 'products' : '' ) ) ) {
					$result = $this->bulk_deactivate_product();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'products', $result );
				}
			}
		}

		public function process_bulk_activate_product() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && 'activate-product' == $_GET['ec_admin_form_action'] && ! isset( $_GET['product_id'] ) && isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-' . ( ( isset( $_GET['subpage'] ) ) ? 'products' : '' ) ) ) {
					$result = $this->bulk_activate_product();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'products', $result );
				}
			}
		}

		public function process_duplicate_product() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && 'duplicate-product' == $_GET['ec_admin_form_action'] && isset( $_GET['product_id'] ) && ! isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-duplicate-product' ) ) {
					$result = $this->duplicate_product();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'products', $result );
				}
			}
		}

		public function process_delete_product() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && 'delete-product' == $_GET['ec_admin_form_action'] && isset( $_GET['product_id'] ) && ! isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-product' ) ) {
					$result = $this->delete_product();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'products', $result );
				}
			}
		}

		public function process_bulk_delete_product() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && 'delete-product' == $_GET['ec_admin_form_action'] && ! isset( $_GET['product_id'] ) && isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-' . ( ( isset( $_GET['subpage'] ) ) ? 'products' : '' ) ) ) {
					$result = $this->bulk_delete_product();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'products', $result );
				}
			}
		}

		public function process_export_products_csv() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && ( 'export-products-csv' == $_GET['ec_admin_form_action'] || 'export-all-products-csv' == $_GET['ec_admin_form_action'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-' . ( ( isset( $_GET['subpage'] ) ) ? 'products' : '' ) ) ) {
					include( $this->export_products_csv );
					die();
				}
			}
		}

		public function add_success_messages( $messages ) {
			if ( isset( $_GET['success'] ) && 'product-inserted' == $_GET['success'] ) {
				$messages[] = __( 'Product successfully created', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'product-updated' == $_GET['success'] ) {
				$messages[] = __( 'Product successfully updated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'product-deleted' == $_GET['success'] ) {
				$messages[] = __( 'Product successfully deleted', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'product-duplicated' == $_GET['success'] ) {
				$messages[] = __( 'Product successfully duplicated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'product-deactivated' == $_GET['success'] ) {
				$messages[] = __( 'Products successfully deactivated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'product-activated' == $_GET['success'] ) {
				$messages[] = __( 'Products successfully activated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'product-activate-single' == $_GET['success'] ) {
				$messages[] = __( 'Product successfully activated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'product-deactivate-single' == $_GET['success'] ) {
				$messages[] = __( 'Product successfully deactivated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'option-items-imported' == $_GET['success'] ) {
				$messages[] = __( 'Product quantities successfully imported', 'wp-easycart' );
			}
			return $messages;
		}

		public function add_failure_messages( $messages ) {
			if ( isset( $_GET['error'] ) && 'product-inserted-error' == $_GET['error'] ) {
				$messages[] = __( 'Product failed to create', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && 'product-updated-error' == $_GET['error'] ) {
				$messages[] = __( 'Product failed to update', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && 'product-deleted-error' == $_GET['error'] ) {
				$messages[] = __( 'Product failed to delete', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && 'product-duplicated-error' == $_GET['error'] ) {
				$messages[] = __( 'Product failed to duplicate', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && 'product-deactivated-error' == $_GET['error'] ) {
				$messages[] = __( 'Product failed to deactivate', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && 'product-activated-error' == $_GET['error'] ) {
				$messages[] = __( 'Product failed to activate', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && 'product-duplicate' == $_GET['error'] ) {
				$messages[] = __( 'Product failed to create due to duplicate', 'wp-easycart' );
			}
			return $messages;
		}

		public function load_products_setup() {
			include( $this->products_setup_file );
		}

		public function load_product_list_setup() {
			include( $this->product_list_setup_file );
		}

		public function load_product_store_defaults() {
			include( $this->product_store_defaults_file );
		}

		public function load_product_details_setup() {
			include( $this->product_details_setup_file );
		}
		public function load_product_settings() {
			include( $this->product_settings_file );
		}

		public function load_customer_review_setup() {
			include( $this->customer_review_setup_file );
		}

		public function load_price_display_options() {
			include( $this->price_display_options_file );
		}

		public function load_inventory_options() {
			include( $this->inventory_options_file );
		}

		public function add_inventory_notification_setting() {
			echo '<div style="margin-top:5px;"><input type="checkbox" name="ec_option_enable_inventory_notification" id="ec_option_enable_inventory_notification" value="0" onclick="show_pro_required();" readonly="readonly" /><span class="dashicons dashicons-lock" style="color:#FC0; margin-top:10px;"></span> ' . esc_attr__( 'Allow Customers to Subscribe to Stock Notifications', 'wp-easycart' ) . '</div>';
		}

		public function load_products_list() {
			if ( isset( $_GET['product_id'] ) && isset( $_GET['ec_admin_form_action'] ) && 'edit' == $_GET['ec_admin_form_action'] ) {
				include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_details_products.php' );
				$details = new wp_easycart_admin_details_products();
				$details->output( 'edit' );
			} else if ( isset( $_GET['ec_admin_form_action'] ) && 'add-new' == $_GET['ec_admin_form_action'] ) {
				include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_details_products.php' );
				$details = new wp_easycart_admin_details_products();
				$details->output( 'add-new' );
			} else {
				include( $this->product_list_file );
			}
		}

		public function deactivate_product() {
			global $wpdb;

			$product_id = (int) $_GET['product_id'];
			$product = $wpdb->get_row( $wpdb->prepare( 'SELECT post_id, model_number, title, activate_in_store FROM ec_product WHERE product_id = %d', $product_id ) );
			$active_status = 1;
			$status = 'publish';
			if ( $product->activate_in_store == 1 ) { 
				$active_status = 0;
				$status = 'private';
			}

			/* Manually Update Post */
			$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'posts SET post_status = %s WHERE ID = %d', $status, $product->post_id ) );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET activate_in_store = %d WHERE product_id = %d', $active_status, $product_id ) );
			do_action( 'wpeasycart_product_deactivated', $product_id );

			if ( $active_status ) {
				$args = array( 'success' => 'product-activate-single' );
			} else {
				$args = array( 'success' => 'product-deactivate-single' );
			}
			if ( isset( $_GET['pagenum'] ) ) {
				$args['pagenum'] = (int) $_GET['pagenum'];
			}
			$valid_orderby = array( 'title', 'stock_quantity', 'price', 'model_number', 'is_visible', 'product_id' );
			if ( isset( $_GET['orderby'] ) && in_array( $_GET['orderby'], $valid_orderby ) ) {
				$args['orderby'] = sanitize_text_field( $_GET['orderby'] );
			}
			if ( isset( $_GET['order'] ) && 'desc' == strtolower( $_GET['order'] ) ) {
				$args['order'] = 'desc';
			} else {
				$args['order'] = 'asc';
			}
			if ( isset( $_GET['s'] ) ) {
				$args['s'] = sanitize_text_field( wp_unslash( $_GET['s'] ) );
			}
			return $args;
		}

		public function bulk_deactivate_product() {
			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				$product = $wpdb->get_row( $wpdb->prepare( 'SELECT post_id, model_number, title, activate_in_store FROM ec_product WHERE product_id = %d', (int) $bulk_id ) );
				$active_status = 0;
				$status = 'private';
				$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'posts SET post_status = %s WHERE ID = %d', $status, $product->post_id ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET activate_in_store = %d WHERE product_id = %d', $active_status, (int) $bulk_id ) );
				do_action( 'wpeasycart_product_deactivated', (int) $bulk_id );
			}

			$args = array( 'success' => 'product-deactivated' );

			if ( isset( $_GET['pagenum'] ) ) {
				$args['pagenum'] = (int) $_GET['pagenum'];
			}
			$valid_orderby = array( 'title', 'stock_quantity', 'price', 'model_number', 'is_visible', 'product_id' );
			if ( isset( $_GET['orderby'] ) && in_array( $_GET['orderby'], $valid_orderby ) ) {
				$args['orderby'] = sanitize_text_field( $_GET['orderby'] );
			}
			if ( isset( $_GET['order'] ) && 'desc' == strtolower( $_GET['order'] ) ) {
				$args['order'] = 'desc';
			} else {
				$args['order'] = 'asc';
			}
			if ( isset( $_GET['s'] ) ) {
				$args['s'] = sanitize_text_field( wp_unslash( $_GET['s'] ) );
			}
			return $args;
		}

		public function bulk_activate_product() {
			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				$product = $wpdb->get_row( $wpdb->prepare( 'SELECT post_id, model_number, title, activate_in_store FROM ec_product WHERE product_id = %d', (int) $bulk_id ) );
				$active_status = 1;
				$status = 'publish';
				$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'posts SET post_status = %s WHERE ID = %d', $status, $product->post_id ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET activate_in_store = %d WHERE product_id = %d', $active_status, (int) $bulk_id ) );
				do_action( 'wpeasycart_product_activated', (int) $bulk_id );
			}

			$args = array( 'success' => 'product-activated' );

			if ( isset( $_GET['pagenum'] ) ) {
				$args['pagenum'] = (int) $_GET['pagenum'];
			}
			$valid_orderby = array( 'title', 'stock_quantity', 'price', 'model_number', 'is_visible', 'product_id' );
			if ( isset( $_GET['orderby'] ) && in_array( $_GET['orderby'], $valid_orderby ) ) {
				$args['orderby'] = sanitize_text_field( $_GET['orderby'] );
			}
			if ( isset( $_GET['order'] ) && 'desc' == strtolower( $_GET['order'] ) ) {
				$args['order'] = 'desc';
			} else {
				$args['order'] = 'asc';
			}
			if ( isset( $_GET['s'] ) ) {
				$args['s'] = sanitize_text_field( wp_unslash( $_GET['s'] ) );
			}
			return $args;
		}

		public function duplicate_product() {
			global $wpdb;

			$product_id = (int) $_GET['product_id'];
			$product = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_product.* FROM ec_product WHERE product_id = %d', $product_id ) );
			$original_record = $product;
			$randmodel = rand(1000000, 10000000);

			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_product( model_number ) VALUES( %s )', $randmodel ) );
			$newid = $wpdb->insert_id;

			$sql = 'UPDATE ec_product SET ';
			foreach ( $original_record as $key => $value ) {

				if ( $key != 'product_id' && $key != 'model_number' && $key != 'square_id' ) {
					if ( $key == 'added_to_db_date' ) {
						$sql .= '`'.$key.'` = NOW(), ';
					} else if ( $key == 'views' ) {
						$sql .= '`'.$key.'` = "0", ';
					} else if ( $key == 'subscription_unique_id' ) {
						$sql .= '`'.$key.'` = "0", ';
					} else {
						$sql .= '`'.$key.'` = ' . $wpdb->prepare( '%s', $value ) .', ';
					}
				}

			}

			$sql = substr( $sql, 0, strlen( $sql ) - 2 );
			$sql .= ' WHERE product_id = ' . $newid;
			$duplicate_result = $wpdb->query( $sql );

			$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitemimage WHERE product_id = %d', $product_id ) );
			foreach ( $results as $row ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_optionitemimage( optionitem_id, image1, image2, image3, image4, image5, product_images, product_id ) VALUES( %s, %s, %s, %s, %s, %s, %s, %d )', $row->optionitem_id, $row->image1, $row->image2, $row->image3, $row->image4, $row->image5, $row->product_images, $newid ) );
			}

			$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_pricetier WHERE product_id = %d', $product_id ) );
			foreach ( $results as $row ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_pricetier( product_id, price, quantity) VALUES( %d, %s, %s )', $newid, $row->price, $row->quantity ) );
			}

			$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_categoryitem WHERE product_id = %d', $product_id ) );
			foreach ( $results as $row ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_categoryitem( product_id, category_id ) VALUES( %d, %d )', $newid, $row->category_id ) ); 
			}

			$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_roleprice WHERE product_id = %d', $product_id));
			foreach ( $results as $row ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_roleprice( product_id, role_label, role_price ) VALUES( %d, %s, %s )', $newid, $row->role_label, $row->role_price ) ); 
			}

			$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitemquantity WHERE product_id = %d', $product_id ) );
			foreach ( $results as $row ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_optionitemquantity( optionitem_id_1, optionitem_id_2, optionitem_id_3, optionitem_id_4, optionitem_id_5, quantity, product_id ) VALUES( %d, %d, %d, %d, %d, %s, %d )', $row->optionitem_id_1, $row->optionitem_id_2, $row->optionitem_id_3, $row->optionitem_id_4, $row->optionitem_id_5, $row->quantity, $newid ) );
			}

			$replace_ids = array();
			$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_option_to_product WHERE product_id = %d ORDER BY option_to_product_id ASC', $product_id ) );
			foreach ( $results as $row ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_option_to_product( option_id, product_id, role_label, option_order, conditional_logic ) VALUES( %d, %d, %s, %d, %s )', $row->option_id, $newid, $row->role_label, $row->option_order, $row->conditional_logic ) );
				$replace_ids[ $row->option_to_product_id ] = $wpdb->insert_id;
				do_action( 'wp_easycart_option_to_product_created', (int) $row->option_id, (int) $newid );
			}
			$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_option_to_product WHERE product_id = %d ORDER BY option_to_product_id ASC', $newid ) );
			foreach ( $results as $row ) {
				if ( $row->conditional_logic ) {
					$meta = json_decode( stripslashes( $row->conditional_logic ) );
					for ( $i = 0; $i < count( $meta->rules ); $i++ ) {
						$meta->rules[ $i ]->option_id = $replace_ids[ $meta->rules[ $i ]->option_id ];
					}
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_option_to_product SET conditional_logic = %s WHERE option_to_product_id = %d', json_encode( $meta ), $row->option_to_product_id ) );
				}
			}

			if ( file_exists( '../../../../wp-easycart-quickbooks/QuickBooks.php' ) ) {
				$quickbooks = new ec_quickbooks();
				$quickbooks->add_product( $randmodel );	
			}

			$status = ( $product->activate_in_store ) ? 'publish' : 'private';
			$post_slug = preg_replace( '/(\-+)/', '-', preg_replace( '/[^A-Za-z0-9\-]/', '', str_replace( ' ', '-', wp_unslash( strtolower( $product->title ) ) ) ) );
			while ( substr( $post_slug, -1 ) == '-' ) {
				$post_slug = substr( $post_slug, 0, strlen( $post_slug ) - 1 );
			}
			while ( substr( $post_slug, 0, 1 ) == '-' ) {
				$post_slug = substr( $post_slug, 1, strlen( $post_slug ) );
			}
			if ( $post_slug == '' ) {
				$post_slug = rand( 1000000, 9999999 );
			}
			$store_page = get_permalink( get_option( 'ec_option_storepage' ) );
			if ( strstr( $store_page, '?' ) ) {
				$guid = $store_page . '&model_number=' . $randmodel;
			} else if ( substr( $store_page, strlen( $store_page ) - 1 ) == '/' ) {
				$guid = $store_page . $post_slug;
			} else {
				$guid = $store_page . '/' . $post_slug;
			}
			$guid = strtolower( $guid );
			$post_slug_orig = $post_slug;
			$guid_orig = $guid;
			$guid = $guid . '/';
			$i=1;
			while ( $guid_check = $wpdb->get_row( $wpdb->prepare( 'SELECT ' . $wpdb->prefix . 'posts.guid FROM ' . $wpdb->prefix . 'posts WHERE ' . $wpdb->prefix . 'posts.guid = %s', $guid ) ) ) {
				$guid = $guid_orig . '-' . $i . '/';
				$post_slug = $post_slug_orig . '-' . $i;
				$i++;
			} 

			$wpdb->query( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix . 'posts( post_content, post_status, post_title, post_name, guid, post_type, post_date, post_date_gmt, post_modified, post_modified_gmt, comment_status ) VALUES( %s, %s, %s, %s, %s, %s, NOW(), UTC_TIMESTAMP(), NOW(), UTC_TIMESTAMP(), "closed" )', '[ec_store modelnumber="' . $randmodel . '"]', $status, wp_easycart_language()->convert_text( wp_unslash( $product->title ) ), $post_slug, $guid, 'ec_store' ) );
			$post_id = $wpdb->insert_id;

			$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET post_id = %d WHERE product_id = %d', $post_id, $newid ) );

			if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' || get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) {
				if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' )
					$stripe = new ec_stripe();
				else if ( get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' )
					$stripe = new ec_stripe_connect();

				$product_row = $wpdb->get_row( $wpdb->prepare( 'SELECT post_id, is_subscription_item, stripe_plan_added, subscription_unique_id, product_id, price, title, subscription_bill_period, subscription_bill_length, trial_period_days FROM ec_product WHERE product_id = %d', $newid ) );
				$product_row->subscription_unique_id = rand( 10000, 10000000 );
				$result = $stripe->insert_plan( $product_row );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET subscription_unique_id = %d, stripe_plan_added = 1 WHERE product_id = %d', $product_row->subscription_unique_id, $newid ) );
			}

			$args = array( 'success' => 'product-duplicated' );

			if ( isset( $_GET['pagenum'] ) ) {
				$args['pagenum'] = (int) $_GET['pagenum'];
			}
			$valid_orderby = array( 'title', 'stock_quantity', 'price', 'model_number', 'is_visible', 'product_id' );
			if ( isset( $_GET['orderby'] ) && in_array( $_GET['orderby'], $valid_orderby ) ) {
				$args['orderby'] = sanitize_text_field( $_GET['orderby'] );
			}
			if ( isset( $_GET['order'] ) && 'desc' == strtolower( $_GET['order'] ) ) {
				$args['order'] = 'desc';
			} else {
				$args['order'] = 'asc';
			}
			if ( isset( $_GET['s'] ) ) {
				$args['s'] = sanitize_text_field( wp_unslash( $_GET['s'] ) );
			}
			return $args;
		}

		public function delete_product() {
			global $wpdb;

			$product_id = (int) $_GET['product_id'];		
			$post_id = $wpdb->get_var( $wpdb->prepare( 'SELECT post_id FROM ec_product WHERE product_id = %d', $product_id ) );
			do_action( 'wpeasycart_product_deleting', $product_id );

			wp_delete_post( $post_id, true );
			if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
				$stripe_plan = ( object ) array( 'product_id' => $product_id );
				$stripe = new ec_stripe;
				$response = $stripe->delete_plan( $stripe_plan );
			} else if ( get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) {
				$stripe_plan = ( object ) array( 'product_id' => $product_id );
				$stripe = new ec_stripe_connect;
				$response = $stripe->delete_plan( $stripe_plan );
			}

			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_product WHERE product_id = %d', $product_id ) );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_optionitemimage WHERE product_id = %d', $product_id ) );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_pricetier WHERE product_id = %d', $product_id ) );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_roleprice WHERE product_id = %d', $product_id ) );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_optionitemquantity WHERE product_id = %d', $product_id ) );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_option_to_product WHERE product_id = %d', $product_id ) );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_review WHERE product_id = %d', $product_id ) );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_affiliate_rule_to_product WHERE product_id = %d', $product_id ) );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_categoryitem WHERE product_id = %d', $product_id ) );
			do_action( 'wpeasycart_product_deleted', $product_id );

			$args = array( 'success' => 'product-deleted' );

			if ( isset( $_GET['pagenum'] ) ) {
				$args['pagenum'] = (int) $_GET['pagenum'];
			}
			$valid_orderby = array( 'title', 'stock_quantity', 'price', 'model_number', 'is_visible', 'product_id' );
			if ( isset( $_GET['orderby'] ) && in_array( $_GET['orderby'], $valid_orderby ) ) {
				$args['orderby'] = sanitize_text_field( $_GET['orderby'] );
			}
			if ( isset( $_GET['order'] ) && 'desc' == strtolower( $_GET['order'] ) ) {
				$args['order'] = 'desc';
			} else {
				$args['order'] = 'asc';
			}
			if ( isset( $_GET['s'] ) ) {
				$args['s'] = sanitize_text_field( wp_unslash( $_GET['s'] ) );
			}
			return $args;
		}

		public function bulk_delete_product() {
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.
			$query_vars = array();

			global $wpdb;
			$errors = 0;
			foreach ( $bulk_ids as $bulk_id ) {
				$bulk_id = (int) $bulk_id;
				$post_id = $wpdb->get_var( $wpdb->prepare( 'SELECT post_id FROM ec_product WHERE product_id = %d', $bulk_id ) );
				do_action( 'wpeasycart_product_deleting', $bulk_id );

				wp_delete_post( $post_id, true );
				if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
					$stripe_plan = ( object ) array( 'product_id' => $bulk_id );
					$stripe = new ec_stripe;
					$response = $stripe->delete_plan( $stripe_plan );
				} else if ( get_option( 'ec_option_payment_process_method' ) == 'stripe_conect' ) {
					$stripe_plan = ( object ) array( 'product_id' => $bulk_id );
					$stripe = new ec_stripe_conect;
					$response = $stripe->delete_plan( $stripe_plan );
				}

				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_product WHERE product_id = %d', $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_optionitemimage WHERE product_id = %d', $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_pricetier WHERE product_id = %d', $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_roleprice WHERE product_id = %d', $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_optionitemquantity WHERE product_id = %d', $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_option_to_product WHERE product_id = %d', $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_review WHERE product_id = %d', $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_affiliate_rule_to_product WHERE product_id = %d', $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_categoryitem WHERE product_id = %d', $bulk_id ) );
				do_action( 'wpeasycart_product_deleted', $bulk_id );
			}

			$args = array( 'success' => 'product-deleted' );

			if ( isset( $_GET['pagenum'] ) ) {
				$args['pagenum'] = (int) $_GET['pagenum'];
			}
			$valid_orderby = array( 'title', 'stock_quantity', 'price', 'model_number', 'is_visible', 'product_id' );
			if ( isset( $_GET['orderby'] ) && in_array( $_GET['orderby'], $valid_orderby ) ) {
				$args['orderby'] = sanitize_text_field( $_GET['orderby'] );
			}
			if ( isset( $_GET['order'] ) && 'desc' == strtolower( $_GET['order'] ) ) {
				$args['order'] = 'desc';
			} else {
				$args['order'] = 'asc';
			}
			if ( isset( $_GET['s'] ) ) {
				$args['s'] = sanitize_text_field( wp_unslash( $_GET['s'] ) );
			}
			return $args;
		}
		
		public function save_product_settings_v2() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-products' ) ) {
				return false;
			}

			$options = array(
				'ec_option_customer_review_require_login', 'ec_option_customer_review_show_user_name', 'ec_option_customer_review_notification',
				'ec_option_stock_removed_in_cart',
				'ec_option_hide_price_seasonal', 'ec_option_hide_price_inquiry', 'ec_option_show_multiple_vat_pricing', 'ec_option_tiered_price_format', 'ec_option_tiered_price_by_option',
				'ec_option_show_breadcrumbs', 'ec_option_show_magnification', 'ec_option_show_large_popup', 'ec_option_show_model_number', 'ec_option_short_description_below', 'ec_option_show_categories',
				'ec_option_show_manufacturer', 'ec_option_show_stock_quantity', 'ec_option_use_facebook_icon', 'ec_option_use_twitter_icon', 'ec_option_use_delicious_icon',
				'ec_option_use_myspace_icon', 'ec_option_use_linkedin_icon', 'ec_option_use_email_icon', 'ec_option_use_digg_icon', 'ec_option_use_googleplus_icon', 'ec_option_use_pinterest_icon',
				'ec_option_show_sort_box', 'ec_option_product_filter_0', 'ec_option_product_filter_1', 'ec_option_product_filter_2', 'ec_option_product_filter_3',
				'ec_option_product_filter_4', 'ec_option_product_filter_5', 'ec_option_product_filter_6', 'ec_option_product_filter_7', 'ec_option_product_filter_8', 'ec_option_short_description_on_product',
				'ec_option_show_featured_categories', 'ec_option_enable_product_paging', 'ec_option_hide_out_of_stock',
				'ec_option_display_as_catalog', 'ec_option_subscription_one_only',
				'ec_option_enable_product_paging', 'ec_option_show_store_sidebar', 'ec_option_store_sidebar_filter_clear', 'ec_option_store_sidebar_include_search', 'ec_option_store_sidebar_include_categories',
				'ec_option_sidebar_include_categories_first', 'ec_option_sidebar_include_option_filters', 'ec_option_enable_inventory_notification', 'ec_option_product_add_to_cart_enable_quantity', 'ec_option_sidebar_include_category_filters', 'ec_option_product_no_checkout_button', 'ec_option_redirect_add_to_cart',
			);
			$options_text = array(
				'ec_option_tempcart_stock_hours', 'ec_option_tempcart_stock_timeframe',
				'ec_option_model_number_extension', 'ec_option_product_details_sizing',
				'ec_option_default_store_filter',
				'ec_option_product_image_default',
				'ec_option_enable_product_paging_per_page', 'ec_option_store_sidebar_position',
				'ec_option_sidebar_category_filter_id', 'ec_option_sidebar_category_filter_method', 'ec_option_sidebar_category_filter_open',
			);

			if ( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $options ) ) {
				$val = ( isset( $_POST['val'] ) && $_POST['val'] == '1' ) ? 1 : 0;
				update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), $val );
			} else if ( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $options_text ) ) {
				update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), sanitize_text_field( wp_unslash( $_POST['val'] ) ) );
			} else if ( isset( $_POST['update_var'] ) && $_POST['update_var'] == 'ec_option_restrict_store' ) {
				$val = ( isset( $_POST['val'] ) && is_array( $_POST['val'] ) ) ? implode( '***', $_POST['val'] ) : '';
				update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), $val );
			} else if ( isset( $_POST['update_var'] ) && $_POST['update_var'] == 'ec_option_store_sidebar_categories' ) {
				$val = ( isset( $_POST['val'] ) && is_array( $_POST['val'] ) ) ? implode( ',', $_POST['val'] ) : '';
				update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), $val );
			} else if ( isset( $_POST['update_var'] ) && $_POST['update_var'] == 'ec_option_store_sidebar_option_filters' ) {
				$val = ( isset( $_POST['val'] ) && is_array( $_POST['val'] ) ) ? implode( ',', $_POST['val'] ) : '';
				update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), $val );
			}
		}

		public function save_product_settings() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-products' ) ) {
				return false;
			}

			$ec_option_display_as_catalog = ( isset( $_POST['ec_option_display_as_catalog'] ) && $_POST['ec_option_display_as_catalog'] == '1' ) ? 1 : 0;
			$ec_option_subscription_one_only = ( isset( $_POST['ec_option_subscription_one_only'] ) && $_POST['ec_option_subscription_one_only'] == '1' ) ? 1 : 0;
			$ec_option_restrict_store = '';
			if ( isset( $_POST['ec_option_restrict_store'] ) ) {
				$valid_roles = array();
				foreach ( (array) $_POST['ec_option_restrict_store'] as $role ) { // XSS OK. Forced array and each item sanitized.
					if ( wp_easycart_admin_verification()->valid_user_role( sanitize_text_field( wp_unslash( $role ) ) ) ) {
						$valid_roles[] = sanitize_text_field( wp_unslash( $role ) );
					}
				}
				$ec_option_restrict_store = implode( '***', $valid_roles );
			}
			$ec_option_product_image_default = esc_url_raw( $_POST['ec_option_product_image_default'] );

			update_option( 'ec_option_display_as_catalog', $ec_option_display_as_catalog );
			update_option( 'ec_option_subscription_one_only', $ec_option_subscription_one_only );
			update_option( 'ec_option_restrict_store', $ec_option_restrict_store );
			update_option( 'ec_option_product_image_default', $ec_option_product_image_default );
		}

		public function save_product_list_display() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-products' ) ) {
				return false;
			}

			$ec_option_show_sort_box = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_show_sort_box' );
			$ec_option_default_store_filter = wp_easycart_admin_verification()->filter_int( sanitize_text_field( wp_unslash( $_POST['ec_option_default_store_filter'] ) ) );
			$ec_option_product_filter_0 = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_product_filter_0' );
			$ec_option_product_filter_1 = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_product_filter_1' );
			$ec_option_product_filter_2 = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_product_filter_2' );
			$ec_option_product_filter_3 = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_product_filter_3' );
			$ec_option_product_filter_4 = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_product_filter_4' );	
			$ec_option_product_filter_5 = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_product_filter_5' );	
			$ec_option_product_filter_6 = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_product_filter_6' );
			$ec_option_product_filter_7 = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_product_filter_7' );
			$ec_option_short_description_on_product = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_short_description_on_product' );
			$ec_option_show_featured_categories = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_show_featured_categories' );
			$ec_option_enable_product_paging = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_enable_product_paging' );
			$ec_option_hide_out_of_stock = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_hide_out_of_stock' );

			update_option( 'ec_option_show_sort_box', $ec_option_show_sort_box );
			update_option( 'ec_option_default_store_filter', $ec_option_default_store_filter );
			update_option( 'ec_option_product_filter_0', $ec_option_product_filter_0 );
			update_option( 'ec_option_product_filter_1', $ec_option_product_filter_1 );
			update_option( 'ec_option_product_filter_2', $ec_option_product_filter_2 );
			update_option( 'ec_option_product_filter_3', $ec_option_product_filter_3 );
			update_option( 'ec_option_product_filter_4', $ec_option_product_filter_4 );
			update_option( 'ec_option_product_filter_5', $ec_option_product_filter_5 );
			update_option( 'ec_option_product_filter_6', $ec_option_product_filter_6 );
			update_option( 'ec_option_product_filter_7', $ec_option_product_filter_7 );
			update_option( 'ec_option_short_description_on_product', $ec_option_short_description_on_product );
			update_option( 'ec_option_show_featured_categories', $ec_option_show_featured_categories );
			update_option( 'ec_option_enable_product_paging', $ec_option_enable_product_paging );
			update_option( 'ec_option_hide_out_of_stock', $ec_option_hide_out_of_stock );
		}

		public function save_product_details_display() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-products' ) ) {
				return false;
			}

			$ec_option_model_number_extension = wp_easycart_admin_verification()->filter_length( sanitize_text_field( wp_unslash( $_POST['ec_option_model_number_extension'] ) ), 1 );
			$ec_option_product_details_sizing = (int) sanitize_text_field( wp_unslash( $_POST['ec_option_product_details_sizing'] ) );
			$ec_option_show_breadcrumbs = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_show_breadcrumbs' );
			$ec_option_show_magnification = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_show_magnification' );
			$ec_option_show_large_popup = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_show_large_popup' );
			$ec_option_show_model_number = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_show_model_number' );
			$ec_option_show_categories = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_show_categories' );
			$ec_option_show_manufacturer = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_show_manufacturer' );
			$ec_option_show_stock_quantity = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_show_stock_quantity' );
			$ec_option_use_facebook_icon = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_use_facebook_icon' );
			$ec_option_use_twitter_icon = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_use_twitter_icon' );
			$ec_option_use_delicious_icon = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_use_delicious_icon' );
			$ec_option_use_myspace_icon = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_use_myspace_icon' );
			$ec_option_use_linkedin_icon = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_use_linkedin_icon' );
			$ec_option_use_email_icon = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_use_email_icon' );
			$ec_option_use_digg_icon = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_use_digg_icon' );
			$ec_option_use_googleplus_icon = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_use_googleplus_icon' );
			$ec_option_use_pinterest_icon = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_use_pinterest_icon' );

			update_option( 'ec_option_model_number_extension', $ec_option_model_number_extension );
			update_option( 'ec_option_product_details_sizing', $ec_option_product_details_sizing );
			update_option( 'ec_option_show_breadcrumbs', $ec_option_show_breadcrumbs );
			update_option( 'ec_option_show_magnification', $ec_option_show_magnification );
			update_option( 'ec_option_show_large_popup', $ec_option_show_large_popup );
			update_option( 'ec_option_show_model_number', $ec_option_show_model_number );
			update_option( 'ec_option_show_categories', $ec_option_show_categories );
			update_option( 'ec_option_show_manufacturer', $ec_option_show_manufacturer );
			update_option( 'ec_option_show_stock_quantity', $ec_option_show_stock_quantity );

			update_option( 'ec_option_use_facebook_icon', $ec_option_use_facebook_icon );
			update_option( 'ec_option_use_twitter_icon', $ec_option_use_twitter_icon );
			update_option( 'ec_option_use_delicious_icon', $ec_option_use_delicious_icon );
			update_option( 'ec_option_use_myspace_icon', $ec_option_use_myspace_icon );
			update_option( 'ec_option_use_linkedin_icon', $ec_option_use_linkedin_icon );
			update_option( 'ec_option_use_email_icon', $ec_option_use_email_icon );
			update_option( 'ec_option_use_digg_icon', $ec_option_use_digg_icon );
			update_option( 'ec_option_use_googleplus_icon', $ec_option_use_googleplus_icon );
			update_option( 'ec_option_use_pinterest_icon', $ec_option_use_pinterest_icon );
		}

		public function save_customer_review_display() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-products' ) ) {
				return false;
			}

			$ec_option_customer_review_require_login = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_customer_review_require_login' );
			$ec_option_customer_review_show_user_name = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_customer_review_show_user_name' );
			$ec_option_customer_review_notification = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_customer_review_notification' );

			update_option( 'ec_option_customer_review_require_login', $ec_option_customer_review_require_login );
			update_option( 'ec_option_customer_review_show_user_name', $ec_option_customer_review_show_user_name );
			update_option( 'ec_option_customer_review_notification', $ec_option_customer_review_notification );
		}

		public function save_price_display() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-products' ) ) {
				return false;
			}

			$ec_option_hide_price_seasonal = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_hide_price_seasonal' );
			$ec_option_hide_price_inquiry = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_hide_price_inquiry' );
			$ec_option_show_multiple_vat_pricing = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_show_multiple_vat_pricing' );
			$ec_option_tiered_price_format = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_tiered_price_format' );
			$ec_option_tiered_price_by_option = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_tiered_price_by_option' );

			update_option( 'ec_option_hide_price_seasonal', $ec_option_hide_price_seasonal );
			update_option( 'ec_option_hide_price_inquiry', $ec_option_hide_price_inquiry );
			update_option( 'ec_option_show_multiple_vat_pricing', $ec_option_show_multiple_vat_pricing );
			update_option( 'ec_option_tiered_price_format', $ec_option_tiered_price_format );
			update_option( 'ec_option_tiered_price_by_option', $ec_option_tiered_price_by_option );
		}

		public function save_inventory_options() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-products' ) ) {
				return false;
			}

			$ec_option_stock_removed_in_cart = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_stock_removed_in_cart' );
			$ec_option_tempcart_stock_hours = ( ( round( (int) $_POST['ec_option_tempcart_stock_hours'] ) <= 0 ) ? 1 : round( (int) $_POST['ec_option_tempcart_stock_hours'] ) );
			$ec_option_tempcart_stock_timeframe = wp_easycart_admin_verification()->filter_list( sanitize_text_field( wp_unslash( $_POST['ec_option_tempcart_stock_timeframe'] ) ), array( 'SECOND', 'MINUTE', 'HOUR' ) );
			$ec_option_enable_inventory_notification = wp_easycart_admin_verification()->filter_checkbox( 'ec_option_enable_inventory_notification' );

			update_option( 'ec_option_stock_removed_in_cart', $ec_option_stock_removed_in_cart );
			update_option( 'ec_option_tempcart_stock_hours', $ec_option_tempcart_stock_hours );
			update_option( 'ec_option_tempcart_stock_timeframe', $ec_option_tempcart_stock_timeframe );
			update_option( 'ec_option_enable_inventory_notification', $ec_option_enable_inventory_notification );
		}

		public function add_menu_js() {
			global $wpdb;
			$menus = $wpdb->get_results( 'SELECT ec_menulevel1.menulevel1_id as id, ec_menulevel1.name as text FROM ec_menulevel1 ORDER BY ec_menulevel1.name ASC' );
			$submenus = $wpdb->get_results( 'SELECT ec_menulevel2.menulevel2_id as id, ec_menulevel2.menulevel1_id AS parent_id, ec_menulevel2.name as text FROM ec_menulevel2 ORDER BY ec_menulevel2.menulevel1_id ASC, ec_menulevel2.name ASC' );
			$subsubmenus = $wpdb->get_results( 'SELECT ec_menulevel3.menulevel3_id as id, ec_menulevel3.menulevel2_id AS parent_id, ec_menulevel3.name as text FROM ec_menulevel3 ORDER BY ec_menulevel3.menulevel2_id ASC, ec_menulevel3.name ASC' );

			echo '<script>';
			echo 'var menulevel1 = [';
			for ( $i = 0; $i < count( $menus ); $i++ ) {
				if ( $i != 0 )
					echo ', ';
				echo '{ id:' . esc_attr( $menus[ $i ]->id ) . ", text:'" . esc_attr( str_replace( "'", "\'", $menus[ $i ]->text ) ) . "' }";
			}
			echo '];';

			echo 'var menulevel2 = [';
			for ( $i = 0; $i < count( $submenus ); $i++ ) {
				if ( $i != 0 )
					echo ', ';
				echo '{ id:' . esc_attr( $submenus[ $i ]->id ) . ", text:'" . esc_attr( str_replace( "'", "\'", $submenus[ $i ]->text ) ) . "', parent_id:" . esc_attr( $submenus[ $i ]->parent_id ) . ' }';
			}
			echo '];';

			echo 'var menulevel3 = [';
			for ( $i = 0; $i < count( $subsubmenus ); $i++ ) {
				if ( $i != 0 )
					echo ', ';
				echo '{ id:' . esc_attr( $subsubmenus[ $i ]->id ) . ", text:'" . esc_attr( str_replace( "'", "\'", $subsubmenus[ $i ]->text ) ) . "', parent_id:" . esc_attr( $subsubmenus[ $i ]->parent_id ) . ' }';
			}
			echo '];';
			echo "jQuery( document ).ready( function() {
				/* Load Select2 Menu CBs (if applicable) */
				if ( jQuery( document.getElementById( 'menulevel1_id_1' ) ).length ) {
					if ( jQuery( document.getElementById( 'menulevel1_id_1' ) ).val() == '0' ) { // Clear 2 & 3
						jQuery( '#ec_admin_row_menulevel1_id_2 select option' ).remove();
						jQuery( '#ec_admin_row_menulevel1_id_3 select option' ).remove();

					} else { // Update Menu 2 Options
						for ( i = 0; i < menulevel2.length; i++ ) {
							if ( menulevel2[i].parent_id != jQuery( document.getElementById( 'menulevel1_id_1' ) ).val() ) {
								jQuery( '#ec_admin_row_menulevel1_id_2 select option[value=\"' + menulevel2[i].id + '\"]' ).remove();
							}
						}
					}

					if ( jQuery( document.getElementById( 'menulevel1_id_2' ) ).val() == '0' ) { // Clear 3
						jQuery( '#ec_admin_row_menulevel1_id_3 select option' ).remove();

					} else { // Update Menu 3 Options
						for ( i = 0; i < menulevel3.length; i++ ) {
							if ( menulevel3[i].parent_id != jQuery( document.getElementById( 'menulevel1_id_2' ) ).val() ) {
								jQuery( '#ec_admin_row_menulevel1_id_3 select option[value=\"' + menulevel3[i].id + '\"]' ).remove();
							}
						}
					}

					if ( jQuery( document.getElementById( 'menulevel2_id_1' ) ).val() == '0' ) { // Clear 2 & 3
						jQuery( '#ec_admin_row_menulevel2_id_2 select option' ).remove();
						jQuery( '#ec_admin_row_menulevel2_id_3 select option' ).remove();

					} else { // Update Menu 2 Options
						for ( i = 0; i < menulevel2.length; i++ ) {
							if ( menulevel2[i].parent_id != jQuery( document.getElementById( 'menulevel2_id_1' ) ).val() ) {
								jQuery( '#ec_admin_row_menulevel2_id_2 select option[value=\"' + menulevel2[i].id + '\"]' ).remove();
							}
						}
					}

					if ( jQuery( document.getElementById( 'menulevel2_id_2' ) ).val() == '0' ) { // Clear 3
						jQuery( '#ec_admin_row_menulevel2_id_3 select option' ).remove();

					} else { // Update Menu 3 Options
						for ( i = 0; i < menulevel3.length; i++ ) {
							if ( menulevel3[i].parent_id != jQuery( document.getElementById( 'menulevel2_id_2' ) ).val() ) {
								jQuery( '#ec_admin_row_menulevel2_id_3 select option[value=\"' + menulevel3[i].id + '\"]' ).remove();
							}
						}
					}

					if ( jQuery( document.getElementById( 'menulevel3_id_1' ) ).val() == '0' ) { // Clear 2 & 3
						jQuery( '#ec_admin_row_menulevel3_id_2 select option' ).remove();
						jQuery( '#ec_admin_row_menulevel3_id_3 select option' ).remove();

					} else { // Update Menu 2 Options
						for ( i = 0; i < menulevel2.length; i++ ) {
							if ( menulevel2[i].parent_id != jQuery( document.getElementById( 'menulevel3_id_1' ) ).val() ) {
								jQuery( '#ec_admin_row_menulevel3_id_2 select option[value=\"' + menulevel2[i].id + '\"]' ).remove();
							}
						}
					}

					if ( jQuery( document.getElementById( 'menulevel3_id_2' ) ).val() == '0' ) { // Clear 3
						jQuery( '#ec_admin_row_menulevel3_id_3 select option' ).remove();

					} else { // Update Menu 3 Options
						for ( i = 0; i < menulevel3.length; i++ ) {
							if ( menulevel3[i].parent_id != jQuery( document.getElementById( 'menulevel3_id_2' ) ).val() ) {
								jQuery( '#ec_admin_row_menulevel3_id_3 select option[value=\"' + menulevel3[i].id + '\"]' ).remove();
							}
						}
					}

					// ALSO ADD ON CHANGE EVENTS TO MENU LEVEL 1 & 2 AND UPDATE CBS ON CHANGE!
					/* Update Open Panel Based on Hash */
					var hash = jQuery.trim( window.location.hash ).substr( 1, jQuery.trim( window.location.hash ).length ).replace( '-', '_' );
					if ( hash.length > 0 && jQuery( document.getElementById( 'ec_admin_product_details_options_section' ) ) )
						jQuery( document.getElementById( 'ec_admin_product_details_' + hash + '_section' ) ).show();
				}
			} );";
			echo '</script>';
		}

		public function save_new_quick_product() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$activate_in_store = (int) $_POST['ec_new_product_status'];

				// Product Type
				$product_type = (int) $_POST['ec_new_product_type'];
				$is_download = $is_donation = $is_invoice = $is_subscription = $is_giftcard = $is_deconetwork = $is_inquiry = $is_seasonal = 0;
				if ( 1 == $product_type || 2 == $product_type ) {
					$is_download = 1;
				} else if ( 3 == $product_type || 4 == $product_type ) {
					$is_donation = 1;
				} else if ( 5 == $product_type || 6 == $product_type ) {
					$is_subscription = 1;
				} else if ( 7 == $product_type ) {
					$is_giftcard = 1;
				} else if ( 8 == $product_type ) {
					$is_deconetwork = 1;
				} else if ( 9 == $product_type ) {
					$is_inquiry = 1;
				} else if ( 10 == $product_type ) {
					$is_seasonal = 1;
				}
				$post_status = ( $activate_in_store ) ? 'publish' : 'private';
				$show_on_startup = (int) $_POST['ec_new_product_featured'];
				$title = wp_easycart_escape_html( wp_unslash( $_POST['ec_new_product_title'] ) ); // Xss OK
				$post_slug = preg_replace( '/(\-+)/', '-', preg_replace( '/[^A-Za-z0-9\-]/', '', str_replace( ' ', '-', wp_unslash( strtolower( $title ) ) ) ) );
				while ( substr( $post_slug, -1 ) == '-' ) {
					$post_slug = substr( $post_slug, 0, strlen( $post_slug ) - 1 );
				}
				while ( substr( $post_slug, 0, 1 ) == '-' ) {
					$post_slug = substr( $post_slug, 1, strlen( $post_slug ) );
				}
				if ( $post_slug == '' ) {
					$post_slug = rand( 1000000, 9999999 );
				}
				$sku = preg_replace( '/(\-+)/', '-', preg_replace( '/[^A-Za-z0-9\-]/', '', str_replace( ' ', '-', sanitize_text_field( wp_unslash( $_POST['ec_new_product_sku'] ) ) ) ) );
				$manufacturer = sanitize_text_field( wp_unslash( $_POST['ec_new_product_manufacturer'] ) );
				$price = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_new_product_price'] ) ) );
				$image = sanitize_text_field( wp_unslash( $_POST['ec_new_product_image'] ) );
				$is_shippable = (int) $_POST['ec_new_product_is_shippable'];

				$show_stock_quantity = $stock_quantity = $use_optionitem_quantity_tracking = 0;
				if ( $_POST['ec_new_product_stock_option'] == '1' ) {
					$show_stock_quantity = 1;
					$stock_quantity = (int) $_POST['ec_new_product_stock_quantity'];
				} else if ( $_POST['ec_new_product_stock_option'] == '2' ) {
					$use_optionitem_quantity_tracking = 1;
				}

				$weight = $length = $width = $height = 0;
				if ( $is_shippable ) {
					$weight = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_new_product_weight'] ) ) );
					$length = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_new_product_length'] ) ) );
					$width = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_new_product_width'] ) ) );
					$height = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_new_product_height'] ) ) );
				}

				if ( ! get_option( 'ec_option_admin_product_show_stock_option' ) && $product_type == 0 ) {
					$is_shippable = 1;
					$weight = .1;
					$length = 1;
					$width = 1;
					$height = 1;
				}

				$is_taxable = $vat_rate = 0;
				if ( $_POST['ec_new_product_is_taxable'] == '1' ) {
					$is_taxable = 1;
				} else if ( $_POST['ec_new_product_is_taxable'] == '2' ) {
					$vat_rate = 1;
				}
				$option_type = (int) $_POST['ec_new_product_option_type'];
				$use_advanced_optionset = 0;
				$option1 = ( $option_type == '1' ) ? (int) $_POST['option1'] : 0;
				$option2 = ( $option_type == '1' ) ? (int) $_POST['option2'] : 0;
				$option3 = ( $option_type == '1' ) ? (int) $_POST['option3'] : 0;
				$option4 = ( $option_type == '1' ) ? (int) $_POST['option4'] : 0;
				$option5 = ( $option_type == '1' ) ? (int) $_POST['option5'] : 0;

				if ( ! $this->verify_model_number( $sku ) ) {
					return array( 'error' => 'model-number-error' );
				}
				$store_page = get_permalink( get_option( 'ec_option_storepage' ) );
				if ( strstr( $store_page, '?' ) ) {
					$guid = $store_page . '&model_number=' . $model_number;
				} else if ( substr( $store_page, strlen( $store_page ) - 1 ) == '/' ) {
					$guid = $store_page . $post_slug;
				} else {
					$guid = $store_page . '/' . $post_slug;
				}
				$guid = strtolower( $guid );
				$post_slug_orig = $post_slug;
				$guid_orig = $guid;
				$guid = $guid . '/';

				$i=1;
				while( $guid_check = $wpdb->get_row( $wpdb->prepare( 'SELECT ' . $wpdb->prefix . 'posts.guid FROM ' . $wpdb->prefix . 'posts WHERE ' . $wpdb->prefix . 'posts.guid = %s', $guid ) ) ) {
					$guid = $guid_orig . '-' . $i . '/';
					$post_slug = $post_slug_orig . '-' . $i;
					$i++;
				} 

				$wpdb->query( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix . 'posts( post_content, post_status, post_title, post_name, guid, post_type, post_excerpt, post_date, post_date_gmt, post_modified, post_modified_gmt, comment_status ) VALUES( %s, %s, %s, %s, %s, %s, %s, NOW(), UTC_TIMESTAMP(), NOW(), UTC_TIMESTAMP(), "closed" )', '[ec_store modelnumber="' . $sku . '"]', $post_status, wp_easycart_language()->convert_text( $title ), $post_slug, $guid, 'ec_store', '' ) );
				$post_id = $wpdb->insert_id;

				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_product( activate_in_store, show_on_startup, title, model_number, manufacturer_id, price, image1, post_id, use_advanced_optionset, use_both_option_types, option_id_1, option_id_2, option_id_3, option_id_4, option_id_5, is_shippable, weight, length, width, height, is_taxable, vat_rate, show_stock_quantity, stock_quantity, use_optionitem_quantity_tracking, is_giftcard, is_download, is_donation, is_subscription_item, is_deconetwork, inquiry_mode, catalog_mode ) VALUES( %d, %d, %s, %s, %d, %s, %s, %d, %d, 1, %d, %d, %d, %d, %d, %d, %s, %s, %s, %s, %d, %d, %d, %d, %d, %d, %d, %d, %d, %d, %d, %d )', $activate_in_store, $show_on_startup, $title, $sku, $manufacturer, $price, $image, $post_id, $use_advanced_optionset, $option1, $option2, $option3, $option4, $option5, $is_shippable, $weight, $length, $width, $height, $is_taxable, $vat_rate, $show_stock_quantity, $stock_quantity, $use_optionitem_quantity_tracking, $is_giftcard, $is_download, $is_donation, $is_subscription, $is_deconetwork, $is_inquiry, $is_seasonal ) );
				$product_id = $wpdb->insert_id;

				if ( $is_subscription ) {
					if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' || get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) {
						if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
							$stripe = new ec_stripe();
						} else {
							$stripe = new ec_stripe_connect();
						}
						$product_row = $wpdb->get_row( $wpdb->prepare( 'SELECT post_id, is_subscription_item, stripe_plan_added, subscription_unique_id, product_id, price, title, subscription_bill_period, subscription_bill_length, trial_period_days FROM ec_product WHERE product_id = %d', $product_id ) );
						$product_row->subscription_unique_id = rand(10000, 10000000);
						$result = $stripe->insert_plan( $product_row );
						$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET subscription_unique_id = %d, stripe_plan_added = 1 WHERE product_id = %d', $product_row->subscription_unique_id, $product_id ) );
					}
				}

				$option_items_1 = ( 0 != (int) $option1 ) ? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', (int) $option1 ) ) : array();
				$option_items_2 = ( 0 != (int) $option2 ) ? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', (int) $option2 ) ) : array();
				$option_items_3 = ( 0 != (int) $option3 ) ? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', (int) $option3 ) ) : array();
				$option_items_4 = ( 0 != (int) $option4 ) ? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', (int) $option4 ) ) : array();
				$option_items_5 = ( 0 != (int) $option5 ) ? $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d', (int) $option5 ) ) : array();
				$query = 'INSERT INTO ec_optionitemquantity( product_id, optionitem_id_1, optionitem_id_2, optionitem_id_3, optionitem_id_4, optionitem_id_5 ) VALUES';
				$first = true;
				for ( $a = 0; $a<count( $option_items_1 ); $a++ ) {
					if ( count( $option_items_2 ) <= 0 ) {
						if ( ! $first ) {
							$query .= ',';
						}
						$query .= $wpdb->prepare( '( %d, %d, 0, 0, 0, 0 )', (int) $product_id, $option_items_1[$a]->optionitem_id );
						$first = false;
					} else {
						for ( $b = 0; $b<count( $option_items_2 ); $b++ ) {
							if ( count( $option_items_3 ) <= 0 ) {
								if ( ! $first ) {
									$query .= ',';
								}
								$query .= $wpdb->prepare( '( %d, %d, %d, 0, 0, 0 )', (int) $product_id, $option_items_1[$a]->optionitem_id, $option_items_2[$b]->optionitem_id );
								$first = false;
							} else {
								for ( $c = 0; $c<count( $option_items_3 ); $c++ ) {
									if ( count( $option_items_4 ) <= 0 ) {
										if ( ! $first ) {
											$query .= ',';
										}
										$query .= $wpdb->prepare( '( %d, %d, %d, %d, 0, 0 )', (int) $product_id, $option_items_1[$a]->optionitem_id, $option_items_2[$b]->optionitem_id, $option_items_3[$c]->optionitem_id );
										$first = false;
									} else {
										for ( $d = 0; $d<count( $option_items_4 ); $d++ ) {
											if ( count( $option_items_5 ) <= 0 ) {
												if ( ! $first ) {
													$query .= ',';
												}
												$query .= $wpdb->prepare( '( %d, %d, %d, %d, %d, 0 )', (int) $product_id, $option_items_1[$a]->optionitem_id, $option_items_2[$b]->optionitem_id, $option_items_3[$c]->optionitem_id, $option_items_4[$d]->optionitem_id );
												$first = false;
											} else {
												for ( $e = 0; $e<count( $option_items_5 ); $e++ ) {
													if ( ! $first ) {
														$query .= ',';
													}
													$query .= $wpdb->prepare( '( %d, %d, %d, %d, %d, %d )', (int) $product_id, $option_items_1[$a]->optionitem_id, $option_items_2[$b]->optionitem_id, $option_items_3[$c]->optionitem_id, $option_items_4[$d]->optionitem_id, $option_items_5[$e]->optionitem_id );
													$first = false;
												}
											}
										}
									}
								}
							}
						}
					}
				}
				$wpdb->query( $query );

				do_action( 'wpeasycart_product_added', $product_id, $sku );
				do_action( 'wpeasycart_admin_product_inserted', $product_id, $sku );

				return array( 'product_id' => $product_id );
			}
		}

		public function save_product_details_basic() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$activate_in_store = (int) $_POST['activate_in_store'];
				$title = wp_easycart_escape_html( wp_unslash( $_POST['title'] ) ); // XSS OK
				$post_slug = preg_replace( '/(\-+)/', '-', preg_replace( '/[^A-Za-z0-9\-]/', '', str_replace( ' ', '-', strtolower( sanitize_text_field( wp_unslash( $_POST['post_slug'] ) ) ) ) ) );
				while ( substr( $post_slug, -1 ) == '-' ) {
					$post_slug = substr( $post_slug, 0, strlen( $post_slug ) - 1 );
				}
				while ( substr( $post_slug, 0, 1 ) == '-' ) {
					$post_slug = substr( $post_slug, 1, strlen( $post_slug ) );
				}
				if ( $post_slug == '' ) {
					$post_slug = rand( 1000000, 9999999 );
				}
				$model_number = sanitize_text_field( wp_unslash( $_POST['model_number'] ) );
				$manufacturer_id = (int) $_POST['manufacturer_id'];
				$price = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['price'] ) ) );
				$description = wp_easycart_escape_html( $_POST['description'] ); // XSS OK

				if ( $this->verify_model_number() ) {
					if ( $product_id != '0' ) {
						$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET activate_in_store = %d, title = %s, model_number = %s, manufacturer_id = %d, price = %s, description = %s WHERE product_id = %d', $activate_in_store, $title, $model_number, $manufacturer_id, $price, $description, $product_id ) );
						$product_row = $wpdb->get_row( $wpdb->prepare( 'SELECT post_id, is_subscription_item, stripe_plan_added, subscription_unique_id, product_id, price, title, subscription_bill_period, subscription_bill_length, trial_period_days FROM ec_product WHERE product_id = %d', $product_id ) );
						$previous_guid = $wpdb->get_var( $wpdb->prepare( 'SELECT ' . $wpdb->prefix . 'posts.guid FROM ' . $wpdb->prefix . 'posts WHERE ' . $wpdb->prefix . 'posts.ID = %d', $product_row->post_id ) );

						if ( $activate_in_store ) {
							$status = 'publish';
						} else {
							$status = 'private';
						}
						$store_page = get_permalink( get_option( 'ec_option_storepage' ) );
						if ( strstr( $store_page, '?' ) ) {
							$guid = $store_page . '&model_number=' . $model_number;
						} else if ( substr( $store_page, strlen( $store_page ) - 1 ) == '/' ) {
							$guid = $store_page . $post_slug;
						} else {
							$guid = $store_page . '/' . $post_slug;
						}
						$guid = strtolower( $guid );
						$post_slug_orig = $post_slug;
						$guid_orig = $guid;
						$guid = $guid . '/';

						if ( $previous_guid != $guid ) {
							$i = 1;
							while ( $guid_check = $wpdb->get_row( $wpdb->prepare( 'SELECT ' . $wpdb->prefix . 'posts.guid FROM ' . $wpdb->prefix . 'posts WHERE ' . $wpdb->prefix . 'posts.guid = %s AND ' . $wpdb->prefix . 'posts.ID != %d', $guid, $product_row->post_id ) ) ) {
								$guid = $guid_orig . '-' . $i . '/';
								$post_slug = $post_slug_orig . '-' . $i;
								$i++;
							}
						}

						$post_exists = false;
						$post_check = $wpdb->get_row( $wpdb->prepare( 'SELECT ' . $wpdb->prefix . 'posts.guid FROM ' . $wpdb->prefix . 'posts WHERE ' . $wpdb->prefix . 'posts.ID = %d', $product_row->post_id ) );
						if ( $post_check ) {
							/* Manually Update Post */
							$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'posts SET post_content = %s, post_status = %s, post_title = %s, post_name = %s, guid = %s, post_excerpt = %s, post_modified = NOW(), post_modified_gmt = UTC_TIMESTAMP() WHERE ID = %d', '[ec_store modelnumber="' . $model_number . '"]', $status, wp_easycart_language()->convert_text( $title ), $post_slug, $guid, $description, $product_row->post_id ) );
						} else {
							$wpdb->query( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix . 'posts( post_content, post_status, post_title, post_name, guid, post_type, post_excerpt, post_date, post_date_gmt, post_modified, post_modified_gmt, comment_status ) VALUES( %s, %s, %s, %s, %s, %s, %s, NOW(), UTC_TIMESTAMP(), NOW(), UTC_TIMESTAMP(), "closed" )', '[ec_store modelnumber="' . $model_number . '"]', $status, wp_easycart_language()->convert_text( $title ), $post_slug, $guid, 'ec_store', $description ) );
							$post_id = $wpdb->insert_id;
							$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET post_id = %d WHERE product_id = %d', $post_id, $product_id ) );
						}

						if ( $product_row->is_subscription_item && ( get_option( 'ec_option_payment_process_method' ) == 'stripe' || get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) ) {
							if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
								$stripe = new ec_stripe();
							} else {
								$stripe = new ec_stripe_connect();
							}
							if ( $product_row->stripe_plan_added ) {
								$stripe_arr = (object) array(
									'product_id' => $product_row->product_id,
									'title' => $product_row->title,
									'trial_period_days' => $product_row->trial_period_days
								);
								if ( $product_row->subscription_unique_id ) {
									$stripe_arr->product_id = $product_row->subscription_unique_id;
								}
								$plan = $stripe->get_plan( $stripe_arr );

								if ( $plan === false || $price != ( $plan->amount / 100 ) ) {
									$product_row->subscription_unique_id = rand(10000, 10000000);
									$result = $stripe->insert_plan( $product_row );
									$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET subscription_unique_id = %d, stripe_plan_added = 1 WHERE product_id = %d', $product_row->subscription_unique_id, $product_id ) );

								} else if ( $plan->name != $product_row->title ) {
									$result = $stripe->update_plan( $stripe_arr );
								}

							} else {
								$product_row->subscription_unique_id = rand(10000, 10000000);
								$result = $stripe->insert_plan( $product_row );
								$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET subscription_unique_id = %d, stripe_plan_added = 1 WHERE product_id = %d', $product_row->subscription_unique_id, $product_id ) );
							}
						}
						do_action( 'wpeasycart_product_updated', $product_id, $model_number );
						return true;
					} else {
						if ( $activate_in_store ) {
							$status = 'publish';
						} else {
							$status = 'private';
						}
						$post_slug = preg_replace( '/(\-+)/', '-', preg_replace( '/[^A-Za-z0-9\-]/', '', str_replace( ' ', '-', wp_unslash( strtolower( $title ) ) ) ) );
						$store_page = get_permalink( get_option( 'ec_option_storepage' ) );
						if ( strstr( $store_page, '?' ) ) {
							$guid = $store_page . '&model_number=' . $model_number;
						} else if ( substr( $store_page, strlen( $store_page ) - 1 ) == '/' ) {
							$guid = $store_page . $post_slug;
						} else {
							$guid = $store_page . '/' . $post_slug;
						}
						$guid = strtolower( $guid );
						$post_slug_orig = $post_slug;
						$guid_orig = $guid;
						$guid = $guid . '/';
						$i=1;
						while ( $guid_check = $wpdb->get_row( $wpdb->prepare( 'SELECT ' . $wpdb->prefix . 'posts.guid FROM ' . $wpdb->prefix . 'posts WHERE ' . $wpdb->prefix . 'posts.guid = %s', $guid ) ) ) {
							$guid = $guid_orig . '-' . $i . '/';
							$post_slug = $post_slug_orig . '-' . $i;
							$i++;
						} 

						$wpdb->query( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix . 'posts( post_content, post_status, post_title, post_name, guid, post_type, post_excerpt, post_date, post_date_gmt, post_modified, post_modified_gmt, comment_status ) VALUES( %s, %s, %s, %s, %s, %s, %s, NOW(), UTC_TIMESTAMP(), NOW(), UTC_TIMESTAMP(), "closed" )', '[ec_store modelnumber="' . $model_number . '"]', $status, wp_easycart_language()->convert_text( $title ), $post_slug, $guid, 'ec_store', $description ) );
						$post_id = $wpdb->insert_id;

						$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_product( activate_in_store, title, model_number, manufacturer_id, price, description, post_id, show_on_startup, show_stock_quantity ) VALUES( %d, %s, %s, %d, %s, %s, %d, 1, 0 )', $activate_in_store, $title, $model_number, $manufacturer_id, $price, $description, $post_id ) );
						$product_id = $wpdb->insert_id;
						do_action( 'wpeasycart_product_added', $product_id, $model_number );
						do_action( 'wpeasycart_admin_product_inserted', $product_id, $model_number );
						return $product_id;
					}

				} else {
					return false;
				}
			}
		}

		public function save_new_optionset() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$option_type = sanitize_text_field( wp_unslash( $_POST['ec_new_option_type'] ) );
				$option_name = sanitize_text_field( wp_unslash( $_POST['ec_new_option_name'] ) );
				$option_label = sanitize_text_field( wp_unslash( $_POST['ec_new_option_label'] ) );
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_option( option_name, option_label, option_type ) VALUES( %s, %s, %s )', $option_name, $option_label, $option_type ) );
				return $wpdb->insert_id;
			}
		}

		public function save_new_optionitem() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$option_id = (int) $_POST['ec_new_optionitem_option_id'];
				$order = (int) $_POST['ec_new_optionitem_sort_order'];
				$name = sanitize_text_field( wp_unslash( $_POST['ec_new_optionitem_name'] ) );
				$model_number = sanitize_text_field( wp_unslash( $_POST['ec_new_optionitem_model_number_extension'] ) );
				$price_adjustment = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_new_optionitem_price_adjustment'] ) ) );
				$weight_adjustment = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_new_optionitem_weight_adjustment'] ) ) );
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_optionitem( option_id, optionitem_order, optionitem_name, optionitem_model_number, optionitem_price, optionitem_weight ) VALUES( %d, %d, %s, %s, %s, %s )', $option_id, $order, $name, $model_number, $price_adjustment, $weight_adjustment ) );
			}
		}

		public function save_new_adv_optionset() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$option_type = sanitize_text_field( wp_unslash( $_POST['ec_new_adv_option_type'] ) );
				$option_name = sanitize_text_field( wp_unslash( $_POST['ec_new_adv_option_name'] ) );
				$option_label = sanitize_text_field( wp_unslash( $_POST['ec_new_adv_option_label'] ) );
				$option_meta = array(
					'min' => sanitize_text_field( wp_unslash( $_POST['ec_new_adv_option_meta_min'] ) ),
					'max' => sanitize_text_field( wp_unslash( $_POST['ec_new_adv_option_meta_max'] ) ),
					'step' => sanitize_text_field( wp_unslash( $_POST['ec_new_adv_option_meta_step'] ) ),
				);
				$option_required = 0;
				if ( isset( $_POST['ec_new_adv_option_required'] ) && $_POST['ec_new_adv_option_required'] == '1' ) {
					$option_required = 1;
				}
				$option_error_text = sanitize_text_field( wp_unslash( $_POST['ec_new_adv_option_error_text'] ) );

				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_option( option_name, option_label, option_type, option_required, option_error_text, option_meta ) VALUES( %s, %s, %s, %s, %s, %s )', $option_name, $option_label, $option_type, $option_required, $option_error_text, maybe_serialize( $option_meta ) ) );
				$option_id = $wpdb->insert_id;

				if ( 'file' == $option_type || 'text' == $option_type || 'number' == $option_type || 'textarea' == $option_type || 'date' == $option_type || 'dimensions1' == $option_type || 'dimensions2' == $option_type ) {
					if ( $option_type == 'file' ) {
						$option_name = 'File Field';
					}
					if ( $option_type == 'text' ) {
						$option_name = 'Text Box Input';
					}
					if ( $option_type == 'number' ) {
						$option_name = 'Number Box Input';
					}
					if ( $option_type == 'textarea' ) {
						$option_name = 'Text Area Input';
					}
					if ( $option_type == 'date' ) {
						$option_name = 'Date Field';
					}
					if ( $option_type == 'dimensions1' ) {
						$option_name = 'DimensionType1';
					}
					if ( $option_type == 'dimensions2' ) {
						$option_name = 'DimensionType2'; 
					}
					$wpdb->query( $wpdb->prepare( "INSERT INTO ec_optionitem( option_id, optionitem_name, optionitem_price, optionitem_price_onetime, optionitem_price_override, optionitem_weight, optionitem_weight_onetime, optionitem_weight_override, optionitem_order, optionitem_icon, optionitem_initial_value ) VALUES( %d, %s, '0.00', '0.00', '-1', '0.00', '0.00', '-1.00', 1, '', '' )", $option_id, $option_name ) );
				}
				return $option_id;
			}
		}

		public function save_new_adv_optionitem() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$option_id = (int) $_POST['ec_new_optionitem_option_id'];
				$order = (int) $_POST['ec_new_optionitem_sort_order'];
				$name = sanitize_text_field( wp_unslash( $_POST['ec_new_optionitem_name'] ) );
				$model_number = sanitize_text_field( wp_unslash( $_POST['ec_new_optionitem_model_number_extension'] ) );
				$initial_value = '';
				$icon = '';
				$optionitem_initially_selected = false;
				if ( isset( $_POST['ec_admin_adv_optionitem_initially_selected'] ) && $_POST['ec_admin_adv_optionitem_initially_selected'] == '1' )
					$optionitem_initially_selected = 1;
				$optionitem_disallow_shipping = false;
				if ( isset( $_POST['ec_admin_adv_optionitem_no_shipping'] ) && $_POST['ec_admin_adv_optionitem_no_shipping'] == '1' )
					$optionitem_disallow_shipping = 1;
				$optionitem_allow_download = false;
				if ( isset( $_POST['ec_admin_adv_optionitem_allows_download'] ) && $_POST['ec_admin_adv_optionitem_allows_download'] == '1' )
					$optionitem_allow_download = 1;

				$price_adjustment_type = sanitize_text_field( wp_unslash( $_POST['ec_new_optionitem_price_adjustment_type'] ) );
				$optionitem_price = 0; $optionitem_price_onetime = 0; $optionitem_price_override = -1; $optionitem_price_multiplier = 0;
				if ( $price_adjustment_type == 'basic_price' ) {
					$optionitem_price = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_new_optionitem_price_adjustment'] ) ) );
				} else if ( $price_adjustment_type == 'one_time_price' ) {
					$optionitem_price_onetime = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_new_optionitem_price_adjustment'] ) ) );
				} else if ( $price_adjustment_type == 'override_price' ) {
					$optionitem_price_override = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_new_optionitem_price_adjustment'] ) ) );
				} else if ( $price_adjustment_type == 'multiplier_price' ) {
					$optionitem_price_multiplier = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_new_optionitem_price_adjustment'] ) ) );
				}

				$weight_adjustment_type = sanitize_text_field( wp_unslash( $_POST['ec_new_optionitem_weight_adjustment_type'] ) );
				$optionitem_weight = 0; $optionitem_weight_onetime = 0; $optionitem_weight_override = -1; $optionitem_weight_multiplier = 0;
				if ( $weight_adjustment_type == 'basic_weight' ) {
					$optionitem_weight = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_new_optionitem_weight_adjustment'] ) ) );
				} else if ( $weight_adjustment_type == 'one_time_weight' ) {
					$optionitem_weight_onetime = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_new_optionitem_weight_adjustment'] ) ) );
				} else if ( $weight_adjustment_type == 'override_weight' ) {
					$optionitem_weight_override = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_new_optionitem_weight_adjustment'] ) ) );
				} else if ( $weight_adjustment_type == 'multiplier_weight' ) {
					$optionitem_weight_multiplier = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_new_optionitem_weight_adjustment'] ) ) );
				}

				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_optionitem( 
					option_id, optionitem_name, optionitem_price, optionitem_price_onetime, optionitem_price_override,
					optionitem_price_multiplier, optionitem_weight, optionitem_weight_onetime, optionitem_weight_override,
					optionitem_weight_multiplier, optionitem_order, optionitem_icon, optionitem_initial_value, optionitem_model_number,
					optionitem_allow_download, optionitem_disallow_shipping, optionitem_initially_selected
				) VALUES( 
					%d, %s, %s, %s, %s,
					%s, %s, %s, %s,
					%s, %d, %s, %s, %s, 
					%d, %d, %d
				)', 

					$option_id, $name, $optionitem_price, $optionitem_price_onetime, $optionitem_price_override,
					$optionitem_price_multiplier, $optionitem_weight, $optionitem_weight_onetime, $optionitem_weight_override,
					$optionitem_weight_multiplier, $order, $icon, $initial_value, $model_number, 
					$optionitem_allow_download, $optionitem_disallow_shipping, $optionitem_initially_selected
				) );
			}
		}

		public function save_product_details_options() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$use_advanced_optionset = (int) $_POST['use_advanced_optionset'];
				$option1 = (int) $_POST['option1'];
				$option2 = (int) $_POST['option2'];
				$option3 = (int) $_POST['option3'];
				$option4 = (int) $_POST['option4'];
				$option5 = (int) $_POST['option5'];

				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET use_advanced_optionset = %d, option_id_1 = %d, option_id_2 = %d, option_id_3 = %d, option_id_4 = %d, option_id_5 = %d WHERE product_id = %d', $use_advanced_optionset, $option1, $option2, $option3, $option4, $option5, $product_id ) );

				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_optionitemquantity WHERE product_id = %d', $product_id ) );
			}
		}

		public function add_advanced_option() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$option_id = (int) $_POST['option_id'];

				$highest_sort = $wpdb->get_var( $wpdb->prepare( 'SELECT option_order FROM ec_option_to_product WHERE product_id = %d ORDER BY option_order DESC', $product_id ) );
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_option_to_product( product_id, option_id, option_order ) VALUES( %d, %d, %d )', $product_id, $option_id, $highest_sort + 1 ) );
				$option_to_product_id = $wpdb->insert_id;
				do_action( 'wp_easycart_option_to_product_created', $option_id, $product_id );
				return $option_to_product_id;
			}
		}

		public function delete_advanced_option() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$option_to_product_id = (int) $_POST['option_to_product_id'];

				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_option_to_product WHERE option_to_product_id = %d', $option_to_product_id ) );
				do_action( 'wp_easycart_option_to_product_deleted', $option_to_product_id );
			}
		}

		public function save_product_details_images() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$use_optionitem_images = (int) $_POST['use_optionitem_images'];
				$image1 = sanitize_text_field( wp_unslash( $_POST['image1'] ) );
				$image2 = sanitize_text_field( wp_unslash( $_POST['image2'] ) );
				$image3 = sanitize_text_field( wp_unslash( $_POST['image3'] ) );
				$image4 = sanitize_text_field( wp_unslash( $_POST['image4'] ) );
				$image5 = sanitize_text_field( wp_unslash( $_POST['image5'] ) );
				$optionitem_images = ( isset( $_POST['optionitem_images'] ) ) ? (array) $_POST['optionitem_images'] : array(); // XSS OK. Forced array and each item sanitized.

				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET use_optionitem_images = %d, image1 = %s, image2 = %s, image3 = %s, image4 = %s, image5 = %s WHERE product_id = %d', $use_optionitem_images, $image1, $image2, $image3, $image4, $image5, $product_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_optionitemimage WHERE product_id = %d', $product_id ) );
				foreach ( $optionitem_images as $optionitem_image ) {
					$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_optionitemimage( optionitem_id, product_id, image1, image2, image3, image4, image5 ) VALUES( %d, %d, %s, %s, %s, %s, %s )', 
						(int) $optionitem_image['optionitem_id'],
						$product_id,
						sanitize_text_field( wp_unslash( $optionitem_image['image1'] ) ),
						sanitize_text_field( wp_unslash( $optionitem_image['image2'] ) ),
						sanitize_text_field( wp_unslash( $optionitem_image['image3'] ) ),
						sanitize_text_field( wp_unslash( $optionitem_image['image4'] ) ),
						sanitize_text_field( wp_unslash( $optionitem_image['image5'] ) )
					) );
				}
			}
		}

		public function save_product_details_menus() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$menulevel1_id_1 = (int) $_POST['menulevel1_id_1'];
				$menulevel1_id_2 = (int) $_POST['menulevel1_id_2'];
				$menulevel1_id_3 = (int) $_POST['menulevel1_id_3'];
				$menulevel2_id_1 = (int) $_POST['menulevel2_id_1'];
				$menulevel2_id_2 = (int) $_POST['menulevel2_id_2'];
				$menulevel2_id_3 = (int) $_POST['menulevel2_id_3'];
				$menulevel3_id_1 = (int) $_POST['menulevel3_id_1'];
				$menulevel3_id_2 = (int) $_POST['menulevel3_id_2'];
				$menulevel3_id_3 = (int) $_POST['menulevel3_id_3'];

				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel1_id_1 = %d, menulevel1_id_2 = %d, menulevel1_id_3 = %d, menulevel2_id_1 = %d, menulevel2_id_2 = %d, menulevel2_id_3 = %d, menulevel3_id_1 = %d, menulevel3_id_2 = %d, menulevel3_id_3 = %d WHERE product_id = %d', $menulevel1_id_1, $menulevel1_id_2, $menulevel1_id_3, $menulevel2_id_1, $menulevel2_id_2, $menulevel2_id_3, $menulevel3_id_1, $menulevel3_id_2, $menulevel3_id_3, $product_id ) );
			}
		}

		public function add_category() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$category_id = (int) $_POST['category_id'];

				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_categoryitem( product_id, category_id ) VALUES( %d, %d )', $product_id, $category_id ) );
				return $wpdb->insert_id;
			}
		}

		public function delete_category() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$category_id = (int) $_POST['category_id'];

				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_categoryitem WHERE category_id = %d AND product_id = %d', $category_id, $product_id ) );
			}
		}

		public function save_product_details_quantities() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$show_stock_quantity = 0;
				$use_optionitem_quantity_tracking = 0;
				$stock_quantity_type = sanitize_text_field( wp_unslash( $_POST['stock_quantity_type'] ) );
				if ( $stock_quantity_type == '1' )
					$show_stock_quantity = 1;
				else if ( $stock_quantity_type == '2' )
					$use_optionitem_quantity_tracking = 1;
				$stock_quantity = (int) $_POST['stock_quantity'];
				$min_purchase_quantity = (int) $_POST['min_purchase_quantity'];
				$max_purchase_quantity = (int) $_POST['max_purchase_quantity'];
				$model_number = $wpdb->get_var( $wpdb->prepare( 'SELECT model_number FROM ec_product WHERE product_id = %d', $product_id ) );

				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET show_stock_quantity = %d, use_optionitem_quantity_tracking = %d, stock_quantity = %d, min_purchase_quantity = %d, max_purchase_quantity = %d WHERE product_id = %d', $show_stock_quantity, $use_optionitem_quantity_tracking, $stock_quantity, $min_purchase_quantity, $max_purchase_quantity, $product_id ) );
				do_action( 'wpeasycart_product_updated', $product_id, $model_number );
			}
		}

		public function add_optionitem_quantity() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$optionitem_id_1 = ( isset( $_POST['add_new_optionitem_quantity_1'] ) ) ? (int) $_POST['add_new_optionitem_quantity_1'] : 0;
				$optionitem_id_2 = ( isset( $_POST['add_new_optionitem_quantity_2'] ) ) ? (int) $_POST['add_new_optionitem_quantity_2'] : 0;
				$optionitem_id_3 = ( isset( $_POST['add_new_optionitem_quantity_3'] ) ) ? (int) $_POST['add_new_optionitem_quantity_3'] : 0;
				$optionitem_id_4 = ( isset( $_POST['add_new_optionitem_quantity_4'] ) ) ? (int) $_POST['add_new_optionitem_quantity_4'] : 0;
				$optionitem_id_5 = ( isset( $_POST['add_new_optionitem_quantity_5'] ) ) ? (int) $_POST['add_new_optionitem_quantity_5'] : 0;
				$quantity = (int) $_POST['add_new_optionitem_quantity'];
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_optionitemquantity( product_id, optionitem_id_1, optionitem_id_2, optionitem_id_3, optionitem_id_4, optionitem_id_5, quantity ) VALUES( %d, %d, %d, %d, %d, %d, %d )', $product_id, $optionitem_id_1, $optionitem_id_2, $optionitem_id_3, $optionitem_id_4, $optionitem_id_5, $quantity ) );
				$optionitemquantity_id = $wpdb->insert_id;
				$this->update_stock_from_optionitem_quantity( $product_id );
				return $optionitemquantity_id;
			}
		}

		public function update_optionitem_quantity() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$optionitemquantity_id = (int) $_POST['optionitemquantity_id'];
				$quantity = (int) $_POST['quantity'];
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_optionitemquantity SET quantity = %d WHERE optionitemquantity_id = %d', $quantity, $optionitemquantity_id ) );
				$this->update_stock_from_optionitem_quantity( $product_id );
			}
		}

		public function delete_optionitem_quantity() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$optionitemquantity_id = (int) $_POST['optionitemquantity_id'];
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_optionitemquantity WHERE optionitemquantity_id = %d', $optionitemquantity_id ) );
				$this->update_stock_from_optionitem_quantity( $product_id );
			}
		}

		public function update_stock_from_optionitem_quantity( $product_id ) {
			global $wpdb;
			$total = $wpdb->get_var( $wpdb->prepare( 'SELECT SUM( ec_optionitemquantity.quantity ) as quantity FROM ec_optionitemquantity WHERE product_id = %d', $product_id ) );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET stock_quantity = %d WHERE product_id = %d', $total, $product_id ) );
		}

		public function save_product_details_pricing() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$model_number = $wpdb->get_var( $wpdb->prepare( 'SELECT model_number FROM ec_product WHERE product_id = %d', $product_id ) );
				$list_price = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['list_price'] ) ) );
				$login_for_pricing = ( isset( $_POST['login_for_pricing'] ) && $_POST['login_for_pricing'] == '1' ) ? 1 : 0;
				$valid_user_levels = array();
				if ( isset( $_POST['login_for_pricing_user_level'] ) && is_array( $_POST['login_for_pricing_user_level'] ) ) {
					foreach ( (array) $_POST['login_for_pricing_user_level'] as $user_role ) { // XSS OK. Forced array and each item sanitized.
						if ( wp_easycart_admin_verification()->valid_user_role( sanitize_text_field( wp_unslash( $user_role ) ) ) ) {
							$valid_user_levels[] = sanitize_text_field( wp_unslash( $user_role ) );
						}
					}
				}

				$login_for_pricing_user_level = json_encode( $valid_user_levels );
				$login_for_pricing_label = sanitize_text_field( wp_unslash( $_POST['login_for_pricing_label'] ) );
				$enable_price_label = (int) apply_filters( 'wp_easycart_admin_product_custom_price_label_save', 0, $_POST['enable_price_label'] );
				$replace_price_label = (int) $_POST['replace_price_label'];
				$custom_price_label = wp_easycart_escape_html( wp_unslash( $_POST['custom_price_label'] ) ); // XSS OK

				$show_custom_price_range = 0;
				if ( isset( $_POST['show_custom_price_range'] ) && $_POST['show_custom_price_range'] == '1' )
					$show_custom_price_range = 1;

				$price_range_low = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['price_range_low'] ) ) );
				$price_range_high = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['price_range_high'] ) ) );

				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET list_price = %s, show_custom_price_range = %d, price_range_low = %s, price_range_high = %s, login_for_pricing = %s, login_for_pricing_user_level = %s, login_for_pricing_label = %s, enable_price_label = %d, replace_price_label = %d, custom_price_label = %s WHERE product_id = %d', $list_price, $show_custom_price_range, $price_range_low, $price_range_high, $login_for_pricing, $login_for_pricing_user_level, $login_for_pricing_label, $enable_price_label, $replace_price_label, $custom_price_label, $product_id ) );
				do_action( 'wpeasycart_product_updated', $product_id, $model_number );
			}
		}

		public function add_price_tier() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$price = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_admin_new_price_tier_price'] ) ) );
				$quantity = (int) $_POST['ec_admin_new_price_tier_quantity'];

				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_pricetier( product_id, price, quantity ) VALUES( %d, %s, %s )', $product_id, $price, $quantity ) );
				return $wpdb->insert_id;
			}
		}

		public function update_price_tier() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$pricetier_id = (int) $_POST['pricetier_id'];
				$product_id = (int) $_POST['product_id'];
				$quantity = (int) $_POST['quantity'];
				$price = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['price'] ) ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_pricetier SET quantity = %s, price = %s WHERE pricetier_id = %d AND product_id = %d', $quantity, $price, $pricetier_id, $product_id ) );
			}
		}

		public function delete_price_tier() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$pricetier_id = (int) $_POST['pricetier_id'];

				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_pricetier WHERE pricetier_id = %d', $pricetier_id ) );
			}
		}

		public function add_role_price() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$role_label = sanitize_text_field( wp_unslash( $_POST['add_new_role_price_role'] ) );
				$role_price = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_admin_new_role_price'] ) ) );

				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_roleprice( product_id, role_label, role_price ) VALUES( %d, %s, %s )', $product_id, $role_label, $role_price ) );
				return $wpdb->insert_id;
			}
		}

		public function delete_role_price() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$roleprice_id = (int) $_POST['roleprice_id'];

				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_roleprice WHERE roleprice_id = %d', $roleprice_id ) );
			}
		}

		public function save_product_details_packaging() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$weight = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['weight'] ) ) );
				$width = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['width'] ) ) );
				$height = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['height'] ) ) );
				$length = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['length'] ) ) );

				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET weight = %s, width = %s, height = %s, length = %s WHERE product_id = %d', $weight, $width, $height, $length, $product_id ) );
			}
		}

		public function save_product_details_shipping() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$is_shippable = (int) $_POST['is_shippable'];
				$exclude_shippable_calculation = (int) $_POST['exclude_shippable_calculation'];
				$ship_to_billing = (int) $_POST['ship_to_billing'];
				$allow_backorders = (int) $_POST['allow_backorders'];
				$backorder_fill_date = wp_easycart_escape_html( wp_unslash( $_POST['backorder_fill_date'] ) );
				$handling_price = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['handling_price'] ) ) );
				$handling_price_each = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['handling_price_each'] ) ) );
				$shipping_restriction = (int) $_POST['shipping_restriction'];
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET is_shippable = %d, exclude_shippable_calculation = %d, ship_to_billing = %d, allow_backorders = %d, backorder_fill_date = %s, handling_price = %s, handling_price_each = %s, shipping_restriction = %d WHERE product_id = %d', $is_shippable, $exclude_shippable_calculation, $ship_to_billing, $allow_backorders, $backorder_fill_date, $handling_price, $handling_price_each, $shipping_restriction, $product_id ) );
			}
		}

		public function save_product_details_short_description() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$short_description = wp_easycart_escape_html( $_POST['short_description'] ); // XSS OK

				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET short_description = %s WHERE product_id = %d', $short_description, $product_id ) );
			}
		}

		public function save_product_details_specifications() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$specifications = wp_easycart_escape_html( $_POST['specifications'] ); // XSS OK
				$use_specifications = 0;
				if ( strlen( trim( $specifications ) ) > 0 )
					$use_specifications = 1;

				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET specifications = %s, use_specifications = %d WHERE product_id = %d', $specifications, $use_specifications, $product_id ) );
			}
		}

		public function save_product_details_order_completed_note() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$order_completed_note = wp_easycart_escape_html( $_POST['order_completed_note'] ); // XSS OK
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET order_completed_note = %s WHERE product_id = %d', $order_completed_note, $product_id ) );
			}
		}

		public function save_product_details_order_completed_email_note() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$order_completed_email_note = wp_easycart_escape_html( $_POST['order_completed_email_note'] ); // XSS OK
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET order_completed_email_note = %s WHERE product_id = %d', $order_completed_email_note, $product_id ) );
			}
		}

		public function save_product_details_order_completed_details_note() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$order_completed_details_note = wp_easycart_escape_html( $_POST['order_completed_details_note'] ); // XSS OK
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET order_completed_details_note = %s WHERE product_id = %d', $order_completed_details_note, $product_id ) );
			}
		}

		public function save_product_details_tags() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$tag_type = sanitize_text_field( wp_unslash( $_POST['tag_type'] ) );
				$tag_text = sanitize_text_field( wp_unslash( $_POST['tag_text'] ) );
				$tag_bg_color = sanitize_text_field( wp_unslash( $_POST['tag_bg_color'] ) );
				$tag_text_color = sanitize_text_field( wp_unslash( $_POST['tag_text_color'] ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET tag_type = %d, tag_text = %s, tag_bg_color = %s, tag_text_color = %s WHERE product_id = %d', $tag_type, $tag_text, $tag_bg_color, $tag_text_color, $product_id ) );
			}
		}

		public function save_product_details_featured_products() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$featured_product_id_1 = (int) $_POST['featured_product_id_1'];
				$featured_product_id_2 = (int) $_POST['featured_product_id_2'];
				$featured_product_id_3 = (int) $_POST['featured_product_id_3'];
				$featured_product_id_4 = (int) $_POST['featured_product_id_4'];
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET featured_product_id_1 = %d, featured_product_id_2 = %d, featured_product_id_3 = %d, featured_product_id_4 = %d WHERE product_id = %d', $featured_product_id_1, $featured_product_id_2, $featured_product_id_3, $featured_product_id_4, $product_id ) );
			}
		}

		public function save_product_details_general_options() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$show_on_startup = (int) $_POST['show_on_startup'];
				$is_special = (int) $_POST['is_special'];
				$use_customer_reviews = (int) $_POST['use_customer_reviews'];
				$is_donation = (int) $_POST['is_donation'];
				$is_giftcard = (int) $_POST['is_giftcard'];
				$inquiry_mode = (int) $_POST['inquiry_mode'];
				$inquiry_url = esc_url_raw( $_POST['inquiry_url'] );
				$catalog_mode = (int) $_POST['catalog_mode'];
				$catalog_mode_phrase = sanitize_text_field( wp_unslash( $_POST['catalog_mode_phrase'] ) );
				$role_id = (int) $_POST['role_id'];
				$sort_position = (int) $_POST['sort_position'];
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET show_on_startup = %d, is_special = %d, use_customer_reviews = %d, is_donation = %d, is_giftcard = %d, inquiry_mode = %d, inquiry_url = %s, catalog_mode = %d, catalog_mode_phrase = %s, role_id = %s, sort_position = %d WHERE product_id = %d', $show_on_startup, $is_special, $use_customer_reviews, $is_donation, $is_giftcard, $inquiry_mode, $inquiry_url, $catalog_mode, $catalog_mode_phrase, $role_id, $sort_position, $product_id ) );
				do_action( 'wpeasycart_admin_product_details_general_saved', $product_id );
			}
		}

		public function save_product_details_tax() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$is_taxable = (int) $_POST['is_taxable'];
				$vat_rate = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['vat_rate'] ) ) );
				$TIC = sanitize_text_field( wp_unslash( $_POST['TIC'] ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET is_taxable = %d, vat_rate = %s, TIC = %s WHERE product_id = %d', $is_taxable, $vat_rate, $TIC, $product_id ) );
			}
		}

		public function save_product_details_deconetwork() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$is_deconetwork = (int) $_POST['is_deconetwork'];
				$deconetwork_mode = sanitize_text_field( wp_unslash( $_POST['deconetwork_mode'] ) );
				$deconetwork_product_id = sanitize_text_field( wp_unslash( $_POST['deconetwork_product_id'] ) );
				$deconetwork_size_id = sanitize_text_field( wp_unslash( $_POST['deconetwork_size_id'] ) );
				$deconetwork_color_id = sanitize_text_field( wp_unslash( $_POST['deconetwork_color_id'] ) );
				$deconetwork_design_id = sanitize_text_field( wp_unslash( $_POST['deconetwork_design_id'] ) );

				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET is_deconetwork = %d, deconetwork_mode = %s, deconetwork_product_id = %s, deconetwork_size_id = %s, deconetwork_color_id = %s, deconetwork_design_id = %s WHERE product_id = %d', $is_deconetwork, $deconetwork_mode, $deconetwork_product_id, $deconetwork_size_id, $deconetwork_color_id, $deconetwork_design_id, $product_id ) );
			}
		}

		public function save_product_details_subscription() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$product_pre_update = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_product WHERE product_id = %d', $product_id ) );
				$product_modifiers = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_option_to_product WHERE product_id = %d', $product_id ) );
				$is_subscription_item = (int) $_POST['is_subscription_item'];
				$subscription_bill_length = (int) $_POST['subscription_bill_length'];
				$subscription_bill_period = wp_easycart_admin_verification()->filter_list( sanitize_text_field( wp_unslash( $_POST['subscription_bill_period'] ) ), array( 'W', 'M', 'Y' ) );
				$subscription_bill_duration = (int) $_POST['subscription_bill_duration'];
				$subscription_shipping_recurring = (int) $_POST['subscription_shipping_recurring'];
				$trial_period_days = (int) $_POST['trial_period_days'];
				$subscription_signup_fee = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['subscription_signup_fee'] ) ) );
				$allow_multiple_subscription_purchases = (int) $_POST['allow_multiple_subscription_purchases'];
				$subscription_prorate = (int) $_POST['subscription_prorate'];
				$subscription_plan_id = (int) $_POST['subscription_plan_id'];
				$membership_page = esc_url_raw( $_POST['membership_page'] );

				$found_price_change = false;
				if ( $subscription_shipping_recurring ) {
					$found_price_change = true;
				}
				if ( ! $found_price_change && $product_pre_update && 0 != $product_pre_update->option_id_1 ) {
					$option_items = $GLOBALS['ec_options']->get_optionitems( $product_pre_update->option_id_1 );
					foreach ( $option_items as $option_item ) {
						if ( $option_item->optionitem_price > 0 ) {
							$found_price_change = true;
						}
					}
				}
				if ( ! $found_price_change && $product_pre_update && 0 != $product_pre_update->option_id_2 ) {
					$option_items = $GLOBALS['ec_options']->get_optionitems( $product_pre_update->option_id_2 );
					foreach ( $option_items as $option_item ) {
						if ( $option_item->optionitem_price > 0 ) {
							$found_price_change = true;
						}
					}
				}
				if ( ! $found_price_change && $product_pre_update && 0 != $product_pre_update->option_id_3 ) {
					$option_items = $GLOBALS['ec_options']->get_optionitems( $product_pre_update->option_id_3 );
					foreach ( $option_items as $option_item ) {
						if ( $option_item->optionitem_price > 0 ) {
							$found_price_change = true;
						}
					}
				}
				if ( ! $found_price_change && $product_pre_update && 0 != $product_pre_update->option_id_4 ) {
					$option_items = $GLOBALS['ec_options']->get_optionitems( $product_pre_update->option_id_4 );
					foreach ( $option_items as $option_item ) {
						if ( $option_item->optionitem_price > 0 ) {
							$found_price_change = true;
						}
					}
				}
				if ( ! $found_price_change && $product_pre_update && 0 != $product_pre_update->option_id_5 ) {
					$option_items = $GLOBALS['ec_options']->get_optionitems( $product_pre_update->option_id_5 );
					foreach ( $option_items as $option_item ) {
						if ( $option_item->optionitem_price > 0 ) {
							$found_price_change = true;
						}
					}
				}
				if ( ! $found_price_change && $product_modifiers && count( $product_modifiers ) > 0 ) {
					foreach ( $product_modifiers as $product_modifier ) {
						$option_item = $GLOBALS['ec_options']->get_optionitem( $product_modifier->optionitem_id );
						if ( $option_item && ( $option_item->optionitem_price > 0 || $option_item->optionitem_price_onetime > 0 || $option_item->optionitem_price_override > 0 ) ) {
							$found_price_change = true;
						}
					}
				}
				if ( $found_price_change ) {
					$subscription_plan_id = 0;
				}

				$intervals = array(
					'day'	=> 'D',
					'week'	=> 'W',
					'month'	=> 'M',
					'year'	=> 'Y'
				);

				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET is_subscription_item = %d, subscription_bill_length = %s, subscription_bill_period = %s, subscription_bill_duration = %s, subscription_shipping_recurring = %d, trial_period_days = %s, subscription_signup_fee = %s, allow_multiple_subscription_purchases = %s, subscription_prorate = %s, subscription_plan_id = %s, membership_page = %s WHERE product_id = %d', $is_subscription_item, $subscription_bill_length, $subscription_bill_period, $subscription_bill_duration, $subscription_shipping_recurring, $trial_period_days, $subscription_signup_fee, $allow_multiple_subscription_purchases, $subscription_prorate, $subscription_plan_id, $membership_page, $product_id ) );
				$product_row = $wpdb->get_row( $wpdb->prepare( 'SELECT stripe_plan_added, subscription_unique_id, product_id, price, title, subscription_bill_period, subscription_bill_length, trial_period_days FROM ec_product WHERE product_id = %d', $product_id ) );

				if ( $is_subscription_item && ( get_option( 'ec_option_payment_process_method' ) == 'stripe' || get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) ) {
					if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' )
						$stripe = new ec_stripe();
					else
						$stripe = new ec_stripe_connect();

					if ( $product_row->stripe_plan_added ) {
						$stripe_arr = (object) array( 'product_id' => $product_row->product_id, 'title' => $product_row->title, 'trial_period_days' => $product_row->trial_period_days );
						if ( $product_row->subscription_unique_id )
							$stripe_arr->product_id = $product_row->subscription_unique_id;

						$plan = $stripe->get_plan( $stripe_arr );

						if ( $plan === false || ( $plan->amount / 100 ) != $product_row->price || $intervals[ $plan->interval ] != $subscription_bill_period || $plan->interval_count != $subscription_bill_length ) {
							$product_row->subscription_unique_id = rand(10000, 10000000);
							$result = $stripe->insert_plan( $product_row );
							$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET subscription_unique_id = %d, stripe_plan_added = 1 WHERE product_id = %d', $product_row->subscription_unique_id, $product_id ) );

						} else if ( ( ! isset( $plan->trial_period_days ) && $trial_period_days != 0 ) || ( isset( $plan->trial_period_days ) && $trial_period_days != $plan->trial_period_days ) ) {
							$stripe->update_plan( $stripe_arr );

						}

					} else {
						$product_row->subscription_unique_id = rand(10000, 10000000);
						$result = $stripe->insert_plan( $product_row );
						$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET subscription_unique_id = %d, stripe_plan_added = 1 WHERE product_id = %d', $product_row->subscription_unique_id, $product_id ) );

					}
				}
			}
		}

		public function save_product_details_seo() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$seo_description = sanitize_text_field( wp_unslash( $_POST['seo_description'] ) );
				$seo_keywords = sanitize_text_field( wp_unslash( $_POST['seo_keywords'] ) );
				$post_excerpt = sanitize_text_field( wp_unslash( $_POST['post_excerpt'] ) );
				$featured_image = (int) $_POST['featured_image'];

				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET seo_description = %s, seo_keywords = %s WHERE product_id = %d', $seo_description, $seo_keywords, $product_id ) );

				$product_row = $wpdb->get_row( $wpdb->prepare( 'SELECT post_id FROM ec_product WHERE product_id = %d', $product_id ) );
				if ( $product_row && $product_row->post_id ) {
					$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'posts SET post_excerpt = %s WHERE ID = %d', $post_excerpt, $product_row->post_id ) );
					if ( !$featured_image ) {
						delete_post_thumbnail( $product_row->post_id );
					} else {
						set_post_thumbnail( $product_row->post_id, $featured_image );
					}
				}
			}
		}

		public function save_product_details_downloads() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_products' ) ) {
				global $wpdb;
				$product_id = (int) $_POST['product_id'];
				$is_download = (int) $_POST['is_download'];
				$is_amazon_download = (int) $_POST['is_amazon_download'];
				$amazon_key = sanitize_text_field( wp_unslash( $_POST['amazon_key'] ) );
				$download_file_name = sanitize_text_field( wp_unslash( $_POST['download_file_name'] ) );
				$maximum_downloads_allowed = (int) $_POST['maximum_downloads_allowed'];
				$download_timelimit_seconds = (int) $_POST['download_timelimit_seconds'];

				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET is_download = %d, is_amazon_download = %s, amazon_key = %s, download_file_name = %s, maximum_downloads_allowed = %s, download_timelimit_seconds = %s WHERE product_id = %d', $is_download, $is_amazon_download, $amazon_key, $download_file_name, $maximum_downloads_allowed, $download_timelimit_seconds, $product_id ) );
			}
		}

		public function verify_model_number( $model_number = '' ) {
			global $wpdb;
			if ( $model_number != '' ) {
				$possible_match = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_product.model_number FROM ec_product WHERE ec_product.model_number = %s', $model_number ) );
				if ( $possible_match )
					return false;
			} else {
				$product_id = (int) $_POST['product_id'];
				$current_model = $wpdb->get_var( $wpdb->prepare( 'SELECT ec_product.model_number FROM ec_product WHERE ec_product.product_id = %d', $product_id ) );
				$model_number = sanitize_text_field( wp_unslash( $_POST['model_number'] ) );
				if ( ! preg_match( '/^[a-zA-Z0-9-\/\_]*$/', $model_number ) ) {
					return false;
				} else if ( $product_id == '0' || $current_model != $model_number ) {
					$possible_match = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_product.model_number FROM ec_product WHERE ec_product.model_number = %s', $model_number ) );
					if ( $possible_match ) {
						return false;
					}
				}
			}
			return true;
		}

		public function run_importer() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return false;
			}
			global $wpdb;
			$this->db = $wpdb;
			$this->error_list = '';
			$this->product_id_index = -1;
			$this->post_id_index = -1;
			$this->model_number_index = -1;
			$this->title_index = -1;
			$this->activate_in_store_index = -1;
			$this->is_subscription_index = -1;
			$this->bill_period_index = -1;
			$this->bill_length_index = -1;
			$this->trial_period_index = -1;
			$this->use_advanced_optionset_index = -1;
			$this->use_both_option_types_index = -1;
			$this->advanced_option_ids_index = -1;
			$this->categories_index = -1;
			$this->price_tiers_index = -1;
			$this->b2b_prices_index = -1;
			$this->limit = 20;

			ini_set( 'auto_detect_line_endings', '1' );

			require_once( 'Encoding.php' );

			if ( isset( $_POST['import_file_url'] ) ) {

				set_time_limit( 500 );

				$file_path = get_attached_file( (int) $_POST['import_file_url'] );
				if ( ! $file_path ) {
					echo esc_attr__( 'Invalid file path.', 'wp-easycart' );
					return;
				}

				$file_type = wp_check_filetype( $file_path );
				if ( ! $file_type || ! isset( $file_type['ext'] ) || ! isset( $file_type['type'] ) || 'csv' != $file_type['ext'] || 'text/csv' != $file_type['type'] ) {
					echo esc_attr__( 'Invalid file type.', 'wp-easycart' );
					return;
				}

				$file =  fopen( esc_url_raw( $file_path ), 'r' );
				if ( ! $file ) {
					echo esc_attr__( 'Could not open your import file.', 'wp-easycart' );
					return;
				}

				$valid_product_ids = array();
				$existing_model_numbers = array();
				$valid_product_ids_result = $this->db->get_results( 'SELECT product_id FROM ec_product', ARRAY_N );
				$existing_model_numbers_result = $this->db->get_results( 'SELECT model_number FROM ec_product', ARRAY_N );

				foreach ( $valid_product_ids_result as $product_id ) {
					$valid_product_ids[] = $product_id[0];
				}

				foreach ( $existing_model_numbers_result as $model_number ) {
					$existing_model_numbers[] = $model_number[0];
				}

				$valid_headers_result = $this->db->get_results( 'SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME`="ec_product"', ARRAY_N );
				$valid_headers = array();
				foreach ( $valid_headers_result as $header ) {
					$valid_headers[] = $header[0];
				}
				$valid_headers[] = 'advanced_option_ids';
				$this->headers = fgetcsv( $file );

				for ( $i = 0; $i < count( $this->headers ); $i++ ) {

					$this->headers[ $i ] = trim( $this->headers[ $i ] );

					if ( $this->headers[ $i ] == chr(0xEF) . chr(0xBB) . chr(0xBF) . 'product_id' || $this->headers[ $i ] == 'product_id' ) { // do not add product id to list
						$this->product_id_index = $i;

					} else if ($this->headers[ $i ] == 'post_id' ) { // do not add post id to list
						$this->post_id_index = $i;

					} else if ($this->headers[ $i ] == 'activate_in_store' ) { // do not add post id to list
						$this->activate_in_store_index = $i;

					} else if ($this->headers[ $i ] == 'model_number' ) { // use to check for errors
						$this->model_number_index = $i;

					} else if ($this->headers[ $i ] == 'title' ) { // use to check for errors
						$this->title_index = $i;

					} else if ($this->headers[ $i ] == 'price' ) { // use to check for errors
						$this->price_index = $i;

					} else if ($this->headers[ $i ] == 'list_price' ) { // use to check for errors
						$this->list_price_index = $i;

					} else if ($this->headers[ $i ] == 'is_subscription_item' ) { // use to check for errors
						$this->is_subscription_index = $i;

					} else if ($this->headers[ $i ] == 'subscription_bill_period' ) { // use to check for errors
						$this->bill_period_index = $i;

					} else if ($this->headers[ $i ] == 'subscription_bill_length' ) { // use to check for errors
						$this->bill_length_index = $i;

					} else if ($this->headers[ $i ] == 'trial_period_days' ) { // use to check for errors
						$this->trial_period_index = $i;

					} else if ($this->headers[ $i ] == 'use_advanced_optionset' ) { // use to check for errors
						$this->use_advanced_optionset_index = $i;

					} else if ($this->headers[ $i ] == 'use_both_option_types' ) { // use to check for errors
						$this->use_both_option_types_index = $i;

					} else if ($this->headers[ $i ] == 'advanced_option_ids' ) { // use to check for errors
						$this->advanced_option_ids_index = $i;

					} else if ($this->headers[ $i ] == 'categories' ) { // use to check for errors
						$this->categories_index = $i;

					} else if ($this->headers[ $i ] == 'price_tiers' ) { // use to check for errors
						$this->price_tiers_index = $i;

					} else if ($this->headers[ $i ] == 'b2b_prices' ) { // use to check for errors
						$this->b2b_prices_index = $i;

					} else if ( ! in_array( $this->headers[ $i ], $valid_headers ) ) { // error, invalid column
						echo sprintf( esc_attr__( 'You have an invalid column header at column %d (value %s), please remove or correct the label of that column to continue.', 'wp-easycart' ), esc_attr( $i ), esc_attr( $this->headers[ $i ] ) );

					}

				}

				if ( $this->product_id_index == -1 ) {
					echo esc_attr__( 'Missing `product_id` Key field! Values for additions should be 0, updates should be the exported product_id value.', 'wp-easycart' );
				}

				if ( $this->model_number_index == -1 ) {
					echo esc_attr__( 'Missing `model_number` Key field! Values must be unique from other imported products and those products already in your store.', 'wp-easycart' );
				}

				$insert_sql = 'INSERT INTO ec_product(';
				$update_sql = 'UPDATE ec_product SET ';

				$first = true;

				for ( $i = 0; $i < count( $this->headers ); $i++ ) {

					if ( $i != $this->product_id_index && $i != $this->post_id_index && $i != $this->advanced_option_ids_index && $i != $this->categories_index && $i != $this->price_tiers_index && $i != $this->b2b_prices_index ) { // Skip rows with product id and post id
						if ( ! $first ) {
							$insert_sql .= ',';
							$update_sql .= ',';
						}

						$insert_sql .= '`' . $this->headers[ $i ] . '`';
						$update_sql .= '`' . $this->headers[ $i ] . '`=%s';
						$first = false;
					}
				}

				$insert_sql .= ', `post_id`) VALUES(';

				$first = true;

				for ( $i = 0; $i < count( $this->headers ); $i++ ) {
					if ( $i != $this->product_id_index && $i != $this->post_id_index && $i != $this->advanced_option_ids_index && $i != $this->categories_index && $i != $this->price_tiers_index && $i != $this->b2b_prices_index ) { // Skip rows with product id and post id
						if ( ! $first ) {
							$insert_sql .= ',';
						}
						$insert_sql .= '%s';
						$first = false;
					}
				}

				$insert_sql .= ',%d)';
				$update_sql .= ' WHERE ec_product.product_id = %s';

				/* Start through the rows */
				$current_iteration = 0;
				$eof_reached = false;

				while( ! feof( $file ) && ! $eof_reached ) {

					$rows = array();

					for ( $current_row = 0; ! feof( $file ) && ! $eof_reached && $current_row < $this->limit; $current_row++ ) {

						$this_row = fgetcsv( $file );

						if ( ! is_array( $this_row ) || ! isset( $this_row[ $this->model_number_index ] ) || strlen( trim( $this_row[ $this->model_number_index ] ) ) <= 0 ) {
							$eof_reached = true;

						} else {
							$rows[] = $this_row;

						}

					}

					for ( $i = 0; $i < count( $rows ); $i++ ) {

						$product_id = $rows[ $i ][ $this->product_id_index ];
						$post_id = ( -1 != $this->post_id_index && isset( $rows[ $i ][ $this->post_id_index ] ) ) ? $rows[ $i ][ $this->post_id_index ] : -1;
						$model_number = $rows[ $i ][ $this->model_number_index ];

						if ( $rows[ $i ][ $this->product_id_index ] != 0 && $rows[ $i ][ $this->product_id_index ] != '' ) { // product_id is available

							if ( ! in_array( $product_id, $valid_product_ids ) ) {

								$this->error_list .= sprintf( __( 'Product %s on line %s failed to update, invalid product_id (if you are trying to add a new product use 0 for the product_id)', 'wp-easycart' ), $product_id, ( ( $current_iteration * $this->limit ) + ($i+1) ) ) . "\r";

							} else {

								$existing_model_numbers[] = $model_number;

								$update_vals = array();
								for ( $j = 0; $j < count( $rows[ $i ] ); $j++ ) {
									if ( $j != $this->product_id_index && $j != $this->post_id_index && $j != $this->advanced_option_ids_index && $j != $this->categories_index && $j != $this->price_tiers_index && $j != $this->b2b_prices_index ) {
										$rows[ $i ][ $j ] = html_entity_decode( preg_replace( "/U\+([0-9A-F]{4})/", "&#x\\1;", $rows[ $i ][ $j ] ), ENT_NOQUOTES, 'UTF-8' );
										if ( $j == $this->price_index || $j == $this->list_price_index ) {
											//$update_vals[] = \ForceUTF8\Encoding::fixUTF8( str_replace( ',', '', $rows[ $i ][ $j ] ) );
											$update_vals[] = str_replace( ',', '', $rows[ $i ][ $j ] );
										} else if ( $j == $this->model_number_index ) {
											$chars = "!@#$%^&*()+={}[]|\'\";:,<.>/?`~*";
											$pattern = "/[".preg_quote($chars, "/")."]/";
											$update_vals[] = preg_replace( $pattern, '', $rows[ $i ][ $j ] );
										} else {
											//$update_vals[] = \ForceUTF8\Encoding::fixUTF8( $rows[ $i ][ $j ] );
											$update_vals[] = $rows[ $i ][ $j ];
										}
									}
								}
								$update_vals[] = $product_id;

								$result = $this->db->query( $this->db->prepare( $update_sql, $update_vals ) );
								if ( $result === false ) {
									$this->error_list .= sprintf( __( 'Product on line %s failed to update due to an error.', 'wp-easycart' ), ( ( $current_iteration * $this->limit ) + ($i+1) ) ) . '<br />';
									echo esc_attr( $this->error_list );
									//die();
								}

								$status = false;
								if ( -1 != $this->activate_in_store_index && isset( $rows[ $i ][ $this->activate_in_store_index ] ) && $rows[ $i ][ $this->activate_in_store_index ] ) {
									$status = 'publish';
								} else if ( -1 != $this->activate_in_store_index && isset( $rows[ $i ][ $this->activate_in_store_index ] ) && ! $rows[ $i ][ $this->activate_in_store_index ] ) {
									$status = 'private';
								}

								if ( -1 != $post_id ) {
									$post_sql_items = array( '[ec_store modelnumber="' . $rows[ $i ][ $this->model_number_index ] . '"]' );
									$post_sql = 'UPDATE ' . $wpdb->prefix . 'posts SET post_content = %s';
									if ( $status ) {
										$post_sql .= ', post_status = %s';
										$post_sql_items[] = $status;
									}
									if ( -1 != $this->title_index && isset( $rows[ $i ][ $this->title_index ] ) ) {
										$post_sql .= ', post_title = %s';
										$post_sql_items[] = wp_easycart_language()->convert_text( $rows[ $i ][ $this->title_index ] );
									}
									$post_sql .= ', post_modified = NOW(), post_modified_gmt = UTC_TIMESTAMP() WHERE ID = %d';
									$post_sql_items[] = $post_id;
									$wpdb->query( $wpdb->prepare( $post_sql, $post_sql_items ) );
								}

								if ( ( $this->use_advanced_optionset_index != -1 && $this->advanced_option_ids_index != -1 && $rows[ $i ][ $this->use_advanced_optionset_index ] == '1' ) || ( $this->use_both_option_types_index != -1 && $this->use_both_option_types_index != -1 && $rows[ $i ][ $this->use_both_option_types_index ] == '1' ) ) {
									$advanced_options_current = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_option_to_product WHERE product_id = %d', $product_id ) );
									$advanced_option_ids_string = str_replace( ' ', '', $rows[ $i ][ $this->advanced_option_ids_index ] );
									$advanced_option_ids = explode( ',', $advanced_option_ids_string );
									// Remove options no longer in list
									for ( $j = 0; $j < count( $advanced_options_current ); $j++ ) {
										if ( ! in_array( $advanced_options_current[$j]->option_id, $advanced_option_ids ) ) {
											$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_option_to_product WHERE option_id = %d AND product_id = %d', $advanced_options_current[$j]->option_id, $product_id ) );
										}
									}
									// Add new options
									for ( $j = 0; $j < count( $advanced_option_ids ); $j++ ) {
										$found = false;
										for ( $k = 0; $k < count( $advanced_options_current ); $k++ ) {
											if ( $advanced_options_current[$k]->option_id == $advanced_option_ids[$j] ) {
												$found = true;
											}
										}
										if ( ! $found ) {
											$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_option_to_product( option_id, product_id ) VALUES( %d, %d )', $advanced_option_ids[$j], $product_id ) );
											do_action( 'wp_easycart_option_to_product_created', (int) $advanced_option_ids[$j], (int) $product_id );
										}
									}
								}

								if ( $this->categories_index != -1 ) {
									$categories_ids_string = str_replace( ' ', '', $rows[ $i ][ $this->categories_index ] );
									$categories_ids = explode( ',', $categories_ids_string );
									$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_categoryitem WHERE product_id = %d;', $product_id ) );
									if ( count( $categories_ids ) > 0 ) {
										$sql = 'INSERT INTO ec_categoryitem( product_id, category_id ) VALUES';
										for ( $j = 0; $j < count( $categories_ids ); $j++ ) {
											if ( $j > 0 ) {
												$sql .= ',';
											}
											$sql .= $wpdb->prepare( '( %d, %d)', $product_id, $categories_ids[ $j ] );
										}
										$wpdb->query( $sql );
									}
								}

								if ( -1 != $this->price_tiers_index ) {
									$price_tiers_string = str_replace( ' ', '', $rows[ $i ][ $this->price_tiers_index ] );
									$price_tiers_items = explode( ',', $price_tiers_string );
									$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_pricetier WHERE product_id = %d;', $product_id ) );
									if ( count( $price_tiers_items ) > 0 ) {
										$sql = 'INSERT INTO ec_pricetier( product_id, quantity, price ) VALUES';
										for ( $j = 0; $j < count( $price_tiers_items ) - 1; $j+=2 ) {
											if ( $j > 0 ) {
												$sql .= ',';
											}
											$sql .= $wpdb->prepare( '( %d, %s, %s)', $product_id, $price_tiers_items[ $j ], $price_tiers_items[ $j + 1 ] );
										}
										$wpdb->query( $sql );
									}
								}

								if ( -1 != $this->b2b_prices_index ) {
									$b2b_prices_string = str_replace( ' ', '', $rows[ $i ][ $this->b2b_prices_index ] );
									$b2b_prices_items = explode( ',', $b2b_prices_string );
									$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_roleprice WHERE product_id = %d;', $product_id ) );
									if ( count( $b2b_prices_items ) > 0 ) {
										$sql = 'INSERT INTO ec_roleprice( product_id, role_label, role_price ) VALUES';
										for ( $j = 0; $j < count( $b2b_prices_items ) - 1; $j+=2 ) {
											if ( $j > 0 ) {
												$sql .= ',';
											}
											$sql .= $wpdb->prepare( '( %d, %s, %s)', $product_id, $b2b_prices_items[ $j ], $b2b_prices_items[ $j + 1 ] );
										}
										$wpdb->query( $sql );
									}
								}
							}

						} else {

							if ( in_array( $model_number, $existing_model_numbers ) ) {

								$this->error_list .= sprintf( __( 'Product on line %s failed to update, duplicate model number listed for this product.', 'wp-easycart' ), ( ( $current_iteration * $this->limit ) + ($i+1) ) ) . "\r";

							} else {

								$existing_model_numbers[] = $model_number;
								$insert_vals = array();
								for ( $j = 0; $j < count( $rows[ $i ] ); $j++ ) {
									if ( $j != $this->product_id_index && $j != $this->post_id_index && $j != $this->advanced_option_ids_index ) {
										$rows[ $i ][ $j ] = html_entity_decode( preg_replace( "/U\+([0-9A-F]{4})/", "&#x\\1;", $rows[ $i ][ $j ] ), ENT_NOQUOTES, 'UTF-8' );
										if ( $j == $this->price_index || $j == $this->list_price_index ) {
											//$insert_vals[] = \ForceUTF8\Encoding::fixUTF8( str_replace( ',', '', $rows[ $i ][ $j ] ) );
											$insert_vals[] = str_replace( ',', '', $rows[ $i ][ $j ] );
										} else if ( $j == $this->model_number_index ) {
											$chars = "!@#$%^&*()+={}[]|\'\";:,<.>/?`~*";
											$pattern = "/[".preg_quote($chars, "/")."]/";
											$insert_vals[] = preg_replace( $pattern, '', $rows[ $i ][ $j ] );
										} else {
											$insert_vals[] = $rows[ $i ][ $j ];
										}
									}
								}

								// Insert WordPress Post
								if ( -1 != $this->activate_in_store_index && isset( $rows[ $i ][ $this->activate_in_store_index ] ) && $rows[ $i ][ $this->activate_in_store_index ] ) {
									$status = 'publish';
								} else {
									$status = 'private';
								}

								$post_slug = rand( 100000000000, 999999999999999 );
								$post_title = '';
								if ( -1 != $this->title_index && isset( $rows[ $i ][ $this->title_index ] ) ) {
									$post_slug = preg_replace( '/(\-+)/', '-', preg_replace( '/[^A-Za-z0-9\-]/', '', str_replace( ' ', '-', stripslashes_deep( strtolower( $rows[ $i ][ $this->title_index ] ) ) ) ) );
									$post_title =  wp_easycart_language()->convert_text( $rows[ $i ][ $this->title_index ] );
								}
								$store_page = get_permalink( get_option( 'ec_option_storepage' ) );
								if ( strstr( $store_page, '?' ) ) { 
									$guid = $store_page . '&model_number=' . $rows[ $i ][ $this->model_number_index ];
								} else if ( substr( $store_page, strlen( $store_page ) - 1 ) == '/' ) { 
									$guid = $store_page . $post_slug;
								} else {
									$guid = $store_page . '/' . $post_slug;
								}

								$guid = strtolower( $guid );
								$post_slug_orig = $post_slug;
								$guid_orig = $guid;
								$guid = $guid . '/';
								$k=1;
								while( $guid_check = $wpdb->get_row( $wpdb->prepare( 'SELECT ' . $wpdb->prefix . 'posts.guid FROM ' . $wpdb->prefix . 'posts WHERE ' . $wpdb->prefix . 'posts.guid = %s', $guid ) ) ) {
									$guid = $guid_orig . '-' . $k . '/';
									$post_slug = $post_slug_orig . '-' . $k;
									$k++;
								} 

								/* Manually Insert Post */
								$wpdb->query( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix . 'posts( post_content, post_status, post_title, post_name, guid, post_type, post_date, post_date_gmt, post_modified, post_modified_gmt, comment_status ) VALUES( %s, %s, %s, %s, %s, %s, NOW(), UTC_TIMESTAMP(), NOW(), UTC_TIMESTAMP(), "closed" )', '[ec_store modelnumber="' . $rows[ $i ][ $this->model_number_index ] . '"]', $status, $post_title, $post_slug, $guid, 'ec_store' ) );
								$post_id = $wpdb->insert_id;

								$insert_vals[] = $post_id;

								$this->db->query( $this->db->prepare( $insert_sql, $insert_vals ) );
								$product_id = $this->db->insert_id;

								if ( ! $product_id ) {
									wp_delete_post( $post_id, true );
									$this->error_list .= sprintf( __( 'Product on line %s never inserted', 'wp-easycart' ), ( ( $current_iteration * $this->limit ) + ($i+1) ) ) . "\r";
								}

								if ( $this->is_subscription_index != -1 && $product_id && ( get_option( 'ec_option_payment_process_method' ) == 'stripe' || get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) && $rows[ $i ][ $this->is_subscription_index ] == '1' ) {
									$stripe_plan = ( object ) array(
											'price' => $rows[ $i ][ $this->price_index ],
											'product_id' => $product_id,
											'title' => $rows[ $i ][ $this->title_index ]
									);
									if ( $this->bill_period_index != -1 )
										$stripe_plan->subscription_bill_period = $rows[ $i ][ $this->bill_period_index ];
									else
										$stripe_plan->subscription_bill_period = 'M';
									if ( $this->bill_length_index != -1 )
										$stripe_plan->subscription_bill_length = $rows[ $i ][ $this->bill_length_index ];
									else
										$stripe_plan->subscription_bill_length = 1;
									if ( $this->trial_period_index != -1 )
										$stripe_plan->trial_period_days = $rows[ $i ][ $this->trial_period_index ];
									else
										$stripe_plan->trial_period_days = 0;

									if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' )
										$stripe = new ec_stripe();
									else
										$stripe = new ec_stripe_connect();
									$response = $stripe->insert_plan( $stripe_plan );

								}

								// If Advanced Option, Lets Insert
								if ( ( $this->use_advanced_optionset_index != -1 && $this->advanced_option_ids_index != -1 && $rows[ $i ][ $this->use_advanced_optionset_index ] ) || ( $this->use_both_option_types_index != -1 && $this->use_both_option_types_index != -1 && $rows[ $i ][ $this->use_both_option_types_index ] ) ) {
									$advanced_option_ids_string = str_replace( ' ', '', $rows[ $i ][ $this->advanced_option_ids_index ] );
									$advanced_option_ids = explode( ',', $advanced_option_ids_string );
									if ( count( $advanced_option_ids ) > 0 && trim( $advanced_option_ids[0] ) != '' ) {
										$new_adv_opt_sql = 'INSERT INTO ec_option_to_product( option_id, product_id ) VALUES';
										for ( $adv_ins_index = 0; $adv_ins_index < count( $advanced_option_ids ); $adv_ins_index++ ) {
											if ( $adv_ins_index != 0 )
												$new_adv_opt_sql .= ',';
											$new_adv_opt_sql .= $wpdb->prepare( '(%d, %d)', $advanced_option_ids[ $adv_ins_index ], $product_id );
										}
										$wpdb->query( $new_adv_opt_sql );
										do_action( 'wp_easycart_option_to_product_created', (int) $advanced_option_ids[ $adv_ins_index ], (int) $product_id );
									}
								}

								// Process categories if available
								if ( $this->categories_index != -1 ) {
									$categories_ids_string = str_replace( ' ', '', $rows[ $i ][ $this->categories_index ] );
									$categories_ids = explode( ',', $categories_ids_string );
									$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_categoryitem WHERE product_id = %d;', $product_id ) );
									if ( count( $categories_ids ) > 0 ) {
										$sql = 'INSERT INTO ec_categoryitem( product_id, category_id ) VALUES';
										for ( $j = 0; $j < count( $categories_ids ); $j++ ) {
											if ( $j > 0 ) {
												$sql .= ',';
											}
											$sql .= $wpdb->prepare( '( %d, %d)', $product_id, $categories_ids[ $j ] );
										}
										$wpdb->query( $sql );
									}
								}

								if ( -1 != $this->price_tiers_index ) {
									$price_tiers_string = str_replace( ' ', '', $rows[ $i ][ $this->price_tiers_index ] );
									$price_tiers_items = explode( ',', $price_tiers_string );
									$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_pricetier WHERE product_id = %d;', $product_id ) );
									if ( count( $price_tiers_items ) > 0 ) {
										$sql = 'INSERT INTO ec_pricetier( product_id, quantity, price ) VALUES';
										for ( $j = 0; $j < count( $price_tiers_items ) - 1; $j+=2 ) {
											if ( $j > 0 ) {
												$sql .= ',';
											}
											$sql .= $wpdb->prepare( '( %d, %s, %s)', $product_id, $price_tiers_items[ $j ], $price_tiers_items[ $j + 1 ] );
										}
										$wpdb->query( $sql );
									}
								}

								if ( -1 != $this->b2b_prices_index ) {
									$b2b_prices_string = str_replace( ' ', '', $rows[ $i ][ $this->b2b_prices_index ] );
									$b2b_prices_items = explode( ',', $b2b_prices_string );
									$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_roleprice WHERE product_id = %d;', $product_id ) );
									if ( count( $b2b_prices_items ) > 0 ) {
										$sql = 'INSERT INTO ec_roleprice( product_id, role_label, role_price ) VALUES';
										for ( $j = 0; $j < count( $b2b_prices_items ) - 1; $j+=2 ) {
											if ( $j > 0 ) {
												$sql .= ',';
											}
											$sql .= $wpdb->prepare( '( %d, %s, %s)', $product_id, $b2b_prices_items[ $j ], $b2b_prices_items[ $j + 1 ] );
										}
										$wpdb->query( $sql );
									}
								}

							}// model number duplicate check

						}// close check for insert or update

					} // Close iteration for loop

					unset( $rows );

					$current_iteration++;

				}

				unset( $this->headers );

				fclose( $file );

				if ( $this->error_list == '' ) {
					echo 'success' ;
				} else {
					echo esc_attr( $this->error_list );
				}


			} else {
				echo esc_attr__( 'No URL', 'wp-easycart' );
			}
			die();
		}

		function get_product_link( $product_id ) {
			global $wpdb;
			$product = $wpdb->get_row( $wpdb->prepare( 'SELECT post_id, model_number FROM ec_product WHERE product_id = %d', $product_id ) );
			if ( $product ) {
				if ( ! get_option( 'ec_option_use_old_linking_style' ) ) {
					return get_permalink( $product->post_id );
				} else {
					$storepageid = get_option( 'ec_option_storepage' );
					$store_page = get_permalink( $storepageid );
					if ( substr_count( $store_page, '?' ) )
						$permalink_divider = '&';
					else
						$permalink_divider = '?';
					return $store_page . $permalink_divider . 'model_number=' . $product->model_number;
				}
			} else {
				return '';
			}
		}

		public function save_product_advanced_option_order() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return false;
			}
			global $wpdb;
			$sort_order = (array) $_POST['sort_order']; // XSS OK. Forced array and each item sanitized.
			$product_id = (int) $_POST['product_id'];

			foreach ( $sort_order as $sort_item ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_option_to_product SET option_order = %d WHERE option_to_product_id = %d AND product_id = %d', 
					(int) $sort_item['order'],
					(int) $sort_item['id'],
					$product_id
				) );
			}
		}

		public function save_logic() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return false;
			}
			global $wpdb;
			$option_to_product_id = (int) $_POST['option_to_product_id'];
			$enabled = (bool) $_POST['enabled'];
			$show_field = (bool) $_POST['show_field'];
			$and_rules = wp_easycart_admin_verification()->filter_list( sanitize_text_field( wp_unslash( $_POST['and_rules'] ) ), array( 'AND', 'OR' ) );
			$rules = json_decode( stripslashes( $_POST['rules'] ) ); // XSS OK - Pass JSON Rules, Process, Sanitize Each Value, Then Store.
			$rules_clean = array();
			if ( $rules && is_array( $rules ) ) {
				foreach ( (array) $rules as $rule ) { // XSS OK. Forced array and each item sanitized.
					$rules_clean[] = (object) array(
						'option_id' => (int) $rule->option_id,
						'operator' => wp_easycart_admin_verification()->filter_list( sanitize_text_field( $rule->operator ), array( '=', '!=' ) ),
						'optionitem_id' => (int) $rule->optionitem_id,
						'optionitem_value' => sanitize_text_field( $rule->optionitem_value ),
					);
				}
			}

			$meta = (object) array(
				'enabled' => $enabled,
				'show_field' => $show_field,
				'and_rules' => $and_rules,
				'rules' => $rules_clean,
			);
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_option_to_product SET conditional_logic = %s WHERE option_to_product_id = %d', json_encode( $meta ), $option_to_product_id ) );
		}

		public function get_quick_product( $product_id ) {
			global $wpdb;
			$product = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_product WHERE product_id = %d', $product_id ) );
			$product->price = $GLOBALS['currency']->get_number_only( $product->price );
			$product->list_price = $GLOBALS['currency']->get_number_only( $product->list_price );
			return $product;
		}

		public function product_quick_update() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return false;
			}
			global $wpdb;
			$is_taxable = $vat_rate = $show_stock_quantity = $use_optionitem_quantity_tracking = $stock_quantity = 0;
			if ( $_POST['is_taxable'] == '1' || $_POST['is_taxable'] == '3' ) {
				$is_taxable = 1;
			}
			if ( $_POST['is_taxable'] == '2' || $_POST['is_taxable'] == '3' ) {
				$vat_rate = 1;
			}
			if ( $_POST['stock_option'] != '0' ) {
				$show_stock_quantity = 1;
				$stock_quantity = (int) $_POST['stock_quantity'];
			}
			if ( $_POST['stock_option'] == '2' ) {
				$use_optionitem_quantity_tracking = 1;
			}
			$sort_position = ( isset( $_POST['sort_position'] ) ) ? (int) $_POST['sort_position'] : 0;
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET 
				activate_in_store = %d, show_on_startup = %d, title = %s, model_number = %s, manufacturer_id = %d,
				price = %s, list_price = %s, image1 = %s, show_stock_quantity = %d, use_optionitem_quantity_tracking = %d, stock_quantity = %d, is_shippable = %d,
				weight = %s, length = %s, width = %s, height = %s, is_taxable = %d, vat_rate = %s, sort_position = %d
				WHERE product_id = %d',
				(int) $_POST['activate_in_store'], 
				(int) $_POST['show_on_startup'], 
				wp_easycart_escape_html( wp_unslash( $_POST['title'] ) ), // XSS OK.
				sanitize_text_field( wp_unslash( $_POST['model_number'] ) ),
				(int) $_POST['manufacturer_id'],
				wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['price'] ) ) ),
				wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['list_price'] ) ) ),
				sanitize_text_field( wp_unslash( $_POST['image1'] ) ),
				(int) $show_stock_quantity,
				(int) $use_optionitem_quantity_tracking,
				(int) $stock_quantity,
				(int) $_POST['is_shippable'],
				wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['weight'] ) ) ),
				wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['length'] ) ) ),
				wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['width'] ) ) ),
				wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['height'] ) ) ),
				$is_taxable, $vat_rate, $sort_position,
				(int) $_POST['product_id']
			) );
		}

		public function google_merchant_fields() {
			$upgrade_icon = 'dashicons-admin-generic';
			$upgrade_title = __( 'Google Merchant Setup', 'wp-easycart' );
			$upgrade_subtitle = '';
			$upgrade_checkbox_label = apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:5px;"></span>' ) . __( 'Enable Google Merchant on this product.', 'wp-easycart' );
			$upgrade_button_label = __( 'Save Setup', 'wp-easycart' );
			include( $this->upgrade_file );
		}

	}
endif; // End if class_exists check

function wp_easycart_admin_products() {
	return wp_easycart_admin_products::instance();
}
wp_easycart_admin_products();

add_action( 'wp_ajax_ec_admin_ajax_save_product_settings', 'ec_admin_ajax_save_product_settings' );
function ec_admin_ajax_save_product_settings() {
	wp_easycart_admin_products()->save_product_settings();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_product_list_display', 'ec_admin_ajax_save_product_list_display' );
function ec_admin_ajax_save_product_list_display() {	
	wp_easycart_admin_products()->save_product_list_display();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_customer_review_display', 'ec_admin_ajax_save_customer_review_display' );
function ec_admin_ajax_save_customer_review_display() {
	wp_easycart_admin_products()->save_customer_review_display();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_product_details_display', 'ec_admin_ajax_save_product_details_display' );
function ec_admin_ajax_save_product_details_display() {
	wp_easycart_admin_products()->save_product_details_display();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_price_display', 'ec_admin_ajax_save_price_display' );
function ec_admin_ajax_save_price_display() {
	wp_easycart_admin_products()->save_price_display();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_inventory_options', 'ec_admin_ajax_save_inventory_options' );
function ec_admin_ajax_save_inventory_options() {
	wp_easycart_admin_products()->save_inventory_options();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_product_details_basic', 'ec_admin_ajax_save_product_details_basic' );
function ec_admin_ajax_save_product_details_basic() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}
	global $wpdb;
	$result = wp_easycart_admin_products()->save_product_details_basic();
	if ( $_POST['product_id'] == '0' ) {
		$product_id = $result;
	} else {
		$product_id = (int) $_POST['product_id'];
	}
	$guid = $wpdb->get_var( $wpdb->prepare( 'SELECT ' . $wpdb->prefix . 'posts.guid FROM ec_product LEFT JOIN ' . $wpdb->prefix . 'posts ON ' . $wpdb->prefix . 'posts.ID = ec_product.post_id WHERE product_id = %d', $product_id ) );
	echo json_encode( array( 
		'product_id' => (int) $product_id, 
		'link' => wp_easycart_admin_products()->get_product_link( $product_id ),
		'post_slug' => basename( $guid )
	) );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_options', 'ec_admin_ajax_save_product_details_options' );
function ec_admin_ajax_save_product_details_options() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_details_options();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_product_details_add_advanced_option', 'ec_admin_ajax_product_details_add_advanced_option' );
function ec_admin_ajax_product_details_add_advanced_option() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	$option_to_product_id = wp_easycart_admin_products()->add_advanced_option();
	$product_id = (int) $_POST['product_id'];
	global $wpdb;
	$advanced_options = $wpdb->get_results( $wpdb->prepare( 'SELECT ec_option.*, ec_option_to_product.product_id, ec_option_to_product.option_to_product_id, ec_option_to_product.conditional_logic FROM ec_option_to_product, ec_option WHERE ec_option_to_product.product_id = %d AND ec_option.option_id = ec_option_to_product.option_id ORDER BY ec_option_to_product.option_order', $product_id ) );
	foreach ( $advanced_options as $advanced_option ) {
		echo '<div class="ec_admin_option_row" id="ec_admin_product_details_advanced_option_row_' . esc_attr( $advanced_option->option_to_product_id ) . '" data-id="' . esc_attr( $advanced_option->option_to_product_id ) . '"><span>' . esc_attr( $advanced_option->option_name ) . '</span><span>' . esc_attr( $advanced_option->option_type ) . '</span><span>' . ( $advanced_option->option_required ? 'Yes' : 'No' ) . '</span><span><a href="" onclick="return ec_admin_product_details_delete_advanced_option( \'' . esc_attr( $advanced_option->option_to_product_id ) . '\' );"><div class="dashicons-before dashicons-trash"></div></a></span>';
		do_action( 'wp_easycart_admin_product_advanced_option_row_end', $advanced_option );
		echo '</div>';
	}
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_product_details_delete_advanced_option', 'ec_admin_ajax_product_details_delete_advanced_option' );
function ec_admin_ajax_product_details_delete_advanced_option() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	global $wpdb;
	$option_to_product_id = (int) $_POST['option_to_product_id'];
	$product_id = $wpdb->get_var( $wpdb->prepare( 'SELECT product_id FROM ec_option_to_product WHERE option_to_product_id = %d', $option_to_product_id ) );
	wp_easycart_admin_products()->delete_advanced_option();
	$advanced_options = $wpdb->get_results( $wpdb->prepare( 'SELECT ec_option.*, ec_option_to_product.product_id, ec_option_to_product.option_to_product_id, ec_option_to_product.conditional_logic FROM ec_option_to_product, ec_option WHERE ec_option_to_product.product_id = %d AND ec_option.option_id = ec_option_to_product.option_id ORDER BY ec_option_to_product.option_order', $product_id ) );
	foreach ( $advanced_options as $advanced_option ) {
		echo '<div class="ec_admin_option_row" id="ec_admin_product_details_advanced_option_row_' . esc_attr( $advanced_option->option_to_product_id ) . '" data-id="' . esc_attr( $advanced_option->option_to_product_id ) . '"><span>' . esc_attr( $advanced_option->option_name ) . '</span><span>' . esc_attr( $advanced_option->option_type ) . '</span><span>' . ( $advanced_option->option_required ? 'Yes' : 'No' ) . '</span><span><a href="" onclick="return ec_admin_product_details_delete_advanced_option( \'' . esc_attr( $advanced_option->option_to_product_id ) . '\' );"><div class="dashicons-before dashicons-trash"></div></a></span>';
		do_action( 'wp_easycart_admin_product_advanced_option_row_end', $advanced_option );
		echo '</div>';
	}
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_images', 'ec_admin_ajax_save_product_details_images' );
function ec_admin_ajax_save_product_details_images() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_details_images();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_menus', 'ec_admin_ajax_save_product_details_menus' );
function ec_admin_ajax_save_product_details_menus() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_details_menus();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_product_details_add_category', 'ec_admin_ajax_product_details_add_category' );
function ec_admin_ajax_product_details_add_category() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	$categoryitem_id = wp_easycart_admin_products()->add_category();
	global $wpdb;
	$category = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_categoryitem.category_id, ec_category.category_name FROM ec_categoryitem, ec_category WHERE ec_categoryitem.categoryitem_id = %d AND ec_category.category_id = ec_categoryitem.category_id', $categoryitem_id ) );
	echo '<div class="ec_admin_category_row" id="ec_admin_product_details_category_row_' . esc_attr( $category->category_id ) . '"><span>' . esc_attr( $category->category_name ) . '</span><span><a href="" onclick="return ec_admin_product_details_delete_category( \'' . esc_attr( $category->category_id ) . '\' );"><div class="dashicons-before dashicons-trash"></div></a></span></div>';
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_product_details_delete_category', 'ec_admin_ajax_product_details_delete_category' );
function ec_admin_ajax_product_details_delete_category() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->delete_category();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_quantities', 'ec_admin_ajax_save_product_details_quantities' );
function ec_admin_ajax_save_product_details_quantities() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_details_quantities();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_product_details_add_optionitem_quantity', 'ec_admin_ajax_product_details_add_optionitem_quantity' );
function ec_admin_ajax_product_details_add_optionitem_quantity() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	$optionitemquantity_id = wp_easycart_admin_products()->add_optionitem_quantity();
	global $wpdb;
	$option_item_quantity = $wpdb->get_row( $wpdb->prepare( 'SELECT 
				ec_optionitemquantity.*, 
				optionitem1.optionitem_name as optionitem_name_1, 
				optionitem2.optionitem_name as optionitem_name_2, 
				optionitem3.optionitem_name as optionitem_name_3, 
				optionitem4.optionitem_name as optionitem_name_4, 
				optionitem5.optionitem_name as optionitem_name_5
			FROM 
				ec_optionitemquantity 
				LEFT JOIN ec_optionitem AS optionitem1 ON ( optionitem1.optionitem_id = ec_optionitemquantity.optionitem_id_1 )
				LEFT JOIN ec_optionitem AS optionitem2 ON ( optionitem2.optionitem_id = ec_optionitemquantity.optionitem_id_2 )
				LEFT JOIN ec_optionitem AS optionitem3 ON ( optionitem3.optionitem_id = ec_optionitemquantity.optionitem_id_3 )
				LEFT JOIN ec_optionitem AS optionitem4 ON ( optionitem4.optionitem_id = ec_optionitemquantity.optionitem_id_4 )
				LEFT JOIN ec_optionitem AS optionitem5 ON ( optionitem5.optionitem_id = ec_optionitemquantity.optionitem_id_5 )
			WHERE 
				ec_optionitemquantity.optionitemquantity_id = %d', $optionitemquantity_id ) );
	echo '<div id="ec_admin_product_details_optionitem_quantity_row_' . esc_attr( $option_item_quantity->optionitemquantity_id ) . '" class="ec_admin_opionitem_quantity_row"><label>';
				echo esc_attr( $option_item_quantity->optionitem_name_1 );
				if ( $option_item_quantity->optionitem_id_2 )
					echo ', ' . esc_attr( $option_item_quantity->optionitem_name_2 );
				if ( $option_item_quantity->optionitem_id_3 )
					echo ', ' . esc_attr( $option_item_quantity->optionitem_name_3 );
				if ( $option_item_quantity->optionitem_id_4 )
					echo ', ' . esc_attr( $option_item_quantity->optionitem_name_4 );
				if ( $option_item_quantity->optionitem_id_5 )
					echo ', ' . esc_attr( $option_item_quantity->optionitem_name_5 );

				echo '</label><input type="number" name="optionitem_quantity_' . esc_attr( $option_item_quantity->optionitemquantity_id ) . '" id="optionitem_quantity_' . esc_attr( $option_item_quantity->optionitemquantity_id ) . '" value="' . esc_attr( $option_item_quantity->quantity ) . '" onchange="return ec_admin_product_details_update_optionitem_quantity( \'' . esc_attr( $option_item_quantity->optionitemquantity_id ) . '\' )" /><span><a href="#" onclick="return ec_admin_product_details_delete_optionitem_quantity( \'' . esc_attr( $option_item_quantity->optionitemquantity_id ) . '\' )" title="' . esc_attr__( 'Delete', 'wp-easycart' ) . '"><div class="dashicons-before dashicons-trash"></div></a> <a href="#" onclick="return ec_admin_product_details_update_optionitem_quantity( \'' . esc_attr( $option_item_quantity->optionitemquantity_id ) . '\' )" title="' . esc_attr__( 'Save', 'wp-easycart' ) . '"><div class="dashicons-before dashicons-yes"></div></a>';
				echo '</div>';
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_product_details_update_optionitem_quantity', 'ec_admin_ajax_product_details_update_optionitem_quantity' );
function ec_admin_ajax_product_details_update_optionitem_quantity() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->update_optionitem_quantity();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_product_details_delete_optionitem_quantity', 'ec_admin_ajax_product_details_delete_optionitem_quantity' );
function ec_admin_ajax_product_details_delete_optionitem_quantity() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->delete_optionitem_quantity();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_pricing', 'ec_admin_ajax_save_product_details_pricing' );
function ec_admin_ajax_save_product_details_pricing() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_details_pricing();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_product_details_add_price_tier', 'ec_admin_ajax_product_details_add_price_tier' );
function ec_admin_ajax_product_details_add_price_tier() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	$pricetier_id = wp_easycart_admin_products()->add_price_tier();
	global $wpdb;
	$price_tier = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_pricetier.* FROM ec_pricetier WHERE pricetier_id = %d', $pricetier_id ) );
	echo '<div class="ec_admin_price_tier_row" id="ec_admin_product_details_price_tier_row_' . esc_attr( $price_tier->pricetier_id ) . '"><span><input type="number" value="' . esc_attr( $price_tier->quantity ) . '" id="ec_admin_product_details_price_tier_row_' . esc_attr( $price_tier->pricetier_id ) . '_quantity" onchange="ec_admin_product_details_edit_price_tier( \'' . esc_attr( $price_tier->pricetier_id ) . '\' );" /></span><span><input type="number" min="0" step=".001" value="' . esc_attr( number_format( $price_tier->price, 2, '.', '' ) ) . '" id="ec_admin_product_details_price_tier_row_' . esc_attr( $price_tier->pricetier_id ) . '_price" onchange="ec_admin_product_details_edit_price_tier( \'' . esc_attr( $price_tier->pricetier_id ) . '\' );" /></span><span><a href="" onclick="return ec_admin_product_details_delete_price_tier( \'' . esc_attr( $price_tier->pricetier_id ) . '\' );" title="' . esc_attr__( 'Delete', 'wp-easycart' ) . '"><div class="dashicons-before dashicons-trash"></div></a><a href="" onclick="return ec_admin_product_details_edit_price_tier( \'' . esc_attr( $price_tier->pricetier_id ) . '\' );" title="' . esc_attr__( 'Save', 'wp-easycart' ) . '"><div class="dashicons-before dashicons-yes"></div></a></span></div>';
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_product_details_update_price_tier', 'ec_admin_ajax_product_details_update_price_tier' );
function ec_admin_ajax_product_details_update_price_tier() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	$pricetier_id = wp_easycart_admin_products()->update_price_tier();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_product_details_delete_price_tier', 'ec_admin_ajax_product_details_delete_price_tier' );
function ec_admin_ajax_product_details_delete_price_tier() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->delete_price_tier();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_product_details_add_role_price', 'ec_admin_ajax_product_details_add_role_price' );
function ec_admin_ajax_product_details_add_role_price() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	$roleprice_id = wp_easycart_admin_products()->add_role_price();
	global $wpdb;
	$role_price = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_roleprice.* FROM ec_roleprice WHERE roleprice_id = %d', $roleprice_id ) );
	echo '<div class="ec_admin_role_price_row" id="ec_admin_product_details_role_price_row_' . esc_attr( $role_price->roleprice_id ) . '"><span>' . esc_attr( $role_price->role_label ) . '</span><span>' . esc_attr( $GLOBALS['currency']->get_currency_display( $role_price->role_price ) ) . '</span><span><a href="" onclick="return ec_admin_product_details_delete_role_price( \'' . esc_attr( $role_price->roleprice_id ) . '\' );"><div class="dashicons-before dashicons-trash"></div></a></span></div>';
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_product_details_delete_role_price', 'ec_admin_ajax_product_details_delete_role_price' );
function ec_admin_ajax_product_details_delete_role_price() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->delete_role_price();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_packaging', 'ec_admin_ajax_save_product_details_packaging' );
function ec_admin_ajax_save_product_details_packaging() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_details_packaging();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_shipping', 'ec_admin_ajax_save_product_details_shipping' );
function ec_admin_ajax_save_product_details_shipping() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_details_shipping();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_short_description', 'ec_admin_ajax_save_product_details_short_description' );
function ec_admin_ajax_save_product_details_short_description() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_details_short_description();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_specifications', 'ec_admin_ajax_save_product_details_specifications' );
function ec_admin_ajax_save_product_details_specifications() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_details_specifications();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_order_completed_note', 'ec_admin_ajax_save_product_details_order_completed_note' );
function ec_admin_ajax_save_product_details_order_completed_note() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_details_order_completed_note();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_order_completed_email_note', 'ec_admin_ajax_save_product_details_order_completed_email_note' );
function ec_admin_ajax_save_product_details_order_completed_email_note() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_details_order_completed_email_note();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_order_completed_details_note', 'ec_admin_ajax_save_product_details_order_completed_details_note' );
function ec_admin_ajax_save_product_details_order_completed_details_note() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_details_order_completed_details_note();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_tags', 'ec_admin_ajax_save_product_details_tags' );
function ec_admin_ajax_save_product_details_tags() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_details_tags();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_featured_products', 'ec_admin_ajax_save_product_details_featured_products' );
function ec_admin_ajax_save_product_details_featured_products() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_details_featured_products();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_general_options', 'ec_admin_ajax_save_product_details_general_options' );
function ec_admin_ajax_save_product_details_general_options() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_details_general_options();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_tax', 'ec_admin_ajax_save_product_details_tax' );
function ec_admin_ajax_save_product_details_tax() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_details_tax();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_deconetwork', 'ec_admin_ajax_save_product_details_deconetwork' );
function ec_admin_ajax_save_product_details_deconetwork() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_details_deconetwork();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_subscription', 'ec_admin_ajax_save_product_details_subscription' );
function ec_admin_ajax_save_product_details_subscription() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_details_subscription();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_seo', 'ec_admin_ajax_save_product_details_seo' );
function ec_admin_ajax_save_product_details_seo() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_details_seo();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_downloads', 'ec_admin_ajax_save_product_details_downloads' );
function ec_admin_ajax_save_product_details_downloads() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_details_downloads();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_get_optionitem_images_content', 'ec_admin_ajax_get_optionitem_images_content' );
function ec_admin_ajax_get_optionitem_images_content() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	global $wpdb;
	$optionitems = $wpdb->get_results( $wpdb->prepare( 'SELECT ec_optionitem.*, ec_optionitemimage.image1, ec_optionitemimage.image2, ec_optionitemimage.image3, ec_optionitemimage.image4, ec_optionitemimage.image5 FROM ec_optionitem LEFT JOIN ec_optionitemimage ON ( ec_optionitemimage.optionitem_id = ec_optionitem.optionitem_id AND ec_optionitemimage.product_id = %d ) WHERE option_id = %d ORDER BY optionitem_order ASC', (int) $_POST['product_id'], (int) $_POST['option_id'] ) );
	$advanced_options = $wpdb->get_results( 'SELECT * FROM ec_option WHERE option_type != "basic-combo" AND option_type != "basic-swatch" ORDER BY option_label ASC' );

	echo '<div id="ec_admin_add_new_optionitem_image_row">';
	echo '<label>' . esc_attr__( 'Choose Option', 'wp-easycart' ) . ':</label>';
	echo '<select name="optionitems_images" id="optionitems_images" onchange="ec_admin_product_details_update_optionitem_images();">';
	foreach ( $optionitems as $optionitem ) {
		echo '<option value="' . esc_attr( $optionitem->optionitem_id ) . '">' . esc_attr( $optionitem->optionitem_name ) . '</option>';
	}
	echo '</select>';
	echo '</div>';
	echo '<div id="optionitem_images_holder">';
	for ( $i = 0; $i < count( $optionitems ); $i++ ) {
		echo '<div class="ec_admin_optionitem_image_row';
		if ( 0 != $i ) {
			echo ' ec_admin_hidden';
		}
		echo '" id="ec_admin_product_details_optionitem_image_row_' . esc_attr( $optionitems[ $i ]->optionitem_id ) . '">';
		echo '<div class="ec_admin_product_details_optionitem_image_row_label">' . esc_attr__( 'Images for', 'wp-easycart' ) . ' ' . esc_attr( $optionitems[ $i ]->optionitem_name ) . '</div>';
		$fields = array(
			array(
				'name'				=> 'image1_' . $optionitems[ $i ]->optionitem_id,
				'type'				=> 'image_upload',
				'label'				=> __( 'Image 1', 'wp-easycart' ),
				'required' 			=> false,
				'validation_type' 	=> 'image',
				'visible'			=> true,
				'value'				=> $optionitems[ $i ]->image1
			),
			array(
				'name'				=> 'image2_' . $optionitems[ $i ]->optionitem_id,
				'type'				=> 'image_upload',
				'label'				=> __( 'Image 2', 'wp-easycart' ),
				'required' 			=> false,
				'validation_type' 	=> 'image',
				'visible'			=> true,
				'value'				=> $optionitems[ $i ]->image2
			),
			array(
				'name'				=> 'image3_' . $optionitems[ $i ]->optionitem_id,
				'type'				=> 'image_upload',
				'label'				=> __( 'Image 3', 'wp-easycart' ),
				'required' 			=> false,
				'validation_type' 	=> 'image',
				'visible'			=> true,
				'value'				=> $optionitems[ $i ]->image3
			),
			array(
				'name'				=> 'image4_' . $optionitems[ $i ]->optionitem_id,
				'type'				=> 'image_upload',
				'label'				=> __( 'Image 4', 'wp-easycart' ),
				'required' 			=> false,
				'validation_type' 	=> 'image',
				'visible'			=> true,
				'value'				=> $optionitems[ $i ]->image4
			),
			array(
				'name'				=> 'image5_' . $optionitems[ $i ]->optionitem_id,
				'type'				=> 'image_upload',
				'label'				=> __( 'Image 5', 'wp-easycart' ),
				'required' 			=> false,
				'validation_type' 	=> 'image',
				'visible'			=> true,
				'value'				=> $optionitems[ $i ]->image5
			)
		);
		$details = new wp_easycart_admin_details();
		$details->print_fields( $fields );
		echo '</div>';
	}
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_get_optionitem_quantity_content', 'ec_admin_ajax_get_optionitem_quantity_content' );
function ec_admin_ajax_get_optionitem_quantity_content() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	global $wpdb;
	$optionitems1 = array();
	$optionitems2 = array();
	$optionitems3 = array();
	$optionitems4 = array();
	$optionitems5 = array();
	if ( isset( $_POST['option1'] ) )
		$optionitems1 = $wpdb->get_results( $wpdb->prepare( 'SELECT ec_optionitem.* FROM ec_optionitem WHERE option_id = %d ORDER BY optionitem_order ASC', (int) $_POST['option1'] ) );
	if ( isset( $_POST['option2'] ) )
		$optionitems2 = $wpdb->get_results( $wpdb->prepare( 'SELECT ec_optionitem.* FROM ec_optionitem WHERE option_id = %d ORDER BY optionitem_order ASC', (int) $_POST['option2'] ) );
	if ( isset( $_POST['option3'] ) )
		$optionitems3 = $wpdb->get_results( $wpdb->prepare( 'SELECT ec_optionitem.* FROM ec_optionitem WHERE option_id = %d ORDER BY optionitem_order ASC', (int) $_POST['option3'] ) );
	if ( isset( $_POST['option4'] ) )
		$optionitems4 = $wpdb->get_results( $wpdb->prepare( 'SELECT ec_optionitem.* FROM ec_optionitem WHERE option_id = %d ORDER BY optionitem_order ASC', (int) $_POST['option4'] ) );
	if ( isset( $_POST['option5'] ) )
		$optionitems5 = $wpdb->get_results( $wpdb->prepare( 'SELECT ec_optionitem.* FROM ec_optionitem WHERE option_id = %d ORDER BY optionitem_order ASC', (int) $_POST['option5'] ) );

	$option_item_quantities = $wpdb->get_results( $wpdb->prepare( 'SELECT 
			ec_optionitemquantity.*, 
			optionitem1.optionitem_name as optionitem_name_1, 
			optionitem2.optionitem_name as optionitem_name_2, 
			optionitem3.optionitem_name as optionitem_name_3, 
			optionitem4.optionitem_name as optionitem_name_4, 
			optionitem5.optionitem_name as optionitem_name_5
		FROM 
			ec_optionitemquantity 
			LEFT JOIN ec_optionitem AS optionitem1 ON ( optionitem1.optionitem_id = ec_optionitemquantity.optionitem_id_1 )
			LEFT JOIN ec_optionitem AS optionitem2 ON ( optionitem2.optionitem_id = ec_optionitemquantity.optionitem_id_2 )
			LEFT JOIN ec_optionitem AS optionitem3 ON ( optionitem3.optionitem_id = ec_optionitemquantity.optionitem_id_3 )
			LEFT JOIN ec_optionitem AS optionitem4 ON ( optionitem4.optionitem_id = ec_optionitemquantity.optionitem_id_4 )
			LEFT JOIN ec_optionitem AS optionitem5 ON ( optionitem5.optionitem_id = ec_optionitemquantity.optionitem_id_5 )
		WHERE 
			ec_optionitemquantity.product_id = %d', 
	(int) $_POST['product_id'] ) );

	echo '<div id="ec_admin_add_new_optionitem_quantity_row"><h3>' . esc_attr__( 'Add New Quantity Item', 'wp-easycart' ) . ' <a href="admin.php?page=wp-easycart-products&subpage=products&product_id=' . esc_attr( (int) $_POST['product_id'] ) . '&ec_admin_form_action=export-option-item-quantities" target="_blank"' . wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' onclick="return show_pro_required();"' ) ) . '>' . esc_attr__( 'Export', 'wp-easycart' ) . wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:8px;"></span>' ) ) . '</a><form action="" method="POST" enctype="multipart/form-data" style="float:right; border:1px solid #CCC; padding:5px;"><input type="hidden" name="ec_admin_form_action" value="import-option-item-quantities" /><input type="hidden" name="product_id" id="product_id" value="' . esc_attr( (int) $_POST['product_id'] ) . '" /><input type="file" placeholder="' . esc_attr__( 'Choose Quantity File', 'wp-easycart' ) . '" name="import_file" /><input type="submit" value="' . esc_attr__( 'Import Quantities', 'wp-easycart' ) . '"' . wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' onclick="return show_pro_required();"' ) ) . ' />' . wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="float:right; color:#FC0; margin-top:8px;"></span>' ) ) . '</form></h3>';
	if ( count( $optionitems1 ) ) {
		echo '<select name="add_new_optionitem_quantity_1" id="add_new_optionitem_quantity_1" class="select2-basic">';
		echo '<option value="0">' . esc_attr__( 'No Selection', 'wp-easycart' ) . '</option>';
		foreach ( $optionitems1 as $optionitem ) {
			echo '<option value="' . esc_attr( $optionitem->optionitem_id ) . '">' . esc_attr( $optionitem->optionitem_name ) . '</option>';
		}
		echo '</select>';
	}

	if ( count( $optionitems2 ) ) {
		echo '<select name="add_new_optionitem_quantity_2" id="add_new_optionitem_quantity_2" class="select2-basic">';
		echo '<option value="0">' . esc_attr__( 'No Selection', 'wp-easycart' ) . '</option>';
		foreach ( $optionitems2 as $optionitem ) {
			echo '<option value="' . esc_attr( $optionitem->optionitem_id ) . '">' . esc_attr( $optionitem->optionitem_name ) . '</option>';
		}
		echo '</select>';
	}

	if ( count( $optionitems3 ) ) {
		echo '<select name="add_new_optionitem_quantity_3" id="add_new_optionitem_quantity_3" class="select2-basic">';
		echo '<option value="0">' . esc_attr__( 'No Selection', 'wp-easycart' ) . '</option>';
		foreach ( $optionitems3 as $optionitem ) {
			echo '<option value="' . esc_attr( $optionitem->optionitem_id ) . '">' . esc_attr( $optionitem->optionitem_name ) . '</option>';
		}
		echo '</select>';
	}

	if ( count( $optionitems4 ) ) {
		echo '<select name="add_new_optionitem_quantity_4" id="add_new_optionitem_quantity_4" class="select2-basic">';
		echo '<option value="0">' . esc_attr__( 'No Selection', 'wp-easycart' ) . '</option>';
		foreach ( $optionitems4 as $optionitem ) {
			echo '<option value="' . esc_attr( $optionitem->optionitem_id ) . '">' . esc_attr( $optionitem->optionitem_name ) . '</option>';
		}
		echo '</select>';
	}

	if ( count( $optionitems5 ) ) {
		echo '<select name="add_new_optionitem_quantity_5" id="add_new_optionitem_quantity_5" class="select2-basic">';
		echo '<option value="0">' . esc_attr__( 'No Selection', 'wp-easycart' ) . '</option>';
		foreach ( $optionitems5 as $optionitem ) {
			echo '<option value="' . esc_attr( $optionitem->optionitem_id ) . '">' . esc_attr( $optionitem->optionitem_name ) . '</option>';
		}
		echo '</select>';
	}
	$add_new_click_action = apply_filters( 'wp_easycart_admin_optionitem_quantity_add_click', 'show_pro_required' );
	$update_click_action = apply_filters( 'wp_easycart_admin_optionitem_quantity_update_click', 'show_pro_required' );
	$delete_click_action = apply_filters( 'wp_easycart_admin_optionitem_quantity_delete_click', 'show_pro_required' );
	echo '<input type="number" value="" placeholder="' . esc_attr__( 'Quantity', 'wp-easycart' ) . '" name="add_new_optionitem_quantity" id="add_new_optionitem_quantity" />';
	echo '<input type="button" value="' . esc_attr__( 'Add New', 'wp-easycart' ) . '" onclick="return ' . esc_attr( $add_new_click_action ) . '();" />' . wp_easycart_escape_html(apply_filters('wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="float:left; width:25px !important; color:#FC0; margin-top:15px;"></span>'));
	echo '</div>';
	echo '<div class="ec_admin_optionitem_quantity_header"><span>' . esc_attr__( 'Options', 'wp-easycart' ) . '</span><span>' . esc_attr__( 'Quantity', 'wp-easycart' ) . '</span><span></span></div>';
	echo '<div id="ec_admin_product_details_optionitem_quantities_holder">';
	if ( count( $option_item_quantities ) ) {
		for ( $i = 0; $i < count( $option_item_quantities ); $i++ ) {
			echo '<div id="ec_admin_product_details_optionitem_quantity_row_' . esc_attr( $option_item_quantities[ $i ]->optionitemquantity_id ) . '" class="ec_admin_opionitem_quantity_row"><label>';
			echo esc_attr( $option_item_quantities[ $i ]->optionitem_name_1 );
			if ( $option_item_quantities[ $i ]->optionitem_id_2 )
				echo ', ' . esc_attr( $option_item_quantities[ $i ]->optionitem_name_2 );
			if ( $option_item_quantities[ $i ]->optionitem_id_3 )
				echo ', ' . esc_attr( $option_item_quantities[ $i ]->optionitem_name_3 );
			if ( $option_item_quantities[ $i ]->optionitem_id_4 )
				echo ', ' . esc_attr( $option_item_quantities[ $i ]->optionitem_name_4 );
			if ( $option_item_quantities[ $i ]->optionitem_id_5 )
				echo ', ' . esc_attr( $option_item_quantities[ $i ]->optionitem_name_5 );

			echo '</label><input type="number" name="optionitem_quantity_' . esc_attr( $option_item_quantities[ $i ]->optionitemquantity_id ) . '" id="optionitem_quantity_' . esc_attr( $option_item_quantities[ $i ]->optionitemquantity_id ) . '" value="' . esc_attr( $option_item_quantities[ $i ]->quantity ) . '" onchange="return ' . esc_attr( $update_click_action ) . '( \'' . esc_attr( $option_item_quantities[ $i ]->optionitemquantity_id ) . '\' )" /><span><a href="#" onclick="return ' . esc_attr( $delete_click_action ). '( \'' . esc_attr( $option_item_quantities[ $i ]->optionitemquantity_id ) . '\' )" title="' . esc_attr__( 'Delete', 'wp-easycart' ) . '"><div class="dashicons-before dashicons-trash"></div></a> <a href="#" onclick="return ' . esc_attr( $update_click_action ) . '( \'' . esc_attr( $option_item_quantities[ $i ]->optionitemquantity_id ) . '\' )"><div class="dashicons-before dashicons-yes" title="' . esc_attr__( 'Save', 'wp-easycart' ) . '"></div></a>';
			echo '</div>';
		}
	} else {
		echo '<div id="ec_admin_no_optionitem_quantities">' . esc_attr__( 'No Option Item Quantities Setup', 'wp-easycart' ) . '</div>';
	}
	echo '</div>';
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_product_details_insert_manufacturer', 'ec_admin_ajax_product_details_insert_manufacturer' );
function ec_admin_ajax_product_details_insert_manufacturer() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-manufacturer-details' ) ) {
		return false;
	}

	global $wpdb;
	$result = wp_easycart_admin_manufacturers()->insert_manufacturer();
	$manufacturer_id = $result['manufacturer_id'];
	$manufacturer_row = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_manufacturer WHERE manufacturer_id = %d', $manufacturer_id ) );
	echo json_encode( $manufacturer_row );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_validate_model_number', 'ec_admin_ajax_validate_model_number' );
function ec_admin_ajax_validate_model_number() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	if ( wp_easycart_admin_products()->verify_model_number() ) {
		echo '1';
	} else {
		global $wpdb;
		$possible_match = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_product.model_number FROM ec_product WHERE ec_product.model_number = %s AND ec_product.product_id != %d', sanitize_text_field( wp_unslash( $_POST['model_number'] ) ), (int) $_POST['product_id'] ) );
		if ( $possible_match ) {
			echo '-1';
		} else {
			echo '0';
		}
	}
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_import_products', 'ec_admin_ajax_import_products' );
function ec_admin_ajax_import_products() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-start-import' ) ) {
		return false;
	}

	$import_results = wp_easycart_admin_products()->run_importer();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_new_quick_product', 'ec_admin_ajax_save_new_quick_product' );
function ec_admin_ajax_save_new_quick_product() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-quick-edit' ) ) {
		return false;
	}

	$result = wp_easycart_admin_products()->save_new_quick_product();
	echo json_encode( $result );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_new_optionset', 'ec_admin_ajax_save_new_optionset' );
function ec_admin_ajax_save_new_optionset() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-optionset-quick-edit' ) ) {
		return false;
	}

	$option_id = wp_easycart_admin_products()->save_new_optionset();
	echo json_encode( array( 'option_id' => $option_id ) );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_new_optionitem', 'ec_admin_ajax_save_new_optionitem' );
function ec_admin_ajax_save_new_optionitem() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-optionitem-quick-edit' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_new_optionitem();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_new_adv_optionset', 'ec_admin_ajax_save_new_adv_optionset' );
function ec_admin_ajax_save_new_adv_optionset() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-adv-optionset-quick-edit' ) ) {
		return false;
	}

	$option_id = wp_easycart_admin_products()->save_new_adv_optionset();
	echo json_encode( array( 'option_id' => $option_id ) );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_new_adv_optionitem', 'ec_admin_ajax_save_new_adv_optionitem' );
function ec_admin_ajax_save_new_adv_optionitem() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-adv-optionitem-quick-edit' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_new_adv_optionitem();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_advanced_option_order', 'ec_admin_ajax_save_product_advanced_option_order' );
function ec_admin_ajax_save_product_advanced_option_order() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_advanced_option_order();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_logic', 'ec_admin_ajax_save_product_details_logic' );
function ec_admin_ajax_save_product_details_logic() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_logic();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_get_product_quick_edit', 'ec_admin_ajax_get_product_quick_edit' );
function ec_admin_ajax_get_product_quick_edit() {
	$product = wp_easycart_admin_products()->get_quick_product( (int) $_POST['product_id'] );
	$product->title = htmlspecialchars_decode( $product->title );
	$product->image1 = htmlspecialchars_decode( $product->image1 );
	echo json_encode( $product );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_product_quick_update', 'ec_admin_ajax_product_quick_update' );
function ec_admin_ajax_product_quick_update() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-quick-edit' ) ) {
		return false;
	}

	wp_easycart_admin_products()->product_quick_update();
	$product = wp_easycart_admin_products()->get_quick_product( (int) $_POST['product_id'] );
	$product->title = htmlspecialchars_decode( $product->title );
	$product->image1 = htmlspecialchars_decode( $product->image1 );
	$product->unit_price_formatted = $GLOBALS['currency']->get_currency_display( $product->price );
	echo json_encode( $product );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_is_optionitem_images', 'ec_admin_ajax_save_product_details_is_optionitem_images' );
function ec_admin_ajax_save_product_details_is_optionitem_images() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	global $wpdb;
	$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET use_optionitem_images = 0 WHERE product_id = %d', $_POST['product_id'] ) );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_details_is_advanced_options', 'ec_admin_ajax_save_product_details_is_advanced_options' );
function ec_admin_ajax_save_product_details_is_advanced_options() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-product-details' ) ) {
		return false;
	}

	global $wpdb;
	$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET use_advanced_options = 0 WHERE product_id = %d', $_POST['product_id'] ) );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_product_settings_v2', 'ec_admin_ajax_save_product_settings_v2' );
function ec_admin_ajax_save_product_settings_v2() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-products' ) ) {
		return false;
	}

	wp_easycart_admin_products()->save_product_settings_v2();
	die();
}
