<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_cart_importer' ) ) :

	final class wp_easycart_admin_cart_importer {

		protected static $_instance = null;

		private $wpdb;

		public $oscommerce_import_file;
		public $woo_import_file;
		public $square_import_file;
		public $settings_file;
		public $shopify_import_file;

		public static function instance( ) {

			if( is_null( self::$_instance ) ) {
				self::$_instance = new self(  );
			}
			return self::$_instance;

		}

		public function __construct( ){
			// Keep reference to wpdb
			global $wpdb;
			$this->wpdb =& $wpdb;

			// Setup File Names 
			$this->oscommerce_import_file	 	= EC_PLUGIN_DIRECTORY . '/admin/template/settings/cart-importer/oscommerce-import.php';
			$this->woo_import_file	 			= EC_PLUGIN_DIRECTORY . '/admin/template/settings/cart-importer/woo-import.php';
			$this->square_import_file	 		= EC_PLUGIN_DIRECTORY . '/admin/template/settings/cart-importer/square-import.php';
			$this->settings_file		 		= EC_PLUGIN_DIRECTORY . '/admin/template/settings/cart-importer/settings.php';
			$this->shopify_import_file	 		= EC_PLUGIN_DIRECTORY . '/admin/template/settings/cart-importer/shopify-import.php';

			// Actions
			add_action( 'wpeasycart_admin_cart_importer', array( $this, 'load_woo_importer' ) );
			add_action( 'wpeasycart_admin_cart_importer', array( $this, 'load_oscommerce_importer' ) );
			add_action( 'wpeasycart_admin_cart_importer', array( $this, 'load_square_importer' ) );
			add_action( 'wpeasycart_admin_cart_importer', array( $this, 'load_shopify_importer' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_woo_import' ) );
		}

		public function load_cart_importer( ){
			include( $this->settings_file );
		}

		public function load_woo_importer( ){
			include( $this->woo_import_file );
		}
		public function load_oscommerce_importer( ){
			include( $this->oscommerce_import_file );
		}

		public function load_square_importer( ){
			include( $this->square_import_file );
		}

		public function load_shopify_importer( ){
			include( $this->shopify_import_file );
		}

		public function process_woo_import() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return false;
			}
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-woo-importer-settings' ) ) {
				return false;
			}

			if ( ! isset( $_POST['ec_admin_form_action'] ) ) {
				return false;
			}

			if ( 'import-woo-products' != $_POST['ec_admin_form_action'] ) {
				return false;
			}

			global $wpdb;
			$prefix = $wpdb->prefix;
			$new_optionsets = array();
			$new_categories = array();
			$new_products = array();
			$add_crosssale = array();

			$optionsets = $wpdb->get_results( 'SELECT * FROM ' . $prefix . 'woocommerce_attribute_taxonomies' );

			foreach ( $optionsets as $optionset ) {
				$attribute_id = $optionset->attribute_id;
				$option_name = $optionset->attribute_name;
				$option_label = $optionset->attribute_label;
				$option_type = $optionset->attribute_type;

				if ( 'select' == $option_type ) {
					$option_type = 'combo';
				}

				$optionitems = $wpdb->get_results( $wpdb->prepare( 'SELECT ' . $prefix . 'terms.* FROM ' . $prefix . 'term_taxonomy LEFT JOIN ' . $prefix . 'terms ON (' . $prefix . 'terms.term_id = ' . $prefix . 'term_taxonomy.term_id ) WHERE ' . $prefix . 'term_taxonomy.taxonomy = %s', 'pa_' . $option_name ) );

				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_option( option_name, option_label, option_type, option_required ) VALUES( %s, %s, %s, 0 )', $option_name, $option_label, $option_type ) );
				$option_id = $wpdb->insert_id;
				$new_optionsets[ 'pa_' . $option_name ] = $option_id;

				$order_num = 0;
				foreach ( $optionitems as $optionitem ) {
					$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_optionitem( option_id, optionitem_name, optionitem_order ) VALUES( %d, %s, %d )', $option_id, $optionitem->name, $order_num ) );
					$order_num++;
				}
			}

			$categories = $wpdb->get_results( 'SELECT ' . $prefix . 'terms.* FROM ' . $prefix . 'term_taxonomy LEFT JOIN ' . $prefix . 'terms ON (' . $prefix . 'terms.term_id = ' . $prefix . 'term_taxonomy.term_id ) WHERE ' . $prefix . 'term_taxonomy.taxonomy = "product_cat"' );

			foreach ( $categories as $category ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_category( category_name ) VALUES( %s )', $category->name ) );
				$category_id = $wpdb->insert_id;
				$new_categories[ 'id-' . $category->term_id ] = $category_id;

				$post = array(
					'post_content' => '[ec_store groupid="' . $category_id . '"]',
					'post_status' => 'publish',
					'post_title' => $category->name,
					'post_type' => 'ec_store'
				);
				$post_id = wp_insert_post( $post );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_category SET ec_category.post_id = %s WHERE ec_category.category_id = %d', $post_id, $category_id ) );
			}

			$wpdb->query( 'INSERT INTO ec_manufacturer( `name` ) VALUES( "Woo Products" )' );
			$manufacturer_id = $wpdb->insert_id;

			$post = array(
				'post_content' => '[ec_store manufacturerid="' . $manufacturer_id . '"]',
				'post_status' => 'publish',
				'post_title' => 'WOO Products',
				'post_type' => 'ec_store'
			);
			$post_id = wp_insert_post( $post );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_manufacturer SET ec_manufacturer.post_id = %s WHERE ec_manufacturer.manufacturer_id = %d', $post_id, $manufacturer_id ) );

			$product_args = array(
				'posts_per_page' => 100000,
				'offset' => 0,
				'post_type' => 'product'
			);
			$woo_products = get_posts( $product_args );

			foreach ( $woo_products as $product ) {
				$post_meta = get_post_meta( $product->ID );

				$sku = $post_meta['_sku'][0];
				$model_number = ( '' == $sku ) ? rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 ) : $sku;
				$title = $product->post_title;
				$description = $product->post_content;
				$short_description = $product->post_excerpt;

				$visibility = $post_meta['_visibility'][0];
				$is_active = ( 'publish' == $product->post_status ) ? true : false;
				$activate_in_store = ( $is_active && 'visible' == $visibility ) ? true : false;

				$regular_price = $post_meta['_regular_price'][0];
				$sale_price = $post_meta['_sale_price'][0];
				$price = $post_meta['_price'][0];
				if ( $sale_price != "" ) {
					$price = $sale_price;
					$list_price = $regular_price;
				}

				$tax_status = $post_meta['_tax_status'][0];
				$is_taxable = ( $tax_status == "taxable" ) ? true : false;

				$manage_stock = $post_meta['_manage_stock'][0];
				$stock_status = $post_meta['_stock_status'][0];
				$stock = $post_meta['_stock'][0];
				if ( 'yes' == $manage_stock && '' != $stock ) {
					$stock_quantity = $stock;
				} else if ( 'instock' == $stock_status ) {
					$stock_quantity = 9999;
				} else {
					$stock_quantity = 0;
				}
				$show_stock_quantity = ( 'yes' == $manage_stock ) ? true : false;

				$virtual = $post_meta['_virtual'][0];
				$weight = $post_meta['_weight'][0];
				if ( '' == $weight || 'yes' == $virtual ) {
					$weight = 0;
				}
				$length = $post_meta['_length'][0];
				if ( '' == $length || 'yes' == $virtual ) {
					$length = 0;
				}
				$width = $post_meta['_width'][0];
				if ( '' == $width || 'yes' == $virtual ) {
					$width = 0;
				}
				$height = $post_meta['_height'][0];
				if ( '' == $height || 'yes' == $virtual ) {
					$height = 0;
				}

				$use_customer_reviews = ( 'open' == $product->comment_status ) ? true : false;
				$reviews = get_comments( array( 'post_id' => $product->ID ) );

				$downloadable = $post_meta['_downloadable'][0]; // no if not downloadable
				if ( 'yes' == $downloadable ) {
					$files = maybe_unserialize( $post_meta['_downloadable_files'][0] );
					foreach ( $files as $file ) {
						break;
					}

					$path = pathinfo( $file['file'] );
					$file_name = $path['filename'] . '_' . rand( 100000, 999999 ) . '.' . $path['extension'];
					copy( $file['file'], EC_PLUGIN_DATA_DIRECTORY . '/products/downloads/' . $file_name );

					$is_download = true;
					$download_file_name = $file_name;
					$maximum_downloads_allowed = $post_meta['_download_limit'][0];
					$download_timelimit_seconds = $post_meta['_download_expiry'][0] * 24 * 60 * 60;
				} else {
					$is_download = false;
					$download_file_name = '';
					$maximum_downloads_allowed = 0;
					$download_timelimit_seconds = 0;
				}

				$image1 = wp_get_attachment_url( get_post_thumbnail_id( $product->ID ) );
				$image2 = '';
				$image3 = '';
				$image4 = '';
				$image5 = '';

				$gallery_images_string = $post_meta['_product_image_gallery'][0];
				$gallery_images_array = explode( ',', $gallery_images_string );
				if ( '' != $gallery_images_array[0] ) {
					$product_images = array();
					foreach ( $gallery_images_array as $gallery_item ) {
						$product_images[] = wp_get_attachment_url( $gallery_item );
					}

					for ( $i = 0; $i < count( $product_images ) && $i < 5; $i++ ) {
						if ( 0 == $i ) {
							$image1 = $product_images[ $i ];
						} else if( 1 == $i ) {
							$image2 = $product_images[ $i ];
						} else if( 2 == $i ) {
							$image3 = $product_images[ $i ];
						} else if( 3 == $i ) {
							$image4 = $product_images[ $i ];
						} else if( 4 == $i ) {
							$image5 = $product_images[ $i ];
						}
					}
				}

				$product_attributes = maybe_unserialize( $post_meta['_product_attributes'][0] );
				$product_options = array();
				foreach ( $product_attributes as $key => $value ) {
					$product_options[] = $new_optionsets[ $key ];
				}

				$product_cats = $wpdb->get_results( $wpdb->prepare( 'SELECT ' . $prefix . 'term_relationships.term_taxonomy_id FROM ' . $prefix . 'term_relationships, ' . $prefix . 'terms, ' . $prefix . 'term_taxonomy WHERE ' . $prefix . 'term_taxonomy.taxonomy = "product_cat" AND ' . $prefix . 'term_taxonomy.term_id = ' . $prefix . 'terms.term_id AND ' . $prefix . 'terms.term_id = ' . $prefix . 'term_relationships.term_taxonomy_id AND ' . $prefix . 'term_relationships.object_id = %d', $product->ID ) );

				$product_categories = array();
				foreach ( $product_cats as $value ) {
					$product_categories[] = $new_categories[ 'id-' . $value->term_taxonomy_id ];
				}

				$crosssell_ids = maybe_unserialize( $post_meta['_crosssell_ids'][0] );
				$show_on_startup = true;
				$is_shippable = true;
				if( $is_download || $weight <= 0 ) {
					$is_shippable = false;
				}

				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_product( model_number, activate_in_store, title, description, price, list_price, stock_quantity, weight, width, height, length, use_customer_reviews, manufacturer_id, download_file_name, image1, image2, image3, image4, image5,  use_advanced_optionset, featured_product_id_1, featured_product_id_2, featured_product_id_3, featured_product_id_4, is_download, is_taxable, is_shippable, show_on_startup, show_stock_quantity, maximum_downloads_allowed, download_timelimit_seconds ) VALUES( %s, %d, %s, %s, %s, %s, %d, %s, %s, %s, %s, %d, %d, %s, %s, %s, %s, %s, %s, 1, %d, %d, %d, %d, %d, %d, %d, %d, %d, %s, %s )', $model_number, $activate_in_store, $title, $description, $price, $list_price, $stock_quantity, $weight, $width, $height, $length, $use_customer_reviews, $manufacturer_id, $download_file_name, $image1, $image2, $image3, $image4, $image5, $featured_id_1, $featured_id_2, $featured_id_3, $featured_id_4, $is_download, $is_taxable, $is_shippable, $show_on_startup, $show_stock_quantity, $maximum_downloads_allowed, $download_timelimit_seconds ) );
				$product_id = $wpdb->insert_id;
				$new_products[ 'id-' . $product->ID ] = $product_id;
				if ( $crosssell_ids ) {
					$add_crosssale[ 'id-' . $product_id ] = $crosssell_ids;
				}

				$status = ( $activate_in_store ) ? 'publish' : 'private';
				$post = array(
					'post_content' => '[ec_store modelnumber="' . $model_number . '"]',
					'post_status' => $status,
					'post_title' => $title,
					'post_type' => 'ec_store'
				);
				$post_id = wp_insert_post( $post );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET ec_product.post_id = %s WHERE ec_product.product_id = %d', $post_id, $product_id ) );

				foreach ( $product_options as $option_id ) {
					$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_option_to_product( option_id, product_id ) VALUES( %d, %d )', $option_id, $product_id ) );
				}

				foreach ( $product_categories as $category_id ) {
					$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_categoryitem( category_id, product_id ) VALUES( %d, %d )', $category_id, $product_id ) );
				}

				foreach ( $reviews as $review ) {
					$approved = $review->comment_approved;
					$rating = get_comment_meta( $review->comment_ID, 'rating', true );
					$comment_title = '';
					$comment_description = $review->comment_content;
					$date_submitted = $review->comment_date;
					$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_review( product_id, approved, rating, title, description, date_submitted ) VALUES( %d, %d, %d, %s, %s, %s )', $product_id, $approved, $rating, $comment_title, $comment_description, $date_submitted ) );
				}

			}

			foreach ( $add_crosssale as $key => $value ) {
				$product_id = substr( $key, 3 );
				$featured_ids = array();
				foreach ( $value as $the_post_id ) {
					$featured_ids[] = $new_products[ 'id-' . $the_post_id ];
				}
				$featured_id_1 = 0;
				if ( count( $featured_ids ) > 0 ) {
					$featured_id_1 = $featured_ids[0];
				}
				$featured_id_2 = 0;
				if ( count( $featured_ids ) > 1 ) {
					$featured_id_2 = $featured_ids[1];
				}
				$featured_id_3 = 0;
				if ( count( $featured_ids ) > 2 ) {
					$featured_id_3 = $featured_ids[2];
				}
				$featured_id_4 = 0;
				if ( count( $featured_ids ) > 3 ) {
					$featured_id_4 = $featured_ids[3];
				}
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET ec_product.featured_product_id_1 = %d, ec_product.featured_product_id_2 = %d, ec_product.featured_product_id_3 = %d, ec_product.featured_product_id_4 = %d WHERE ec_product.product_id = %d', $featured_id_1, $featured_id_2, $featured_id_3, $featured_id_4, $product_id ) );
			}

			wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=cart-importer&ec_success=woo-imported' );
			die();
		}

		public function square_import_modifiers( $cursor, $curr_count ){ // These are our option items
			$square = new ec_square( );
			$response = $square->get_modifiers( $cursor );

			foreach( $response->objects as $object ){
				$square->insert_option_item( $object, (bool) $_POST['sync_modifiers'] );
				$curr_count++;
			}
			if( $response->cursor ){
				echo json_encode( array(
					'has_more'      => true,
					'cursor'        => $response->cursor,
					'curr_count'    => $curr_count
				) );
			}else{
				echo json_encode( array(
					'has_more'      => false,
					'curr_count'    => $curr_count
				) );
			}
		}

		public function square_import_modifier_items( $cursor, $curr_count ){ // Note, these are our options
			$square = new ec_square( );
			$response = $square->get_modifier_items( $cursor );

			if ( isset( $response->objects ) ) {
				foreach( $response->objects as $object ){
					 $square->insert_option( $object, (bool) $_POST['sync_modifiers'] );
					 $curr_count++;
				}
			}
			if ( isset( $response->cursor ) && $response->cursor ) {
				echo json_encode( array(
					'has_more'      => true,
					'cursor'        => $response->cursor,
					'curr_count'    => $curr_count
				) );
			} else {
				echo json_encode( array(
					'has_more'      => false,
					'curr_count'    => $curr_count
				) );
			}
		}

		public function square_import_categories( $cursor, $curr_count ){
			$square = new ec_square( );
			$response = $square->get_catalog( false, 0, $types = array( 'CATEGORY' ) );

			if ( isset( $response->objects ) ) {
				foreach( $response->objects as $object ){
					$square->insert_category( $object );
					$curr_count++;
				}
			}
			
			echo json_encode( array(
				'has_more'      => false,
				'curr_count'    => $curr_count
			) );
		}
		
		public function square_sync_catalog_items( $cursor, $curr_count ) {
			$square = new ec_square( );
			$response = $square->get_catalog( $cursor, 0, array( 'ITEM' ) );
			
			foreach( $response->objects as $object ){
				$square->insert_product( $object );
				$curr_count++;
			}
			if ( isset( $response->cursor ) && $response->cursor ) {
				echo json_encode( array(
					'has_more'      => true,
					'cursor'        => $response->cursor,
					'curr_count'    => $curr_count
				) );
			} else {
				echo json_encode( array(
					'has_more'      => false,
					'curr_count'    => $curr_count
				) );
			}
		}
		
		public function square_sync_inventory_items( $cursor, $curr_count ) {
			$square = new ec_square( );
			$response = $square->get_inventory_results( $cursor );
			
			if ( isset( $response->counts ) ) {
				foreach( $response->counts as $object ){
					$this->update_inventory( $object );
					$curr_count++;
				}
			}
			if ( isset( $response->cursor ) && $response->cursor ) {
				echo json_encode( array(
					'has_more'      => true,
					'cursor'        => $response->cursor,
					'curr_count'    => $curr_count
				) );
			} else {
				echo json_encode( array(
					'has_more'      => false,
					'curr_count'    => $curr_count
				) );
			}
		}

		private function update_inventory( $object ) {
			global $wpdb;
			if ( 'ITEM_VARIATION' == $object->catalog_object_type && 'IN_STOCK' == $object->state ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_optionitemquantity SET quantity = %d WHERE square_id = %s', $object->quantity, $object->catalog_object_id ) );
				$found_optionitem = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_optionitemquantity WHERE square_id = %s', $object->catalog_object_id ) );
				if ( $found_optionitem ) {
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET ec_product.stock_quantity = ( SELECT SUM( ec_optionitemquantity.quantity ) FROM ec_optionitemquantity WHERE ec_optionitemquantity.product_id = ec_product.product_id ) WHERE ec_product.product_id = %d', $found_optionitem->product_id ) );
				}
			} else if ( 'IN_STOCK' == $object->state ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET stock_quantity = %d WHERE square_id = %s', $object->quantity, $object->catalog_object_id ) );
			}
		}

		public function square_import( $cursor, $curr_count ){
			$square = new ec_square( );
			$response = $square->get_catalog( $cursor );

			foreach( $response->objects as $object ){
				if( $object->type == "CATEGORY" ){
					$square->insert_category( $object, (bool) $_POST['sync_products'] );

				}else if( $object->type == "ITEM" ){
					$square->insert_product( $object, (bool) $_POST['sync_products'], (bool) $_POST['sync_inventory'] );
					$curr_count++;

				}
			}
			if( isset( $response->cursor ) && $response->cursor ){
				echo json_encode( array(
					'has_more'      => true,
					'cursor'        => $response->cursor,
					'curr_count'    => $curr_count
				) );
			} else {
				echo json_encode( array(
					'has_more'      => false,
					'curr_count'    => $curr_count
				) );
				flush_rewrite_rules();
			}
		}
	}
endif;

function wp_easycart_admin_cart_importer( ){
	return wp_easycart_admin_cart_importer::instance( );
}
wp_easycart_admin_cart_importer( );

add_action( 'wp_ajax_ec_admin_ajax_square_modifier_import', 'ec_admin_ajax_square_modifier_import' );
function ec_admin_ajax_square_modifier_import( ){
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-cart-importer' ) ) {
		return false;
	}

	wp_easycart_admin_cart_importer( )->square_import_modifiers( sanitize_text_field( wp_unslash( $_POST['cursor'] ) ), (int) $_POST['curr_count'] );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_square_modifier_items_import', 'ec_admin_ajax_square_modifier_items_import' );
function ec_admin_ajax_square_modifier_items_import( ){
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-cart-importer' ) ) {
		return false;
	}

	wp_easycart_admin_cart_importer( )->square_import_modifier_items( sanitize_text_field( wp_unslash( $_POST['cursor'] ) ), (int) $_POST['curr_count'] );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_square_categories_import', 'ec_admin_ajax_square_categories_import' );
function ec_admin_ajax_square_categories_import( ){
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-cart-importer' ) ) {
		return false;
	}

	wp_easycart_admin_cart_importer( )->square_import_categories( sanitize_text_field( wp_unslash( $_POST['cursor'] ) ), (int) $_POST['curr_count'] );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_square_sync_catalog', 'ec_admin_ajax_square_sync_catalog' );
function ec_admin_ajax_square_sync_catalog( ){
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-cart-importer' ) ) {
		return false;
	}

	wp_easycart_admin_cart_importer( )->square_sync_catalog_items( sanitize_text_field( wp_unslash( $_POST['cursor'] ) ), (int) $_POST['curr_count'] );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_square_sync_inventory', 'ec_admin_ajax_square_sync_inventory' );
function ec_admin_ajax_square_sync_inventory( ){
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-cart-importer' ) ) {
		return false;
	}

	wp_easycart_admin_cart_importer( )->square_sync_inventory_items( sanitize_text_field( wp_unslash( $_POST['cursor'] ) ), (int) $_POST['curr_count'] );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_square_import', 'ec_admin_ajax_square_import' );
function ec_admin_ajax_square_import( ){
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-cart-importer' ) ) {
		return false;
	}

	wp_easycart_admin_cart_importer( )->square_import( sanitize_text_field( wp_unslash( $_POST['cursor'] ) ), (int) $_POST['curr_count'] );
	die( );
}
