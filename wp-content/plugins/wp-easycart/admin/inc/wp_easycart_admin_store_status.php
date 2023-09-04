<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_store_status' ) ) :

	final class wp_easycart_admin_store_status {

		protected static $_instance = null;

		public $store_status_file;
		public $settings;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() { 
			$this->store_status_file			= EC_PLUGIN_DIRECTORY . '/admin/template/status/store-status/store-status.php';		
			$this->settings 					= EC_PLUGIN_DIRECTORY . '/admin/template/status/store-status/settings.php';

			add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );
			add_action( 'wpeasycart_admin_store_status', array( $this, 'load_store_status' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_repair_database' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'reset_store_permalinks' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'fix_category_permalinks' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'fix_product_permalinks' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'fix_gateway_log' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'fix_webhook_log' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'fix_data_folders' ) );
		}

		public function load_status() {
			include( $this->settings );
		}

		public function load_store_status() {
			include( $this->store_status_file );
		}

		public function ec_get_php_version() {
			return phpversion();
		}

		public function add_success_messages( $messages ) {
			if ( isset( $_GET['success'] ) && $_GET['success'] == 'database-repair-complete' ) {
				$messages[] = __( 'The database repair tool has completed. If you are still seeing errors, please contact WP EasyCart support for help.', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'database-repair-dismissed' ) {
				$messages[] = __( 'The database notice has been dismissed, but will continue to show in your store status. Please contact WP EasyCart support for help.', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'reset-store-permalinks' ) {
				$messages[] = __( 'Your store permalinks have been reset.', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'rebuild-store-permalinks' ) {
				$messages[] = __( 'Your store permalinks have been rebuilt.', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'fix-category-permalinks' ) {
				$messages[] = __( 'We have completed an attempt to fix your category permalinks.', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'fix-product-permalinks' ) {
				$messages[] = __( 'We have completed an attempt to fix your product permalinks.', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'fix-gateway-log' ) {
				$messages[] = __( 'Your gateway log size has been reduced.', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'fix-webhook-log' ) {
				$messages[] = __( 'Your webhook log size has been reduced.', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'fix-data-folders' ) {
				$messages[] = __( 'Your data folders have been repaired.', 'wp-easycart' );
			}
			return $messages;
		}

		public function process_repair_database() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_diagnostics' ) ) {
				return false;
			}
			if ( $_GET['ec_admin_form_action'] == 'repair-database' ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-repair-database' ) ) {
					$db_manager = new ec_db_manager();
					$db_manager->try_repair();
					wp_redirect( 'admin.php?page=wp-easycart-status&subpage=store-status&success=database-repair-complete' );
					die();
				}

			} else if ( $_GET['ec_admin_form_action'] == 'repair-database-data' ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-repair-database-data' ) ) {
					$db_manager = new ec_db_manager();
					$db_manager->install_base_data();
					wp_redirect( 'admin.php?page=wp-easycart-status&subpage=store-status&success=database-repair-complete' );
					die();
				}

			} else if ( $_GET['ec_admin_form_action'] == 'dismiss-database-error' ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-dismiss-database-error' ) ) {
					update_option( 'ec_option_db_version_verified', str_replace( '_', '.', EC_CURRENT_VERSION ) );
					wp_redirect( 'admin.php?page=wp-easycart-status&subpage=store-status&success=database-repair-dismissed' );
					die();
				}
			}
		}

		public function reset_store_permalinks() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_diagnostics' ) ) {
				return false;
			}
			if ( 'reset-store-permalinks' == $_GET['ec_admin_form_action'] ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-reset-store-permalinks' ) ) {
					$this->ec_reset_store_permalinks();
					if ( isset( $_GET['ec_reset_phase2'] ) ) {
						wp_redirect( 'admin.php?page=wp-easycart-status&subpage=store-status&success=rebuild-store-permalinks' );
					} else {
						wp_redirect( 'admin.php?page=wp-easycart-status&subpage=store-status&success=reset-store-permalinks' );
					}
					die();
				}
			}
		}

		public function fix_category_permalinks() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_diagnostics' ) ) {
				return false;
			}
			if ( 'fix-category-permalinks' == $_GET['ec_admin_form_action'] ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-fix-category-permalinks' ) ) {
					global $wpdb;
					$count_fixed = 0;
					$categories = $wpdb->get_results( "SELECT * FROM ec_category" );
					foreach ( $categories as $category ) {
						$post = get_post( $category->post_id );
						if ( ! $post ) {
							$insert_post_id = wp_insert_post(
								array(
									'post_content' => "[ec_store groupid=\"" . $category->category_id . "\"]",
									'post_status' => "publish",
									'post_title' => wp_easycart_language( )->convert_text( $category->category_name ),
									'post_type' => "ec_store",
								)
							);
							if ( $insert_post_id != 0 ) {
								$post_id = $insert_post_id;
								$wpdb->query( $wpdb->prepare( "UPDATE ec_category SET post_id = %d WHERE category_id = %d", $post_id, $category->category_id ) );
							}
						}
					}
					wp_redirect( 'admin.php?page=wp-easycart-status&subpage=store-status&success=fix-category-permalinks' );
					die();
				}
			}
		}

		public function fix_product_permalinks() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_diagnostics' ) ) {
				return false;
			}
			if ( 'fix-product-permalinks' == $_GET['ec_admin_form_action'] ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-fix-product-permalinks' ) ) {
					global $wpdb;
					$products = $wpdb->get_results( 'SELECT activate_in_store, post_id, title, product_id, model_number FROM ec_product' );
					foreach ( $products as $product ) {
						$post = get_post( $product->post_id );
						if ( ! $post ) {
							$insert_post_id = wp_insert_post(
								array(
									'post_content' => "[ec_store modelnumber=\"" . $product->model_number . "\"]",
									'post_status' => "publish",
									'post_title' => wp_easycart_language( )->convert_text( $product->title ),
									'post_type' => "ec_store",
								)
							);
							if ( $insert_post_id != 0 ) {
								$post_id = $insert_post_id;
								$wpdb->query( $wpdb->prepare( "UPDATE ec_product SET post_id = %d WHERE product_id = %d", $post_id, $product->product_id ) );
							}

						} else {
							if ( $post->post_status == 'publish' && ! $product->activate_in_store ) {
								wp_update_post(
									array(
										'ID' => $post->ID,
										'post_status' => "private"
									)
								);

							} else if ( $post->post_status == 'private' && $product->activate_in_store ) {
								wp_update_post(
									array(
										'ID' => $post->ID,
										'post_status'	=> "publish"
									)
								);
							}
						}
					}
					wp_redirect( 'admin.php?page=wp-easycart-status&subpage=store-status&success=fix-product-permalinks' );
					die();
				}
			}
		}

		public function fix_gateway_log() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_diagnostics' ) ) {
				return false;
			}
			if ( 'fix-gateway-log' == $_GET['ec_admin_form_action'] ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-fix-gateway-log' ) ) {
					global $wpdb;
					$gateway_log_items = $wpdb->get_results( 'SELECT * FROM ec_response ORDER BY response_time DESC LIMIT 10000000000000 OFFSET 100' );
					$response_ids = array();
					foreach ( $gateway_log_items as $log_item ) {
						$response_ids[] = $log_item->response_id;
					}
					if ( count( $response_ids ) > 0 ) {
						$wpdb->query( 'DELETE FROM ec_response WHERE response_id IN (' . implode( ',', $response_ids ) . ')' );
					}
					wp_redirect( 'admin.php?page=wp-easycart-status&subpage=store-status&success=fix-gateway-log' );
					die();
				}
			}
		}

		public function fix_webhook_log() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_diagnostics' ) ) {
				return false;
			}
			if ( 'fix-webhook-log' == $_GET['ec_admin_form_action'] ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-fix-webhook-log' ) ) {
					global $wpdb;
					$webhook_items = $wpdb->get_results( 'SELECT * FROM ec_webhook ORDER BY webhook_id DESC LIMIT 10000000000000 OFFSET 1000' );
					$webhook_ids = array();
					foreach ( $webhook_items as $webhook_item ) {
						$webhook_ids[] = $webhook_item->webhook_id;
					}
					if ( count( $webhook_ids ) > 0 ) {
						$wpdb->query( 'DELETE FROM ec_webhook WHERE webhook_id IN (' . implode( ',', $webhook_ids ) . ')' );
					}
					wp_redirect( 'admin.php?page=wp-easycart-status&subpage=store-status&success=fix-webhook-log' );
					die();
				}
			}
		}

		public function fix_data_folders() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_diagnostics' ) ) {
				return false;
			}
			if ( 'fix-data-folders' == $_GET['ec_admin_form_action'] ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-fix-data-folders' ) ) {
					$this->ec_fix_data_folders();
					wp_redirect( 'admin.php?page=wp-easycart-status&subpage=store-status&success=fix-data-folders' );
					die();
				}
			}
		}

		public function database_check() {
			$db_manager = new ec_db_manager();
			$errors = $db_manager->verify_db();
			if ( count( $errors ) ) {
				return $errors;
			}
			return false;
		}

		public function settings_check() {
			global $wpdb;
			$results = $wpdb->get_results( 'SELECT * FROM ec_setting' );
			return ( $results && count( $results ) > 0 );
		}

		public function countries_check() {
			global $wpdb;
			$results = $wpdb->get_results( 'SELECT * FROM ec_country' );
			return ( $results && count( $results ) > 0 );
		}

		public function order_status_check() {
			global $wpdb;
			$results = $wpdb->get_results( 'SELECT * FROM ec_orderstatus' );
			return ( $results && count( $results ) > 0 );
		}

		public function timezone_check() {
			global $wpdb;
			$results = $wpdb->get_results( 'SELECT * FROM ec_timezone' );
			return ( $results && count( $results ) > 0 );
		}

		public function state_check() {
			global $wpdb;
			$results = $wpdb->get_results( 'SELECT * FROM ec_state' );
			return ( $results && count( $results ) > 0 );
		}

		public function zone_check() {
			global $wpdb;
			$results = $wpdb->get_results( 'SELECT * FROM ec_zone' );
			return ( $results && count( $results ) > 0 );
		}

		public function wpeasycart_is_data_folder_setup() {
			$folders = $this->wpeasycart_get_data_folder_list();
			foreach ( $folders as $dir ) {
				if ( !file_exists( $dir[0] ) || !is_dir( $dir[0] ) ) {
					return false;
				}
			}
			return true;
		}

		public function ec_get_data_folders_error() {
			$error = __( "You are missing the following wp-easycart-data folders", 'wp-easycart' ) . ": ";
			$folders = $this->wpeasycart_get_data_folder_list();
			$first = true;
			foreach ( $folders as $dir ) {
				if ( !file_exists( $dir[0] ) || !is_dir( $dir[0] ) ) {
					if ( !$first )
						$error .= ", ";
					$dir_split = explode( "wp-easycart-data/", $dir[0] );
					$error .= $dir_split[1];
					$first = false;
				}
			}
			return $error;
		}

		public function ec_fix_data_folders() {
			$folders = $this->wpeasycart_get_data_folder_list();
			foreach ( $folders as $dir ) {
				if ( !file_exists( $dir[0] ) || !is_dir( $dir[0] ) ) {
					mkdir( $dir[0], $dir[1] );
				}
			}
		}

		public function wpeasycart_get_data_folder_list() {
			$folders = array(
				array( 
					EC_PLUGIN_DATA_DIRECTORY . "/design/",
					"0755"
				),
				array( 
					EC_PLUGIN_DATA_DIRECTORY . "/design/theme/",
					"0755"
				),
				array( 
					EC_PLUGIN_DATA_DIRECTORY . "/design/theme/custom-theme/",
					"0755"
				),
				array( 
					EC_PLUGIN_DATA_DIRECTORY . "/design/layout/",
					"0755"
				),
				array( 
					EC_PLUGIN_DATA_DIRECTORY . "/design/layout/custom-layout/",
					"0755"
				),
				array( 
					EC_PLUGIN_DATA_DIRECTORY . "/products/",
					"0755"
				),
				array( 
					EC_PLUGIN_DATA_DIRECTORY . "/products/banners/",
					"0755"
				),
				array( 
					EC_PLUGIN_DATA_DIRECTORY . "/products/downloads/",
					"0751"
				),
				array( 
					EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/",
					"0755"
				),
				array( 
					EC_PLUGIN_DATA_DIRECTORY . "/products/pics2/",
					"0755"
				),
				array( 
					EC_PLUGIN_DATA_DIRECTORY . "/products/pics3/",
					"0755"
				),
				array( 
					EC_PLUGIN_DATA_DIRECTORY . "/products/pics4/",
					"0755"
				),
				array( 
					EC_PLUGIN_DATA_DIRECTORY . "/products/pics5/",
					"0755"
				),
				array( 
					EC_PLUGIN_DATA_DIRECTORY . "/products/swatches/",
					"0755"
				),
				array( 
					EC_PLUGIN_DATA_DIRECTORY . "/products/uploads/",
					"0751"
				)
			);
			return $folders;
		}

		public function wpeasycart_is_database_setup() {
			$db_manager = new ec_db_manager();
			return $db_manager->check_db();
		}

		public function ec_get_database_error() {
			$db_manager = new ec_db_manager();
			return $db_manager->get_db_errors();
		}

		public function ec_fix_database_errors() {
			$db_manager = new ec_db_manager();
			return $db_manager->install_db();
		}

		public function ec_is_store_page_setup() {
			$store_page_found = false;
			$store_is_match = false;
			$store_page_ids = array();
			$selected_store_id = get_option( 'ec_option_storepage' );
			$pages = get_pages();
			foreach ( $pages as $page ) {
				if ( strstr( $page->post_content, '[ec_store' ) ) {
					$store_page_ids[] = $page->ID;
					$store_page_found = true;
				}
			}
			if ( in_array( $selected_store_id, $store_page_ids ) ) {
				$store_is_match = true;
			}
			return ( $store_page_found && $store_is_match );
		}

		public function ec_get_store_page_error() {
			$store_page_found = false;
			$store_is_match = false;
			$store_page_ids = array();
			$selected_store_id = get_option( 'ec_option_storepage' );
			$pages = get_pages();
			foreach ( $pages as $page ) {
				if ( strstr( $page->post_content, '[ec_store' ) ) {
					$store_page_ids[] = $page->ID;
					$store_page_found = true;
				}
			}
			if ( in_array( $selected_store_id, $store_page_ids ) ) {
				$store_is_match = true;
			}
			if ( !$store_page_found ) {
				return __( "The shortcode [ec_store] was not found on any page. Please add [ec_store] to a WordPress page to correct this.", 'wp-easycart' );
			} else if ( !$store_is_match ) {
				return __( "You have not connected your store page with the EasyCart system. Please go to the setup page and select the correct page from the dropdown menu.", 'wp-easycart' );
			} else {
				return __( "Something went wrong, there may not be an error.", 'wp-easycart' );
			}
		}

		public function ec_is_cart_page_setup() {
			$cart_page_found = false;
			$cart_is_match = false;
			$cart_page_ids = array();
			$selected_cart_id = get_option( 'ec_option_cartpage' );
			$pages = get_pages();
			foreach ( $pages as $page ) {
				if ( strstr( $page->post_content, '[ec_cart' ) ) {
					$cart_page_ids[] = $page->ID;
					$cart_page_found = true;
				}
			}
			if ( in_array( $selected_cart_id, $cart_page_ids ) ) {
				$cart_is_match = true;
			}
			return ( $cart_page_found && $cart_is_match );
		}

		public function ec_get_cart_page_error() {
			$cart_page_found = false;
			$cart_is_match = false;
			$cart_page_ids = array();
			$selected_cart_id = get_option( 'ec_option_cartpage' );
			$pages = get_pages();
			foreach ( $pages as $page ) {
				if ( strstr( $page->post_content, '[ec_cart' ) ) {
					$cart_page_ids[] = $page->ID;
					$cart_page_found = true;
				}
			}
			if ( in_array( $selected_cart_id, $cart_page_ids ) ) {
				$cart_is_match = true;
			}
			if ( !$cart_page_found ) {
				return __( "The shortcode [ec_cart] was not found on any page. Please add [ec_cart] to a WordPress page to correct this.", 'wp-easycart' );
			} else if ( !$cart_is_match ) {
				return __( "You have not connected your cart page with the EasyCart system. Please go to the setup page and select the correct page from the dropdown menu.", 'wp-easycart' );
			} else {
				return __( "Something went wrong, there may not be an error.", 'wp-easycart' );
			}
		}

		public function ec_is_account_page_setup() {
			$account_page_found = false;
			$account_is_match = false;
			$account_page_ids = array();
			$selected_account_id = get_option( 'ec_option_accountpage' );
			$pages = get_pages();
			foreach ( $pages as $page ) {
				if ( strstr( $page->post_content, '[ec_account' ) ) {
					$account_page_ids[] = $page->ID;
					$account_page_found = true;
				}
			}
			if ( in_array( $selected_account_id, $account_page_ids ) )
				$account_is_match = true;

			return ( $account_page_found && $account_is_match );
		}

		public function ec_get_account_page_error() {
			$account_page_found = false;
			$account_is_match = false;
			$account_page_ids = array();
			$selected_account_id = get_option( 'ec_option_accountpage' );
			$pages = get_pages();
			foreach ( $pages as $page ) {
				if ( strstr( $page->post_content, '[ec_account' ) ) {
					$account_page_ids[] = $page->ID;
					$account_page_found = true;
				}
			}
			if ( in_array( $selected_account_id, $account_page_ids ) ) {
				$account_is_match = true;
			}
			if ( !$account_page_found ) {
				return __( "The shortcode [ec_account] was not found on any page. Please add [ec_account] to a WordPress page to correct this.", 'wp-easycart' );
			} else if ( !$account_is_match ) {
				return __( "You have not connected your account page with the EasyCart system. Please go to the setup page and select the correct page from the dropdown menu.", 'wp-easycart' );
			} else {
				return __( "Something went wrong, there may not be an error.", 'wp-easycart' );
			}
		}

		public function ec_get_basic_missing_settings() {
			$return_text = array();

			if ( get_option( 'ec_option_order_from_email' ) == "youremail@url.com" || get_option( 'ec_option_order_from_email' ) == "" )
				$return_text[] = __( "order from email address", 'wp-easycart' );

			if ( get_option( 'ec_option_password_from_email' ) == "youremail@url.com" || get_option( 'ec_option_password_from_email' ) == "" )
				$return_text[] = __( "password from email address", 'wp-easycart' );

			if ( get_option( 'ec_option_bcc_email_addresses' ) == "youremail@url.com" || get_option( 'ec_option_bcc_email_addresses' ) == "" )
				$return_text[] = __( "receipt copy admin email address", 'wp-easycart' );

			if ( get_option( 'ec_option_terms_link' ) == "http://yoursite.com/termsandconditions" || get_option( 'ec_option_terms_link' ) == "" )
				$return_text[] = __( "terms and conditions page link", 'wp-easycart' );

			if ( get_option( 'ec_option_privacy_link' ) == "http://yoursite.com/privacypolicy" || get_option( 'ec_option_privacy_link' ) == "" )
				$return_text[] = __( "privacy policy page link", 'wp-easycart' );

			return implode( ", ", $return_text );
		}



	////////////////////////////////////////////////
	//Shipping methods
	////////////////////////////////////////////////
		public function ec_get_shipping_method() {
			$db = new ec_db_admin();
			$setting_row = $db->get_settings();
			$settings = new ec_setting( $setting_row );
			$shipping_method = $settings->get_shipping_method();
			return $shipping_method;
		}

		public function ec_using_price_shipping() {
			$shipping_method = $this->ec_get_shipping_method();
			if ( $shipping_method == "price" )
				return true;
			else
				return false;
		}

		public function ec_price_shipping_setup() {
			$db = new ec_db_admin();
			$shippingrates = $db->get_shipping_data();
			$has_price_shipping = false;
			foreach ( $shippingrates as $shiprate ) {
				if ( $shiprate->is_price_based ) {
					$has_price_shipping = true;
					break;
				}
			}
			return $has_price_shipping;
		}

		public function ec_using_weight_shipping() {
			$shipping_method = $this->ec_get_shipping_method();
			if ( $shipping_method == "weight" )
				return true;
			else
				return false;
		}

		public function ec_weight_shipping_setup() {
			$db = new ec_db_admin();
			$shippingrates = $db->get_shipping_data();
			$has_weight_shipping = false;
			foreach ( $shippingrates as $shiprate ) {
				if ( $shiprate->is_weight_based ) {
					$has_weight_shipping = true;
					break;
				}
			}
			return $has_weight_shipping;
		}

		public function ec_using_quantity_shipping() {
			$shipping_method = $this->ec_get_shipping_method();
			if ( $shipping_method == "quantity" )
				return true;
			else
				return false;
		}

		public function ec_quantity_shipping_setup() {
			$db = new ec_db_admin();
			$shippingrates = $db->get_shipping_data();
			$has_quantity_shipping = false;
			foreach ( $shippingrates as $shiprate ) {
				if ( $shiprate->is_quantity_based ) {
					$has_quantity_shipping = true;
					break;
				}
			}
			return $has_quantity_shipping;
		}

		public function ec_using_percentage_shipping() {
			$shipping_method = $this->ec_get_shipping_method();
			if ( $shipping_method == "percentage" )
				return true;
			else
				return false;
		}

		public function ec_percentage_shipping_setup() {
			$db = new ec_db_admin();
			$shippingrates = $db->get_shipping_data();
			$has_percentage_shipping = false;
			foreach ( $shippingrates as $shiprate ) {
				if ( $shiprate->is_percentage_based ) {
					$has_percentage_shipping = true;
					break;
				}
			}
			return $has_percentage_shipping;
		}

		public function ec_using_method_shipping() {
			$shipping_method = $this->ec_get_shipping_method();
			if ( $shipping_method == "method" )
				return true;
			else
				return false;
		}

		public function ec_method_shipping_setup() {
			$db = new ec_db_admin();
			$shippingrates = $db->get_shipping_data();
			$has_method_shipping = false;
			foreach ( $shippingrates as $shiprate ) {
				if ( $shiprate->is_method_based ) {
					$has_method_shipping = true;
					break;
				}
			}
			return $has_method_shipping;
		}

		public function ec_using_live_shipping() {
			$shipping_method = $this->ec_get_shipping_method();
			if ( $shipping_method == "live" )
				return true;
			else
				return false;
		}

		public function ec_live_shipping_setup() {
			$db = new ec_db_admin();
			$shippingrates = $db->get_shipping_data();
			$has_live_shipping = false;
			foreach ( $shippingrates as $shiprate ) {
				if ( $shiprate->is_ups_based || $shiprate->is_usps_based || $shiprate->is_fedex_based || $shiprate->is_dhl_based || $shiprate->is_auspost_based || $shiprate->is_canadapost_based ) {
					$has_live_shipping = true;
					break;
				}
			}
			return $has_live_shipping;
		}

		public function ec_using_ups_shipping() {
			$db = new ec_db_admin();
			$shippingrates = $db->get_shipping_data();
			$has_ups_shipping = false;
			foreach ( $shippingrates as $shiprate ) {
				if ( $shiprate->is_ups_based ) {
					$has_ups_shipping = true;
					break;
				}
			}
			return $has_ups_shipping;
		}

		public function ec_ups_shipping_setup() {
			$ups_has_settings = false;
			$ups_setup = false;
			$ups_error_reason = 0;

			$db = new ec_db_admin();
			$setting_row = $db->get_settings();
			$settings = new ec_setting( $setting_row );

			if ( $setting_row->ups_access_license_number && $setting_row->ups_user_id && $setting_row->ups_password && $setting_row->ups_ship_from_zip && $setting_row->ups_shipper_number && $setting_row->ups_country_code && $setting_row->ups_weight_type ) {
				$ups_has_settings = true;

				// Run test of the settings
				$ups_class = new ec_ups( $settings );
				$ups_response = $ups_class->get_rate_test(
					"01",
					$setting_row->ups_ship_from_zip,
					$setting_row->ups_country_code,
					"1", 10, 10, 10, 10,
					array(
						(object) array(
							'quantity' => 1,
							'weight' => 1,
							'width' => 10,
							'length' => 10,
							'height' => 10,
							'is_shippable' => 1,
							'exclude_shippable_calculation' => 0,
							'unit_price' => 1,
						)
					)
				);
				if ( get_option( 'ec_option_ups_use_oauth' ) ) {
					if ( isset( $ups_response->RateResponse ) && isset( $ups_response->RateResponse->Response ) && isset( $ups_response->RateResponse->Response->ResponseStatus ) && isset( $ups_response->RateResponse->Response->ResponseStatus->Code ) && '1' == $ups_response->RateResponse->Response->ResponseStatus->Code ) {
						$ups_setup = true;
					} else {
						$ups_error_reason = esc_attr__( 'UPS is not connected properly. Check your WP EasyCart UPS setup.', 'wp-easycart' );
					}
				} else {
					$ups_response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $ups_response );
					try {
						$ups_xml = new SimpleXMLElement($ups_response);
						$body = $ups_xml->xpath('//soapenvBody');
						$body = $body[0];

						if ( !isset( $body->soapenvFault ) && $body->rateRateResponse->commonResponse->commonResponseStatus->commonCode == "1" ) {
							$ups_setup = true;
						} else {
							$ups_error_reason = $body->soapenvFault->detail->errErrors->errErrorDetail->errPrimaryErrorCode->errDescription;
						}
					} catch( Exception $e ) {
						$ups_error_reason = esc_attr__( 'UPS XML has occurred. Check your WP EasyCart UPS setup.', 'wp-easycart' );
					}
				}
			}

			return ( $ups_has_settings && $ups_setup );
		}

		public function ec_using_usps_shipping() {
			$db = new ec_db_admin();
			$shippingrates = $db->get_shipping_data();
			$has_usps_shipping = false;
			foreach ( $shippingrates as $shiprate ) {
				if ( $shiprate->is_usps_based ) {
					$has_usps_shipping = true;
					break;
				}
			}
			return $has_usps_shipping;
		}

		public function ec_usps_shipping_setup() {
			$usps_has_settings = false;
			$usps_setup = false;
			$usps_error_reason = 0;

			$db = new ec_db_admin();
			$setting_row = $db->get_settings();
			$settings = new ec_setting( $setting_row );

			if ( $setting_row->usps_user_name && $setting_row->usps_ship_from_zip ) {
				$usps_has_settings = true;
				// Run test of the settings
				$usps_class = new ec_usps( $settings );
				$usps_response = $usps_class->get_rate_test(
					"PRIORITY",
					$setting_row->usps_ship_from_zip,
					"US", "1", 0, 0, 0, 0,
					array(
						(object) array(
							'quantity' => 1,
							'weight' => 1,
							'width' => 1,
							'length' => 1,
							'height' => 1,
							'is_shippable' => 1,
							'exclude_shippable_calculation' => 0,
							'unit_price' => 1,
						)
					)
				);
				$usps_xml = new SimpleXMLElement( $usps_response );

				if ( $usps_xml->Number )
					$usps_error_reason = 1;
				else if ( $usps_xml->Package[0]->Error )
					$usps_error_reason = 2;
				else
					$usps_setup = true;
			}

			return ( $usps_has_settings && $usps_setup );
		}

		public function ec_using_fedex_shipping() {
			$db = new ec_db_admin();
			$shippingrates = $db->get_shipping_data();
			$has_fedex_shipping = false;
			foreach ( $shippingrates as $shiprate ) {
				if ( $shiprate->is_fedex_based ) {
					$has_fedex_shipping = true;
					break;
				}
			}
			return $has_fedex_shipping;
		}

		public function ec_fedex_shipping_setup() {
			$fedex_has_settings = false;
			$fedex_setup = false;
			$fedex_error_reason = 0;

			$db = new ec_db_admin();
			$setting_row = $db->get_settings();
			$settings = new ec_setting( $setting_row );

			if ( $setting_row->fedex_key && $setting_row->fedex_account_number && $setting_row->fedex_meter_number && $setting_row->fedex_password && $setting_row->fedex_ship_from_zip && $setting_row->fedex_weight_units && $setting_row->fedex_country_code ) {
				$fedex_has_settings = true;
				// Run test of the settings

				if ( $setting_row->fedex_weight_units != "LB" && $setting_row->fedex_weight_units != "KG" ) {
					$fedex_error_reason = 2;
				} else {
					$fedex_class = new ec_fedex( $settings );
					$fedex_response = $fedex_class->get_rate_test( "FEDEX_GROUND", $setting_row->fedex_ship_from_zip, $setting_row->fedex_country_code, "1", 10, 10, 10, 10, array( (object) array( 'quantity' => 1, 'weight' => 1, 'width' => 10, 'length' => 10, 'height' => 10, 'is_shippable' => 1, 'exclude_shippable_calculation' => 0 ) ) );

					if ( $fedex_response->HighestSeverity == 'FAILURE' || $fedex_response->HighestSeverity == 'ERROR' )
						if ( isset( $fedex_response->Notifications->Code ) )
							$fedex_error_reason = $fedex_response->Notifications->Code;
						else
							$fedex_error_reason = $fedex_response->Notifications[0]->Code;
					else
						$fedex_setup = true;
				}
			}

			return ( $fedex_has_settings && $fedex_setup );
		}

		public function ec_using_dhl_shipping() {
			$db = new ec_db_admin();
			$shippingrates = $db->get_shipping_data();
			$has_dhl_shipping = false;
			foreach ( $shippingrates as $shiprate ) {
				if ( $shiprate->is_dhl_based ) {
					$has_dhl_shipping = true;
					break;
				}
			}
			return $has_dhl_shipping;
		}

		public function ec_dhl_shipping_setup() {
			$dhl_has_settings = false;
			$dhl_setup = false;
			$dhl_error_reason = 0;

			$db = new ec_db_admin();
			$setting_row = $db->get_settings();
			$settings = new ec_setting( $setting_row );

			if ( $setting_row->dhl_site_id && $setting_row->dhl_password && $setting_row->dhl_ship_from_country && $setting_row->dhl_ship_from_zip && $setting_row->dhl_weight_unit ) {
				$dhl_has_settings = true;

				// Run test of the settings
				$dhl_class = new ec_dhl( $settings );
				$dhl_response = $dhl_class->get_rate_test( "N", $setting_row->dhl_ship_from_zip, $setting_row->dhl_ship_from_country, "1" );
				$dhl_xml = new SimpleXMLElement( $dhl_response );

				if ( $dhl_xml && $dhl_xml->Response && $dhl_xml->Response->Status && $dhl_xml->Response->Status->ActionStatus && $dhl_xml->Response->Status->ActionStatus == "Error" ) {
					$dhl_error_code = $dhl_xml->Response->Status->Condition->ConditionCode;
					$dhl_error_reason = $dhl_xml->Response->Status->Condition->ConditionData;
				} else if ( $dhl_xml && $dhl_xml->Response && $dhl_xml->Response->Note && count( $dhl_xml->Response->Note ) > 0 && $dhl_xml->Response->Note[0]->Status && $dhl_xml->Response->Note[0]->Status->Condition && $dhl_xml->Response->Note[0]->Status->Condition->ConditionData ) {
					$dhl_error_reason = $dhl_xml->Response->Note[0]->Status->Condition->ConditionData;
				} else
					$dhl_setup = true;
			}

			return ( $dhl_has_settings && $dhl_setup );
		}

		public function ec_using_auspost_shipping() {
			$db = new ec_db_admin();
			$shippingrates = $db->get_shipping_data();
			$has_auspost_shipping = false;
			foreach ( $shippingrates as $shiprate ) {
				if ( $shiprate->is_auspost_based ) {
					$has_auspost_shipping = true;
					break;
				}
			}
			return $has_auspost_shipping;
		}

		public function ec_auspost_shipping_setup() {
			$auspost_has_settings = false;
			$auspost_setup = false;
			$auspost_error_reason = 0;

			$db = new ec_db_admin();
			$setting_row = $db->get_settings();
			$settings = new ec_setting( $setting_row );

			if ( $setting_row->auspost_api_key && $setting_row->auspost_ship_from_zip ) {
				$auspost_has_settings = true;

				// Run test of the settings
				$auspost_class = new ec_auspost( $settings );
				$auspost_response = $auspost_class->get_rate_test( "AUS_PARCEL_EXPRESS", $setting_row->auspost_ship_from_zip, "AU", "1" );

				if ( !$auspost_response )
					$auspost_error_reason = "1";
				else
					$auspost_setup = true;
			}

			return ( $auspost_has_settings && $auspost_setup );
		}

		public function ec_using_canadapost_shipping() {
			$db = new ec_db_admin();
			$shippingrates = $db->get_shipping_data();
			$has_canadapost_shipping = false;
			foreach ( $shippingrates as $shiprate ) {
				if ( $shiprate->is_canadapost_based ) {
					$has_canadapost_shipping = true;
					break;
				}
			}
			return $has_canadapost_shipping;
		}

		public function ec_canadapost_shipping_setup() {
			$canadapost_has_settings = false;
			$canadapost_setup = false;
			$canadapost_error_reason = 0;

			$db = new ec_db_admin();
			$setting_row = $db->get_settings();
			$settings = new ec_setting( $setting_row );

			if ( $setting_row->canadapost_username && $setting_row->canadapost_password && $setting_row->canadapost_customer_number && $setting_row->canadapost_ship_from_zip ) {
				$canadapost_has_settings = true;

				// Run test of the settings
				$canadapost_class = new ec_canadapost( $settings );
				$canadapost_response = $canadapost_class->get_rate_test( "DOM.RP", $setting_row->canadapost_ship_from_zip, "CA", "1" );

				if ( !$canadapost_response )
					$canadapost_error_reason = "1";
				else
					$canadapost_setup = true;
			}

			return ( $canadapost_has_settings && $canadapost_setup );
		}

		public function ec_using_fraktjakt_shipping() {
			$shipping_method = $this->ec_get_shipping_method();
			if ( $shipping_method == "fraktjakt" )
				return true;
			else
				return false;
		}

		public function ec_fraktjakt_shipping_setup() {
			$fraktjakt_has_settings = false;
			$fraktjakt_setup = false;
			$fraktjakt_error_reason = 0;

			$db = new ec_db_admin();
			$setting_row = $db->get_settings();
			$settings = new ec_setting( $setting_row );

			if ( $setting_row->fraktjakt_customer_id != "" && $setting_row->fraktjakt_login_key != "" ) {
				$fraktjakt_has_settings = true;

				// Run test of the settings
				$fraktjakt_class = new ec_fraktjakt( $settings );
				$test_user = new ec_user( "" );
				$test_user->setup_shipping_info_data( "", "", "152-153 Fleet St", "", "London", "", "GB", "EC4A2DQ", "" );

				$fraktjakt_response = $fraktjakt_class->get_shipping_options_test( $test_user );
				$xml = new SimpleXMLElement( $fraktjakt_response );

				if ( isset( $xml->shipping_products ) && isset( $xml->shipping_products->shipping_product ) && count( $xml->shipping_products->shipping_product ) > 0 )
					$fraktjakt_setup = true;
				else
					$fraktjakt_error_reason = "1";

			}

			return ( $fraktjakt_has_settings && $fraktjakt_setup );
		}




		public function ec_using_no_tax() {
			$db = new ec_db_admin();
			$taxrates = $db->get_taxrates();
			if ( count( $taxrates ) > 0 )
				return false;
			else
				return true;
		}

		public function ec_using_state_tax() {
			$db = new ec_db_admin();
			$taxrates = $db->get_taxrates();
			foreach ( $taxrates as $taxrate ) {
				if ( $taxrate->tax_by_state ) {
					return true;
				}
			}
			return false;
		}

		public function ec_using_country_tax() {
			$db = new ec_db_admin();
			$taxrates = $db->get_taxrates();
			foreach ( $taxrates as $taxrate ) {
				if ( $taxrate->tax_by_country ) {
					return true;
				}
			}
			return false;
		}

		public function ec_using_global_tax() {
			$db = new ec_db_admin();
			$taxrates = $db->get_taxrates();
			foreach ( $taxrates as $taxrate ) {
				if ( $taxrate->tax_by_all ) {
					return true;
				}
			}
			return false;
		}

		public function ec_using_duty_tax() {
			$db = new ec_db_admin();
			$taxrates = $db->get_taxrates();
			foreach ( $taxrates as $taxrate ) {
				if ( $taxrate->tax_by_duty ) {
					return true;
				}
			}
			return false;
		}

		public function ec_using_vat_tax() {
			$db = new ec_db_admin();
			$taxrates = $db->get_taxrates();
			foreach ( $taxrates as $taxrate ) {
				if ( $taxrate->tax_by_vat ) {
					return true;
				}
			}
			return false;
		}

		public function ec_global_vat_setup() {
			$db = new ec_db_admin();
			$countries = $GLOBALS['ec_countries']->countries;
			foreach ( $countries as $country ) {
				if ( $country->vat_rate_cnt > 0 ) {
					return true;
				}
			}
			return false;
		}






		public function ec_no_payment_selected() {
			$manual_payment = get_option( 'ec_option_use_direct_deposit' );
			$affirm = get_option( 'ec_option_use_affirm' );
			$third_party = get_option( 'ec_option_payment_third_party' );
			$live_payment = get_option( 'ec_option_payment_process_method' );

			if ( $manual_payment || $affirm || $third_party || $live_payment )
				return false;
			else
				return true;
		}

		public function ec_manual_payment_selected() {
			$manual_payment = get_option( 'ec_option_use_direct_deposit' );
			if ( $manual_payment )
				return true;
			else
				return false;
		}

		public function ec_affirm_payment_selected() {
			$affirm = get_option( 'ec_option_use_affirm' );
			if ( $affirm )
				return true;
			else
				return false;
		}

		public function ec_third_party_payment_selected() {
			$third_party = get_option( 'ec_option_payment_third_party' );
			if ( $third_party && $third_party != "0" )
				return true;
			else
				return false;
		}

		public function ec_third_party_payment_setup() {
			$third_party = get_option( 'ec_option_payment_third_party' );
			if ( $third_party == "dwolla_thirdparty" ) {
				if ( get_option( 'ec_option_dwolla_thirdparty_key' ) != "" && get_option( 'ec_option_dwolla_thirdparty_secret' ) != "" )
					return true;
				else
					return false;
			} else if ( $third_party == "nets" ) {
				if ( get_option( 'ec_option_nets_merchant_id' ) != "" && get_option( 'ec_option_nets_token' ) != "" )
					return true;
				else
					return false;
			} else if ( $third_party == "payfast_thirdparty" ) {
				if ( get_option( 'ec_option_payfast_merchant_id' ) != "" && get_option( 'ec_option_payfast_merchant_key' ) != "" && get_option( 'ec_option_payfast_passphrase' ) != "" )
					return true;
				else
					return false;
			} else if ( $third_party == "payfort" ) {
				if ( get_option( 'ec_option_payfort_access_code' ) != "" && get_option( 'ec_option_payfort_merchant_id' ) != "" && get_option( 'ec_option_payfort_request_phrase' ) != "" && get_option( 'ec_option_payfort_currency_code' ) != "" )
					return true;
				else
					return false;
			} else if ( $third_party == "paypal" && get_option( 'ec_option_paypal_enable_pay_now' ) == "0" ) { // PayPal Standard
				if ( get_option( 'ec_option_paypal_email' ) != "" )
					return true;
				else
					return false;
			} else if ( $third_party == "paypal" && get_option( 'ec_option_paypal_enable_pay_now' ) == "1" && get_option( 'ec_option_paypal_use_sandbox' ) == '1' ) { // PayPal Express Sandbox
				if ( get_option( 'ec_option_paypal_sandbox_app_id' ) != "" && get_option( 'ec_option_paypal_sandbox_secret' ) != "" )
					return true;
				else if ( get_option( 'ec_option_paypal_enable_pay_now' ) && get_option( 'ec_option_paypal_sandbox_merchant_id' ) != '' )
					return true;
				else
					return false;
			} else if ( $third_party == "paypal" && get_option( 'ec_option_paypal_enable_pay_now' ) == "1" && get_option( 'ec_option_paypal_use_sandbox' ) == '0' ) { // PayPal Express Sandbox
				if ( get_option( 'ec_option_paypal_production_app_id' ) != "" && get_option( 'ec_option_paypal_production_secret' ) != "" )
					return true;
				else if ( get_option( 'ec_option_paypal_enable_pay_now' ) && get_option( 'ec_option_paypal_production_merchant_id' ) != '' )
					return true;
				else
					return false;
			} else if ( $third_party == "sagepay_paynow_za" ) {
				if ( get_option( 'ec_option_sagepay_paynow_za_service_key' ) != "" )
					return true;
				else
					return false;
			} else if ( $third_party == "paypal_advanced" ) {
				if ( get_option( 'ec_option_paypal_advanced_partner' ) != "" && get_option( 'ec_option_paypal_advanced_user' ) != "" && get_option( 'ec_option_paypal_advanced_vendor' ) != "" && get_option( 'ec_option_paypal_advanced_password' ) != "" )
					return true;
				else
					return false;
			} else if ( $third_party == "skrill" ) {
				if ( get_option( 'ec_option_skrill_merchant_id' ) != "" && get_option( 'ec_option_skrill_company_name' ) != "" && get_option( 'ec_option_skrill_email' ) != "" )
					return true;
				else
					return false;
			} else if ( $third_party == "realex" ) {
				if ( get_option( 'ec_option_realex_thirdparty_merchant_id' ) != "" && get_option( 'ec_option_realex_thirdparty_secret' ) != "" )
					return true;
				else
					return false;
			} else if ( $third_party == "redsys" ) {
				if ( get_option( 'ec_option_redsys_merchant_code' ) != "" && get_option( 'ec_option_redsys_terminal' ) != "" && get_option( 'ec_option_redsys_currency' ) != "" && get_option( 'ec_option_redsys_key' ) != "" )
					return true;
				else
					return false;
			} else if ( $third_party == "paymentexpress_thirdparty" ) {
				if ( get_option( 'ec_option_payment_express_thirdparty_username' ) != "" && get_option( 'ec_option_payment_express_thirdparty_key' ) != "" )
					return true;
				else
					return false;
			} else if ( $third_party == "custom_thirdparty" ) {
				return true;
			}
		}

		public function ec_get_third_party_method() {
			$third_party = get_option( 'ec_option_payment_third_party' );
			if ( $third_party == "dwolla_thirdparty" )
				return "Dwolla";
			else if ( $third_party == "nets" )
				return "Nets Netaxept";
			else if ( $third_party == "payfast_thirdparty" )
				return "Payfast";
			else if ( $third_party == "payfort" )
				return "Payfort";
			else if ( $third_party == "paypal" )
				return "PayPal";
			else if ( $third_party == "sagepay_paynow_za" )
				return "SagePay Pay Now South Africa";
			else if ( $third_party == "paypal_advanced" )
				return "PayPal Advanced";
			else if ( $third_party == "skrill" )
				return "Skrill";
			else if ( $third_party == "realex" )
				return "RealEx";
			else if ( $third_party == "redsys" )
				return "Redsys";
			else if ( $third_party == "paymentexpress_thirdparty" )
				return "Payment Express PxPay 2.0";
			else if ( $third_party == "custom_thirdparty" )
				return __( "Custom Gateway", 'wp-easycart' );
		}

		public function ec_live_payment_selected() {
			$live_payment = get_option( 'ec_option_payment_process_method' );
			if ( $live_payment && $live_payment != "0" )
				return true;
			else
				return false;
		}

		public function ec_live_payment_setup() {
			$live_payment = get_option( 'ec_option_payment_process_method' );
			if ( $live_payment == "authorize" ) {
				if ( get_option( 'ec_option_authorize_login_id' ) != "" && get_option( 'ec_option_authorize_trans_key' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "beanstream" ) {
				if ( get_option( 'ec_option_beanstream_merchant_id' ) != "" && get_option( 'ec_option_beanstream_api_passcode' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "braintree" ) {
				if ( get_option( 'ec_option_braintree_merchant_id' ) != "" && get_option( 'ec_option_braintree_public_key' ) != "" &&  get_option( 'ec_option_braintree_private_key' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "chronopay" ) {
				if ( get_option( 'ec_option_chronopay_currency' ) != "" && get_option( 'ec_option_chronopay_product_id' ) != "" &&  get_option( 'ec_option_chronopay_shared_secret' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "eway" ) {
				if ( get_option( 'ec_option_eway_customer_id' ) != "" || ( get_option( 'ec_option_eway_customer_id' ) != "" && get_option( 'ec_option_eway_api_key' ) != "" ) )
					return true;
				else
					return false;
			} else if ( $live_payment == "firstdata" ) {
				if ( get_option( 'ec_option_firstdatae4_exact_id' ) != "" && get_option( 'ec_option_firstdatae4_password' ) != "" && get_option( 'ec_option_firstdatae4_key_id' ) != "" && get_option( 'ec_option_firstdatae4_key' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "goemerchant" ) {
				if ( get_option( 'ec_option_goemerchant_trans_center_id' ) != "" && get_option( 'ec_option_goemerchant_gateway_id' ) != "" &&  get_option( 'ec_option_goemerchant_processor_id' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "intuit" ) {
				if ( get_option( 'ec_option_intuit_oauth_version' ) == 1 && get_option( 'ec_option_intuit_app_token' ) != "" && get_option( 'ec_option_intuit_consumer_key' ) != "" && get_option( 'ec_option_intuit_consumer_secret' ) != "" && get_option( 'ec_option_intuit_realm_id' ) != "" && get_option( 'ec_option_intuit_access_token_secret' ) != "" )
					return true;
				else if ( get_option( 'ec_option_intuit_oauth_version' ) == 2 && get_option( 'ec_option_intuit_consumer_key' ) != "" && get_option( 'ec_option_intuit_consumer_secret' ) != "" && get_option( 'ec_option_intuit_realm_id' ) != "" )
					return true;
				else if ( get_option( 'ec_option_intuit_oauth_version' ) == 3 && get_option( 'ec_option_intuit_refresh_token' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "migs" ) {
				if ( get_option( 'ec_option_migs_signature' ) != "" && get_option( 'ec_option_migs_access_code' ) != "" && get_option( 'ec_option_migs_merchant_id' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "moneris_ca" ) {
				if ( get_option( 'ec_option_moneris_ca_store_id' ) != "" && get_option( 'ec_option_moneris_ca_api_token' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "moneris_us" ) {
				if ( get_option( 'ec_option_moneris_us_store_id' ) != "" && get_option( 'ec_option_moneris_us_api_token' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "nmi" ) {
				if ( get_option( 'ec_option_nmi_username' ) != "" && get_option( 'ec_option_nmi_password' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "payline" ) {
				if ( get_option( 'ec_option_payline_username' ) != "" && get_option( 'ec_option_payline_password' ) != "" && get_option( 'ec_option_payline_processor_id' ) != "" && get_option( 'ec_option_payline_currency' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "paymentexpress" ) {
				if ( get_option( 'ec_option_payment_express_username' ) != "" && get_option( 'ec_option_payment_express_password' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "paypal_pro" ) {
				if ( get_option( 'ec_option_paypal_pro_partner' ) != "" && get_option( 'ec_option_paypal_pro_user' ) != "" &&  get_option( 'ec_option_paypal_pro_vendor' ) != "" &&  get_option( 'ec_option_paypal_pro_password' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "paypal_payments_pro" ) {
				if ( get_option( 'ec_option_paypal_payments_pro_user' ) != "" && get_option( 'ec_option_paypal_payments_pro_password' ) != "" &&  get_option( 'ec_option_paypal_payments_pro_signature' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "paypoint" ) {
				if ( get_option( 'ec_option_paypoint_merchant_id' ) != "" && get_option( 'ec_option_paypoint_vpn_password' ) != "" &&  get_option( 'ec_option_paypoint_vpn_password' ) != "0" )
					return true;
				else
					return false;
			} else if ( $live_payment == "realex" ) {
				if ( get_option( 'ec_option_realex_merchant_id' ) != "" && get_option( 'ec_option_realex_secret' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "sagepay" ) {
				if ( get_option( 'ec_option_sagepay_vendor' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "sagepayus" ) {
				if ( get_option( 'ec_option_sagepayus_mid' ) != "" && get_option( 'ec_option_sagepayus_mkey' ) != "" && get_option( 'ec_option_sagepayus_application_id' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "securenet" ) {
				if ( get_option( 'ec_option_securenet_id' ) != "" && get_option( 'ec_option_securenet_secure_key' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "securepay" ) {
				if ( get_option( 'ec_option_securepay_merchant_id' ) != "" && get_option( 'ec_option_securepay_password' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "stripe" ) {
				if ( get_option( 'ec_option_stripe_api_key' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "stripe_connect" ) {
				if ( ( get_option( 'ec_option_stripe_connect_use_sandbox' ) && get_option( 'ec_option_stripe_connect_sandbox_access_token' ) != '' ) || ( !get_option( 'ec_option_stripe_connect_use_sandbox' ) && get_option( 'ec_option_stripe_connect_production_access_token' ) != '' ) )
					return true;
				else
					return false;
			} else if ( $live_payment == "square" ) {
				if ( get_option( 'ec_option_square_access_token' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "virtualmerchant" ) {
				if ( get_option( 'ec_option_virtualmerchant_ssl_merchant_id' ) != "" && get_option( 'ec_option_virtualmerchant_ssl_user_id' ) != "" && get_option( 'ec_option_virtualmerchant_ssl_pin' ) != "" )
					return true;
				else
					return false;
			} else if ( $live_payment == "custom" ) {
				if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/ec_customgateway.php' ) )
					return true;
				else
					return false;
			}
		}

		public function ec_get_live_payment_method() {
			$live_payment = get_option( 'ec_option_payment_process_method' );
			if ( $live_payment == "authorize" )
				return "Authorize.Net";
			else if ( $live_payment == "beanstream" )
				return "Beanstream";
			else if ( $live_payment == "braintree" )
				return "Braintree S2S";
			else if ( $live_payment == "chronopay" )
				return "Chronopay";
			else if ( $live_payment == "eway" )
				return "Eway";
			else if ( $live_payment == "firstdata" )
				return "First Data Global Gateway e4";
			else if ( $live_payment == "goemerchant" )
				return "GoeMerchant";
			else if ( $live_payment == "intuit" )
				return "Intuit Payments";
			else if ( $live_payment == "migs" )
				return "MasterCard Internet Gateway Service (MIGS)";
			else if ( $live_payment == "moneris_ca" )
				return "Moneris Canada";
			else if ( $live_payment == "moneris_us" )
				return "Moneris US";
			else if ( $live_payment == "nmi" )
				return "Network Merchants (NMI)";
			else if ( $live_payment == "payline" )
				return "Payline";
			else if ( $live_payment == "paymentexpress" )
				return "Payment Express PxPost";
			else if ( $live_payment == "paypal_pro" )
				return "PayPal PayFlow Pro";
			else if ( $live_payment == "paypal_payments_pro" )
				return "PayPal Payments Pro";
			else if ( $live_payment == "paypoint" )
				return "PayPoint";
			else if ( $live_payment == "realex" )
				return "Realex";
			else if ( $live_payment == "sagepay" )
				return "Sagepay";
			else if ( $live_payment == "sagepayus" )
				return "Sagepay US";
			else if ( $live_payment == "securenet" )
				return "WorldPay";
			else if ( $live_payment == "securepay" )
				return "SecurePay";
			else if ( $live_payment == "stripe" )
				return "Stripe";
			else if ( $live_payment == "stripe_connect" )
				return "Stripe";
			else if ( $live_payment == "square" )
				return "Square";
			else if ( $live_payment == "virtualmerchant" )
				return "Converge (Virtual Merchant)";
			else if ( $live_payment == "custom" )
				return __( "Custom Payment Gateway", 'wp-easycart' );
		}



		public function ec_reset_store_permalinks() {

			global $wpdb;
			$db = new ec_db();
			if ( !isset( $_GET['ec_reset_phase2'] ) ) {

				$args = array(
					'posts_per_page'   => 1000000,
					'offset'           => 0,
					'orderby'          => 'date',
					'order'            => 'DESC',
					'post_type'        => 'ec_store',
					'post_status'      => 'any'
				);
				$posts_array = get_posts( $args );

				foreach ( $posts_array as $post ) {
					wp_delete_post( $post->ID, true );
				}
				$wpdb->query( "UPDATE ec_product SET ec_product.post_id = 0" );
				$wpdb->query( "UPDATE ec_menulevel1 SET ec_menulevel1.post_id = 0" );
				$wpdb->query( "UPDATE ec_menulevel2 SET ec_menulevel2.post_id = 0" );
				$wpdb->query( "UPDATE ec_menulevel3 SET ec_menulevel3.post_id = 0" );
				$wpdb->query( "UPDATE ec_category SET ec_category.post_id = 0" );
				$wpdb->query( "UPDATE ec_manufacturer SET ec_manufacturer.post_id = 0" );

			}

			$menulevel1_items = $wpdb->get_results( "SELECT * FROM ec_menulevel1 WHERE ec_menulevel1.post_id = 0" );
			$menulevel2_items = $wpdb->get_results( "SELECT * FROM ec_menulevel2 WHERE ec_menulevel2.post_id = 0" );
			$menulevel3_items = $wpdb->get_results( "SELECT * FROM ec_menulevel3 WHERE ec_menulevel3.post_id = 0" );
			$product_list = $wpdb->get_results( "SELECT ec_product.model_number, ec_product.post_id, ec_product.title, ec_product.product_id, ec_product.description FROM ec_product WHERE ec_product.post_id = 0" );
			$category_list = $wpdb->get_results( "SELECT * FROM ec_category WHERE ec_category.post_id = 0" );
			$manufacturer_list = $wpdb->get_results( "SELECT * FROM ec_manufacturer WHERE ec_manufacturer.post_id = 0" );

			echo sprintf( esc_attr__( "Rebuilding Menu %d", 'wp-easycart' ), 1 ) . ": ";
			foreach ( $menulevel1_items as $menu_item ) {

				if ( $menu_item->post_id == 0 ) {
					// Add a post id
					$post = array(	'post_content'	=> "[ec_store menuid=\"" . $menu_item->menulevel1_id . "\"]",
									'post_status'	=> "publish",
									'post_title'	=> $menu_item->name,
									'post_type'		=> "ec_store"
								  );
					$post_id = wp_insert_post( $post );
					$db->update_menu_post_id( $menu_item->menulevel1_id, $post_id );
				}

				echo sprintf( esc_attr__( "Item %s Done...", 'wp-easycart' ), esc_attr( $menu_item->menulevel1_id ) );

			}

			echo "<br />" . sprintf( esc_attr__( "Rebuilding Menu %d", 'wp-easycart' ), 2 ) . ": ";
			foreach ( $menulevel2_items as $menu_item ) {

				if ( $menu_item->post_id == 0 ) {
					// Add a post id
					$post = array(	'post_content'	=> "[ec_store submenuid=\"" . $menu_item->menulevel2_id . "\"]",
									'post_status'	=> "publish",
									'post_title'	=> $menu_item->name,
									'post_type'		=> "ec_store"
								  );
					$post_id = wp_insert_post( $post );
					$db->update_submenu_post_id( $menu_item->menulevel2_id, $post_id );
				}
				echo sprintf( esc_attr__( "Item %s Done...", 'wp-easycart' ), esc_attr( $menu_item->menulevel2_id ) );

			}

			echo "<br />" . sprintf( esc_attr__( "Rebuilding Menu %d", 'wp-easycart' ), 1 ) . ": ";
			foreach ( $menulevel3_items as $menu_item ) {

				if ( $menu_item->post_id == 0 ) {
					// Add a post id
					$post = array(	'post_content'	=> "[ec_store subsubmenuid=\"" . $menu_item->menulevel3_id . "\"]",
									'post_status'	=> "publish",
									'post_title'	=> $menu_item->name,
									'post_type'		=> "ec_store"
								  );
					$post_id = wp_insert_post( $post );
					$db->update_subsubmenu_post_id( $menu_item->menulevel3_id, $post_id );
				}
				echo sprintf( esc_attr__( "Item %s Done...", 'wp-easycart' ), esc_attr( $menu_item->menulevel3_id ) );

			}

			echo "<br>" . esc_attr( 'Rebuilding Products', 'wp-easycart' ) . ": ";
			foreach ( $product_list as $product_single ) {

				if ( $product_single->post_id == 0 ) {
					// Add a post id
					$post = array(	'post_content'	=> "[ec_store modelnumber=\"" . $product_single->model_number . "\"]",
									'post_status'	=> "publish",
									'post_title'	=> $product_single->title,
									'post_type'		=> "ec_store",
									'post_excerpt'	=> $product_single->description
								  );
					$post_id = wp_insert_post( $post );
					$db->update_product_post_id( $product_single->product_id, $post_id );
				}
				echo sprintf( esc_attr__( "Item %s Done...", 'wp-easycart' ), esc_attr( $product_single->model_number ) );

			}

			echo "<br>" . esc_attr( 'Rebuilding Manufacturers', 'wp-easycart' ) . ": ";
			foreach ( $manufacturer_list as $manufacturer_single ) {

				if ( $manufacturer_single->post_id == 0 ) {
					// Add a post id
					$post = array(	'post_content'	=> "[ec_store manufacturerid=\"" . $manufacturer_single->manufacturer_id . "\"]",
									'post_status'	=> "publish",
									'post_title'	=> $manufacturer_single->name,
									'post_type'		=> "ec_store"
								  );
					$post_id = wp_insert_post( $post );
					$db->update_manufacturer_post_id( $manufacturer_single->manufacturer_id, $post_id );
				}
				echo sprintf( esc_attr__( "Item %s Done...", 'wp-easycart' ), esc_attr( $manufacturer_single->manufacturer_id ) );

			}

			echo "<br>" . esc_attr( 'Rebuilding Categories', 'wp-easycart' ) . ": ";
			foreach ( $category_list as $category_single ) {

				if ( $category_single->post_id == 0 ) {
					// Add a post id
					$post = array(	'post_content'	=> "[ec_store groupid=\"" . $category_single->category_id . "\"]",
									'post_status'	=> "publish",
									'post_title'	=> $category_single->category_name,
									'post_type'		=> "ec_store"
								  );
					$post_id = wp_insert_post( $post );
					$db->update_category_post_id( $category_single->category_id, $post_id );
				}
				echo sprintf( esc_attr__( "Item %s Done...", 'wp-easycart' ), esc_attr( $category_single->category_id ) );

			}

		}

	}
endif;

function wp_easycart_admin_store_status() {
	return wp_easycart_admin_store_status::instance();
}
wp_easycart_admin_store_status();
