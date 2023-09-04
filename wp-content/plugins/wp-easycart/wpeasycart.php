<?php
/**
 * Plugin Name: WP EasyCart
 * Plugin URI: http://www.wpeasycart.com
 * Description: The WordPress Shopping Cart by WP EasyCart is a simple eCommerce solution that installs into new or existing WordPress blogs. Customers purchase directly from your store! Get a full ecommerce platform in WordPress! Sell products, downloadable goods, gift cards, clothing and more! Now with WordPress, the powerful features are still very easy to administrate! If you have any questions, please view our website at <a href="http://www.wpeasycart.com" target="_blank">WP EasyCart</a>.

 * Version: 5.5.7
 * Author: WP EasyCart
 * Author URI: http://www.wpeasycart.com
 * Text Domain: wp-easycart
 * Domain Path: /languages
 *
 * This program is free to download and install and sell with PayPal. Although we offer a ton of FREE features, some of the more advanced features and payment options requires the purchase of our professional shopping cart admin plugin. Professional features include alternate third party gateways, live payment gateways, coupons, promotions, advanced product features, and much more!
 *
 * @package wpeasycart
 * @version 5.5.7
 * @author WP EasyCart <sales@wpeasycart.com>
 * @copyright Copyright (c) 2012, WP EasyCart
 * @link http://www.wpeasycart.com
 */

define( 'EC_PUGIN_NAME', 'WP EasyCart' );
define( 'EC_PLUGIN_DIRECTORY', __DIR__ );
define( 'EC_PLUGIN_DATA_DIRECTORY', __DIR__ . '-data' );
define( 'EC_CURRENT_VERSION', '5_5_7' );
define( 'EC_CURRENT_DB', '1_30' );/* Backwards Compatibility */
define( 'EC_UPGRADE_DB', '87' );

require_once( EC_PLUGIN_DIRECTORY . '/inc/ec_config.php' );

add_action( 'init', 'wpeasycart_load_startup', 1 );
add_action( 'plugins_loaded', 'wpeasycart_load_translation', 1 );
add_action( 'widgets_init', 'wpeasycart_register_widgets' );
add_filter( 'upload_mimes', 'wp_easycart_add_allow_uploads_admin', 1, 1 );

function wp_easycart_add_allow_uploads_admin( $mimes ) {
	$mimes['csv'] = 'text/csv';
	$mimes['pdf'] = 'application/pdf';
	$mimes['zip'] = 'application/zip';
	$mimes['gzip'] = 'application/x-gzip';
	return $mimes;
}

function wpeasycart_load_translation() {
	load_plugin_textdomain( 'wp-easycart', '', basename( dirname( __FILE__ ) ) . '/languages' );
}

function wpeasycart_load_startup() {

	ec_setup_hooks();

	if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/ec_hooks.php" ) ) {
		include( EC_PLUGIN_DATA_DIRECTORY . "/ec_hooks.php" );
	}

	if ( ! is_admin() && get_option( 'ec_option_load_ssl' ) && ! is_ssl() && ( ! defined( 'WP_CLI' ) || ! WP_CLI ) ) {
		$redirect_url = 'https://' . sanitize_text_field( $_SERVER['HTTP_HOST'] ) . sanitize_text_field( $_SERVER['REQUEST_URI'] );
		wp_redirect( $redirect_url, 301 );
		exit;
	}

	if ( version_compare( str_replace( '_', '.', EC_CURRENT_VERSION ), get_option( 'ec_option_db_version_updated' ), '>' ) ) {
		$db_manager = new ec_db_manager();
		$db_manager->try_db_update();
	}

	do_action( 'wp_easycart_startup' );
}

function wpeasycart_register_widgets() {
	register_widget( 'ec_categorywidget' );
	register_widget( 'ec_cartwidget' );
	register_widget( 'ec_colorwidget' );
	register_widget( 'ec_currencywidget' );
	register_widget( 'ec_donationwidget' );
	register_widget( 'ec_groupwidget' );
	register_widget( 'ec_languagewidget' );
	register_widget( 'ec_loginwidget' );
	register_widget( 'ec_manufacturerwidget' );
	register_widget( 'ec_menuwidget' );
	register_widget( 'ec_newsletterwidget' );
	register_widget( 'ec_pricepointwidget' );
	register_widget( 'ec_productwidget' );
	register_widget( 'ec_searchwidget' );
	register_widget( 'ec_specialswidget' );
}

function ec_activate() {

	global $wpdb;

	$wpoptions = new ec_wpoptionset();
	$wpoptions->add_options();
	update_option( 'ec_option_wpoptions_version', EC_CURRENT_VERSION );

	if ( ! get_option( 'ec_option_db_new_version' ) || EC_UPGRADE_DB != get_option( 'ec_option_db_new_version' ) ) {
		$db_manager = new ec_db_manager();
		$db_manager->install_db();
		update_option( 'ec_option_is_installed', '1' );
	}

	$mysqli = new ec_db();

	$site = explode( "://", ec_get_url() );
	$site = $site[1];
	$mysqli->update_url( $site );
	
	$GLOBALS['ec_cart_data'] = new ec_cart_data( ( ( isset( $GLOBALS['ec_cart_id'] ) ) ? $GLOBALS['ec_cart_id'] : 'not-set' ) );
	$GLOBALS['ec_cart_data']->restore_session_from_db();
	wp_easycart_language()->update_language_data(); //Do this to update the database if a new language is added

	update_option( 'ec_option_is_installed', '1' );

	if ( '&#36;' == get_option( 'ec_option_currency' ) ) {
		update_option( 'ec_option_currency', '$' );
	}
	
	if ( ! is_dir( EC_PLUGIN_DATA_DIRECTORY . '/' ) ) {

		$to = EC_PLUGIN_DATA_DIRECTORY . '/';
		$from = EC_PLUGIN_DIRECTORY . '/';

		if ( ! is_writable( plugin_dir_path( __FILE__ ) ) ) {
			// We really can't do anything now about the data folder. Lets try and get people to do this in the install page.

		} else {
			mkdir( EC_PLUGIN_DATA_DIRECTORY . "/", 0755 );
			mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/", 0755 );

			wpeasycart_copyr( $from . "products", $to . "products" );
			if ( !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/design/" ) ) {
				mkdir( EC_PLUGIN_DATA_DIRECTORY . "/design/", 0755 );
			}
			if ( !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" ) ) {
				mkdir( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/", 0755 );
			}
			if ( !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/custom-theme/" ) ) {
				mkdir( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/custom-theme/", 0755 );
			}
			if ( !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/design/layout/" ) ) {
				mkdir( EC_PLUGIN_DATA_DIRECTORY . "/design/layout/", 0755 );
			}
			if ( !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/design/layout/custom-layout/" ) ) {
				mkdir( EC_PLUGIN_DATA_DIRECTORY . "/design/layout/custom-layout/", 0755 );
			}
			if ( !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/banners/" ) ) {
				mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/banners/", 0755 );
			}
			if ( !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/categories/" ) ) {
				mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/categories/", 0751 );
			}
			if ( !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/downloads/" ) ) {
				mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/downloads/", 0751 );
			}
			if ( !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" ) ) {
				mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/", 0755 );
			}
			if ( !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics2/" ) ) {
				mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics2/", 0755 );
			}
			if ( !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics3/" ) ) {
				mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics3/", 0755 );
			}
			if ( !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics4/" ) ) {
				mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics4/", 0755 );
			}
			if ( !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics5/" ) ) {
				mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics5/", 0755 );
			}
			if ( !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/swatches/" ) ) {
				mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/swatches/", 0755 );
			}
			if ( !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/uploads/" ) ) {
				mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/uploads/", 0751 );
			}

		}
	}

	if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/" ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/design/" ) ) {
		mkdir( EC_PLUGIN_DATA_DIRECTORY . "/design/", 0755 );
	}

	if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" ) ) {
		mkdir( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/", 0755 );
	}

	if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/custom-theme/" ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/custom-theme/" ) ) {
		mkdir( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/custom-theme/", 0755 );
	}

	if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/layout/" ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/design/layout/" ) ) {
		mkdir( EC_PLUGIN_DATA_DIRECTORY . "/design/layout/", 0755 );
	}

	if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/layout/custom-layout/" ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/design/layout/custom-layout/" ) ) {
		mkdir( EC_PLUGIN_DATA_DIRECTORY . "/design/layout/custom-layout/", 0755 );
	}

	if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/" ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/" ) ) {
		mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/", 0755 );
	}

	if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/banners/" ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/banners/" ) ) {
		mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/banners/", 0755 );
	}

	if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/categories/" ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/categories/" ) ) {
		mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/categories/", 0751 );
	}

	if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/downloads/" ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/downloads/" ) ) {
		mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/downloads/", 0751 );
	}

	if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" ) ) {
		mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/", 0755 );
	}

	if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/pics2/" ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics2/" ) ) {
		mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics2/", 0755 );
	}

	if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/pics3/" ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics3/" ) ) {
		mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics3/", 0755 );
	}

	if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/pics4/" ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics4/" ) ) {
		mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics4/", 0755 );
	}

	if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/pics5/" ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics5/" ) ) {
		mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics5/", 0755 );
	}

	if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/swatches/" ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/swatches/" ) ) {
		mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/swatches/", 0755 );
	}

	if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/uploads/" ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/uploads/" ) ) {
		mkdir( EC_PLUGIN_DATA_DIRECTORY . "/products/uploads/", 0751 );
	}

	if ( get_option( 'ec_option_allow_tracking' ) && '1' == get_option( 'ec_option_allow_tracking' ) && ! function_exists( 'wp_easycart_admin_tracking' ) ) {
		include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_tracking.php' );
	}
	do_action( 'wpeasycart_activated' );
}

function ec_uninstall() {

	$db_manager = new ec_db_manager();
	$db_manager->uninstall_db();

	$wpoptions = new ec_wpoptionset();
	$wpoptions->delete_options();

	$data_dir = EC_PLUGIN_DATA_DIRECTORY . "/";
	if ( is_dir( $data_dir ) && ! is_writable( $data_dir ) ) {
		$ftp_server = sanitize_text_field( $_POST['hostname'] );
		$ftp_user_name = sanitize_text_field( $_POST['username'] );
		$ftp_user_pass = $_POST['password']; // XSS OK. Do not sanitize password.

		$conn_id = ftp_connect( $ftp_server ) or die( esc_attr( 'Couldn\'t connect to ' . $ftp_server ) );

		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

		if ( !$login_result ) {
			die( "Could not connect to your server via FTP to uninstall your wp-easycart. Please remove the files manually." );

		} else {
			ec_delete_directory_ftp( $conn_id, $data_dir );
		}
	} else {
		ec_recursive_remove_directory( $data_dir );
	}

	$store_posts = get_posts( array( 'post_type' => 'ec_store', 'posts_per_page' => 10000 ) );
	foreach ( $store_posts as $store_post ) {
		wp_delete_post( $store_post->ID, true);
	}

	wp_clear_scheduled_hook( 'wp_easycart_square_renew_token' );
}

function wpeasycart_update_check() {
	if ( ! get_option( 'ec_option_wpoptions_version' ) || get_option( 'ec_option_wpoptions_version' ) != EC_CURRENT_VERSION ) {
		$wpoptions = new ec_wpoptionset();
		$wpoptions->add_options();
		wp_easycart_language()->update_language_data();
		update_option( 'ec_option_wpoptions_version', EC_CURRENT_VERSION );
	}

	if ( is_admin() && ! get_option( 'ec_option_db_new_version' ) || EC_UPGRADE_DB != get_option( 'ec_option_db_new_version' ) ) {
		$db_manager = new ec_db_manager();
		$db_manager->install_db();
		update_option( 'ec_option_is_installed', '1' );
	}

	if ( !get_option( 'ec_option_data_folders_installed' ) || EC_CURRENT_VERSION != get_option( 'ec_option_data_folders_installed' ) ) {

		if ( !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/" ) ) {

			$to = EC_PLUGIN_DATA_DIRECTORY . '/';
			$from = EC_PLUGIN_DIRECTORY . '/';

			if ( ! is_writable( plugin_dir_path( __FILE__ ) ) ) {
				// We really can't do anything now about the data folder. Lets try and get people to do this in the install page.

			} else {
				mkdir( $to, 0755 );
				wpeasycart_copyr( $from . 'products', $to . 'products' );
				mkdir( EC_PLUGIN_DATA_DIRECTORY . '/design/', 0755 );
				mkdir( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/', 0755 );
				mkdir( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/custom-theme/', 0755 );
				mkdir( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/', 0755 );
				mkdir( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/custom-layout/', 0755 );
				mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/banners/', 0755 );
				mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/categories/', 0751 );
				mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/downloads/', 0751 );
				mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/pics1/', 0755 );
				mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/pics2/', 0755 );
				mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/pics3/', 0755 );
				mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/pics4/', 0755 );
				mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/pics5/', 0755 );
				mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/swatches/', 0755 );
				mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/uploads/', 0751 );
			}
		}

		if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/' ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . '/design/' ) ) {
			mkdir( EC_PLUGIN_DATA_DIRECTORY . '/design/', 0755 );
		}

		if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/' ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/' ) ) {
			mkdir( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/', 0755 );
		}

		if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/custom-theme/' ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/custom-theme/' ) ) {
			mkdir( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/custom-theme/', 0755 );
		}

		if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' ) ) {
			mkdir( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/', 0755 );
		}

		if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/custom-layout/' ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/custom-layout/' ) ) {
			mkdir( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/custom-layout/', 0755 );
		}

		if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . '/products/' ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . '/products/' ) ) {
			mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/', 0755 );
		}

		if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . '/products/banners/' ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . '/products/banners/' ) ) {
			mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/banners/', 0755 );
		}

		if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . '/products/categories/' ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . '/products/categories/' ) ) {
			mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/categories/', 0751 );
		}

		if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . '/products/downloads/' ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . '/products/downloads/' ) ) {
			mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/downloads/', 0751 );
		}

		if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . '/products/pics1/' ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . '/products/pics1/' ) ) {
			mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/pics1/', 0755 );
		}

		if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . '/products/pics2/' ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . '/products/pics2/' ) ) {
			mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/pics2/', 0755 );
		}

		if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . '/products/pics3/' ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . '/products/pics3/' ) ) {
			mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/pics3/', 0755 );
		}

		if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . '/products/pics4/' ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . '/products/pics4/' ) ) {
			mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/pics4/', 0755 );
		}

		if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . '/products/pics5/' ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . '/products/pics5/' ) ) {
			mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/pics5/', 0755 );
		}

		if ( ! file_exists( EC_PLUGIN_DATA_DIRECTORY . '/products/swatches/' ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . '/products/swatches/' ) ) {
			mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/swatches/', 0755 );
		}

		if ( !file_exists( EC_PLUGIN_DATA_DIRECTORY . '/products/uploads/' ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . '/products/uploads/' ) ) {
			mkdir( EC_PLUGIN_DATA_DIRECTORY . '/products/uploads/', 0751 );
		}

		update_option( 'ec_option_data_folders_installed', EC_CURRENT_VERSION );
	}

}
add_action( 'plugins_loaded', 'wpeasycart_update_check' );
register_activation_hook( __FILE__, 'ec_activate' );
register_uninstall_hook( __FILE__, 'ec_uninstall' );

function load_ec_pre() {

	$storepageid = get_option('ec_option_storepage');
	$cartpageid = get_option('ec_option_cartpage');
	$accountpageid = get_option('ec_option_accountpage');

	if ( function_exists( 'icl_object_id' ) ) {
		$storepageid = icl_object_id( $storepageid, 'page', true, ICL_LANGUAGE_CODE );
		$cartpageid = icl_object_id( $cartpageid, 'page', true, ICL_LANGUAGE_CODE );
		$accountpageid = icl_object_id( $accountpageid, 'page', true, ICL_LANGUAGE_CODE );
	}

	$storepage = get_permalink( $storepageid );
	$cartpage = get_permalink( $cartpageid );
	$accountpage = get_permalink( $accountpageid );

	if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
		$https_class = new WordPressHTTPS();
		$storepage = $https_class->makeUrlHttps( $storepage );
		$cartpage = $https_class->makeUrlHttps( $cartpage );
		$accountpage = $https_class->makeUrlHttps( $accountpage );
	}

	if (substr_count($storepage, '?'))							$permalinkdivider = "&";
	else														$permalinkdivider = "?";

	if ( isset( $_SERVER['HTTPS'] ) )							$currentpageid = url_to_postid( "https://" . sanitize_text_field( $_SERVER['SERVER_NAME'] ) . sanitize_text_field( $_SERVER['REQUEST_URI'] ) );
	else														$currentpageid = url_to_postid( "http://" . sanitize_text_field( $_SERVER['SERVER_NAME'] ) . sanitize_text_field( $_SERVER['REQUEST_URI'] ) );

	$cartpage = apply_filters( 'wp_easycart_cart_page_url', $cartpage );

	if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "checkout_success" && isset( $_GET['error_description'] ) && get_option( 'ec_option_payment_third_party' ) == "dwolla_thirdparty" ) {
		$db = new ec_db();
		$db->insert_response( (int) $_GET['order_id'], 1, "Dwolla Third Party", print_r( $_GET, true ) );
		header( "location: " . $accountpage . $permalinkdivider . "ec_page=order_details&order_id=" . (int) $_GET['order_id'] . "&ec_error=dwolla_error" );

	} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "checkout_success" && get_option( 'ec_option_payment_third_party' ) == "dwolla_thirdparty" && isset( $_GET['signature'] ) && isset( $_GET['checkoutId'] ) && isset( $_GET['amount'] ) ) {

		$dwolla_verification = ec_dwolla_verify_signature( sanitize_text_field( $_GET['signature'] ), sanitize_text_field( $_GET['checkoutId'] ), sanitize_text_field( $_GET['amount'] ) );
		if ( $dwolla_verification ) {
			global $wpdb;
			$db = new ec_db_admin();
			$db->update_order_status( (int) $_GET['order_id'], "10" );

			// send email
			$order_row = $db->get_order_row_admin( (int) $_GET['order_id'] );
			$orderdetails = $db->get_order_details_admin( (int) $_GET['order_id'] );

			/* Update Stock Quantity */
			foreach ( $orderdetails as $orderdetail ) {
				$product = $wpdb->get_row( $wpdb->prepare( "SELECT ec_product.* FROM ec_product WHERE ec_product.product_id = %d", $orderdetail->product_id ) );
				if ( $product ) {
					if ( $product->use_optionitem_quantity_tracking )	
						$db->update_quantity_value( $orderdetail->quantity, $orderdetail->product_id, $orderdetail->optionitem_id_1, $orderdetail->optionitem_id_2, $orderdetail->optionitem_id_3, $orderdetail->optionitem_id_4, $orderdetail->optionitem_id_5 );
					$db->update_product_stock( $orderdetail->product_id, $orderdetail->quantity );
				}
			}

			$order_display = new ec_orderdisplay( $order_row, true );
			$order_display->send_email_receipt();
			$order_display->send_gift_cards();

			do_action( 'wpeasycart_order_paid', $this->order_id );

			header( "location: " . $cartpage . $permalinkdivider . "ec_page=checkout_success&order_id=" . (int) $_GET['order_id'] );

		} else {
			$db = new ec_db();
			$db->insert_response( (int) $_GET['order_id'], 1, "Dwolla Third Party", print_r( $_GET, true ) );
			header( "location: " . $accountpage . $permalinkdivider . "ec_page=order_details&order_id=" . (int) $_GET['order_id'] . "&ec_error=dwolla_error" );

		}
	}

	/* Update the Menu and Product Statistics */
	if ( isset( $_GET['model_number'] ) ) {
		$db = new ec_db();
		$db->update_product_views( sanitize_text_field( $_GET['model_number'] ) );
	} else if ( isset( $_GET['menuid'] ) ) {
		$db = new ec_db();
		$db->update_menu_views( (int) $_GET['menuid'] );	
	} else if ( isset( $_GET['submenuid'] ) ) {
		$db = new ec_db();
		$db->update_submenu_views( (int) $_GET['submenuid'] );	
	} else if ( isset( $_GET['subsubmenuid'] ) ) {
		$db = new ec_db();
		$db->update_subsubmenu_views( (int) $_GET['subsubmenuid'] );	
	}

	/* Cart Form Actions, Process Prior to WP Loading */
	if ( isset( $_POST['ec_cart_form_action'] ) ) {
		$ec_cartpage = new ec_cartpage();
		$ec_cartpage->process_form_action( sanitize_key( $_POST['ec_cart_form_action'] ) );
	} else if ( isset( $_GET['ec_cart_action'] ) ) {
		$ec_cartpage = new ec_cartpage();
		$ec_cartpage->process_form_action( sanitize_key( $_GET['ec_cart_action'] ) );	
	} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "3dsecure" ) {
		$ec_cartpage = new ec_cartpage();
		$ec_cartpage->process_form_action( "3dsecure" );
	} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "3ds" ) {
		$ec_cartpage = new ec_cartpage();
		$ec_cartpage->process_form_action( "3ds" );
	} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "3dsprocess" ) {
		$ec_cartpage = new ec_cartpage();
		$ec_cartpage->process_form_action( "3dsprocess" );
	} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "third_party" ) {
		$ec_cartpage = new ec_cartpage();
		$ec_cartpage->process_form_action( "third_party_forward" );
	} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "realex_redirect" ) {
		$ec_cartpage = new ec_cartpage();
		$ec_cartpage->process_form_action( "realex_redirect" );
	} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "realex_response" ) {
		$ec_cartpage = new ec_cartpage();
		$ec_cartpage->process_form_action( "realex_response" );
	} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "process_affirm" ) {
		$ec_cartpage = new ec_cartpage( true );
		$ec_cartpage->process_form_action( "submit_order" );
	} else if ( isset( $_GET['ec_action'] ) && $_GET['ec_action'] == "deconetwork_add_to_cart" ) {
		$ec_cartpage = new ec_cartpage( true );
		$ec_cartpage->process_form_action( "deconetwork_add_to_cart" );
	} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "checkout_success" && isset( $_GET['ec_action'] ) && $_GET['ec_action'] == "paymentexpress" ) {
		$ec_cartpage = new ec_cartpage();
		$ec_cartpage->process_form_action( "paymentexpress_thirdparty_response" );
	} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "nets_return" && isset( $_GET['transactionId'] ) ) {
		global $wpdb;
		$order_id = $wpdb->get_var( $wpdb->prepare( "SELECT ec_order.order_id FROM ec_order WHERE ec_order.nets_transaction_id = %s", sanitize_text_field( $_GET['transactionId'] ) ) );

		$nets = new ec_nets();
		$nets->process_payment_final( 
			$order_id, 
			htmlspecialchars( sanitize_text_field( $_GET['transactionId'] ), ENT_QUOTES ), 
			htmlspecialchars( sanitize_text_field( $_GET['responseCode'] ), ENT_QUOTES ) 
		);
	} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "wp-easycart-sagepay-za" ) {
		$sagepay_za = new ec_sagepay_paynow_za();
		$sagepay_za->process_response();
	} else if ( isset( $_GET['stripe'] ) && $_GET['stripe'] == 'returning' && isset( $_GET['payment_intent_client_secret'] ) && isset( $_GET['payment_intent'] ) ) {
		$ec_cartpage = new ec_cartpage( true );
		$ec_cartpage->process_form_action( "stripe_redirect_action" );
	}

	/* Account Form Actions, Process Prior to WP Loading */
	if ( isset( $_POST['ec_account_form_action'] ) ) {
		$ec_accountpage = new ec_accountpage();
		$ec_accountpage->process_form_action( sanitize_key( $_POST['ec_account_form_action'] ) );

	} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "logout" ) {
		$ec_accountpage = new ec_accountpage();
		$ec_accountpage->process_form_action( "logout" );

	} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "print_receipt" ) {
		include( EC_PLUGIN_DIRECTORY . "/inc/scripts/print_receipt.php" );
		die();

	} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "activate_account" && isset( $_GET['email'] ) && isset( $_GET['key'] ) ) {
		$db = new ec_db();
		$is_activated = $db->activate_user( sanitize_email( $_GET['email'] ), sanitize_text_field( $_GET['key'] ) );
		if ( $is_activated ) {
			header( "location: " . $account_page . $permalinkdivider . "ec_page=login&account_success=activation_success" );
		} else {
			header( "location: " . $account_page . $permalinkdivider . "ec_page=login&account_error=activation_error" );
		}
	}

	if ( isset( $_GET['ec_add_to_cart'] ) ) {

		global $wpdb;

		wpeasycart_session()->handle_session();
		wp_easycart_apply_query_coupon();

		// Verify Product can be added
		$product = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_product WHERE model_number = %s', sanitize_text_field( $_GET['ec_add_to_cart'] ) ) );
		if ( ! $product ) {
			header( "location: " . $cartpage );
			die();
		}
		
		if ( ( ! $product->use_advanced_optionset || $product->use_both_option_types ) && ( $product->option_id_1 != 0 || $product->option_id_2 != 0 || $product->option_id_3 != 0 || $product->option_id_4 != 0 || $product->option_id_5 != 0 ) ) {
			header( "location: " . $storepage . "?model_number=" . htmlspecialchars( sanitize_text_field( $_GET['ec_add_to_cart'] ), ENT_QUOTES ) );
			die();
		}
		
		if ( $product->use_advanced_optionset || $product->use_both_option_types ) {
			$advanced_options_check = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_option_to_product WHERE product_id = %d', $product->product_id ) );
			if ( count( $advanced_options_check ) > 0 ) {
				header( "location: " . $storepage . "?model_number=" . htmlspecialchars( sanitize_text_field( $_GET['ec_add_to_cart'] ), ENT_QUOTES ) );
				die();
			}
		}

		$db = new ec_db();
		$tempcart_id = $db->quick_add_to_cart( sanitize_text_field( $_GET['ec_add_to_cart'] ) );

		if ( $tempcart_id ) {

			$product = $wpdb->get_row( $wpdb->prepare( "SELECT product_id, model_number, option_id_1, option_id_2, option_id_3, option_id_4, option_id_5, use_advanced_optionset FROM ec_product WHERE model_number = %s", sanitize_text_field( $_GET['ec_add_to_cart'] ) ) );
			if ( $product ) {

				$product_id = $product->product_id;
				$use_advanced_optionset = $product->use_advanced_optionset;
				$option_vals = array();

				if ( $use_advanced_optionset ) {

					$optionsets = $GLOBALS['ec_advanced_optionsets']->get_advanced_optionsets( $product_id );
					$grid_quantity = 0;

					foreach ( $optionsets as $optionset ) {
						if ( $optionset->option_meta['url_var'] != "" && isset( $_GET[$optionset->option_meta['url_var']] ) ) {

							if ( $optionset->option_type == "checkbox" ) {
								$selected_optionitems = array();
								if ( is_array( $_GET[$optionset->option_meta['url_var']] ) ) {
									foreach ( (array) $_GET[$optionset->option_meta['url_var']] as $selected_optionitem ) { // XSS OK. Forced array and each item sanitized.
										$selected_optionitems[] = sanitize_text_field( $selected_optionitem );
									}
								} else {
									$selected_optionitems[] = sanitize_text_field( $_GET[$optionset->option_meta['url_var']] );
								}
								$optionitems = $db->get_advanced_optionitems( $optionset->option_id );
								foreach ( $optionitems as $optionitem ) {
									if ( in_array( $optionitem->optionitem_name, $selected_optionitems ) ) {
										$option_vals[] = array( "option_id" => $optionset->option_id, "optionitem_id" => $optionitem->optionitem_id, "option_name" => $optionitem->option_name, "optionitem_name" => $optionitem->optionitem_name, "option_type" => $optionitem->option_type, "optionitem_value" => $optionitem->optionitem_name, "optionitem_model_number" => $optionitem->optionitem_model_number );
									}
								}
							} else if ( $optionset->option_type == "combo" || $optionset->option_type == "swatch" || $optionset->option_type == "radio" ) {
								$optionitems = $db->get_advanced_optionitems( $optionset->option_id );
								foreach ( $optionitems as $optionitem ) {
									if ( $optionitem->optionitem_name == $_GET[$optionset->option_meta['url_var']] ) {
										$option_vals[] = array( "option_id" => $optionset->option_id, "optionitem_id" => $optionitem->optionitem_id, "option_name" => $optionitem->option_name, "optionitem_name" => $optionitem->optionitem_name, "option_type" => $optionitem->option_type, "optionitem_value" => $optionitem->optionitem_name, "optionitem_model_number" => $optionitem->optionitem_model_number );
									}
								}
							} else {
								$optionitems = $db->get_advanced_optionitems( $optionset->option_id );
								foreach ( $optionitems as $optionitem ) {
									$option_vals[] = array( "option_id" => $optionset->option_id, "optionitem_id" => $optionitem->optionitem_id, "option_name" => $optionitem->option_name, "optionitem_name" => $optionitem->optionitem_name, "option_type" => $optionitem->option_type, "optionitem_value" => stripslashes( sanitize_text_field( $_GET[$optionset->option_meta['url_var']] ) ), "optionitem_model_number" => $optionitem->optionitem_model_number );
								}
							}
						}

					} //end foreach

				} else {// else use basic
					$option_id_1 = $option_id_2 = $option_id_3 = $option_id_4 = $option_id_5 = 0;

					if ( $product->option_id_1 || $product->option_id_2 || $product->option_id_3 || $product->option_id_4 || $product->option_id_5 ) {
						$products = $db->get_product_list( $wpdb->prepare( " WHERE product.model_number = %s AND product.activate_in_store = 1", $product->model_number ), "", "", "", "wpeasycart-product-only-".$product->model_number );
						if ( count( $products ) ) {
							$product_item = new ec_product( $products[0], 0, 1, 0 );
							if ( $product_item->has_options ) {
								if ( $product->option_id_1 && $product_item->options->optionset1->option_meta['url_var'] != '' && isset( $_GET[$product_item->options->optionset1->option_meta['url_var']] ) ) {
									for ( $j=0; $j<count( $product_item->options->optionset1->optionset ); $j++ ) {
										if ( $_GET[$product_item->options->optionset1->option_meta['url_var']] == $product_item->options->optionset1->optionset[$j]->optionitem_name ) {
											$option_id_1 = $product_item->options->optionset1->optionset[$j]->optionitem_id;
										}
									}
								}

								if ( $product->option_id_2 && $product_item->options->optionset2->option_meta['url_var'] != '' && isset( $_GET[$product_item->options->optionset2->option_meta['url_var']] ) ) {
									for ( $j=0; $j<count( $product_item->options->optionset2->optionset ); $j++ ) {
										if ( $_GET[$product_item->options->optionset2->option_meta['url_var']] == $product_item->options->optionset2->optionset[$j]->optionitem_name ) {
											$option_id_2 = $product_item->options->optionset2->optionset[$j]->optionitem_id;
										}
									}
								}

								if ( $product->option_id_3 && $product_item->options->optionset3->option_meta['url_var'] != '' && isset( $_GET[$product_item->options->optionset3->option_meta['url_var']] ) ) {
									for ( $j=0; $j<count( $product_item->options->optionset3->optionset ); $j++ ) {
										if ( $_GET[$product_item->options->optionset3->option_meta['url_var']] == $product_item->options->optionset3->optionset[$j]->optionitem_name ) {
											$option_id_3 = $product_item->options->optionset3->optionset[$j]->optionitem_id;
										}
									}
								}

								if ( $product->option_id_4 && $product_item->options->optionset4->option_meta['url_var'] != '' && isset( $_GET[$product_item->options->optionset4->option_meta['url_var']] ) ) {
									for ( $j=0; $j<count( $product_item->options->optionset4->optionset ); $j++ ) {
										if ( $_GET[$product_item->options->optionset4->option_meta['url_var']] == $product_item->options->optionset4->optionset[$j]->optionitem_name ) {
											$option_id_4 = $product_item->options->optionset4->optionset[$j]->optionitem_id;
										}
									}
								}

								if ( $product->option_id_5 && $product_item->options->optionset5->option_meta['url_var'] != '' && isset( $_GET[$product_item->options->optionset5->option_meta['url_var']] ) ) {
									for ( $j=0; $j<count( $product_item->options->optionset5->optionset ); $j++ ) {
										if ( $_GET[$product_item->options->optionset5->option_meta['url_var']] == $product_item->options->optionset5->optionset[$j]->optionitem_name ) {
											$option_id_5 = $product_item->options->optionset5->optionset[$j]->optionitem_id;
										}
									}
								}

								$wpdb->query( $wpdb->prepare( "UPDATE ec_tempcart SET optionitem_id_1 = %d, optionitem_id_2 = %d, optionitem_id_3 = %d, optionitem_id_4 = %d, optionitem_id_5 = %d WHERE tempcart_id = %d", $option_id_1, $option_id_2, $option_id_3, $option_id_4, $option_id_5, $tempcart_id ) );
							}
						}
					}
				}

				for ( $i=0; $i<count( $option_vals ); $i++ ) {
					$db->add_option_to_cart( $tempcart_id, $GLOBALS['ec_cart_data']->ec_cart_id, $option_vals[$i] );
				}
			}// If product found

			header( "location: " . $cartpage );
			die();
		} else {
			header( "location: " . $storepage . "?model_number=" . htmlspecialchars( sanitize_text_field( $_GET['ec_add_to_cart'] ), ENT_QUOTES ) );
			die();
		}

	} else if ( isset( $_GET['ec_action'] ) && $_GET['ec_action'] == "addtocart" && isset( $_GET['model_number'] ) ) {
		wpeasycart_session()->handle_session();

		$db = new ec_db();
		$tempcart_id = $db->quick_add_to_cart( sanitize_text_field( $_GET['model_number'] ) );
		if ( $tempcart_id ) {
			global $wpdb;
			$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT ec_tempcart.product_id FROM ec_tempcart WHERE ec_tempcart.tempcart_id = %d", $tempcart_id ) );
			header( "location: " . apply_filters( 'wp_easycart_add_to_cart_return_url_cart', $cartpage, $tempcart_id, $product_id ) );
		} else {
			header( "location: " . $storepage . "?model_number=" . htmlspecialchars( sanitize_text_field( $_GET['model_number'] ), ENT_QUOTES ) );
		}
	}

	/* Load abandoned cart */
	if ( isset( $_GET['ec_load_tempcart'] ) && isset( $_GET['ec_load_email'] ) ) {
		global $wpdb;
		$tempcart_row = $wpdb->get_row( $wpdb->prepare( "SELECT ec_tempcart.session_id FROM ec_tempcart, ec_tempcart_data WHERE ec_tempcart.session_id = %s AND ec_tempcart_data.session_id = ec_tempcart.session_id AND ec_tempcart_data.email = %s", sanitize_text_field( $_GET['ec_load_tempcart'] ), sanitize_email( $_GET['ec_load_email'] ) ) );
		if ( $tempcart_row ) {
			$GLOBALS['ec_cart_id'] = $tempcart_row->session_id;
			setcookie( "ec_cart_id", "", time() - 3600 );
			setcookie( "ec_cart_id", "", time() - 3600, "/" );
			setcookie( 'ec_cart_id', $GLOBALS['ec_cart_id'], time() + ( 3600 * 24 * 1 ), "/" );
			$cart_page_id = get_option('ec_option_cartpage');
			if ( function_exists( 'icl_object_id' ) )
				$cart_page_id = icl_object_id( $cart_page_id, 'page', true, ICL_LANGUAGE_CODE );
			$cart_page = get_permalink( $cart_page_id );
			if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
				$https_class = new WordPressHTTPS();
				$cart_page = $https_class->makeUrlHttps( $cart_page );
			}
			wp_redirect( $cart_page );
		}
	}

	/* Newsletter Form Actions */
	if ( isset( $_POST['ec_newsletter_email'] ) ) {

		if ( isset( $_POST['ec_newsletter_name'] ) )
			$newsletter_name = sanitize_text_field( $_POST['ec_newsletter_name'] );
		else
			$newsletter_name = "";

		if ( filter_var( $_POST['ec_newsletter_email'], FILTER_VALIDATE_EMAIL ) ) {
			$ec_db = new ec_db();
			$ec_db->insert_subscriber( sanitize_email( $_POST['ec_newsletter_email'] ), $newsletter_name, "" );

			// MyMail Hook
			if ( function_exists( 'mailster' ) ) {
				$subscriber_id = mailster('subscribers')->add( array(
					'email' => sanitize_email( $_POST['ec_newsletter_email'] ),
					'name' => $newsletter_name,
					'status' => 1,
				), false );
			}

			do_action( 'wpeasycart_subscriber_added', sanitize_email( $_POST['ec_newsletter_email'] ), $newsletter_name );
		}
		setcookie( 'ec_newsletter_popup', 'hide', time() + ( 10 * 365 * 24 * 60 * 60 ), "/" );
	}

	/* Manual Hide Video */
	if ( ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) && isset( $_GET['ec_admin_action'] ) && $_GET['ec_admin_action'] == "hide-video" ) {
		update_option( 'ec_option_hide_design_help_video', '1' );
	}

	// END STATS AND FORM PROCESSING

} // CLOSE PRE FUNCTION

function ec_custom_headers() {
	if ( isset( $_GET['order_id'] ) && isset( $_GET['orderdetail_id'] ) && isset( $_GET['download_id'] ) && $GLOBALS['ec_cart_data']->cart_data->user_id != "" ) {
		$mysqli = new ec_db();
		$orderdetail_row = $mysqli->get_orderdetail_row( (int) $_GET['order_id'], (int) $_GET['orderdetail_id'], $GLOBALS['ec_cart_data']->cart_data->user_id );
		$ec_orderdetail = new ec_orderdetail( $orderdetail_row, 1 );
	}

	if ( !get_option( 'ec_option_cache_prevent' ) && (
			( 
				isset( $_GET['ec_page'] ) && 
				( 
					$_GET['ec_page'] == "checkout_payment" || $_GET['ec_page'] == "checkout_shipping" || $_GET['ec_page'] == "checkout_info"
				)
			) || (
				get_option( 'ec_option_cartpage' ) == get_the_ID()
			) || (
				get_option( 'ec_option_accountpage' ) == get_the_ID()
			)
		)
	) {
		header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
		header('Pragma: no-cache'); // HTTP 1.0.
		header('Expires: 0'); // Proxies.
	}
}

function wpeasycart_prevent_iframe() {
	global $is_wpec_cart, $is_wpec_account;
	if ( $is_wpec_cart || $is_wpec_account ) {
		header( 'X-Frame-Options: SAMEORIGIN' );
	}
}
add_action( 'wp', 'wpeasycart_prevent_iframe' );

function ec_css_loader_v3() {

	$pageURL = 'http';
	if ( isset( $_SERVER["HTTPS"] ) )
		$pageURL .= "s";

	if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/' . get_option( 'ec_option_base_theme' ) . '/live-editor.css' ) ) {
			wp_register_style( 'wpeasycart_admin_css', plugins_url( 'wp-easycart-data/design/theme/' . get_option( 'ec_option_base_theme' ) . '/live-editor.css', EC_PLUGIN_DATA_DIRECTORY ) );
		} else {
			wp_register_style( 'wpeasycart_admin_css', plugins_url( 'wp-easycart/design/theme/' . get_option( 'ec_option_latest_theme' ) . '/live-editor.css', EC_PLUGIN_DIRECTORY ) );
		}
		wp_enqueue_style( 'wpeasycart_admin_css' );
	}

	if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/' . get_option( 'ec_option_base_theme' ) . '/ec-store.css' ) ) {
		wp_register_style( 'wpeasycart_css', plugins_url( 'wp-easycart-data/design/theme/' . get_option( 'ec_option_base_theme' ) . '/ec-store.css', EC_PLUGIN_DATA_DIRECTORY ), array( 'jquery-ui' ), EC_CURRENT_VERSION );
	} else if( get_option( 'ec_option_enabled_minified_scripts' ) ) {
		wp_register_style( 'wpeasycart_css', plugins_url( 'wp-easycart/design/theme/' . get_option( 'ec_option_latest_theme' ) . '/ec-store.min.css', EC_PLUGIN_DIRECTORY ), array( 'jquery-ui' ), EC_CURRENT_VERSION );
	} else {
		wp_register_style( 'wpeasycart_css', plugins_url( 'wp-easycart/design/theme/' . get_option( 'ec_option_latest_theme' ) . '/ec-store.css', EC_PLUGIN_DIRECTORY ), array( 'jquery-ui' ), EC_CURRENT_VERSION );
	}
	wp_enqueue_style( 'wpeasycart_css' );

	$gfont_string = "://fonts.googleapis.com/css?family=Lato|Monda|Open+Sans|Droid+Serif";
	if ( get_option( 'ec_option_font_main' ) ) {
		$gfont_string .= "|" . str_replace( " ", "+", get_option( 'ec_option_font_main' ) );
	}
	wp_register_style( "wpeasycart_gfont", $pageURL . $gfont_string );
	wp_enqueue_style( 'wpeasycart_gfont' );

	if ( get_option( 'ec_option_use_rtl' ) ) {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/' . get_option( 'ec_option_base_theme' ) . '/rtl_support.css' ) ) {
			wp_register_style( 'wpeasycart_rtl_css', plugins_url( 'wp-easycart-data/design/theme/' . get_option( 'ec_option_base_theme' ) . '/rtl_support.css', EC_PLUGIN_DATA_DIRECTORY ) );
		} else {
			wp_register_style( 'wpeasycart_rtl_css', plugins_url( 'wp-easycart/design/theme/' . get_option( 'ec_option_latest_theme' ) . '/rtl_support.css', EC_PLUGIN_DIRECTORY ) );
		}
		wp_enqueue_style( 'wpeasycart_rtl_css' );
	}

	if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/' . get_option( 'ec_option_base_theme' ) . '/smoothness-jquery-ui.min.css' ) ) {
		wp_register_style( 'jquery-ui', plugins_url( 'wp-easycart-data/design/theme/' . get_option( 'ec_option_base_theme' ) . '/smoothness-jquery-ui.min.css', EC_PLUGIN_DATA_DIRECTORY ) );
	} else {
		wp_register_style( 'jquery-ui', plugins_url( 'wp-easycart/design/theme/' . get_option( 'ec_option_latest_theme' ) . '/smoothness-jquery-ui.min.css', EC_PLUGIN_DIRECTORY ) );
	}

}

function ec_js_loader_v3() {

	if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/' . get_option( 'ec_option_base_theme' ) . '/live-editor.js' ) ) {
			wp_enqueue_script( 'wpeasycart_admin_js', plugins_url( 'wp-easycart-data/design/theme/' . get_option( 'ec_option_base_theme' ) . '/live-editor.js', EC_PLUGIN_DATA_DIRECTORY ), array( 'jquery', 'jquery-ui-core' ), EC_CURRENT_VERSION );
		} else {
			wp_enqueue_script( 'wpeasycart_admin_js', plugins_url( 'wp-easycart/design/theme/' . get_option( 'ec_option_latest_theme' ) . '/live-editor.js', EC_PLUGIN_DIRECTORY ), array( 'jquery', 'jquery-ui-core' ), EC_CURRENT_VERSION );
		}
	}

	if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/' . get_option( 'ec_option_base_theme' ) . '/ec-store.js' ) ) {
		wp_enqueue_script( 'wpeasycart_js', plugins_url( 'wp-easycart-data/design/theme/' . get_option( 'ec_option_base_theme' ) . '/ec-store.js', EC_PLUGIN_DATA_DIRECTORY ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'jquery-ui-datepicker' ), EC_CURRENT_VERSION, false );
	} else if( get_option( 'ec_option_enabled_minified_scripts' ) ) {
		wp_enqueue_script( 'wpeasycart_js', plugins_url( 'wp-easycart/design/theme/' . get_option( 'ec_option_latest_theme' ) . '/ec-store.min.js', EC_PLUGIN_DIRECTORY ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'jquery-ui-datepicker' ), EC_CURRENT_VERSION, false );
	} else {
		wp_enqueue_script( 'wpeasycart_js', plugins_url( 'wp-easycart/design/theme/' . get_option( 'ec_option_latest_theme' ) . '/ec-store.js', EC_PLUGIN_DIRECTORY ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'jquery-ui-datepicker' ), EC_CURRENT_VERSION, false );
	}

	wp_enqueue_script( 'wpeasycart_owl_carousel_js', plugins_url( 'wp-easycart/design/theme/' . get_option( 'ec_option_latest_theme' ) . '/owl.carousel.min.js', EC_PLUGIN_DATA_DIRECTORY ), array( 'jquery' ), EC_CURRENT_VERSION, false );
	wp_register_style( 'wpeasycart_owl_carousel_css', plugins_url( 'wp-easycart/design/theme/' . get_option( 'ec_option_latest_theme' ) . '/owl.carousel.css', EC_PLUGIN_DIRECTORY ) );
	wp_enqueue_style( 'wpeasycart_owl_carousel_css' );

}

function wp_easycart_load_cart_js() {
	if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/' . get_option( 'ec_option_base_theme' ) . '/jquery.payment.min.js' ) ) {
		wp_enqueue_script( 'payment_jquery_js', plugins_url( 'wp-easycart-data/design/theme/' . get_option( 'ec_option_base_theme' ) . '/jquery.payment.min.js', EC_PLUGIN_DATA_DIRECTORY ), array( 'jquery' ), EC_CURRENT_VERSION, false );
	} else {
		wp_enqueue_script( 'payment_jquery_js', plugins_url( 'wp-easycart/design/theme/' . get_option( 'ec_option_latest_theme' ) . '/jquery.payment.min.js', EC_PLUGIN_DIRECTORY ), array( 'jquery' ), EC_CURRENT_VERSION, false );
	}

	if ( get_option( 'ec_option_payment_process_method' ) == "square" ) {
		wp_enqueue_script( 'wpeasycart_square_js', ( ( get_option( 'ec_option_square_is_sandbox' ) ) ? 'https://sandbox.web.squarecdn.com/v1/square.js' : 'https://web.squarecdn.com/v1/square.js' ), array(), EC_CURRENT_VERSION, false );
		add_filter( 'sgo_js_async_exclude', 'wp_easycart_exclude_from_siteground', 10, 1 );
	}

	if ( get_option( 'ec_option_payment_process_method' ) == "stripe" || get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) {
		wp_enqueue_script( 'wpeasycart_stripe_js', 'https://js.stripe.com/v3/', array(), EC_CURRENT_VERSION, false );
		add_filter( 'sgo_js_async_exclude', 'wp_easycart_exclude_from_siteground', 10, 1 );
	}

	if ( get_option( 'ec_option_payment_process_method' ) == "eway" && get_option( 'ec_option_eway_use_rapid_pay' ) ) {
		wp_enqueue_script( 'wpeasycart_eway_js', 'https://secure.ewaypayments.com/scripts/eCrypt.min.js', array(), EC_CURRENT_VERSION, false );
		add_filter( 'sgo_js_async_exclude', 'wp_easycart_exclude_from_siteground', 10, 1 );
	}

	if ( get_option( 'ec_option_payment_third_party' ) == "paypal" && ( get_option( 'ec_option_paypal_enable_credit' ) == '1' || get_option( 'ec_option_paypal_enable_pay_now' ) == '1' ) ) {
		wp_enqueue_script( 'wpeasycart_paypal_js', 'https://www.paypalobjects.com/api/checkout.js', array(), EC_CURRENT_VERSION, false );
		add_filter( 'sgo_js_async_exclude', 'wp_easycart_exclude_from_siteground', 10, 1 );
	}

	if ( get_option( 'ec_option_payment_process_method' ) == "braintree" && isset( $_GET['ec_page'] ) && ( $_GET['ec_page'] == 'checkout_payment' || $_GET['ec_page'] == 'subscription_info' ) ) {
		wp_enqueue_script( 'wpeasycart_braintree_js', 'https://js.braintreegateway.com/web/dropin/1.13.0/js/dropin.min.js', array(), EC_CURRENT_VERSION, false );
	}

	if ( get_option( 'ec_option_enable_recaptcha' ) ) {
		wp_enqueue_script( 'wpeasycart_google_recaptcha_js', 'https://www.google.com/recaptcha/api.js?onload=wpeasycart_recaptcha_onload&render=explicit', array(), EC_CURRENT_VERSION, false );
	}
}

function wp_easycart_load_grecaptcha_js() {
	if ( get_option( 'ec_option_enable_recaptcha' ) ) {
		wp_enqueue_script( 'wpeasycart_google_recaptcha_js', 'https://www.google.com/recaptcha/api.js?onload=wpeasycart_recaptcha_onload&render=explicit', array(), EC_CURRENT_VERSION, false );
	}
}

function wp_easycart_exclude_from_siteground( $list ) {
	$list[] = 'wpeasycart_stripe_js';
	$list[] = 'wpeasycart_square_js';
	$list[] = 'wpeasycart_paypal_js';
	$list[] = 'wpeasycart_eway_js';
	$list[] = 'wpeasycart_amazonpay_js';
	return $list;
}

function ec_load_css() {

	ec_css_loader_v3();

}	

function ec_load_js() {

	ec_js_loader_v3();

	$https_link = "";
	if ( class_exists( "WordPressHTTPS" ) ) {
		$https_class = new WordPressHTTPS();
		$https_link = $https_class->makeUrlHttps( admin_url( 'admin-ajax.php' ) );
	} else {
		$https_link = str_replace( "http://", "https://", admin_url( 'admin-ajax.php' ) );
	}

	if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		$current_language = ICL_LANGUAGE_CODE;
	} else {
		$current_language = wp_easycart_language()->get_language_code();
	}

	$ajax_array = array(
		'ga4_id' => esc_attr( get_option( 'ec_option_google_ga4_property_id' ) ),
		'ga4_conv_id' => esc_attr( get_option( 'ec_option_google_adwords_tag_id' ) ),
		'ajax_url' => ( ( isset( $_SERVER['HTTPS'] ) && 'on' == $_SERVER["HTTPS"] ) ? $https_link : admin_url( 'admin-ajax.php' ) ),
		'current_language' => $current_language
	);
	wp_localize_script( 'wpeasycart_js', 'wpeasycart_ajax_object', $ajax_array );
}

function wpeasycart_seo_tags() {

	global $wp_query;
	global $wpdb;

	/* Check for Post Content Shortcodes */
	$post_obj = $wp_query->get_queried_object();
	if ( $post_obj && isset( $post_obj->post_content ) ) {

		if ( strstr( $post_obj->post_content, "[ec_store" ) && strstr( $post_obj->post_content, "modelnumber" ) ) {
			$matches = array();
			preg_match( '/\[ec_store modelnumber=\"(.*)?\"\]/', $post_obj->post_content, $matches );
			if ( count( $matches ) >= 2 ) {
				$post_meta = get_post_meta( $post_obj->ID );
				if ( !class_exists( 'WPSEO_Options' ) || !$post_meta || !isset( $post_meta['_yoast_wpseo_metadesc'] ) ) {
					$model_number = $matches[1];
					$product_seo = $wpdb->get_row( $wpdb->prepare( "SELECT ec_product.seo_keywords, ec_product.seo_description FROM ec_product WHERE ec_product.model_number = %s", $model_number ) );
					if ( isset( $product_seo->seo_description ) && '' != $product_seo->seo_description ) {
						echo "<meta name=\"description\" content=\"" . esc_js( $product_seo->seo_description ) . "\">\n";
					}
					if ( isset( $product_seo->seo_keywords ) && '' != $product_seo->seo_keywords ) {
						echo "<meta name=\"keywords\" content=\"" . esc_js( $product_seo->seo_keywords ) . "\">\n";
					}
				}
			}
			if ( !class_exists( 'WPSEO_Options' ) ) {
				ec_show_facebook_meta( $model_number );
			}

		} else if ( strstr( $post_obj->post_content, "[ec_store" ) && strstr( $post_obj->post_content, "menuid" ) ) {
			$matches = array();
			preg_match( '/\[ec_store menuid=\"(.*)?\"\]/', $post_obj->post_content, $matches );
			if ( count( $matches ) >= 2 ) {
				$post_meta = get_post_meta( $post_obj->ID );
				if ( !class_exists( 'WPSEO_Options' ) || !$post_meta || !isset( $post_meta['_yoast_wpseo_metadesc'] ) ) {
					$menu_id = $matches[1];
					$menu_seo = $wpdb->get_row( $wpdb->prepare( "SELECT ec_menulevel1.seo_keywords, ec_menulevel1.seo_description FROM ec_menulevel1 WHERE ec_menulevel1.menulevel1_id = %d", $menu_id ) );
					if ( $menu_seo->seo_description != "" )
						echo "<meta name=\"description\" content=\"" . esc_js( $menu_seo->seo_description ) . "\">\n";
					if ( $menu_seo->seo_keywords != "" )
						echo "<meta name=\"keywords\" content=\"" . esc_js( $menu_seo->seo_keywords ) . "\">\n";
				}
			}

		} else if ( strstr( $post_obj->post_content, "[ec_store" ) && strstr( $post_obj->post_content, "submenuid" ) ) {
			$matches = array();
			preg_match( '/\[ec_store submenuid=\"(.*)?\"\]/', $post_obj->post_content, $matches );
			if ( count( $matches ) >= 2 ) {
				$post_meta = get_post_meta( $post_obj->ID );
				if ( !class_exists( 'WPSEO_Options' ) || !$post_meta || !isset( $post_meta['_yoast_wpseo_metadesc'] ) ) {
					$submenu_id = $matches[1];
					$submenu_seo = $wpdb->get_row( $wpdb->prepare( "SELECT ec_menulevel2.seo_keywords, ec_menulevel2.seo_description FROM ec_menulevel2 WHERE ec_menulevel2.menulevel2_id = %d", $submenu_id ) );
					if ( $submenu_seo->seo_description != "" )
						echo "<meta name=\"description\" content=\"" . esc_js( $submenu_seo->seo_description ) . "\">\n";
					if ( $submenu_seo->seo_keywords != "" )
						echo "<meta name=\"keywords\" content=\"" . esc_js( $submenu_seo->seo_keywords ) . "\">\n";
				}
			}

		} else if ( strstr( $post_obj->post_content, "[ec_store" ) && strstr( $post_obj->post_content, "subsubmenuid" ) ) {
			$matches = array();
			preg_match( '/\[ec_store menuid=\"(.*)?\"\]/', $post_obj->post_content, $matches );
			if ( count( $matches ) >= 2 ) {
				$post_meta = get_post_meta( $post_obj->ID );
				if ( !class_exists( 'WPSEO_Options' ) || !$post_meta || !isset( $post_meta['_yoast_wpseo_metadesc'] ) ) {
					$subsubmenu_id = $matches[1];
					$subsubmenu_seo = $wpdb->get_row( $wpdb->prepare( "SELECT ec_menulevel3.seo_keywords, ec_menulevel3.seo_description FROM ec_menulevel3 WHERE ec_menulevel3.menulevel3_id = %d", $subsubmenu_id ) );
					if ( $subsubmenu_seo->seo_description != "" )
						echo "<meta name=\"description\" content=\"" . esc_js( $subsubmenu_seo->seo_description ) . "\">\n";
					if ( $subsubmenu_seo->seo_keywords != "" )
						echo "<meta name=\"keywords\" content=\"" . esc_js( $subsubmenu_seo->seo_keywords ) . "\">\n";
				}
			}
		}

	}

	/* Check for GET VARS */
	if ( isset( $_GET['model_number'] ) ) {
		$matches = array();
		$model_number = sanitize_text_field( $_GET['model_number'] );
		$product_seo = wp_cache_get( 'wpeasycart-product-seo-'.$model_number, 'wpeasycart-product-seo' );
		if ( !$product_seo ) {
			$product_seo = $wpdb->get_row( $wpdb->prepare( "SELECT ec_product.seo_keywords, ec_product.seo_description FROM ec_product WHERE ec_product.model_number = %s", $model_number ) );
			wp_cache_set( 'wpeasycart-product-seo-'.$model_number, $product_seo, 'wpeasycart-product-seo' );
		}
		if ( $product_seo->seo_description != "" )
			echo "<meta name=\"description\" content=\"" . esc_js( $product_seo->seo_description ) . "\">\n";
		if ( $product_seo->seo_keywords != "" )
			echo "<meta name=\"keywords\" content=\"" . esc_js( $product_seo->seo_keywords ) . "\">\n";
		ec_show_facebook_meta( $model_number );

	} else if ( isset( $_GET['menuid'] ) ) {
		$menu_id = (int) $_GET['menuid'];
		$menu_seo = $wpdb->get_row( $wpdb->prepare( "SELECT ec_menulevel1.seo_keywords, ec_menulevel1.seo_description FROM ec_menulevel1 WHERE ec_menulevel1.menulevel1_id = %d", $menu_id ) );
		if ( $menu_seo->seo_description != "" )
			echo "<meta name=\"description\" content=\"" . esc_js( $menu_seo->seo_description ) . "\">\n";
		if ( $menu_seo->seo_keywords != "" )
			echo "<meta name=\"keywords\" content=\"" . esc_js( $menu_seo->seo_keywords ) . "\">\n";

	} else if ( isset( $_GET['submenuid'] ) ) {
		$submenu_id = (int) $_GET['submenuid'];
		$submenu_seo = $wpdb->get_row( $wpdb->prepare( "SELECT ec_menulevel2.seo_keywords, ec_menulevel2.seo_description FROM ec_menulevel2 WHERE ec_menulevel2.menulevel2_id = %d", $submenu_id ) );
		if ( $submenu_seo->seo_description != "" )
			echo "<meta name=\"description\" content=\"" . esc_js( $submenu_seo->seo_description ) . "\">\n";
		if ( $submenu_seo->seo_keywords != "" )
			echo "<meta name=\"keywords\" content=\"" . esc_js( $submenu_seo->seo_keywords ) . "\">\n";

	} else if ( isset( $_GET['subsubmenuid'] ) ) {
		$subsubmenu_id = (int) $_GET['subsubmenuid'];
		$subsubmenu_seo = $wpdb->get_row( $wpdb->prepare( "SELECT ec_menulevel3.seo_keywords, ec_menulevel3.seo_description FROM ec_menulevel3 WHERE ec_menulevel3.menulevel3_id = %d", $subsubmenu_id ) );
		if ( $subsubmenu_seo->seo_description != "" )
			echo "<meta name=\"description\" content=\"" . esc_js( $subsubmenu_seo->seo_description ) . "\">\n";
		if ( $subsubmenu_seo->seo_keywords != "" )
			echo "<meta name=\"keywords\" content=\"" . esc_js( $subsubmenu_seo->seo_keywords ) . "\">\n";

	}

	if ( get_option( 'ec_option_use_affirm' ) && get_option( 'ec_option_affirm_public_key' ) != "" ) {

		if ( get_option( 'ec_option_affirm_sandbox_account' ) ) {
			echo '<script>
			 var _affirm_config = {
				public_api_key: "' . esc_js( get_option( 'ec_option_affirm_public_key' ) ) . '",
				script:     "https://cdn1-sandbox.affirm.com/js/v2/affirm.js"
			 };
			 (function(l,g,m,e,a,f,b) {var d,c=l[m]||{},h=document.createElement(f),n=document.getElementsByTagName(f)[0],k=function(a,b,c) {return function() {a[b]._.push([c,arguments])}};c[e]=k(c,e,"set");d=c[e];c[a]={};c[a]._=[];d._=[];c[a][b]=k(c,a,b);a=0;for (b="set add save post open empty reset on off trigger ready setProduct".split(" ");a<b.length;a++)d[b[a]]=k(c,e,b[a]);a=0;for (b=["get","token","url","items"];a<b.length;a++)d[b[a]]=function() {};h.async=!0;h.src=g[f];n.parentNode.insertBefore(h,n);delete g[f];d(g);l[m]=c})(window,_affirm_config,"affirm","checkout","ui","script","ready");
			</script>';
		} else {
			echo '<script>
			 var _affirm_config = {
				public_api_key: "' . esc_js( get_option( 'ec_option_affirm_public_key' ) ) . '",
				script:     "https://cdn1.affirm.com/js/v2/affirm.js"
			 };
			 (function(l,g,m,e,a,f,b) {var d,c=l[m]||{},h=document.createElement(f),n=document.getElementsByTagName(f)[0],k=function(a,b,c) {return function() {a[b]._.push([c,arguments])}};c[e]=k(c,e,"set");d=c[e];c[a]={};c[a]._=[];d._=[];c[a][b]=k(c,a,b);a=0;for (b="set add save post open empty reset on off trigger ready setProduct".split(" ");a<b.length;a++)d[b[a]]=k(c,e,b[a]);a=0;for (b=["get","token","url","items"];a<b.length;a++)d[b[a]]=function() {};h.async=!0;h.src=g[f];n.parentNode.insertBefore(h,n);delete g[f];d(g);l[m]=c})(window,_affirm_config,"affirm","checkout","ui","script","ready");
			</script>';
		}
	}

}

function ec_show_facebook_meta( $model_number ) {

	global $wpdb;
	$ec_db = new ec_db();
	$product = wp_cache_get( 'wpeasycart-product-only-'.$model_number, 'wpeasycart-product-list' );
	if ( !$product ) {
		$product = $ec_db->get_product_list( $wpdb->prepare( " WHERE product.model_number = %s AND product.activate_in_store = 1", $model_number ), "", "", "", "wpeasycart-product-only-".$model_number );	
		wp_cache_set( "wpeasycart-product-only-".$model_number, $product, 'wpeasycart-product-list' );
	}
	if ( count( $product ) > 0 )
		$product = $product[0];
	$product_id = $product['product_id'];
	$prod_title = $product['title'];
	$prod_model_number = $product['model_number'];
	$prod_description = $product['seo_description'];
	if ( $prod_description == "" ) {
		$prod_description = htmlspecialchars( strip_tags( $product['short_description'] ), ENT_QUOTES );
	}
	if ( $prod_description == "" ) {
		$prod_description = htmlspecialchars( strip_tags( $product['description'] ), ENT_QUOTES );
	}
	$prod_use_optionitem_images = $product['use_optionitem_images'];
	$prod_image = $product['image1'];
	$prod_image2 = $product['image2'];
	$prod_image3 = $product['image3'];
	$prod_image4 = $product['image4'];
	$prod_image5 = $product['image5'];
	$product_images = ( isset( $product['product_images'] ) && '' != $product['product_images'] ) ? explode( ',', $product['product_images'] ) : array();

	if ( $prod_use_optionitem_images ) {
		$optimgs = $wpdb->get_results( $wpdb->prepare( "SELECT optionitemimage.optionitemimage_id, optionitemimage.optionitem_id, optionitemimage.product_id, optionitemimage.image1, optionitemimage.image2, optionitemimage.image3, optionitemimage.image4, optionitemimage.image5, optionitemimage.product_images, optionitem.optionitem_order FROM ec_optionitemimage as optionitemimage, ec_optionitem as optionitem WHERE optionitemimage.product_id = %d AND optionitem.optionitem_id = optionitemimage.optionitem_id GROUP BY optionitemimage.optionitemimage_id ORDER BY optionitemimage.product_id, optionitem.optionitem_order", $product_id ) );
		if ( count( $optimgs ) > 0 ) {
			$prod_image = $optimgs[0]->image1;
			$prod_image2 = $optimgs[0]->image2;
			$prod_image3 = $optimgs[0]->image3;
			$prod_image4 = $optimgs[0]->image4;
			$prod_image5 = $optimgs[0]->image5;
			$product_images = ( isset( $optimgs[0]->product_images ) && '' != $optimgs[0]->product_images ) ? explode( ',', $optimgs[0]->product_images ) : array();
		}
	}

	if ( count( $product_images ) > 0 ) {
		if( 'video:' == substr( $product_images[0], 0, 6 ) ) {
			$video_str = substr( $product_images[0], 6, strlen( $product_images[0] ) - 6 );
			$video_arr = explode( ':::', $video_str );
			if ( count( $video_arr ) >= 2 ) {
				$prod_image = $video_arr[1];
			}
		} else if( 'youtube:' == substr( $product_images[0], 0, 8 ) ) {
			$youtube_video_str = substr( $product_images[0], 8, strlen( $product_images[0] ) - 8 );
			$youtube_video_arr = explode( ':::', $youtube_video_str );
			if ( count( $youtube_video_arr ) >= 2 ) {
				$prod_image = $youtube_video_arr[1];
			}
		} else if( 'vimeo:' == substr( $product_images[0], 0, 6 ) ) {
			$vimeo_video_str = substr( $product_images[0], 6, strlen( $product_images[0] ) - 6 );
			$vimeo_video_arr = explode( ':::', $vimeo_video_str );
			if ( count( $vimeo_video_arr ) >= 2 ) {
				$prod_image = $vimeo_video_arr[1];
			}
		} else {
			if ( 'image1' == $product_images[0] ) {
				// Already correct
			} else if( 'image2' == $product_images[0] ) {
				$prod_image = $prod_image2;
			} else if( 'image3' == $product_images[0] ) {
				$prod_image = $prod_image3;
			} else if( 'image4' == $product_images[0] ) {
				$prod_image = $prod_image4;
			} else if( 'image5' == $product_images[0] ) {
				$prod_image = $prod_image5;
			} else if( 'image:' == substr( $product_images[0], 0, 6 ) ) {
				$prod_image = apply_filters('wp_easycart_product_details_image_url_type', substr( $product_images[0], 6, strlen( $product_images[0] ) - 6 ) );
			} else {
				$product_image_media = wp_get_attachment_image_src( $product_images[0], apply_filters( 'wp_easycart_product_details_full_size', 'large' ) );
				if( $product_image_media && isset( $product_image_media[0] ) ) {
					$prod_image = $product_image_media[0];
				}
			}
		}
	}

	remove_action('wp_head', 'rel_canonical');

	//this method places to early, before html tags open
	echo "\n";
	echo "<meta property=\"og:title\" content=\"" . esc_js( $prod_title ) . "\" />\n"; 
	echo "<meta property=\"og:type\" content=\"product\" />\n";
	echo "<meta property=\"og:description\" content=\"" . esc_js( ec_short_string( $prod_description, 300 ) ) . "\" />\n";
	if ( substr( $prod_image, 0, 7 ) == 'http://' || substr( $prod_image, 0, 8 ) == 'https://' ) {
		echo "<meta property=\"og:image\" content=\"" . esc_js( $prod_image ) . "\" />\n"; 
		if ( file_exists( $prod_image ) && list( $width, $height ) = @getimagesize( $prod_image ) ) {
			echo "<meta property=\"og:image:width\" content=\"" . esc_js( $width ) . "\" />\n";
			echo "<meta property=\"og:image:height\" content=\"" . esc_js( $height ) . "\" />\n";
		}

	} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $prod_image ) ) {
		echo "<meta property=\"og:image\" content=\"" . esc_js( plugin_dir_url( "wp-easycart-data/products/pics1/" . $prod_image, EC_PLUGIN_DATA_DIRECTORY ) ) . "\" />\n"; 
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $prod_image ) && ! is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $prod_image ) && list( $width, $height ) = @getimagesize( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $prod_image ) ) {
			echo "<meta property=\"og:image:width\" content=\"" . esc_js( $width ) . "\" />\n";
			echo "<meta property=\"og:image:height\" content=\"" . esc_js( $height ) . "\" />\n";
		}
	}
	echo "<meta property=\"og:url\" content=\"" . esc_js( ec_curPageURL() ) . "\" /> \n";

}

function ec_theme_head_data() {
	$GLOBALS['ec_page_options'] = new ec_page_options();

	if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/head_content.php" ) ) {
		include( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/head_content.php" );

	} else if ( file_exists( EC_PLUGIN_DIRECTORY . '/design/theme/' . get_option( 'ec_option_latest_theme' ) . '/head_content.php' ) ) {
		include( EC_PLUGIN_DIRECTORY . '/design/theme/' . get_option( 'ec_option_latest_theme' ) . '/head_content.php' );

	}
}

function ec_curPageURL() {
	$pageURL = 'http';
	if ( isset( $_SERVER["HTTPS"] ) )
		$pageURL .= "s";

	$pageURL .= "://";
	if ( (int) $_SERVER["SERVER_PORT"] != 80 )
		$pageURL .= sanitize_text_field( $_SERVER["SERVER_NAME"] ) . ":" . (int) $_SERVER["SERVER_PORT"] . htmlspecialchars( sanitize_text_field( $_SERVER["REQUEST_URI"] ), ENT_QUOTES );
	else
		$pageURL .= sanitize_text_field( $_SERVER["SERVER_NAME"] ) . htmlspecialchars ( sanitize_text_field( $_SERVER["REQUEST_URI"] ), ENT_QUOTES );

	return $pageURL;
}

function ec_short_string($text, $length) {
	$text = strip_tags( $text );
	if ( strlen( $text ) > $length )
		$text = substr($text, 0, strpos($text, ' ', $length));

	return $text;
}

//[ec_store]
function load_ec_store( $atts ) {

	if ( ! defined( 'DONOTCACHEPAGE' ) ) {
		define( "DONOTCACHEPAGE", true );
	}

	if ( ! defined( 'DONOTCDN' ) ) {
		define('DONOTCDN', true);
	}

	$args = shortcode_atts( array(
		'menuid' => 'NOMENU',
		'submenuid' => 'NOSUBMENU',
		'subsubmenuid' => 'NOSUBSUBMENU',
		'manufacturerid' => 'NOMANUFACTURER',
		'groupid' => 'NOGROUP',
		'modelnumber' => 'NOMODELNUMBER',
		'language' => 'NONE',
		'background_add' => false,
		'columns' => false,
		'cols_desktop' => false,
		'cols_tablet' => false,
		'cols_mobile' => false,
		'cols_mobile_small' => 1,
		'spacing' => 20,
		'productid' => false,
		'category' => false,
		'manufacturer' => false,
		'elementor' => false,
		'show_breadcrumbs' => get_option( 'ec_option_show_breadcrumbs' ),
		'show_image_hover' => get_option( 'ec_option_show_magnification' ),
		'show_lightbox' => get_option( 'ec_option_show_large_popup' ),
		'show_thumbnails' => true,
		'show_title' => true,
		'title_font' => null,
		'title_color' => null,
		'title_divider_color' => null,
		'price_font' => null,
		'price_color' => null,
		'list_price_font' => null,
		'list_price_color' => null,
		'add_to_cart_color' => null,
		'show_customer_reviews' => null,
		'show_price' => true,
		'show_short_description' => null,
		'show_model_number' => get_option( 'ec_option_show_model_number' ),
		'show_categories' => get_option( 'ec_option_show_categories' ),
		'show_manufacturer' => get_option( 'ec_option_show_manufacturer' ),
		'show_stock' => get_option( 'ec_option_show_stock_quantity' ),
		'show_social' => true,
		'show_description' => null,
		'show_specifications' => null,
		'show_related_products' => null,
		'background_add' => null,
		'details_sizing' => (int) get_option( 'ec_option_product_details_sizing' ),
		'paging' => get_option( 'ec_option_enable_product_paging' ),
		'per_page' => get_option( 'ec_option_enable_product_paging_per_page' ),
		'sorting' => get_option( 'ec_option_show_sort_box' ),
		'sorting_default' => get_option( 'ec_option_default_store_filter' ),
		'status' => 'featured',
		'product_style' => get_option( 'ec_option_default_product_type' ),
		'product_align' => get_option( 'ec_option_default_product_align' ),
		'product_visible_options' => get_option( 'ec_option_default_product_visible_options' ),
		'product_rounded_corners' => get_option( 'ec_option_default_product_rounded_corners' ),
		'product_rounded_corners_tl' => get_option( 'ec_option_default_product_rounded_corners_tl' ),
		'product_rounded_corners_tr' => get_option( 'ec_option_default_product_rounded_corners_tr' ),
		'product_rounded_corners_bl' => get_option( 'ec_option_default_product_rounded_corners_bl' ),
		'product_rounded_corners_br' => get_option( 'ec_option_default_product_rounded_corners_br' ),
		'product_border' => get_option( 'ec_option_default_product_border' ),
		'sidebar' => get_option( 'ec_option_show_store_sidebar' ),
		'sidebar_position' => get_option( 'ec_option_store_sidebar_position' ),
		'sidebar_filter_clear' => get_option( 'ec_option_store_sidebar_filter_clear' ),
		'sidebar_include_search' => get_option( 'ec_option_store_sidebar_include_search' ),
		'sidebar_include_categories' => get_option( 'ec_option_store_sidebar_include_categories' ),
		'sidebar_include_categories_first' => get_option( 'ec_option_sidebar_include_categories_first' ),
		'sidebar_categories' => get_option( 'ec_option_store_sidebar_categories' ),
		'sidebar_include_category_filters' => get_option( 'ec_option_sidebar_include_category_filters' ),
		'sidebar_category_filter_id' => get_option( 'ec_option_sidebar_category_filter_id' ),
		'sidebar_category_filter_method' => get_option( 'ec_option_sidebar_category_filter_method' ),
		'sidebar_category_filter_open' => get_option( 'ec_option_sidebar_category_filter_open' ),
		'sidebar_include_option_filters' => get_option( 'ec_option_sidebar_include_option_filters' ),
		'sidebar_option_filters' => get_option( 'ec_option_store_sidebar_option_filters' ),
	), $atts );

	if ( $args['language'] != 'NONE' ) {
		wp_easycart_language()->update_selected_language( $args['language'] );
		$GLOBALS['ec_cart_data']->cart_data->translate_to = $args['language'];
		$GLOBALS['ec_cart_data']->save_session_to_db( );
	}

	$GLOBALS['ec_store_shortcode_options'] = array( $args['menuid'], $args['submenuid'], $args['subsubmenuid'], $args['manufacturerid'], $args['groupid'], $args['modelnumber'], $args );

	ob_start();
	$store_page = new ec_storepage( $args['menuid'], $args['submenuid'], $args['subsubmenuid'], $args['manufacturerid'], $args['groupid'], $args['modelnumber'], $args );
	$store_page->display_store_page();
	return ob_get_clean();

}

//[ec_cart]
function load_ec_cart( $atts ) {

	if ( !get_option( 'ec_option_cache_prevent' ) ) {
		if ( !defined( 'DONOTCACHEPAGE' ) )
			define( "DONOTCACHEPAGE", true );

		if ( !defined( 'DONOTCDN' ) )
			define('DONOTCDN', true);
	}

	extract( shortcode_atts( array(
		'language' => 'NONE'
	), $atts ) );

	if ( $language != 'NONE' ) {
		wp_easycart_language()->update_selected_language( $language );
		$GLOBALS['ec_cart_data']->cart_data->translate_to = $language;
		$GLOBALS['ec_cart_data']->save_session_to_db();
	}

	ob_start();
	if ( get_option( 'ec_option_cache_prevent' ) ) {
		wp_easycart_dynamic_cart_display( $language );
	} else {
	  $cart_page = new ec_cartpage();
	  $cart_page->display_cart_page();
	}
	return ob_get_clean();
}

function wp_easycart_dynamic_cart_display( $language = 'NONE' ) {
	$ec_db = new ec_db();
	if ( get_option( 'ec_option_onepage_checkout' ) && get_option( 'ec_option_onepage_checkout_cart_first' ) ) {
		$cart_page = 1;
	} else if ( get_option( 'ec_option_onepage_checkout' ) ) {
		$cart_page = 2;
	} else {
		$cart_page = 1;
	}
	if ( isset( $_GET['ec_page'] ) ) {
		if ( $_GET['ec_page'] == 'checkout_success' ) {
			$cart_page = 6;
		} else if ( $_GET['ec_page'] == 'checkout_info' ) {
			$cart_page = 2;
		} else if ( $_GET['ec_page'] == 'checkout_shipping' ) {
			$cart_page = 3;
		} else if ( $_GET['ec_page'] == 'checkout_payment' ) {
			$cart_page = 4;
			if ( isset( $_GET['ideal'] ) && $_GET['ideal'] == 'returning' && isset( $_GET['client_secret'] ) && isset( $_GET['source'] ) ) {
				$source = htmlspecialchars( sanitize_text_field( $_GET['source'] ), ENT_QUOTES );
				$client_secret = htmlspecialchars( sanitize_text_field( $_GET['client_secret'] ), ENT_QUOTES );
				$cart_page .= '-ideal' . '-' . $source . '-' . $client_secret;
			}
		} else if ( $_GET['ec_page'] == 'subscription_info' ) {
			$cart_page = 1;
			global $wpdb;
			$model_number = preg_replace( "/[^A-Za-z0-9\-\_]/", '', sanitize_text_field( $_GET['subscription'] ) );
			$products = $ec_db->get_product_list( $wpdb->prepare( " WHERE product.model_number = %s", $model_number ), "", "", "" );
			if ( count( $products ) > 0 ) {
				$cart_page = 5;
				$product_id = $products[0]['product_id'];
			}
		}
	} else if ( get_option( 'ec_option_onepage_checkout' ) && isset( $_GET['eccheckout'] ) ) {
		if ( $_GET['eccheckout'] == 'success' ) {
			$cart_page = 6;
		} else if ( $_GET['eccheckout'] == 'cart' ) {
			$cart_page = 1;
		} else if ( $_GET['eccheckout'] == 'information' ) {
			$cart_page = 2;
		} else if ( $_GET['eccheckout'] == 'shipping' ) {
			$cart_page = 3;
		} else if ( $_GET['eccheckout'] == 'payment' ) {
			$cart_page = 4;
			if ( isset( $_GET['ideal'] ) && $_GET['ideal'] == 'returning' && isset( $_GET['client_secret'] ) && isset( $_GET['source'] ) ) {
				$source = htmlspecialchars( sanitize_text_field( $_GET['source'] ), ENT_QUOTES );
				$client_secret = htmlspecialchars( sanitize_text_field( $_GET['client_secret'] ), ENT_QUOTES );
				$cart_page .= '-ideal' . '-' . $source . '-' . $client_secret;
			}
		}
	}
	$error_codes = apply_filters( 'wpeasycart_valid_cart_errors', array( "email_exists", "login_failed", "3dsecure_failed", "manualbill_failed", "thirdparty_failed", "payment_failed", "card_error", "already_subscribed", "not_activated", "subscription_not_found", "user_insert_error", "subscription_added_failed", "subscription_failed", "invalid_address", "session_expired", "invalid_vat_number", "stock_invalid", "ideal-pending", "shipping_method", "invalid_cart_shipping" ) );
	echo '<div id="wpeasycart_cart_holder" style="position:relative; width:100%; min-height:350px;"><style>
	@keyframes rotation{
		0% { transform:rotate(0deg); }
		100%{ transform:rotate(359deg); }
	}
	</style>
	<div style=\'font-family: "HelveticaNeue", "HelveticaNeue-Light", "Helvetica Neue Light", helvetica, arial, sans-serif; font-size: 14px; text-align: center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; width: 350px; top: 50%; left: 50%; position: absolute; margin-left: -165px; margin-top: -80px; cursor: pointer; text-align: center;\'>
		<div>
			<div style="height: 30px; width: 30px; display: inline-block; box-sizing: content-box; opacity: 1; filter: alpha(opacity=100); -webkit-animation: rotation .7s infinite linear; -moz-animation: rotation .7s infinite linear; -o-animation: rotation .7s infinite linear; animation: rotation .7s infinite linear; border-left: 8px solid rgba(0, 0, 0, .2); border-right: 8px solid rgba(0, 0, 0, .2); border-bottom: 8px solid rgba(0, 0, 0, .2); border-top: 8px solid #fff; border-radius: 100%;"></div>
		</div>
	</div></div><script type="text/javascript">jQuery( document ).ready( function() { wpeasycart_load_cart( \'' . esc_js( $cart_page ) . ( ( isset( $_GET['order_id'] ) ) ? '-' . (int) $_GET['order_id'] : '' ) . ( ( isset( $_GET['PID'] ) && sanitize_text_field( $_GET['PID'] ) != '' ) ? '-paypal-' . esc_js( preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_GET['PID'] ) ) ) . '-' . esc_js( preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_GET['PYID'] ) ) ) : '' ) . ( ( isset( $_GET['OID'] ) && sanitize_text_field( $_GET['OID'] ) != '' ) ? '-paypal-' . esc_js( preg_replace( "/[^A-Z0-9]/", '', sanitize_text_field( $_GET['OID'] ) ) ) . '-' . esc_js( preg_replace( "/[^A-Z0-9]/", '', sanitize_text_field( $_GET['PYID'] ) ) ) : '' ) . ( ( $cart_page == 5 ) ? '-sub-' . esc_attr( $product_id ) : '' ) . '\', \'' . ( ( isset( $_GET['ec_cart_success'] ) && sanitize_text_field( $_GET['ec_cart_success'] ) == 'account_created' ) ? 'account_created' : '' ) . '\', \'' . ( ( isset( $_GET['ec_cart_error'] ) && in_array( $_GET['ec_cart_error'], $error_codes ) ) ? esc_js( sanitize_text_field( $_GET['ec_cart_error'] ) ) : '' ) . '\', \'' . esc_attr( sanitize_text_field( $language ) ). '\', \'' . esc_attr( wp_create_nonce( 'wp-easycart-get-dynamic-cart-page' ) ) .'\' ); } );</script>';
}

//[ec_account]
function load_ec_account( $atts ) {

	if ( !get_option( 'ec_option_cache_prevent' ) ) {
		if ( !defined( 'DONOTCACHEPAGE' ) )
			define( "DONOTCACHEPAGE", true );

		if ( !defined( 'DONOTCDN' ) )
			define('DONOTCDN', true);
	}

	extract( shortcode_atts( array(
		'language' => 'NONE',
		'redirect' => false
	), $atts ) );

	if ( $language != 'NONE' ) {
		wp_easycart_language()->update_selected_language( $language );
		$GLOBALS['ec_cart_data']->cart_data->translate_to = $language;
		$GLOBALS['ec_cart_data']->save_session_to_db( );
	}

	ob_start();
	if ( isset( $_POST['ec_form_action'] ) ) {
		$account_page = new ec_accountpage( $redirect );
		$account_page->process_form_action( sanitize_key( $_POST['ec_form_action'] ) );	

	} else if ( get_option( 'ec_option_cache_prevent' ) ) {
		wp_easycart_dynamic_account_display( $language );

	} else {
		$account_page = new ec_accountpage( $redirect );
		$account_page->display_account_page();
	}
	return ob_get_clean();
}

function wp_easycart_dynamic_account_display( $language = 'NONE' ) {
	$account_page = '';
	$pages = array( 'forgot_password', 'register', 'billing_information', 'shipping_information', 'personal_information', 'password', 'orders', 'order_details', 'subscription', 'subscriptions', 'subscription_details' );
	if ( isset( $_GET['ec_page'] ) && in_array( $_GET['ec_page'], $pages ) ) {
		$account_page = sanitize_key( $_GET['ec_page'] );
	}
	echo '<div id="wpeasycart_account_holder" style="position:relative; width:100%; min-height:350px;"><style>
	@keyframes rotation{
		0% { transform:rotate(0deg); }
		100%{ transform:rotate(359deg); }
	}
	</style>
	<div style=\'font-family: "HelveticaNeue", "HelveticaNeue-Light", "Helvetica Neue Light", helvetica, arial, sans-serif; font-size: 14px; text-align: center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; width: 350px; top: 50%; left: 50%; position: absolute; margin-left: -165px; margin-top: -80px; cursor: pointer; text-align: center;\'>
		<div>
			<div style="height: 30px; width: 30px; display: inline-block; box-sizing: content-box; opacity: 1; filter: alpha(opacity=100); -webkit-animation: rotation .7s infinite linear; -moz-animation: rotation .7s infinite linear; -o-animation: rotation .7s infinite linear; animation: rotation .7s infinite linear; border-left: 8px solid rgba(0, 0, 0, .2); border-right: 8px solid rgba(0, 0, 0, .2); border-bottom: 8px solid rgba(0, 0, 0, .2); border-top: 8px solid #fff; border-radius: 100%;"></div>
		</div>
	</div></div><script type="text/javascript">jQuery( document ).ready( function() { ';
	$valid_success_codes = array( 'login_success', 'validation_required', 'reset_email_sent', 'personal_information_updated', 'billing_information_updated', 'billing_information_updated', 'shipping_information_updated', 'shipping_information_updated', 'subscription_updated', 'subscription_updated', 'subscription_canceled', 'cart_account_created', 'activation_success' );
	$valid_error_codes = array( 'register_email_error', 'not_activated', 'login_failed', 'register_email_error', 'register_invalid', 'no_reset_email_found', 'personal_information_update_error', 'password_no_match', 'password_wrong_current', 'billing_information_error', 'shipping_information_error', 'subscription_update_failed', 'subscription_cancel_failed' );
	$success_code = ( isset( $_GET['account_success'] ) && in_array( $_GET['account_success'], $valid_success_codes ) ) ? sanitize_text_field( $_GET['account_success'] ) : '';
	$error_code = ( isset( $_GET['account_error'] ) && in_array( $_GET['account_error'], $valid_error_codes ) ) ? sanitize_text_field( $_GET['account_error'] ) : '';
	if ( $account_page == 'order_details' && isset( $_GET['order_id'] ) && isset( $_GET['ec_guest_key'] ) ) {
		echo 'wpeasycart_load_account( \'' . esc_js( $account_page ) . '-' . (int) $_GET['order_id'] . '-' . esc_js( substr( preg_replace( '/[^A-Z]/', '', sanitize_text_field( $_GET['ec_guest_key'] ) ), 0, 30 ) ) . '\', ' . esc_js( get_queried_object_id() ) . ', \'' . esc_js( $success_code ) . '\', \'' . esc_js( $error_code ) . '\', \'' . esc_attr( sanitize_text_field( $language ) ). '\', \'' . esc_attr( wp_create_nonce( 'wp-easycart-get-dynamic-account-page' ) ) .'\' ); } );';

	} else if ( $account_page == 'order_details' && isset( $_GET['order_id'] ) ) {
		echo 'wpeasycart_load_account( \'' . esc_js( $account_page ) . '-' . (int) $_GET['order_id']. '\', ' . esc_js( get_queried_object_id() ) . ', \'' . esc_js( $success_code ) . '\', \'' . esc_js( $error_code ) . '\', \'' . esc_attr( sanitize_text_field( $language ) ). '\', \'' . esc_attr( wp_create_nonce( 'wp-easycart-get-dynamic-account-page' ) ) .'\' ); } );';

	} else if ( $account_page == 'subscription_details' && isset( $_GET['subscription_id'] ) ) {
		echo 'wpeasycart_load_account( \'' . esc_js( $account_page ) . '-' . (int) $_GET['subscription_id'] . '\', ' . esc_js( get_queried_object_id() ) . ', \'' . esc_js( $success_code ) . '\', \'' . esc_js( $error_code ) . '\', \'' . esc_attr( sanitize_text_field( $language ) ). '\', \'' . esc_attr( wp_create_nonce( 'wp-easycart-get-dynamic-account-page' ) ) .'\' ); } );';

	} else {
		echo 'wpeasycart_load_account( \'' . esc_js( $account_page ) . '\', ' . esc_js( get_queried_object_id() ) . ', \'' . esc_js( $success_code ) . '\', \'' . esc_js( $error_code ) . '\', \'' . esc_attr( sanitize_text_field( $language ) ). '\', \'' . esc_attr( wp_create_nonce( 'wp-easycart-get-dynamic-account-page' ) ) .'\' ); } );';

	}
	echo '</script>';
}

//[ec_product]
function load_ec_product( $atts ) {
	extract( shortcode_atts( array(
		'is_elementor' => false,
		'model_number' => 'NOPRODUCT',
		'productid' => 'NOPRODUCTID',
		'category' => '',
		'manufacturer' => '',
		'orderby' => '',
		'order' => 'ASC',
		'status' => '',
		'columns' => false,
		'cols_desktop' => false,
		'cols_tablet' => false,
		'cols_mobile' => false,
		'cols_mobile_small' => 1,
		'margin' => '45px',
		'width' => '175px',
		'minheight' => '375px',
		'imagew' => '140px',
		'imageh' => '140px',
		'style' => '1',
		'layout_mode' => 'grid',
		'product_border' => true,
		'per_page' => false,
		'product_slider_nav_pos' => '',
		'product_slider_nav_type' => 'owl-simple',
		'slider_nav' => 0,
		'slider_nav_show' => 0,
		'slider_nav_tablet' => 0,
		'slider_nav_mobile' => 0,
		'slider_dot' => 0,
		'slider_dot_tablet' => 0,
		'slider_dot_mobile' => 0,
		'slider_loop' => 0,
		'slider_auto_play' => 0,
		'slider_auto_play_time' => 10000,
		'slider_center' => 0,
		'spacing' => 20,
		'product_style' => 'default',
		'product_align' => 'default',
		'product_visible_options' => 'title,category,price,rating,cart,quickview,desc',
		'product_rounded_corners' => false,
		'product_rounded_corners_tl' => 10,
		'product_rounded_corners_tr' => 10,
		'product_rounded_corners_bl' => 10,
		'product_rounded_corners_br' => 10
	), $atts ) );
	if( !$style ) {
		$style = '1';
	}
	if( $is_elementor && !$columns ) {
		$columns = 4;
	} else if( !$columns ) {
		if ( get_option( 'ec_option_default_desktop_columns' ) ) {
			$columns = get_option( 'ec_option_default_desktop_columns' );
		} else {
			$columns = 1;
		}
	}
	if( $is_elementor && !$cols_desktop ) {
		$cols_desktop = 4;
	} else if( !$cols_desktop ) {
		if ( get_option( 'ec_option_default_laptop_columns' ) ) {
			$cols_desktop = get_option( 'ec_option_default_laptop_columns' );
		} else {
			$cols_desktop = 1;
		}
	}
	if( $is_elementor && !$cols_tablet ) {
		$cols_tablet = 3;
	} else if( !$cols_tablet ) {
		if ( get_option( 'ec_option_default_tablet_columns' ) ) {
			$cols_tablet = get_option( 'ec_option_default_tablet_columns' );
		} else {
			$cols_tablet = 1;
		}
	}
	if( $is_elementor && !$cols_mobile ) {
		$cols_mobile = 2;
	} else if( !$cols_mobile ) {
		$cols_mobile = 1;
		if ( get_option( 'ec_option_default_smartphone_columns' ) ) {
			$cols_mobile = get_option( 'ec_option_default_smartphone_columns' );
		} else {
			$cols_mobile = 1;
		}
	}
	$simp_product_id = $model_number;
	ob_start();
	global $wpdb;
	$mysqli = new ec_db();
	if ( $model_number != "NOPRODUCT" ) {
		$products = $mysqli->get_product_list( " WHERE product.activate_in_store = 1 AND product.model_number = '" . $model_number . "'", "", "", "" );
	} else {
		$product_where = " WHERE product.activate_in_store = 1";
		$product_order_default = ' ORDER BY ';
		if ( $status == 'featured' ) {
			$product_where .= ' AND product.show_on_startup = 1';
		} else if ( $status == 'on_sale' ) {
			$product_where .= ' AND product.list_price > product.price';
		} else if ( $status == 'in_stock' ) {
			$product_where .= ' AND ( product.stock_quantity > 0 OR ( product.show_stock_quantity = 0 AND product.use_optionitem_quantity_tracking = 0 ) OR product.allow_backorders = 1 )';
		}
		if ( ( $productid != '' && $productid != 'NOPRODUCTID' ) || $category != '' || $manufacturer != '' ) {
			$product_where .= ' AND (';
		}
		$ids = 0;
		if ( ( $productid != '' && $productid != 'NOPRODUCTID' ) || $category != '' ) {
			$product_ids = array();
			$cat_prod_ids = array();

			if ( $productid != '' && $productid != 'NOPRODUCTID' ) {
				$product_ids = explode( ',', $productid );
			}

			if ( $category != '' ) {
				$category_ids = explode( ',', $category );
				$cat_id_string = '';
				foreach ( $category_ids as $category_id ) {
					if ( $cat_id_string != '' ) {
						$cat_id_string .= ',';
					}
					$cat_id_string .= (int) $category_id;
				}
				$cat_products = $wpdb->get_results( "SELECT DISTINCT product_id FROM ec_categoryitem WHERE category_id IN(" . $cat_id_string . ")" );
				foreach ( $cat_products as $cat_product ) {
					if ( !in_array( $cat_product->product_id, $product_ids ) ) {
						$product_ids[] = $cat_product->product_id;
					}
				}
			}

			if ( count( $product_ids ) > 0 ) {
				foreach ( $product_ids as $product_id ) {
					if ( $ids > 0 ) {
						$product_where .= " OR ";
						$product_order_default .= ", ";
					}
					$product_where .= "product.product_id = " . $product_id;
					$product_order_default .= "product.product_id = $product_id DESC";
					$ids++;
				}

			}
		} else {
			$product_order_default = ' ORDER BY product.product_id DESC';
		}

		if ( $manufacturer != '' ) {
			$manufacturer_ids = explode( ',', $manufacturer );
			foreach ( $manufacturer_ids as $manufacturer_id ) {
				if ( $ids > 0 ) {
					$product_where .= " OR ";
				}
				$product_where .= "product.manufacturer_id = " . $manufacturer_id;
				$ids++;
			}
		}

		if ( ( $productid != '' && $productid != 'NOPRODUCTID' ) || $category != '' || $manufacturer != '' ) {
			$product_where .= ')';
		}

		$orderdir = ( $order == 'DESC' ) ? 'DESC' : 'ASC';
		if ( $orderby == 'title' ) {
			$product_order = " ORDER BY product.title " . $orderdir;
		} else if ( $orderby == 'price' ) {
			$product_order = " ORDER BY product.price " . $orderdir;
		} else if ( $orderby == 'product_id' ) {
			$product_order = " ORDER BY product.product_id " . $orderdir;
		} else if ( $orderby == 'added_to_db_date' ) {
			$product_order = " ORDER BY product.added_to_db_date " . $orderdir;
		} else if ( $orderby == 'rand' ) {
			$product_order = " ORDER BY RAND()";
		} else if ( $orderby == 'views' ) {
			$product_order = " ORDER BY product.views " . $orderdir;
		} else if ( $orderby == 'rating' ) {
			$product_order = " ORDER BY review_average " . $orderdir;
		} else {
			$product_order = $product_order_default;
		}

		$limit_query = "";
		if ( $per_page ) {
			$limit_query = " LIMIT " . ( (int) $per_page );
		}

		$products = $mysqli->get_product_list( $product_where, $product_order, $limit_query, "" );
	}
	if ( count( $products ) > 0 ) {
		
		if( ! $is_elementor && 1 == count( $products ) ){
			$columns = 1;
			$cols_desktop = 1;
			$cols_tablet = 1;
			$cols_mobile = 1;
		}

		$cart_page_id = get_option('ec_option_cartpage');
		if ( function_exists( 'icl_object_id' ) ) {
			$cart_page_id = icl_object_id( $cart_page_id, 'page', true, ICL_LANGUAGE_CODE );
		}
		$cart_page = get_permalink( $cart_page_id );
		if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
			$https_class = new WordPressHTTPS();
			$cart_page = $https_class->makeUrlHttps( $cart_page );
		}

		echo "<div class=\"ec_product_shortcode" . ( ( $product_border ) ? '' : ' ec_product_shortcode_no_borders' ) . "\"><div class=\"ec_product_added_to_cart\"><div class=\"ec_product_added_icon\"></div>" . wp_easycart_language()->get_text( "product_page", "product_product_added_note" ) . "<a href=\"" . esc_attr( $cart_page ) . "\" title=\"" . wp_easycart_language()->get_text( "product_page", "product_view_cart" ) . "\">" . wp_easycart_language()->get_text( "product_page", "product_view_cart" ) . "</a></div><div id=\"ec_current_media_size\"></div>";
		if ( $layout_mode == 'slider' ) {
			$owl_options = (object) array(
				'margin'      => (int) $spacing,
				'loop'       => (bool) $slider_loop,
				'autoplay'     => (bool) $slider_auto_play,
				'autoplayTimeout'  => (int) $slider_auto_play_time,
				'center'      => (bool) $slider_center,
				'responsive'    => (object) array(
					'0'       => (object) array(
						'items'   => (int) $cols_mobile_small,
						'nav'    => (bool) $slider_nav_mobile,
						'dots'   => (bool) $slider_dot_mobile
					),
					'576'      => (object) array(
						'items'   => (int) $cols_mobile,
						'nav'    => (bool) $slider_nav_mobile,
						'dots'   => (bool) $slider_dot_mobile
					),
					'768'      => (object) array(
						'items'   => (int) $cols_tablet,
						'nav'    => (bool) $slider_nav_tablet,
						'dots'   => (bool) $slider_dot_tablet
					),
					'992'      => (object) array(
						'items'   => (int) $columns,
						'nav'    => (bool) $slider_nav_tablet,
						'dots'   => (bool) $slider_dot_tablet
					),
					'1200'     => (object) array(
						'items'   => (int) $cols_desktop,
						'nav'    => (bool) $slider_nav,
						'dots'   => (bool) $slider_dot
					),
					'1600'     => (object) array(
						'items'   => (int) $cols_desktop,
						'nav'    => (bool) $slider_nav,
						'dots'   => (bool) $slider_dot
					)
				)
			);
			echo "<div id=\"wpeasycart-owl-slider-" . esc_attr( rand( 10000, 999999 ) ) . "\" class=\"colsdesktop" . esc_attr( $cols_desktop ) . " columns" . esc_attr( $columns ) . " colstablet" . esc_attr( $cols_tablet ) . " colsmobile" . esc_attr( $cols_mobile ) . " colssmall" . esc_attr( $cols_mobile_small ) . " owl-wpeasycart owl-carousel" . ( ( $product_slider_nav_type == 'owl-simple' || $product_slider_nav_type == '' ) ? ' owl-simple' : '' ) . ( ( $product_slider_nav_type == 'owl-full' ) ? ' owl-full' : '' ) . ( ( $product_slider_nav_type == 'owl-nav-rounded' ) ? ' owl-simple owl-nav-rounded' : '' ) . " carousel-with-shadow" . ( ( $slider_nav_show ) ? '' : ' owl-nav-show' ) . ( ( $product_slider_nav_pos == 'owl-nav-inside' ) ? ' owl-nav-inside' : '' ) . ( ( $product_slider_nav_pos == 'owl-nav-top' ) ? ' owl-nav-top' : '' ) . "\" data-owl-options=\"" . htmlspecialchars( json_encode( $owl_options ) ) . "\" style=\"float:left; width:100%;\">"; // XSS OK. Output owl options, which are properly typed above.

		} else {
			echo "<ul class=\"ec_productlist_ul " . esc_attr( ( isset( $spacing ) ) ? 'sp-' . ((int)$spacing) : '' ) . " colsdesktop" . esc_attr( $cols_desktop ) . " columns" . esc_attr( $columns ) . " colstablet" . esc_attr( $cols_tablet ) . " colsmobile" . esc_attr( $cols_mobile ) . " colssmall" . esc_attr( $cols_mobile_small ) . " \" style=\"min-height:" . esc_attr( $minheight ) . ";\">";
		}

		for ( $prod_index=0; $prod_index<count( $products ); $prod_index++ ) {
			$product = new ec_product( $products[$prod_index], 0, 0, 1 );
			if ( $style == '1' ) {
				if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product.php' ) ) {
					include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product.php' );
				} else {
					include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_product.php' );
				}
			} else if ( $style == '2' ) {
				if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product_widget.php' ) ) {
					include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product_widget.php' );
				} else {
					include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_product_widget.php' );
				}
			} else {
				echo "<a href=\"" . esc_url( $product->get_product_link() ) . "\">";
				echo "<img src=\"" . esc_url( $product->get_product_single_image() ) . "\" alt=\"" . esc_attr( $product->title ) . "\" width=\"" . esc_attr( $imagew ) . "\" height=\"" . esc_attr( $imageh ) . "\">";
				echo "</a>";
				echo "<h3><a href=\"" . esc_url( $product->get_product_link() ) . "\">" . esc_attr( $product->title ) . "</a></h3>";
				echo "<span class=\"ec_price_button\" style=\"width:" . esc_attr( $width ) . "\">";
				if ( $product->has_sale_price() ) {
					echo "<span class=\"ec_price_before\"><del>" . esc_attr( $product->get_formatted_before_price() ) . "</del></span>";
					echo "<span class=\"ec_price_sale\">" . esc_attr( $product->get_formatted_price() ) . "</span>";
				} else {
					echo "<span class=\"ec_price\">" . esc_attr( $product->get_formatted_price() ) . "</span>";
				}
				echo "</span>";
			}
		}
		if ( $layout_mode == 'slider' ) {
			echo "</div>
			<style>
			@keyframes rotation{
				0% { transform:rotate(0deg); }
				100%{ transform:rotate(359deg); }
			}
			</style>
			<div class=\"wpec-product-slider-loader\" style=\"font-size: 14px; text-align: center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; width: 350px; top: 50%; left: 50%; position: absolute; margin-left: -165px; margin-top: -80px; cursor: pointer; text-align: center; z-index:99;\">
				<div>
					<div style=\"height: 30px; width: 30px; display: inline-block; box-sizing: content-box; opacity: 1; filter: alpha(opacity=100); -webkit-animation: rotation .7s infinite linear; -moz-animation: rotation .7s infinite linear; -o-animation: rotation .7s infinite linear; animation: rotation .7s infinite linear; border-left: 8px solid rgba(0, 0, 0, .2); border-right: 8px solid rgba(0, 0, 0, .2); border-bottom: 8px solid rgba(0, 0, 0, .2); border-top: 8px solid #fff; border-radius: 100%;\"></div>
				</div>
			</div>";
		} else {
			echo "</ul>";
		}
		echo "<div style=\"clear:both;\"></div></div>";
		if ( $layout_mode == 'slider' && ( wp_doing_ajax() || ( isset( $_GET['action'] ) && $_GET['action'] == 'elementor' ) ) ) {
			echo "<script>
			jQuery( '.ec_product_shortcode .owl-carousel' ).each( function() {
				jQuery( this ).on({
					'initialized.owl.carousel': function() {
						jQuery( this ).find( '.wp-easycart-carousel-item' ).show();
						jQuery( this ).parent().find( '.wpec-product-slider-loader' ).hide();
					}

				}).owlCarousel( JSON.parse( jQuery( this ).attr( 'data-owl-options' ) ) );
			} );
			</script>";
		}
	}
	return ob_get_clean();
}

//[ec_addtocart]
function load_ec_addtocart( $atts ) {
	extract( 
		shortcode_atts( 
			array(
				'productid' => 'NOPRODUCTID',
				'enable_quantity' => 1,
				'button_width' => false,
				'button_font' => false,
				'button_bg_color' => false,
				'button_text_color' => false,
				'background_add' => false,
			),
			$atts
		)
	);
	ob_start();
	$mysqli = new ec_db();
	$products = $mysqli->get_product_list( " WHERE product.product_id = " . $productid, "", "", "" );
	if ( count( $products ) > 0 ) {
		$product = new ec_product( $products[0], 0, 1, 1 );
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_add_to_cart_shortcode.php' ) ) {
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_add_to_cart_shortcode.php' );
		} else {
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_add_to_cart_shortcode.php' );
		}
	}
	return ob_get_clean();
}

//[ec_cartdisplay]
function load_ec_cartdisplay( $atts ) {

	ob_start();
	$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
	if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cartdisplay_shortcode.php' ) )
		include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cartdisplay_shortcode.php' );
	else
		include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cartdisplay_shortcode.php' );

	return ob_get_clean();
}

// [ec_cart_count]
function load_ec_cart_count( $atts ) {
	ob_start();
	$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
	echo $cart->total_items;
	return ob_get_clean();
}

//[ec_membership productid=''][/ec_membership]
function load_ec_membership( $atts, $content = NULL ) {
	extract( shortcode_atts( array(
		'productid' => '',
		'userroles' => ''
	), $atts ) );

	if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) {

		return "<h4>MEMBER AND NON MEMBER CONTENT SHOWN TO ADMIN USER</h4><hr />" . do_shortcode( $content ) . "<hr />";

	} else if ( $GLOBALS['ec_user']->user_id ) {

		$db = new ec_db();
		$is_member = false;

		if ( $productid != '' ) {
			$is_member = $db->has_membership_product_ids( $productid );

		}

		if ( $userroles != '' ) {
			$user_role_array = explode( ',', $userroles );

			if ( in_array( $GLOBALS['ec_user']->user_level, $user_role_array ) )
				$is_member = true;

		}

		if ( $is_member )
			return do_shortcode( $content );

		else
			return "";

	}

}

//[ec_membership_alt productid=''][/ec_membership_alt]
function load_ec_membership_alt( $atts, $content = NULL ) {
	extract( shortcode_atts( array(
		'productid' => '',
		'userroles' => ''
	), $atts ) );

	if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) {

		return "<h4>NON-MEMBER CONTENT (WORDPRESS ADMIN DISPLAY ONLY)</h4><hr />" . do_shortcode( $content ) . "<hr />";

	} else if ( $GLOBALS['ec_user']->user_id ) {

		$db = new ec_db();
		$is_member = false;

		if ( $productid != '' ) {
			$is_member = $db->has_membership_product_ids( $productid );

		}

		if ( $userroles != '' ) {
			$user_role_array = explode( ',', $userroles );

			if ( in_array( $GLOBALS['ec_user']->user_level, $user_role_array ) )
				$is_member = true;

		}


		if ( !$is_member )
			return do_shortcode( $content );

		else
			return "";

	} else {

		return do_shortcode( "[ec_account redirect='" . get_the_ID() . "']" ) . do_shortcode( $content );

	}

}

//[ec_store_table]
function load_ec_store_table_display( $atts ) {

	global $wpdb;

	extract( shortcode_atts( array(
		'productid' => '',
		'menuid' => '',
		'submenuid' => '',
		'subsubmenuid' => '',
		'categoryid' => '',
		'labels' => 'Model Number,Product Name,Price,',
		'columns' => 'model_number,title,price,details_link',
		'view_details' => 'VIEW DETAILS'
	), $atts ) );

	$label_start = explode( ",", $labels );
	$columns_start = explode( ",", $columns );

	$columns = array();
	$labels = array();

	for ( $k=0; $k<count($columns_start); $k++ ) {
		if ( $columns_start[$k] != '0' ) {
			$columns[] = $columns_start[$k];
			$labels[] = $label_start[$k];
		}
	}

	$storepageid = get_option('ec_option_storepage');

	if ( function_exists( 'icl_object_id' ) ) {
		$storepageid = icl_object_id( $storepageid, 'page', true, ICL_LANGUAGE_CODE );
	}

	$storepage = get_permalink( $storepageid );

	if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
		$https_class = new WordPressHTTPS();
		$storepage = $https_class->makeUrlHttps( $storepage );
	}

	if (substr_count($storepage, '?'))							$permalink_divider = "&";
	else														$permalink_divider = "?";

	$product_ids = array();
	$menu_ids = array();
	$submenu_ids = array();
	$subsubmenu_ids = array();
	$category_ids = array();

	if ( $productid != '' ) {
		$product_ids = explode( ",", $productid );
	}

	if ( $menuid != '' ) {
		$menu_ids = explode( ",", $menuid );
	}

	if ( $submenuid != '' ) {
		$submenu_ids = explode( ",", $submenuid );
	}

	if ( $subsubmenuid != '' ) {
		$subsubmenu_ids = explode( ",", $subsubmenuid );
	}

	if ( $categoryid != '' ) {
		$category_ids = explode( ",", $categoryid );
	}

	$has_added_to_where = false;
	$where_query = "";
	if ( count( $product_ids ) > 0 || 
		count( $menu_ids ) > 0 || 
		count( $submenu_ids ) > 0 || 
		count( $subsubmenu_ids ) > 0 || 
		count( $category_ids ) > 0 ) {

		$where_query = " WHERE";

	}

	if ( count( $product_ids ) > 0 ) {
		if ( !$has_added_to_where )
			$where_query .= " (";
		else
			$where_query .= " OR (";

		for ( $i=0; $i<count( $product_ids ); $i++ ) {
			if ( $i > 0 )
				$where_query .= " OR";
			$where_query .= $wpdb->prepare( " product.product_id = %d", $product_ids[$i] );
		}
		$where_query .= ")";
		$has_added_to_where = true;
	}

	if ( count( $menu_ids ) > 0 ) {
		if ( !$has_added_to_where )
			$where_query .= " (";
		else
			$where_query .= " OR (";

		for ( $i=0; $i<count( $menu_ids ); $i++ ) {
			if ( $i > 0 )
				$where_query .= " OR";

			$where_query .= $wpdb->prepare( " ( product.menulevel1_id_1 = %d OR product.menulevel2_id_1 = %d OR product.menulevel3_id_1 = %d )", $menu_ids[$i], $menu_ids[$i], $menu_ids[$i] );
		}
		$where_query .= ")";
		$has_added_to_where = true;
	}

	if ( count( $submenu_ids ) > 0 ) {
		if ( !$has_added_to_where )
			$where_query .= " (";
		else
			$where_query .= " OR (";

		for ( $i=0; $i<count( $submenu_ids ); $i++ ) {
			if ( $i > 0 )
				$where_query .= " OR";

			$where_query .= $wpdb->prepare( " ( product.menulevel1_id_2 = %d OR product.menulevel2_id_2 = %d OR product.menulevel3_id_2 = %d )", $submenu_ids[$i], $submenu_ids[$i], $submenu_ids[$i] );
		}
		$where_query .= ")";
		$has_added_to_where = true;
	}

	if ( count( $subsubmenu_ids ) > 0 ) {
		if ( !$has_added_to_where )
			$where_query .= " (";
		else
			$where_query .= " OR (";

		for ( $i=0; $i<count( $subsubmenu_ids ); $i++ ) {
			if ( $i > 0 )
				$where_query .= " OR";

			$where_query .= $wpdb->prepare( " ( product.menulevel1_id_3 = %d OR product.menulevel2_id_3 = %d OR product.menulevel3_id_3 = %d )", $subsubmenu_ids[$i], $subsubmenu_ids[$i], $subsubmenu_ids[$i] );
		}
		$where_query .= ")";
		$has_added_to_where = true;
	}

	if ( count( $category_ids ) > 0 ) {
		if ( !$has_added_to_where )
			$where_query .= " (";
		else
			$where_query .= " OR (";

		for ( $i=0; $i<count( $category_ids ); $i++ ) {
			if ( $i > 0 )
				$where_query .= " OR";

			$where_query .= $wpdb->prepare( " ec_categoryitem.category_id = %d", $category_ids[$i] );
		}
		$where_query .= ")";
		$has_added_to_where = true;
	}
	$order_query = " ORDER BY product.title ASC";
	$limit_query = "";
	$session_id = $GLOBALS['ec_cart_id'];

	$db = new ec_db();
	$products = $db->get_product_list( $where_query, $order_query, $limit_query, $session_id );

	ob_start();
	if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_store_table_display.php' ) )
		include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_store_table_display.php' );
	else
		include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_store_table_display.php' );

	return ob_get_clean();
}

//[ec_category_view]
function load_ec_category_view( $atts ) {

	extract( shortcode_atts( array(
		'parentid' => '0',
		'columns' => 2
	), $atts ) );

	ob_start();
	if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_category_view.php' ) )
		include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_category_view.php' );
	else
		include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_category_view.php' );

	return ob_get_clean();

}

//[ec_categories]
function load_ec_categories( $atts ) {

	if ( !defined( 'DONOTCACHEPAGE' ) )
		define( "DONOTCACHEPAGE", true );

	if ( !defined( 'DONOTCDN' ) )
		define('DONOTCDN', true);

	extract( shortcode_atts( array(
		'menuid' => 'NOMENU',
		'submenuid' => 'NOSUBMENU',
		'subsubmenuid' => 'NOSUBSUBMENU',
		'manufacturerid' => 'NOMANUFACTURER',
		'groupid' => 'NOGROUP',
		'modelnumber' => 'NOMODELNUMBER',
		'language' => 'NONE'
	), $atts ) );

	if ( $language != 'NONE' ) {
		wp_easycart_language()->update_selected_language( $language );
		$GLOBALS['ec_cart_data']->cart_data->translate_to = $language;
		$GLOBALS['ec_cart_data']->save_session_to_db( );
	}

	$GLOBALS['ec_store_shortcode_options'] = array( $menuid, $submenuid, $subsubmenuid, $manufacturerid, $groupid, $modelnumber );

	ob_start();
	$store_page = new ec_storepage( $menuid, $submenuid, $subsubmenuid, $manufacturerid, $groupid, $modelnumber );
	$store_page->display_category_page();
	return ob_get_clean();

}

//[ec_search]
function load_ec_search( $atts ) {

	extract( shortcode_atts( array(
		'label' => 'Search',
		'postid' => false
	), $atts ) );

	// Translate if needed
	$label = wp_easycart_language()->convert_text( $label );

	if ( $postid ) {
		$storepageid = $postid;
	} else {
		$storepageid = get_option( 'ec_option_storepage' );
	}

	if ( function_exists( 'icl_object_id' ) ) {
		$storepageid = icl_object_id( $storepageid, 'page', true, ICL_LANGUAGE_CODE );
	}
	$store_page = get_permalink( $storepageid );

	if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
		$https_class = new WordPressHTTPS();
		$store_page = $https_class->makeUrlHttps( $store_page );
	}

	if ( substr_count( $store_page, '?' ) )						$permalink_divider = "&";
	else														$permalink_divider = "?";

	ob_start();
	if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_search_widget.php' ) )
		include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_search_widget.php' );
	else
		include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_search_widget.php' );
	return ob_get_clean();

}

function ec_plugins_loaded() {
	/* Admin Form Actions */
	if ( ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) && isset( $_GET['ec_action'] ) && isset( $_GET['ec_language'] ) && $_GET['ec_action'] == "export-language" ) {
		wp_easycart_language()->export_language( sanitize_key( $_GET['ec_language'] ) );
		die();
	}
}

function ec_footer_load() {
	if ( get_option( 'ec_option_enable_newsletter_popup' ) && !isset( $_COOKIE['ec_newsletter_popup'] ) ) {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_newsletter_popup.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_newsletter_popup.php' );
		else
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_newsletter_popup.php' );

	}
}

add_action( 'wp', 'load_ec_pre' );
add_action( 'wp_enqueue_scripts', 'ec_load_css' );
add_action( 'wp_enqueue_scripts', 'ec_load_js' );
add_action( 'send_headers', 'ec_custom_headers' );
add_action( 'plugins_loaded', 'ec_plugins_loaded' );
add_action( 'wp_footer', 'ec_footer_load' );

if ( !is_admin() || wp_doing_ajax() || ( isset( $_GET['action'] ) && $_GET['action'] == 'elementor' ) ) {
	add_shortcode( 'ec_store', 'load_ec_store' );
	add_shortcode( 'ec_cart', 'load_ec_cart' );
	add_shortcode( 'ec_account', 'load_ec_account' );
	add_shortcode( 'ec_product', 'load_ec_product' );
	add_shortcode( 'ec_addtocart', 'load_ec_addtocart' );
	add_shortcode( 'ec_cartdisplay', 'load_ec_cartdisplay' );
	add_shortcode( 'ec_cart_count', 'load_ec_cart_count' );
	add_shortcode( 'ec_membership', 'load_ec_membership' );
	add_shortcode( 'ec_membership_alt', 'load_ec_membership_alt' );
	add_shortcode( 'ec_store_table', 'load_ec_store_table_display' );
	add_shortcode( 'ec_category_view', 'load_ec_category_view' );
	add_shortcode( 'ec_categories', 'load_ec_categories' );
	add_shortcode( 'ec_search', 'load_ec_search' );
}

add_filter( 'widget_text', 'do_shortcode');

add_action( 'wp_head', 'wpeasycart_seo_tags' );
add_action('wp_head', 'ec_theme_head_data');
add_action( 'wp_head', 'wpeasycart_order_completed' );
function wpeasycart_order_completed() {
	// Checkout Success Check.
	if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "checkout_success" && isset( $_GET['order_id'] ) ) {
		// Try and get order and run action
		$ec_db = new ec_db_admin();
		$order_id = (int) $_GET['order_id'];
		if ( $GLOBALS['ec_cart_data']->cart_data->is_guest ) {
			$order_row = $ec_db->get_guest_order_row( $order_id, $GLOBALS['ec_cart_data']->cart_data->guest_key );
		} else {
			$order_row = $ec_db->get_order_row( $order_id, $GLOBALS['ec_cart_data']->cart_data->user_id );
		}
		if ( $order_row ) { // order found and valid for user
			$order = new ec_orderdisplay( $order_row, true );
			do_action( 'wpeasycart_order_success_pre', $order_id, $order_row, $order->orderdetails );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'ec_load_dashicons' );
function ec_load_dashicons() {
	wp_enqueue_style( 'dashicons' );
}

//////////////////////////////////////////////
//UPDATE FUNCTIONS
//////////////////////////////////////////////

function wpeasycart_copyr( $source, $dest ) {

	// Check for symlinks
	if ( is_link( $source ) ) {
		return symlink( readlink( $source ), $dest );
	}

	// Simple copy for a file
	if ( is_file( $source ) ) {
		$success = copy( $source, $dest );
		if ( $success ) {
		  return true;
		} else {
			$err_message = "wpeasycart - error backing up " . $source . ". Updated halted.";
			exit( esc_attr( $err_message ) );
		}
	}

	// Make destination directory
	if ( !is_dir( $dest ) ) {
		$success = mkdir( $dest, 0755 );
		if ( !$success ) {
			$err_message = "wpeasycart - error creating backup directory: " . $dest . ". Updated halted.";
			exit( esc_attr( $err_message ) );
		}
	}

	// Loop through the folder
	$dir = dir( $source );
	while ( false !== $entry = $dir->read() ) {
		// Skip pointers
		if ($entry == '.' || $entry == '..') {
			continue;
		}

		// Deep copy directories
		wpeasycart_copyr( "$source/$entry", "$dest/$entry" ); // <------- defines wpeasycart copy action
	}

	// Clean up
	$dir->close();
	return true;
}

function wpeasycart_backup() {
	// Test for data folder
	if ( !file_exists( EC_PLUGIN_DATA_DIRECTORY . "/" ) ) {
		echo "YOU DO NOT HAVE A WP EASYCART DATA FOLDER, PLEASE <a href=\"http://www.wpeasycart.com/plugin-update-help/\" target=\"_blank\">CLICK HERE TO READ HOW TO PREVENT DATA LOSS DURING THE UPDATE</a>";
		die();
	}
}

function ec_recursive_remove_directory( $directory, $empty=FALSE ) {
	 // if the path has a slash at the end we remove it here
	 if ( substr( $directory, -1 ) == '/' )
		 $directory = substr( $directory, 0, -1);

	 // if the path is not valid or is not a directory ...
	 if ( !file_exists( $directory ) || !is_dir( $directory ) )
		 return FALSE;

	 // ... if the path is not readable
	 elseif (!is_readable($directory))
		 return FALSE;

	 // ... else if the path is readable
	 else {

		 // we open the directory
		 $handle = opendir( $directory );

		 // and scan through the items inside
		 while ( FALSE !== ( $item = readdir( $handle ) ) ) {
			 // if the filepointer is not the current directory
			 // or the parent directory
			 if ( $item != '.' && $item != '..' ) {
				 // we build the new path to delete
				 $path = $directory . '/' . $item;

				 // if the new path is a directory
				 if ( is_dir( $path ) ) {
					 // we call this function with the new path
					ec_recursive_remove_directory( $path );

				 // if the new path is a file
				 } else {
					 // we remove the file
					 unlink( $path );
				 }
			 }
		 }
		 // close the directory
		 closedir( $handle );

		 // if the option to empty is not set to true
		 if ( $empty == FALSE ) {
			 // try to delete the now empty directory
			 if ( ! rmdir( $directory ) ) {
				 // return false if not possible
				 return FALSE;
			 }
		 }
		 // return success
		 return TRUE;
	}
}

function ec_delete_directory_ftp( $resource, $path ) {
	$result_message = "";
	$list = ftp_nlist( $resource, $path );

	if ( empty($list) ) {
		$list = ec_ran_list_n( ftp_rawlist($resource, $path), $path . ( substr($path, strlen($path) - 1, 1) == "/" ? "" : "/" ) );
	}
	if ($list[0] != $path) {
		$path .= ( substr($path, strlen($path)-1, 1) == "/" ? "" : "/" );
		foreach ($list as $item) {
			if ($item != $path.".." && $item != $path.".") {
				$result_message .= ec_delete_directory_ftp($resource, $item);
			}
		}
		if (ftp_rmdir ($resource, $path)) {
			$result_message .= "Successfully deleted $path <br />\n";
		} else {
			$result_message .= "There was a problem while deleting $path <br />\n";
		}
	}
	else {
		$res = ftp_site( $resource, 'CHMOD 0777 ' . $path );
		if (ftp_delete ($resource, $path)) {
			$result_message .= "Successfully deleted $path <br />\n";
		} else {
			$result_message .= "There was a problem while deleting $path <br />\n";
		}
	}
	return $result_message;
}

function ec_ran_list_n($rawlist, $path) {
	$array = array();
	foreach ($rawlist as $item) {
		$filename = trim(substr($item, 55, strlen($item) - 55));
		if ($filename != "." || $filename != "..") {
		$array[] = $path . $filename;
		}
	}
	return $array;
}

add_filter( 'upgrader_pre_install', 'wpeasycart_backup', 10, 2 );

//////////////////////////////////////////////
//END UPDATE FUNCTIONS
//////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////
//AJAX SETUP FUNCTIONS
/////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_ec_ajax_get_optionitem_quantities', 'ec_ajax_get_optionitem_quantities' );
add_action( 'wp_ajax_nopriv_ec_ajax_get_optionitem_quantities', 'ec_ajax_get_optionitem_quantities' );
function ec_ajax_get_optionitem_quantities() {
	$product_id = (int) $_POST['product_id'];
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-product-details-' . $product_id ) ) {
		die();
	}

	$db = new ec_db();
	$optionitem_id_1 = (int) $_POST['optionitem_id_1'];

	if ( isset( $_POST['optionitem_id_2'] ) ) {
		$optionitem_id_2 = (int) $_POST['optionitem_id_2'];
	} else {
		$quantity_values = $db->get_option2_quantity_values( $product_id, $optionitem_id_1 );
		echo json_encode( $quantity_values );

		die();
	}

	if ( isset( $_POST['optionitem_id_3'] ) ) {
		$optionitem_id_3 = (int) $_POST['optionitem_id_3'];
	} else {
		$quantity_values = $db->get_option3_quantity_values( $product_id, $optionitem_id_1, $optionitem_id_2 );
		echo json_encode( $quantity_values );

		die();
	}

	if ( isset( $_POST['optionitem_id_4'] ) ) {
		$optionitem_id_4 = (int) $_POST['optionitem_id_4'];
	} else {
		$quantity_values = $db->get_option4_quantity_values( $product_id, $optionitem_id_1, $optionitem_id_2, $optionitem_id_3 );
		echo json_encode( $quantity_values );

		die();
	}


	$quantity_values = $db->get_option5_quantity_values( $product_id, $optionitem_id_1, $optionitem_id_2, $optionitem_id_3, $optionitem_id_4 );
	echo json_encode( $quantity_values );

	die();

}

add_action( 'wp_ajax_ec_ajax_add_to_cart_complete', 'ec_ajax_add_to_cart_complete' );
add_action( 'wp_ajax_nopriv_ec_ajax_add_to_cart_complete', 'ec_ajax_add_to_cart_complete' );
function ec_ajax_add_to_cart_complete() {
	$product_id = (int) $_POST['product_id'];
	if ( ! isset( $_POST['ec_cart_form_nonce'] ) || ! wp_verify_nonce( $_POST['ec_cart_form_nonce'], 'wp-easycart-add-to-cart-' . $product_id ) ) {
		die();
	}

	if ( isset( $_POST['ec_cart_form_action'] ) && 'add_to_cart_v3' == $_POST['ec_cart_form_action'] ) {
		wpeasycart_session()->handle_session();
		$ec_cartpage = new ec_cartpage();
		$ec_cartpage->process_form_action( sanitize_key( $_POST['ec_cart_form_action'] ) );
		wp_cache_flush();
		do_action( 'wpeasycart_cart_updated' );
	}
	$db = new ec_db();
	$tempcart = $db->get_temp_cart( $GLOBALS['ec_cart_data']->ec_cart_id );

	$cart_arr = array();
	$total_items = 0;
	$total_cost = 0;

	foreach ( $tempcart as $item ) {
		$cart_arr[] = array( 'title' => $item->title, 'price' => $GLOBALS['currency']->get_currency_display( $item->unit_price ), 'quantity' => $item->quantity );
		$total_items = $total_items + $item->quantity;
		$total_cost = $total_cost + ( $item->quantity * $item->unit_price );
	}
	$cart_arr[0]['total_items'] = $total_items;
	$cart_arr[0]['total_price'] = $GLOBALS['currency']->get_currency_display( $total_cost );
	echo json_encode( $cart_arr );
	die();
}

add_action( 'wp_ajax_ec_ajax_add_to_cart', 'ec_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_ec_ajax_add_to_cart', 'ec_ajax_add_to_cart' );
function ec_ajax_add_to_cart() {
	$product_id = (int) $_POST['product_id'];
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-add-to-cart-' . $product_id ) ) {
		die();
	}

	wpeasycart_session()->handle_session();

	$model_number = sanitize_text_field( $_POST['model_number'] );
	$quantity = (int) $_POST['quantity'];
	$db = new ec_db();

	$tempcart = $db->add_to_cart( $product_id, $GLOBALS['ec_cart_data']->ec_cart_id, $quantity, 0, 0, 0, 0, 0, "", "", "", 0.00, false, 1 );
	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );

	$cart_arr = array();
	$total_items = 0;
	$total_cost = 0;

	$store_page_id = get_option( 'ec_option_storepage' );
	if ( function_exists( 'icl_object_id' ) ) {
		$store_page_id = icl_object_id( $store_page_id, 'page', true, ICL_LANGUAGE_CODE );
	}
	$store_page = get_permalink( $store_page_id );
	if ( class_exists( 'WordPressHTTPS' ) && isset( $_SERVER['HTTPS'] ) ) {
		$https_class = new WordPressHTTPS();
		$store_page = $https_class->makeUrlHttps( $store_page );
	}
	if ( substr_count( $store_page, '?' ) ) {
		$permalink_divider = '&';
	} else {
		$permalink_divider = '?';
	}

	foreach ( $tempcart as $item ) {
		if ( !get_option( 'ec_option_use_old_linking_style' ) && $item->post_id != '0' ) {
			$link = $item->guid;
		} else {
			$link = $store_page . $permalink_divider . 'model_number=' . $item->model_number;
		}
		$cart_arr[] = array( 'title' => $item->title, 'price' => $GLOBALS['currency']->get_currency_display( $item->unit_price ), 'quantity' => $item->quantity, 'link' => $link );
		$total_items = $total_items + $item->quantity;
		$total_cost = $total_cost + ( $item->quantity * $item->unit_price );
	}
	$cart_arr[0]['total_items'] = $total_items;
	$cart_arr[0]['total_price'] = $GLOBALS['currency']->get_currency_display( $total_cost );
	echo json_encode( $cart_arr );

	die();
}

add_action( 'wp_ajax_ec_ajax_cartitem_update', 'ec_ajax_cartitem_update' );
add_action( 'wp_ajax_nopriv_ec_ajax_cartitem_update', 'ec_ajax_cartitem_update' );
function ec_ajax_cartitem_update() {
	$tempcart_id = (int) sanitize_text_field( $_POST['cartitem_id'] );
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-update-cart-item-' . $tempcart_id ) ) {
		die();
	}

	wpeasycart_session()->handle_session();

	// UPDATE CART ITEM
	$session_id = sanitize_text_field( $GLOBALS['ec_cart_data']->ec_cart_id );
	$quantity = (int) $_POST['quantity'];

	if ( is_numeric( $quantity ) ) {
		$db = new ec_db();
		$db->update_cartitem( $tempcart_id, $session_id, $quantity );
		wp_cache_flush();
		do_action( 'wpeasycart_cart_updated' );
	}
	// UPDATE CART ITEM

	// GET NEW CART ITEM INFO
	if ( isset( $_POST['ec_v3_24'] ) ) {
		$return_array = ec_get_cart_data();

		echo json_encode( $return_array );
	} else {
		$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );

		$unit_price = 0;
		$total_price = 0;
		$new_quantity = 0;
		for ( $i=0; $i<count( $cart->cart ); $i++ ) {
			if ( $cart->cart[$i]->cartitem_id == $tempcart_id ) {
				$unit_price = $cart->cart[$i]->unit_price;
				$total_price = $cart->cart[$i]->total_price;
				$new_quantity = $cart->cart[$i]->quantity;
			}
		}
		// GET NEW CART ITEM INFO
		$order_totals = ec_get_order_totals( $cart );

		echo esc_attr( $GLOBALS['currency']->get_currency_display( $unit_price ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $total_price ) ) . '***' . 
				esc_attr( $new_quantity ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->sub_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->tax_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->shipping_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->duty_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->vat_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( (-1) * $order_totals->discount_total ) ) . '***' .
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->grand_total ) );

		if ( $cart->total_items > 0 ) {

			if ( $cart->total_items != 1 ) {
				$items_label = wp_easycart_language()->get_text( 'cart', 'cart_menu_icon_label_plural' );
			} else {
				$items_label = wp_easycart_language()->get_text( 'cart', 'cart_menu_icon_label' );
			}

			echo '***' . esc_attr( $cart->total_items ) . ' ' . esc_attr( $items_label ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $cart->subtotal ) );
		} else {
			echo '***' . esc_attr( $cart->total_items ) . ' ' . esc_attr( $items_label );
		}
		echo '***' . esc_attr( $cart->total_items );
	}

	die();
}

add_action( 'wp_ajax_ec_ajax_cartitem_delete', 'ec_ajax_cartitem_delete' );
add_action( 'wp_ajax_nopriv_ec_ajax_cartitem_delete', 'ec_ajax_cartitem_delete' );
function ec_ajax_cartitem_delete() {
	$tempcart_id = sanitize_text_field( $_POST['cartitem_id'] );
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-delete-cart-item-' . $tempcart_id ) ) {
		die();
	}

	wpeasycart_session()->handle_session();

	//Get the variables from the AJAX call
	$session_id = sanitize_text_field( $GLOBALS['ec_cart_data']->ec_cart_id );

	// DELTE CART ITEM
	$db = new ec_db();
	$ret_data = $db->delete_cartitem( $tempcart_id, $session_id );
	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );

	// GET NEW CART ITEM INFO
	if ( isset( $_POST['ec_v3_24'] ) ) {
		$return_array = ec_get_cart_data();

		echo json_encode( $return_array );
	} else {
		$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
		$order_totals = ec_get_order_totals( $cart );

		echo esc_attr( $cart->total_items ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->sub_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->tax_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->shipping_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->duty_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->vat_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( (-1) * $order_totals->discount_total ) ) . '***' .
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->grand_total ) );

		if ( $cart->total_items != 1 ) {
			$items_label = wp_easycart_language()->get_text( 'cart', 'cart_menu_icon_label_plural' );
		} else {
			$items_label = wp_easycart_language()->get_text( 'cart', 'cart_menu_icon_label' );
		}

		if ( $cart->total_items > 0 ) {
			echo '***' . esc_attr( $cart->total_items ) . ' ' . esc_attr( $items_label ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $cart->subtotal ) );

		} else {
			echo '***' . esc_attr( $cart->total_items ) . ' ' . esc_attr( $items_label );

		}
		echo '***' . esc_attr( $cart->total_items );
	}

	die();

}

add_action( 'wp_ajax_ec_ajax_update_tip_amount', 'ec_ajax_update_tip_amount' );
add_action( 'wp_ajax_nopriv_ec_ajax_update_tip_amount', 'ec_ajax_update_tip_amount' );
function ec_ajax_update_tip_amount() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-update-tip-' . $session_id ) ) {
		die();
	}

	$GLOBALS['ec_cart_data']->cart_data->tip_amount = ( $_POST['tip_rate'] == 'custom' && (float) $_POST['tip_amount'] > 0 ) ? (float) $_POST['tip_amount'] : 0;
	$GLOBALS['ec_cart_data']->cart_data->tip_rate = ( $_POST['tip_rate'] == 'custom' ) ? 'custom' : (float) $_POST['tip_rate'];
	$GLOBALS['ec_cart_data']->save_session_to_db();

	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );

	// GET NEW CART ITEM INFO
	$return_array = ec_get_cart_data();
	echo json_encode( $return_array );
	die();
}

add_action( 'wp_ajax_ec_ajax_subscription_create_account', 'ec_ajax_subscription_create_account' );
add_action( 'wp_ajax_nopriv_ec_ajax_subscription_create_account', 'ec_ajax_subscription_create_account' );
function ec_ajax_subscription_create_account() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-subscription-create-account-' . $session_id ) ) {
		die();
	}

	global $wpdb;
	$ec_db = new ec_db();

	if ( $ec_db->does_user_exist( sanitize_email( $_POST['ec_contact_email'] ) ) ) {
		$response = array( 'error' => array( 
			'id'		=> 'user_create_error',
			'message'	=> wp_easycart_language()->get_text( "ec_errors", "email_exists_error" )
		) );

	} else {
		$password = md5( $_POST['ec_contact_password'] ); // XSS OK. Password Hashed Immediately
		$password = apply_filters( 'wpeasycart_password_hash', $password, $_POST['ec_contact_password'] ); // XSS OK. Password should not be hashed.

		$billing_id = $ec_db->insert_address( sanitize_text_field( $_POST['ec_contact_first_name'] ), sanitize_text_field( $_POST['ec_contact_last_name'] ), '', '', '', '', '', '', '', '' );
		$shipping_id = $ec_db->insert_address( sanitize_text_field( $_POST['ec_contact_first_name'] ), sanitize_text_field( $_POST['ec_contact_last_name'] ), '', '', '', '', '', '', '', '' );

		$user_id = $ec_db->insert_user( sanitize_email( $_POST['ec_contact_email'] ), $password, sanitize_text_field( $_POST['ec_contact_first_name'] ), sanitize_text_field( $_POST['ec_contact_last_name'] ), $billing_id, $shipping_id, 'shopper', 0, '', '' );

		$GLOBALS['ec_cart_data']->cart_data->user_id = $user_id;

		$ec_db->update_address_user_id( $billing_id, $user_id );
		$ec_db->update_address_user_id( $shipping_id, $user_id );

		do_action( 'wpeasycart_account_added', $user_id, sanitize_email( $_POST['ec_contact_email'] ), $_POST['ec_contact_password'] ); // XSS OK. Password should not be hashed.

		// Maybe insert WP user
		if ( apply_filters( 'wp_easycart_sync_wordpress_users', false ) ) {
			$user_name = 'wpeasycart_' . $user_id;
			$wp_user_id = wp_create_user( $user_name, $_POST['ec_contact_password'], sanitize_email( $_POST['ec_contact_email'] ) ); // XSS OK. Password should not be hashed.
			add_user_meta( $wp_user_id, 'wpeasycart_user_id', $user_id, true );
		}

		// Send registration email if needed
		if ( get_option( 'ec_option_send_signup_email' ) ) {
			$headers   = array();
			$headers[] = "MIME-Version: 1.0";
			$headers[] = "Content-Type: text/html; charset=utf-8";
			$headers[] = "From: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
			$headers[] = "Reply-To: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
			$headers[] = "X-Mailer: PHP/" . phpversion();

			$message = wp_easycart_language()->get_text( "account_register", "account_register_email_message" ) . " " . $_POST['ec_contact_email'];

			if ( get_option( 'ec_option_use_wp_mail' ) ) {
				wp_mail( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), wp_easycart_language()->get_text( "account_register", "account_register_email_title" ), $message, implode("\r\n", $headers ) );
			} else {
				$admin_email = stripslashes( get_option( 'ec_option_bcc_email_addresses' ) );
				$subject = wp_easycart_language()->get_text( "account_register", "account_register_email_title" );
				$mailer = new wpeasycart_mailer();
				$mailer->send_order_email( $admin_email, $subject, $message );
			}

		}

		$GLOBALS['ec_cart_data']->cart_data->is_guest = false;
		$GLOBALS['ec_cart_data']->cart_data->email = sanitize_email( $_POST['ec_contact_email'] );
		$GLOBALS['ec_cart_data']->cart_data->username = sanitize_text_field( $_POST['ec_contact_first_name'] . ' ' . $_POST['ec_contact_last_name'] );
		$GLOBALS['ec_cart_data']->cart_data->first_name = sanitize_text_field( $_POST['ec_contact_first_name'] );
		$GLOBALS['ec_cart_data']->cart_data->last_name = sanitize_text_field( $_POST['ec_contact_last_name'] );
		$GLOBALS['ec_cart_data']->cart_data->billing_first_name = sanitize_text_field( $_POST['ec_contact_first_name'] );
		$GLOBALS['ec_cart_data']->cart_data->billing_last_name = sanitize_text_field( $_POST['ec_contact_last_name'] );
		$GLOBALS['ec_cart_data']->cart_data->shipping_first_name = sanitize_text_field( $_POST['ec_contact_first_name'] );
		$GLOBALS['ec_cart_data']->cart_data->shipping_last_name = sanitize_text_field( $_POST['ec_contact_last_name'] );

		$GLOBALS['ec_user'] = new ec_user( '' );
		$GLOBALS['ec_cart_data']->save_session_to_db();
		wp_cache_flush();
		do_action( 'wpeasycart_cart_updated' );

		$response = array(
			'first_name' => sanitize_text_field( $_POST['ec_contact_first_name'] ),
			'last_name' => sanitize_text_field( $_POST['ec_contact_last_name'] ),
			'name' => sanitize_text_field( $_POST['ec_contact_first_name'] . ' ' . $_POST['ec_contact_last_name'] )
		);
	}

	echo json_encode( $response );
	die();
}

add_action( 'wp_ajax_ec_ajax_update_subscription_tax', 'ec_ajax_update_subscription_tax' );
add_action( 'wp_ajax_nopriv_ec_ajax_update_subscription_tax', 'ec_ajax_update_subscription_tax' );
function ec_ajax_update_subscription_tax() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-update-subscription-tax-' . $session_id ) ) {
		die();
	}
	
	global $wpdb;
	$ec_db = new ec_db();

	$GLOBALS['ec_cart_data']->cart_data->shipping_selector = ( $_POST['shipping_selector'] ) ? 'true' : '';

	$GLOBALS['ec_cart_data']->cart_data->vat_registration_number = preg_replace( '/[^a-zA-Z0-9\s]/', '', sanitize_text_field( $_POST['vat_registration_number'] ) );
	$GLOBALS['ec_user']->vat_registration_number = preg_replace( '/[^a-zA-Z0-9\s]/', '', sanitize_text_field( $_POST['vat_registration_number'] ) );
	$ec_db->update_user( $GLOBALS['ec_user']->user_id, preg_replace( '/[^a-zA-Z0-9\s]/', '', sanitize_text_field( $_POST['vat_registration_number'] ) ) );

	$GLOBALS['ec_cart_data']->cart_data->billing_first_name = sanitize_text_field( $_POST['billing_first_name'] );
	$GLOBALS['ec_cart_data']->cart_data->billing_last_name = sanitize_text_field( $_POST['billing_last_name'] );
	$GLOBALS['ec_cart_data']->cart_data->billing_company_name = sanitize_text_field( $_POST['billing_company_name'] );
	$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = sanitize_text_field( $_POST['billing_address'] );
	$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = sanitize_text_field( $_POST['billing_address2'] );
	$GLOBALS['ec_cart_data']->cart_data->billing_city = sanitize_text_field( $_POST['billing_city'] );
	$GLOBALS['ec_cart_data']->cart_data->billing_state = sanitize_text_field( $_POST['billing_state'] );
	$GLOBALS['ec_cart_data']->cart_data->billing_zip = sanitize_text_field( $_POST['billing_zip'] );
	$GLOBALS['ec_cart_data']->cart_data->billing_country = sanitize_text_field( $_POST['billing_country'] );
	$GLOBALS['ec_cart_data']->cart_data->billing_phone = sanitize_text_field( $_POST['billing_phone'] );

	$GLOBALS['ec_cart_data']->cart_data->shipping_first_name = sanitize_text_field( $_POST['shipping_first_name'] );
	$GLOBALS['ec_cart_data']->cart_data->shipping_last_name = sanitize_text_field( $_POST['shipping_last_name'] );
	$GLOBALS['ec_cart_data']->cart_data->shipping_company_name = sanitize_text_field( $_POST['shipping_company_name'] );
	$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = sanitize_text_field( $_POST['shipping_address'] );
	$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = sanitize_text_field( $_POST['shipping_address2'] );
	$GLOBALS['ec_cart_data']->cart_data->shipping_city = sanitize_text_field( $_POST['shipping_city'] );
	$GLOBALS['ec_cart_data']->cart_data->shipping_state = sanitize_text_field( $_POST['shipping_state'] );
	$GLOBALS['ec_cart_data']->cart_data->shipping_zip = sanitize_text_field( $_POST['shipping_zip'] );
	$GLOBALS['ec_cart_data']->cart_data->shipping_country = sanitize_text_field( $_POST['shipping_country'] );
	$GLOBALS['ec_cart_data']->cart_data->shipping_phone = sanitize_text_field( $_POST['shipping_phone'] );

	$GLOBALS['ec_cart_data']->save_session_to_db();
	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );

	wp_easycart_subscription_output_ajax_totals();

	die();
}

function wp_easycart_subscription_output_ajax_totals() {
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	global $wpdb;
	$ec_db = new ec_db();
	$products = $ec_db->get_product_list( $wpdb->prepare( " WHERE product.product_id = %d", (int) $_POST['product_id'] ), "", "", "" );
	$product = new ec_product( $products[0], 0, 1, 0 );
	$subscription_cart = array();

	if ( !get_option( 'ec_option_subscription_one_only' ) && $GLOBALS['ec_cart_data']->cart_data->subscription_quantity != "" ) { 
		$subscription_quantity = $GLOBALS['ec_cart_data']->cart_data->subscription_quantity;
	} else { 
		$subscription_quantity = 1; 
	}

	// Create Promotion Multiplier for Options
	$option_promotion_multiplier = 1;
	$option_promotion_discount = 0;
	$promotions = $GLOBALS['ec_promotions']->promotions;
	for ( $i=0; $i<count( $promotions ); $i++ ) {
		if ( $product->promotion_text == $promotions[$i]->promotion_name ) {
			if ( $promotions[$i]->price1 == 0 ) {
				$option_promotion_multiplier = round( $promotions[$i]->percentage1 / 100, 2 );
			} else if ( $promotions[$i]->price1 != 0 ) {
				$option_promotion_discount = $promotions[$i]->price1;
			}
		}
	}

	// Get option item price adjustments
	$option_total = 0;
	$option_total_onetime = 0;
	$option_weight = 0;
	$option_weight_onetime = 0;
	$optionitem_list = $GLOBALS['ec_options']->get_all_optionitems();
	$subscription_option1 = $subscription_option2 = $subscription_option3 = $subscription_option4 = $subscription_option5 = 0;
	if ( ( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_option1 ) && $GLOBALS['ec_cart_data']->cart_data->subscription_option1 != "" ) || 
		( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_option2 ) && $GLOBALS['ec_cart_data']->cart_data->subscription_option2 != "" ) || 
		( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_option3 ) && $GLOBALS['ec_cart_data']->cart_data->subscription_option3 != "" ) || 
		( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_option4 ) && $GLOBALS['ec_cart_data']->cart_data->subscription_option4 != "" ) || 
		( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_option5 ) && $GLOBALS['ec_cart_data']->cart_data->subscription_option5 != "" ) ) {


		if ( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_option1 ) && $GLOBALS['ec_cart_data']->cart_data->subscription_option1 != "" ) {
			$subscription_option1 = $GLOBALS['ec_cart_data']->cart_data->subscription_option1;
		}

		if ( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_option2 ) && $GLOBALS['ec_cart_data']->cart_data->subscription_option2 != "" ) {
			$subscription_option2 = $GLOBALS['ec_cart_data']->cart_data->subscription_option2;
		}

		if ( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_option3 ) && $GLOBALS['ec_cart_data']->cart_data->subscription_option3 != "" ) {
			$subscription_option3 = $GLOBALS['ec_cart_data']->cart_data->subscription_option3;
		}

		if ( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_option4 ) && $GLOBALS['ec_cart_data']->cart_data->subscription_option4 != "" ) {
			$subscription_option4 = $GLOBALS['ec_cart_data']->cart_data->subscription_option4;
		}

		if ( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_option5 ) && $GLOBALS['ec_cart_data']->cart_data->subscription_option5 != "" ) {
			$subscription_option5 = $GLOBALS['ec_cart_data']->cart_data->subscription_option5;
		}

		if ( $subscription_option1 != 0 ) {
			$subscription_option1 = $GLOBALS['ec_options']->get_optionitem( $subscription_option1 );
			if ( $subscription_option1->optionitem_price > 0 ) {
				$option_total += $subscription_option1->optionitem_price;
				$subscription_cart[] = (object) array(
					'vat_enabled' => ( $product->vat_rate != 0 ),
					'is_taxable' => $product->is_taxable,
					'item_total' => round( $subscription_option1->optionitem_price * $subscription_quantity, 2 ),
					'item_discount' => 0,
				);
			}
			if ( $subscription_option1->optionitem_weight > 0 ) {
				$option_weight += $subscription_option1->optionitem_weight;
			}
		}
		if ( $subscription_option2 != 0 ) {
			$subscription_option2 = $GLOBALS['ec_options']->get_optionitem( $subscription_option2 );
			if ( $subscription_option2->optionitem_price > 0 ) {
				$option_total += $subscription_option2->optionitem_price;
				$subscription_cart[] = (object) array(
					'vat_enabled' => ( $product->vat_rate != 0 ),
					'is_taxable' => $product->is_taxable,
					'item_total' => round( $subscription_option2->optionitem_price * $subscription_quantity, 2 ),
					'item_discount' => 0,
				);
			}
			if ( $subscription_option2->optionitem_weight > 0 ) {
				$option_weight += $subscription_option2->optionitem_weight;
			}
		}
		if ( $subscription_option3 != 0 ) {
			$subscription_option3 = $GLOBALS['ec_options']->get_optionitem( $subscription_option3 );
			if ( $subscription_option3->optionitem_price > 0 ) {
				$option_total += $subscription_option3->optionitem_price;
				$subscription_cart[] = (object) array(
					'vat_enabled' => ( $product->vat_rate != 0 ),
					'is_taxable' => $product->is_taxable,
					'item_total' => round( $subscription_option3->optionitem_price * $subscription_quantity, 2 ),
					'item_discount' => 0,
				);
			}
			if ( $subscription_option3->optionitem_weight > 0 ) {
				$option_weight += $subscription_option3->optionitem_weight;
			}
		}
		if ( $subscription_option4 != 0 ) {
			$subscription_option4 = $GLOBALS['ec_options']->get_optionitem( $subscription_option4 );
			if ( $subscription_option4->optionitem_price > 0 ) {
				$option_total += $subscription_option4->optionitem_price;
				$subscription_cart[] = (object) array(
					'vat_enabled' => ( $product->vat_rate != 0 ),
					'is_taxable' => $product->is_taxable,
					'item_total' => round( $subscription_option4->optionitem_price * $subscription_quantity, 2 ),
					'item_discount' => 0,
				);
			}
			if ( $subscription_option4->optionitem_weight > 0 ) {
				$option_weight += $subscription_option4->optionitem_weight;
			}
		}
		if ( $subscription_option5 != 0 ) {
			$subscription_option5 = $GLOBALS['ec_options']->get_optionitem( $subscription_option5 );
			if ( $subscription_option5->optionitem_price > 0 ) {
				$option_total += $subscription_option5->optionitem_price;
				$subscription_cart[] = (object) array(
					'vat_enabled' => ( $product->vat_rate != 0 ),
					'is_taxable' => $product->is_taxable,
					'item_total' => round( $subscription_option5->optionitem_price * $subscription_quantity, 2 ),
					'item_discount' => 0,
				);
			}
			if ( $subscription_option5->optionitem_weight > 0 ) {
				$option_weight += $subscription_option5->optionitem_weight;
			}
		}
	}
	
	// Subscription Advanced Options
	if ( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_advanced_option ) && $GLOBALS['ec_cart_data']->cart_data->subscription_advanced_option != "" ) {
		$subscription_advanced_options = maybe_unserialize( $GLOBALS['ec_cart_data']->cart_data->subscription_advanced_option );
		if ( $subscription_advanced_options ) {
			foreach( $subscription_advanced_options as $option ) {
				$optionitem = $GLOBALS['ec_options']->get_optionitem( $option['optionitem_id'] );
				if ( $optionitem->optionitem_disallow_shipping ) {
					$product->is_shippable = false;
				}
				if ( $optionitem && $optionitem->optionitem_price > 0 ) {
					if ( 'number' == $option['option_type'] ) {
						$option_total += ( $optionitem->optionitem_price * (int) $option['optionitem_value'] );
						$subscription_cart[] = (object) array(
							'vat_enabled' => ( $product->vat_rate != 0 ),
							'is_taxable' => $product->is_taxable,
							'item_total' => round( ( $optionitem->optionitem_price * (int) $option['optionitem_value'] ) * $subscription_quantity, 2 ),
							'item_discount' => 0,
						);
					} else {
						$option_total += $optionitem->optionitem_price;
						$subscription_cart[] = (object) array(
							'vat_enabled' => ( $product->vat_rate != 0 ),
							'is_taxable' => $product->is_taxable,
							'item_total' => round( $optionitem->optionitem_price * $subscription_quantity, 2 ),
							'item_discount' => 0,
						);
					}
				} else if ( $optionitem && $optionitem->optionitem_price_onetime > 0 ) {
					if ( 'number' == $option['option_type'] ) {
						$option_total_onetime += ( $optionitem->optionitem_price_onetime * (int) $option['optionitem_value'] );
						$subscription_cart[] = (object) array(
							'vat_enabled' => ( $product->vat_rate != 0 ),
							'is_taxable' => $product->is_taxable,
							'item_total' => round( ( $optionitem->optionitem_price_onetime * (int) $option['optionitem_value'] ), 2 ),
							'item_discount' => 0,
						);
					} else {
						$option_total_onetime += $optionitem->optionitem_price_onetime;
						$subscription_cart[] = (object) array(
							'vat_enabled' => ( $product->vat_rate != 0 ),
							'is_taxable' => $product->is_taxable,
							'item_total' => round( $optionitem->optionitem_price_onetime, 2 ),
							'item_discount' => 0,
						);
					}
				} else if ( $optionitem && $optionitem->optionitem_price_override > -1 ) {
					$product->price = $optionitem->optionitem_price_override;
				}
				if ( $optionitem && $optionitem->optionitem_weight > 0 ) {
					if ( 'number' == $option['option_type'] ) {
						$option_weight += ( $optionitem->optionitem_weight * (int) $option['optionitem_value'] );
					} else {
						$option_weight += $optionitem->optionitem_weight;
					}
				} else if ( $optionitem && $optionitem->optionitem_weight_onetime > 0 ) {
					if ( 'number' == $option['option_type'] ) {
						$option_weight_onetime += ( $optionitem->optionitem_weight_onetime * (int) $option['optionitem_value'] );
					} else {
						$option_weight_onetime += $optionitem->optionitem_weight_onetime;
					}
				} else if ( $optionitem && $optionitem->optionitem_weight_override > -1 ) {
					$product->weight = $optionitem->optionitem_weight_override;
				}
			}
		}
	}

	$subscription_cart[] = (object) array(
		'vat_enabled' => ( $product->vat_rate != 0 ),
		'is_taxable' => $product->is_taxable,
		'item_total' => round( $product->price * $subscription_quantity, 2 ),
		'item_discount' => 0,
	);

	if ( $product->is_shippable ) {
		$ship_price_total = ( ( $product->price + $option_total ) * $subscription_quantity ) + $option_total_onetime;
		$ship_weight_total = ( ( $product->weight + $option_weight ) * $subscription_quantity ) + $option_weight_onetime;
		$ship_quantity = $subscription_quantity;
	} else {
		$ship_price_total = 0;
		$ship_weight_total = 0;
		$ship_quantity = 0;
	}

	$product->weight = $ship_weight_total;
	$product->quantity = $ship_quantity;
	do_action( 'wpeasycart_cart_subscription_updated', $product, $subscription_quantity );

	$cartpage= new ec_cartpage();
	$cartpage->cart->cart = array( $product );
	$cartpage->shipping = new ec_shipping( $ship_price_total, $ship_weight_total, $ship_quantity, 'RADIO', $GLOBALS['ec_user']->freeshipping, $product->length, $product->width, $product->height * $subscription_quantity, array( $product ) );
	$cartpage->shipping->change_shipping_js_func = 'ec_cart_subscription_shipping_method_change';

	$handling_total = $product->handling_price + ( $product->handling_price_each * $subscription_quantity );
	$shipping_total = floatval( $cartpage->shipping->get_shipping_price( $handling_total ) );
	$subscription_cart[] = (object) array(
		'vat_enabled' => ! get_option( 'ec_option_no_vat_on_shipping' ),
		'is_taxable' => get_option( 'ec_option_collect_tax_on_shipping' ),
		'item_total' => round( $shipping_total, 2 ),
		'item_discount' => 0,
	);

	if ( get_option( 'ec_option_collect_shipping_for_subscriptions' ) && $product->is_shippable ) {
		$cartpage->cart->shippable_total_items = $subscription_quantity;
	}

	$coupon = $GLOBALS['ec_coupons']->redeem_coupon_code( $GLOBALS['ec_cart_data']->cart_data->coupon_code );
	$coupon_code_invalid = true;
	$coupon_applicable = true;
	$coupon_exceeded_redemptions = false;
	$coupon_expired = false;

	if ( !$coupon ) { // Invalid Coupon
		$coupon_code_invalid = false;
	} else if ( $coupon->by_product_id && $coupon->product_id != $product->product_id ) { // Product does not match
		$coupon_applicable = false;
	} else if ( $coupon->by_manufacturer_id && $coupon->manufacturer_id != $product->manufacturer_id ) { // Manufacturer Does not Match
		$coupon_applicable = false;
	} else if ( $coupon->by_category_id ) { // validate category id match
		$has_categories = $wpdb->get_results( $wpdb->prepare( "SELECT categoryitem_id FROM ec_categoryitem WHERE category_id = %d AND product_id = %d", $coupon->category_id, $product->product_id ) );
		if ( !$has_categories ) {
			$coupon_applicable = false;
		}
	} else if ( $coupon->max_redemptions != 999 && $coupon->times_redeemed >= $coupon->max_redemptions ) {
		$coupon_exceeded_redemptions = true;
	} else if ( $coupon->coupon_expired ) {
		$coupon_expired = true;
	}

	$discount_amount = 0;
	$is_dollar_discount = false;
	if ( $coupon && $coupon_applicable && ! $coupon_exceeded_redemptions && ! $coupon_expired ) {
		if ( $coupon->is_percentage_based ) {
			$coupon_percentage = round( $coupon->promo_percentage / 100, 2 );
			for ( $i = 0; $i < count( $subscription_cart ); $i++ ) {
				$subscription_cart[ $i ]->item_discount = round( $subscription_cart[ $i ]->item_total * $coupon_percentage, 2 );
				$discount_amount += $subscription_cart[ $i ]->item_discount;
			}
		} else if ( $coupon->is_dollar_based ) {
			$is_dollar_discount = true;
			$discount_amount = $coupon->promo_dollar;
		}
		if ( $discount_amount > ( ( $product->price + $option_total ) * $subscription_quantity ) + $option_total_onetime ) {
			$discount_amount = ( ( $product->price + $option_total ) * $subscription_quantity ) + $option_total_onetime;
		}
	} else if ( $option_promotion_multiplier < 1 ) {
		for ( $i = 0; $i < count( $subscription_cart ); $i++ ) {
			$subscription_cart[ $i ]->item_discount = round( $subscription_cart[ $i ]->item_total * $option_promotion_multiplier, 2 );
			$discount_amount += $subscription_cart[ $i ]->item_discount;
		}
	} else if ( $option_promotion_discount > 0 ) {
		$is_dollar_discount = true;
		$discount_amount = round( $option_promotion_discount, 2 );
	}
	$discount_amount = round( $discount_amount, 2 );

	// Get and Print Order Totals
	do_action( 'wpeasycart_cart_subscription_pre_tax', $product, $subscription_quantity, $shipping_total, $handling_total, $discount_amount );
	wpeasycart_taxcloud()->setup_subscription_for_tax( $product, $subscription_quantity, $discount_amount );
	if ( function_exists( 'wpeasycart_taxjar' ) ) {
		wpeasycart_taxjar()->setup_subscription_for_tax( $product, $subscription_quantity, $discount_amount );
	}
	$sub_total = ( ( $product->price + $option_total ) * $subscription_quantity ) + $option_total_onetime;
	if ( $is_dollar_discount ) {
		for ( $i = 0; $i < count( $subscription_cart ); $i++ ) {
			$subscription_cart[$i]->item_total = $subscription_cart[$i]->item_total - round( ( $subscription_cart[$i]->item_total / ( $sub_total + $shipping_total ) ) * $discount_amount, 2 );
		}
	} else {
		for ( $i = 0; $i < count( $subscription_cart ); $i++ ) {
			$subscription_cart[$i]->item_total = $subscription_cart[$i]->item_total - $subscription_cart[$i]->item_discount;
		}
	}
	$sub_total -= $discount_amount;
	$tax_subtotal = ( $product->is_taxable ) ? $sub_total - ( $product->subscription_signup_fee * $subscription_quantity ) : 0;
	$vat_subtotal = ( $product->vat_rate > 0 ) ? $sub_total - ( $product->subscription_signup_fee * $subscription_quantity ) : 0;
	$ec_tax = new ec_tax( $sub_total, $tax_subtotal, $vat_subtotal, $GLOBALS['ec_cart_data']->cart_data->shipping_state, $GLOBALS['ec_cart_data']->cart_data->shipping_country, $GLOBALS['ec_user']->taxfree, 0, $subscription_cart, true );

	$tax_total = $ec_tax->tax_total;
	$vat_rate = $ec_tax->vat_rate;
	$vat_total = $ec_tax->vat_total;

	$coupon_message = '';
	$coupon_status = '';

	if ( !$coupon_code_invalid ) {
		$coupon_message = wp_easycart_language()->get_text( 'cart_coupons', 'cart_invalid_coupon' );
		$coupon_status = "invalid";

	} else if ( !$coupon_applicable ) {
		$coupon_message = wp_easycart_language()->get_text( 'cart_coupons', 'cart_not_applicable_coupon' );
		$coupon_status = "invalid";

	} else if ( $coupon_exceeded_redemptions ) {
		$coupon_message = wp_easycart_language()->get_text( 'cart_coupons', 'cart_max_exceeded_coupon' );
		$coupon_status = "invalid";

	} else if ( $coupon_expired ) {
		$coupon_message = wp_easycart_language()->get_text( 'cart_coupons', 'cart_coupon_expired' );
		$coupon_status = "invalid";

	} else {
		$cartpage = new ec_cartpage();
		if ( $cartpage->discount->coupon_matches <= 0 ) {
			$coupon_message = wp_easycart_language()->get_text( 'cart_coupons', 'coupon_not_applicable' );
		} else {
			$coupon_message = $coupon->message;
		}
		$coupon_status = "valid";

	}

	if ( $product->trial_period_days > 0 ) {
		$grand_total = ( $product->subscription_signup_fee * $subscription_quantity );
	} else if ( $ec_tax->vat_included ) {
		$grand_total = ( ( $product->price + $option_total + $product->subscription_signup_fee ) * $subscription_quantity ) + $option_total_onetime - $discount_amount + $tax_total + $ec_tax->hst + $ec_tax->gst + $ec_tax->pst + $shipping_total;
	} else {
		$grand_total = ( ( $product->price + $option_total + $product->subscription_signup_fee ) * $subscription_quantity ) + $option_total_onetime - $discount_amount + $tax_total + $vat_total + $ec_tax->hst + $ec_tax->gst + $ec_tax->pst + $shipping_total;
	}

	ob_start();
	$cartpage->shipping->print_shipping_options( wp_easycart_language( )->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ), wp_easycart_language( )->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ) );
	$shipping_method_content = ob_get_clean();

	$billing_address_display = esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_first_name . ' ' . $GLOBALS['ec_cart_data']->cart_data->billing_last_name ) . ', ';
	$billing_address_display .= esc_attr( ( ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_company_name ) ? $GLOBALS['ec_cart_data']->cart_data->billing_company_name . ', ' : '' ) );
	$billing_address_display .= esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 ) . ', ';
	$billing_address_display .= esc_attr( ( ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 ) ? $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 . ', ' : '' ) );
	$billing_address_display .= esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_city ) . ' ' . esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_state ) . ' ' . esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_zip ) . ', ' . esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_country );
	$billing_address_display .= esc_attr( ( ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_phone ) ? ', ' . $GLOBALS['ec_cart_data']->cart_data->billing_phone : '' ) );
	$shipping_address_display = $billing_address_display;
	if ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_selector && 'true' == $GLOBALS['ec_cart_data']->cart_data->shipping_selector ) {
		$shipping_address_display = esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_first_name . ' ' . $GLOBALS['ec_cart_data']->cart_data->shipping_last_name ) . ', ';
		$shipping_address_display .= esc_attr( ( ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_company_name ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_company_name . ', ' : '' ) );
		$shipping_address_display .= esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 ) . ', ';
		$shipping_address_display .= esc_attr( ( ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 . ', ' : '' ) );
		$shipping_address_display .= esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_city ) . ' ' . esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_state ) . ' ' . esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_zip ) . ', ' . esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_country );
		$shipping_address_display .= esc_attr( ( ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_phone ) ? ', ' . $GLOBALS['ec_cart_data']->cart_data->shipping_phone : '' ) );
	}

	echo json_encode( array(
		'quantity'			=> $subscription_quantity, 
		'subtotal'			=> $GLOBALS['currency']->get_currency_display( $sub_total ),
		'has_tax'			=> ( $tax_total > 0 ) ? 1 : 0,
		'tax_total'			=> $product->get_option_price_formatted( $tax_total, 1 ), 
		'hst_total'			=> $product->get_option_price_formatted( $ec_tax->hst, 1 ),
		'hst_rate'			=> (string) $ec_tax->hst_rate,
		'pst_total'			=> $product->get_option_price_formatted( $ec_tax->pst, 1 ),
		'pst_rate'			=> (string) $ec_tax->pst_rate,
		'gst_total'			=> $product->get_option_price_formatted( $ec_tax->gst, 1 ),
		'gst_rate'			=> (string) $ec_tax->gst_rate,
		'is_shippable'		=> ( get_option( 'ec_option_collect_shipping_for_subscriptions' ) && get_option( 'ec_option_use_shipping' ) && $product->is_shippable ),
		'shipping_total'	=> ( ( $product->subscription_shipping_recurring ) ? $product->get_option_price_formatted( $shipping_total, 1 ): $GLOBALS['currency']->get_currency_display( $shipping_total ) ),
		'shipping_methods'	=> $shipping_method_content,
		'discount_total'	=> $GLOBALS['currency']->get_currency_display( (-1) * $discount_amount ), 
		'has_vat'			=> ( $vat_total > 0 ) ? 1 : 0,
		'vat_total'			=> $product->get_option_price_formatted( $vat_total, 1 ),
		'vat_rate'			=> $vat_rate,
		'vat_rate_formatted'=> $cartpage->get_vat_rate_formatted( $vat_rate ),
		'grand_total'		=> $GLOBALS['currency']->get_currency_display( $grand_total ),
		'coupon_message'	=> $coupon_message,
		'coupon_status'		=> $coupon_status,
		'has_discount'		=> ( $discount_amount == 0 ) ? 0 : 1,
		'price_formatted'	=> $product->get_price_formatted( $subscription_quantity ),
		'billing_address_display' => $billing_address_display,
		'shipping_address_display' => $shipping_address_display,
	) );
}

add_action( 'wp_ajax_ec_ajax_save_checkout_info', 'ec_ajax_save_checkout_info' );
add_action( 'wp_ajax_nopriv_ec_ajax_save_checkout_info', 'ec_ajax_save_checkout_info' );
function ec_ajax_save_checkout_info() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['wpeasycart_checkout_nonce'] ) ) {
		die();
	}

	if ( ! wp_verify_nonce( sanitize_text_field( $_POST['wpeasycart_checkout_nonce'] ), 'wp-easycart-save-checkout-info-' . $session_id ) ) {
		die();
	}

	$ec_db = new ec_db();
	$errors = false;

	// Add recaptcha check
	
	// Manage Subscriber
	$is_subscriber = ( isset( $_POST['ec_cart_is_subscriber'] ) && '1' == $_POST['ec_cart_is_subscriber'] ) ? 1 : 0;
	if ( isset( $_POST['ec_cart_is_subscriber'] ) && '1' == $_POST['ec_cart_is_subscriber'] ) {
		$first_name = sanitize_text_field( $_POST['ec_shipping_name'] );
		$last_name = sanitize_text_field( $_POST['ec_shipping_last_name'] );
		$email = sanitize_text_field( $_POST['ec_contact_email'] );

		$ec_db->insert_subscriber( $email, $first_name, $last_name );

		if ( $GLOBALS['ec_user']->user_id ) {
			global $wpdb;
			$wpdb->query( $wpdb->prepare( "UPDATE ec_user SET is_subscriber = 1 WHERE ec_user.user_id = %d", $GLOBALS['ec_user']->user_id ) );
		}

		// MyMail Hook
		if ( function_exists( 'mailster' ) ) {
			$subscriber_id = mailster('subscribers')->add(array(
				'firstname' => $first_name,
				'lastname' => $last_name,
				'email' => $email,
				'status' => 1,
			), false );
		}

		do_action( 'wpeasycart_insert_subscriber', $email, $first_name, $last_name );
	}
	
	$GLOBALS['ec_cart_data']->cart_data->vat_registration_number = sanitize_text_field( ( isset( $_POST['ec_vat_registration_number'] ) ) ? $_POST['vat_registration_number'] : '' );
	
	$address_type = 'shipping';
	if ( ! isset( $_POST['ec_shipping_country'] ) ) {
		$address_type = 'billing';
	}

	$name = explode( ' ', sanitize_text_field( $_POST['ec_' . $address_type . '_name'] ) );
	$first_name = $last_name = '';
	if ( is_array( $name ) ) {
		$first_name = ( isset( $name[0] ) ) ? $name[0] : $_POST['ec_' . $address_type . '_name'];
		for ( $i = 1; $i < count( $name ); $i++ ) {
			if ( $i > 1 ) {
				$last_name .= ' ';
			}
			$last_name .= $name[ $i ];
		}
	}
	if ( '' == $last_name && isset( $_POST['ec_' . $address_type . '_last_name'] ) ) {
		$last_name = sanitize_text_field( $_POST['ec_' . $address_type . '_last_name'] );
	}

	if ( 'shipping' == $type ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_selector = ( isset( $_POST['ec_shipping_selector'] ) && 1 == (int) $_POST['ec_shipping_selector'] ) ? 'true' : '';
	}
	$GLOBALS['ec_cart_data']->cart_data->{ $address_type . '_first_name' } = $first_name;
	$GLOBALS['ec_cart_data']->cart_data->{ $address_type . '_last_name' } = $last_name;
	$GLOBALS['ec_cart_data']->cart_data->{ $address_type . '_company_name' } = sanitize_text_field( $_POST['ec_' . $address_type . '_company_name'] );
	$GLOBALS['ec_cart_data']->cart_data->{ $address_type . '_address_line_1' } = sanitize_text_field( $_POST['ec_' . $address_type . '_address_line_1'] );
	$GLOBALS['ec_cart_data']->cart_data->{ $address_type . '_address_line_2' } = sanitize_text_field( $_POST['ec_' . $address_type . '_address_line_2'] );
	$GLOBALS['ec_cart_data']->cart_data->{ $address_type . '_city' } = sanitize_text_field( $_POST['ec_' . $address_type . '_city'] );
	$GLOBALS['ec_cart_data']->cart_data->{ $address_type . '_state' } = sanitize_text_field( $_POST['ec_' . $address_type . '_state'] );
	$GLOBALS['ec_cart_data']->cart_data->{ $address_type . '_zip' } = sanitize_text_field( $_POST['ec_' . $address_type . '_zip'] );
	$GLOBALS['ec_cart_data']->cart_data->{ $address_type . '_country' } = sanitize_text_field( $_POST['ec_' . $address_type . '_country'] );
	$GLOBALS['ec_cart_data']->cart_data->{ $address_type . '_phone' } = sanitize_text_field( $_POST['ec_' . $address_type . '_phone'] );
	$GLOBALS['ec_cart_data']->cart_data->order_notes = sanitize_text_field( $_POST['ec_order_notes'] );
	if ( 'shipping' == $address_type && '' == $GLOBALS['ec_cart_data']->cart_data->shipping_selector ) {
		$GLOBALS['ec_cart_data']->cart_data->billing_first_name = $GLOBALS['ec_cart_data']->cart_data->shipping_first_name;
		$GLOBALS['ec_cart_data']->cart_data->billing_last_name = $GLOBALS['ec_cart_data']->cart_data->shipping_last_name;
		$GLOBALS['ec_cart_data']->cart_data->billing_company_name = $GLOBALS['ec_cart_data']->cart_data->shipping_company_name;
		$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1;
		$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2;
		$GLOBALS['ec_cart_data']->cart_data->billing_city = $GLOBALS['ec_cart_data']->cart_data->shipping_city;
		$GLOBALS['ec_cart_data']->cart_data->billing_state = $GLOBALS['ec_cart_data']->cart_data->shipping_state;
		$GLOBALS['ec_cart_data']->cart_data->billing_zip = $GLOBALS['ec_cart_data']->cart_data->shipping_zip;
		$GLOBALS['ec_cart_data']->cart_data->billing_country = $GLOBALS['ec_cart_data']->cart_data->shipping_country;
		$GLOBALS['ec_cart_data']->cart_data->billing_phone = $GLOBALS['ec_cart_data']->cart_data->shipping_phone;
	}
	if ( $GLOBALS['ec_user']->user_id ) {
		$GLOBALS['ec_cart_data']->cart_data->user_id = $GLOBALS['ec_user']->user_id;
		$GLOBALS['ec_cart_data']->cart_data->email = $GLOBALS['ec_user']->email;
		$GLOBALS['ec_cart_data']->cart_data->is_guest = false;
		$GLOBALS['ec_cart_data']->cart_data->guest_key = '';
		$GLOBALS['ec_cart_data']->cart_data->first_name = $first_name;
		$GLOBALS['ec_cart_data']->cart_data->last_name = $last_name;
	} else {
		$GLOBALS['ec_cart_data']->cart_data->email = sanitize_text_field( $_POST['ec_contact_email'] );
		if ( isset( $_POST['ec_create_account'] ) && '1' == $_POST['ec_create_account'] ) {
			if ( $ec_db->does_user_exist( sanitize_email( $_POST['ec_contact_email'] ) ) ) {
				$errors = 'user_create_error';
			} else {
				$password = md5( $_POST['ec_contact_password'] ); // XSS OK. Password Hashed Immediately
				$password = apply_filters( 'wpeasycart_password_hash', $password, $_POST['ec_contact_password'] ); // XSS OK. Password should not be hashed.
				$billing_id = $ec_db->insert_address(
					( ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_first_name ) ? $GLOBALS['ec_cart_data']->cart_data->billing_first_name : $GLOBALS['ec_cart_data']->cart_data->shipping_first_name ),
					( ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_last_name ) ? $GLOBALS['ec_cart_data']->cart_data->billing_last_name : $GLOBALS['ec_cart_data']->cart_data->shipping_last_name ),
					( ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 ) ? $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 : $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 ),
					( ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 ) ? $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 : $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 ),
					( ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_city ) ? $GLOBALS['ec_cart_data']->cart_data->billing_city : $GLOBALS['ec_cart_data']->cart_data->shipping_city ),
					( ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_state ) ? $GLOBALS['ec_cart_data']->cart_data->billing_state : $GLOBALS['ec_cart_data']->cart_data->shipping_state ),
					( ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_zip ) ? $GLOBALS['ec_cart_data']->cart_data->billing_zip : $GLOBALS['ec_cart_data']->cart_data->shipping_zip ),
					( ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_country ) ? $GLOBALS['ec_cart_data']->cart_data->billing_country : $GLOBALS['ec_cart_data']->cart_data->shipping_country ),
					( ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_phone ) ? $GLOBALS['ec_cart_data']->cart_data->billing_phone : $GLOBALS['ec_cart_data']->cart_data->shipping_phone ),
					( ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_company_name ) ? $GLOBALS['ec_cart_data']->cart_data->billing_company_name : $GLOBALS['ec_cart_data']->cart_data->shipping_company_name )
				);
				$shipping_id = $ec_db->insert_address(
					( ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_first_name ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_first_name : $GLOBALS['ec_cart_data']->cart_data->billing_first_name ),
					( ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_last_name ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_last_name : $GLOBALS['ec_cart_data']->cart_data->billing_last_name ),
					( ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 : $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 ),
					( ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 : $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 ),
					( ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_city ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_city : $GLOBALS['ec_cart_data']->cart_data->billing_city ),
					( ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_state ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_state : $GLOBALS['ec_cart_data']->cart_data->billing_state ),
					( ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_zip ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_zip : $GLOBALS['ec_cart_data']->cart_data->billing_zip ),
					( ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_country ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_country : $GLOBALS['ec_cart_data']->cart_data->billing_country ),
					( ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_phone ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_phone : $GLOBALS['ec_cart_data']->cart_data->billing_phone ),
					( ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_company_name ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_company_name : $GLOBALS['ec_cart_data']->cart_data->billing_company_name )
				);
				$user_id = $ec_db->insert_user(
					sanitize_email( $_POST['ec_contact_email'] ),
					$password,
					( ( isset( $_POST['ec_contact_first_name'] ) ) ? sanitize_text_field( $_POST['ec_contact_first_name'] ) : $first_name ),
					( ( isset( $_POST['ec_contact_last_name'] ) ) ? sanitize_text_field( $_POST['ec_contact_last_name'] ) : $last_name ),
					$billing_id,
					$shipping_id,
					'shopper',
					0,
					'',
					''
				);

				$GLOBALS['ec_cart_data']->cart_data->user_id = $user_id;

				$ec_db->update_address_user_id( $billing_id, $user_id );
				$ec_db->update_address_user_id( $shipping_id, $user_id );

				do_action( 'wpeasycart_account_added', $user_id, sanitize_email( $_POST['ec_contact_email'] ), $_POST['ec_contact_password'] ); // XSS OK. Password should not be hashed.

				// Maybe insert WP user
				if ( apply_filters( 'wp_easycart_sync_wordpress_users', false ) ) {
					$user_name = 'wpeasycart_' . $user_id;
					$wp_user_id = wp_create_user( $user_name, $_POST['ec_contact_password'], sanitize_email( $_POST['ec_contact_email'] ) ); // XSS OK. Password should not be hashed.
					add_user_meta( $wp_user_id, 'wpeasycart_user_id', $user_id, true );
				}

				// Send registration email if needed
				if ( get_option( 'ec_option_send_signup_email' ) ) {
					$headers   = array();
					$headers[] = "MIME-Version: 1.0";
					$headers[] = "Content-Type: text/html; charset=utf-8";
					$headers[] = "From: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
					$headers[] = "Reply-To: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
					$headers[] = "X-Mailer: PHP/" . phpversion();

					$message = wp_easycart_language()->get_text( "account_register", "account_register_email_message" ) . " " . sanitize_email( $_POST['ec_contact_email'] );

					if ( get_option( 'ec_option_use_wp_mail' ) ) {
						wp_mail( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), wp_easycart_language()->get_text( "account_register", "account_register_email_title" ), $message, implode("\r\n", $headers ) );
					} else {
						$admin_email = stripslashes( get_option( 'ec_option_bcc_email_addresses' ) );
						$subject = wp_easycart_language()->get_text( "account_register", "account_register_email_title" );
						$mailer = new wpeasycart_mailer();
						$mailer->send_order_email( $admin_email, $subject, $message );
					}
				}

				$GLOBALS['ec_cart_data']->cart_data->is_guest = false;
				$GLOBALS['ec_cart_data']->cart_data->guest_key = '';
				$GLOBALS['ec_cart_data']->cart_data->username = sanitize_text_field( $_POST['ec_contact_first_name'] . ' ' . $_POST['ec_contact_last_name'] );
				$GLOBALS['ec_cart_data']->cart_data->first_name = ( isset( $_POST['ec_contact_first_name'] ) ) ? sanitize_text_field( $_POST['ec_contact_first_name'] ) : $first_name;
				$GLOBALS['ec_cart_data']->cart_data->last_name = ( isset( $_POST['ec_contact_last_name'] ) ) ? sanitize_text_field( $_POST['ec_contact_last_name'] ) : $last_name;
				$GLOBALS['ec_user'] = new ec_user( '' );
			}
		} else {
			$GLOBALS['ec_cart_data']->cart_data->user_id = '';
			$GLOBALS['ec_cart_data']->cart_data->is_guest = true;
			$GLOBALS['ec_cart_data']->cart_data->guest_key = sanitize_text_field( $GLOBALS['ec_cart_data']->ec_cart_id );
			$GLOBALS['ec_cart_data']->cart_data->first_name = $first_name;
			$GLOBALS['ec_cart_data']->cart_data->last_name = $last_name;
		}
	}
	$GLOBALS['ec_cart_data']->cart_data->email_other = sanitize_text_field( ( isset( $_POST['ec_email_other'] ) ) ? $_POST['ec_email_other'] : '' );

	$GLOBALS['ec_cart_data']->save_session_to_db();
	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );

	// Get cart and totals
	$cartpage = new ec_cartpage();
	$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
	$order_totals = ec_get_order_totals( $cart );

	if ( 'stripe' == get_option( 'ec_option_payment_process_method' ) || 'stripe_connect' == get_option( 'ec_option_payment_process_method' ) ) {
		if ( 'stripe' == get_option( 'ec_option_payment_process_method' ) ) {
			$stripe = new ec_stripe();
		} else {
			$stripe = new ec_stripe_connect();
		}
		$stripe->update_payment_intent_total( $GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id, $order_totals );
	}

	$displayItems = wpeasycart_get_cart_display_items( $cart, $order_totals, $order_totals->tax );

	$return_cart_data = ec_get_cart_data();

	if ( get_option( 'ec_option_onepage_checkout_tabbed' ) ) {
		ob_start();
		if ( get_option( 'ec_option_use_shipping' ) && $cartpage->shipping_address_allowed && ( $cartpage->cart->shippable_total_items > 0 || $cartpage->order_totals->handling_total > 0 || $cartpage->cart->excluded_shippable_total_items > 0 ) ) {
			if( $cartpage->page_allowed( 'shipping' ) ) {
				if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_shipping_v2.php' ) ) {
					include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_shipping_v2.php';
				} else {
					include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_shipping_v2.php';
				}
			} else {
				if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_information_v2.php' ) ) {
					include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_information_v2.php';
				} else {
					include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_information_v2.php';
				}
			}
		} else {
			if( $cartpage->page_allowed( 'payment' ) ) {
				if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_payment_v2.php' ) ) {
					include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_payment_v2.php';
				} else {
					include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_payment_v2.php';
				}
			} else {
				if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_information_v2.php' ) ) {
					include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_information_v2.php';
				} else {
					include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_information_v2.php';
				}
			}
		}
		$html_content = ob_get_clean();

		// Output new info
		$result = (object) array(
			'shipping_options' 	=> $cartpage->ec_cart_display_shipping_methods_stripe_dynamic( wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ), wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ) ),
			'display_items'		=> $displayItems,
			'total'				=> (int) round( ( $order_totals->grand_total * 100 ), 2 ),
			'cart_data'			=> $return_cart_data,
			'shipping_allowed'	=> ( ( $cartpage->page_allowed( 'shipping' ) ) ? 1 : 0 ),
			'payment_allowed'	=> ( ( $cartpage->page_allowed( 'payment' ) ) ? 1 : 0 ),
			'error'				=> $errors,
			'html_content'		=> $html_content,
		);
		echo json_encode( $result );
	} else {
		ob_start();
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_shipping_v2.php' ) ) {
			include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_shipping_v2.php';
		} else {
			include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_shipping_v2.php';
		}
		$shipping_html_content = ob_get_clean();

		ob_start();
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_shipping_v2.php' ) ) {
			include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_payment_v2.php';
		} else {
			include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_payment_v2.php';
		}
		$payment_html_content = ob_get_clean();

		// Output new info
		$result = (object) array(
			'shipping_options' 	=> $cartpage->ec_cart_display_shipping_methods_stripe_dynamic( wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ), wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ) ),
			'display_items'		=> $displayItems,
			'total'				=> (int) round( ( $order_totals->grand_total * 100 ), 2 ),
			'cart_data'			=> $return_cart_data,
			'shipping_allowed'	=> ( ( $cartpage->page_allowed( 'shipping' ) ) ? 1 : 0 ),
			'payment_allowed'	=> ( ( $cartpage->page_allowed( 'payment' ) ) ? 1 : 0 ),
			'error'				=> $errors,
			'shipping_content'	=> $shipping_html_content,
			'payment_content'	=> $payment_html_content,
		);
		echo json_encode( $result );
	}
	die();
}

add_action( 'wp_ajax_ec_ajax_update_contact_email', 'ec_ajax_update_contact_email' );
add_action( 'wp_ajax_nopriv_ec_ajax_update_contact_email', 'ec_ajax_update_contact_email' );
function ec_ajax_update_contact_email() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['wpeasycart_checkout_nonce'] ) ) {
		die();
	}

	if ( ! wp_verify_nonce( sanitize_text_field( $_POST['wpeasycart_checkout_nonce'] ), 'wp-easycart-save-checkout-info-' . $session_id ) ) {
		die();
	}

	$ec_db = new ec_db();
	if ( isset( $_POST['ec_create_account'] ) && '1' == $_POST['ec_create_account']  && $ec_db->does_user_exist( sanitize_email( $_POST['ec_contact_email'] ) ) ) {
		$GLOBALS['ec_cart_data']->cart_data->email = '';
		echo json_encode( array( 'error' => 'user_create_error' ) );
	} else {
		$GLOBALS['ec_cart_data']->cart_data->email = sanitize_text_field( $_POST['ec_contact_email'] );
		echo json_encode( array( 'success' => 1 ) );
	}
	$GLOBALS['ec_cart_data']->save_session_to_db();
	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );
	die();
}

add_action( 'wp_ajax_ec_ajax_cart_login_v2', 'ec_ajax_cart_login_v2' );
add_action( 'wp_ajax_nopriv_ec_ajax_cart_login_v2', 'ec_ajax_cart_login_v2' );
function ec_ajax_cart_login_v2() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['wpeasycart_nonce'] ) ) {
		die();
	}

	if ( ! wp_verify_nonce( sanitize_text_field( $_POST['wpeasycart_nonce'] ), 'wp-easycart-cart-login-' . $session_id ) ) {
		die();
	}
	
	$cartpage = new ec_cartpage();
	$result = $cartpage->process_login_user( false );
	echo json_encode( $result );
	die();
}

add_action( 'wp_ajax_ec_ajax_goto_page_v2', 'ec_ajax_goto_page_v2' );
add_action( 'wp_ajax_nopriv_ec_ajax_goto_page_v2', 'ec_ajax_goto_page_v2' );
function ec_ajax_goto_page_v2() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['wpeasycart_checkout_nonce'] ) ) {
		die();
	}

	if ( ! wp_verify_nonce( sanitize_text_field( $_POST['wpeasycart_checkout_nonce'] ), 'wp-easycart-goto-cart-page-' . $session_id ) ) {
		die();
	}
	
	// Get cart and totals
	$cartpage = new ec_cartpage();
	$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
	$order_totals = ec_get_order_totals( $cart );

	if ( 'stripe' == get_option( 'ec_option_payment_process_method' ) || 'stripe_connect' == get_option( 'ec_option_payment_process_method' ) ) {
		if ( 'stripe' == get_option( 'ec_option_payment_process_method' ) ) {
			$stripe = new ec_stripe();
		} else {
			$stripe = new ec_stripe_connect();
		}
		$stripe->update_payment_intent_total( $GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id, $order_totals );
	}

	$displayItems = wpeasycart_get_cart_display_items( $cart, $order_totals, $order_totals->tax );

	$return_cart_data = ec_get_cart_data();
	
	ob_start();
	if ( get_option( 'ec_option_onepage_checkout_tabbed' ) ) {
		if ( ( isset( $_POST['page'] ) && 'information' == $_POST['page'] ) || ( isset( $_POST['page'] ) && 'shipping' == $_POST['page'] && ! $cartpage->page_allowed( 'shipping' )  ) ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_information_v2.php' ) ) {
				include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_information_v2.php';
			} else {
				include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_information_v2.php';
			}
		} else if ( ( isset( $_POST['page'] ) && 'shipping' == $_POST['page'] && $cartpage->page_allowed( 'shipping' ) ) || ( isset( $_POST['page'] ) && 'payment' == $_POST['page'] && ! $cartpage->page_allowed( 'payment' ) ) ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_shipping_v2.php' ) ) {
				include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_shipping_v2.php';
			} else {
				include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_shipping_v2.php';
			}
		} else if ( isset( $_POST['page'] ) && 'payment' == $_POST['page'] && $cartpage->page_allowed( 'payment' ) ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_payment_v2.php' ) ) {
				include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_payment_v2.php';
			} else {
				include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_payment_v2.php';
			}
		} else {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_v2.php' ) ) {
				include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_v2.php';
			} else {
				include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_v2.php';
			}
		}
	} else {
		if ( isset( $_POST['page'] ) && 'cart' == $_POST['page'] ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_v2.php' ) ) {
				include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_v2.php';
			} else {
				include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_v2.php';
			}
		} else {
			echo '<div class="ec_cart_onepage" id="ec_cart_onepage_cart"></div>';
			echo '<div class="ec_cart_information" id="ec_cart_onepage_info">';
				if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_information_v2.php' ) ) {
					include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_information_v2.php' );
				} else {
					include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_information_v2.php' );
				}
			echo '</div>';
			if( get_option( 'ec_option_use_shipping' ) && $cartpage->shipping_address_allowed && ( $cartpage->cart->shippable_total_items > 0 || $cartpage->order_totals->handling_total > 0 || $cartpage->cart->excluded_shippable_total_items > 0 ) ) {
				echo '<div class="ec_cart_shipping" id="ec_cart_onepage_shipping">';
					if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_shipping_v2.php' ) ) {
						include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_shipping_v2.php' );
					} else {
						include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_shipping_v2.php' );
					}
				echo '</div>';
			}
			echo '<div class="ec_cart_payment" id="ec_cart_onepage_payment">';
				if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_payment_v2.php' ) ) {
					include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_payment_v2.php' );
				} else {
					include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_payment_v2.php' );
				}
			echo '</div>';
		}
	}
	$html_content = ob_get_clean();

	// Output new info
	$result = (object) array(
		'shipping_options' 	=> $cartpage->ec_cart_display_shipping_methods_stripe_dynamic( wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ),wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ) ),
		'display_items'		=> $displayItems,
		'total'				=> (int) round( ( $order_totals->grand_total * 100 ), 2 ),
		'cart_data'			=> $return_cart_data,
		'shipping_allowed'	=> ( ( $cartpage->page_allowed( 'shipping' ) ) ? 1 : 0 ),
		'payment_allowed'	=> ( ( $cartpage->page_allowed( 'payment' ) ) ? 1 : 0 ),
		'html_content'		=> $html_content,
	);
	echo json_encode( $result );
	die();
}

add_action( 'wp_ajax_ec_ajax_save_shipping_method', 'ec_ajax_save_shipping_method' );
add_action( 'wp_ajax_nopriv_ec_ajax_save_shipping_method', 'ec_ajax_save_shipping_method' );
function ec_ajax_save_shipping_method() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['wpeasycart_checkout_nonce'] ) ) {
		die();
	}

	if ( ! wp_verify_nonce( sanitize_text_field( $_POST['wpeasycart_checkout_nonce'] ), 'wp-easycart-save-shipping-method-' . $session_id ) ) {
		die();
	}
	
	$GLOBALS['ec_cart_data']->cart_data->shipping_method = sanitize_text_field( $_POST['shipping_method'] );
	$GLOBALS['ec_cart_data']->cart_data->expedited_shipping = (int) sanitize_text_field( $_POST['ship_express'] );
	$GLOBALS['ec_cart_data']->save_session_to_db();
	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );
	
	// Get cart and totals
	$cartpage = new ec_cartpage();
	$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
	$order_totals = ec_get_order_totals( $cart );

	if ( 'stripe' == get_option( 'ec_option_payment_process_method' ) || 'stripe_connect' == get_option( 'ec_option_payment_process_method' ) ) {
		if ( 'stripe' == get_option( 'ec_option_payment_process_method' ) ) {
			$stripe = new ec_stripe();
		} else {
			$stripe = new ec_stripe_connect();
		}
		$stripe->update_payment_intent_total( $GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id, $order_totals );
	}

	$displayItems = wpeasycart_get_cart_display_items( $cart, $order_totals, $order_totals->tax );

	$return_cart_data = ec_get_cart_data();
	
	ob_start();
	if ( $cartpage->page_allowed( 'payment' ) ) {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_payment_v2.php' ) ) {
			include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_payment_v2.php';
		} else {
			include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_payment_v2.php';
		}
	} else if ( $cartpage->page_allowed( 'shipping' ) ) {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_shipping_v2.php' ) ) {
			include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_shipping_v2.php';
		} else {
			include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_shipping_v2.php';
		}
	} else {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_information_v2.php' ) ) {
			include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_information_v2.php';
		} else {
			include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_information_v2.php';
		}
	}
	$html_content = ob_get_clean();

	// Output new info
	$result = (object) array(
		'shipping_options' 	=> $cartpage->ec_cart_display_shipping_methods_stripe_dynamic( wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ),wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ) ),
		'display_items'		=> $displayItems,
		'total'				=> (int) round( ( $order_totals->grand_total * 100 ), 2 ),
		'cart_data'			=> $return_cart_data,
		'shipping_allowed'	=> ( ( $cartpage->page_allowed( 'shipping' ) ) ? 1 : 0 ),
		'payment_allowed'	=> ( ( $cartpage->page_allowed( 'payment' ) ) ? 1 : 0 ),
		'html_content'		=> $html_content,
	);
	echo json_encode( $result );
	die();
}

add_action( 'wp_ajax_ec_ajax_update_billing_address_type', 'ec_ajax_update_billing_address_type' );
add_action( 'wp_ajax_nopriv_ec_ajax_update_billing_address_type', 'ec_ajax_update_billing_address_type' );
function ec_ajax_update_billing_address_type() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) ) {
		die();
	}

	if ( ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-update-billing-address-type-' . $session_id ) ) {
		die();
	}

	$GLOBALS['ec_cart_data']->cart_data->shipping_selector = ( isset( $_POST['billing_address_type'] ) && '1' == $_POST['billing_address_type'] ) ? 'true' : '';
	if ( 'true' == $GLOBALS['ec_cart_data']->cart_data->shipping_selector ) {
		if ( isset( $_POST['ec_billing_last_name'] ) && '' != $_POST['ec_billing_last_name'] ) {
			$first_name = sanitize_text_field( $_POST['ec_billing_name'] );
			$last_name = sanitize_text_field( $_POST['ec_billing_last_name'] );
		} else {
			$name = explode( ' ', sanitize_text_field( $_POST['ec_billing_name'] ) );
			$first_name = $last_name = '';
			if ( is_array( $name ) ) {
				$first_name = ( isset( $name[0] ) ) ? $name[0] : $_POST['ec_billing_name'];
				for ( $i = 1; $i < count( $name ); $i++ ) {
					if ( $i > 1 ) {
						$last_name .= ' ';
					}
					$last_name .= $name[ $i ];
				}
			}
		}
		$GLOBALS['ec_cart_data']->cart_data->billing_first_name = $first_name;
		$GLOBALS['ec_cart_data']->cart_data->billing_last_name = $last_name;
		$GLOBALS['ec_cart_data']->cart_data->billing_company_name = sanitize_text_field( $_POST['ec_billing_company_name'] );
		$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = sanitize_text_field( $_POST['ec_billing_address_line_1'] );
		$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = sanitize_text_field( $_POST['ec_billing_address_line_2'] );
		$GLOBALS['ec_cart_data']->cart_data->billing_city = sanitize_text_field( $_POST['ec_billing_city'] );
		$GLOBALS['ec_cart_data']->cart_data->billing_state = sanitize_text_field( $_POST['ec_billing_state'] );
		$GLOBALS['ec_cart_data']->cart_data->billing_zip = sanitize_text_field( $_POST['ec_billing_zip'] );
		$GLOBALS['ec_cart_data']->cart_data->billing_country = sanitize_text_field( $_POST['ec_billing_country'] );
		$GLOBALS['ec_cart_data']->cart_data->billing_phone = sanitize_text_field( $_POST['ec_billing_phone'] );
	} else {
		$GLOBALS['ec_cart_data']->cart_data->billing_first_name = $GLOBALS['ec_cart_data']->cart_data->shipping_first_name;
		$GLOBALS['ec_cart_data']->cart_data->billing_last_name = $GLOBALS['ec_cart_data']->cart_data->shipping_last_name;
		$GLOBALS['ec_cart_data']->cart_data->billing_company_name = $GLOBALS['ec_cart_data']->cart_data->shipping_company_name;
		$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1;
		$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2;
		$GLOBALS['ec_cart_data']->cart_data->billing_city = $GLOBALS['ec_cart_data']->cart_data->shipping_city;
		$GLOBALS['ec_cart_data']->cart_data->billing_state = $GLOBALS['ec_cart_data']->cart_data->shipping_state;
		$GLOBALS['ec_cart_data']->cart_data->billing_zip = $GLOBALS['ec_cart_data']->cart_data->shipping_zip;
		$GLOBALS['ec_cart_data']->cart_data->billing_country = $GLOBALS['ec_cart_data']->cart_data->shipping_country;
		$GLOBALS['ec_cart_data']->cart_data->billing_phone = $GLOBALS['ec_cart_data']->cart_data->shipping_phone;
	}
	$GLOBALS['ec_cart_data']->save_session_to_db();
	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );

	die();
}

add_action( 'wp_ajax_ec_ajax_get_stripe_express_shipping_dynamic', 'ec_ajax_get_stripe_express_shipping_dynamic' );
add_action( 'wp_ajax_nopriv_ec_ajax_get_stripe_express_shipping_dynamic', 'ec_ajax_get_stripe_express_shipping_dynamic' );
function ec_ajax_get_stripe_express_shipping_dynamic() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-get-stripe-shipping-dynamic-' . $session_id ) ) {
		die();
	}
	
	// Update Shipping
	$GLOBALS['ec_cart_data']->cart_data->shipping_selector = 'true';
	$GLOBALS['ec_cart_data']->cart_data->shipping_first_name = sanitize_text_field( $_POST['shippingAddress']['recipient'] );
	$GLOBALS['ec_cart_data']->cart_data->shipping_last_name = '';
	$GLOBALS['ec_cart_data']->cart_data->shipping_company_name = '';

	if ( isset( $_POST['shippingAddress']['addressLine'] ) && count( $_POST['shippingAddress']['addressLine'] ) > 0 ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = $GLOBALS['ec_user']->shipping->address_line_1 = sanitize_text_field( $_POST['shippingAddress']['addressLine'][0] );
	}
	if ( isset( $_POST['shippingAddress']['addressLine'] ) && count( $_POST['shippingAddress']['addressLine'] ) > 1 ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = $GLOBALS['ec_user']->shipping->address_line_2 = sanitize_text_field( $_POST['shippingAddress']['addressLine'][1] );
	}
	$GLOBALS['ec_cart_data']->cart_data->shipping_city = $GLOBALS['ec_user']->shipping->city = sanitize_text_field( $_POST['shippingAddress']['city'] );
	$GLOBALS['ec_cart_data']->cart_data->shipping_state = $GLOBALS['ec_user']->shipping->state = sanitize_text_field( $_POST['shippingAddress']['region'] );
	$GLOBALS['ec_cart_data']->cart_data->shipping_zip = $GLOBALS['ec_user']->shipping->zip = sanitize_text_field( $_POST['shippingAddress']['postalCode'] );
	$GLOBALS['ec_cart_data']->cart_data->shipping_country = $GLOBALS['ec_user']->shipping->country = sanitize_text_field( $_POST['shippingAddress']['country'] );
	$GLOBALS['ec_cart_data']->cart_data->shipping_phone = sanitize_text_field( $_POST['shippingAddress']['phone'] );
	$GLOBALS['ec_cart_data']->save_session_to_db();
	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );

	$cartpage = new ec_cartpage();
	$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
	$order_totals = ec_get_order_totals( $cart );

	if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
		$stripe = new ec_stripe();
	} else {
		$stripe = new ec_stripe_connect();
	}
	$stripe->update_payment_intent_total( $GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id, $order_totals );

	$return_cart_data = ec_get_cart_data();

	$result = (object) array(
		'shipping_rates' 	=> $cartpage->get_stripe_express_shipping_items( wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ),wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ) ),
		'line_items'		=> $cartpage->get_stripe_express_cart_items(),
		'total'				=> (int) round( ( $order_totals->grand_total * 100 ), 2 ),
		'cart_data'			=> $return_cart_data
	);
	echo json_encode( $result );
	die();
}

add_action( 'wp_ajax_ec_ajax_get_stripe_express_shipping_rate_dynamic', 'ec_ajax_get_stripe_express_shipping_rate_dynamic' );
add_action( 'wp_ajax_nopriv_ec_ajax_get_stripe_express_shipping_rate_dynamic', 'ec_ajax_get_stripe_express_shipping_rate_dynamic' );
function ec_ajax_get_stripe_express_shipping_rate_dynamic() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-get-stripe-shipping-dynamic-' . $session_id ) ) {
		die();
	}
	
	// Update Shipping
	$GLOBALS['ec_cart_data']->cart_data->shipping_method = sanitize_text_field( (int) $_POST['shippingRate'] );
	$GLOBALS['ec_cart_data']->save_session_to_db();
	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );

	$cartpage = new ec_cartpage();
	$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
	$order_totals = ec_get_order_totals( $cart );

	if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
		$stripe = new ec_stripe();
	} else {
		$stripe = new ec_stripe_connect();
	}
	$stripe->update_payment_intent_total( $GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id, $order_totals );

	$return_cart_data = ec_get_cart_data();

	$result = (object) array(
		'shipping_rates' 	=> $cartpage->get_stripe_express_shipping_items( wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ),wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ) ),
		'line_items'		=> $cartpage->get_stripe_express_cart_items(),
		'total'				=> (int) round( ( $order_totals->grand_total * 100 ), 2 ),
		'cart_data'			=> $return_cart_data
	);
	echo json_encode( $result );
	die();
}

add_action( 'wp_ajax_ec_ajax_get_stripe_shipping_dynamic', 'ec_ajax_get_stripe_shipping_dynamic' );
add_action( 'wp_ajax_nopriv_ec_ajax_get_stripe_shipping_dynamic', 'ec_ajax_get_stripe_shipping_dynamic' );
function ec_ajax_get_stripe_shipping_dynamic() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-get-stripe-shipping-dynamic-' . $session_id ) ) {
		die();
	}
	
	// Update Shipping
	$GLOBALS['ec_cart_data']->cart_data->shipping_selector = 'true';
	$GLOBALS['ec_cart_data']->cart_data->shipping_first_name = sanitize_text_field( $_POST['shippingAddress']['recipient'] );
	$GLOBALS['ec_cart_data']->cart_data->shipping_last_name = '';
	$GLOBALS['ec_cart_data']->cart_data->shipping_company_name = '';

	if ( isset( $_POST['shippingAddress']['addressLine'] ) && count( $_POST['shippingAddress']['addressLine'] ) > 0 ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = $GLOBALS['ec_user']->shipping->address_line_1 = sanitize_text_field( $_POST['shippingAddress']['addressLine'][0] );
	}
	if ( isset( $_POST['shippingAddress']['addressLine'] ) && count( $_POST['shippingAddress']['addressLine'] ) > 1 ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = $GLOBALS['ec_user']->shipping->address_line_2 = sanitize_text_field( $_POST['shippingAddress']['addressLine'][1] );
	}
	$GLOBALS['ec_cart_data']->cart_data->shipping_city = $GLOBALS['ec_user']->shipping->city = sanitize_text_field( $_POST['shippingAddress']['city'] );
	$GLOBALS['ec_cart_data']->cart_data->shipping_state = $GLOBALS['ec_user']->shipping->state = sanitize_text_field( $_POST['shippingAddress']['region'] );
	$GLOBALS['ec_cart_data']->cart_data->shipping_zip = $GLOBALS['ec_user']->shipping->zip = sanitize_text_field( $_POST['shippingAddress']['postalCode'] );
	$GLOBALS['ec_cart_data']->cart_data->shipping_country = $GLOBALS['ec_user']->shipping->country = sanitize_text_field( $_POST['shippingAddress']['country'] );
	$GLOBALS['ec_cart_data']->cart_data->shipping_phone = sanitize_text_field( $_POST['shippingAddress']['phone'] );
	$GLOBALS['ec_cart_data']->save_session_to_db();
	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );

	// Get cart and totals
	$cartpage = new ec_cartpage();
	$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
	$order_totals = ec_get_order_totals( $cart );

	if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
		$stripe = new ec_stripe();
	} else {
		$stripe = new ec_stripe_connect();
	}
	$stripe->update_payment_intent_total( $GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id, $order_totals );

	$displayItems = wpeasycart_get_cart_display_items( $cart, $order_totals, $order_totals->tax );

	$return_cart_data = ec_get_cart_data();

	// Output new info
	$result = (object) array(
		'shipping_options' 	=> $cartpage->ec_cart_display_shipping_methods_stripe_dynamic( wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ),wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ) ),
		'display_items'		=> $displayItems,
		'total'				=> (int) round( ( $order_totals->grand_total * 100 ), 2 ),
		'cart_data'			=> $return_cart_data
	);
	echo json_encode( $result );
	die();
}

add_action( 'wp_ajax_ec_ajax_get_stripe_shipping_option_dynamic', 'ec_ajax_get_stripe_shipping_option_dynamic' );
add_action( 'wp_ajax_nopriv_ec_ajax_get_stripe_shipping_option_dynamic', 'ec_ajax_get_stripe_shipping_option_dynamic' );
function ec_ajax_get_stripe_shipping_option_dynamic() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-get-stripe-shipping-option-dynamic-' . $session_id ) ) {
		die();
	}

	// Save Selected Method
	$GLOBALS['ec_cart_data']->cart_data->shipping_method = (int) $_POST['shippingOption']['id'];
	if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method == 'shipexpress' ) {
		$GLOBALS['ec_cart_data']->cart_data->expedited_shipping = 'shipexpress';
	} else {
		$GLOBALS['ec_cart_data']->cart_data->expedited_shipping = '';
	}
	$GLOBALS['ec_cart_data']->save_session_to_db();
	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );

	// Get cart and totals
	$cartpage = new ec_cartpage();
	$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
	$order_totals = ec_get_order_totals( $cart );

	if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
		$stripe = new ec_stripe();
	} else {
		$stripe = new ec_stripe_connect();
	}
	$stripe->update_payment_intent_total( $GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id, $order_totals );

	$displayItems = wpeasycart_get_cart_display_items( $cart, $order_totals, $order_totals->tax );

	$return_cart_data = ec_get_cart_data();

	// Output new info
	$result = (object) array(
		'shipping_options' 	=> $cartpage->ec_cart_display_shipping_methods_stripe_dynamic( wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ),wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ) ),
		'display_items'		=> $displayItems,
		'total'				=> (int) ( $order_totals->grand_total * 100 ),
		'cart_data'			=> $return_cart_data
	);
	echo json_encode( $result );
	die();
}

add_action( 'wp_ajax_ec_ajax_update_square_shipping_address_dynamic', 'ec_ajax_update_square_shipping_address_dynamic' );
add_action( 'wp_ajax_nopriv_ec_ajax_update_square_shipping_address_dynamic', 'ec_ajax_update_square_shipping_address_dynamic' );
function ec_ajax_update_square_shipping_address_dynamic() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-get-square-shipping-address-dynamic-' . $session_id ) ) {
		die();
	}

	// Update Shipping
	$GLOBALS['ec_cart_data']->cart_data->shipping_selector = 'true';
	if ( isset( $_POST['shippingAddress']['givenName'] ) ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_first_name = sanitize_text_field( $_POST['shippingAddress']['givenName'] );
	}
	if ( isset( $_POST['shippingAddress']['familyName'] ) ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_last_name = sanitize_text_field( $_POST['shippingAddress']['familyName'] );
	}
	$GLOBALS['ec_cart_data']->cart_data->shipping_company_name = '';

	if ( isset( $_POST['shippingAddress']['addressLines'] ) && count( $_POST['shippingAddress']['addressLines'] ) > 0 ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = sanitize_text_field( $_POST['shippingAddress']['addressLines'][0] );
	}
	if ( isset( $_POST['shippingAddress']['addressLines'] ) && count( $_POST['shippingAddress']['addressLines'] ) > 1 ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = sanitize_text_field( $_POST['shippingAddress']['addressLines'][1] );
	}
	if ( isset( $_POST['shippingAddress']['city'] ) ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_city = sanitize_text_field( $_POST['shippingAddress']['city'] );
	}
	if ( isset( $_POST['shippingAddress']['region'] ) ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_state = sanitize_text_field( $_POST['shippingAddress']['region'] );
	} else if ( isset( $_POST['shippingAddress']['state'] ) ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_state = sanitize_text_field( $_POST['shippingAddress']['state'] );
	}
	if ( isset( $_POST['shippingAddress']['postalCode'] ) ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_zip = sanitize_text_field( $_POST['shippingAddress']['postalCode'] );
	}
	if ( isset( $_POST['shippingAddress']['country'] ) ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_country = sanitize_text_field( $_POST['shippingAddress']['country'] );
	} else if ( isset( $_POST['shippingAddress']['countryCode'] ) ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_country = sanitize_text_field( $_POST['shippingAddress']['countryCode'] );
	}
	if ( isset( $_POST['shippingAddress']['phone'] ) ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_phone = sanitize_text_field( $_POST['shippingAddress']['phone'] );
	}
	$GLOBALS['ec_cart_data']->save_session_to_db();
	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );

	// Get cart and totals
	$cartpage = new ec_cartpage();
	$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
	$order_totals = ec_get_order_totals( $cart );
	$displayItems = wpeasycart_get_cart_display_items( $cart, $order_totals, $order_totals->tax );
	$return_cart_data = ec_get_cart_data();

	// Output new info
	$result = (object) array(
		'shipping_options' 	=> $cartpage->ec_cart_display_shipping_methods_square_dynamic( wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ),wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ) ),
		'display_items'		=> $cartpage->get_dynamic_square_line_items(),
		'total'				=> number_format( $order_totals->grand_total, 2, '.', '' ),
		'cart_data'			=> $return_cart_data
	);
	echo json_encode( $result );
	die();
}

add_action( 'wp_ajax_ec_ajax_update_square_shipping_option_dynamic', 'ec_ajax_update_square_shipping_option_dynamic' );
add_action( 'wp_ajax_nopriv_ec_ajax_update_square_shipping_option_dynamic', 'ec_ajax_update_square_shipping_option_dynamic' );
function ec_ajax_update_square_shipping_option_dynamic() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-get-square-shipping-option-dynamic-' . $session_id ) ) {
		die();
	}

	// Save Selected Method
	$GLOBALS['ec_cart_data']->cart_data->shipping_method = sanitize_text_field( $_POST['shippingAddress'] );
	if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method == 'shipexpress' ) {
		$GLOBALS['ec_cart_data']->cart_data->expedited_shipping = 'shipexpress';
	} else {
		$GLOBALS['ec_cart_data']->cart_data->expedited_shipping = '';
	}
	$GLOBALS['ec_cart_data']->save_session_to_db();
	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );

	// Get cart and totals
	$cartpage = new ec_cartpage();
	$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
	$order_totals = ec_get_order_totals( $cart );
	$return_cart_data = ec_get_cart_data();

	// Output new info
	$result = (object) array(
		'shipping_options' 	=> $cartpage->ec_cart_display_shipping_methods_square_dynamic( wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ),wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ) ),
		'display_items'		=> $cartpage->get_dynamic_square_line_items(),
		'total'				=> number_format( $order_totals->grand_total, 2, '.', '' ),
		'cart_data'			=> $return_cart_data
	);
	echo json_encode( $result );
	die();
}

add_action( 'wp_ajax_ec_ajax_square_complete_payment', 'ec_ajax_square_complete_payment' );
add_action( 'wp_ajax_nopriv_ec_ajax_square_complete_payment', 'ec_ajax_square_complete_payment' );
function ec_ajax_square_complete_payment() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['easycartnonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['easycartnonce'] ), 'wp-easycart-get-square-complete-payment-' . $session_id ) ) {
		die();
	}

	if ( isset( $_POST['shipping_address_first_name'] ) && '' != $_POST['shipping_address_first_name'] && 'undefined' != $_POST['shipping_address_first_name'] ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_first_name = sanitize_text_field( $_POST['shipping_address_first_name'] );
	}
	if ( isset( $_POST['shipping_address_last_name'] ) && '' != $_POST['shipping_address_last_name'] && 'undefined' != $_POST['shipping_address_last_name'] ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_last_name = sanitize_text_field( $_POST['shipping_address_last_name'] );
	}
	$GLOBALS['ec_cart_data']->cart_data->shipping_company_name = '';
	if ( isset( $_POST['shipping_address_line_1'] ) && '' != $_POST['shipping_address_line_1'] && 'undefined' != $_POST['shipping_address_line_1'] ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = sanitize_text_field( $_POST['shipping_address_line_1'] );
	}
	if ( isset( $_POST['shipping_address_line_2'] ) && '' != $_POST['shipping_address_line_2'] && 'undefined' != $_POST['shipping_address_line_2'] ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = sanitize_text_field( $_POST['shipping_address_line_2'] );
	}
	if ( isset( $_POST['shipping_address_city'] ) && '' != $_POST['shipping_address_city'] && 'undefined' != $_POST['shipping_address_city'] ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_city = sanitize_text_field( $_POST['shipping_address_city'] );
	}
	if ( isset( $_POST['shipping_address_region'] ) && '' != $_POST['shipping_address_region'] && 'undefined' != $_POST['shipping_address_region'] ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_state = sanitize_text_field( $_POST['shipping_address_region'] );
	} else if ( isset( $_POST['shipping_address_dependentLocality'] ) && '' != $_POST['shipping_address_dependentLocality'] && 'undefined' != $_POST['shipping_address_dependentLocality'] ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_state = sanitize_text_field( $_POST['shipping_address_dependentLocality'] );
	} else if ( isset( $_POST['shipping_address_state'] ) && '' != $_POST['shipping_address_state'] && 'undefined' != $_POST['shipping_address_state'] ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_state = sanitize_text_field( $_POST['shipping_address_state'] );
	}
	if ( isset( $_POST['shipping_address_zip'] ) && '' != $_POST['shipping_address_zip'] && 'undefined' != $_POST['shipping_address_zip'] ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_zip = sanitize_text_field( $_POST['shipping_address_zip'] );
	}
	if ( isset( $_POST['shipping_address_country'] ) && '' != $_POST['shipping_address_country'] && 'undefined' != $_POST['shipping_address_country'] ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_country = sanitize_text_field( $_POST['shipping_address_country'] );
	}
	if ( isset( $_POST['shipping_address_phone'] ) && '' != $_POST['shipping_address_phone'] && 'undefined' != $_POST['shipping_address_phone'] ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_phone = sanitize_text_field( $_POST['shipping_address_phone'] );
	}
	if ( ! $GLOBALS['ec_user']->user_id && isset( $_POST['shipping_address_email'] ) ) {
		$GLOBALS['ec_user']->email = sanitize_email( $_POST['shipping_address_email'] );
		$GLOBALS['ec_cart_data']->cart_data->email = sanitize_email( $_POST['shipping_address_email'] );
	} else if ( !$GLOBALS['ec_user']->user_id && isset( $_POST['billing_address_email'] ) ) {
		$GLOBALS['ec_user']->email = sanitize_email( $_POST['billing_address_email'] );
		$GLOBALS['ec_cart_data']->cart_data->email = sanitize_email( $_POST['billing_address_email'] );
	}
	if ( isset( $_POST['shipping_method'] ) ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_method = sanitize_text_field( $_POST['shipping_method'] );
	}
	if ( isset( $_POST['billing_address_first_name'] ) && '' != $_POST['billing_address_first_name'] && 'undefined' != $_POST['billing_address_first_name'] ) {
		$GLOBALS['ec_cart_data']->cart_data->billing_first_name = sanitize_text_field( $_POST['billing_address_first_name'] );
	}
	if ( isset( $_POST['billing_address_last_name'] ) && '' != $_POST['billing_address_last_name'] && 'undefined' != $_POST['billing_address_last_name'] ) {
		$GLOBALS['ec_cart_data']->cart_data->billing_last_name = sanitize_text_field( $_POST['billing_address_last_name'] );
	}
	$GLOBALS['ec_cart_data']->cart_data->billing_company_name = '';
	if ( isset( $_POST['billing_address_line_1'] ) && '' != $_POST['billing_address_line_1'] && 'undefined' != $_POST['billing_address_line_1'] ) {
		$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = sanitize_text_field( $_POST['billing_address_line_1'] );
	}
	if ( isset( $_POST['billing_address_line_2'] ) && '' != $_POST['billing_address_line_2'] && 'undefined' != $_POST['billing_address_line_2'] ) {
		$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = sanitize_text_field( $_POST['billing_address_line_2'] );
	}
	if ( isset( $_POST['billing_address_city'] ) && '' != $_POST['billing_address_city'] && 'undefined' != $_POST['billing_address_city'] ) {
		$GLOBALS['ec_cart_data']->cart_data->billing_city = sanitize_text_field( $_POST['billing_address_city'] );
	}
	if ( isset( $_POST['billing_address_region'] ) && '' != $_POST['billing_address_region'] && 'undefined' != $_POST['billing_address_region'] ) {
		$GLOBALS['ec_cart_data']->cart_data->billing_state = sanitize_text_field( $_POST['billing_address_region'] );
	} else if ( isset( $_POST['billing_address_dependentLocality'] ) && '' != $_POST['billing_address_dependentLocality'] && 'undefined' != $_POST['billing_address_dependentLocality'] ) {
		$GLOBALS['ec_cart_data']->cart_data->billing_state = sanitize_text_field( $_POST['billing_address_dependentLocality'] );
	} else if ( isset( $_POST['billing_address_state'] ) && '' != $_POST['billing_address_state'] && 'undefined' != $_POST['billing_address_state'] ) {
		$GLOBALS['ec_cart_data']->cart_data->billing_state = sanitize_text_field( $_POST['billing_address_state'] );
	}
	if ( isset( $_POST['billing_address_zip'] ) && '' != $_POST['billing_address_zip'] && 'undefined' != $_POST['billing_address_zip'] ) {
		$GLOBALS['ec_cart_data']->cart_data->billing_zip = sanitize_text_field( $_POST['billing_address_zip'] );
	}
	if ( isset( $_POST['billing_address_country'] ) && '' != $_POST['billing_address_country'] && 'undefined' != $_POST['billing_address_country'] ) {
		$GLOBALS['ec_cart_data']->cart_data->billing_country = sanitize_text_field( $_POST['billing_address_country'] );
	}
	if ( isset( $_POST['billing_address_phone'] ) && '' != $_POST['billing_address_phone'] && 'undefined' != $_POST['billing_address_phone'] ) {
		$GLOBALS['ec_cart_data']->cart_data->billing_phone = sanitize_text_field( $_POST['billing_address_phone'] );
	}

	$GLOBALS['ec_cart_data']->cart_data->first_name = sanitize_text_field( $GLOBALS['ec_cart_data']->cart_data->billing_first_name );
	$GLOBALS['ec_cart_data']->cart_data->last_name = sanitize_text_field( $GLOBALS['ec_cart_data']->cart_data->billing_last_name );

	if ( !$GLOBALS['ec_cart_data']->cart_data->user_id ) {
		$GLOBALS['ec_cart_data']->cart_data->is_guest = true;
		$GLOBALS['ec_cart_data']->cart_data->guest_key = sanitize_text_field( $GLOBALS['ec_cart_data']->ec_cart_id );
	} else {
		$GLOBALS['ec_cart_data']->cart_data->is_guest = false;
		$GLOBALS['ec_cart_data']->cart_data->guest_key = "";	
	}
	$GLOBALS['ec_cart_data']->save_session_to_db();

	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );

	$cartpage = new ec_cartpage();
	if ( ! $cartpage->validate_cart_shipping() ) {
		echo json_encode( (object) array(
			'error' => 'invalid_cart_shipping',
		) );
	} else {
		echo $cartpage->submit_square_quick_payment_v2();
	}
	die();
}

add_action( 'wp_ajax_ec_ajax_get_stripe_complete_payment', 'ec_ajax_get_stripe_complete_payment' );
add_action( 'wp_ajax_nopriv_ec_ajax_get_stripe_complete_payment', 'ec_ajax_get_stripe_complete_payment' );
function ec_ajax_get_stripe_complete_payment() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-get-stripe-complete-payment-' . $session_id ) ) {
		die();
	}

	$ec_db = new ec_db();
	if ( isset( $_POST['shipping_address'] ) ) {
		$shipping_name = sanitize_text_field( $_POST['shipping_address']['recipient'] );
		$shipping_names = explode( " ", $shipping_name );
		$GLOBALS['ec_cart_data']->cart_data->shipping_first_name = "";
		for ( $i=0; $i<count( $shipping_names ) - 1; $i++ ) {
			if ( $i > 0 )
				$GLOBALS['ec_cart_data']->cart_data->shipping_first_name .= ' ';
			$GLOBALS['ec_cart_data']->cart_data->shipping_first_name .= $shipping_names[$i];
		}
		$GLOBALS['ec_cart_data']->cart_data->shipping_last_name = ( count( $shipping_names ) > 1 ) ? $shipping_names[count( $shipping_names ) - 1] : '';
		$GLOBALS['ec_cart_data']->cart_data->shipping_company_name = sanitize_text_field( $_POST['shipping_address']['organization'] );
		if ( isset( $_POST['shipping_address']['addressLine'] ) ) {
			$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = ( count( $_POST['shipping_address']['addressLine'] ) > 0 ) ? sanitize_text_field( $_POST['shipping_address']['addressLine'][0] ) : '';
			$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = ( count( $_POST['shipping_address']['addressLine'] ) > 1 ) ? sanitize_text_field( $_POST['shipping_address']['addressLine'][1] ) : '';
		} else if ( isset( $_POST['shipping_address']['addressLines'] ) ) {
			$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = ( count( $_POST['shipping_address']['addressLines'] ) > 0 ) ? sanitize_text_field( $_POST['shipping_address']['addressLines'][0] ) : '';
			$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = ( count( $_POST['shipping_address']['addressLines'] ) > 1 ) ? sanitize_text_field( $_POST['shipping_address']['addressLines'][1] ) : '';
		} else if ( isset( $_POST['shipping_address']['line1'] ) ) {
			$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = sanitize_text_field( $_POST['shipping_address']['line1'] );
			$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = sanitize_text_field( $_POST['shipping_address']['line2'] );
		} else {
			$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = '';
			$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = '';
		}
		$GLOBALS['ec_cart_data']->cart_data->shipping_city = sanitize_text_field( $_POST['shipping_address']['city'] );
		if ( isset( $_POST['shipping_address']['region'] ) && $_POST['shipping_address']['region'] != '' ) {
			$GLOBALS['ec_cart_data']->cart_data->shipping_state = sanitize_text_field( $_POST['shipping_address']['region'] );

		} else if ( isset( $_POST['shipping_address']['dependentLocality'] ) && $_POST['shipping_address']['dependentLocality'] != '' ) {
			$GLOBALS['ec_cart_data']->cart_data->shipping_state = sanitize_text_field( $_POST['shipping_address']['dependentLocality'] );

		} else if ( isset( $_POST['shipping_address']['state'] ) && $_POST['shipping_address']['stat'] != '' ) {
			$GLOBALS['ec_cart_data']->cart_data->shipping_state = sanitize_text_field( $_POST['shipping_address']['state'] );

		} else {
			$GLOBALS['ec_cart_data']->cart_data->shipping_state = '';
		}
		if ( isset( $_POST['shipping_address']['postalCode'] ) && $_POST['shipping_address']['postalCode'] != '' ) {
			$GLOBALS['ec_cart_data']->cart_data->shipping_zip = sanitize_text_field( $_POST['shipping_address']['postalCode'] );

		} else if ( isset( $_POST['shipping_address']['postal_code'] ) && $_POST['shipping_address']['postal_code'] != '' ) {
			$GLOBALS['ec_cart_data']->cart_data->shipping_zip = sanitize_text_field( $_POST['shipping_address']['postal_code'] );

		} else {
			$GLOBALS['ec_cart_data']->cart_data->shipping_zip = '';

		}
		$GLOBALS['ec_cart_data']->cart_data->shipping_country = sanitize_text_field( $_POST['shipping_address']['country'] );
		$GLOBALS['ec_cart_data']->cart_data->shipping_phone = sanitize_text_field( $_POST['phone'] );
	}

	if ( isset( $_POST['shipping_method'] ) ) {
		$GLOBALS['ec_cart_data']->cart_data->shipping_method = sanitize_text_field( $_POST['shipping_method'] );
	}

	if ( isset( $_POST['billing_name'] ) && $_POST['billing_name'] != '' ) {
		$billing_name = sanitize_text_field( $_POST['billing_name'] );
		$billing_names = explode( " ", $billing_name );
		$GLOBALS['ec_cart_data']->cart_data->billing_first_name = "";
		for ( $i=0; $i<count( $billing_names ) - 1; $i++ ) {
			if ( $i > 0 )
				$GLOBALS['ec_cart_data']->cart_data->billing_first_name .= ' ';
			$GLOBALS['ec_cart_data']->cart_data->billing_first_name .= $billing_names[$i];
		}
		$GLOBALS['ec_cart_data']->cart_data->billing_last_name = ( count( $billing_names ) > 1 ) ? $billing_names[count( $billing_names ) - 1] : '';
		if ( isset( $_POST['billing_address']['addressLine'] ) ) {
			$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = ( count( $_POST['billing_address']['addressLine'] ) > 0 ) ? sanitize_text_field( $_POST['billing_address']['addressLine'][0] ) : '';
			$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = ( count( $_POST['billing_address']['addressLine'] ) > 1 ) ? sanitize_text_field( $_POST['billing_address']['addressLine'][1] ) : '';
		} else if ( isset( $_POST['billing_address']['addressLines'] ) ) {
			$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = ( count( $_POST['billing_address']['addressLines'] ) > 0 ) ? sanitize_text_field( $_POST['billing_address']['addressLines'][0] ) : '';
			$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = ( count( $_POST['billing_address']['addressLines'] ) > 1 ) ? sanitize_text_field( $_POST['billing_address']['addressLines'][1] ) : '';
		} else if ( isset( $_POST['billing_address']['line1'] ) ) {
			$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = sanitize_text_field( $_POST['billing_address']['line1'] );
			$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = sanitize_text_field( $_POST['billing_address']['line2'] );
		} else {
			$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1;
			$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2;
		}
		$GLOBALS['ec_cart_data']->cart_data->billing_city = ( isset( $_POST['billing_address']['city'] ) ) ? sanitize_text_field( $_POST['billing_address']['city'] ) : sanitize_text_field( $_POST['shipping_address']['city'] );
		if ( isset( $_POST['billing_address']['region'] ) && $_POST['billing_address']['region'] != '' ) {
			$GLOBALS['ec_cart_data']->cart_data->billing_state = sanitize_text_field( $_POST['billing_address']['region'] );

		} else if ( isset( $_POST['billing_address']['dependentLocality'] ) && $_POST['billing_address']['dependentLocality'] != '' ) {
			$GLOBALS['ec_cart_data']->cart_data->billing_state = sanitize_text_field( $_POST['billing_address']['dependentLocality'] );

		} else if ( isset( $_POST['billing_address']['state'] ) && $_POST['billing_address']['stat'] != '' ) {
			$GLOBALS['ec_cart_data']->cart_data->billing_state = sanitize_text_field( $_POST['billing_address']['state'] );

		} else {
			$GLOBALS['ec_cart_data']->cart_data->billing_state = sanitize_text_field( $GLOBALS['ec_cart_data']->cart_data->shipping_state );
		}
		if ( isset( $_POST['billing_address']['postalCode'] ) && $_POST['billing_address']['postalCode'] != '' ) {
			$GLOBALS['ec_cart_data']->cart_data->billing_zip = sanitize_text_field( $_POST['billing_address']['postalCode'] );

		} else if ( isset( $_POST['billing_address']['postal_code'] ) && $_POST['billing_address']['postal_code'] != '' ) {
			$GLOBALS['ec_cart_data']->cart_data->billing_zip = sanitize_text_field( $_POST['billing_address']['postal_code'] );

		} else {
			$GLOBALS['ec_cart_data']->cart_data->billing_zip = sanitize_text_field( $GLOBALS['ec_cart_data']->cart_data->shipping_zip );

		}
		$GLOBALS['ec_cart_data']->cart_data->billing_country = sanitize_text_field( $_POST['billing_address']['country'] );
		$GLOBALS['ec_cart_data']->cart_data->billing_phone = sanitize_text_field( $_POST['billing_phone'] );

	} else {
		$GLOBALS['ec_cart_data']->cart_data->billing_first_name = sanitize_text_field( $GLOBALS['ec_cart_data']->cart_data->shipping_first_name );
		$GLOBALS['ec_cart_data']->cart_data->billing_last_name = sanitize_text_field( $GLOBALS['ec_cart_data']->cart_data->shipping_last_name );
		$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = sanitize_text_field( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 );
		$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = sanitize_text_field( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 );
		$GLOBALS['ec_cart_data']->cart_data->billing_city = sanitize_text_field( $GLOBALS['ec_cart_data']->cart_data->shipping_city );
		$GLOBALS['ec_cart_data']->cart_data->billing_state = sanitize_text_field( $GLOBALS['ec_cart_data']->cart_data->shipping_state );
		$GLOBALS['ec_cart_data']->cart_data->billing_zip = sanitize_text_field( $GLOBALS['ec_cart_data']->cart_data->shipping_zip );
		$GLOBALS['ec_cart_data']->cart_data->billing_country = sanitize_text_field( $GLOBALS['ec_cart_data']->cart_data->shipping_country );
		$GLOBALS['ec_cart_data']->cart_data->billing_phone = sanitize_text_field( $GLOBALS['ec_cart_data']->cart_data->shipping_phone );

	}

	$GLOBALS['ec_cart_data']->cart_data->first_name = sanitize_text_field( $GLOBALS['ec_cart_data']->cart_data->billing_first_name );
	$GLOBALS['ec_cart_data']->cart_data->last_name = sanitize_text_field( $GLOBALS['ec_cart_data']->cart_data->billing_last_name );

	if ( !$GLOBALS['ec_user']->user_id && isset( $_POST['email'] ) ) {
		$GLOBALS['ec_user']->email = sanitize_email( $_POST['email'] );
		$GLOBALS['ec_cart_data']->cart_data->email = sanitize_email( $_POST['email'] );
	}

	if ( !$GLOBALS['ec_cart_data']->cart_data->user_id ) {
		$GLOBALS['ec_cart_data']->cart_data->is_guest = true;
		$GLOBALS['ec_cart_data']->cart_data->guest_key = sanitize_text_field( $GLOBALS['ec_cart_data']->ec_cart_id );
	} else {
		$GLOBALS['ec_cart_data']->cart_data->is_guest = false;
		$GLOBALS['ec_cart_data']->cart_data->guest_key = "";	
	}
	$payment_intent_id = $GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id;
	$GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id = "";
	$GLOBALS['ec_cart_data']->save_session_to_db();

	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );

	$cartpage = new ec_cartpage();
	if ( ! $cartpage->validate_cart_shipping() ) {
		echo esc_url_raw( apply_filters( 'wp_easycart_invalid_checkout_details_url', $cartpage->cart_page . $cartpage->permalink_divider . "ec_cart_error=invalid_cart_shipping" ) );
	} else {
		$goto_url = $cartpage->submit_stripe_quick_payment( 
			$payment_intent_id, 
			sanitize_text_field( $_POST['card_type'] ), 
			sanitize_text_field( $_POST['last_4'] ), 
			sanitize_text_field( $_POST['exp_month'] ), 
			sanitize_text_field( $_POST['exp_year'] )
		);
		echo esc_url_raw( $goto_url );
	}
	die();
}

add_action( 'wp_ajax_ec_ajax_get_stripe_complete_payment_main', 'ec_ajax_get_stripe_complete_payment_main' );
add_action( 'wp_ajax_nopriv_ec_ajax_get_stripe_complete_payment_main', 'ec_ajax_get_stripe_complete_payment_main' );
function ec_ajax_get_stripe_complete_payment_main() {
	global $wpdb;
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-get-stripe-complete-payment-main-' . $session_id ) ) {
		die();
	}

	// Get Payment Intent Info
	if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
		$stripe = new ec_stripe();
	} else {
		$stripe = new ec_stripe_connect();
	}
	$payment_intent = $stripe->get_payment_intent( $GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id );
	$order = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ec_order WHERE gateway_transaction_id = %s", $payment_intent->id . ':' . $payment_intent->client_secret ) );
	if ( $order ) { /* Verify Order Doesn't Already Exist! */
		$cart_page_id = get_option( 'ec_option_cartpage' );
		if ( function_exists( 'icl_object_id' ) ) {
			$cart_page_id = icl_object_id( $cart_page_id, 'page', true, ICL_LANGUAGE_CODE );
		}
		$cart_page = get_permalink( $cart_page_id );
		if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
			$https_class = new WordPressHTTPS();
			$cart_page = $https_class->makeUrlHttps( $cart_page );
		}
		if ( substr_count( $cart_page, '?' ) ) {
			$permalink_divider = "&";
		} else {
			$permalink_divider = "?";
		}
		echo esc_url_raw( $cart_page . $permalink_divider . "ec_page=checkout_success&order_id=" . $order->order_id );
		die();
	}

	$charge = $stripe->get_charge( $payment_intent->latest_charge );
	$payment_method = $last_4 = $exp_month = $exp_year = '';
	if ( $charge && isset( $charge->payment_method_details ) && isset( $charge->payment_method_details->type ) ) {
		if ( 'ach_debit' == $charge->payment_method_details->type ) { 
			$payment_method = $charge->payment_method_details->ach_debit->bank_name;
			$last_4 = $charge->payment_method_details->ach_debit->last4;
			$exp_month = '';
			$exp_year = '';

		} else if ( 'acss_debit' == $charge->payment_method_details->type ) { 
			$payment_method = $charge->payment_method_details->acss_debit->bank_name;
			$last_4 = $charge->payment_method_details->acss_debit->last4;
			$exp_month = '';
			$exp_year = '';

		} else if ( 'affirm' == $charge->payment_method_details->type ) { 
			$payment_method = 'Affirm';
			$last_4 = '';
			$exp_month = '';
			$exp_year = '';

		} else if ( 'afterpay_clearpay' == $charge->payment_method_details->type ) { 
			$payment_method = 'Afterpay / Clearpay';
			$last_4 = '';
			$exp_month = '';
			$exp_year = '';

		} else if ( 'alipay' == $charge->payment_method_details->type ) { 
			$payment_method = 'Alipay';
			$last_4 = $charge->payment_method_details->alipay->transaction_id;
			$exp_month = '';
			$exp_year = '';

		} else if ( 'au_becs_debit' == $charge->payment_method_details->type ) { 
			$payment_method = 'BECS';
			$last_4 = $charge->payment_method_details->au_becs_debit->last4;
			$exp_month = '';
			$exp_year = '';

		} else if ( 'bancontact' == $charge->payment_method_details->type ) { 
			$payment_method = 'Bancontact';
			$last_4 = $charge->payment_method_details->bancontact->bacs_debit;
			$exp_month = '';
			$exp_year = '';

		} else if ( 'bacs_debit' == $charge->payment_method_details->type ) { 
			$payment_method = 'BACS';
			$last_4 = $charge->payment_method_details->bacs_debit->last4;
			$exp_month = '';
			$exp_year = '';

		} else if ( 'blik' == $charge->payment_method_details->type ) { 
			$payment_method = 'BLIK';
			$last_4 = '';
			$exp_month = '';
			$exp_year = '';

		} else if ( 'boleto' == $charge->payment_method_details->type ) { 
			$payment_method = 'Boleto';
			$last_4 = '';
			$exp_month = '';
			$exp_year = '';

		} else if ( 'eps' == $charge->payment_method_details->type ) { 
			$payment_method = 'EPS';
			$last_4 = '';
			$exp_month = '';
			$exp_year = '';

		} else if ( 'fpx' == $charge->payment_method_details->type ) { 
			$payment_method = 'FPX';
			$last_4 = '';
			$exp_month = '';
			$exp_year = '';

		} else if ( 'giropay' == $charge->payment_method_details->type ) { 
			$payment_method = 'Giropay';
			$last_4 = '';
			$exp_month = '';
			$exp_year = '';

		} else if ( 'grabpay' == $charge->payment_method_details->type ) { 
			$payment_method = 'Grabpay';
			$last_4 = '';
			$exp_month = '';
			$exp_year = '';

		} else if ( 'ideal' == $charge->payment_method_details->type ) { 
			$payment_method = 'iDeal';
			$last_4 = $charge->payment_method_details->ideal->iban_last4;
			$exp_month = '';
			$exp_year = '';

		} else if ( 'klarna' == $charge->payment_method_details->type ) { 
			$payment_method = 'Klarna';
			$last_4 = '';
			$exp_month = '';
			$exp_year = '';

		} else if ( 'kobini' == $charge->payment_method_details->type ) { 
			$payment_method = 'Kobini';
			$last_4 = '';
			$exp_month = '';
			$exp_year = '';

		} else if ( 'link' == $charge->payment_method_details->type ) { 
			$payment_method = 'Link';
			$last_4 = '';
			$exp_month = '';
			$exp_year = '';

		} else if ( 'multibanco' == $charge->payment_method_details->type ) { 
			$payment_method = 'Multibanco';
			$last_4 = '';
			$exp_month = '';
			$exp_year = '';

		} else if ( 'oxxo' == $charge->payment_method_details->type ) { 
			$payment_method = 'OXXO';
			$last_4 = '';
			$exp_month = '';
			$exp_year = '';

		} else if ( 'p24' == $charge->payment_method_details->type ) { 
			$payment_method = 'P24';
			$last_4 = '';
			$exp_month = '';
			$exp_year = '';

		} else if ( 'paynow' == $charge->payment_method_details->type ) { 
			$payment_method = 'Paynow';
			$last_4 = '';
			$exp_month = '';
			$exp_year = '';

		} else if ( 'pix' == $charge->payment_method_details->type ) { 
			$payment_method = 'Pix';
			$last_4 = '';
			$exp_month = '';
			$exp_year = '';

		} else if ( 'promptpay' == $charge->payment_method_details->type ) { 
			$payment_method = 'Promptpay';
			$last_4 = '';
			$exp_month = '';
			$exp_year = '';

		} else if ( 'sepa_debit' == $charge->payment_method_details->type ) { 
			$payment_method = 'SEPA';
			$last_4 = $charge->payment_method_details->sepa_debit->last4;
			$exp_month = '';
			$exp_year = '';

		} else if ( 'sofort' == $charge->payment_method_details->type ) { 
			$payment_method = 'Sofort';
			$last_4 = $charge->payment_method_details->sofort->iban_last4;
			$exp_month = '';
			$exp_year = '';

		} else if ( 'wechat' == $charge->payment_method_details->type ) { 
			$payment_method = 'WeChat';
			$last_4 = '';
			$exp_month = '';
			$exp_year = '';

		} else if ( 'wechat_pay' == $charge->payment_method_details->type ) { 
			$payment_method = 'WeChat';
			$last_4 = '';
			$exp_month = '';
			$exp_year = '';

		} else {
			$payment_method = $charge->payment_method_details->card->brand;
			$last_4 = $charge->payment_method_details->card->last4;
			$exp_month = $charge->payment_method_details->card->exp_month;
			$exp_year = $charge->payment_method_details->card->exp_year;
		}
	}

	// Create the Stripe Order Dynamically
	$cartpage = new ec_cartpage();
	if ( ! $cartpage->validate_cart_shipping() ) {
		echo esc_url_raw( apply_filters( 'wp_easycart_invalid_checkout_details_url', $cartpage->cart_page . $cartpage->permalink_divider . "ec_cart_error=invalid_cart_shipping" ) );
	} else {
		$goto_url = $cartpage->submit_stripe_quick_payment( $GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id, $payment_method, $last_4, $exp_month, $exp_year );
		echo esc_url_raw( $goto_url );
	}
	die();
}

add_action( 'wp_ajax_ec_ajax_get_stripe_complete_payment_invoice', 'ec_ajax_get_stripe_complete_payment_invoice' );
add_action( 'wp_ajax_nopriv_ec_ajax_get_stripe_complete_payment_invoice', 'ec_ajax_get_stripe_complete_payment_invoice' );
function ec_ajax_get_stripe_complete_payment_invoice() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-get-stripe-complete-payment-invoice-' . $session_id ) ) {
		die();
	}

	// Get Payment Intent Info
	if ( 'stripe' == get_option( 'ec_option_payment_process_method' ) ) {
		$stripe = new ec_stripe();
	} else {
		$stripe = new ec_stripe_connect();
	}
	$payment_intent = $stripe->get_payment_intent( $GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id );

	// Get data we want to keep
	$card_type = $payment_intent->charges->data[0]->payment_method_details->card->brand;
	$last_4 = $payment_intent->charges->data[0]->payment_method_details->card->last4;
	$exp_month = $payment_intent->charges->data[0]->payment_method_details->card->exp_month;
	$exp_year = $payment_intent->charges->data[0]->payment_method_details->card->exp_year;

	// Create the Stripe Order Dynamically
	$cartpage = new ec_cartpage();
	$goto_url = $cartpage->submit_stripe_invoice_payment( $GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id, $card_type, $last_4, $exp_month, $exp_year );

	echo esc_url_raw( $goto_url );
	die();
}

add_action( 'wp_ajax_ec_ajax_get_stripe_complete_payment_subscription', 'ec_ajax_get_stripe_complete_payment_subscription' );
add_action( 'wp_ajax_nopriv_ec_ajax_get_stripe_complete_payment_subscription', 'ec_ajax_get_stripe_complete_payment_subscription' );
function ec_ajax_get_stripe_complete_payment_subscription() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-get-stripe-complete-payment-subscription-' . $session_id ) ) {
		die();
	}

	// Get Payment Intent Info
	if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
		$stripe = new ec_stripe();
	} else {
		$stripe = new ec_stripe_connect();
	}

	// Create the Order Dynamically
	$cartpage = new ec_cartpage();
	$goto_url = $cartpage->submit_stripe_quick_subscription_payment( $GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id );

	echo esc_url_raw( $goto_url );
	die();
}

add_action( 'wp_ajax_ec_ajax_get_stripe_create_subscription', 'ec_ajax_get_stripe_create_subscription' );
add_action( 'wp_ajax_nopriv_ec_ajax_get_stripe_create_subscription', 'ec_ajax_get_stripe_create_subscription' );
function ec_ajax_get_stripe_create_subscription() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-get-stripe-create-subscription-' . $session_id ) ) {
		die();
	}

	$cartpage = new ec_cartpage();
	$response = $cartpage->submit_stripe_quick_subscription( $GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id );//, $card_type, $last_4, $exp_month, $exp_year );
	if ( ! $response ) {
		echo json_encode( array( 'status' => 'error' ) );
	} else {
		echo json_encode( $response );
	}
	die();
}

add_action( 'wp_ajax_ec_ajax_get_stripe_update_customer_card', 'ec_ajax_get_stripe_update_customer_card' );
add_action( 'wp_ajax_nopriv_ec_ajax_get_stripe_update_customer_card', 'ec_ajax_get_stripe_update_customer_card' );
function ec_ajax_get_stripe_update_customer_card() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-get-stripe-update-customer-card-' . $session_id ) ) {
		die();
	}

	global $wpdb;
	$ec_db = new ec_db();
	$payment_method = get_option( 'ec_option_payment_process_method' );

	// Get Payment Intent Info
	if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
		$stripe = new ec_stripe();
	} else {
		$stripe = new ec_stripe_connect();
	}

	$subscription = $ec_db->get_subscription_row( (int) $_POST['subscription_id'] );
	$subscription_info = $stripe->get_subscription( $GLOBALS['ec_user']->stripe_customer_id, $subscription->stripe_subscription_id );
	if ( $subscription_info ) {
		$card_info = $stripe->attach_payment_method( sanitize_text_field( $_POST['payment_id'] ), $GLOBALS['ec_user'] );
		$update_response = $stripe->set_subscription_payment_method( sanitize_text_field( $_POST['payment_id'] ), $subscription_info, $subscription, (int) $_POST['quantity'] );
		if ( $update_response ) {
			$wpdb->query( $wpdb->prepare( "UPDATE ec_subscription SET quantity = %d WHERE subscription_id = %d", (int) $_POST['quantity'], $subscription->subscription_id ) );
			$card = new ec_credit_card( $card_info->card->brand, $card_info->card->name, $card_info->card->last4, $card_info->card->exp_month, $card_info->card->exp_year, '' );
			$ec_db->update_user_default_card( $GLOBALS['ec_user'], $card );
		}
	}

	// Update Plan if Changed
	$products = $ec_db->get_product_list( $wpdb->prepare( " WHERE product.product_id = %d", sanitize_text_field( $_POST['ec_selected_plan'] ) ), "", "", "" );
	if ( count( $products ) > 0 ) {
		$product = new ec_product( $products[0] );
		$plan_added = $product->stripe_plan_added;
		if ( $payment_method == "stripe" || $payment_method == "stripe_connect" ) {
			if ( $payment_method == "stripe" )
				$stripe = new ec_stripe();
			else
				$stripe = new ec_stripe_connect();

			if ( !$product->stripe_plan_added ) {
				$plan_added = $stripe->insert_plan( $product );
				$ec_db->update_product_stripe_added( $product->product_id );
			}

			if ( $plan_added ) {
				$success = $stripe->update_subscription( $product, $GLOBALS['ec_user'], NULL, sanitize_text_field( $_POST['stripe_subscription_id'] ), NULL, $product->subscription_prorate, NULL, (int) $_POST['quantity'] );
				if ( $success ) {
					$ec_db->update_subscription( (int) $_POST['subscription_id'], $GLOBALS['ec_user'], $product, $card, (int) $_POST['quantity'] );
					$ec_db->update_user_default_card( $GLOBALS['ec_user'], $card );
				}
			}
		}
	}

	// Return the Redirect URL
	$account_page_id = get_option('ec_option_accountpage');
	if ( function_exists( 'icl_object_id' ) ) {
		$account_page_id = icl_object_id( $account_page_id, 'page', true, ICL_LANGUAGE_CODE );
	}
	$account_page = get_permalink( $account_page_id );
	if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
		$https_class = new WordPressHTTPS();
		$account_page = $https_class->makeUrlHttps( $account_page );
	}
	if ( substr_count( $account_page, '?' ) )					$permalink_divider = "&";
	else														$permalink_divider = "?";

	echo json_encode( 
		array( 
			'url' => $account_page . $permalink_divider . "ec_page=subscription_details&subscription_id=" . (int) $_POST['subscription_id']
		)
	);
	die();
}

function wpeasycart_get_cart_display_items( $cart, $order_totals, $tax ) {
	$displayItems = array(
		(object) array(
			"pending" 	=> (bool) 1,
			"label"		=> 'Subtotal',
			"amount"	=> (int) round( ( $order_totals->get_converted_sub_total() * 100 ), 2 )
		)
	);
	if ( $order_totals->tax_total > 0 ) {
		$displayItems[] = (object) array(
			"pending" 	=> (bool) 1,
			"label" 	=> wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_tax' ),
			"amount" 	=> (int) round( ( $order_totals->tax_total * 100 ), 2 )
		);
	}
	if ( $order_totals->shipping_total > 0 ) {
		$displayItems[] = (object) array(
			"pending" 	=> (bool) 1,
			"label" 	=> wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_shipping' ),
			"amount" 	=> (int) round( ( $order_totals->shipping_total * 100 ), 2 )
		);
	}
	if ( $order_totals->discount_total != 0 ) {
		$displayItems[] = (object) array(
			"pending" 	=> (bool) 1,
			"label" 	=> wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_discounts' ),
			"amount" 	=> (int) round( ( $order_totals->discount_total * 100 ), 2 )
		);
	}
	if ( $tax->is_duty_enabled() ) {
		$displayItems[] = (object) array(
			"pending" 	=> (bool) 1,
			"label" 	=> wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_duty' ),
			"amount" 	=> (int) round( ( $order_totals->duty_total * 100 ), 2 )
		);
	}
	if ( $tax->is_vat_enabled() && $tax->vat_total > 0 ) { 
		$displayItems[] = (object) array(
			"pending" 	=> (bool) 1,
			"label" 	=> wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_vat' ),
			"amount" 	=> (int) round( ( $tax->vat_total * 100 ), 2 )
		);
	}
	if ( get_option( 'ec_option_enable_easy_canada_tax' ) && $order_totals->gst_total > 0 ) {
		$displayItems[] = (object) array(
			"pending" 	=> (bool) 1,
			"label" 	=> 'GST (' . $tax->gst_rate . '%)',
			"amount" 	=> (int) round( ( $order_totals->gst_total * 100 ), 2 )
		);
	}
	if ( get_option( 'ec_option_enable_easy_canada_tax' ) && $order_totals->pst_total > 0 ) {
		$displayItems[] = (object) array(
			"pending" 	=> (bool) 1,
			"label" 	=> 'PST (' . $tax->pst_rate . '%)',
			"amount" 	=> (int) round( ( $order_totals->pst_total * 100 ), 2 )
		);
	}
	if ( get_option( 'ec_option_enable_easy_canada_tax' ) && $order_totals->hst_total > 0 ) {
		$displayItems[] = (object) array(
			"pending" 	=> (bool) 1,
			"label" 	=> 'HST (' . $tax->hst_rate . '%)',
			"amount" 	=> (int) round( ( $order_totals->hst_total * 100 ), 2 )
		);
	}
	return $displayItems;
}

add_action( 'wp_ajax_ec_ajax_redeem_coupon_code', 'ec_ajax_redeem_coupon_code' );
add_action( 'wp_ajax_nopriv_ec_ajax_redeem_coupon_code', 'ec_ajax_redeem_coupon_code' );
function ec_ajax_redeem_coupon_code() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-redeem-coupon-code-' . $session_id ) ) {
		die();
	}

	//UPDATE COUPON CODE
	$coupon_code = "";
	if ( isset( $_POST['couponcode'] ) ) {
		$coupon_code = trim( sanitize_text_field( $_POST['couponcode'] ) );
		$GLOBALS['ec_cart_data']->cart_data->coupon_code = $coupon_code;
	}

	$GLOBALS['ec_cart_data']->save_session_to_db();
	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );

	$coupon = $GLOBALS['ec_coupons']->redeem_coupon_code( $coupon_code );
	if ( isset( $_POST['ec_v3_24'] ) ) {
		$return_array = ec_get_cart_data();
		if ( $coupon ) {
			if ( $coupon && !$coupon->coupon_expired && ( $coupon->max_redemptions == 999 || $coupon->times_redeemed < $coupon->max_redemptions ) ) {
				$return_array['coupon_message'] = $coupon->message;
				$return_array['is_coupon_valid'] = true;
				$GLOBALS['ec_cart_data']->cart_data->coupon_code = $coupon_code;
				$cartpage = new ec_cartpage();
				if ( $cartpage->discount->coupon_matches <= 0 ) {
					$return_array['coupon_message'] = wp_easycart_language()->get_text( 'cart_coupons', 'coupon_not_applicable' );
				}

			} else if ( $coupon && $coupon->times_redeemed >= $coupon->max_redemptions ) {
				$return_array['coupon_message'] = wp_easycart_language()->get_text( 'cart_coupons', 'cart_max_exceeded_coupon' );
				$return_array['is_coupon_valid'] = false;
				$GLOBALS['ec_cart_data']->cart_data->coupon_code = "";

			} else if ( $coupon->coupon_expired ) {
				$return_array['coupon_message'] = wp_easycart_language()->get_text( 'cart_coupons', 'cart_coupon_expired' );
				$return_array['is_coupon_valid'] = false;
				$GLOBALS['ec_cart_data']->cart_data->coupon_code;

			} else {
				$return_array['coupon_message'] = wp_easycart_language()->get_text( 'cart_coupons', 'cart_invalid_coupon' );
				$return_array['is_coupon_valid'] = false;
				$GLOBALS['ec_cart_data']->cart_data->coupon_code;
			}
		} else {
			$return_array['coupon_message'] = wp_easycart_language()->get_text( 'cart_coupons', 'cart_invalid_coupon' );
			$return_array['is_coupon_valid'] = false;
			$GLOBALS['ec_cart_data']->cart_data->coupon_code;
		}

		echo json_encode( $return_array );
	} else {
		// UPDATE COUPON CODE
		$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
		$order_totals = ec_get_order_totals( $cart );

		echo esc_attr( $cart->total_items ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->sub_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->tax_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->shipping_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( (-1) * $order_totals->discount_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->duty_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->vat_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->grand_total ) );

		if ( $coupon ) {
			if ( $coupon && !$coupon->coupon_expired && ( $coupon->max_redemptions == 999 || $coupon->times_redeemed < $coupon->max_redemptions ) ) {
				$GLOBALS['ec_cart_data']->cart_data->coupon_code = $coupon_code;
				$cartpage = new ec_cartpage();
				if ( $cartpage->discount->coupon_matches <= 0 ) {
					echo '***' . wp_easycart_language()->get_text( 'cart_coupons', 'coupon_not_applicable' ) . '***' . "valid";
				} else {
					echo '***' . esc_attr( $coupon->message ) . '***' . "valid";
				}

			} else if ( $coupon && $coupon->times_redeemed >= $coupon->max_redemptions ) {
				echo '***' . wp_easycart_language()->get_text( 'cart_coupons', 'cart_max_exceeded_coupon' ) . '***' . "invalid";
				$GLOBALS['ec_cart_data']->cart_data->coupon_code = "";

			} else if ( $coupon->coupon_expired ) {
				echo '***' . wp_easycart_language()->get_text( 'cart_coupons', 'cart_coupon_expired' ) . '***' . "invalid";
				esc_attr( $GLOBALS['ec_cart_data']->cart_data->coupon_code );

			} else {
				echo '***' . wp_easycart_language()->get_text( 'cart_coupons', 'cart_invalid_coupon' ) . '***' . "invalid";
				esc_attr( $GLOBALS['ec_cart_data']->cart_data->coupon_code );
			}
		} else {
			echo '***' . wp_easycart_language()->get_text( 'cart_coupons', 'cart_invalid_coupon' ) . '***' . "invalid";
			esc_attr( $GLOBALS['ec_cart_data']->cart_data->coupon_code );
		}

		if ( $order_totals->discount_total == 0 )
			echo "***0";
		else
			echo "***1";
	}
	die();

}

add_action( 'wp_ajax_ec_ajax_redeem_subscription_coupon_code', 'ec_ajax_redeem_subscription_coupon_code' );
add_action( 'wp_ajax_nopriv_ec_ajax_redeem_subscription_coupon_code', 'ec_ajax_redeem_subscription_coupon_code' );
function ec_ajax_redeem_subscription_coupon_code() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-redeem-subscription-coupon-code-' . $session_id ) ) {
		die();
	}

	global $wpdb;

	// Get Coupon Code Info
	$product_id = "";
	$manufacturer_id = "";
	$coupon_code = "";
	if ( isset( $_POST['couponcode'] ) ) {
		$coupon_code = trim( sanitize_text_field( $_POST['couponcode'] ) );
	}
	if ( isset( $_POST['product_id'] ) ) {
		$product_id = (int) $_POST['product_id'];
	}
	if ( isset( $_POST['manufacturer_id'] ) ) {
		$manufacturer_id = (int) $_POST['manufacturer_id'];
	}

	// Get the Coupon and Check Validity
	$GLOBALS['ec_cart_data']->cart_data->coupon_code = $coupon_code;
	$coupon = $GLOBALS['ec_coupons']->redeem_coupon_code( $coupon_code );
	$coupon_code_invalid = true;
	$coupon_applicable = true;
	$coupon_exceeded_redemptions = false;
	$coupon_expired = false;

	if ( !$coupon ) { // Invalid Coupon
		$coupon_code_invalid = false;
	} else if ( $coupon->by_product_id && $coupon->product_id != $product_id ) { // Product does not match
		$coupon_applicable = false;
	} else if ( $coupon->by_manufacturer_id && $coupon->manufacturer_id != $manufacturer_id ) { // Manufacturer Does not Match
		$coupon_applicable = false;
	} else if ( $coupon->by_category_id ) { // validate category id match
		$has_categories = $wpdb->get_results( $wpdb->prepare( "SELECT categoryitem_id FROM ec_categoryitem WHERE category_id = %d AND product_id = %d", $coupon->category_id, $product_id ) );
		if ( !$has_categories ) {
			$coupon_applicable = false;
		}
	} else if ( $coupon->max_redemptions != 999 && $coupon->times_redeemed >= $coupon->max_redemptions ) {
		$coupon_exceeded_redemptions = true;
	} else if ( $coupon->coupon_expired ) {
		$coupon_expired = true;
	}

	// If valid and applicable, set to cache.
	if ( '' != $coupon_code && $coupon_applicable && !$coupon_exceeded_redemptions && !$coupon_expired ) {
		$GLOBALS['ec_cart_data']->cart_data->coupon_code = $coupon_code;
	} else {
		$GLOBALS['ec_cart_data']->cart_data->coupon_code ='';
	}

	$GLOBALS['ec_cart_data']->save_session_to_db();
	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );

	wp_easycart_subscription_output_ajax_totals();
	die();
}

add_action( 'wp_ajax_ec_ajax_redeem_gift_card', 'ec_ajax_redeem_gift_card' );
add_action( 'wp_ajax_nopriv_ec_ajax_redeem_gift_card', 'ec_ajax_redeem_gift_card' );
function ec_ajax_redeem_gift_card() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-redeem-gift-card-' . $session_id ) ) {
		die();
	}

	// UPDATE GIFT CARD
	$gift_card = "";
	if ( isset( $_POST['giftcard'] ) )
		$gift_card = trim( sanitize_text_field( $_POST['giftcard'] ) );

	$GLOBALS['ec_cart_data']->cart_data->giftcard = $gift_card;

	$GLOBALS['ec_cart_data']->save_session_to_db();
	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );

	$db = new ec_db();
	$giftcard = $db->redeem_gift_card( $gift_card );

	if ( isset( $_POST['ec_v3_24'] ) ) {
		$return_array = ec_get_cart_data();
		if ( $giftcard ) {
			$return_array['giftcard_message'] = $giftcard->message;
			$return_array['is_giftcard_valid'] = true;

		} else {
			$GLOBALS['ec_cart_data']->cart_data->giftcard = "";
			$return_array['giftcard_message'] = wp_easycart_language()->get_text( 'cart_coupons', 'cart_invalid_giftcard' );
			$return_array['is_giftcard_valid'] = false;
		}	
		echo json_encode( $return_array );
	} else {
		$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
		$order_totals = ec_get_order_totals( $cart );
		echo esc_attr( $cart->total_items ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->sub_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->tax_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->shipping_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( (-1) * $order_totals->discount_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->duty_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->vat_total ) ) . '***' . 
				esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->grand_total ) );

		if ( $giftcard )
			echo '***' . esc_attr( $giftcard->message ) . '***' . "valid";
		else {
			$GLOBALS['ec_cart_data']->cart_data->giftcard = "";
			echo '***' . wp_easycart_language()->get_text( 'cart_coupons', 'cart_invalid_giftcard' ) . '***' . "invalid";
		}

		if ( $order_totals->discount_total == 0 )
			echo "***0";
		else
			echo "***1";
	}

	die();

}

add_action( 'wp_ajax_ec_ajax_estimate_shipping', 'ec_ajax_estimate_shipping' );
add_action( 'wp_ajax_nopriv_ec_ajax_estimate_shipping', 'ec_ajax_estimate_shipping' );
function ec_ajax_estimate_shipping() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-estimate-shipping-' . $session_id ) ) {
		die();
	}

	//Get the variables from the AJAX call
	if ( isset( $_POST['zipcode'] ) ) {
		$GLOBALS['ec_cart_data']->cart_data->estimate_shipping_zip = sanitize_text_field( $_POST['zipcode'] );
		$GLOBALS['ec_cart_data']->cart_data->shipping_zip = sanitize_text_field( $_POST['zipcode'] );
	}

	if ( isset( $_POST['country'] ) && $_POST['country'] != "0" ) {
		$GLOBALS['ec_cart_data']->cart_data->estimate_shipping_country = sanitize_text_field( $_POST['country'] );
		$GLOBALS['ec_cart_data']->cart_data->shipping_country = sanitize_text_field( $_POST['country'] );
	}

	if ( isset( $_POST['zipcode'] ) && isset( $_POST['country'] ) && '0' != $_POST['country'] ) {
		$estimate_state = $GLOBALS['ec_countries']->get_state_from_zip( $_POST['country'], $_POST['zipcode'] );
		if ( $estimate_state ) {
			$GLOBALS['ec_cart_data']->cart_data->shipping_state = $estimate_state;
		}
	}

	$GLOBALS['ec_cart_data']->save_session_to_db();
	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );

	if ( isset( $_POST['ec_v3_24'] ) ) {
		$return_array = ec_get_cart_data();
		echo json_encode( $return_array );

	} else {
		$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
		$order_totals = ec_get_order_totals( $cart );
		$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
		$shipping = new ec_shipping( $cart->subtotal, $cart->weight, $cart->shippable_total_items, 'RADIO', $GLOBALS['ec_user']->freeshipping );

		if ( $GLOBALS['ec_setting']->get_shipping_method() == "live" ) {
			echo esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->shipping_total ) ) . '***' . esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->grand_total ) ) . '***';
			$shipping->print_shipping_options( 
				wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ),
				wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ),
				'RADIO'
			);
			echo '***' . esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->vat_total ) );
		} else {
			echo esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->shipping_total ) ) . '***' . esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->grand_total ) ) . '***';
			$shipping->print_shipping_options( 
				wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ),
				wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ),
				'RADIO'
			);
		}
	}
	die();

}

add_action( 'wp_ajax_ec_ajax_update_subscription_shipping_method', 'ec_ajax_update_subscription_shipping_method' );
add_action( 'wp_ajax_nopriv_ec_ajax_update_subscription_shipping_method', 'ec_ajax_update_subscription_shipping_method' );
function ec_ajax_update_subscription_shipping_method() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-update-shipping-method-' . $session_id ) ) {
		die();
	}

	//Get the variables from the AJAX call
	$shipping_method = sanitize_text_field( $_POST['shipping_method'] );
	$ship_express = (int) sanitize_text_field( $_POST['ship_express'] );

	//Create a new db and submit review
	$GLOBALS['ec_cart_data']->cart_data->shipping_method = $shipping_method;
	$GLOBALS['ec_cart_data']->cart_data->expedited_shipping = $ship_express;

	$GLOBALS['ec_cart_data']->save_session_to_db();
	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );

	wp_easycart_subscription_output_ajax_totals();
	die();
}

add_action( 'wp_ajax_ec_ajax_update_shipping_method', 'ec_ajax_update_shipping_method' );
add_action( 'wp_ajax_nopriv_ec_ajax_update_shipping_method', 'ec_ajax_update_shipping_method' );
function ec_ajax_update_shipping_method() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-update-shipping-method-' . $session_id ) ) {
		die();
	}

	//Get the variables from the AJAX call
	$shipping_method = sanitize_text_field( $_POST['shipping_method'] );
	$ship_express = (int) sanitize_text_field( $_POST['ship_express'] );

	//Create a new db and submit review
	$GLOBALS['ec_cart_data']->cart_data->shipping_method = $shipping_method;
	$GLOBALS['ec_cart_data']->cart_data->expedited_shipping = $ship_express;

	$GLOBALS['ec_cart_data']->save_session_to_db();
	wp_cache_flush();
	do_action( 'wpeasycart_cart_updated' );

	$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
	$order_totals = ec_get_order_totals( $cart );
	$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
	$shipping = new ec_shipping( $cart->subtotal, $cart->weight, $cart->shippable_total_items, 'RADIO', $GLOBALS['ec_user']->freeshipping );

	if ( $GLOBALS['ec_setting']->get_shipping_method() == "live" ) {
		echo esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->shipping_total ) ) . '***' .esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->grand_total ) ) . '***';
		$shipping->print_shipping_options(
			wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ),
			wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ),
			'RADIO'
		);
		echo '***' . esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->vat_total ) );
	} else {
		echo esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->shipping_total ) ) . '***' . esc_attr( $GLOBALS['currency']->get_currency_display( $order_totals->grand_total ) ) . '***';
		$shipping->print_shipping_options(
			wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ),
			wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ),
			'RADIO'
		);
	}

	die();

}

add_action( 'wp_ajax_ec_ajax_update_payment_method', 'ec_ajax_update_payment_method' );
add_action( 'wp_ajax_nopriv_ec_ajax_update_payment_method', 'ec_ajax_update_payment_method' );
function ec_ajax_update_payment_method() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-update-payment-method-' . $session_id ) ) {
		die();
	}

	//Get the variables from the AJAX call
	$payment_method = sanitize_text_field( $_POST['payment_method'] );
	$GLOBALS['ec_cart_data']->cart_data->payment_method = $payment_method;
	$GLOBALS['ec_cart_data']->save_session_to_db();
	die();

}

add_action( 'wp_ajax_ec_ajax_insert_customer_review', 'ec_ajax_insert_customer_review' );
add_action( 'wp_ajax_nopriv_ec_ajax_insert_customer_review', 'ec_ajax_insert_customer_review' );
function ec_ajax_insert_customer_review() {
	wpeasycart_session()->handle_session();
	$product_id = (int) $_POST['product_id'];
	
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-insert-customer-review-' . $product_id ) ) {
		die();
	}

	//Get the variables from the AJAX call
	$rating = (int) $_POST['review_score'];
	$title = sanitize_text_field( $_POST['review_title'] );
	$description = sanitize_textarea_field( $_POST['review_message'] );

	//Create a new db and submit review
	$db = new ec_db();
	echo esc_attr( ( $db->submit_customer_review( $product_id, $rating, $title, $description, $GLOBALS['ec_user']->user_id ) ) ? '1' : '0' );

	die();

}

add_action( 'wp_ajax_ec_ajax_live_search', 'ec_ajax_live_search' );
add_action( 'wp_ajax_nopriv_ec_ajax_live_search', 'ec_ajax_live_search' );
function ec_ajax_live_search() {

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-live-search' ) ) {
		die();
	}

	//Get the variables from the AJAX call
	$search_val = sanitize_text_field( $_POST['search_val'] );

	//Create a new db and submit review
	$db = new ec_db();
	$results = $db->get_live_search_options( $search_val );
	echo json_encode( $results );

	die();

}

add_action( 'wp_ajax_ec_ajax_close_newsletter', 'ec_ajax_close_newsletter' );
add_action( 'wp_ajax_nopriv_ec_ajax_close_newsletter', 'ec_ajax_close_newsletter' );
function ec_ajax_close_newsletter() {

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-close-newsletter' ) ) {
		die();
	}

	setcookie( 'ec_newsletter_popup', 'hide', time() + ( 10 * 365 * 24 * 60 * 60 ), "/" );

	die();

}

add_action( 'wp_ajax_ec_ajax_submit_newsletter_signup', 'ec_ajax_submit_newsletter_signup' );
add_action( 'wp_ajax_nopriv_ec_ajax_submit_newsletter_signup', 'ec_ajax_submit_newsletter_signup' );
function ec_ajax_submit_newsletter_signup() {

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-submit-newsletter' ) ) {
		die();
	}

	$newsletter_name = "";
	if ( isset( $_POST['newsletter_name'] ) ) {
		$newsletter_name = sanitize_text_field( $_POST['newsletter_name'] );
	}

	if ( filter_var( $_POST['email_address'], FILTER_VALIDATE_EMAIL ) ) {
		$ec_db = new ec_db();
		$ec_db->insert_subscriber( sanitize_email( $_POST['email_address'] ), $newsletter_name, "" );

		// MyMail Hook
		if ( function_exists( 'mailster' ) ) {
			$subscriber_id = mailster('subscribers')->add(array(
				'email' => sanitize_email( $_POST['email_address'] ),
				'name' => $newsletter_name,
				'status' => 1,
			), false );
		}

		do_action( 'wpeasycart_subscriber_added', sanitize_email( $_POST['email_address'] ), sanitize_text_field( $_POST['newsletter_name'] ) );
	}
	setcookie( 'ec_newsletter_popup', 'hide', time() + ( 10 * 365 * 24 * 60 * 60 ), "/" );

	die();

}

add_action( 'wp_ajax_ec_ajax_create_stripe_ideal_order', 'ec_ajax_create_stripe_ideal_order' );
add_action( 'wp_ajax_nopriv_ec_ajax_create_stripe_ideal_order', 'ec_ajax_create_stripe_ideal_order' );
function ec_ajax_create_stripe_ideal_order() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-create-stripe-ideal-order-' . $session_id ) ) {
		die();
	}

	$source = array(
		'id' => ( isset( $_POST['source']['id'] ) ) ? sanitize_text_field( $_POST['source']['id'] ) : '',
		'client_secret' => ( isset( $_POST['source']['client_secret'] ) ) ? sanitize_text_field( $_POST['source']['client_secret'] ) : '',
	);
	$cartpage = new ec_cartpage();
	$order_id = $cartpage->insert_ideal_order( $source );
	die();
}

add_action( 'wp_ajax_ec_ajax_stripe_check_order_status', 'ec_ajax_stripe_check_order_status' );
add_action( 'wp_ajax_nopriv_ec_ajax_stripe_check_order_status', 'ec_ajax_stripe_check_order_status' );
function ec_ajax_stripe_check_order_status() {
	global $wpdb;

	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-create-stripe-ideal-order-' . $session_id ) ) {
		die();
	}

	if ( ! isset( $_POST['source'] ) ) {
		die();
	}

	$cart_page_id = get_option( 'ec_option_cartpage' );
	if ( function_exists( 'icl_object_id' ) ) {
		$cart_page_id = icl_object_id( $cart_page_id, 'page', true, ICL_LANGUAGE_CODE );
	}
	$cart_page = get_permalink( $cart_page_id );
	if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
		$https_class = new WordPressHTTPS();
		$cart_page = $https_class->makeUrlHttps( $cart_page );
	}
	if ( substr_count( $cart_page, '?' ) ) {
		$permalink_divider = "&";
	} else {
		$permalink_divider = "?";
	}

	if ( 'stripe' == get_option( 'ec_option_payment_process_method' ) ) {
		$stripe = new ec_stripe();
	} else {
		$stripe = new ec_stripe_connect();
	}
	$stripe_pi_response = $stripe->get_payment_intent( $_POST['source'] );

	if ( ! $stripe_pi_response ) {
		$response_obj = (object) array(
			'status' => '',
			'redirect' => esc_url_raw( $cart_page ),
		);
		echo json_encode( $response_obj );
		die();
	}

	if ( 'requires_confirmation' == $stripe_pi_response->status ) {
		$response_obj = (object) array(
			'status' => $stripe_pi_response->status,
			'redirect' => '',
		);
		echo json_encode( $response_obj );
		die();
	}

	$order = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ec_order WHERE gateway_transaction_id = %s", $stripe_pi_response->id . ':' . $stripe_pi_response->client_secret ) );
	if ( $order ) {
		$order_id = $order->order_id;
		$response_obj = (object) array(
			'status' => $stripe_pi_response->status,
			'redirect' => esc_url_raw( $cart_page . $permalink_divider . "ec_page=checkout_success&order_id=" . $order_id ),
		);
		echo json_encode( $response_obj );
		die();
	}

	if ( (int) $_POST['count'] > 3 || ( $stripe_pi_response && in_array( $stripe_pi_response->status, array( 'succeeded', 'processing', 'requires_capture', 'canceled' ) ) ) ) {
		$cartpage = new ec_cartpage();
		$source = array(
			'id' => $stripe_pi_response->id,
			'client_secret' => $stripe_pi_response->client_secret,
		);
		$order_id = $cartpage->insert_ideal_order( $source );
		$response_obj = (object) array(
			'status' => '',
			'redirect' => esc_url_raw( $cart_page . $permalink_divider . "ec_page=checkout_success&order_id=" . $order_id ),
		);
		echo json_encode( $response_obj );
		die();
	}
	
	$response_obj = (object) array(
		'status' => $stripe_pi_response->status,
		'redirect' => esc_url_raw( $cart_page ),
	);
	echo json_encode( $response_obj );
	die();
}

add_action( 'wp_ajax_ec_ajax_subscribe_to_stock_notification', 'ec_ajax_subscribe_to_stock_notification' );
add_action( 'wp_ajax_nopriv_ec_ajax_subscribe_to_stock_notification', 'ec_ajax_subscribe_to_stock_notification' );
function ec_ajax_subscribe_to_stock_notification() {
	$product_id = (int) $_POST['product_id'];
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-subscribe-to-stock-notification-' . $product_id ) ) {
		die();
	}

	$email = sanitize_email( $_POST['email'] );
	$cartpage = new ec_cartpage();

	$recaptcha_valid = true;
	if ( get_option( 'ec_option_enable_recaptcha' ) ) {
		if ( !isset( $_POST['recaptcha_response'] ) || $_POST['recaptcha_response'] == '' ) {
			die();
		}

		$db = new ec_db_admin();
		$recaptcha_response = sanitize_text_field( $_POST['recaptcha_response'] );

		$data = array(
			"secret"	=> get_option( 'ec_option_recaptcha_secret_key' ),
			"response"	=> $recaptcha_response
		);

		$request = new WP_Http;
		$response = $request->request( 
			"https://www.google.com/recaptcha/api/siteverify", 
			array( 
				'method' => 'POST', 
				'body' => http_build_query( $data ),
				'timeout' => 30
			)
		);
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			$db->insert_response( 0, 1, "GOOGLE RECAPTCHA CURL ERROR", $error_message );
			$response = (object) array( "error" => $error_message );
		} else {
			$response = json_decode( $response['body'] );
			$db->insert_response( 0, 0, "Google Recaptcha Response", print_r( $response, true ) );
		}

		$recaptcha_valid = ( isset( $response->success ) && $response->success ) ? true : false;
	}

	if ( $recaptcha_valid ) {
		global $wpdb;

		$found = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ec_product_subscriber WHERE email = %s AND product_id = %d", $email, $product_id ) );
		if ( !$found ) {
			$wpdb->query( $wpdb->prepare( "INSERT INTO ec_product_subscriber( email, product_id ) VALUES( %s, %d )", $email, $product_id ) );
		} else {
			$wpdb->query( $wpdb->prepare( "UPDATE ec_product_subscriber SET status = 'subscribed' WHERE email = %s AND product_id = %d", $email, $product_id ) );
		}

	}

	die();
}

add_action( 'wp_ajax_ec_ajax_check_stripe_3ds_order', 'ec_ajax_check_stripe_3ds_order' );
add_action( 'wp_ajax_nopriv_ec_ajax_check_stripe_3ds_order', 'ec_ajax_check_stripe_3ds_order' );
function ec_ajax_check_stripe_3ds_order() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-check-stripe-3ds-order-' . $session_id ) ) {
		die();
	}

	if ( ! isset( $_POST['source'] ) ) {
		die();
	}

	if ( ! isset( $_POST['client_secret'] ) ) {
		die();
	}
	
	if ( 'stripe' == get_option( 'ec_option_payment_process_method' ) ) {
		$stripe = new ec_stripe();
	} else {
		$stripe = new ec_stripe_connect();
	}
	$result = $stripe->get_payment_intent( $_POST['source'] );
	if ( ! $result ) {
		$response = array(
			'status'  => 'failed'
		);
		echo json_encode( $response );
	} else {
		echo json_encode( $result );
	}
	die();
}

add_action( 'wp_ajax_ec_ajax_check_stripe_ideal_order', 'ec_ajax_check_stripe_ideal_order' );
add_action( 'wp_ajax_nopriv_ec_ajax_check_stripe_ideal_order', 'ec_ajax_check_stripe_ideal_order' );
function ec_ajax_check_stripe_ideal_order() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-check-stripe-ideal-order-' . $session_id ) ) {
		die();
	}

	global $wpdb;
	$order = $wpdb->get_row( $wpdb->prepare( "SELECT ec_order.order_id FROM ec_order, ec_orderstatus WHERE ec_order.gateway_transaction_id = %s AND ec_order.orderstatus_id = ec_orderstatus.status_id AND is_approved = 1", sanitize_text_field( $_POST['source'] . ':' . $_POST['client_secret'] ) ) );
	$failed_order = $wpdb->get_row( $wpdb->prepare( "SELECT ec_order.order_id FROM ec_order WHERE ec_order.gateway_transaction_id = %s", sanitize_text_field( $_POST['source'] . ':' . $_POST['client_secret'] ) ) );
	if ( $order ) {
		// Clear tempcart
		$ec_db_admin = new ec_db_admin();
		$ec_db_admin->clear_tempcart( $GLOBALS['ec_cart_data']->ec_cart_id );
		$GLOBALS['ec_cart_data']->checkout_session_complete();
		$GLOBALS['ec_cart_data']->save_session_to_db();
		echo esc_attr( $order->order_id );

	} else if ( !$failed_order ) {
		echo 'failed';

	} else {
		echo '0';
	}
	die();
}

add_action( 'wp_ajax_ec_ajax_check_stripe_ideal_order_skip', 'ec_ajax_check_stripe_ideal_order_skip' );
add_action( 'wp_ajax_nopriv_ec_ajax_check_stripe_ideal_order_skip', 'ec_ajax_check_stripe_ideal_order_skip' );
function ec_ajax_check_stripe_ideal_order_skip() {
	wpeasycart_session()->handle_session();
	$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-check-stripe-ideal-order-skip-' . $session_id ) ) {
		die();
	}

	global $wpdb;
	$order = $wpdb->get_row( $wpdb->prepare( "SELECT ec_order.order_id, ec_orderstatus.is_approved FROM ec_order, ec_orderstatus WHERE ec_order.gateway_transaction_id = %s AND ec_order.orderstatus_id = ec_orderstatus.status_id", sanitize_text_field( $_POST['source'] . ':' . $_POST['client_secret'] ) ) );
	if ( $order ) {
		// Clear tempcart
		$ec_db_admin = new ec_db_admin();
		$ec_db_admin->clear_tempcart( $GLOBALS['ec_cart_data']->ec_cart_id );
		$GLOBALS['ec_cart_data']->checkout_session_complete();
		$GLOBALS['ec_cart_data']->save_session_to_db();
		$response = array(
			'order_id'  => $order->order_id,
			'is_approved' => $order->is_approved,
			'status'   => 'skip'
		);
		echo json_encode( $response );

	} else {
		$response = array(
			'status'  => 'failed'
		);
		echo json_encode( $response );
	}
	die();
}

add_action( 'wp_ajax_ec_ajax_save_page_options', 'ec_ajax_save_page_options' );
function ec_ajax_save_page_options() {

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-save-page-options' ) ) {
		die();
	}

	if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) {
		update_option( 'ec_option_design_saved', 1 );
		$db = new ec_db();
		$post_id = (int) $_POST['post_id'];
		foreach ( $_POST as $key => $var ) {

			if ( $key == 'ec_option_details_main_color' ) {
				update_option( 'ec_option_details_main_color', preg_replace( '/[^\#0-9A-Z]/', '', strtoupper( sanitize_text_field( $_POST['ec_option_details_main_color'] ) ) ) );
			} else if ( $key == 'ec_option_details_second_color' ) {
				update_option( 'ec_option_details_second_color', preg_replace( '/[^\#0-9A-Z]/', '', strtoupper( sanitize_text_field( $_POST['ec_option_details_second_color'] ) ) ) );
			} else if ( $key != 'post_id' ) {
				$db->update_page_option( $post_id, $key, $var );
			}

		}
		do_action( 'wpeasycart_page_options_updated' );
	}	
	die();

}

add_action( 'wp_ajax_ec_ajax_save_page_default_options', 'ec_ajax_save_page_default_options' );
function ec_ajax_save_page_default_options() {

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-save-page-default-options' ) ) {
		die();
	}

	if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) {
		update_option( 'ec_option_design_saved', 1 );
		$db = new ec_db();
		$post_id = (int) $_POST['post_id'];
		foreach ( $_POST as $key => $var ) {

			if ( $key != 'post_id' ) {
				update_option( $key, $var );
			}

		}
		do_action( 'wpeasycart_page_options_updated' );
	}
	die();

}

add_action( 'wp_ajax_ec_ajax_save_product_options', 'ec_ajax_save_product_options' );
function ec_ajax_save_product_options() {

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-save-product-options' ) ) {
		die();
	}

	if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) {
		$model_number = sanitize_text_field( $_POST['model_number'] );

		$product_options = new stdClass();
		$product_options->image_hover_type = (int) $_POST['image_hover_type'];
		$product_options->image_effect_type = preg_replace( '/[^0-9a-z]/', '', sanitize_text_field( $_POST['image_effect_type'] ) );
		$product_options->tag_type = (int) $_POST['tag_type'];
		$product_options->tag_text = sanitize_text_field( $_POST['tag_text'] );
		$product_options->tag_bg_color = preg_replace( '/[^0-9A-Z\#]/', '', strtoupper( sanitize_text_field( $_POST['tag_bg_color'] ) ) );
		$product_options->tag_text_color = preg_replace( '/[^0-9A-Z\#]/', '', strtoupper( sanitize_text_field( $_POST['tag_text_color'] ) ) );

		$db = new ec_db();
		$db->update_product_options( $model_number, $product_options );
		do_action( 'wpeasycart_page_options_updated' );
	}
	die();

}

add_action( 'wp_ajax_ec_ajax_mass_save_product_options', 'ec_ajax_mass_save_product_options' );
function ec_ajax_mass_save_product_options() {

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-mass-save-product-options' ) ) {
		die();
	}

	if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) {
		$product_list = (array) $_POST['products']; // XSS OK. Forced array and each item sanitized.

		$product_options = new stdClass();
		$product_options->image_hover_type = (int) $_POST['image_hover_type'];
		$product_options->image_effect_type = preg_replace( '/[^0-9a-z]/', '', sanitize_text_field( $_POST['image_effect_type'] ) );

		$db = new ec_db();
		foreach ( $product_list as $model_number ) {
			$db->update_product_options( sanitize_text_field( $model_number ), $product_options );
		}
		do_action( 'wpeasycart_page_options_updated' );
	}
	die();

}

add_action( 'wp_ajax_ec_ajax_save_product_order', 'ec_ajax_save_product_order' );
function ec_ajax_save_product_order() {
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-save-product-order' ) ) {
		die();
	}

	if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) {
		$post_id = (int) $_POST['post_id'];
		$products_sanitized = array();
		$products = json_decode( wp_unslash( $_POST['product_order'] ) );// XSS OK. Each Item Sanitized and Validated.
		foreach ( $products as $model_number ) {
			$products_sanitized[] = preg_replace( '/[^a-zA-Z0-9-]*$/', '', sanitize_text_field( $model_number ) );
		}
		$db = new ec_db();
		$db->update_page_option( $post_id, 'product_order', json_encode( $products_sanitized ) );
		do_action( 'wpeasycart_page_options_updated' );
	}
	die();
}

add_action( 'wp_ajax_ec_ajax_ec_update_product_description', 'ec_ajax_ec_update_product_description' );
function ec_ajax_ec_update_product_description() {
	$product_id = (int) $_POST['product_id'];
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-update-product-description-' . $product_id ) ) {
		die();
	}

	if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) {
		$description = wp_easycart_language()->convert_text( $_POST['description'] ); // XSS OK, Handled within conversion function.
		global $wpdb;
		$wpdb->query( $wpdb->prepare( "UPDATE ec_product SET ec_product.description = %s WHERE ec_product.product_id = %d", $description, $product_id ) );
		do_action( 'wpeasycart_page_options_updated' );
	}
	die();
}

add_action( 'wp_ajax_ec_ajax_ec_update_product_specifications', 'ec_ajax_ec_update_product_specifications' );
function ec_ajax_ec_update_product_specifications() {
	$product_id = (int) $_POST['product_id'];
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-update-product-specifications-' . $product_id ) ) {
		die();
	}

	if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) {
		$specifications = wp_easycart_language()->convert_text( $_POST['specifications'] ); // XSS OK, Handled within conversion function.
		global $wpdb;
		$wpdb->query( $wpdb->prepare( "UPDATE ec_product SET ec_product.specifications = %s WHERE ec_product.product_id = %d", $specifications, $product_id ) );
		do_action( 'wpeasycart_page_options_updated' );
	}
	die();
}

add_action( 'wp_ajax_ec_ajax_save_product_details_options', 'ec_ajax_save_product_details_options' );
function ec_ajax_save_product_details_options() {
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-save-product-details-options' ) ) {
		die();
	}

	if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) {
		update_option( 'ec_option_details_main_color', preg_replace( '/[^\#0-9A-Z]/', '', strtoupper( sanitize_text_field( $_POST['ec_option_details_main_color'] ) ) ) );
		update_option( 'ec_option_details_second_color', preg_replace( '/[^\#0-9A-Z]/', '', strtoupper( sanitize_text_field( $_POST['ec_option_details_second_color'] ) ) ) );
		update_option( 'ec_option_details_columns_desktop', (int) $_POST['ec_option_details_columns_desktop'] );
		update_option( 'ec_option_details_columns_laptop', (int) $_POST['ec_option_details_columns_laptop'] );
		update_option( 'ec_option_details_columns_tablet_wide', (int) $_POST['ec_option_details_columns_tablet_wide'] );
		update_option( 'ec_option_details_columns_tablet', (int) $_POST['ec_option_details_columns_tablet'] );
		update_option( 'ec_option_details_columns_smartphone', (int) $_POST['ec_option_details_columns_smartphone'] );
		update_option( 'ec_option_use_dark_bg', (int) $_POST['ec_option_use_dark_bg'] );
		do_action( 'wpeasycart_page_options_updated' );
	}
	die();

}

add_action( 'wp_ajax_ec_ajax_save_cart_options', 'ec_ajax_save_cart_options' );
function ec_ajax_save_cart_options() {
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-save-cart-options' ) ) {
		die();
	}

	if ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) {
		update_option( 'ec_option_cart_columns_desktop', (int) $_POST['ec_option_cart_columns_desktop'] );
		update_option( 'ec_option_cart_columns_laptop', (int) $_POST['ec_option_cart_columns_laptop'] );
		update_option( 'ec_option_cart_columns_tablet_wide', (int) $_POST['ec_option_cart_columns_tablet_wide'] );
		update_option( 'ec_option_cart_columns_tablet', (int) $_POST['ec_option_cart_columns_tablet'] );
		update_option( 'ec_option_cart_columns_smartphone', (int) $_POST['ec_option_cart_columns_smartphone'] );
		update_option( 'ec_option_use_dark_bg', (int) $_POST['ec_option_use_dark_bg'] );
		do_action( 'wpeasycart_page_options_updated' );
	}
	die();

}

add_action( 'wp_ajax_ec_ajax_get_dynamic_cart_menu', 'ec_ajax_get_dynamic_cart_menu' );
add_action( 'wp_ajax_nopriv_ec_ajax_get_dynamic_cart_menu', 'ec_ajax_get_dynamic_cart_menu' );
function ec_ajax_get_dynamic_cart_menu() {
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-mini-cart' ) ) {
		die();
	}

	if ( isset( $_POST['language'] ) ) {
		wp_easycart_language()->set_language( sanitize_text_field( $_POST['language'] ) );
	}
	$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
	if ( !get_option( 'ec_option_hide_cart_icon_on_empty' ) || $cart->total_items > 0 ) {

		// Get Cart Page Link
		$cartpageid = get_option('ec_option_cartpage');
		if ( function_exists( 'icl_object_id' ) ) {
			$cartpageid = icl_object_id( $cartpageid, 'page', true, ICL_LANGUAGE_CODE );
		}
		$cartpage = get_permalink( $cartpageid );
		if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
			$https_class = new WordPressHTTPS();
			$cartpage = $https_class->makeUrlHttps( $cartpage );
		}

		$cartpage = apply_filters( 'wpml_permalink', $cartpage, sanitize_text_field( $_POST['language'] ) );

		// Check for correct Label
		if ( $cart->total_items != 1 ) {
			$items_label = wp_easycart_language()->get_text( 'cart', 'cart_menu_icon_label_plural' );
		} else {
			$items_label = wp_easycart_language()->get_text( 'cart', 'cart_menu_icon_label' );
		}

		// Then display to user
		if ( $cart->total_items > 0 ) {
			echo '<a href="' . esc_attr( $cartpage ) . '"><span class="dashicons dashicons-cart" style="vertical-align:middle; margin-top:-5px; margin-right:5px; font-family:dashicons;"></span> ' . ' ( <span class="ec_menu_cart_text"><span class="ec_cart_items_total">' . esc_attr( $cart->total_items ) . '</span> ' . esc_attr( $items_label ) . ' <span class="ec_cart_price_total">' . esc_attr( $GLOBALS['currency']->get_currency_display( $cart->subtotal ) ) . '</span></span> )</a>';

		} else {
			echo '<a href="' . esc_attr( $cartpage ) . '"><span class="dashicons dashicons-cart" style="vertical-align:middle; margin-top:-5px; margin-right:5px; font-family:dashicons;"></span> ' . ' ( <span class="ec_menu_cart_text"><span class="ec_cart_items_total">' . esc_attr( $cart->total_items ) . '</span> ' . esc_attr( $items_label ) . ' <span class="ec_cart_price_total"></span></span> )</a>';
		}

	}

	die();

}

// Helper function for AJAX calls in cart.
function ec_get_order_totals( $cart = false ) {

	if ( ! $cart ) {
		$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
	}
	$user =& $GLOBALS['ec_user'];

	$coupon_code = "";
	if ( $GLOBALS['ec_cart_data']->cart_data->coupon_code != "" )
		$coupon_code = $GLOBALS['ec_cart_data']->cart_data->coupon_code;

	$gift_card = "";
	if ( $GLOBALS['ec_cart_data']->cart_data->giftcard != "" )
		$gift_card = $GLOBALS['ec_cart_data']->cart_data->giftcard;

	// Shipping
	$sales_tax_discount = new ec_discount( $cart, $cart->discountable_subtotal, 0.00, $coupon_code, "", 0 );
	$GLOBALS['wpeasycart_current_coupon_discount'] = $sales_tax_discount->coupon_discount;
	$shipping = new ec_shipping( $cart->shipping_subtotal, $cart->weight, $cart->shippable_total_items, 'RADIO', $GLOBALS['ec_user']->freeshipping, $cart->length, $cart->width, $cart->height, $cart->cart );
	$shipping_price = $shipping->get_shipping_price( $cart->get_handling_total() );
	// Tax (no VAT here)
	$sales_tax_discount = new ec_discount( $cart, $cart->discountable_subtotal, 0.00, $coupon_code, "", 0 );
	$tax = new ec_tax( $cart->subtotal, $cart->taxable_subtotal - $sales_tax_discount->coupon_discount, 0, $GLOBALS['ec_cart_data']->cart_data->shipping_state, $GLOBALS['ec_cart_data']->cart_data->shipping_country, $GLOBALS['ec_user']->taxfree, $shipping_price, $cart );
	// Duty (Based on Product Price) - already calculated in tax
	// Get Total Without VAT, used only breifly
	if ( get_option( 'ec_option_no_vat_on_shipping' ) ) {
		$total_without_vat_or_discount = $cart->vat_subtotal + $tax->tax_total + $tax->duty_total;
	} else {
		$total_without_vat_or_discount = $cart->vat_subtotal + $shipping_price + $tax->tax_total + $tax->duty_total;
	}
	//If a discount used, and no vatable subtotal, we need to set to 0
	if ( $total_without_vat_or_discount < 0 )
		$total_without_vat_or_discount = 0;
	// Discount for Coupon
	$discount = new ec_discount( $cart, $cart->discountable_subtotal, $shipping_price, $coupon_code, $gift_card, $total_without_vat_or_discount );
	// Amount to Apply VAT on
	$promotion = new ec_promotion();
	$vatable_subtotal = $total_without_vat_or_discount - $tax->tax_total - $discount->coupon_discount - $promotion->get_discount_total( $cart->subtotal );
	// If for some reason this is less than zero, we should correct
	if ( $vatable_subtotal < 0 )
		$vatable_subtotal = 0;
	// Get Tax Again For VAT
	$tax = new ec_tax( $cart->subtotal, $cart->taxable_subtotal - $sales_tax_discount->coupon_discount, $vatable_subtotal, $GLOBALS['ec_cart_data']->cart_data->shipping_state, $GLOBALS['ec_cart_data']->cart_data->shipping_country, $GLOBALS['ec_user']->taxfree, $shipping_price, $cart );
	// Discount for Gift Card
	$grand_total = ( $cart->subtotal + $tax->tax_total + $shipping_price + $tax->duty_total );
	$discount = new ec_discount( $cart, $cart->discountable_subtotal, $shipping_price, $coupon_code, $gift_card, $grand_total );
	// Order Totals
	$order_totals = new ec_order_totals( $cart, $GLOBALS['ec_user'], $shipping, $tax, $discount );
	return $order_totals;
}

function ec_get_cart_data() {
	$ec_db = new ec_db();
	$cartpage = new ec_cartpage();

	// GET NEW CART ITEM INFO
	$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
	$user_zones = $ec_db->get_zone_ids( $GLOBALS['ec_cart_data']->cart_data->shipping_country, $GLOBALS['ec_cart_data']->cart_data->shipping_state );
	$cart_array = array();

	for ( $i=0; $i<count( $cart->cart ); $i++ ) {
		$shipping_restricted = 0;
		if ( '0' != $cart->cart[$i]->shipping_restriction && '' != $GLOBALS['ec_cart_data']->cart_data->shipping_country && '' != $GLOBALS['ec_cart_data']->cart_data->shipping_state ) {
			$zone_found = false;
			for( $j = 0; $j < count( $user_zones ); $j++ ) {
				if ( $cart->cart[$i]->shipping_restriction == $user_zones[$j]->zone_id ) {
					$zone_found = 1;
				}
			}
			if ( ! $zone_found ) {
				$shipping_restricted = 1;
			}
		}
		$cart_item = array( 
			'id' => $cart->cart[$i]->cartitem_id,
			'unit_price' => $cart->cart[$i]->get_unit_price(),
			'total_price' => $cart->cart[$i]->get_total(),
			'quantity' => $cart->cart[$i]->quantity,
			'stock_quantity' => $cart->cart[$i]->stock_quantity,
			'allow_backorders' => $cart->cart[$i]->allow_backorders,
			'use_optionitem_quantity_tracking' => $cart->cart[$i]->use_optionitem_quantity_tracking,
			'optionitem_stock_quantity' => $cart->cart[$i]->optionitem_stock_quantity,
			'shipping_restricted' => $shipping_restricted,
		);
		$cart_array[] = $cart_item;
	}
	// GET NEW CART ITEM INFO
	$order_totals = ec_get_order_totals( $cart );

	if ( $order_totals->discount_total != 0 )
		$has_discount = 1;
	else
		$has_discount = 0;

	$order_totals_array = array( 
		"sub_total_amt" => round( $order_totals->get_converted_sub_total(), 2 ),
		"sub_total" => $GLOBALS['currency']->get_currency_display( $order_totals->get_converted_sub_total(), false ), 
		"tax_total" => $GLOBALS['currency']->get_currency_display( $order_totals->tax_total ),
		"has_tax" => ( ( $order_totals->tax_total > 0 ) ? 1 : 0 ),
		"shipping_total" => $GLOBALS['currency']->get_currency_display( $order_totals->shipping_total ),
		"duty_total" => $GLOBALS['currency']->get_currency_display( $order_totals->duty_total ),
		"has_duty" => ( ( $order_totals->duty_total > 0 ) ? 1 : 0 ),
		"vat_total" => $GLOBALS['currency']->get_currency_display( $order_totals->vat_total ),
		"has_vat" => ( ( $order_totals->vat_total > 0 ) ? 1 : 0 ),
		"vat_rate_formatted" => $cartpage->get_vat_rate_formatted(),
		"gst_total" => $GLOBALS['currency']->get_currency_display( $order_totals->gst_total ),
		"has_gst" => ( ( $order_totals->gst_total > 0 ) ? 1 : 0 ),
		"hst_total" => $GLOBALS['currency']->get_currency_display( $order_totals->hst_total ),
		"has_hst" => ( ( $order_totals->hst_total > 0 ) ? 1 : 0 ),
		"pst_total" => $GLOBALS['currency']->get_currency_display( $order_totals->pst_total ),
		"has_pst" => ( ( $order_totals->pst_total > 0 ) ? 1 : 0 ),
		"tip_total" => $GLOBALS['currency']->get_currency_display( $order_totals->tip_total ),
		"discount_total" => $GLOBALS['currency']->get_currency_display( (-1) * $order_totals->discount_total ),
		"grand_total" => $GLOBALS['currency']->get_currency_display( $order_totals->get_converted_grand_total(), false ),
		'fees' => array(),
	);

	if ( count( $cartpage->tax->fees ) > 0 ) {
		foreach ( $cartpage->tax->fees as $fee ) {
			$order_totals_array['fees'][] = (object) array(
				'fee_id' => esc_attr( $fee->fee_id ),
				'fee_label' => esc_attr( $fee->label ),
				'fee_total' => esc_attr( $GLOBALS['currency']->get_currency_display( $fee->amount, false ) ),
			);
		}
	}

	ob_start();
	$cartpage->print_stripe_payment_button( false );
	$stripe_button = ob_get_clean();

	$final_array = apply_filters( 'wp_easycart_cart_update_response', array( 	
		"cart" 										=> $cart_array,
		"order_totals"								=> $order_totals_array,
		"items_total"								=> $cart->total_items,
		"weight_total"								=> $cart->weight,
		"has_discount"								=> $has_discount,
		"has_backorder"								=> $cart->has_backordered_item(),
		"stripe_wallet"               => $stripe_button
	) );

	return $final_array;
}

add_action( 'wp_ajax_ec_ajax_get_dynamic_cart_page', 'ec_ajax_get_dynamic_cart_page' );
add_action( 'wp_ajax_nopriv_ec_ajax_get_dynamic_cart_page', 'ec_ajax_get_dynamic_cart_page' );
function ec_ajax_get_dynamic_cart_page() {

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-get-dynamic-cart-page' ) ) {
		die();
	}

	if ( !preg_match( '/[0-9]+/', sanitize_text_field( $_POST['cart_page'] ) ) && !preg_match( '/[0-9]+\-[0-9]+/', sanitize_text_field( $_POST['cart_page'] ) ) ) {
		die();
	}

	if ( isset( $_POST['language'] ) && $_POST['language'] != 'NONE' ) {
		wp_easycart_language()->update_selected_language( sanitize_text_field( $_POST['language'] ) );
		$GLOBALS['ec_cart_data']->cart_data->translate_to = sanitize_text_field( $_POST['language'] );
		$GLOBALS['ec_cart_data']->save_session_to_db( );
	}

	//Get the variables from the AJAX call
	$cartpage = new ec_cartpage();
	$cartpage->display_cart_dynamic( sanitize_text_field( $_POST['cart_page'] ), sanitize_key( $_POST['success_code'] ), sanitize_key( $_POST['error_code'] ) );
	die();
}

add_action( 'wp_ajax_ec_ajax_get_dynamic_account_page', 'ec_ajax_get_dynamic_account_page' );
add_action( 'wp_ajax_nopriv_ec_ajax_get_dynamic_account_page', 'ec_ajax_get_dynamic_account_page' );
function ec_ajax_get_dynamic_account_page() {

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp-easycart-get-dynamic-account-page' ) ) {
		die();
	}

	$pages = array( 'forgot_password', 'register', 'billing_information', 'shipping_information', 'personal_information', 'password', 'orders', 'order_details', 'subscription', 'subscriptions', 'subscription_details' );
	if ( sanitize_text_field( $_POST['account_page'] ) != '' && !in_array( sanitize_text_field( $_POST['account_page'] ), $pages ) && substr( sanitize_text_field( $_POST['account_page'] ), 0, 13 ) != 'order_details' && substr( sanitize_text_field( $_POST['account_page'] ), 0, 20 ) != 'subscription_details' ) {
		$account_page = '';
	} else {
		$account_page = sanitize_text_field( $_POST['account_page'] );
	}
	
	if ( isset( $_POST['language'] ) ) {
		wp_easycart_language()->update_selected_language( sanitize_text_field( $_POST['language'] ) );
		$GLOBALS['ec_cart_data']->cart_data->translate_to = sanitize_text_field( $_POST['language'] );
		$GLOBALS['ec_cart_data']->save_session_to_db( );
	}

	//Get the variables from the AJAX call
	$accountpage = new ec_accountpage();
	$accountpage->display_account_dynamic( $account_page, (int) $_POST['page_id'], sanitize_key( $_POST['success_code'] ), sanitize_key( $_POST['error_code'] ) );
	die();
}
// End AJAX helper function for cart.

add_filter( 'wp_title', 'ec_custom_title', 20 );

function ec_custom_title( $title ) {

	$page_id = get_the_ID();
	$store_id = get_option( 'ec_option_storepage' );

	if ( $page_id == $store_id && isset( $_GET['model_number'] ) ) {
		$db = new ec_db();
		$products = $db->get_product_list( " WHERE product.model_number = '" . sanitize_text_field( $_GET['model_number'] ) . "'", "", "", "" );
		if ( count( $products ) > 0 ) {
			$custom_title = $products[0]['title'] . " |" . $title;
			return $custom_title;
		} else {
			return $title;
		}
	} else if ( $page_id == $store_id ) {

		$additional_title = "";

		if ( isset( $_GET['manufacturer'] ) ) {
			$db = new ec_db();
			$manufacturer = $db->get_manufacturer_row( (int) $_GET['manufacturer'] );

			$additional_title .= $manufacturer->name . " |";
		}

		if ( isset( $_GET['menu'] ) ) {
			$custom_title = sanitize_text_field( $_GET['menu'] ) . " |" . $additional_title . $title;
			return $custom_title;
		} else if ( isset( $_GET['submenu'] ) ) {
			$custom_title = sanitize_text_field( $_GET['submenu'] ) . " |" . $additional_title . $title;
			return $custom_title;
		} else if ( isset( $_GET['subsubmenu'] ) ) {
			$custom_title = sanitize_text_field( $_GET['subsubmenu'] ) . " |" . $additional_title . $title;
			return $custom_title;
		} else {
			return $additional_title . $title;
		}	
	} else {
		return $title;
	}

}

function ec_theme_options_page_callback() {
	if ( is_dir( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option('ec_option_base_theme') . "/" ) )
		include( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option('ec_option_base_theme') . "/admin_panel.php");
	else
		include( EC_PLUGIN_DIRECTORY . "/design/theme/" . get_option('ec_option_latest_theme') . "/admin_panel.php");
}

/////////////////////////////////////////////////////////////////////
//CUSTOM POST TYPES
/////////////////////////////////////////////////////////////////////
add_action( 'init', 'wp_easycart_add_rewrite_webhooks' );
function wp_easycart_add_rewrite_webhooks() {
	add_rewrite_rule( '(.*)/wp-easycart/inc/amfphp/(.*)', '$1/wp-easycart-pro/inc/amfphp/$2', 'top' );
	add_rewrite_rule( '(.*)/paypal_webhook.php', '?wpeasycarthook=paypal-webhook', 'top' );
	add_rewrite_rule( '(.*)/print_giftcard.php?(.*)', '?wpeasycarthook=print-giftcard&$2', 'top' );
	add_rewrite_rule( '(.*)/redsys_success.php', '?wpeasycarthook=redsys-webhook', 'top' );
	add_rewrite_rule( '(.*)/sagepay_paynow_za_payment_complete.php', '?wpeasycarthook=sagepay-webhook', 'top' );
	add_rewrite_rule( '(.*)/stripe_webhook.php', '?wpeasycarthook=stripe-webhook', 'top' );
	if ( get_option( 'ec_option_added_custom_post_type' ) < 3 ) {	
		global $wp_rewrite;		
		$wp_rewrite->flush_rules();
		update_option( 'ec_option_added_custom_post_type', 3 );
	}
}

add_action( 'wp', 'wp_easycart_webhook_catch' );
function wp_easycart_webhook_catch() {
	if ( isset( $_GET['wpeasycarthook'] ) && $_GET['wpeasycarthook'] == 'stripe-webhook' ) {
		$mysqli = new ec_db();

		$body = @file_get_contents('php://input');
		$json = json_decode( $body );
		
		if ( isset( $json->type ) && isset( $json->data ) ) {

			$webhook_id = $json->id;
			$webhook_type = $json->type;
			$webhook_data = $json->data->object;


			$webhook = $mysqli->get_webhook( $webhook_id );

			if ( !$webhook || $webhook_id == "evt_00000000000000" ) {

				$mysqli->insert_webhook( $webhook_id, $webhook_type, $webhook_data );

				// Refund an Order
				if ( $webhook_type == "charge.refunded" ) {

					global $wpdb;
					$order_status = $wpdb->get_var( $wpdb->prepare( "SELECT ec_order.orderstatus_id FROM ec_order WHERE ec_order.stripe_charge_id = %s", $webhook_data->id ) );

					if ( $order_status != 16 && $order_status != 17 ) {
						// Refund order
						$stripe_charge_id = $webhook_data->id;
						$original_amount = $webhook_data->amount;

						$refunds = $webhook_data->refunds->data;
						$refund_total = 0;
						$order_status = 16;

						foreach ( $refunds as $refund ) {
							$refund_total = $refund_total + $refund->amount;
						}

						if ( $refund_total < $original_amount ) {
							$order_status = 17;
						}

						$mysqli->update_stripe_order_status( $stripe_charge_id, $order_status, ( $refund_total / 100 ) );

						if ( $status == "16" )
							do_action( 'wpeasycart_full_order_refund', $orderid );
						else if ( $status == "17" )
							do_action( 'wpeasycart_partial_order_refund', $orderid );
					}

				// Subscription Cancelled (manaually, by customer, or by failed payments)	
				} else if ( $webhook_type == "customer.subscription.deleted" ) {
					$stripe_subscription_id = $webhook_data->id;
					$subscription_row = $mysqli->get_stripe_subscription( $stripe_subscription_id );
					$subscription = new ec_subscription( $subscription_row );
					$mysqli->cancel_stripe_subscription( $stripe_subscription_id );
					$user = $mysqli->get_stripe_user( $webhook_data->customer );
					$subscription->send_subscription_ended_email( $user );

				// Subscription Trial is Ending in 3 Days	
				} else if ( $webhook_type == "customer.subscription.trial_will_end" ) {
					$stripe_subscription_id = $webhook_data->id;
					$subscription_row = $mysqli->get_stripe_subscription( $stripe_subscription_id );
					$subscription = new ec_subscription( $subscription_row );
					$subscription->send_subscription_trial_ending_email();

				// Subscription Recurring Billing Succeeded	
				} else if ( $webhook_type == "invoice.payment_succeeded" ) {
					$payment_timestamp = $webhook_data->created;
					$stripe_subscription_id = $webhook_data->subscription;
					$stripe_charge_id = $webhook_data->charge;
					$subscription = $mysqli->get_stripe_subscription( $stripe_subscription_id );

					$mysqli->insert_response( 0, 1, "STRIPE Subscription", print_r( $webhook_data, true ) );

					if ( $subscription && ( $subscription->last_payment_date + 10 ) >= $payment_timestamp ) {
						$mysqli->update_stripe_order( $subscription->subscription_id, $stripe_charge_id );
					} else if ( $subscription ) {
						$user = $mysqli->get_stripe_user( $webhook_data->customer );
						$order_id = $mysqli->insert_stripe_order( $subscription, $webhook_data, $user );

						do_action( 'wpeasycart_subscription_paid', $order_id );
						do_action( 'wpeasycart_order_paid', $order_id );

						$db_admin = new ec_db_admin();
						$order_row = $db_admin->get_order_row_admin( $order_id );
						$order = new ec_orderdisplay( $order_row, true, true );
						$order->send_email_receipt();

						if ( $subscription->payment_duration > 0 && $subscription->payment_duration <= $subscription->number_payments_completed + 1 ) {
							// Used to cancel when payment duration reached
							$stripe = new ec_stripe();
							$stripe->cancel_subscription( $user, $stripe_subscription_id );
							$mysqli->cancel_stripe_subscription( $stripe_subscription_id );
						} else {
							$mysqli->update_stripe_subscription( $stripe_subscription_id, $webhook_data );
						}
					}

				// Subscription Failed Payment	
				} else if ( $webhook_type == "invoice.payment_failed" ) {

					if ( $webhook_data->billing_reason != 'subscription_create' ) {
						$payment_timestamp = $webhook_data->date;
						$stripe_subscription_id = $webhook_data->subscription;
						$stripe_charge_id = $webhook_data->charge;
						$subscription = $mysqli->get_stripe_subscription( $stripe_subscription_id );

						$mysqli->insert_response( 0, 1, "STRIPE Subscription Failed", print_r( $subscription, true ) );

						if ( $subscription ) {

							$order_id = $mysqli->insert_stripe_failed_order( $subscription, $webhook_data );
							$mysqli->update_stripe_subscription_failed( $subscription_id, $webhook_data );

							$db_admin = new ec_db_admin();
							$order_row = $db_admin->get_order_row_admin( $order_id );
							$order = new ec_orderdisplay( $order_row, true, true );

							$order->send_failed_payment();
						}
					}

				// iDEAL now chargeable	
				} else if ( $webhook_type == "source.chargeable" ) {
					global $wpdb;
					$order = $wpdb->get_row( $wpdb->prepare( "SELECT order_id, grand_total FROM ec_order WHERE gateway_transaction_id = %s", $webhook_data->id . ':' . $webhook_data->client_secret ) );
					if ( $order ) {
						if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' )
							$stripe = new ec_stripe();
						else
							$stripe = new ec_stripe_connect();

						$order_totals = (object) array(
							"grand_total"	=> $order->grand_total
						);

						$response = $stripe->insert_charge( $order_totals, false, $webhook_data->id, $order->order_id, false );

						if ( !isset( $response->error ) ) {
							$wpdb->query( $wpdb->query( "" ) );
							/* Update Stock Quantity */
							$ec_db_admin = new ec_db_admin();
							$order_row = $ec_db_admin->get_order_row_admin( $order->order_id );
							$orderdetails = $ec_db_admin->get_order_details_admin( $order->order_id );

							foreach ( $orderdetails as $orderdetail ) {
								$product = $wpdb->get_row( $wpdb->prepare( "SELECT ec_product.* FROM ec_product WHERE ec_product.product_id = %d", $orderdetail->product_id ) );
								if ( $product ) {
									if ( $product->use_optionitem_quantity_tracking )	
										$ec_db_admin->update_quantity_value( $orderdetail->quantity, $orderdetail->product_id, $orderdetail->optionitem_id_1, $orderdetail->optionitem_id_2, $orderdetail->optionitem_id_3, $orderdetail->optionitem_id_4, $orderdetail->optionitem_id_5 );
									$ec_db_admin->update_product_stock( $orderdetail->product_id, $orderdetail->quantity );
								}
							}

							// Update Order Status/Send Alerts
							$ec_db_admin->update_order_status( $order->order_id, "3" );
							do_action( 'wpeasycart_order_paid', $order->order_id );

							// send email
							$order_display = new ec_orderdisplay( $order_row, true, true );
							$order_display->send_email_receipt();
							$order_display->send_gift_cards();
						}
					}

				// iDEAL failed	
				} else if ( $webhook_type == "source.failed" || $webhook_type == "source.canceled" ) {
					global $wpdb;
					$wpdb->query( $wpdb->prepare( "DELETE FROM ec_order WHERE gateway_transaction_id = %s", $webhook_data->id . ':' . $webhook_data->client_secret ) );

				// Payment Intent Succeeded	
				} else if ( $webhook_type == "payment_intent.succeeded" ) {

					global $wpdb;
					$ec_db_admin = new ec_db_admin();

					$stripe = false;
					if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
						$stripe = new ec_stripe();
					} else if ( get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) {
						$stripe = new ec_stripe_connect();
					}

					$mysqli->insert_response( 0, 0, "STRIPE Payment Complete", print_r( $webhook_data, true ) );
					$order = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ec_order WHERE gateway_transaction_id = %s", $webhook_data->id ) );
					if ( ! $order ) {
						$order = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ec_order WHERE gateway_transaction_id = %s", $webhook_data->id . ':' . $webhook_data->client_secret ) );
					}
					if ( $order ) {
						$order_row = $ec_db_admin->get_order_row_admin( $order->order_id );
						$orderdetails = $ec_db_admin->get_order_details_admin( $order->order_id );
						// Update Order Status/Send Alerts
						$ec_db_admin->update_order_status( $order->order_id, "6" );
						$charge_id = ( isset( $webhook_data->charges ) & isset( $webhook_data->charges->data ) && isset( $webhook_data->charges->data[0]->id ) ) ? $webhook_data->charges->data[0]->id : '';
						if ( '' == $charge_id && isset( $webhook->latest_charge ) && '' != $webhook->latest_charge ) {
							$charge_id = $webhook->latest_charge;
						}
						if ( '' != $charge_id ) {
							$wpdb->query( $wpdb->prepare( "UPDATE ec_order SET stripe_charge_id = %s WHERE order_id = %d", $charge_id, $order->order_id ) );
						}
						do_action( 'wpeasycart_order_paid', $order->order_id );

						// send email
						if ( apply_filters( 'wp_easycart_stripe_webhook_payment_intent_succeeded_send_email', true ) ) {
							$order_display = new ec_orderdisplay( $order_row, true, true );
							$order_display->send_email_receipt();
							$order_display->send_gift_cards();
						}

						if ( $stripe ) {
							$stripe->update_payment_intent_description( $webhook_data->id, $order->order_id );
						}

					} else {
						if ( $stripe ) {
							$payment_intent = $stripe->get_payment_intent( $webhook_data->id );
							$mysqli->insert_response( 0, 0, "STRIPE Payment Intent", print_r( $payment_intent, true ) );
							if ( $payment_intent ) {
								sleep(3); // Add delay to prevent duplicate order creation in test mode, not really an issue on a live site.
								global $wpdb;
								$tempcart_data = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_tempcart_data WHERE stripe_paymentintent_id = %s AND stripe_pi_client_secret = %s', $webhook_data->id, $webhook_data->client_secret ) );
								$mysqli->insert_response( 0, 0, "STRIPE tempcart data", print_r( $tempcart_data, true ) );
								if ( $tempcart_data ) {
									$GLOBALS['ec_cart_data'] = new ec_cart_data( $tempcart_data->session_id );
									$charge = $stripe->get_charge( $payment_intent->latest_charge );
									$mysqli->insert_response( 0, 0, "STRIPE Charge", print_r( $tempcart_data, true ) );
									if ( $charge ) {
										$redirect_types = array( 'affirm', 'afterpay_clearpay', 'alipay', 'bancontact', 'eps', 'giropay', 'ideal', 'klarna', 'multibanco', 'p24', 'sofort' );
										if ( isset( $charge->payment_method_details ) && isset( $charge->payment_method_details->type ) && in_array( $charge->payment_method_details->type, $redirect_types ) ) {
											$mysqli->insert_response( 0, 0, "STRIPE Redirect Types", print_r( $redirect_types, true ) );
											$ec_db = new ec_db();
											$cart = new ec_cart( $tempcart_data->session_id );
											$user = new ec_user( $tempcart_data->user_id );
											$shipping = new ec_shipping( $cart->shipping_subtotal, $cart->weight, $cart->shippable_total_items, 'RADIO', $user->freeshipping, $cart->length, $cart->width, $cart->height, $cart->cart );

											if ( isset( $tempcart_data->coupon_code ) && '' != $tempcart_data->coupon_code ) {
												$coupon_code = $tempcart_data->coupon_code;
												$coupon_result = $GLOBALS['ec_coupons']->redeem_coupon_code( $coupon_code );
												if ( $coupon_result ) {
													$coupon = $coupon_result;
												}
											} else {
												$coupon_code = '';
											}

											if ( isset( $tempcart_data->giftcard ) && '' != $tempcart_data->giftcard ) {
												$gift_card = $tempcart_data->giftcard;
												$giftcard = $ec_db->redeem_gift_card( $gift_card );
												if ( ! $giftcard ) {
													$gift_card = '';
												}
											} else {
												$gift_card = '';
											}

											$promotion = new ec_promotion();
											$promotion->apply_free_shipping( $cart );

											$shipping_price = $shipping->get_shipping_price( $cart->get_handling_total() );
											$sales_tax_discount = new ec_discount( $cart, $cart->discountable_subtotal, 0.00, $coupon_code, "", 0 );
											$GLOBALS['wpeasycart_current_coupon_discount'] = $sales_tax_discount->coupon_discount;

											$tax = new ec_tax( $cart->subtotal, $cart->taxable_subtotal - $sales_tax_discount->coupon_discount, 0, $tempcart_data->shipping_state, $tempcart_data->shipping_country, $user->taxfree, $shipping_price, $cart );

											if ( get_option( 'ec_option_no_vat_on_shipping' ) ) {
												$total_without_vat_or_discount = $cart->vat_subtotal + $tax->tax_total + $tax->duty_total;
											} else {
												$total_without_vat_or_discount = $cart->vat_subtotal + $shipping_price + $tax->tax_total + $tax->duty_total;
											}

											if ( $total_without_vat_or_discount < 0 ) {
												$total_without_vat_or_discount = 0;
											}

											$discount = new ec_discount( $cart, $cart->discountable_subtotal, $shipping_price, $coupon_code, $gift_card, $total_without_vat_or_discount );
											$promotion = new ec_promotion();

											$vatable_subtotal = $total_without_vat_or_discount - $tax->tax_total - $discount->coupon_discount - $promotion->get_discount_total( $cart->subtotal );
											if ( $vatable_subtotal < 0 ) {
												$vatable_subtotal = 0;
											}

											$tax = new ec_tax( $cart->subtotal, $cart->taxable_subtotal - $sales_tax_discount->coupon_discount, $vatable_subtotal, $tempcart_data->shipping_state, $tempcart_data->shipping_country, $user->taxfree, $shipping_price, $cart );

											$grand_total = ( $cart->subtotal + $tax->tax_total + $shipping_price + $tax->duty_total );
											$discount = new ec_discount( $cart, $cart->discountable_subtotal, $shipping_price, $coupon_code, $gift_card, $grand_total );

											$order_totals = new ec_order_totals( $cart, $user, $shipping, $tax, $discount );
											$GLOBALS['ec_order_grand_total' ] = $order_totals->grand_total;

											$credit_card = new ec_credit_card( '', '', '', '', '', '' );
											$payment = new ec_payment( $credit_card, '' );
											
											$order = new ec_order( $cart, $user, $shipping, $tax, $discount, $order_totals, $payment );
											$mysqli->insert_response( 0, 0, "STRIPE New Webhook Order", print_r( $order, true ) );
											$order->submit_order( 'ideal' );
											$order_id = $order->order_id;
											$stripe->update_payment_intent_description( $webhook_data->id, $order_id );

											$order_gateway = 'stripe_connect';
											if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
												$order_gateway = 'stripe';
											}

											$order_status = 6;
											if ( $webhook_data->status == 'succeeded' ) {
												$order_status = 3;
											} else if ( $webhook_data->status == 'requires_capture' ) {
												$order_status = 12;
											} else if ( $webhook_data->status == 'processing' ) {
												$order_status = 12;
											} else if ( $webhook_data->status == 'canceled' ) {
												$order_status = 19;
											}

											$ec_db_admin->clear_tempcart( $tempcart_data->session_id );
											$wpdb->query( $wpdb->prepare( 'UPDATE ec_order SET orderstatus_id = %d, order_gateway = %s, gateway_transaction_id = %s, payment_method = %s, stripe_charge_id = %s WHERE order_id = %d', $order_status, $order_gateway, $webhook_data->id . ':' . $webhook_data->client_secret, $charge->payment_method_details->type, $payment_intent->latest_charge, $order_id ) );
										}
									}
								}
							}
						}

					}

				} else if ( $webhook_type == "payment_intent.payment_failed" ) {

					global $wpdb;
					$ec_db_admin = new ec_db_admin();

					$ec_db_admin->insert_response( 0, 0, "STRIPE Payment Failed", print_r( $webhook_data, true ) );
					$order = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ec_order WHERE gateway_transaction_id = %s", $webhook_data->id ) );
					if ( ! $order ) {
						$order = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ec_order WHERE gateway_transaction_id = %s", $webhook_data->id . ':' . $webhook_data->client_secret ) );
					}
					if ( $order ) {
						$ec_db_admin->update_order_status( $order->order_id, "19" );
					}

				}

				do_action( 'wpeasycart_stripe_webhook', $webhook_id, $webhook_type, $webhook_data );


			}

		}
		wp_send_json_success( array( 'success' => true ), 200 );

	} else if ( isset( $_GET['wpeasycarthook'] ) && $_GET['wpeasycarthook'] == 'paypal-webhook' ) {
		// Init DB References
		global $wpdb;
		$ec_db_admin = new ec_db_admin();

		$body = @file_get_contents('php://input');
		$json = json_decode( $body );


		// Payment was voided
		if ( $json->event_type == 'PAYMENT.AUTHORIZATION.VOIDED' ) {
			$paypal_payment_id = $json->resource->parent_payment;
			$order_id = $wpdb->get_var( $wpdb->prepare( "SELECT order_id FROM ec_order WHERE gateway_transaction_id = %s", $paypal_order_id ) );
			if ( !$order_id ) {
				die();
			}
			$ec_db_admin->insert_response( $order_id, 0, "PayPal Webhook VOIDED Response", print_r( $json, true ) );

			$ec_db_admin->update_order_status( $order_id, "19" );

		// Order Processed
		} else if ( $json->event_type == 'CHECKOUT.ORDER.PROCESSED' || ( $json->event_type == 'PAYMENT.SALE.COMPLETED' && $json->resource->payment_mode == 'ECHECK' ) ) {
			$paypal_order_id = $json->resource->id;
			$order_id = $wpdb->get_var( $wpdb->prepare( "SELECT order_id FROM ec_order WHERE gateway_transaction_id = %s", $paypal_order_id ) );
			if ( !$order_id ) {
				die();
			}

			$order_row = $ec_db_admin->get_order_row_admin( $order_id );
			$orderdetails = $ec_db_admin->get_order_details_admin( $order_id );
			$ec_db_admin->insert_response( $order_id, 0, "PayPal Webhook Complete Response", print_r( $json, true ) . " --- " . print_r( $order_row, true ) );
			if ( $order_row ) {
				// Update Order Gateway ID From Order to Payment (used on refunds)
				global $wpdb;
				$wpdb->query( $wpdb->prepare( "UPDATE ec_order SET gateway_transaction_id = %s WHERE order_id = %d", $json->resource->payment_details->payment_id, $order_id ) );

				/* Update Stock Quantity */
				foreach ( $orderdetails as $orderdetail ) {
					$product = $wpdb->get_row( $wpdb->prepare( "SELECT ec_product.* FROM ec_product WHERE ec_product.product_id = %d", $orderdetail->product_id ) );
					if ( $product ) {
						if ( $product->use_optionitem_quantity_tracking )	
							$ec_db_admin->update_quantity_value( $orderdetail->quantity, $orderdetail->product_id, $orderdetail->optionitem_id_1, $orderdetail->optionitem_id_2, $orderdetail->optionitem_id_3, $orderdetail->optionitem_id_4, $orderdetail->optionitem_id_5 );
						$ec_db_admin->update_product_stock( $orderdetail->product_id, $orderdetail->quantity );
					}
				}

				// Update Order Status to Paid
				$ec_db_admin->update_order_status( $order_id, "10" );
				do_action( 'wpeasycart_order_paid', $order_id );

				// send email
				$order_display = new ec_orderdisplay( $order_row, true, true );
				$order_display->send_email_receipt();
				$order_display->send_gift_cards();
			}

		// Payment was Refunded
		} else if ( $json->event_type == 'PAYMENT.CAPTURE.REFUNDED' || $json->event_type == 'PAYMENT.SALE.REFUNDED' ) {
			$paypal_sale_id = $json->resource->sale_id;
			$paypal_payment_id = $json->resource->parent_payment;
			$order_id = $wpdb->get_var( $wpdb->prepare( "SELECT order_id FROM ec_order WHERE gateway_transaction_id = %s OR gateway_transaction_id = %s", $paypal_payment_id, $paypal_sale_id ) );
			if ( !$order_id ) {
				die();
			}
			$ec_db_admin->insert_response( $order_id, 0, "PayPal Webhook REFUNDED Response", print_r( $json, true ) );

			$order = $wpdb->get_row( $wpdb->prepare( "SELECT orderstatus_id, refund_total, grand_total FROM ec_order WHERE order_id = %d", $order_id ) );
			$order_status = $order->orderstatus_id;

			if ( $order_status != 16 && $order_status != 17 ) {
				$original_amount = (float) $order->grand_total;
				$refund_total = (float) $order->refund_total + (float) $json->resource->amount->total;
				$order_status = ( $refund_total < $original_amount ) ? 17 : 16;
				$wpdb->query( $wpdb->prepare( "UPDATE ec_order SET orderstatus_id = %d, refund_total = %s WHERE order_id = %d", $order_status, $refund_total, $order_id ) );

				if ( $order_status == "16" )
					do_action( 'wpeasycart_full_order_refund', $orderid );
				else if ( $order_status == "17" )
					do_action( 'wpeasycart_partial_order_refund', $orderid );
			}

		// Payment was Denied
		} else if ( $json->event_type == 'PAYMENT.CAPTURE.DENIED' || $json->event_type == 'PAYMENT.SALE.DENIED' ) {
			$paypal_sale_id = $json->resource->sale_id;
			$paypal_payment_id = $json->resource->parent_payment;
			$order_id = $wpdb->get_var( $wpdb->prepare( "SELECT order_id FROM ec_order WHERE gateway_transaction_id = %s OR gateway_transaction_id = %s", $paypal_payment_id, $paypal_sale_id ) );
			if ( !$order_id ) {
				die();
			}
			$ec_db_admin->insert_response( $order_id, 0, "PayPal Webhook DENIED Response", print_r( $json, true ) );
			$ec_db_admin->update_order_status( $order_id, "7" );

		// Payment Pending
		} else if ( $json->event_type == 'PAYMENT.CAPTURE.PENDING' || $json->event_type == 'PAYMENT.SALE.PENDING' ) {
			$paypal_sale_id = $json->resource->id;
			$paypal_payment_id = $json->resource->parent_payment;
			$order_id = $wpdb->get_var( $wpdb->prepare( "SELECT order_id FROM ec_order WHERE gateway_transaction_id = %s OR gateway_transaction_id = %s", $paypal_payment_id, $paypal_sale_id ) );
			if ( !$order_id ) {
				die();
			}
			$ec_db_admin->insert_response( $order_id, 0, "PayPal Webhook PENDING Response", print_r( $json, true ) );
			$ec_db_admin->update_order_status( $order_id, "8" );

		// Payment Reversed
		} else if ( $json->event_type == 'PAYMENT.CAPTURE.REVERSED' || $json->event_type == 'PAYMENT.SALE.REVERSED' ) {
			$paypal_sale_id = $json->resource->sale_id;
			$paypal_payment_id = $json->resource->parent_payment;
			$order_id = $wpdb->get_var( $wpdb->prepare( "SELECT order_id FROM ec_order WHERE gateway_transaction_id = %s OR gateway_transaction_id = %s", $paypal_payment_id, $paypal_sale_id ) );
			if ( !$order_id ) {
				die();
			}
			$ec_db_admin->insert_response( $order_id, 0, "PayPal Webhook REVERSED Response", print_r( $json, true ) );
			$ec_db_admin->update_order_status( $order_id, "9" );

		} else {
			$ec_db_admin->insert_response( 0, 0, "PayPal Webhook", 'No event type match! ---- ' . print_r( $json, true ) );
		}
		wp_send_json_success( array( 'success' => true ), 200 );

	} else if ( isset( $_GET['wpeasycarthook'] ) && $_GET['wpeasycarthook'] == 'redsys-webhook' ) {
		global $wpdb;
		$mysqli = new ec_db_admin();

		try{
			$redsys = new Tpv();
			$key = get_option( 'ec_option_redsys_key' );

			$parameters = $redsys->getMerchantParameters( sanitize_text_field( $_POST["Ds_MerchantParameters"] ) );
			$DsResponse = (int) $parameters["Ds_Response"];
			$DsResponse += 0;
			if ( $redsys->check( $key, $_POST ) && $DsResponse <= 99 ) {
				$order_id = intval( substr( $parameters['Ds_Order'], 0, -3 ) );
				$response_code = intval( $parameters['Ds_Response'] );
				$mysqli->insert_response( $orderid, 0, "Redsys Success", $response_code . ", " . print_r( $parameters, true ) );


				if ( $response_code <= 99 ) {
					$mysqli->update_order_transaction_id( $order_id, $parameters['Ds_AuthorisationCode'] );
					$order_row = $mysqli->get_order_row_admin( $order_id );
					$orderdetails = $mysqli->get_order_details_admin( $order_id );

					if ( $order_row ) {
						$mysqli->update_order_status( $order_id, "10" );
						do_action( 'wpeasycart_order_paid', $order_id );

						/* Update Stock Quantity */
						foreach ( $orderdetails as $orderdetail ) {
							$product = $wpdb->get_row( $wpdb->prepare( "SELECT ec_product.* FROM ec_product WHERE ec_product.product_id = %d", $orderdetail->product_id ) );
							if ( $product ) {
								if ( $product->use_optionitem_quantity_tracking )	
									$mysqli->update_quantity_value( $orderdetail->quantity, $orderdetail->product_id, $orderdetail->optionitem_id_1, $orderdetail->optionitem_id_2, $orderdetail->optionitem_id_3, $orderdetail->optionitem_id_4, $orderdetail->optionitem_id_5 );
								$mysqli->update_product_stock( $orderdetail->product_id, $orderdetail->quantity );
							}
						}

						// send email
						$order_display = new ec_orderdisplay( $order_row, true, true );
						$order_display->send_email_receipt();
						$order_display->send_gift_cards();
					}
				}
			} else {
				$mysqli->insert_response( 0, 1, "Redsys Failed", "response was invalid." );
			}
		}
		catch( Exception $e ) {
			$mysqli->insert_response( 0, 1, "Redsys Try Failed", $e->getMessage() );
		}
		wp_send_json_success( array( 'success' => true ), 200 );

	} else if ( isset( $_GET['wpeasycarthook'] ) && $_GET['wpeasycarthook'] == 'sagepay-webhook' ) {
		global $wpdb;
		$mysqli = new ec_db_admin();

		$response_string = print_r( $_POST, true );
		$mysqli->insert_response( $order_id, 0, "SagePay PayNow South Africa", $response_string );

		$data = $_POST;
		$order_id = $data['Extra3'];
		$transaction_id = $data['RequestTrace'];

		$pieces = explode( "_", $order_id );
		$order_id = $pieces[0];
		$order_key = esc_attr( $sessionid );
		$data_string = '';
		$data_array = array();

		foreach ( $data as $key => $val ) {
			$data_string .= $key . '=' . urlencode( $val ) . '&';
			$data_array [$key] = $val;
		}

		$data_string = substr( $data_string, 0, - 1 );

		// Get Order
		$order_row = $mysqli->get_order_row_admin( $order_id );
		$orderdetails = $mysqli->get_order_details_admin( $order_id );

		if ( $order_row->orderstatus_id != "10" ) { // Order Has Not Been Processed

			if ( $data['Amount'] == $order_row->grand_total ) { // Make Sure Transaction Matches DB Value

				if ( $data['TransactionAccepted'] == "true" ) { // Transaction Has Been Accepted

					$mysqli->update_order_status( $order_id, "10" );
					do_action( 'wpeasycart_order_paid', $orderid );

					/* Update Stock Quantity */
					foreach ( $orderdetails as $orderdetail ) {
						$product = $wpdb->get_row( $wpdb->prepare( "SELECT ec_product.* FROM ec_product WHERE ec_product.product_id = %d", $orderdetail->product_id ) );
						if ( $product ) {
							if ( $product->use_optionitem_quantity_tracking )	
								$mysqli->update_quantity_value( $orderdetail->quantity, $orderdetail->product_id, $orderdetail->optionitem_id_1, $orderdetail->optionitem_id_2, $orderdetail->optionitem_id_3, $orderdetail->optionitem_id_4, $orderdetail->optionitem_id_5 );
							$mysqli->update_product_stock( $orderdetail->product_id, $orderdetail->quantity );
						}
					}

					// send email
					$order_display = new ec_orderdisplay( $order_row, true, true );
					$order_display->send_email_receipt();
					$order_display->send_gift_cards();

				} else if ( $data['Reason'] == "Denied" ) {
					$mysqli->update_order_status( $order_id, "7" );

				} else { // Transaction Not accepted, log it

					$mysqli->insert_response( $order_id, 0, "SagePay PayNow South Africa", "Warning: Transaction not accepted, but also not denied." );

				}

			} else { // Values do not match

				$mysqli->insert_response( $order_id, 0, "SagePay PayNow South Africa", "Error: Transaction total does not match that in the order table." );

			}

		}
		wp_send_json_success( array( 'success' => true ), 200 );

	} else if ( isset( $_GET['wpeasycarthook'] ) && $_GET['wpeasycarthook'] == 'print-giftcard' ) {
		if ( isset( $_GET['order_id'] ) && isset( $_GET['orderdetail_id'] ) && isset( $_GET['giftcard_id'] ) ) { 
			//Get the variables from the AJAX call
			$order_id = (int) $_GET['order_id'];
			$orderdetail_id = (int) $_GET['orderdetail_id'];
			$giftcard_id = isset( $_GET['giftcard_id'] ) ? preg_replace( '/[^A-Za-z0-9]/', '', sanitize_text_field( $_GET['giftcard_id'] ) ) : '';
			$mysqli = new ec_db_admin();

			if ( isset( $_GET['ec_guest_key'] ) ) {
				$guest_key = isset( $_GET['ec_guest_key'] ) ? substr( preg_replace( '/[^A-Z]/', '', sanitize_text_field( $_GET['ec_guest_key'] ) ), 0, 30 ) : '';
				$order_row = $mysqli->get_guest_order_row( $order_id, $guest_key );
				$orderdetail_row = $mysqli->get_orderdetail_row_guest( $order_id, $orderdetail_id );
				if ( $orderdetail_row ) {
					$giftcard_id = $orderdetail_row->giftcard_id;
				}
			} else {
				$order_row = $mysqli->get_order_row_admin( $order_id );
				$orderdetail_row = $mysqli->get_orderdetail_row_guest( $order_id, $orderdetail_id );
			}

			if ( $orderdetail_row && $orderdetail_row->giftcard_id == $giftcard_id ) {

				if ( $order_row && $order_row->is_approved ) {

					global $wpdb;
					$giftcard_total = $orderdetail_row->unit_price;
					$giftcard_total = $wpdb->get_var( $wpdb->prepare( "SELECT amount FROM ec_giftcard WHERE giftcard_id = %s", $giftcard_id ) );

					$storepageid = get_option('ec_option_storepage');
					if ( function_exists( 'icl_object_id' ) ) {
						$storepageid = icl_object_id( $storepageid, 'page', true, ICL_LANGUAGE_CODE );
					}
					$store_page = get_permalink( $storepageid );
					if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
						$https_class = new WordPressHTTPS();
						$store_page = $https_class->makeUrlHttps( $store_page );
					}

					if ( substr_count( $store_page, '?' ) ) {
						$permalink_divider = "&";
					} else {
						$permalink_divider = "?";
					}

					$ec_orderdetail = new ec_orderdetail( $orderdetail_row );
					$email_logo_url = get_option( 'ec_option_email_logo' );

					// Get receipt
					if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_print_gift_card.php' ) ) {
						include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_print_gift_card.php';
					} else {
						include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_print_gift_card.php';
					}

				} else {

					echo wp_easycart_language()->get_text( "cart_success", "cart_giftcards_unavailable" );

				}
			} else {
				echo "No Order Found";	
			}
		}
		die();

	}
}

add_action( 'init', 'ec_create_post_type_menu' );
function ec_create_post_type_menu() {

	// Fix, V3 upgrades missed the ec_tempcart_optionitem.session_id upgrade!
	if ( !get_option( 'ec_option_v3_fix' ) ) {
		global $wpdb;
		$wpdb->query( "INSERT INTO ec_tempcart_optionitem( tempcart_id, option_id, optionitem_id, optionitem_value ) VALUES( '999999999', '3', '3', 'test' )" );
		$tempcart_optionitem_row = $wpdb->get_row( "SELECT * FROM ec_tempcart_optionitem WHERE tempcart_id = '999999999'" );
		if ( !isset( $tempcart_optionitem_row->session_id ) ) {
			$wpdb->query( "ALTER TABLE ec_tempcart_optionitem ADD `session_id` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'The ec_cart_id that determines the user who entered this value.'" );
		}
		update_option( 'ec_option_v3_fix', 1 );
	}

	// Update store item posts, set to private if inactive in store
	if ( !get_option( 'ec_option_published_check' ) || get_option( 'ec_option_published_check' ) != EC_CURRENT_VERSION ) {	
		global $wpdb;
		$inactive_products = $wpdb->get_results( "SELECT ec_product.post_id, ec_product.model_number, ec_product.title FROM ec_product WHERE ec_product.activate_in_store = 0" );
		foreach ( $inactive_products as $product ) {
			$post = array(	'ID'			=> $product->post_id,
							'post_content'	=> "[ec_store modelnumber=\"" . $product->model_number . "\"]",
							'post_status'	=> "private",
							'post_title'	=> wp_easycart_language()->convert_text( $product->title ),
							'post_type'		=> "ec_store",
							'post_name'		=> str_replace(' ', '-', wp_easycart_language()->convert_text( $product->title ) ),
					 );
			wp_update_post( $post );
		}
		update_option( 'ec_option_published_check', EC_CURRENT_VERSION );
	}

	$store_id = get_option( 'ec_option_storepage' );
	if ( $store_id ) {
		$store_slug = ec_get_the_slug( $store_id );

		$labels = array(
			'name'        => _x( 'Store Items', 'post type general name' ),
			'singular_name'   => _x( 'Store Item', 'post type singular name' ),
			'add_new'      => _x( 'Add New', 'ec_store' ),
			'add_new_item'    => __( 'Add New Store Item' ),
			'edit_item'     => __( 'Edit Store Item' ),
			'new_item'      => __( 'New Store Item' ),
			'all_items'     => __( 'All Store Items' ),
			'view_item'     => __( 'View Store Item' ),
			'search_items'    => __( 'Search Store Items' ),
			'not_found'     => __( 'No store items found' ),
			'not_found_in_trash' => __( 'No store items found in the Trash' ), 
			'parent_item_colon' => '',
			'menu_name'     => 'Store Items'
		);
		$args = array(
			'labels'    	=> $labels,
			'description' 		=> 'Used for the EasyCart Store',
			'public' 			=> true,
			'has_archive' 		=> false,
			'show_ui' 			=> true,
			'show_in_nav_menus' => true,
			'show_in_menu' 		=> apply_filters( 'wp_easycart_show_cpt_in_menu', false ),
			'supports'			=> array( 'title', 'page-attributes', 'author', 'editor', 'post-formats', 'excerpt', 'thumbnail' ),
			'rewrite'			=> array( 'slug' => $store_slug, 'with_front' => false, 'page' => false ),
		);
		register_post_type( 'ec_store', $args );

		global $wp_rewrite;
		$wp_rewrite->add_permastruct( 'ec_store', $store_slug . '/%ec_store%/', true, 1 );
		add_rewrite_rule( '^' . $store_slug . '/([^/]*)/?$', 'index.php?ec_store=$matches[1]', 'top');

		// Only Flush Once!
		if ( get_option( 'ec_option_added_custom_post_type' ) < 2 ) {	
			$wp_rewrite->flush_rules();
			update_option( 'ec_option_added_custom_post_type', 2 );
		}
	}
}

function ec_get_the_slug( $id=null ) {
	if ( empty($id) ) : 
		global $post;
		if ( empty($post) )
			return '';
		$id = $post->ID;
	endif;
	$home_url = parse_url( site_url() );
	if ( isset( $home_url['path'] ) )
		$home_path = $home_url['path'];
	else
		$home_path = '';

	$store_url = parse_url( get_permalink( get_option( 'ec_option_storepage' ) ) );
	$store_path = $store_url['path'];

	$path = ( strlen( $home_path ) == 0 || $home_path == "/" ) ? $store_path : str_replace( $home_path, "", $store_path );

	if ( substr( $path, 0, 1 ) == '/' )
		$path = substr( $path, 1, strlen( $path ) - 1 );

	if ( substr( $path, -1, 1 ) == '/' )
		$path = substr( $path, 0, strlen( $path ) - 1 );

	return $path;
}

add_action( 'wp', 'ec_force_page_type' );
function ec_force_page_type() {
	global $wp_query, $post_type;

	if ( $post_type == 'ec_store' && !get_option( 'ec_option_use_custom_post_theme_template' ) ) {
		$wp_query->is_page = true;
		$wp_query->is_single = false;
		$wp_query->query_vars['post_type'] = "page";
		if ( isset( $wp_query->post ) )
			$wp_query->post->post_type = "page";
	}
}

add_filter( 'template_redirect', 'ec_fix_store_template', 1 );
function ec_fix_store_template() {
	global $wp;
	$custom_post_types = array("ec_store");

	if ( isset( $wp->query_vars["post_type"] ) && in_array( $wp->query_vars["post_type"], $custom_post_types ) ) {
		$store_template = get_post_meta( get_option( 'ec_option_storepage' ), "_wp_page_template", true );
		if ( isset( $store_template ) && $store_template != "" && $store_template != "default" ) {
			if ( file_exists( get_template_directory() . "/" . $store_template ) ) {
				include( get_template_directory() . "/" . $store_template );
				exit();
			}
		}
	}
}

add_action( 'wp_easycart_square_renew_token', 'wp_easycart_square_renew_token' );
function wp_easycart_square_renew_token() {
	if ( get_option( 'ec_option_payment_process_method' ) == 'square' ) {
		if ( class_exists( 'ec_square' ) ) {
			$square = new ec_square();
			$square->renew_token();
		}
	} else {
		wp_clear_scheduled_hook( 'wp_easycart_square_renew_token' );
	}
}

/////////////////////////////////////////////////////////////////////
//HELPER FUNCTIONS
/////////////////////////////////////////////////////////////////////
//Helper Function, Get URL
function ec_get_url() {
 if ( isset( $_SERVER['HTTPS'] ) )
	$protocol = "https";
 else
	$protocol = "http";

 $baseurl = "://" . sanitize_text_field( $_SERVER['HTTP_HOST'] );
 $strip = explode( "/wp-admin", sanitize_text_field( $_SERVER['REQUEST_URI'] ) );
 $folder = $strip[0];
 return $protocol . $baseurl . $folder;
}

function ec_setup_hooks() {
	$GLOBALS['ec_hooks'] = array();
}

function ec_add_hook( $call_location, $function_name, $args = array(), $priority = 1 ) {
	if ( !isset( $GLOBALS['ec_hooks'][$call_location] ) )
		$GLOBALS['ec_hooks'][$call_location] = array();

	$GLOBALS['ec_hooks'][$call_location][] = array( $function_name, $args, $priority );
}

function ec_call_hook( $hook_array, $class_args ) {
	$hook_array[0]( $hook_array[1], $class_args );
}

function ec_dwolla_verify_signature( $proposedSignature, $checkoutId, $amount ) {
	$apiSecret = get_option( 'ec_option_dwolla_thirdparty_secret' );
	$amount = number_format( $amount, 2 );
	$signature = hash_hmac("sha1", "{$checkoutId}&{$amount}", $apiSecret);

	return $signature == $proposedSignature;
}

add_filter( 'wp_nav_menu_items', 'ec_custom_cart_in_menu', 10, 2 );
function ec_custom_cart_in_menu ( $items, $args ) {
	$ids = explode( '***', get_option( 'ec_option_cart_menu_id' ) );
	$menu = wp_get_nav_menu_object( $args->menu );
	if ( get_option( 'ec_option_show_menu_cart_icon' ) && ( in_array( substr( $args->menu_id, 0, -5 ), $ids ) || in_array( $args->theme_location, $ids ) || in_array( 'term_' . $menu->term_id, $ids ) ) ) {
		$items .= '<li class="ec_menu_mini_cart" data-nonce="' . wp_create_nonce( 'wp-easycart-mini-cart' ) . '"></li>';
	}
	return $items;
}

function wpeasycart_activation_redirect( $plugin ) {
	if ( $plugin == plugin_basename( __FILE__ ) ) {
		wp_redirect( admin_url( 'admin.php?page=wp-easycart-settings' ) );
		die();
	}
}
add_action( 'activated_plugin', 'wpeasycart_activation_redirect' );

add_action( 'wpeasycart_abandoned_cart_automation', 'wpeasycart_send_abandoned_cart_emails' );
function wpeasycart_send_abandoned_cart_emails() {
	global $wpdb;
	$abandoned_carts = $wpdb->get_results( $wpdb->prepare( "SELECT ec_tempcart.tempcart_id FROM ec_tempcart, ec_tempcart_data WHERE ec_tempcart.abandoned_cart_email_sent = 0 AND ec_tempcart.session_id = ec_tempcart_data.session_id AND ec_tempcart_data.email != '' AND ec_tempcart.last_changed_date < DATE_SUB( NOW(), INTERVAL %d DAY ) GROUP BY ec_tempcart.session_id", get_option( 'ec_option_abandoned_cart_days' ) ) );
	foreach ( $abandoned_carts as $abandoned_cart ) {
		wpeasycart_send_abandoned_cart_email( $abandoned_cart->tempcart_id );
	}
}

function wpeasycart_send_abandoned_cart_email( $tempcart_id ) {
	global $wpdb;
	$email_logo_url = get_option( 'ec_option_email_logo' );
	$store_page_id = get_option( 'ec_option_storepage' );
	$cart_page_id = get_option( 'ec_option_cartpage' );
	if ( function_exists( 'icl_object_id' ) ) {
		$store_page_id = icl_object_id( $store_page_id, 'page', true, ICL_LANGUAGE_CODE );
		$cart_page_id = icl_object_id( $cart_page_id, 'page', true, ICL_LANGUAGE_CODE );
	}
	$store_page = get_permalink( $store_page_id );
	$cart_page = get_permalink( $cart_page_id );
	if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
		$https_class = new WordPressHTTPS();
		$store_page = $https_class->makeUrlHttps( $store_page );
		$cart_page = $https_class->makeUrlHttps( $cart_page );
	}
	if ( substr_count( $cart_page, '?' ) ) {
		$permalink_divider = "&";
	} else {
		$permalink_divider = "?";
	}

	$headers  = array();
	$headers[] = "MIME-Version: 1.0";
	$headers[] = "Content-Type: text/html; charset=utf-8";
	$headers[] = "From: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
	$headers[] = "Reply-To: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
	$headers[] = "X-Mailer: PHP/".phpversion();

	$tempcart_item = $wpdb->get_row( $wpdb->prepare( "SELECT ec_tempcart.session_id, ec_tempcart.tempcart_id, ec_tempcart.product_id, ec_tempcart.quantity, ec_tempcart_data.translate_to, ec_tempcart_data.billing_first_name, ec_tempcart_data.billing_last_name, ec_tempcart_data.email, ec_product.title FROM ec_tempcart LEFT JOIN ec_tempcart_data ON ec_tempcart_data.session_id = ec_tempcart.session_id LEFT JOIN ec_product ON ec_product.product_id = ec_tempcart.product_id WHERE ec_tempcart.tempcart_id = %d ORDER BY ec_tempcart.session_id, last_changed_date", $tempcart_id ) );
	if ( $tempcart_item->translate_to != '' ) {
		wp_easycart_language()->set_language( $tempcart_item->translate_to );
	}
	$tempcart_rows = $wpdb->get_results( $wpdb->prepare( "SELECT ec_product.*, ec_tempcart.quantity AS tempcart_quantity, ec_tempcart.optionitem_id_1, ec_tempcart.optionitem_id_2, ec_tempcart.optionitem_id_3, ec_tempcart.optionitem_id_4, ec_tempcart.optionitem_id_5 FROM ec_tempcart, ec_product WHERE ec_product.product_id = ec_tempcart.product_id AND ec_tempcart.session_id = %s", $tempcart_item->session_id ) );

	$to = $tempcart_item->email;
	$subject = wp_easycart_language()->get_text( 'ec_abandoned_cart_email', 'email_title' );

	ob_start();
	if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_abandoned_cart_email.php' ) )	
		include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_abandoned_cart_email.php';	
	else
		include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_abandoned_cart_email.php';
	$message = ob_get_clean();

	$email_send_method = get_option( 'ec_option_use_wp_mail' );
	$email_send_method = apply_filters( 'wpeasycart_email_method', $email_send_method );

	if ( $email_send_method == "1" ) {
		wp_mail( $to, $subject, $message, implode("\r\n", $headers), $attachments );
	} else if ( $email_send_method == "0" ) {
		$mailer = new wpeasycart_mailer();
		$mailer->send_order_email( $to, $subject, $message );
	} else {
		do_action( 'wpeasycart_custom_order_email', stripslashes( get_option( 'ec_option_order_from_email' ) ), $to, stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), $subject, $message );
	}
	$wpdb->query( $wpdb->prepare( "UPDATE ec_tempcart SET abandoned_cart_email_sent = 1 WHERE ec_tempcart.session_id = %s", $tempcart_item->session_id ) );

}

function is_wpeasycart_cart() {
	global $is_wpec_cart;
	return $is_wpec_cart;
}

function wp_easycart_load_amazon_js() {
	if ( get_option( 'ec_option_amazonpay_enable' ) ) {
		if( 'EU' == get_option( 'ec_option_amazonpay_region' ) ) {
			wp_enqueue_script( 'wpeasycart_amazonpay_js', 'https://static-eu.payments-amazon.com/checkout.js', array( 'jquery' ), EC_CURRENT_VERSION, false );
		} else if( 'JP' == get_option( 'ec_option_amazonpay_region' ) ) {
			wp_enqueue_script( 'wpeasycart_amazonpay_js', 'https://static-fe.payments-amazon.com/checkout.js', array( 'jquery' ), EC_CURRENT_VERSION, false );
		} else {
			wp_enqueue_script( 'wpeasycart_amazonpay_js', 'https://static-na.payments-amazon.com/checkout.js', array( 'jquery' ), EC_CURRENT_VERSION, false );
		}
		add_filter( 'sgo_js_async_exclude', 'wp_easycart_exclude_from_siteground', 10, 1 );
	}
}

function wp_easycart_check_for_shortcode( $posts ) {
	global $is_wpec_store, $is_wpec_cart, $is_wpec_account, $is_wpec_product_shortcode;
	$is_wpec_store = false;
	$is_wpec_cart = false;
	$is_wpec_account = false;
	$is_wpec_product_shortcode = false;

	if ( empty( $posts ) )
		return $posts;

	$found = false;

	foreach ( $posts as $post ) {
		if ( $post->ID == get_option( 'ec_option_storepage' ) || $post->post_type == "ec_store" ) {
			$found = true;
			$is_wpec_store = true;
			break;
		}
	}

	foreach ( $posts as $post ) {
		if ( stripos( $post->post_content, '[ec_cart' ) !== false ) {
			$is_wpec_cart = true;
			break;
		}
	}

	foreach ( $posts as $post ) {
		if ( stripos( $post->post_content, '[ec_account' ) !== false ) {
			$is_wpec_account = true;
			break;
		}
	}

	foreach ( $posts as $post ) {
		if ( stripos( $post->post_content, '[ec_product' ) !== false ) {
			$is_wpec_product_shortcode = true;
			break;
		}
	}

	if ( $is_wpec_cart || $is_wpec_account ) {
		add_action( 'wp_enqueue_scripts', 'wp_easycart_load_cart_js' );

	} else if ( $is_wpec_store || $is_wpec_product_shortcode ) {
		add_action( 'wp_enqueue_scripts', 'wp_easycart_load_grecaptcha_js' );
	}

	if ( get_option( 'ec_option_amazonpay_enable' ) ) {
		add_action( 'wp_enqueue_scripts', 'wp_easycart_load_amazon_js' );
	}

	if ( $found ) {
		add_filter( 'jetpack_enable_open_graph', '__return_false' ); 
	}

	if ( trim( get_option( 'ec_option_fb_pixel' ) ) != '' ) {
		$found = false;
		foreach ( $posts as $post ) {
			if ( $post->post_type == "ec_store" ||
				stripos( $post->post_content, '[ec_store' ) !== false || 
				stripos( $post->post_content, '[ec_cart' ) !== false 
			) {
				$found = true;
				break;
			}
		}

		if ( $found ) {
			add_action( 'wp_head', 'wp_easycart_init_facebook_pixel' );
		}
	}

	return $posts;
}

function wp_easycart_init_facebook_pixel() {
	echo "<script>
			!function(f,b,e,v,n,t,s) {if (f.fbq)return;n=f.fbq=function() {n.callMethod?
			n.callMethod.apply(n,arguments):n.queue.push(arguments)};if (!f._fbq)f._fbq=n;
			n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
			t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
			document,'script','https://connect.facebook.net/en_US/fbevents.js');
			// Insert Your Custom Audience Pixel ID below. 
			fbq('init', '" . esc_js( get_option( 'ec_option_fb_pixel' ) ) . "');
			fbq('track', 'PageView');
		</script>";
	echo '<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=' . esc_js( get_option( 'ec_option_fb_pixel' ) ) . '&ev=PageView&noscript=1" /></noscript>';
}
add_action( 'the_posts', 'wp_easycart_check_for_shortcode' );

function wp_easycart_restrict_access() {
	$product_restrict = get_post_meta( get_the_ID(), 'wpeasycart_restrict_product_id', true );
	$user_restrict = get_post_meta( get_the_ID(), 'wpeasycart_restrict_user_id', true );
	$role_restrict = get_post_meta( get_the_ID(), 'wpeasycart_restrict_role_id', true );
	$is_product_restricted = $is_user_restricted = $is_role_restricted = $is_restricted = false;

	$redirect_page = get_post_meta( get_the_ID(), 'wpeasycart_restrict_redirect_url', true );
	$redirect_page_auth = get_post_meta( get_the_ID(), 'wpeasycart_restrict_redirect_url_auth', true );
	$redirect_page_not_auth = get_post_meta( get_the_ID(), 'wpeasycart_restrict_redirect_url_not_auth', true );

	$has_redirect = ( $redirect_page != "" || $redirect_page_not_auth != "" || $redirect_page_auth != "" ) ? true : false;
	if ( is_array( $product_restrict ) ) {
		for ( $i=0; $i<count( $product_restrict ); $i++ ) {
			if ( $product_restrict[$i] != '' ) {
				$is_product_restricted = true;
			}
		}
	}
	if ( is_array( $user_restrict ) ) {
		for ( $i=0; $i<count( $user_restrict ); $i++ ) {
			if ( $user_restrict[$i] != '' ) {
				$is_user_restricted = true;
			}
		}
	}
	if ( is_array( $role_restrict ) ) {
		for ( $i=0; $i<count( $role_restrict ); $i++ ) {
			if ( $role_restrict[$i] != '' ) {
				$is_role_restricted = true;
			}
		}
	}

	$is_restricted = ( $is_product_restricted || $is_user_restricted || $is_role_restricted ) ? true : false;
	$is_logged_in = ( ! $GLOBALS['ec_user']->user_id ) ? false : true;

	if ( $has_redirect && $is_restricted ) {

		// Not Logged In, Redirect Out
		if ( $redirect_page != '' && ! $is_logged_in ) {
			wp_redirect( $redirect_page ); die();

		// Not Logged In, But Allowed
		} else if ( !$is_logged_in ) {
			return;

		// Logged In + Content Retriction
		} else {

			$product_restrict_list = $user_restrict_list = $role_restrict_list = '(';
			if ( is_array( $product_restrict ) ) {
				for ( $i=0; $i<count( $product_restrict ); $i++ ) {
					if ( $i>0 ) {
						$product_restrict_list .= ', ';
					}
					$product_restrict_list .= $product_restrict[$i];
				}
				$product_restrict_list .= ')';
			} else {
				$product_restrict_list .= $product_restrict . ')';
			}

			$is_allowed = true;

			if ( ( is_array( $product_restrict ) && count( $product_restrict ) > 0 && $product_restrict[0] != '' && $product_restrict[0] != '0' ) || ( !is_array( $product_restrict ) && $product_restrict != '' ) ) {
				global $wpdb;
				$has_product = false;
				$products = $wpdb->get_results( "SELECT is_subscription_item, product_id FROM ec_product WHERE product_id IN " . $product_restrict_list );
				foreach ( $products as $product ) {
					if ( $product->is_subscription_item ) {
						$active_subscription = $wpdb->get_results( $wpdb->prepare( "SELECT subscription_id FROM ec_subscription WHERE user_id = %d AND product_id = %d AND subscription_status = 'Active'", $GLOBALS['ec_user']->user_id, $product->product_id ) );
						if ( $active_subscription ) {
							$has_product = true;
						}
					} else {
						$order_details = $wpdb->get_results( $wpdb->prepare( "SELECT ec_orderdetail.product_id FROM ec_order, ec_orderdetail, ec_orderstatus WHERE ec_order.user_id = %d AND ec_order.orderstatus_id = ec_orderstatus.status_id AND ec_orderstatus.is_approved = 1 AND ec_order.order_id = ec_orderdetail.order_id AND ec_orderdetail.product_id = %d", $GLOBALS['ec_user']->user_id, $product->product_id ) );
						if ( $order_details ) {
							$has_product = true;
						}
					}
				}
				if ( !$has_product ) {
					$is_allowed = false;
				}
			}

			if ( ( is_array( $user_restrict ) && count( $user_restrict ) > 0 && $user_restrict[0] != '' && $user_restrict[0] != '0' ) || ( !is_array( $user_restrict ) && $user_restrict != '' ) ) {
				$has_user = false;
				if ( is_array( $user_restrict ) && in_array( $GLOBALS['ec_user']->user_id, $user_restrict ) ) {
					$has_user = true;
				} else if ( ! is_array( $user_restrict ) && $GLOBALS['ec_user']->user_id == $user_restrict ) {
					$has_user = true;
				}
				if ( ! $has_user ) {
					$is_allowed = false;
				}
			}

			if ( ( is_array( $role_restrict ) && count( $role_restrict ) > 0 && $role_restrict[0] != '' ) || ( !is_array( $role_restrict ) && $role_restrict != '' ) ) {
				$has_role = false;
				if ( is_array( $role_restrict ) && in_array( $GLOBALS['ec_user']->user_level, $role_restrict ) ) {
					$has_role = true;
				} else if ( !is_array( $role_restrict ) && $role_restrict != $GLOBALS['ec_user']->user_level ) {
					$has_role = true;
				}
				if ( !$has_role ) {
					$is_allowed = false;
				}
			}

			// Check for account or payment type redirect first
			if ( $redirect_page_not_auth != "" || $redirect_page_auth != "" ) {

				// Allowed + Has Logged In Redirect
				if ( $is_allowed && $redirect_page_auth != "" ) {
					wp_redirect( $redirect_page_auth ); die();

				// Not Allowed + No Purchase/Auth Link
				} else if ( !$is_allowed && $redirect_page_not_auth != "" ) { 
					wp_redirect( $redirect_page_not_auth ); die();

				}

			}

			// Not Allowed + Redirect Out Set
			if ( $redirect_page != "" && !$is_allowed ) {
				wp_redirect( $redirect_page ); die();

			}
		}

	}
	return;
}
add_action( 'template_redirect', 'wp_easycart_restrict_access' );

add_action( 'wp_head', 'wp_easycart_show_404_help' );
function wp_easycart_show_404_help( ) {
	// First test for a common issue, possibly fixed here.
	if ( is_404() && get_option( 'ec_option_storepage' ) == get_option( 'page_on_front' ) ) {
		$post = array( 
			'post_content' 	=> "[ec_store]",
			'post_title' 	=> "Store",
			'post_type'		=> "page",
			'post_status'	=> "publish"
		 );
		$post_id = wp_insert_post( $post );
		update_option( 'ec_option_storepage', $post_id );
		flush_rewrite_rules();

	// May times we see the user hit the store page with a 404 and can usually be fixed with a flush.
	} else if ( wp_easycart_404_check() ) {
		echo '<div style="position:relative; top:0; left:0; width:100%; background:red; padding:15px; text-align:center; color:#FFF; font-size:16px; font-weight:bold;">It appears your product is not linking correctly. Refreshing this page may automatically fix the issue, but lots of things can cause this, but we will help you out. Try reading here: <a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=product-404-issues" target="_blank" style="color:#CCC !important;">Help on 404 Errors</a> and if none of these options help, contact us here: <a href="https://www.wpeasycart.com/contact-information/" target="_blank" style="color:#CCC !important;">Contact Us</a>.</div>';
		flush_rewrite_rules();
	}
}
function wp_easycart_404_check() {
	if ( is_404() && ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) && !is_admin() ) {
		$url = str_replace( "https://", "", str_replace( "http://", "", get_site_url() . strtok( sanitize_text_field( $_SERVER["REQUEST_URI"] ), '?' ) ) );
		$store_page_id = get_option( 'ec_option_storepage' );
		$store_page = get_permalink( $store_page_id );
		$store_url = str_replace( "https://", "", str_replace( "http://", "", $store_page ) ); 
		if ( strpos( $url, $store_url ) !== false ) {
			return true;
		}
	}
	return false;
}

function wp_easycart_maybe_add_toolbar_link( $wp_admin_bar ) {

	global $wpdb, $post;
	if ( !is_admin() && isset( $_GET['model_number'] ) ) {
		$product = $wpdb->get_row( $wpdb->prepare( "SELECT product_id FROM ec_product WHERE model_number = %s", sanitize_text_field( $_GET['model_number'] ) ) );
		if ( $product ) {
			$args = array(
				'id' => 'wpeasycart_product',
				'title' => 'Edit Product',
				'href' => get_admin_url() . "admin.php?page=wp-easycart-products&subpage=products&product_id=" . $product->product_id . "&ec_admin_form_action=edit",
				'meta' => array(
					'target' => '_self',
					'class' => 'wp-easycart-toolbar-edit',
					'title' => 'Edit Product'
				)
			);
			$wp_admin_bar->add_node( $args );
		}
	} else if ( !is_admin() && ( $post->post_type == "ec_store" || $post->post_type == "page" ) ) {
		$id = $post->ID;
		$product = $wpdb->get_row( $wpdb->prepare( "SELECT product_id FROM ec_product WHERE post_id = %d", $id ) );
		if ( $product ) {
			$args = array(
				'id' => 'wpeasycart_product',
				'title' => 'Edit Product',
				'href' => get_admin_url() . "admin.php?page=wp-easycart-products&subpage=products&product_id=" . $product->product_id . "&ec_admin_form_action=edit",
				'meta' => array(
					'target' => '_self',
					'class' => 'wp-easycart-toolbar-edit',
					'title' => 'Edit Product'
				)
			);
			$wp_admin_bar->add_node( $args );
		}
	}
}
add_action( 'admin_bar_menu', 'wp_easycart_maybe_add_toolbar_link', 999 );

function wp_easycart_maybe_sync_wordpress_user_pw_update( $user, $new_pass ) {
	if ( apply_filters( 'wp_easycart_sync_wordpress_users', false ) ) {
		if ( $user_id = get_user_meta( $user->ID, 'wpeasycart_user_id', true ) ) {
			global $wpdb;
			$password = md5( $new_pass );
			$password = apply_filters( 'wpeasycart_password_hash', $password, $new_pass );
			$wpdb->query( $wpdb->prepare( "UPDATE ec_user SET password = %s WHERE user_id = %d", $password, $user_id ) );
		}
	}
}

add_action( 'password_reset', 'wp_easycart_maybe_sync_wordpress_user_pw_update', 10, 2 );

function wp_easycart_maybe_sync_new_wordpress_user( $data, $update, $id ) {
	if ( apply_filters( 'wp_easycart_sync_wordpress_users', false ) ) {
		global $wpdb;
		if ( !$update ) {
			$password = md5( $data['user_pass'] );
			$password = apply_filters( 'wpeasycart_password_hash', $password, $data['user_pass'] );
			$wpdb->query( $wpdb->prepare( "INSERT INTO ec_user( email, password ) VALUES( %s, %s )", $data['user_email'], $password ) );
			$user_id = $wpdb->insert_id;
			add_user_meta( $id, 'wpeasycart_user_id', $user_id, true );

		} else {
			if ( $user_id = get_user_meta( $user->ID, 'wpeasycart_user_id', true ) ) {
				$wpdb->query( $wpdb->prepare( "UPDATE ec_user SET email = %s WHERE user_id = %d", $data['user_email'], $user_id ) );
			}
		}
	}
	return $data;
}
add_filter( 'wp_pre_insert_user_data', 'wp_easycart_maybe_sync_new_wordpress_user', 10, 3 );

function wp_easycart_escape_html( $text ) {
	if ( '' == $text ) {
		return $text;
	}
	/* Initial list of tags from https://wp-mix.com/allowed-html-tags-wp_kses/. */
	$allowedposttags = array();
	$allowed_atts = array(
		'align'   => array(),
		'class'   => array(),
		'type'    => array(),
		'id'     => array(),
		'dir'    => array(),
		'lang'    => array(),
		'style'   => array(),
		'xml:lang'  => array(),
		'src'    => array(),
		'alt'    => array(),
		'href'    => array(),
		'rel'    => array(),
		'rev'    => array(),
		'target'   => array(),
		'novalidate' => array(),
		'type'    => array(),
		'value'   => array(),
		'name'    => array(),
		'tabindex'  => array(),
		'action'   => array(),
		'method'   => array(),
		'for'    => array(),
		'width'   => array(),
		'height'   => array(),
		'data'    => array(),
		'title'   => array(),
		'pseudo' => array(),
		'preload' => array(),
		'controls' => array(),
	);
	$allowedposttags['form']   = $allowed_atts;
	$allowedposttags['label']  = $allowed_atts;
	$allowedposttags['input']  = $allowed_atts;
	$allowedposttags['textarea'] = $allowed_atts;
	$allowedposttags['blockquote'] = $allowed_atts;
	$allowedposttags['figure'] = $allowed_atts;
	$allowedposttags['figcaption'] = $allowed_atts;
	$allowedposttags['iframe']  = $allowed_atts;
	$allowedposttags['audio']  = $allowed_atts;
	$allowedposttags['video']  = $allowed_atts;
	$allowedposttags['source']  = $allowed_atts;
	$allowedposttags['style']  = $allowed_atts;
	$allowedposttags['strong']  = $allowed_atts;
	$allowedposttags['small']  = $allowed_atts;
	$allowedposttags['table']  = $allowed_atts;
	$allowedposttags['span']   = $allowed_atts;
	$allowedposttags['abbr']   = $allowed_atts;
	$allowedposttags['code']   = $allowed_atts;
	$allowedposttags['pre']   = $allowed_atts;
	$allowedposttags['div']   = $allowed_atts;
	$allowedposttags['img']   = $allowed_atts;
	$allowedposttags['h1']    = $allowed_atts;
	$allowedposttags['h2']    = $allowed_atts;
	$allowedposttags['h3']    = $allowed_atts;
	$allowedposttags['h4']    = $allowed_atts;
	$allowedposttags['h5']    = $allowed_atts;
	$allowedposttags['h6']    = $allowed_atts;
	$allowedposttags['ol']    = $allowed_atts;
	$allowedposttags['ul']    = $allowed_atts;
	$allowedposttags['li']    = $allowed_atts;
	$allowedposttags['em']    = $allowed_atts;
	$allowedposttags['hr']    = $allowed_atts;
	$allowedposttags['br']    = $allowed_atts;
	$allowedposttags['tr']    = $allowed_atts;
	$allowedposttags['td']    = $allowed_atts;
	$allowedposttags['dl']    = $allowed_atts;
	$allowedposttags['dt']    = $allowed_atts;
	$allowedposttags['p']    = $allowed_atts;
	$allowedposttags['a']    = $allowed_atts;
	$allowedposttags['b']    = $allowed_atts;
	$allowedposttags['i']    = $allowed_atts;
	return wp_kses( $text, $allowedposttags );
}
?>