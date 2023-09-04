<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_category' ) ) :

	final class wp_easycart_admin_category {

		protected static $_instance = null;

		public $category_list_file;
		public $product_list_file;
		public $product_select_list_file;

		public static function instance( ) {

			if( is_null( self::$_instance ) ) {
				self::$_instance = new self(  );
			}
			return self::$_instance;

		}

		public function __construct( ){ 
			$this->category_list_file 			= EC_PLUGIN_DIRECTORY . '/admin/template/products/categories/category-list.php';
			$this->product_list_file 			= EC_PLUGIN_DIRECTORY . '/admin/template/products/categories/product-list.php';
			$this->product_select_list_file 	= EC_PLUGIN_DIRECTORY . '/admin/template/products/categories/product-select-list.php';

			/* Process Admin Messages */
			add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );
			add_filter( 'wp_easycart_admin_error_messages', array( $this, 'add_failure_messages' ) );

			/* Process Form Actions */
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_add_category_product' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_add_category' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_update_category' ) );

			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_duplicate_category' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_category' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_deactivate_category' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_category' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_deactivate_category' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_activate_category' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_add_category_product' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_featured_category' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_not_featured_category' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_category_product' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_category_product' ) );
		}

		public function process_deactivate_category() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['category_id'] ) && isset( $_GET['ec_admin_form_action'] ) && 'deactivate-category' == $_GET['ec_admin_form_action'] && ! isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-deactivate-category' ) ) {
					$result = $this->deactivate_category();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'category', $result );
				}
			}
		}

		public function process_bulk_deactivate_category() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && 'deactivate-category' == $_GET['ec_admin_form_action'] && ! isset( $_GET['category_id'] ) && isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-category' ) ) {
					$result = $this->bulk_deactivate_category();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'category', $result );
				}
			}
		}

		public function process_bulk_activate_category() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && 'activate-category' == $_GET['ec_admin_form_action'] && ! isset( $_GET['category_id'] ) && isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-category' ) ) {
					$result = $this->bulk_activate_category();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'category', $result );
				}
			}
		}

		public function process_add_category_product( ){
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if( $_POST['ec_admin_form_action'] == 'add-new-category-product' ){
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-category-products-manage' ) ) {
					$result = $this->insert_category_product( );
					wp_easycart_admin( )->redirect( 'wp-easycart-products', 'category', $result );
				}
			}
		}

		public function process_add_category( ){
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if( $_POST['ec_admin_form_action'] == 'add-new-category' ){
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-category-details' ) ) {
					$result = $this->insert_category( );
					wp_easycart_admin( )->redirect( 'wp-easycart-products', 'category', $result );
				}
			}
		}

		public function process_update_category( ){
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if( $_POST['ec_admin_form_action'] == 'update-category' ){
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-category-details' ) ) {
					$result = $this->update_category( );
					wp_easycart_admin( )->redirect( 'wp-easycart-products', 'category', $result );
				}
			}
		}

		public function process_duplicate_category( ){
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if( isset( $_GET['subpage'] ) &&  $_GET['subpage'] == 'category' && $_GET['ec_admin_form_action'] == 'duplicate-category' && isset( $_GET['category_id'] ) && !isset( $_GET['bulk'] ) ){
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-duplicate-category' ) ) {
					$result = $this->duplicate_category( );
					wp_easycart_admin( )->redirect( 'wp-easycart-products', 'category', $result );
				}
			}
		}

		public function process_delete_category( ){
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if( isset( $_GET['subpage'] ) &&  $_GET['subpage'] == 'category' && $_GET['ec_admin_form_action'] == 'delete-category' && isset( $_GET['category_id'] ) && !isset( $_GET['bulk'] ) ){
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-category' ) ) {
					$result = $this->delete_category( );
					wp_easycart_admin( )->redirect( 'wp-easycart-products', 'category', $result );
				}
			}
		}

		public function process_bulk_delete_category( ){
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if( isset( $_GET['subpage'] ) && $_GET['subpage']  == 'category' && $_GET['ec_admin_form_action'] == 'delete-category' && !isset( $_GET['category_id'] ) && isset( $_GET['bulk'] ) ){
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-category' ) ) {
					$result = $this->bulk_delete_category( );
					wp_easycart_admin( )->redirect( 'wp-easycart-products', 'category', $result );
				}
			}
		}

		public function process_bulk_featured_category( ){
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if( isset( $_GET['subpage'] ) && $_GET['subpage']  == 'category' && $_GET['ec_admin_form_action'] == 'set-featured-category' && isset( $_GET['bulk'] ) ){
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-category' ) ) {
					$result = $this->bulk_set_featured_category( );
					wp_easycart_admin( )->redirect( 'wp-easycart-products', 'category', $result );
				}
			}
		}

		public function process_bulk_not_featured_category( ){
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if( isset( $_GET['subpage'] ) && $_GET['subpage']  == 'category' && $_GET['ec_admin_form_action'] == 'unset-featured-category' && isset( $_GET['bulk'] ) ){
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-category' ) ) {
					$result = $this->bulk_unset_featured_category( );
					wp_easycart_admin( )->redirect( 'wp-easycart-products', 'category', $result );
				}
			}
		}

		public function process_bulk_add_category_product( ){
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if( isset( $_GET['subpage'] ) && $_GET['subpage']  == 'category-products-manage' && $_GET['ec_admin_form_action'] == 'add-to-category-product' && isset( $_GET['category_id'] ) && isset( $_GET['bulk'] ) ){
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-category-products-manage' ) ) {
					$result = $this->bulk_add_category_product( );
					wp_easycart_admin( )->redirect( 'wp-easycart-products', 'category-products', $result );
				}
			}
		}

		public function process_delete_category_product( ){
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if( isset( $_GET['subpage'] ) &&  $_GET['subpage'] == 'category-products' && $_GET['ec_admin_form_action'] == 'delete-category-product' && isset( $_GET['categoryitem_id'] ) && !isset( $_GET['bulk'] ) ){
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-category-product' ) ) {
					$result = $this->delete_category_product( );
					wp_easycart_admin( )->redirect( 'wp-easycart-products', 'category-products', $result );
				}
			}
		}

		public function process_bulk_delete_category_product( ){
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if( isset( $_GET['subpage'] ) &&  $_GET['subpage'] == 'category-products' && $_GET['ec_admin_form_action'] == 'delete-category-product' && !isset( $_GET['categoryitem_id'] ) && isset( $_GET['bulk'] ) ){
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-category-products' ) ) {
					$result = $this->bulk_delete_category_product( );
					wp_easycart_admin( )->redirect( 'wp-easycart-products', 'category-products', $result );
				}
			}
		}

		public function add_success_messages( $messages ){
			if ( isset( $_GET['success'] ) && $_GET['success'] == 'category-inserted' ) {
				$messages[] = __( 'Category successfully created', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'category-updated' ) {
				$messages[] = __( 'Category successfully updated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'category-deleted' ) {
				$messages[] = __( 'Category successfully deleted', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'category-duplicated' ) {
				$messages[] = __( 'Category successfully duplicated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'category-item-inserted' ) {
				$messages[] = __( 'Category Item successfully created', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'category-item-deleted' ) {
				$messages[] = __( 'Category item(s) successfully deleted', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'category-item-added' ) {
				$messages[] = __( 'Products(s) successfully added to the category', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'category-activate-single' ) {
				$messages[] = __( 'Cateogry successfully activated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'category-deactivate-single' ) {
				$messages[] = __( 'Category successfully deactivated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'category-deactivated' ) {
				$messages[] = __( 'Selected categories successfully deactivated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'category-activated' ) {
				$messages[] = __( 'Selected categories successfully activated', 'wp-easycart' );
			}
			return $messages;
		}

		public function add_failure_messages( $messages ){
			if ( isset( $_GET['error'] ) && $_GET['error'] == 'category-inserted-error' ) {
				$messages[] = __( 'Category failed to create', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'category-updated-error' ) {
				$messages[] = __( 'Category failed to update', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'category-deleted-error' ) {
				$messages[] = __( 'Category failed to delete', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'category-duplicate-error' ) {
				$messages[] = __( 'Category failed to duplicate', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'category-duplicate' ) {
				$messages[] = __( 'Category failed to create due to duplicate', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'category-item-duplicate' ) {
				$messages[] = __( 'Category Item failed to create due to duplicate', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'category-item-inserted-error' ) {
				$messages[] = __( 'Category Item failed to create', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'category-item-deleted-error' ) {
				$messages[] = __( 'Category Item failed to delete', 'wp-easycart' );
			}
			return $messages;
		}

		public function load_category_list( ){
			if( ( isset( $_GET['category_id'] ) && isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'edit' ) || 
				( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'add-new-category' ) ){
					include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_details_category.php' );
					$details = new wp_easycart_admin_details_category( );
					$details->output( sanitize_key( $_GET['ec_admin_form_action'] ) );

			}else{
				include( $this->category_list_file );

			}
		}

		public function load_category_product_list( ){
			include( $this->product_list_file );
		}

		public function load_category_product_manage_list( ){
			include( $this->product_select_list_file );
		}

		/*************************************
		* Category
		*************************************/
		public function deactivate_category() {
			global $wpdb;

			$category_id = (int) $_GET['category_id'];
			$category = $wpdb->get_row( $wpdb->prepare( 'SELECT post_id, is_active FROM ec_category WHERE category_id = %d', $category_id ) );
			$active_status = 1;
			$status = 'publish';
			if ( $category->is_active == 1 ) { 
				$active_status = 0;
				$status = 'private';
			}

			/* Manually Update Post */
			$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'posts SET post_status = %s WHERE ID = %d', $status, $category->post_id ) );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_category SET is_active = %d WHERE category_id = %d', $active_status, $category_id ) );
			do_action( 'wpeasycart_category_deactivated', $category_id );

			if ( $active_status ) {
				$args = array( 'success' => 'category-activate-single' );
			} else {
				$args = array( 'success' => 'category-deactivate-single' );
			}
			if ( isset( $_GET['pagenum'] ) ) {
				$args['pagenum'] = (int) $_GET['pagenum'];
			}
			$valid_orderby = array( 'category_name', 'category_id', 'total_products', 'is_visible', 'featured_category' );
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

		public function bulk_deactivate_category() {
			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				$category = $wpdb->get_row( $wpdb->prepare( 'SELECT post_id, is_active FROM ec_category WHERE category_id = %d', (int) $bulk_id ) );
				$active_status = 0;
				$status = 'private';
				$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'posts SET post_status = %s WHERE ID = %d', $status, $category->post_id ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_category SET is_active = %d WHERE category_id = %d', $active_status, (int) $bulk_id ) );
				do_action( 'wpeasycart_category_deactivated', (int) $bulk_id );
			}

			$args = array( 'success' => 'category-deactivated' );

			if ( isset( $_GET['pagenum'] ) ) {
				$args['pagenum'] = (int) $_GET['pagenum'];
			}
			$valid_orderby = array( 'category_name', 'category_id', 'total_products', 'is_visible', 'featured_category' );
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

		public function bulk_activate_category() {
			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				$category = $wpdb->get_row( $wpdb->prepare( 'SELECT post_id, is_active FROM ec_category WHERE category_id = %d', (int) $bulk_id ) );
				$active_status = 1;
				$status = 'publish';
				$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'posts SET post_status = %s WHERE ID = %d', $status, $category->post_id ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_category SET is_active = %d WHERE category_id = %d', $active_status, (int) $bulk_id ) );
				do_action( 'wpeasycart_category_activated', (int) $bulk_id );
			}

			$args = array( 'success' => 'category-activated' );

			if ( isset( $_GET['pagenum'] ) ) {
				$args['pagenum'] = (int) $_GET['pagenum'];
			}
			$valid_orderby = array( 'category_name', 'category_id', 'total_products', 'is_visible', 'featured_category' );
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

		public function duplicate_category( ){
			if( !wp_easycart_admin_verification( )->verify_access( 'wp-easycart-action-duplicate-category' ) ){
				return false;
			}

			$category_id = (int) $_GET['category_id'];
			$query_vars = array( );
			global $wpdb;

			$category = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_category WHERE category_id = %d', $category_id ) );
			$wpdb->query( 'INSERT INTO ec_category() VALUES()' );
			$new_category_id = $wpdb->insert_id;
			$sql = 'UPDATE ec_category SET ';
			foreach ( $category as $key => $value ) {
				if ( $key != 'category_id' && $key != 'post_id' && $key != 'square_id' ) {
					$sql .= '`'.$key.'` = ' . $wpdb->prepare( '%s', $value ) .', ';
				}
			}
			$sql = substr( $sql, 0, strlen( $sql ) - 2 );
			$wpdb->query( $sql . $wpdb->prepare( ' WHERE category_id = %d', $new_category_id ) );

			// Insert a WordPress Custom post type post.
			$post = array(
				'post_content' => '[ec_store groupid="' . $new_category_id . '"]',
				'post_status' => 'publish',
				'post_title' => wp_easycart_language( )->convert_text( $new_category_name ),
				'post_type' => 'ec_store',
			);
			$post_id = wp_insert_post( $post );

			// Update Category Post ID
			$db = new ec_db( );
			$db->update_category_post_id( $new_category_id, $post_id );

			//SECOND, CREATE DUPLICATE CATEOGRY ITEMS
			$all_subitems = $wpdb->get_results( $wpdb->prepare( 'SELECT ec_categoryitem.* FROM ec_categoryitem WHERE ec_categoryitem.category_id = %s', $category_id));
			foreach($all_subitems AS $subitems) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_categoryitem( ec_categoryitem.categoryitem_id, ec_categoryitem.category_id,  ec_categoryitem.product_id ) VALUES(NULL, %s, %s)',  $new_category_id,  $subitems->product_id) );
			}
			do_action( 'wpeasycart_category_added', $new_category_id );

			$query_vars['success'] = 'category-duplicated';
			return $query_vars;

		}

		public function insert_category() {
			if( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-category-details' ) ) {
				return false;
			}

			global $wpdb;

			$featured_category = ( isset( $_POST['featured_category'] ) ) ? 1 : 0;
			$is_active = ( isset( $_POST['is_active'] ) ) ? 1 : 0;
			$category_name = sanitize_text_field( wp_unslash( $_POST['category_name'] ) );
			$priority = (int) $_POST['priority'];
			$parent_id = (int) $_POST['parent_id'];
			$image = sanitize_text_field( wp_unslash( $_POST['image'] ) );
			$short_description = wp_easycart_escape_html( wp_unslash( $_POST['short_description'] ) );
			$post_excerpt = sanitize_text_field( wp_unslash( $_POST['post_excerpt'] ) );
			$featured_image = ( isset( $_POST['featured_image'] ) && '' != $_POST['featured_image'] ) ? (int) $_POST['featured_image'] : 0;

			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_category( is_active, featured_category, category_name, parent_id, image, short_description, priority ) VALUES( %d, %d, %s, %d, %s, %s, %d )', $is_active, $featured_category, $category_name, $parent_id, $image, $short_description, $priority ) );
			$category_id = $wpdb->insert_id;

			$post = array(
				'post_content' => '[ec_store groupid="' . $category_id . '"]',
				'post_status' => ( $is_active ) ? 'publish' : 'private',
				'post_title' => wp_easycart_language( )->convert_text( $category_name ),
				'post_type' => 'ec_store',
				'post_excerpt' => $post_excerpt
			);
			$post_id = wp_insert_post( $post );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_category SET post_id = %d WHERE category_id = %d', $post_id, $category_id ) );
			if ( 0 != $featured_image ) {
				set_post_thumbnail( $post_id, $featured_image );
			}
			do_action( 'wpeasycart_category_added', $category_id );

			return array( 'success' => 'category-inserted' );
		}

		public function update_category() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-category-details' ) ) {
				return false;
			}

			$category_id = (int) $_POST['category_id'];
			$category_name = sanitize_text_field( wp_unslash( $_POST['category_name'] ) );
			$priority = (int) $_POST['priority'];
			$post_slug = preg_replace( '/[^A-Za-z0-9\-]/', '', str_replace( ' ', '-', sanitize_text_field( wp_unslash( $_POST['post_slug'] ) ) ) );
			$post_id = (int) $_POST['post_id'];
			$parent_id = (int) $_POST['parent_id'];
			$short_description = wp_easycart_escape_html( wp_unslash( $_POST['short_description'] ) );
			$image = sanitize_text_field( wp_unslash( $_POST['image'] ) );
			$featured_category = ( isset( $_POST['featured_category'] ) ) ? 1 : 0;
			$is_active = ( isset( $_POST['is_active'] ) ) ? 1 : 0;
			$post_excerpt = sanitize_text_field( wp_unslash( $_POST['post_excerpt'] ) );
			$featured_image = ( isset( $_POST['featured_image'] ) && '' != $_POST['featured_image'] ) ? (int) $_POST['featured_image'] : 0;

			$query_vars = array( );

			global $wpdb;

			// Update category
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_category SET is_active = %d, category_name = %s, post_id = %d, parent_id = %d, short_description = %s, image = %s, featured_category = %d, priority = %d WHERE category_id = %d', $is_active, $category_name, $post_id, $parent_id, $short_description, $image, $featured_category, $priority, $category_id ) );

			// Update WordPress Post
			$post_id = $wpdb->get_var( $wpdb->prepare( 'SELECT post_id FROM ec_category WHERE category_id = %d', $category_id ) );

			// Create Post Array
			$post = array(	
				'ID'			=> $post_id,
				'post_content'	=> '[ec_store groupid="' . $category_id . '"]',
				'post_status'	=> ( $is_active ) ? 'publish' : 'private',
				'post_title'	=> wp_easycart_language( )->convert_text( $category_name ),
				'post_type'		=> 'ec_store',
				'post_name'		=> $post_slug,
				'post_excerpt'  => $post_excerpt
			);

			// Update WordPress Post
			$updated_post_id = wp_update_post( $post );
			if( $updated_post_id == 0 ){
				$insert_post_id = wp_insert_post( array(
					'post_content' => '[ec_store groupid="' . $category_id . '"]',
					'post_status' => ( $is_active ) ? 'publish' : 'private',
					'post_title' => wp_easycart_language( )->convert_text( $category_name ),
					'post_type' => 'ec_store',
					'post_name' => $post_slug,
					'post_excerpt' => $post_excerpt
				) );
				if( $insert_post_id != 0 ){
					$post_id = $insert_post_id;
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_category SET post_id = %d WHERE category_id = %d', $post_id, $category_id ) );
				}
			}
			if ( 0 == $featured_image ) {
				delete_post_thumbnail( $post_id );
			} else {
				set_post_thumbnail( $post_id, $featured_image );
			}

			// Update GUID
			$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'posts SET ' . $wpdb->prefix . 'posts.guid = %s WHERE ' . $wpdb->prefix . 'posts.ID = %d', get_permalink( $post_id ), $post_id ) );
			do_action( 'wpeasycart_category_updated', $category_id );

			$query_vars['success'] = 'category-updated';
			return $query_vars;
		}

		public function delete_category( ){
			if( !wp_easycart_admin_verification( )->verify_access( 'wp-easycart-action-delete-category' ) ){
				return false;
			}

			$category_id = (int) $_GET['category_id'];
			do_action( 'wpeasycart_category_deleting', $category_id );
			$query_vars = array( );

			global $wpdb;

			// Delete WordPress Post
			$post_id = $wpdb->get_var( $wpdb->prepare( 'SELECT post_id FROM ec_category WHERE category_id = %d', $category_id ) );
			wp_delete_post( $post_id, true );

			// Delete Category
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_category WHERE ec_category.category_id = %s', $category_id ) );

			// Delete Category Items
			$wpdb->query( $wpdb->prepare(  'DELETE FROM ec_categoryitem WHERE ec_categoryitem.category_id = %d', $category_id ) );
			do_action( 'wpeasycart_category_deleted', $category_id );

			$query_vars['success'] = 'category-deleted';
			return $query_vars;
		}

		public function bulk_delete_category( ){
			if( !wp_easycart_admin_verification( )->verify_access( 'wp-easycart-bulk-category' ) ){
				return false;
			}

			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.
			$query_vars = array( );

			global $wpdb;
			foreach( $bulk_ids as $bulk_id ){
				do_action( 'wpeasycart_category_deleting', (int) $bulk_id );
				// Delete WordPress Post
				$post_id = $wpdb->get_var( $wpdb->prepare( 'SELECT post_id FROM ec_category WHERE category_id = %d', (int) $bulk_id ) );
				wp_delete_post( $post_id, true );

				//Delete Category
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_category WHERE ec_category.category_id = %s', (int) $bulk_id ) );

				// Delete Category Items
				$wpdb->query( $wpdb->prepare(  'DELETE FROM ec_categoryitem WHERE ec_categoryitem.category_id = %d', (int) $bulk_id ) );
				do_action( 'wpeasycart_category_deleted', (int) $bulk_id );
			}

			$query_vars['success'] = 'category-deleted';
			return $query_vars;

		}

		public function bulk_set_featured_category( ){
			if( !wp_easycart_admin_verification( )->verify_access( 'wp-easycart-bulk-category' ) ){
				return false;
			}

			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.
			$query_vars = array( );

			global $wpdb;
			foreach( $bulk_ids as $bulk_id ){
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_category SET featured_category = 1 WHERE category_id = %d', (int) $bulk_id ) );
				do_action( 'wpeasycart_category_updated', (int) $bulk_id );
			}

			$query_vars['success'] = 'category-featured';
			return $query_vars;
		}

		public function bulk_unset_featured_category( ){
			if( !wp_easycart_admin_verification( )->verify_access( 'wp-easycart-bulk-category' ) ){
				return false;
			}

			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.
			$query_vars = array( );

			global $wpdb;
			foreach( $bulk_ids as $bulk_id ){
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_category SET featured_category = 0 WHERE category_id = %d', (int) $bulk_id ) );
				do_action( 'wpeasycart_category_updated', (int) $bulk_id );
			}

			$query_vars['success'] = 'category-not-featured';
			return $query_vars;
		}

		/**************************************
		* Category Products
		**************************************/
		public function bulk_add_category_product( ){
			if( !wp_easycart_admin_verification( )->verify_access( 'wp-easycart-bulk-category-products-manage' ) ){
				return false;
			}

			global $wpdb;

			$category_id = (int) $_GET['category_id'];			
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach( $bulk_ids as $bulk_id ){	
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_categoryitem( category_id, product_id ) VALUES( %d, %d )',  $category_id,  (int) $bulk_id ) );
				do_action( 'wpeasycart_product_to_category_added',  $category_id, (int) $bulk_id );
			}

			return array( 
				'success' => 'category-item-added', 
				'ec_admin_form_action' => 'edit-products',
				'category_id' => (int) $category_id
			);
		}

		public function delete_category_product( ){
			if( !wp_easycart_admin_verification( )->verify_access( 'wp-easycart-action-delete-category-product' ) ){
				return false;
			}

			global $wpdb;

			$categoryitem_id = (int) $_GET['categoryitem_id'];
			$category_id = $wpdb->get_var( $wpdb->prepare( 'SELECT category_id FROM ec_categoryitem WHERE categoryitem_id = %d', $categoryitem_id ) );
			$product_id = $wpdb->get_var( $wpdb->prepare( 'SELECT product_id FROM ec_categoryitem WHERE categoryitem_id = %d', $categoryitem_id ) );
			do_action( 'wpeasycart_product_to_category_deleted',  $category_id, $product_id );

			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_categoryitem WHERE ec_categoryitem.categoryitem_id = %d', $categoryitem_id ) );

			return array( 
				'success' => 'category-item-deleted',
				'ec_admin_form_action' => 'edit-products',
				'category_id' => (int) $category_id
			);
		}

		public function bulk_delete_category_product( ){
			if( !wp_easycart_admin_verification( )->verify_access( 'wp-easycart-bulk-category-products' ) ){
				return false;
			}

			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			if( count( $bulk_ids ) > 0 ){
				$category_id = $wpdb->get_var( $wpdb->prepare( 'SELECT category_id FROM ec_categoryitem WHERE categoryitem_id = %d', (int) $bulk_ids[0] ) );
			}

			foreach( $bulk_ids as $bulk_id ){
				$product_id = $wpdb->get_var( $wpdb->prepare( 'SELECT product_id FROM ec_categoryitem WHERE categoryitem_id = %d', (int) $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_categoryitem WHERE categoryitem_id = %d', (int) $bulk_id ) );
				do_action( 'wpeasycart_product_to_category_deleted', $category_id, $product_id );
			}

			return array( 
				'success' => 'category-item-deleted',
				'ec_admin_form_action' => 'edit-products',
				'category_id' => (int) $category_id
			);
		}

		public function save_category_order( ){
			global $wpdb;
			$sort_order = (array) $_POST['sort_order']; // XSS OK. Forced array and each item sanitized.

			foreach( $sort_order as $sort_item ){
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_category SET priority = %d WHERE category_id = %d', 99999999 - (int) $sort_item['order'], (int) $sort_item['id'] ) );
			}
			do_action( 'wpeasycart_category_sort_save' );
		}

	}
endif; // End if class_exists check

function wp_easycart_admin_category() {
	return wp_easycart_admin_category::instance();
}
wp_easycart_admin_category();

add_action( 'wp_ajax_ec_admin_ajax_save_category_order', 'ec_admin_ajax_save_category_order' );
function ec_admin_ajax_save_category_order() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-table-sort' ) ) {
		return false;
	}

	wp_easycart_admin_category( )->save_category_order();
	die();
}
