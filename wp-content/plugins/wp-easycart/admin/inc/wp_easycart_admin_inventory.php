<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_inventory' ) ) :

	final class wp_easycart_admin_inventory {

		protected static $_instance = null;
		public $inventory_list_file;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			$this->inventory_list_file = EC_PLUGIN_DIRECTORY . '/admin/template/products/inventory/inventory-list.php';
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_export_inventory' ) );
		}

		public function process_export_inventory() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['subpage'] ) && 'inventory' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'export-inventory-list' == $_GET['ec_admin_form_action'] ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-export-inventory' ) ) {
					$this->export_inventory_list();
				}
			}
		}

		public function load_inventory_list() {
			include( $this->inventory_list_file );
		}

		public function export_inventory_list() {
			header( 'Content-Type: text/csv; charset=utf-8' );
			header( 'Content-Disposition: attachment; filename=Inventory-Export_' . date( 'Y-m-d' ) . '.csv' );

			$output = fopen( 'php://output', 'w' );

			fputcsv( $output, array( __( 'Title (Options)', 'wp-easycart' ), __( 'Quantity', 'wp-easycart' ) ) );

			global $wpdb;
			$products = $wpdb->get_results( 'SELECT ec_product.product_id, ec_product.title, ec_product.model_number, ec_product.stock_quantity, ec_product.use_optionitem_quantity_tracking, ec_product.show_stock_quantity, ec_product.option_id_1, ec_product.option_id_2, ec_product.option_id_3, ec_product.option_id_4, ec_product.option_id_5 FROM ec_product WHERE ec_product.activate_in_store = 1 ORDER BY ec_product.title ASC' );

			$inventory_cvs = '';

			foreach ( $products as $product ) {
				if ( $product->use_optionitem_quantity_tracking ) {
					$sql = '';
					if ( 0 != $product->option_id_1 ) {
						$sql .= $wpdb->prepare( 'SELECT * FROM ( SELECT optionitem_name AS optname1, optionitem_id as optid1 FROM ec_optionitem WHERE option_id = %d ) as optionitems1 ', $product->option_id_1 );
					}
					if ( 0 != $product->option_id_2 ) {
						$sql .= $wpdb->prepare( ' JOIN ( SELECT optionitem_name AS optname2, optionitem_id as optid2 FROM ec_optionitem WHERE option_id = %d ) as optionitems2 ON (1=1) ', $product->option_id_2 );
					}
					if ( 0 != $product->option_id_3 ) {
						$sql .= $wpdb->prepare( ' JOIN ( SELECT optionitem_name AS optname3, optionitem_id as optid3 FROM ec_optionitem WHERE option_id = %d ) as optionitems3 ON (1=1) ', $product->option_id_3 );
					}
					if ( 0 != $product->option_id_4 ) {
						$sql .= $wpdb->prepare( ' JOIN ( SELECT optionitem_name AS optname4, optionitem_id as optid4 FROM ec_optionitem WHERE option_id = %d ) as optionitems4 ON (1=1) ', $product->option_id_4 );
					}
					if ( 0 != $product->option_id_5 ) {
						$sql .= $wpdb->prepare( ' JOIN ( SELECT optionitem_name AS optname5, optionitem_id as optid5 FROM ec_optionitem WHERE option_id = %s ) as optionitems5 ON (1=1) ', $product->option_id_5 );
					}
					$sql .= ' LEFT JOIN ec_optionitemquantity ON ( 1=1 ';
					if ( 0 != $product->option_id_1 ) {
						$sql .= ' AND ec_optionitemquantity.optionitem_id_1 = optid1';
					}
					if ( 0 != $product->option_id_2 ) {
						$sql .= ' AND ec_optionitemquantity.optionitem_id_2 = optid2';
					}
					if ( 0 != $product->option_id_3 ) {
						$sql .= ' AND ec_optionitemquantity.optionitem_id_3 = optid3';
					}
					if ( 0 != $product->option_id_4 ) {
						$sql .= ' AND ec_optionitemquantity.optionitem_id_4 = optid4';
					}
					if ( 0 != $product->option_id_5 ) {
						$sql .= ' AND ec_optionitemquantity.optionitem_id_5 = optid5';
					}
					$sql .= $wpdb->prepare( ' AND ec_optionitemquantity.product_id = %d )', $product->product_id );
					$sql .= ' ORDER BY optname1';

					$optionitems = $wpdb->get_results( $sql );
					foreach ( $optionitems as $optionitem ) {
						$opt_title = $product->title . ' (';
						if ( 0 != $optionitem->optionitem_id_1 ) {
							$opt_title .= $optionitem->optname1;
						}
						if ( 0 != $optionitem->optionitem_id_2 ) {
							$opt_title .= ', ' . $optionitem->optname2;
						}
						if ( 0 != $optionitem->optionitem_id_3 ) {
							$opt_title .= ', ' . $optionitem->optname3;
						}
						if ( 0 != $optionitem->optionitem_id_4 ) {
							$opt_title .= ', ' . $optionitem->optname4;
						}
						if ( 0 != $optionitem->optionitem_id_5 ) {
							$opt_title .= ', ' . $optionitem->optname5;
						}
						$opt_title .= ')';
						fputcsv( $output, array( $opt_title, $optionitem->quantity ) );
					}
				} else if ( $product->show_stock_quantity ) {
					fputcsv( $output, array( $product->title, $product->stock_quantity ) );
				} else {
					fputcsv( $output, array( $product->title, '' ) );
				}
			}
			die();
		}
		
		public function update_inventory_item() {
			$product_id = (int) $_POST['product_id'];
			$optionitemquantity_id = (int) $_POST['quantity_id'];
			$quantity = (int) $_POST['quantity'];
			
			global $wpdb;
			if ( -1 != $optionitemquantity_id ) {
				echo $wpdb->prepare( 'UPDATE ec_optionitemquantity SET quantity = %d WHERE optionitemquantity_id = %d AND product_id = %d', $quantity, $optionitemquantity_id, $product_id );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_optionitemquantity SET quantity = %d WHERE optionitemquantity_id = %d AND product_id = %d', $quantity, $optionitemquantity_id, $product_id ) );
			} else {
				echo $wpdb->prepare( 'UPDATE ec_product SET stock_quantity = %d WHERE product_id = %d', $quantity, $product_id );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET stock_quantity = %d WHERE product_id = %d', $quantity, $product_id ) );
			}
		}
	}
endif;

function wp_easycart_admin_inventory() {
	return wp_easycart_admin_inventory::instance();
}
wp_easycart_admin_inventory();

add_action( 'wp_ajax_ec_admin_ajax_update_inventory_item', 'ec_admin_ajax_update_inventory_item' );
function ec_admin_ajax_update_inventory_item() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-inventory' ) ) {
		return false;
	}

	wp_easycart_admin_inventory()->update_inventory_item();
	die();
}
