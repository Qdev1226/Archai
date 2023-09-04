<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_menus' ) ) :

	final class wp_easycart_admin_menus {

		protected static $_instance = null;

		public $menus_list_file;
		public $submenus_list_file;
		public $subsubmenus_list_file;

		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self(  );
			}
			return self::$_instance;

		}

		public function __construct() { 
			$this->menus_list_file 			= EC_PLUGIN_DIRECTORY . '/admin/template/products/menus/menu-list.php';
			$this->submenus_list_file 		= EC_PLUGIN_DIRECTORY . '/admin/template/products/menus/submenu-list.php';
			$this->subsubmenus_list_file 	= EC_PLUGIN_DIRECTORY . '/admin/template/products/menus/subsubmenu-list.php';

			/* Process Admin Messages */
			add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );
			add_filter( 'wp_easycart_admin_error_messages', array( $this, 'add_failure_messages' ) );

			/* Process Form Actions */
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_add_new_menulevel1' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_add_new_menulevel2' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_add_new_menulevel3' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_update_menulevel1' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_update_menulevel2' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_update_menulevel3' ) );

			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_menulevel1' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_menulevel2' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_menulevel3' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_menulevel1' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_menulevel2' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_menulevel3' ) );
		}

		public function process_add_new_menulevel1() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( $_POST['ec_admin_form_action'] == 'add-new-menulevel1' ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-menulevel1-details' ) ) {
					$result = $this->insert_menulevel1();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'menus', $result );
				}
			}
		}

		public function process_add_new_menulevel2() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( $_POST['ec_admin_form_action'] == 'add-new-menulevel2' ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-menulevel2-details' ) ) {
					$result = $this->insert_menulevel2();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'submenus', $result );
				}
			}
		}

		public function process_add_new_menulevel3() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( $_POST['ec_admin_form_action'] == 'add-new-menulevel3' ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-menulevel3-details' ) ) {
					$result = $this->insert_menulevel3();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'subsubmenus', $result );
				}
			}
		}

		public function process_update_menulevel1() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( $_POST['ec_admin_form_action'] == 'update-menulevel1' ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-menulevel1-details' ) ) {
					$result = $this->update_menulevel1();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'menus', $result );
				}
			}
		}

		public function process_update_menulevel2() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( $_POST['ec_admin_form_action'] == 'update-menulevel2' ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-menulevel2-details' ) ) {
					$result = $this->update_menulevel2();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'submenus', $result );
				}
			}
		}

		public function process_update_menulevel3() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( $_POST['ec_admin_form_action'] == 'update-menulevel3' ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-menulevel3-details' ) ) {
					$result = $this->update_menulevel3();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'subsubmenus', $result );
				}
			}
		}

		public function process_delete_menulevel1() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['subpage'] ) && $_GET['subpage'] == 'menus' && $_GET['ec_admin_form_action'] == 'delete-menulevel1' && isset( $_GET['menulevel1_id'] ) && !isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-menulevel1' ) ) {
					$result = $this->delete_menulevel1();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'menus', $result );
				}
			}
		}

		public function process_delete_menulevel2() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['subpage'] ) && $_GET['subpage'] == 'submenus' && $_GET['ec_admin_form_action'] == 'delete-menulevel2' && isset( $_GET['menulevel2_id'] ) && !isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-menulevel2' ) ) {
					$result = $this->delete_menulevel2();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'submenus', $result );
				}
			}
		}

		public function process_delete_menulevel3() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['subpage'] ) && $_GET['subpage'] == 'subsubmenus' && $_GET['ec_admin_form_action'] == 'delete-menulevel3' && isset( $_GET['menulevel3_id'] ) && !isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-menulevel3' ) ) {
					$result = $this->delete_menulevel3();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'subsubmenus', $result );
				}
			}
		}

		public function process_bulk_delete_menulevel1() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['subpage'] ) && $_GET['subpage'] == 'menus' && $_GET['ec_admin_form_action'] == 'delete-menulevel1' && isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-menus' ) ) {
					$result = $this->bulk_delete_menulevel1();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'menus', $result );
				}
			}
		}

		public function process_bulk_delete_menulevel2() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['subpage'] ) && $_GET['subpage'] == 'submenus' && $_GET['ec_admin_form_action'] == 'delete-menulevel2' && isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-submenus' ) ) {
					$result = $this->bulk_delete_menulevel2();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'submenus', $result );
				}
			}
		}

		public function process_bulk_delete_menulevel3() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['subpage'] ) && $_GET['subpage'] == 'subsubmenus' && $_GET['ec_admin_form_action'] == 'delete-menulevel3' && isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-subsubmenus' ) ) {
					$result = $this->bulk_delete_menulevel3();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'subsubmenus', $result );
				}
			}
		}

		public function add_success_messages( $messages ) {
			if ( isset( $_GET['success'] ) && $_GET['success'] == 'menulevel1-inserted' ) {
				$messages[] = __( 'Menu successfully created', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'menulevel1-updated' ) {
				$messages[] = __( 'Menu successfully updated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'menulevel1-deleted' ) {
				$messages[] = __( 'Menu successfully deleted', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'menulevel2-inserted' ) {
				$messages[] = __( 'Sub-Menu successfully created', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'menulevel2-updated' ) {
				$messages[] = __( 'Sub-Menu successfully updated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'menulevel2-deleted' ) {
				$messages[] = __( 'Sub-Menu successfully deleted', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'menulevel3-inserted' ) {
				$messages[] = __( 'Sub-Sub-Menu successfully created', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'menulevel3-updated' ) {
				$messages[] = __( 'Sub-Sub-Menu successfully updated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'menulevel3-deleted' ) {
				$messages[] = __( 'Sub-Sub-Menu successfully deleted', 'wp-easycart' );
			}
			return $messages;
		}

		public function add_failure_messages( $messages ) {
			if ( isset( $_GET['error'] ) && $_GET['error'] == 'menulevel1-inserted-error' ) {
				$messages[] = __( 'Menu failed to create', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'menulevel1-updated-error' ) {
				$messages[] = __( 'Menu failed to update', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'menulevel1-deleted-error' ) {
				$messages[] = __( 'Menu failed to delete', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'menulevel1-duplicate' ) {
				$messages[] = __( 'Menu failed to create due to duplicate', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'menulevel2-inserted-error' ) {
				$messages[] = __( 'Sub-Menu failed to create', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'menulevel2-updated-error' ) {
				$messages[] = __( 'Sub-Menu failed to update', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'menulevel2-deleted-error' ) {
				$messages[] = __( 'Sub-Menu failed to delete', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'menulevel2-duplicate' ) {
				$messages[] = __( 'Sub-Menu failed to create due to duplicate', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'menulevel3-inserted-error' ) {
				$messages[] = __( 'Sub-Sub-Menu failed to create', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'menulevel3-updated-error' ) {
				$messages[] = __( 'Sub-Sub-Menu failed to update', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'menulevel3-deleted-error' ) {
				$messages[] = __( 'Sub-Sub-Menu failed to delete', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'menulevel3-duplicate' ) {
				$messages[] = __( 'Sub-Sub-Menu failed to create due to duplicate', 'wp-easycart' );
			}
			return $messages;
		}

		public function load_menus_list() {
			//add new or edit, show details page
			if ( ( isset( $_GET['menulevel1_id'] ) && isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'edit' ) || 
				( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'add-new-menulevel1' ) ) {
					include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_details_menulevel1.php' );
					$details = new wp_easycart_admin_details_menulevel1();
					$details->output( sanitize_key( $_GET['ec_admin_form_action'] ) );
			}else {
				include( $this->menus_list_file );
			}
		}

		public function load_submenus_list() {
			//add new or edit, show details page
			if ( ( isset( $_GET['menulevel2_id'] ) && isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'edit' ) || 
				( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'add-new-menulevel2' ) ) {
					include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_details_menulevel2.php' );
					$details = new wp_easycart_admin_details_menulevel2();
					$details->output( sanitize_key( $_GET['ec_admin_form_action'] ) );

			}else {
				include( $this->submenus_list_file );

			}
		}

		public function load_subsubmenus_list() {
			//add new or edit, show details page
			if ( ( isset( $_GET['menulevel3_id'] ) && isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'edit' ) || 
				( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'add-new-menulevel3' ) ) {
					include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_details_menulevel3.php' );
					$details = new wp_easycart_admin_details_menulevel3();
					$details->output( sanitize_key( $_GET['ec_admin_form_action'] ) );

			}else {
				include( $this->subsubmenus_list_file );

			}
		}

		/**************************************
		* MENU LEVEL 1
		**************************************/
		public function insert_menulevel1() {
			if ( !wp_easycart_admin_verification()->verify_access( 'wp-easycart-menulevel1-details' ) ) {
				return false;
			}

			global $wpdb;

			$name = sanitize_text_field( wp_unslash( $_POST['name'] ) );
			$menu_order = (int) $_POST['menu_order'];
			$seo_keywords = sanitize_text_field( wp_unslash( $_POST['seo_keywords'] ) );
			$seo_description = sanitize_textarea_field( wp_unslash( $_POST['seo_description'] ) );
			$banner_image = sanitize_text_field( wp_unslash( $_POST['banner_image'] ) );
			$post_excerpt = sanitize_text_field( wp_unslash( $_POST['post_excerpt'] ) );
			$featured_image = ( isset( $_POST['featured_image'] ) && '' != $_POST['featured_image'] ) ? (int) $_POST['featured_image'] : 0;

			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_menulevel1( name, menu_order, seo_keywords, seo_description, banner_image ) VALUES( %s, %d, %s, %s, %s )', $name, $menu_order, $seo_keywords, $seo_description, $banner_image ) );
			$menu_id = $wpdb->insert_id;

			// Insert WordPress Post
			$post = array(
				'post_content'	=> '[ec_store menuid="' . $menu_id . '"]',
				'post_status'	=> 'publish',
				'post_title'	=> wp_easycart_language()->convert_text( $name ),
				'post_type'		=> 'ec_store',
				'post_excerpt'  => $post_excerpt,
			);
			$post_id = wp_insert_post( $post, $wp_error );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_menulevel1 SET post_id = %d WHERE menulevel1_id = %d', $post_id, $menu_id ) );
			if ( 0 != $featured_image ) {
				set_post_thumbnail( $post_id, $featured_image );
			}

			return array( 'success' => 'menulevel1-inserted' );
		}

		public function update_menulevel1() {
			if ( !wp_easycart_admin_verification()->verify_access( 'wp-easycart-menulevel1-details' ) ) {
				return false;
			}

			global $wpdb;

			$menulevel1_id = (int) $_POST['menulevel1_id'];			
			$name = sanitize_text_field( wp_unslash( $_POST['name'] ) );
			$post_slug = preg_replace( '/[^A-Za-z0-9\-]/', '', str_replace( ' ', '-', sanitize_text_field( wp_unslash( $_POST['post_slug'] ) ) ) );
			$menu_order = (int) $_POST['menu_order'];
			$seo_keywords = sanitize_text_field( wp_unslash( $_POST['seo_keywords'] ) );
			$seo_description = sanitize_text_field( wp_unslash( $_POST['seo_description'] ) );
			$banner_image = sanitize_text_field( wp_unslash( $_POST['banner_image'] ) );
			$post_id = (int) $_POST['post_id'];
			$post_excerpt = sanitize_text_field( wp_unslash( $_POST['post_excerpt'] ) );
			$featured_image = ( isset( $_POST['featured_image'] ) && '' != $_POST['featured_image'] ) ? (int) $_POST['featured_image'] : 0;

			$post = array(
				'ID'			=> $post_id,
				'post_content'	=> '[ec_store menuid="' . $menulevel1_id . '"]',
				'post_status'	=> 'publish',
				'post_title'	=> wp_easycart_language()->convert_text( $name ),
				'post_type'		=> 'ec_store',
				'post_name'		=> $post_slug,
				'post_excerpt'  => $post_excerpt,
			);
			wp_update_post( $post );
			$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'posts SET ' . $wpdb->prefix . 'posts.guid = %s WHERE ' . $wpdb->prefix . 'posts.ID = %d', get_permalink( $post_id ), $post_id ) );

			$wpdb->query( $wpdb->prepare( 'UPDATE ec_menulevel1 SET name = %s, menu_order = %d, seo_keywords = %s, seo_description = %s, banner_image = %s WHERE menulevel1_id = %d', $name, $menu_order, $seo_keywords, $seo_description, $banner_image, $menulevel1_id ) );
			if ( 0 == $featured_image ) {
				delete_post_thumbnail( $post_id );
			} else {
				set_post_thumbnail( $post_id, $featured_image );
			}

			return array( 'success' => 'menulevel1-updated' );	
		}


		public function delete_menulevel1() {
			if ( !wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-menulevel1' ) ) {
				return false;
			}

			global $wpdb;

			$menulevel1_id = (int) $_GET['menulevel1_id'];
			$post_id = $wpdb->get_var( $wpdb->prepare( 'SELECT post_id FROM ec_menulevel1 WHERE menulevel1_id = %d', $menulevel1_id ) );
			$level2_items = $wpdb->get_results( $wpdb->prepare( 'SELECT menulevel2_id, post_id FROM ec_menulevel2 WHERE menulevel1_id = %d', $menulevel1_id ) );

			foreach ( $level2_items as $level2_item ) {
				$level3_items = $wpdb->get_results( $wpdb->prepare( 'SELECT menulevel3_id, post_id FROM ec_menulevel3 WHERE menulevel2_id = %d', $level2_item->menulevel2_id ) );

				foreach ( $level3_items as $level3_item ) {
					wp_delete_post( $level3_item->post_id, true );
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel1_id_3 = 0 WHERE menulevel1_id_3 = %d', $level3_item->menulevel3_id ) );
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel2_id_3 = 0 WHERE menulevel2_id_3 = %d', $level3_item->menulevel3_id ) );
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel3_id_3 = 0 WHERE menulevel3_id_3 = %d', $level3_item->menulevel3_id ) );
				}

				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_menulevel3 WHERE menulevel2_id = %d', $level2_item->menulevel2_id ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel1_id_2 = 0 WHERE menulevel1_id_2 = %d', $level2_item->menulevel2_id ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel2_id_2 = 0 WHERE menulevel2_id_2 = %d', $level2_item->menulevel2_id ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel3_id_2 = 0 WHERE menulevel3_id_2 = %d', $level2_item->menulevel2_id ) );

				wp_delete_post( $level2_item->post_id, true );
			}

			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_menulevel2 WHERE menulevel1_id = %d', $menulevel1_id ) );
			wp_delete_post( $post_id, true );

			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_menulevel1 WHERE menulevel1_id = %d', $menulevel1_id ) );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel1_id_1 = 0 WHERE menulevel1_id_1 = %d', $menulevel1_id ) );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel2_id_1 = 0 WHERE menulevel2_id_1 = %d', $menulevel1_id ) );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel3_id_1 = 0 WHERE menulevel3_id_1 = %d', $menulevel1_id ) );

			return array( 'success' => 'menulevel1-deleted' );
		}

		public function bulk_delete_menulevel1() {
			if ( !wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-menus' ) ) {
				return false;
			}

			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				$bulk_id = (int) $bulk_id;
				$post_id = $wpdb->get_var( $wpdb->prepare( 'SELECT post_id FROM ec_menulevel1 WHERE menulevel1_id = %d', $bulk_id ) );
				$level2_items = $wpdb->get_results( $wpdb->prepare( 'SELECT menulevel2_id, post_id FROM ec_menulevel2 WHERE menulevel1_id = %d', $bulk_id ) );

				foreach ( $level2_items as $level2_item ) {
					$level3_items = $wpdb->get_results( $wpdb->prepare( 'SELECT menulevel3_id, post_id FROM ec_menulevel3 WHERE menulevel2_id = %d', $level2_item->menulevel2_id ) );

					foreach ( $level3_items as $level3_item ) {
						wp_delete_post( $level3_item->post_id, true );
						$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel1_id_3 = 0 WHERE menulevel1_id_3 = %d', $level3_item->menulevel3_id ) );
						$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel2_id_3 = 0 WHERE menulevel2_id_3 = %d', $level3_item->menulevel3_id ) );
						$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel3_id_3 = 0 WHERE menulevel3_id_3 = %d', $level3_item->menulevel3_id ) );
					}

					$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_menulevel3 WHERE menulevel2_id = %d', $level2_item->menulevel2_id ) );
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel1_id_2 = 0 WHERE menulevel1_id_2 = %d', $level2_item->menulevel2_id ) );
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel2_id_2 = 0 WHERE menulevel2_id_2 = %d', $level2_item->menulevel2_id ) );
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel3_id_2 = 0 WHERE menulevel3_id_2 = %d', $level2_item->menulevel2_id ) );

					wp_delete_post( $level2_item->post_id, true );
				}
				wp_delete_post( $post_id, true );

				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_menulevel2 WHERE menulevel1_id = %d', $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_menulevel1 WHERE menulevel1_id = %d', $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel1_id_1 = 0 WHERE menulevel1_id_1 = %d', $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel2_id_1 = 0 WHERE menulevel2_id_1 = %d', $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel3_id_1 = 0 WHERE menulevel3_id_1 = %d', $bulk_id ) );
			}

			return array( 'success' => 'menulevel1-deleted' );
		}

		/***********************************
		* MENU LEVEL 2
		***********************************/
		public function insert_menulevel2() {
			if ( !wp_easycart_admin_verification()->verify_access( 'wp-easycart-menulevel2-details' ) ) {
				return false;
			}

			global $wpdb;

			$menulevel1_id = (int) $_POST['menulevel1_id'];
			$name = sanitize_text_field( wp_unslash( $_POST['name'] ) );
			$menu_order = (int) $_POST['menu_order'];
			$seo_keywords = sanitize_text_field( wp_unslash( $_POST['seo_keywords'] ) );
			$seo_description = sanitize_text_field( wp_unslash( $_POST['seo_description'] ) );
			$banner_image = sanitize_text_field( wp_unslash( $_POST['banner_image'] ) );
			$post_excerpt = sanitize_text_field( wp_unslash( $_POST['post_excerpt'] ) );
			$featured_image = ( isset( $_POST['featured_image'] ) && '' != $_POST['featured_image'] ) ? (int) $_POST['featured_image'] : 0;

			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_menulevel2( menulevel1_id, name, menu_order, seo_keywords, seo_description, banner_image ) VALUES( %d, %s, %d, %s, %s, %s )', $menulevel1_id, $name, $menu_order, $seo_keywords, $seo_description, $banner_image ) );
			$menulevel2_id = $wpdb->insert_id;
			$post = array(	
				'post_content'	=> '[ec_store submenuid="' . $menulevel2_id . '"]',
				'post_status'	=> 'publish',
				'post_title'	=> wp_easycart_language()->convert_text( $name ),
				'post_type'		=> 'ec_store',
				'post_excerpt'  => $post_excerpt
			);
			$post_id = wp_insert_post( $post, $wp_error );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_menulevel2 SET post_id = %d WHERE menulevel2_id = %d', $post_id, $menulevel2_id ) );
			if ( 0 != $featured_image ) {
				set_post_thumbnail( $post_id, $featured_image );
			}

			return array(
				'success' => 'menulevel2-inserted',
				'menulevel1_id' => (int) $menulevel1_id
			);
		}

		public function update_menulevel2() {
			if ( !wp_easycart_admin_verification()->verify_access( 'wp-easycart-menulevel2-details' ) ) {
				return false;
			}

			global $wpdb;

			$menulevel1_id = (int) $_POST['menulevel1_id'];	
			$menulevel2_id = (int) $_POST['menulevel2_id'];			
			$name = sanitize_text_field( wp_unslash( $_POST['name'] ) );
			$post_slug = preg_replace( '/[^A-Za-z0-9\-]/', '', str_replace( ' ', '-', sanitize_text_field( wp_unslash( $_POST['post_slug'] ) ) ) );
			$menu_order = (int) $_POST['menu_order'];
			$seo_keywords = sanitize_text_field( wp_unslash( $_POST['seo_keywords'] ) );
			$seo_description = sanitize_text_field( wp_unslash( $_POST['seo_description'] ) );
			$banner_image = sanitize_text_field( wp_unslash( $_POST['banner_image'] ) );
			$post_id = (int) $_POST['post_id'];
			$post_excerpt = sanitize_text_field( wp_unslash( $_POST['post_excerpt'] ) );
			$featured_image = ( isset( $_POST['featured_image'] ) && '' != $_POST['featured_image'] ) ? (int) $_POST['featured_image'] : 0;

			$post = array(	
				'ID'			=> $post_id,
				'post_content'	=> '[ec_store submenuid="' . $menulevel2_id . '"]',
				'post_status'	=> 'publish',
				'post_title'	=> wp_easycart_language()->convert_text( $name ),
				'post_type'		=> 'ec_store',
				'post_name'		=> $post_slug,
				'post_excerpt'  => $post_excerpt
			);
			wp_update_post( $post );
			$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'posts SET ' . $wpdb->prefix . 'posts.guid = %s WHERE ' . $wpdb->prefix . 'posts.ID = %d', get_permalink( $post_id ), $post_id ) );

			$wpdb->query( $wpdb->prepare( 'UPDATE ec_menulevel2 SET menulevel1_id = %d, name = %s, menu_order = %d, seo_keywords = %s, seo_description = %s, banner_image = %s WHERE menulevel2_id = %d', $menulevel1_id, $name, $menu_order, $seo_keywords, $seo_description, $banner_image, $menulevel2_id ) );
			if ( 0 == $featured_image ) {
				delete_post_thumbnail( $post_id );
			} else {
				set_post_thumbnail( $post_id, $featured_image );
			}

			return array(
				'success' => 'menulevel2-updated',
				'menulevel1_id' => (int) ( $menulevel1_id )
			);
		}

		public function delete_menulevel2() {
			if ( !wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-menulevel2' ) ) {
				return false;
			}

			global $wpdb;

			$menulevel2_id = (int) $_GET['menulevel2_id'];
			$menulevel2_item = $wpdb->get_row( $wpdb->prepare( 'SELECT menulevel1_id, post_id FROM ec_menulevel2 WHERE menulevel2_id = %d', $menulevel2_id ) );
			$level3_items = $wpdb->get_results( $wpdb->prepare( 'SELECT menulevel3_id, post_id FROM ec_menulevel3 WHERE menulevel2_id = %d', $menulevel2_id ) );

			foreach ( $level3_items as $level3_item ) {
				wp_delete_post( $level3_item->post_id, true );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel1_id_3 = 0 WHERE menulevel1_id_3 = %d', $level3_item->menulevel3_id ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel2_id_3 = 0 WHERE menulevel2_id_3 = %d', $level3_item->menulevel3_id ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel3_id_3 = 0 WHERE menulevel3_id_3 = %d', $level3_item->menulevel3_id ) );
			}
			wp_delete_post( $menulevel2_item->post_id, true );

			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_menulevel3 WHERE menulevel2_id = %d', $menulevel2_id ) );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_menulevel2 WHERE ec_menulevel2.menulevel2_id = %s', $menulevel2_id ) );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel1_id_2 = 0 WHERE menulevel1_id_2 = %d', $menulevel2_id ) );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel2_id_2 = 0 WHERE menulevel2_id_2 = %d', $menulevel2_id) );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel3_id_2 = 0 WHERE menulevel3_id_2 = %d', $menulevel2_id ) );

			return array(
				'success' => 'menulevel2-deleted',
				'menulevel1_id' => (int) $menulevel2_item->menulevel1_id
			);
		}

		public function bulk_delete_menulevel2() {
			if ( !wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-submenus' ) ) {
				return false;
			}

			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				$bulk_id = (int) $bulk_id;

				$menulevel2_item = $wpdb->get_row( $wpdb->prepare( 'SELECT menulevel1_id, post_id FROM ec_menulevel2 WHERE menulevel2_id = %d', $bulk_id ) );
				$level3_items = $wpdb->get_results( $wpdb->prepare( 'SELECT menulevel3_id, post_id FROM ec_menulevel3 WHERE menulevel2_id = %d', $bulk_id ) );

				foreach ( $level3_items as $level3_item ) {
					wp_delete_post( $level3_item->post_id, true );
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel1_id_3 = 0 WHERE menulevel1_id_3 = %d', $level3_item->menulevel3_id ) );
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel2_id_3 = 0 WHERE menulevel2_id_3 = %d', $level3_item->menulevel3_id ) );
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel3_id_3 = 0 WHERE menulevel3_id_3 = %d', $level3_item->menulevel3_id ) );
				}
				wp_delete_post( $menulevel2_item->post_id, true );

				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_menulevel3 WHERE menulevel2_id = %d', $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_menulevel2 WHERE ec_menulevel2.menulevel2_id = %s', $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel1_id_2 = 0 WHERE menulevel1_id_2 = %d', $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel2_id_2 = 0 WHERE menulevel2_id_2 = %d', $bulk_id) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel3_id_2 = 0 WHERE menulevel3_id_2 = %d', $bulk_id ) );
			}

			return array(
				'success' => 'menulevel2-deleted',
				'menulevel1_id' => (int) $menulevel2_item->menulevel1_id
			);
		}

		/*******************************
		* MENU LEVEL 3
		*******************************/
		public function insert_menulevel3() {
			if ( !wp_easycart_admin_verification()->verify_access( 'wp-easycart-menulevel3-details' ) ) {
				return false;
			}

			global $wpdb;

			$menulevel2_id = (int) $_POST['menulevel2_id'];
			$name = sanitize_text_field( wp_unslash( $_POST['name'] ) );
			$menu_order = (int) $_POST['menu_order'];
			$seo_keywords = sanitize_text_field( wp_unslash( $_POST['seo_keywords'] ) );
			$seo_description = sanitize_text_field( wp_unslash( $_POST['seo_description'] ) );
			$banner_image = sanitize_text_field( wp_unslash( $_POST['banner_image'] ) );
			$post_excerpt = sanitize_text_field( wp_unslash( $_POST['post_excerpt'] ) );
			$featured_image = ( isset( $_POST['featured_image'] ) && '' != $_POST['featured_image'] ) ? (int) $_POST['featured_image'] : 0;

			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_menulevel3( menulevel2_id, name, menu_order, seo_keywords, seo_description, banner_image ) VALUES(%d, %s, %d, %s, %s, %s )', $menulevel2_id, $name, $menu_order, $seo_keywords, $seo_description, $banner_image ) );
			$menulevel3_id = $wpdb->insert_id;
			$post = array(	
				'post_content'	=> '[ec_store subsubmenuid="' . $menulevel3_id . '"]',
				'post_status'	=> 'publish',
				'post_title'	=> wp_easycart_language()->convert_text( $name ),
				'post_type'		=> 'ec_store',
				'post_excerpt'  => $post_excerpt
			);
			$post_id = wp_insert_post( $post, $wp_error );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_menulevel3 SET post_id = %d WHERE menulevel3_id = %d', $post_id, $menulevel3_id ) );
			if ( 0 != $featured_image ) {
				set_post_thumbnail( $post_id, $featured_image );
			}

			return array(
				'success' => 'menulevel3-inserted',
				'menulevel2_id' => (int) $menulevel2_id
			);
		}

		public function update_menulevel3() {
			if ( !wp_easycart_admin_verification()->verify_access( 'wp-easycart-menulevel3-details' ) ) {
				return false;
			}

			global $wpdb;

			$menulevel2_id = (int) $_POST['menulevel2_id'];	
			$menulevel3_id = (int) $_POST['menulevel3_id'];			
			$name = sanitize_text_field( wp_unslash( $_POST['name'] ) );
			$post_slug = preg_replace( '/[^A-Za-z0-9\-]/', '', str_replace( ' ', '-', sanitize_text_field( wp_unslash( $_POST['post_slug'] ) ) ) );
			$menu_order = (int) $_POST['menu_order'];
			$seo_keywords = sanitize_text_field( wp_unslash( $_POST['seo_keywords'] ) );
			$seo_description = sanitize_text_field( wp_unslash( $_POST['seo_description'] ) );
			$banner_image = sanitize_text_field( wp_unslash( $_POST['banner_image'] ) );
			$post_id = (int) $_POST['post_id'];
			$post_excerpt = sanitize_text_field( wp_unslash( $_POST['post_excerpt'] ) );
			$featured_image = ( isset( $_POST['featured_image'] ) && '' != $_POST['featured_image'] ) ? (int) $_POST['featured_image'] : 0;

			$post = array(	
				'ID'			=> $post_id,
				'post_content'	=> '[ec_store submenuid="' . $menulevel3_id . '"]',
				'post_status'	=> 'publish',
				'post_title'	=> wp_easycart_language()->convert_text( $name ),
				'post_type'		=> 'ec_store',
				'post_name'		=> $post_slug,
				'post_excerpt'  => $post_excerpt
			);
			wp_update_post( $post );
			$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'posts SET ' . $wpdb->prefix . 'posts.guid = %s WHERE ' . $wpdb->prefix . 'posts.ID = %d', get_permalink( $post_id ), $post_id ) );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_menulevel3 SET menulevel2_id = %d, name = %s, menu_order = %s, seo_keywords = %s, seo_description = %s, banner_image = %s WHERE menulevel3_id = %d', $menulevel2_id, $name, $menu_order, $seo_keywords, $seo_description, $banner_image, $menulevel3_id ) );
			if ( 0 == $featured_image ) {
				delete_post_thumbnail( $post_id );
			} else {
				set_post_thumbnail( $post_id, $featured_image );
			}

			return array(
				'success' => 'menulevel3-updated',
				'menulevel2_id' => (int) $menulevel2_id
			);
		}


		public function delete_menulevel3() {
			if ( !wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-menulevel3' ) ) {
				return false;
			}

			global $wpdb;

			$menulevel3_id = (int) $_GET['menulevel3_id'];
			$menulevel3_item = $wpdb->get_row( $wpdb->prepare( 'SELECT menulevel2_id, post_id FROM ec_menulevel3 WHERE menulevel3_id = %d', $menulevel3_id ) );
			wp_delete_post( $menulevel3_item->post_id, true );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_menulevel3 WHERE menulevel3_id = %s', $menulevel3_id ) );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel1_id_3 = 0 WHERE menulevel1_id_3 = %d', $menulevel3_id ) );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel2_id_3 = 0 WHERE menulevel2_id_3 = %d', $menulevel3_id ) );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel3_id_3 = 0 WHERE menulevel3_id_3 = %d', $menulevel3_id ) );

			return array(
				'success' => 'menulevel3-deleted',
				'menulevel2_id' => (int) $menulevel3_item->menulevel2_id
			);
		}

		public function bulk_delete_menulevel3() {
			if ( !wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-subsubmenus' ) ) {
				return false;
			}

			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				$bulk_id = (int) $bulk_id;
				$menulevel3_item = $wpdb->get_row( $wpdb->prepare( 'SELECT menulevel2_id, post_id FROM ec_menulevel3 WHERE menulevel3_id = %d', $bulk_id ) );
				wp_delete_post( $menulevel3_item->post_id, true );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_menulevel3 WHERE menulevel3_id = %s', $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel1_id_3 = 0 WHERE menulevel1_id_3 = %d', $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel2_id_3 = 0 WHERE menulevel2_id_3 = %d', $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET menulevel3_id_3 = 0 WHERE menulevel3_id_3 = %d', $bulk_id ) );
			}

			return array(
				'success' => 'menulevel3-deleted',
				'menulevel2_id' => (int) $menulevel3_item->menulevel2_id
			);
		}
	}
endif;

function wp_easycart_admin_menus() {
	return wp_easycart_admin_menus::instance();
}
wp_easycart_admin_menus();
