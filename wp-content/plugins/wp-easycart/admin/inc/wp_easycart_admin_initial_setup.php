<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_initial_setup' ) ) :

	final class wp_easycart_admin_initial_setup {

		protected static $_instance = null;

		public $initial_setup_file;
		public $success_messages_file;
		public $product_page_file;
		public $cart_page_file;
		public $account_page_file;
		public $demo_data_file;
		public $currency_setup_file;
		public $goals_setup_file;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			$this->initial_setup_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/initial-setup/initial-setup.php';
			$this->success_messages_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/initial-setup/success-messages.php';
			$this->product_page_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/initial-setup/product-page.php';
			$this->cart_page_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/initial-setup/cart-page.php';
			$this->account_page_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/initial-setup/account-page.php';
			$this->demo_data_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/initial-setup/demo-data.php';
			$this->currency_setup_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/initial-setup/currency-setup.php';
			$this->goals_setup_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/initial-setup/goal-setup.php';

			add_action( 'wpeasycart_admin_initial_setup_success', array( $this, 'load_success_messages' ) );
			add_action( 'wpeasycart_admin_intial_setup', array( $this, 'load_product_page' ) );
			add_action( 'wpeasycart_admin_intial_setup', array( $this, 'load_cart_page' ) );
			add_action( 'wpeasycart_admin_intial_setup', array( $this, 'load_account_page' ) );
			add_action( 'wpeasycart_admin_intial_setup', array( $this, 'load_goal_display' ) );
			add_action( 'wpeasycart_admin_intial_setup', array( $this, 'load_currency_display' ) );
			add_action( 'wpeasycart_admin_intial_setup', array( $this, 'load_demo_data_display' ) );
			add_action( 'admin_init', array( $this, 'check_initial_install_setup' ) );
		}

		public function load_success_messages() {
			include( $this->success_messages_file );
		}

		public function load_initial_setup() {
			include( $this->initial_setup_file );
		}

		public function load_product_page() {
			include( $this->product_page_file );
		}

		public function load_cart_page() {
			include( $this->cart_page_file );
		}

		public function load_account_page() {
			include( $this->account_page_file );
		}
		public function load_goal_display() {
			include( $this->goals_setup_file );
		}

		public function load_demo_data_display() {
			include( $this->demo_data_file );
		}

		public function load_currency_display() {
			include( $this->currency_setup_file );
		}

		public function check_initial_install_setup() {
			if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) {

				global $wpdb;

				if ( get_option( 'ec_option_default_manufacturer' ) == '' ) {
					$name = get_bloginfo( 'name' );
					$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_manufacturer( name ) VALUES( %s )', $name ) );
					$manufacturer_id = $wpdb->insert_id;
					$post = array(
						'post_content' => '[ec_store manufacturerid="' . $manufacturer_id . '"]',
						'post_status' => 'publish',
						'post_title' => $name,
						'post_type' => 'ec_store',
					);
					$post_id = wp_insert_post( $post );
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_manufacturer SET post_id = %d WHERE manufacturer_id = %d', $post_id, $manufacturer_id ) );
					update_option( 'ec_option_default_manufacturer', $manufacturer_id );
				}

				if ( get_option( 'ec_option_storepage' ) == '' && get_option( 'ec_option_cartpage' ) == '' && get_option( 'ec_option_accountpage' ) == '' ) {
					$pages = get_pages();
					$store_id = 0;
					$cart_id = 0;
					$account_id = 0;
					$store_page_ids = array();
					$cart_page_ids = array();
					$account_page_ids = array();
					$pages = get_pages();
					foreach ( $pages as $page ) {
						if ( strstr( $page->post_content, '[ec_store]' ) ) {
							$store_page_ids[] = $page->ID;
						}
						if ( strstr( $page->post_content, '[ec_cart]' ) ) {
							$cart_page_ids[] = $page->ID;
						}
						if ( strstr( $page->post_content, '[ec_account]' ) ) {
							$account_page_ids[] = $page->ID;
						}
					}

					if ( count( $store_page_ids ) > 0 ) {
						$store_id = $store_page_ids[0];
					} else {
						$store_page = array( 
							'post_content' => '[ec_store]',
							'post_title' => 'Store',
							'post_type' => 'page',
							'post_status' => 'publish'
						 );
						$store_id = wp_insert_post( $store_page );
					}
					update_option( 'ec_option_storepage', $store_id );

					if ( count( $cart_page_ids ) > 0 ) {
						$cart_id = $cart_page_ids[0];
					} else {
						$cart_page = array( 
							'post_content' => '[ec_cart]',
							'post_title' => 'Cart',
							'post_type' => 'page',
							'post_status' => 'publish'
						 );
						$cart_id = wp_insert_post( $cart_page );
					}
					update_option( 'ec_option_cartpage', $cart_id );

					if ( count( $account_page_ids ) > 0 ) {
						$account_id = $account_page_ids[0];
					} else {
						$account_page = array( 
							'post_content' => '[ec_account]',
							'post_title' => 'Account',
							'post_type' => 'page',
							'post_status' => 'publish'
						 );
						$account_id = wp_insert_post( $account_page );
					}
					update_option( 'ec_option_accountpage', $account_id );
				}
			}
		}

		public function save_storepage() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-initial-setup-storepage-setup' ) ) {
				return false;
			}

			$ec_option_storepage = (int) $_POST['ec_option_storepage'];
			update_option( 'ec_option_storepage', $ec_option_storepage );
		}

		public function save_cartpage() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-initial-setup-cartpage-setup' ) ) {
				return false;
			}

			$ec_option_cartpage = (int) $_POST['ec_option_cartpage'];
			update_option( 'ec_option_cartpage', $ec_option_cartpage );
		}

		public function save_accountpage() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-initial-setup-accountpage-setup' ) ) {
				return false;
			}

			$ec_option_accountpage = (int) $_POST['ec_option_accountpage'];
			update_option( 'ec_option_accountpage', $ec_option_accountpage );
		}

		public function save_goal_setup() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-initial-setup-goals-setup' ) ) {
				return false;
			}

			$ec_option_admin_display_sales_goal = wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_admin_display_sales_goal'] );
			$ec_option_admin_sales_goal = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['ec_option_admin_sales_goal'] ) ) );

			update_option( 'ec_option_admin_display_sales_goal', $ec_option_admin_display_sales_goal );
			update_option( 'ec_option_admin_sales_goal', $ec_option_admin_sales_goal );
		}

		public function save_currency_settings() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-initial-setup-currency-setup' ) ) {
				return false;
			}

			$ec_option_show_currency_code = wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_show_currency_code'] );
			$ec_option_base_currency = wp_easycart_admin_verification()->filter_chars( sanitize_text_field( wp_unslash( $_POST['ec_option_base_currency'] ) ), 3 );
			$ec_option_currency = wp_easycart_admin_verification()->filter_length( sanitize_text_field( wp_unslash( $_POST['ec_option_currency'] ) ), 10 );
			$ec_option_currency_symbol_location = wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_currency_symbol_location'] );
			$ec_option_currency_negative_location = wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_currency_negative_location'] );
			$ec_option_currency_decimal_symbol = wp_easycart_admin_verification()->filter_length( sanitize_text_field( wp_unslash( $_POST['ec_option_currency_decimal_symbol'] ) ), 1 );
			$ec_option_currency_decimal_places = (int) $_POST['ec_option_currency_decimal_places'];
			$ec_option_currency_thousands_seperator = substr( trim( sanitize_text_field( wp_unslash( $_POST['ec_option_currency_thousands_seperator'] ) ) ), 0, 1 );

			/* Validate Input */
			$exchange_rates_raw = sanitize_text_field( wp_unslash( $_POST['ec_option_exchange_rates'] ) );
			$exchange_rates_groups = explode( ',', $exchange_rates_raw );
			$exchange_rates_arr = array();
			if ( count( $exchange_rates_groups ) > 0 ) {
				foreach ( $exchange_rates_groups as $exchange_rates_group ) {
					$exchange_rate_segments = explode( '=', trim( $exchange_rates_group ) );
					if ( $exchange_rate_segments && count( $exchange_rate_segments ) == 2 ) {
						$exchange_rates_arr[] = wp_easycart_admin_verification()->filter_chars( $exchange_rate_segments[0], 3 ) . '=' . wp_easycart_admin_verification()->filter_float( $exchange_rate_segments[1] );
					}
				}
			}
			$ec_option_exchange_rates = implode( ',', $exchange_rates_arr );

			update_option( 'ec_option_base_currency', $ec_option_base_currency );
			update_option( 'ec_option_show_currency_code', $ec_option_show_currency_code );
			update_option( 'ec_option_currency', $ec_option_currency );
			update_option( 'ec_option_currency_symbol_location', $ec_option_currency_symbol_location );
			update_option( 'ec_option_currency_negative_location', $ec_option_currency_negative_location );
			update_option( 'ec_option_currency_decimal_symbol', $ec_option_currency_decimal_symbol );
			update_option( 'ec_option_currency_decimal_places', $ec_option_currency_decimal_places );
			update_option( 'ec_option_currency_thousands_seperator', $ec_option_currency_thousands_seperator );
			update_option( 'ec_option_exchange_rates', $ec_option_exchange_rates );
		}

		public function add_storepage() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-initial-setup-storepage-setup' ) ) {
				return false;
			}

			$post = array( 
				'post_content' => '[ec_store]',
				'post_title' => __( 'Store', 'wp-easycart' ),
				'post_type' => 'page',
				'post_status' => 'publish',
			);
			$post_id = wp_insert_post( $post );
			update_option( 'ec_option_storepage', $post_id );
			return $post_id;
		}

		public function add_cartpage() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-initial-setup-cartpage-setup' ) ) {
				return false;
			}

			$post = array(
				'post_content' => '[ec_cart]',
				'post_title' => __( 'Cart', 'wp-easycart' ),
				'post_type' => 'page',
				'post_status' => 'publish',
			);
			$post_id = wp_insert_post( $post );
			update_option( 'ec_option_cartpage', $post_id );
		}

		public function add_accountpage() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-initial-setup-accountpage-setup' ) ) {
				return false;
			}

			$post = array(
				'post_content' => '[ec_account]',
				'post_title' => __( 'Account', 'wp-easycart' ),
				'post_type' => 'page',
				'post_status' => 'publish',
			);
			$post_id = wp_insert_post( $post );
			update_option( 'ec_option_accountpage', $post_id );
		}

		public function install_demo_data() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-initial-setup-demo-setup' ) ) {
				return false;
			}

			global $wpdb;

			$wpdb->query( "INSERT INTO `ec_user` ( `is_demo_item`, `email`, `password`, `first_name`, `last_name`, `user_level` ) VALUES ( 1, 'michelle@test.com', '347cea1baa93ea24c9069903d1cfeb2b', 'Michelle', 'Smith', 'shopper' )" );
			$user_id = $wpdb->insert_id;
			$wpdb->query( $wpdb->prepare( "INSERT INTO `ec_address` (`user_id`, `first_name`, `last_name`, `address_line_1`, `city`, `state`, `zip`, `country`, `phone`) VALUES ( %d, 'Michelle', 'Smith', '5555 SW North St.', 'Pendleton', 'OR', '97801', 'US', '541 555 5555' )", $user_id ) );
			$default_billing_id = $wpdb->insert_id;
			$wpdb->query( $wpdb->prepare( "INSERT INTO `ec_address` (`user_id`, `first_name`, `last_name`, `address_line_1`, `city`, `state`, `zip`, `country`, `phone`) VALUES ( %d, 'Michelle', 'Smith', '5555 SW North St.', 'Pendleton', 'OR', '97801', 'US', '541 555 5555' )", $user_id ) );
			$default_shipping_id = $wpdb->insert_id;
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_user SET default_billing_address_id = %d, default_shipping_address_id = %d WHERE user_id = %d', $default_billing_id, $default_shipping_id, $user_id ) );

			$categories = array( 
				(object) array(
					'category_id' => '',
					'name' => __( 'Specials', 'wp-easycart' ),
					'post_id' => '',
				),
				(object) array(
					'category_id' => '',
					'name' => __( 'Sales', 'wp-easycart' ),
					'post_id' => '',
				),
				(object) array(
					'category_id' => '',
					'name' => __( 'New', 'wp-easycart' ),
					'post_id' => '',
				),
			);
			$categories_count = count( $categories );
			for ( $i = 0; $i < $categories_count; $i++ ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO `ec_category` ( `is_demo_item`, `category_name` ) VALUES( 1, %s )', $categories[ $i ]->name ) );
				$categories[ $i ]->category_id = $wpdb->insert_id;
				$post = array(	
					'post_content' => '[ec_store groupid="' . $categories[ $i ]->category_id . '"]',
					'post_status' => 'publish',
					'post_title' => $categories[ $i ]->name,
					'post_type' => 'ec_store',
				);
				$categories[ $i ]->post_id = wp_insert_post( $post );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_category SET post_id = %d WHERE category_id = %d', $categories[ $i ]->post_id, $categories[ $i ]->category_id ) );
			}

			$manufacturers = array( 
				(object) array(
					'manufacturer_id' => '',
					'name' => 'L4 Development',
					'post_id' => '',
				),
				(object) array(
					'manufacturer_id' => '',
					'name' => 'WP EasyCart',
					'post_id' => '',
				),
			);
			$manufacturers_count = count( $manufacturers );
			for ( $i = 0; $i < $manufacturers_count; $i++ ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO `ec_manufacturer` ( `is_demo_item`, `name` ) VALUES( 1, %s )', $manufacturers[ $i ]->name ) );
				$manufacturers[ $i ]->manufacturer_id = $wpdb->insert_id;
				$post = array(	
					'post_content' => '[ec_store manufacturerid="' . $manufacturers[ $i ]->manufacturer_id . '"]',
					'post_status' => 'publish',
					'post_title' => $manufacturers[ $i ]->name,
					'post_type' => 'ec_store',
				);
				$manufacturers[ $i ]->post_id = wp_insert_post( $post );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_manufacturer SET post_id = %d WHERE manufacturer_id = %d', $manufacturers[ $i ]->post_id, $manufacturers[ $i ]->manufacturer_id ) );
			}

			$menulevel1_items = array(
				(object) array(
					'menulevel1_id' => '',
					'name' => __( 'Fashion Type', 'wp-easycart' ),
					'banner_image' => 'https://support.wpeasycart.com/sampledata/v4/products/banners/clothing960x300.png',
					'post_id' => '',
				),
				(object) array(
					'menulevel1_id' => '',
					'name' => __( 'Womens Pants', 'wp-easycart' ),
					'banner_image' => '',
					'post_id' => '',
				),
				(object) array(
					'menulevel1_id' => '',
					'name' => __( 'Womens Coats', 'wp-easycart' ),
					'banner_image' => '',
					'post_id' => '',
				),
				(object) array(
					'menulevel1_id' => '',
					'name' => __( 'Womens Shirts', 'wp-easycart' ),
					'banner_image' => '',
					'post_id' => '',
				),
			);
			$menulevel1_items_count = count( $menulevel1_items );
			for ( $i = 0; $i < $menulevel1_items_count; $i++ ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO `ec_menulevel1` ( `is_demo_item`, `name`, `menu_order`, `banner_image` ) VALUES( 1, %s, %d, %s )', $menulevel1_items[ $i ]->name, $i, $menulevel1_items[ $i ]->banner_image ) );
				$menulevel1_items[ $i ]->menulevel1_id = $wpdb->insert_id;
				$post = array(	
					'post_content' => '[ec_store menuid="' . $menulevel1_items[ $i ]->menulevel1_id . '"]',
					'post_status' => 'publish',
					'post_title' => $menulevel1_items[ $i ]->name,
					'post_type' => 'ec_store',
				);
				$menulevel1_items[ $i ]->post_id = wp_insert_post( $post );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_menulevel1 SET post_id = %d WHERE menulevel1_id = %d', $menulevel1_items[ $i ]->post_id, $menulevel1_items[ $i ]->menulevel1_id ) );
			}

			$menulevel2_items = array(
				(object) array(
					'menulevel2_id' => '',
					'menulevel1_id' => $menulevel1_items[0]->menulevel1_id,
					'name' => __( 'Work Cloths', 'wp-easycart' ),
					'banner_image' => 'https://support.wpeasycart.com/sampledata/v4/products/banners/fashion960x300.png',
					'post_id' => '',
				),
				(object) array(
					'menulevel2_id' => '',
					'menulevel1_id' => $menulevel1_items[0]->menulevel1_id,
					'name' => __( 'Denim Group', 'wp-easycart' ),
					'banner_image' => '',
					'post_id' => '',
				),
				(object) array(
					'menulevel2_id' => '',
					'menulevel1_id' => $menulevel1_items[0]->menulevel1_id,
					'name' => __( 'Weekend Wear', 'wp-easycart' ),
					'banner_image' => '',
					'post_id' => '',
				),
			);
			$menulevel2_items_count = count( $menulevel2_items );
			for ( $i = 0; $i < $menulevel2_items_count; $i++ ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO `ec_menulevel2` ( `is_demo_item`, `menulevel1_id`, `name`, `menu_order`, `banner_image` ) VALUES( 1, %d, %s, %d, %s )', $menulevel2_items[ $i ]->menulevel1_id, $menulevel2_items[ $i ]->name, $i, $menulevel2_items[ $i ]->banner_image ) );
				$menulevel2_items[ $i ]->menulevel2_id = $wpdb->insert_id;
				$post = array(	
					'post_content' => '[ec_store submenuid="' . $menulevel2_items[ $i ]->menulevel2_id . '"]',
					'post_status' => 'publish',
					'post_title' => $menulevel2_items[ $i ]->name,
					'post_type' => 'ec_store',
				);
				$menulevel2_items[ $i ]->post_id = wp_insert_post( $post );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_menulevel2 SET post_id = %d WHERE menulevel2_id = %d', $menulevel2_items[ $i ]->post_id, $menulevel2_items[ $i ]->menulevel2_id ) );
			}

			$menulevel3_items = array(
				(object) array(
					'menulevel3_id' => '',
					'menulevel2_id' => $menulevel2_items[0]->menulevel2_id,
					'name' => __( 'T-Shirts & Short Sleeve', 'wp-easycart' ),
					'banner_image' => '',
					'post_id' => '',
				),
				(object) array(
					'menulevel3_id' => '',
					'menulevel2_id' => $menulevel2_items[0]->menulevel2_id,
					'name' => __( 'Long Sleeve', 'wp-easycart' ),
					'banner_image' => '',
					'post_id' => '',
				),
			);
			$menulevel3_items_count = count( $menulevel3_items );
			for ( $i = 0; $i < $menulevel3_items_count; $i++ ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO `ec_menulevel3` ( `is_demo_item`, `menulevel2_id`, `name`, `menu_order`, `banner_image` ) VALUES( 1, %d, %s, %d, %s )', $menulevel3_items[ $i ]->menulevel2_id, $menulevel3_items[ $i ]->name, $i, $menulevel3_items[ $i ]->banner_image ) );
				$menulevel3_items[ $i ]->menulevel3_id = $wpdb->insert_id;
				$post = array(	
					'post_content' => '[ec_store subsubmenuid="' . $menulevel3_items[ $i ]->menulevel3_id . '"]',
					'post_status' => 'publish',
					'post_title' => $menulevel3_items[ $i ]->name,
					'post_type' => 'ec_store',
				);
				$menulevel3_items[ $i ]->post_id = wp_insert_post( $post );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_menulevel3 SET post_id = %d WHERE menulevel3_id = %d', $menulevel3_items[ $i ]->post_id, $menulevel3_items[ $i ]->menulevel3_id ) );
			}

			$options = array(
				(object) array(
					'option_id' => '',
					'option_name' => __( 'Womens Shirt Sizes', 'wp-easycart' ),
					'option_label' => __( 'Select Shirt Size', 'wp-easycart' ),
					'option_type' => 'basic-swatch',
					'option_required' => 1,
				),
				(object) array(
					'option_id' => '',
					'option_name' => __( 'Womens Shirt Color', 'wp-easycart' ),
					'option_label' => __( 'Select Shirt Color', 'wp-easycart' ),
					'option_type' => 'basic-swatch',
					'option_required' => 1,
				),
			);
			$options_count = count( $options );
			for ( $i = 0; $i < $options_count; $i++ ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO `ec_option` ( `is_demo_item`, `option_name`, `option_label`, `option_type`, `option_required` ) VALUES( 1, %s, %s, %s, %d )', $options[ $i ]->option_name, $options[ $i ]->option_label, $options[ $i ]->option_type, $options[ $i ]->option_required ) );
				$options[ $i ]->option_id = $wpdb->insert_id;
			}
			$option_items = array(
				(object) array(
					'optionitem_id' => '',
					'option_id' => $options[0]->option_id,
					'optionitem_name' => __( 'X-Petite', 'wp-easycart' ),
					'optionitem_icon' => 'https://support.wpeasycart.com/sampledata/v4/products/swatches/xsmall.jpg',
				),
				(object) array(
					'optionitem_id' => '',
					'option_id' => $options[0]->option_id,
					'optionitem_name' => __( 'Small', 'wp-easycart' ),
					'optionitem_icon' => 'https://support.wpeasycart.com/sampledata/v4/products/swatches/small.jpg',
				),
				(object) array(
					'optionitem_id' => '',
					'option_id' => $options[0]->option_id,
					'optionitem_name' => __( 'Medium', 'wp-easycart' ),
					'optionitem_icon' => 'https://support.wpeasycart.com/sampledata/v4/products/swatches/medium.jpg',
				),
				(object) array(
					'optionitem_id' => '',
					'option_id' => $options[0]->option_id,
					'optionitem_name' => __( 'Large', 'wp-easycart' ),
					'optionitem_icon' => 'https://support.wpeasycart.com/sampledata/v4/products/swatches/large.jpg',
				),
				(object) array(
					'optionitem_id' => '',
					'option_id' => $options[0]->option_id,
					'optionitem_name' => __( 'X-Large', 'wp-easycart' ),
					'optionitem_icon' => 'https://support.wpeasycart.com/sampledata/v4/products/swatches/xlarge.jpg',
				),
				(object) array(
					'optionitem_id' => '',
					'option_id' => $options[1]->option_id,
					'optionitem_name' => __( 'Blue', 'wp-easycart' ),
					'optionitem_icon' => 'https://support.wpeasycart.com/sampledata/v4/products/swatches/blue.jpg',
				),
				(object) array(
					'optionitem_id' => '',
					'option_id' => $options[1]->option_id,
					'optionitem_name' => __( 'Green', 'wp-easycart' ),
					'optionitem_icon' => 'https://support.wpeasycart.com/sampledata/v4/products/swatches/green.jpg',
				),
				(object) array(
					'optionitem_id' => '',
					'option_id' => $options[1]->option_id,
					'optionitem_name' => __( 'Orange', 'wp-easycart' ),
					'optionitem_icon' => 'https://support.wpeasycart.com/sampledata/v4/products/swatches/orange.jpg',
				),
				(object) array(
					'optionitem_id' => '',
					'option_id' => $options[1]->option_id,
					'optionitem_name' => __( 'Pink', 'wp-easycart' ),
					'optionitem_icon' => 'https://support.wpeasycart.com/sampledata/v4/products/swatches/pink.jpg',
				),
				(object) array(
					'optionitem_id' => '',
					'option_id' => $options[1]->option_id,
					'optionitem_name' => __( 'Red', 'wp-easycart' ),
					'optionitem_icon' => 'https://support.wpeasycart.com/sampledata/v4/products/swatches/red.jpg',
				),
				(object) array(
					'optionitem_id' => '',
					'option_id' => $options[1]->option_id,
					'optionitem_name' => __( 'Yellow', 'wp-easycart' ),
					'optionitem_icon' => 'https://support.wpeasycart.com/sampledata/v4/products/swatches/yellow.jpg',
				),
			);
			$optionitems_count = count( $option_items );
			for ( $i = 0; $i < $optionitems_count; $i++ ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO `ec_optionitem` ( `option_id`, `optionitem_name`, `optionitem_icon`, `optionitem_order` ) VALUES( %d, %s, %s, %d )', $option_items[ $i ]->option_id, $option_items[ $i ]->optionitem_name, $option_items[ $i ]->optionitem_icon, $i ) );
				$option_items[ $i ]->optionitem_id = $wpdb->insert_id;
			}

			$products = array(
				(object) array(
					'product_id' => '',
					'model_number' => 'SKU3425',
					'title' => __( 'Fall Coat', 'wp-easycart' ),
					'price' => '169.95',
					'list_price' => '189.95',
					'stock_quantity' => 76,
					'manufacturer_id' => $manufacturers[0]->manufacturer_id,
					'image1' => 'https://support.wpeasycart.com/sampledata/v4/products/pics1/womens-8c.jpg',
					'image2' => 'https://support.wpeasycart.com/sampledata/v4/products/pics2/womens-8a.jpg',
					'post_id' => '',
					'option_id_1' => $options[0]->option_id,
					'menulevel1_id_1' => $menulevel1_items[0]->menulevel1_id,
					'menulevel1_id_2' => $menulevel1_items[1]->menulevel1_id,
					'menulevel1_id_3' => '',
					'menulevel2_id_1' => $menulevel2_items[0]->menulevel2_id,
					'menulevel2_id_2' => 0,
					'menulevel2_id_3' => 0,
					'menulevel3_id_1' => $menulevel3_items[0]->menulevel3_id,
					'menulevel3_id_2' => 0,
					'menulevel3_id_3' => 0,
					'featured_product_id_1' => 0,
					'featured_product_id_2' => 0,
					'featured_product_id_3' => 0,
					'featured_product_id_4' => 0,
				),
				(object) array(
					'product_id' => '',
					'model_number' => 'SKU4545',
					'title' => __( 'White Short Sleeve', 'wp-easycart' ),
					'price' => '24.990',
					'list_price' => '27.990',
					'stock_quantity' => 99,
					'manufacturer_id' => $manufacturers[0]->manufacturer_id,
					'image1' => 'https://support.wpeasycart.com/sampledata/v4/products/pics1/womens-5a.jpg',
					'image2' => 'https://support.wpeasycart.com/sampledata/v4/products/pics2/womens-5b.jpg',
					'post_id' => '',
					'option_id_1' => $options[1]->option_id,
					'menulevel1_id_1' => $menulevel1_items[0]->menulevel1_id,
					'menulevel1_id_2' => $menulevel1_items[1]->menulevel1_id,
					'menulevel1_id_3' => '',
					'menulevel2_id_1' => $menulevel2_items[1]->menulevel2_id,
					'menulevel2_id_2' => 0,
					'menulevel2_id_3' => 0,
					'menulevel3_id_1' =>0,
					'menulevel3_id_2' => 0,
					'menulevel3_id_3' => 0,
					'featured_product_id_1' => 0,
					'featured_product_id_2' => 0,
					'featured_product_id_3' => 0,
					'featured_product_id_4' => 0,
				),
				(object) array(
					'product_id' => '',
					'model_number' => 'SKU5535', 
					'title' => __( 'Printed Top', 'wp-easycart' ),
					'price' => '79.950',
					'list_price' => '89.950',
					'stock_quantity' => 15,
					'manufacturer_id' => $manufacturers[0]->manufacturer_id,
					'image1' => 'https://support.wpeasycart.com/sampledata/v4/products/pics1/womens-9a.jpg',
					'image2' => 'https://support.wpeasycart.com/sampledata/v4/products/pics2/womens-9b.jpg',
					'post_id' => '',
					'option_id_1' => $options[1]->option_id,
					'menulevel1_id_1' => $menulevel1_items[0]->menulevel1_id,
					'menulevel1_id_2' => $menulevel1_items[2]->menulevel1_id,
					'menulevel1_id_3' => '',
					'menulevel2_id_1' => $menulevel2_items[2]->menulevel2_id,
					'menulevel2_id_2' => 0,
					'menulevel2_id_3' => 0,
					'menulevel3_id_1' => 0,
					'menulevel3_id_2' => 0,
					'menulevel3_id_3' => 0,
					'featured_product_id_1' => 0,
					'featured_product_id_2' => 0,
					'featured_product_id_3' => 0,
					'featured_product_id_4' => 0,
				),
				(object) array(
					'product_id' => '',
					'model_number' => 'SKU6515',
					'title' => __( 'Spotted Shirt', 'wp-easycart' ),
					'price' => '45.950',
					'list_price' => '65.950',
					'stock_quantity' => 20,
					'manufacturer_id' => $manufacturers[1]->manufacturer_id,
					'image1' => 'https://support.wpeasycart.com/sampledata/v4/products/pics1/womens-6a.jpg',
					'image2' => 'https://support.wpeasycart.com/sampledata/v4/products/pics2/womens-6b.jpg',
					'post_id' => '',
					'option_id_1' => $options[0]->option_id,
					'menulevel1_id_1' => $menulevel1_items[0]->menulevel1_id,
					'menulevel1_id_2' => $menulevel1_items[3]->menulevel1_id,
					'menulevel1_id_3' => '',
					'menulevel2_id_1' => $menulevel2_items[0]->menulevel2_id,
					'menulevel2_id_2' => 0,
					'menulevel2_id_3' => 0,
					'menulevel3_id_1' => 0,
					'menulevel3_id_2' => 0,
					'menulevel3_id_3' => '',
					'featured_product_id_1' => 0,
					'featured_product_id_2' => 0,
					'featured_product_id_3' => 0,
					'featured_product_id_4' => 0,
				),
				(object) array(
					'product_id' => '',
					'model_number' => 'SKU7505',
					'title' => __( 'Denim Coat', 'wp-easycart' ),
					'price' => '109.95',
					'list_price' => '149.95',
					'stock_quantity' => 36,
					'manufacturer_id' => $manufacturers[1]->manufacturer_id,
					'image1' => 'https://support.wpeasycart.com/sampledata/v4/products/pics1/womens-12b.jpg',
					'image2' => 'https://support.wpeasycart.com/sampledata/v4/products/pics2/womens-12a.jpg',
					'post_id' => '',
					'option_id_1' => $options[1]->option_id,
					'menulevel1_id_1' => $menulevel1_items[0]->menulevel1_id,
					'menulevel1_id_2' => $menulevel1_items[3]->menulevel1_id,
					'menulevel1_id_3' => '',
					'menulevel2_id_1' => $menulevel2_items[1]->menulevel2_id,
					'menulevel2_id_2' => 0,
					'menulevel2_id_3' => 0,
					'menulevel3_id_1' => 0,
					'menulevel3_id_2' => 0,
					'menulevel3_id_3' => '',
					'featured_product_id_1' => 0,
					'featured_product_id_2' => 0,
					'featured_product_id_3' => 0,
					'featured_product_id_4' => 0,
				),
				(object) array(
					'product_id' => '',
					'model_number' => 'SKU8595',
					'title' => __( 'Multi-Colored Top', 'wp-easycart' ),
					'price' => '79.950',
					'list_price' => '95.990',
					'stock_quantity' => 88,
					'manufacturer_id' => $manufacturers[1]->manufacturer_id,
					'image1' => 'https://support.wpeasycart.com/sampledata/v4/products/pics1/womens-1a.jpg',
					'image2' => 'https://support.wpeasycart.com/sampledata/v4/products/pics2/womens-1c.jpg',
					'post_id' => '',
					'option_id_1' => $options[0]->option_id,
					'menulevel1_id_1' => $menulevel1_items[0]->menulevel1_id,
					'menulevel1_id_2' => $menulevel1_items[1]->menulevel1_id,
					'menulevel1_id_3' => '',
					'menulevel2_id_1' => $menulevel2_items[2]->menulevel2_id,
					'menulevel2_id_2' => 0,
					'menulevel2_id_3' => 0,
					'menulevel3_id_1' => $menulevel3_items[1]->menulevel3_id,
					'menulevel3_id_2' => 0,
					'menulevel3_id_3' => '',
					'featured_product_id_1' => 0,
					'featured_product_id_2' => 0,
					'featured_product_id_3' => 0,
					'featured_product_id_4' => 0,
				),
				(object) array(
					'product_id' => '',
					'model_number' => 'SKU9585',
					'title' => __( 'Heather Shirt', 'wp-easycart' ),
					'price' => '49.950',
					'list_price' => '55.950',
					'stock_quantity' => 25,
					'manufacturer_id' => $manufacturers[0]->manufacturer_id,
					'image1' => 'https://support.wpeasycart.com/sampledata/v4/products/pics1/womens-3a.jpg',
					'image2' => 'https://support.wpeasycart.com/sampledata/v4/products/pics2/womens-3b.jpg',
					'post_id' => '',
					'option_id_1' => $options[1]->option_id,
					'menulevel1_id_1' => $menulevel1_items[0]->menulevel1_id,
					'menulevel1_id_2' => $menulevel1_items[1]->menulevel1_id,
					'menulevel1_id_3' => '',
					'menulevel2_id_1' => $menulevel2_items[0]->menulevel2_id,
					'menulevel2_id_2' => 0,
					'menulevel2_id_3' => 0,
					'menulevel3_id_1' => $menulevel3_items[1]->menulevel3_id,
					'menulevel3_id_2' => 0,
					'menulevel3_id_3' => '',
					'featured_product_id_1' => 0,
					'featured_product_id_2' => 0,
					'featured_product_id_3' => 0,
					'featured_product_id_4' => 0,
				),
				(object) array(
					'product_id' => '',
					'model_number' => 'SKU1575',
					'title' => __( 'Weekend Coat', 'wp-easycart' ),
					'price' => '119.95',
					'list_price' => '135.95',
					'stock_quantity' => 96,
					'manufacturer_id' => $manufacturers[0]->manufacturer_id,
					'image1' => 'https://support.wpeasycart.com/sampledata/v4/products/pics1/womens-4a.jpg',
					'image2' => 'https://support.wpeasycart.com/sampledata/v4/products/pics2/womens-4b.jpg',
					'post_id' => '',
					'option_id_1' => $options[0]->option_id,
					'menulevel1_id_1' => $menulevel1_items[0]->menulevel1_id,
					'menulevel1_id_2' => $menulevel1_items[1]->menulevel1_id,
					'menulevel1_id_3' => '',
					'menulevel2_id_1' => $menulevel2_items[1]->menulevel2_id,
					'menulevel2_id_2' => 0,
					'menulevel2_id_3' => 0,
					'menulevel3_id_1' => $menulevel3_items[0]->menulevel3_id,
					'menulevel3_id_2' => 0,
					'menulevel3_id_3' => '',
					'featured_product_id_1' => 0,
					'featured_product_id_2' => 0,
					'featured_product_id_3' => 0,
					'featured_product_id_4' => 0,
				),
				(object) array(
					'product_id' => '',
					'model_number' => 'SKU2565',
					'title' => __( 'Fitted Skirt', 'wp-easycart' ),
					'price' => '99.950',
					'list_price' => '120.95',
					'stock_quantity' => 35,
					'manufacturer_id' => $manufacturers[1]->manufacturer_id,
					'image1' => 'https://support.wpeasycart.com/sampledata/v4/products/pics1/womens-10a.jpg',
					'image2' => 'https://support.wpeasycart.com/sampledata/v4/products/pics2/womens-10b.jpg',
					'post_id' => '',
					'option_id_1' => $options[1]->option_id,
					'menulevel1_id_1' => $menulevel1_items[0]->menulevel1_id,
					'menulevel1_id_2' => $menulevel1_items[3]->menulevel1_id,
					'menulevel1_id_3' => '',
					'menulevel2_id_1' => $menulevel2_items[1]->menulevel2_id,
					'menulevel2_id_2' => 0,
					'menulevel2_id_3' => 0,
					'menulevel3_id_1' => $menulevel3_items[0]->menulevel3_id,
					'menulevel3_id_2' => 0,
					'menulevel3_id_3' => '',
					'featured_product_id_1' => 0,
					'featured_product_id_2' => 0,
					'featured_product_id_3' => 0,
					'featured_product_id_4' => 0,
				),
				(object) array(
					'product_id' => '',
					'model_number' => 'SKU0555',
					'title' => __( 'Professional Suit', 'wp-easycart' ),
					'price' => '239.95',
					'list_price' => '279.95',
					'stock_quantity' => 12,
					'manufacturer_id' => $manufacturers[1]->manufacturer_id,
					'image1' => 'https://support.wpeasycart.com/sampledata/v4/products/pics1/womens-11c.jpg',
					'image2' => 'https://support.wpeasycart.com/sampledata/v4/products/pics2/womens-11b.jpg',
					'post_id' => '',
					'option_id_1' => $options[1]->option_id,
					'menulevel1_id_1' => $menulevel1_items[0]->menulevel1_id,
					'menulevel1_id_2' => $menulevel1_items[2]->menulevel1_id,
					'menulevel1_id_3' => '',
					'menulevel2_id_1' => $menulevel2_items[2]->menulevel2_id,
					'menulevel2_id_2' => 0,
					'menulevel2_id_3' => 0,
					'menulevel3_id_1' => $menulevel3_items[0]->menulevel3_id,
					'menulevel3_id_2' => 0,
					'menulevel3_id_3' => '',
					'featured_product_id_1' => 0,
					'featured_product_id_2' => 0,
					'featured_product_id_3' => 0,
					'featured_product_id_4' => 0,
				),
			);
			$product_description = __( 'The beauty of WP EasyCart is that each product description is easily customizable using what is titled the administrative console. Not only does this component allow you to update and manage the whole website, it also provides a simple, easy to use means of providing unique pictures, descriptions, and specifications for each individual product!', 'wp-easycart' ) . "\r\r" . __( 'Your options with the administrative console are nearly endless. Do you want to bolster sales by showing similar items on product pages? No problem! The administrative console allows you to quickly set prices and sale prices, attach the product to multiple menu locations, add options (i.e. color and size) for each product, and optimize search engine key words and descriptions.', 'wp-easycart' ) . "\r\r" . __( 'Want customer feedback, also not a problem with WP EasyCart! We offer a five star feedback system that allows for customer reviews.', 'wp-easycart' ) . "\r\r" . __( "If the ease of use for this software isn't a large enough selling point, consider the download capabilities. This software package is capable of accommodating downloadable merchandise, including gift cards. This capability expands the potential services of this software by allowing for software downloads, music downloads, and photography businesses. This software is flexible and customizable. It fits into every business style, and businessperson's lifestyle.", 'wp-easycart' ) . "\r\r" . __( 'WP EasyCart is a company that prides itself on customer service and constant improvement. As part of this demo, we request that you provide feedback in our forum. As a company we are always looking for the next step and to provide the best options. Your feedback is essential for this process.', 'wp-easycart' );
			$product_width = 1;
			$product_height = 1;
			$product_length = 1;
			$product_weight = 1;
			$product_seo_description = __( 'This short description is useful for Search Engines to locate and display a small sentence or two regarding your product.  You should limit this text to a couple of sentences max.', 'wp-easycart' );
			$product_seo_keywords = __( 'seo keywords, easycart keywords, ecommerce keywords', 'wp-easycart' );
			$products_count = count( $products );
			for ( $i = 0; $i < $products_count; $i++ ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO `ec_product` ( 
					`is_demo_item`, `activate_in_store`, `show_on_startup`, `model_number`, `title`, `price`, `list_price`, 
					`stock_quantity`, `manufacturer_id`, `image1`, `image2`, `option_id_1`, 
					`menulevel1_id_1`, `menulevel1_id_2`, `menulevel1_id_3`, `menulevel2_id_1`, `menulevel2_id_2`, 
					`menulevel2_id_3`, `menulevel3_id_1`, `menulevel3_id_2`, `menulevel3_id_3`, `description`, 
					`seo_description`, `seo_keywords`, `width`, `height`, `length`, 
					`weight`, `is_taxable`, `vat_rate`
				) VALUES( 
					1, 1, 1, %s, %s, %s, %s,
					%d, %d, %s, %s, %d,
					%d, %d, %d, %d, %d, 
					%d, %d, %d, %d, %s,
					%s, %s, %d, %d, %d,
					%d, 1, 1 )', 
					$products[ $i ]->model_number, $products[ $i ]->title, $products[ $i ]->price, $products[ $i ]->list_price,
					$products[ $i ]->stock_quantity, $products[ $i ]->manufacturer_id, $products[ $i ]->image1, $products[ $i ]->image2, $products[ $i ]->option_id_1,
					$products[ $i ]->menulevel1_id_1, $products[ $i ]->menulevel1_id_2, $products[ $i ]->menulevel1_id_3, $products[ $i ]->menulevel2_id_1, $products[ $i ]->menulevel2_id_2,
					$products[ $i ]->menulevel2_id_3, $products[ $i ]->menulevel3_id_1, $products[ $i ]->menulevel3_id_2, $products[ $i ]->menulevel3_id_3, $product_description, 
					$product_seo_description, $product_seo_keywords, $product_width, $product_height, $product_length, 
					$product_weight
				) );

				$products[ $i ]->product_id = $wpdb->insert_id;

				$post = array(	
					'post_content' => '[ec_store modelnumber="' . $products[ $i ]->model_number . '"]',
					'post_status' => 'publish',
					'post_title' => $products[ $i ]->title,
					'post_type' => 'ec_store',
				);
				$products[ $i ]->post_id = wp_insert_post( $post );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET post_id = %d, featured_product_id_1 = %d, featured_product_id_2 = %d, featured_product_id_3 = %d, featured_product_id_4 = %d WHERE product_id = %d', $products[ $i ]->post_id, $products[rand( 0, count( $products ) - 1 )]->product_id, $products[rand( 0, count( $products ) - 1 )]->product_id, $products[rand( 0, count( $products ) - 1 )]->product_id, $products[rand( 0, count( $products ) - 1 )]->product_id, $products[ $i ]->product_id ) );
			}

			$reviews = array( 
				(object) array(
					'title' => __( 'Great', 'wp-easycart' ),
					'rating' => 5,
				),
				(object) array(
					'title' => __( 'Pretty Good', 'wp-easycart' ),
					'rating' => 4,
				),
				(object) array(
					'title' => __( 'Average', 'wp-easycart' ),
					'rating' => 3,
				),
				(object) array(
					'title' => __( 'Poor', 'wp-easycart' ),
					'rating' => 2,
				),
				(object) array(
					'title' => __( 'The Worst', 'wp-easycart' ),
					'rating' => 1,
				),
			);
			$review_description = __( 'This is a sample customer review and 5 star rating system. Each product can be designated to show a review and ratings are automatically calculated by EasyCart. All reviews go through an approval before they can be listed on the site and calculated, giving you the EasyCart owner control over what content may accidentally get posted.', 'wp-easycart' );
			$review_date = '2017-05-24 16:53:36';
			for ( $i = 0; $i < 50; $i++ ) {
				$rand_review_i = rand( 0, count( $reviews ) - 1 );
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_review( `product_id`, `approved`, `rating`, `title`, `description`, `date_submitted` ) VALUES( %d, 1, %d, %s, %s, %s )', $products[rand( 0, count( $products ) - 1)]->product_id, $reviews[ $rand_review_i ]->rating, $reviews[ $rand_review_i ]->title . __( ' Product Review!', 'wp-easycart' ), $review_description, $review_date ) );
			}

			$wpdb->query( $wpdb->prepare( 'INSERT INTO `ec_categoryitem` ( `category_id`, `product_id` ) VALUES (%d,%d), (%d,%d), (%d,%d), (%d,%d), (%d,%d), (%d,%d), (%d,%d), (%d,%d), (%d,%d), (%d,%d), (%d,%d), (%d,%d), (%d,%d), (%d,%d), (%d,%d)',
				$categories[2]->category_id, $products[4]->product_id,
				$categories[2]->category_id, $products[1]->product_id,
				$categories[2]->category_id, $products[6]->product_id,
				$categories[2]->category_id, $products[5]->product_id,

				$categories[1]->category_id, $products[2]->product_id,
				$categories[1]->category_id, $products[7]->product_id,
				$categories[1]->category_id, $products[4]->product_id,
				$categories[1]->category_id, $products[8]->product_id,

				$categories[0]->category_id, $products[3]->product_id,
				$categories[0]->category_id, $products[7]->product_id,
				$categories[0]->category_id, $products[5]->product_id,
				$categories[0]->category_id, $products[0]->product_id,
				$categories[0]->category_id, $products[2]->product_id,
				$categories[0]->category_id, $products[8]->product_id,
				$categories[0]->category_id, $products[9]->product_id
			) );

			for ( $i = 0; $i < 10; $i++ ) {
				$wpdb->query( $wpdb->prepare( "INSERT INTO `ec_order` ( 
					`is_demo_item`, `user_id`, `user_email`, `user_level`, `order_date`, `orderstatus_id`, 
					`order_weight`, `sub_total`, `tax_total`, `shipping_total`, `discount_total`, 
					`grand_total`, `shipping_method`, `billing_first_name`, `billing_last_name`, `billing_company_name`,

					`billing_address_line_1`, `billing_city`, `billing_state`, `billing_country`, `billing_zip`, 
					`billing_phone`, `shipping_first_name`, `shipping_last_name`, `shipping_company_name`, `shipping_address_line_1`, 
					`shipping_city`, `shipping_state`, `shipping_country`, `shipping_zip`, `shipping_phone`, 

					`payment_method` 
				) VALUES ( 
					1, %d, 'michelle@test.com', 'shopper', '2017-08-13 16:28:46', 2,
					'3.500', '154.890', '0.000', '7.950', '0.000', 
					'162.840', 'Ground Delivery (5-7  Days)', 'Michelle', 'Smith', 'WP EasyCart',

					'5555 SW North St.', 'Pendleton', 'OR', 'US', '97801', 
					'541 555 5555', 'Michelle', 'Smith', 'WP EasyCart', '5555 SW North St.', 
					'Pendleton', 'OR', 'US', '97801', '541 555 5555', 
					'Direct Deposit' 
				)", $user_id ) );
				$order_id = $wpdb->insert_id;
				$subtotal = 0;
				for ( $j = 0; $j < 3; $j++ ) {
					$rand_product_i = rand( 0, count( $products ) - 1 );
					$subtotal = $subtotal + $products[ $rand_product_i ]->price;
					$wpdb->query( $wpdb->prepare( "INSERT INTO `ec_orderdetail` (
						`order_id`, `product_id`, `title`, `model_number`, `order_date`,
						`unit_price`, `total_price`, `quantity`, `image1`, `optionitem_name_1`, 
						`optionitem_label_1`, `is_shippable`
					) VALUES ( 
						%d, %d, %s, %s, '2017-08-13 16:28:46',
						%s, %s, 1, %s, 'Large',
						'Shirt Size', 1
					)",
						$order_id, $products[rand( 0, count( $products ) - 1 )]->product_id, $products[ $rand_product_i ]->title, $products[ $rand_product_i ]->model_number,
						$products[ $rand_product_i ]->price, $products[ $rand_product_i ]->price,  $products[ $rand_product_i ]->image1
					) );
				}
				$grand_total = $subtotal + 7.95;
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_order SET sub_total = %s, grand_total = %s WHERE order_id = %d', $subtotal, $grand_total, $order_id ) );
			}

			$wpdb->query( "INSERT INTO `ec_shippingrate` ( `is_demo_item`, `zone_id`, `is_price_based`, `is_weight_based`, `is_method_based`, `is_ups_based`, `is_usps_based`, `is_fedex_based`, `is_auspost_based`, `is_dhl_based`, `trigger_rate`, `shipping_rate`, `shipping_label`, `shipping_order`, `shipping_code`, `shipping_override_rate`, `is_quantity_based`, `is_percentage_based`, `is_canadapost_based`, `free_shipping_at`) VALUES (1,0,0,0,1,0,0,0,0,0,0.000,0.000,__( 'In-Store Pickup', 'wp-easycart' ),1,'',NULL,0,0,0,0.000), (1,0,0,0,1,0,0,0,0,0,0.000,7.950,__( 'Ground Delivery (5-7  Days)', 'wp-easycart' ),2,'',NULL,0,0,0,0.000), (1,0,0,0,1,0,0,0,0,0,0.000,15.950,__( 'Next Day Air (1 Day)', 'wp-easycart' ),3,'',NULL,0,0,0,0.000)" );
			// $wpdb->query( "UPDATE ec_setting SET shipping_method = 'method'" );

			global $wp_rewrite;	
			$wp_rewrite->flush_rules();

			update_option( 'ec_option_demo_data_installed', 1 );
			do_action( 'wpeasycart_admin_demo_data_installed' );

		}

		public function uninstall_demo_data() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-initial-setup-demo-setup' ) ) {
				return false;
			}

			global $wpdb;

			$orders = $wpdb->get_results( 'SELECT order_id FROM ec_order WHERE is_demo_item = 1' );
			$orders_count = count( $orders );
			for ( $i = 0; $i < $orders_count; $i++ ) {
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_order WHERE order_id = %d', $orders[ $i ]->order_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_orderdetail WHERE order_id = %d', $orders[ $i ]->order_id ) );
			}

			$products = $wpdb->get_results( 'SELECT product_id, post_id FROM ec_product WHERE is_demo_item = 1' );
			$products_count = count( $products );
			for ( $i = 0; $i < $products_count; $i++ ) {
				wp_delete_post( $products[ $i ]->post_id, true );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_product WHERE product_id = %d', $products[ $i ]->product_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_review WHERE product_id = %d', $products[ $i ]->product_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_categoryitem WHERE product_id = %d', $products[ $i ]->product_id ) );
			}

			$options = $wpdb->get_results( 'SELECT option_id FROM ec_option WHERE is_demo_item = 1' );
			$options_count = count( $options );
			for ( $i = 0; $i < $options_count; $i++ ) {
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_option WHERE option_id = %d', $options[ $i ]->option_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_optionitem WHERE option_id = %d', $options[ $i ]->option_id ) );
			}

			$menus_level1 = $wpdb->get_results( 'SELECT menulevel1_id, post_id FROM ec_menulevel1 WHERE is_demo_item = 1' );
			$menus_level2 = $wpdb->get_results( 'SELECT menulevel2_id, post_id FROM ec_menulevel2 WHERE is_demo_item = 1' );
			$menus_level3 = $wpdb->get_results( 'SELECT menulevel3_id, post_id FROM ec_menulevel3 WHERE is_demo_item = 1' );
			$menu_level1_count = count( $menus_level1 );
			for ( $i = 0; $i < $menu_level1_count; $i++ ) {
				wp_delete_post( $menus_level1[ $i ]->post_id, true );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_menulevel1 WHERE menulevel1_id = %d', $menus_level1[ $i ]->menulevel1_id ) );
			}
			$menu_level2_count = count( $menus_level2 );
			for ( $i = 0; $i < $menu_level2_count; $i++ ) {
				wp_delete_post( $menus_level2[ $i ]->post_id, true );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_menulevel2 WHERE menulevel2_id = %d', $menus_level2[ $i ]->menulevel2_id ) );
			}
			$menu_level3_count = count( $menus_level3 );
			for ( $i = 0; $i < $menu_level3_count; $i++ ) {
				wp_delete_post( $menus_level3[ $i ]->post_id, true );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_menulevel3 WHERE menulevel3_id = %d', $menus_level3[ $i ]->menulevel3_id ) );
			}

			$manufacturers = $wpdb->get_results( 'SELECT manufacturer_id, post_id FROM ec_manufacturer WHERE is_demo_item = 1' );
			$manufacturers_count = count( $manufacturers );
			for ( $i = 0; $i < $manufacturers_count; $i++ ) {
				wp_delete_post( $manufacturers[ $i ]->post_id, true );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_manufacturer WHERE manufacturer_id = %d', $manufacturers[ $i ]->manufacturer_id ) );
			}

			$categories = $wpdb->get_results( 'SELECT category_id, post_id FROM ec_category WHERE is_demo_item = 1' );
			$categories_count = count( $categories );
			for ( $i = 0; $i < $categories_count; $i++ ) {
				wp_delete_post( $categories[ $i ]->post_id, true );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_category WHERE category_id = %d', $categories[ $i ]->category_id ) );
			}

			$users = $wpdb->get_results( 'SELECT user_id, default_billing_address_id, default_shipping_address_id FROM ec_user WHERE is_demo_item = 1' );
			$users_count = count( $users );
			for ( $i = 0; $i < $users_count; $i++ ) {
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_user WHERE user_id = %d', $users[ $i ]->user_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_address WHERE address_id = %d', $users[ $i ]->default_billing_address_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_address WHERE address_id = %d', $users[ $i ]->default_shipping_address_id ) );
			}

			$wpdb->query( 'DELETE FROM ec_shippingrate WHERE is_demo_item = 1' );
			update_option( 'ec_option_demo_data_installed', 0 );

		}

		private function ec_admin_recursive_remove_dir( $dir ) { 
			if ( is_dir( $dir ) ) {
				$objects = scandir( $dir );
				foreach ( $objects as $object ) {
					if ( $object != '.' && $object != '..' ) {
						if ( filetype( $dir . '/' . $object ) == 'dir' ) {
							$this->ec_admin_recursive_remove_dir( $dir . '/' . $object );
						} else {
							unlink( $dir . '/' . $object );
						}
					}
				}
				reset( $objects );
				rmdir( $dir );
			}
		}
	}
endif; // End if class_exists check

function wp_easycart_admin_initial_setup() {
	return wp_easycart_admin_initial_setup::instance();
}
wp_easycart_admin_initial_setup();

add_action( 'wp_ajax_ec_admin_ajax_save_storepage', 'ec_admin_ajax_save_storepage' );
function ec_admin_ajax_save_storepage() {
	wp_easycart_admin_initial_setup()->save_storepage();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_create_storepage', 'ec_admin_ajax_create_storepage' );
function ec_admin_ajax_create_storepage() {
	$post_id = wp_easycart_admin_initial_setup()->add_storepage();
	echo esc_attr( $post_id );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_cartpage', 'ec_admin_ajax_save_cartpage' );
function ec_admin_ajax_save_cartpage() {
	wp_easycart_admin_initial_setup()->save_cartpage();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_create_cartpage', 'ec_admin_ajax_create_cartpage' );
function ec_admin_ajax_create_cartpage() {
	$post_id = wp_easycart_admin_initial_setup()->add_cartpage();
	echo esc_attr( $post_id );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_accountpage', 'ec_admin_ajax_save_accountpage' );
function ec_admin_ajax_save_accountpage() {
	wp_easycart_admin_initial_setup()->save_accountpage();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_create_accountpage', 'ec_admin_ajax_create_accountpage' );
function ec_admin_ajax_create_accountpage() {
	$post_id = wp_easycart_admin_initial_setup()->add_accountpage();
	echo esc_attr( $post_id );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_install_demo_data', 'ec_admin_ajax_install_demo_data' );
function ec_admin_ajax_install_demo_data() {
	wp_easycart_admin_initial_setup()->install_demo_data();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_uninstall_demo_data', 'ec_admin_ajax_uninstall_demo_data' );
function ec_admin_ajax_uninstall_demo_data() {
	wp_easycart_admin_initial_setup()->uninstall_demo_data();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_currency_options', 'ec_admin_ajax_save_currency_options' );
function ec_admin_ajax_save_currency_options() {
	wp_easycart_admin_initial_setup()->save_currency_settings();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_save_goals_setup', 'ec_admin_ajax_save_goals_setup' );
function ec_admin_ajax_save_goals_setup() {
	wp_easycart_admin_initial_setup()->save_goal_setup();
	die();
}