<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_api_routes' ) ) :

	final class wp_easycart_admin_api_routes {

		protected static $_instance = null;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'register_rest_api' ) );
		}

		public function register_rest_api() {
			register_rest_route(
				'wp-easycart/v1',
				'/easycart_product/',
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'wp_eayscart_ajax_select_product' ),
					'permission_callback' => '__return_true',
				),
				true
			);
			register_rest_route(
				'wp-easycart/v1',
				'/easycart_product_cat/',
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'wp_eayscart_ajax_select_product_cat' ),
					'permission_callback' => '__return_true',
				),
				true
			);
			register_rest_route(
				'wp-easycart/v1',
				'/easycart_product_brand/',
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'wp_eayscart_ajax_select_product_brand' ),
					'permission_callback' => '__return_true',
				),
				true
			);
			register_rest_route(
				'wp-easycart/v1',
				'/categories/',
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'wp_eayscart_ajax_select_categories' ),
					'permission_callback' => '__return_true',
				),
				true
			);
			register_rest_route(
				'wp-easycart/v1',
				'/easycart_product_optionsets/',
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'wp_eayscart_ajax_select_optionsets' ),
					'permission_callback' => '__return_true',
				),
				true
			);
			register_rest_route(
				'wp-easycart/v1',
				'/products/categories/',
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'wp_eayscart_ajax_select_products_by_categories' ),
					'permission_callback' => '__return_true',
				),
				true
			);
		}

		public function wp_eayscart_ajax_select_product() {
			global $wpdb;

			$sql = 'SELECT ec_product.product_id AS id, ec_product.title AS text FROM ec_product WHERE 1=1';

			if ( isset( $_GET['ids'] ) ) {
				$sql .= ' AND ec_product.product_id IN (';
				if ( ! is_array( $_GET['ids'] ) ){
					if ( sanitize_text_field( wp_unslash( $_GET['ids'] ) ) == '' ){
						return ['results' => []];
					}
					$ids = explode( ',', sanitize_text_field( wp_unslash( $_GET['ids'] ) ) );
				}else{
					$ids = (array) $_GET['ids']; // XSS OK. Forced array and each item sanitized.
				}
				$ids_count = count( $ids );
				for ( $i = 0; $i < $ids_count; $i++ ){
					if ( $i > 0 )
						$sql .= ',';
					$sql .= (int) $ids[$i];
				}
				$sql .= ')';
			}
			if ( isset( $_GET['s'] ) && '' != $_GET['s'] ) {
				$sql .= $wpdb->prepare( ' AND ec_product.title LIKE %s', '%' . sanitize_text_field( wp_unslash( $_GET['s'] ) ) . '%' );
			}

			$sql .= ' ORDER BY ec_product.title ASC';
			if ( isset( $_GET['page'] ) || isset( $_GET['perpage'] ) ) {
				$page = ( isset( $_GET['page'] ) ) ? (int) $_GET['page'] : 1;
				$perpage = ( isset( $_GET['perpage'] ) && (int) $_GET['perpage'] > 0 ) ? (int) $_GET['perpage'] : (int) $_GET['perpage'];
				$start_record = $perpage * ( $page - 1 );
				$sql .= ' LIMIT ' . $start_record . ', ' . $perpage;
			}

			$products = $wpdb->get_results( $sql );
			return array( 'results' => $products );
		}

		public function wp_eayscart_ajax_select_product_cat( ){
			global $wpdb;

			$sql = 'SELECT ec_category.category_id AS id, ec_category.category_name AS text FROM ec_category WHERE 1=1';

			if ( isset( $_GET['ids'] ) ) {
				$sql .= ' AND ec_category.category_id IN (';
				if ( ! is_array( $_GET['ids'] ) ) {
					if ( sanitize_text_field( wp_unslash( $_GET['ids'] ) ) == '' ){
						return ['results' => []];
					}
					$ids = explode( ',', sanitize_text_field( wp_unslash( $_GET['ids'] ) ) );
				} else {
					$ids = (array) $_GET['ids']; // XSS OK. Forced array and each item sanitized.
				}
				$ids_count = count( $ids );
				for ( $i = 0; $i < $ids_count; $i++ ) {
					if ( $i > 0 ) {
						$sql .= ',';
					}
					$sql .= (int) $ids[$i];
				}
				$sql .= ')';
			}
			if ( isset( $_GET['s'] ) && '' != $_GET['s'] ) {
				$sql .= $wpdb->prepare( ' AND ec_category.category_name LIKE %s', '%' . sanitize_text_field( wp_unslash( $_GET['s'] ) ) . '%' );
			}

			$sql .= ' ORDER BY ec_category.priority DESC LIMIT 15';

			$products = $wpdb->get_results( $sql );
			return array( 'results' => $products );

		}

		public function wp_eayscart_ajax_select_product_brand( ){
			global $wpdb;

			$sql = 'SELECT ec_manufacturer.manufacturer_id AS id, ec_manufacturer.`name` AS text FROM ec_manufacturer WHERE 1=1';

			if ( isset( $_GET['ids'] ) ) {
				$sql .= ' AND ec_manufacturer.manufacturer_id IN (';
				if ( ! is_array( $_GET['ids'] ) ) {
					if ( sanitize_text_field( wp_unslash( $_GET['ids'] ) ) == '' ){
						return ['results' => []];
					}
					$ids = explode( ',', sanitize_text_field( wp_unslash( $_GET['ids'] ) ) );
				} else {
					$ids = (array) $_GET['ids']; // XSS OK. Forced array and each item sanitized.
				}
				$ids_count = count( $ids );
				for ( $i = 0; $i < $ids_count; $i++ ) {
					if ( $i > 0 ) {
						$sql .= ',';
					}
					$sql .= (int) $ids[$i];
				}
				$sql .= ')';
			}
			if ( isset( $_GET['s'] ) && '' != $_GET['s'] ) {
				$sql .= $wpdb->prepare( ' AND ec_manufacturer.`name` LIKE %s', '%' . sanitize_text_field( wp_unslash( $_GET['s'] ) ) . '%' );
			}

			$sql .= ' ORDER BY ec_manufacturer.`name` ASC LIMIT 15';

			$products = $wpdb->get_results( $sql );
			return array( 'results' => $products );

		}

		public function wp_eayscart_ajax_select_categories( ){
			global $wpdb;

			$sql = 'SELECT ec_category.category_id, ec_category.category_name, ( SELECT COUNT( ec_categoryitem.category_id ) FROM ec_categoryitem WHERE ec_categoryitem.category_id = ec_category.category_id) AS total_products FROM ec_category WHERE 1=1';

			if ( isset( $_GET['ids'] ) ) {
				$sql .= ' AND ec_category.category_id IN (';
				if ( ! is_array( $_GET['ids'] ) ) {
					if ( sanitize_text_field( wp_unslash( $_GET['ids'] ) ) == '' ) {
						return ['results' => []];
					}
					$ids = explode( ',', sanitize_text_field( wp_unslash( $_GET['ids'] ) ) );
				} else {
					$ids = (array) $_GET['ids']; // XSS OK. Forced array and each item sanitized.
				}
				$ids_count = count( $ids );
				for ( $i = 0; $i < $ids_count; $i++ ) {
					if ( $i > 0 ) {
						$sql .= ',';
					}
					$sql .= (int) $ids[$i];
				}
				$sql .= ')';
			}
			if ( isset( $_GET['s'] ) && '' != $_GET['s'] ) {
				$sql .= $wpdb->prepare( ' AND ec_category.category_name LIKE %s', '%' . sanitize_text_field( wp_unslash( $_GET['s'] ) ) . '%' );
			}

			$sql .= ' ORDER BY ec_category.category_name ASC LIMIT 15';

			$categories = $wpdb->get_results( $sql );
			return array( 'results' => $categories );
		}

		public function wp_eayscart_ajax_select_optionsets( ){
			global $wpdb;

			$sql = 'SELECT ec_option.option_id AS id, ec_option.option_name AS text FROM ec_option WHERE 1=1';

			if ( isset( $_GET['ids'] ) ) {
				$sql .= ' AND ec_option.option_id IN (';
				if ( ! is_array( $_GET['ids'] ) ) {
					if ( sanitize_text_field( wp_unslash( $_GET['ids'] ) ) == '' ) {
						return ['results' => []];
					}
					$ids = explode( ',', sanitize_text_field( wp_unslash( $_GET['ids'] ) ) );
				} else {
					$ids = (array) $_GET['ids']; // XSS OK. Forced array and each item sanitized.
				}
				$ids_count = count( $ids );
				for ( $i = 0; $i < $ids_count; $i++ ) {
					if ( $i > 0 ) {
						$sql .= ',';
					}
					$sql .= (int) $ids[$i];
				}
				$sql .= ')';
			}
			if ( isset( $_GET['s'] ) && '' != $_GET['s'] ) {
				$sql .= $wpdb->prepare( ' AND ec_option.option_name LIKE %s', '%' . sanitize_text_field( wp_unslash( $_GET['s'] ) ) . '%' );
			}

			$sql .= ' ORDER BY ec_option.option_name ASC LIMIT 30';

			$options = $wpdb->get_results( $sql );
			return array( 'results' => $options );
		}

		public function wp_eayscart_ajax_select_products_by_categories( ){

			global $wpdb;

			$sql = 'SELECT DISTINCT ec_product.product_id, ec_product.title, ec_product.image1, ec_product.image2, ec_product.image3, ec_product.image4, ec_product.image5, ec_product.product_images, ec_product.price, ec_product.list_price FROM ec_categoryitem, ec_product WHERE ec_categoryitem.product_id = ec_product.product_id';

			if ( isset( $_GET['ids'] ) ) {
				$sql .= ' AND ec_categoryitem.category_id IN (';
				if ( ! is_array( $_GET['ids'] ) ) {
					if ( sanitize_text_field( wp_unslash( $_GET['ids'] ) ) == '' ) {
						return ['results' => []];
					}
					$ids = explode( ',', sanitize_text_field( wp_unslash( $_GET['ids'] ) ) );
				} else {
					$ids = (array) $_GET['ids']; // XSS OK. Forced array and each item sanitized.
				}
				$ids_count = count( $ids );
				for ( $i = 0; $i < $ids_count; $i++ ) {
					if ( $i > 0 ) {
						$sql .= ',';
					}
					$sql .= (int) $ids[$i];
				}
				$sql .= ')';
			}

			if ( isset( $_GET['page'] ) || isset( $_GET['perpage'] ) ) {
				$page = ( isset( $_GET['page'] ) ) ? (int) $_GET['page'] : 1;
				$perpage = ( isset( $_GET['perpage'] ) && (int) $_GET['perpage'] > 0 ) ? (int) $_GET['perpage'] : (int) $_GET['perpage'];
				$start_record = $perpage * ( $page - 1 );
				$sql .= ' LIMIT ' . $start_record . ', ' . $perpage;
			} else {
				$sql .= ' LIMIT 25';
			}

			$products = $wpdb->get_results( $sql );
			$product_count = count( $products );
			for ( $i = 0; $i < $product_count; $i++  ) {
				$products[$i]->price = $GLOBALS['currency']->get_currency_display( $products[$i]->price );
				if( substr( $products[$i]->image1, 0, 7 ) == 'http://' || substr( $products[$i]->image1, 0, 8 ) == 'https://' ){
					$products[$i]->first_image = esc_attr( $products[$i]->image1 );
				}else{
					$products[$i]->first_image = esc_attr( plugins_url( "/wp-easycart-data/products/pics1/" . $products[$i]->image1, EC_PLUGIN_DATA_DIRECTORY ) );
				}
				if ( '' != $products[$i]->product_images ) {
					$product_images = explode( ',', $products[$i]->product_images );
					if( 'image1' == $product_images[0] ) {
						if ( substr( $products[$i]->image1, 0, 7 ) == 'http://' || substr( $products[$i]->image1, 0, 8 ) == 'https://' ){
							$products[$i]->first_image = esc_attr( $products[$i]->image1 );
						} else {
							$products[$i]->first_image = esc_attr( plugins_url( "/wp-easycart-data/products/pics1/" . $products[$i]->image1, EC_PLUGIN_DATA_DIRECTORY ) );
						}
					} else if( 'image2' == $product_images[0] ) {
						if ( substr( $products[$i]->image2, 0, 7 ) == 'http://' || substr( $products[$i]->image2, 0, 8 ) == 'https://' ){
							$products[$i]->first_image = esc_attr( $products[$i]->image2 );
						} else {
							$products[$i]->first_image = esc_attr( plugins_url( "/wp-easycart-data/products/pics2/" . $products[$i]->image2, EC_PLUGIN_DATA_DIRECTORY ) );
						}
					} else if( 'image3' == $product_images[0] ) {
						if ( substr( $products[$i]->image3, 0, 7 ) == 'http://' || substr( $products[$i]->image3, 0, 8 ) == 'https://' ){
							$products[$i]->first_image = esc_attr( $products[$i]->image3 );
						} else {
							$products[$i]->first_image = esc_attr( plugins_url( "/wp-easycart-data/products/pics3/" . $products[$i]->image3, EC_PLUGIN_DATA_DIRECTORY ) );
						}
					} else if( 'image4' == $product_images[0] ) {
						if ( substr( $products[$i]->image4, 0, 7 ) == 'http://' || substr( $products[$i]->image4, 0, 8 ) == 'https://' ){
							$products[$i]->first_image = esc_attr( $products[$i]->image4 );
						} else {
							$products[$i]->first_image = esc_attr( plugins_url( "/wp-easycart-data/products/pics4/" . $products[$i]->image4, EC_PLUGIN_DATA_DIRECTORY ) );
						}
					} else if( 'image5' == $product_images[0] ) {
						if ( substr( $products[$i]->image5, 0, 7 ) == 'http://' || substr( $products[$i]->image5, 0, 8 ) == 'https://' ){
							$products[$i]->first_image = esc_attr( $products[$i]->image5 );
						} else {
							$products[$i]->first_image = esc_attr( plugins_url( "/wp-easycart-data/products/pics5/" . $products[$i]->image5, EC_PLUGIN_DATA_DIRECTORY ) );
						}
					} else if( 'image:' == substr( $product_images[0], 0, 6 ) ) {
						$products[$i]->first_image = esc_attr( substr( $product_images[0], 6, strlen( $product_images[0] ) - 6 ) );
					} else if( 'video:' == substr( $product_images[0], 0, 6 ) ) {
						$video_str = substr( $product_images[0], 6, strlen( $product_images[0] ) - 6 );
						$video_arr = explode( ':::', $video_str );
						if ( count( $video_arr ) >= 2 ) {
							$products[$i]->first_image = esc_attr( $video_arr[1] );
						}
					} else if( 'youtube:' == substr( $product_images[0], 0, 8 ) ) {
						$youtube_video_str = substr( $product_images[0], 8, strlen( $product_images[0] ) - 8 );
						$youtube_video_arr = explode( ':::', $youtube_video_str );
						if ( count( $youtube_video_arr ) >= 2 ) {
							$products[$i]->first_image = esc_attr( $youtube_video_arr[1] );
						}
					} else if( 'vimeo:' == substr( $product_images[0], 0, 6 ) ) {
						$vimeo_video_str = substr( $product_images[0], 6, strlen( $product_images[0] ) - 6 );
						$vimeo_video_arr = explode( ':::', $vimeo_video_str );
						if ( count( $vimeo_video_arr ) >= 2 ) {
							$products[$i]->first_image = esc_attr( $vimeo_video_arr[1] );
						}
					} else {
						$product_image_media = wp_get_attachment_image_src( $product_images[0], 'large' );
						if( $product_image_media && isset( $product_image_media[0] ) ) {
							$products[$i]->first_image = esc_attr( $product_image_media[0] );
						}
					}
				}
			}
			return array( 'results' => $products );
		}
	}
endif;

function wp_easycart_admin_api_routes() {
	return wp_easycart_admin_api_routes::instance();
}
wp_easycart_admin_api_routes();
