<?php
/**
 * WP EasyCart Wrap Class for Gutenberg
 *
 * @category Class
 * @package  Wp_Easycart_Gutenberg
 * @author   WP EasyCart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Wp_Easycart_Gutenberg' ) ) :

	/**
	 * WP EasyCart Wrap Class for Gutenberg
	 *
	 * @category Class
	 * @package  Wp_Easycart_Gutenberg
	 * @author   WP EasyCart
	 * @param Wp_Easycart_Gutenberg $_instance storage of own instance.
	 */
	final class Wp_Easycart_Gutenberg {

		/**
		 * Wp_Easycart_Gutenberg object. This is the storage variable for this class instance.
		 *
		 * @var Wp_Easycart_Gutenberg
		 */
		protected static $_instance = null;

		/**
		 * WP EasyCart Gutenberg Instance Contructor.
		 */
		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;

		}

		/**
		 * WP EasyCart Gutenberg Contructor.
		 */
		public function __construct() {

			add_action( 'enqueue_block_editor_assets', array( $this, 'load_block_editor_assets' ) );
			add_filter( 'block_categories_all', array( $this, 'add_block_category' ), 10, 2 );

		}

		/**
		 * Add block category to Gutenberg.
		 *
		 * @param array  $block_categories full list of categories from WordPress.
		 * @param string $block_editor_context context of filter.
		 */
		public function add_block_category( $block_categories, $block_editor_context ) {

			return array_merge(
				$block_categories,
				array(
					array(
						'slug'  => 'wp-easycart',
						'title' => __( 'WP EasyCart', 'wp-easycart' ),
						'icon'  => 'wp-easycart',
					),
				)
			);

		}

		/**
		 * Load the assets for Gutenber editor if applicable.
		 */
		public function load_block_editor_assets() {
			if ( ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) && is_admin() ) {
				wp_register_script(
					'wp_easycart_block_init_js',
					plugins_url( 'wp-easycart/admin/gutenberg/assets/js/wp-easycart-gutenberg-init.js', EC_PLUGIN_DIRECTORY ),
					array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
					EC_CURRENT_VERSION
				);
				wp_enqueue_script( 'wp_easycart_block_init_js' );
				wp_localize_script(
					'wp_easycart_block_init_js',
					'wpeasycart_guten',
					array(
						'postid' => ( ( isset( $_GET['post'] ) ) ? (int) $_GET['post'] : 0 ),
						'jsonurl' => str_replace( 'http://', 'https://', get_rest_url() ),
					)
				);
				wp_register_script(
					'wp_easycart_block_store_js',
					plugins_url( 'wp-easycart/admin/gutenberg/assets/js/wp-easycart-gutenberg-store.js', EC_PLUGIN_DIRECTORY ),
					array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components' ),
					EC_CURRENT_VERSION
				);
				wp_enqueue_script( 'wp_easycart_block_store_js' );
				wp_register_script(
					'wp_easycart_block_search_js',
					plugins_url( 'wp-easycart/admin/gutenberg/assets/js/wp-easycart-gutenberg-search.js', EC_PLUGIN_DIRECTORY ),
					array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components' ),
					EC_CURRENT_VERSION
				);
				wp_enqueue_script( 'wp_easycart_block_search_js' );
				wp_register_script(
					'wp_easycart_block_js',
					plugins_url( 'wp-easycart/admin/js/block.js', EC_PLUGIN_DIRECTORY ),
					array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
					EC_CURRENT_VERSION
				);
				wp_enqueue_script( 'wp_easycart_block_js' );
				$preview_images = array(
					'store' => plugins_url( 'wp-easycart/admin/gutenberg/assets/images/store.jpg?v=' . EC_CURRENT_VERSION, EC_PLUGIN_DIRECTORY ),
					'store-icon' => plugins_url( 'wp-easycart/admin/gutenberg/assets/images/store-icon.png?v=' . EC_CURRENT_VERSION, EC_PLUGIN_DIRECTORY ),
					'search' => plugins_url( 'wp-easycart/admin/gutenberg/assets/images/search.jpg?v=' . EC_CURRENT_VERSION, EC_PLUGIN_DIRECTORY ),
					'search-icon' => plugins_url( 'wp-easycart/admin/gutenberg/assets/images/search-icon.png?v=' . EC_CURRENT_VERSION, EC_PLUGIN_DIRECTORY ),
				);
				wp_localize_script(
					'wp_easycart_block_js',
					'wp_easycart_admin_preview_images',
					$preview_images
				);
				$wp_easycart_admin_block_language = array(
					'store'             => __( 'Store', 'wp-easycart' ),
					'cart'              => __( 'Cart', 'wp-easycart' ),
					'account'           => __( 'Account', 'wp-easycart' ),
					'category-standard' => __( 'Category Standard Display', 'wp-easycart' ),
					'category-grid'     => __( 'Category Grid Display', 'wp-easycart' ),
					'store-table' => __( 'Store Table Display', 'wp-easycart' ),
					'product-display' => __( 'Product Display', 'wp-easycart' ),
					'add-to-cart-button' => __( 'Add to Cart Button', 'wp-easycart' ),
					'cart-display' => __( 'Cart Display', 'wp-easycart' ),
					'membership-content' => __( 'Membership Content', 'wp-easycart' ),
					'product-id' => __( 'Product ID', 'wp-easycart' ),
					'model-number' => __( 'Model Number', 'wp-easycart' ),
					'title' => __( 'Title', 'wp-easycart' ),
					'price' => __( 'Price', 'wp-easycart' ),
					'details-link' => __( 'Details Link', 'wp-easycart' ),
					'description' => __( 'Description', 'wp-easycart' ),
					'specifications' => __( 'Specifications', 'wp-easycart' ),
					'stock-quantity' => __( 'Stock Quantity', 'wp-easycart' ),
					'weight' => __( 'Weight', 'wp-easycart' ),
					'width' => __( 'Width', 'wp-easycart' ),
					'height' => __( 'Height', 'wp-easycart' ),
					'length' => __( 'Length', 'wp-easycart' ),
					'shortcode' => __( 'WP EasyCart Shortcode', 'wp-easycart' ),
					'add-product-filters' => __( 'Add Product Filters', 'wp-easycart' ),
					'add-filters' => __( 'Add Filters', 'wp-easycart' ),
					'show-featured-only' => __( 'Show Featured Items Only', 'wp-easycart' ),
					'filter-category' => __( 'Filter by Category', 'wp-easycart' ),
					'filter-menu' => __( 'Filter by Menu', 'wp-easycart' ),
					'filter-submenu' => __( 'Filter by Sub-Menu', 'wp-easycart' ),
					'filter-subsubmenu' => __( 'Filter by Sub-Sub-Menu', 'wp-easycart' ),
					'filter-manufacturer' => __( 'Filter by Manufacturer', 'wp-easycart' ),
					'fitler-product' => __( 'Filter by Product', 'wp-easycart' ),
					'category-filter' => __( 'Category Filter', 'wp-easycart' ),
					'show-all-categories' => __( 'Show all Categories', 'wp-easycart' ),
					'enter-category-id' => __( 'Enter Category ID', 'wp-easycart' ),
					'manufacturer-filter' => __( 'Manufacturer Filter', 'wp-easycart' ),
					'show-all-manufacturers' => __( 'Show all Manufacturers', 'wp-easycart' ),
					'enter-manufacturer-id' => __( 'Enter Manufacturer ID', 'wp-easycart' ),
					'menu-filter' => __( 'Menu Filter', 'wp-easycart' ),
					'show-all-menus' => __( 'Show all Menus', 'wp-easycart' ),
					'enter-menu-level1-id' => __( 'Enter Menu Level 1 ID', 'wp-easycart' ),
					'sub-menu-filter' => __( 'Sub Menu Filter', 'wp-easycart' ),
					'show-all-sub-menus' => __( 'Show all Sub Menus', 'wp-easycart' ),
					'enter-menu-level2-id' => __( 'Enter Menu Level 2 ID', 'wp-easycart' ),
					'sub-sub-menu-filter' => __( 'Sub Sub Menu Filter', 'wp-easycart' ),
					'show-all-sub-sub-menus' => __( 'Show all Sub Sub Menus', 'wp-easycart' ),
					'enter-menu-level3-id' => __( 'Enter Menu Level 3 ID', 'wp-easycart' ),
					'product-to-display' => __( 'Product to Display', 'wp-easycart' ),
					'no-product-filter' => __( 'No Product Filter', 'wp-easycart' ),
					'enter-product-sku' => __( 'Enter Product SKU', 'wp-easycart' ),
					'success-redirect-url' => __( 'On Success Redirect URL (optional, default is the account dashboard)', 'wp-easycart' ),
					'categories-to-display' => __( 'Categories to Display', 'wp-easycart' ),
					'show-featured-categories' => __( 'Show Featured Categories', 'wp-easycart' ),
					'show-top-level-categories' => __( 'Show Top Level Categories', 'wp-easycart' ),
					'categories-to-display' => __( 'Categories to Display', 'wp-easycart' ),
					'columns' => __( 'Columns', 'wp-easycart' ),
					'1column' => __( '1 Column', 'wp-easycart' ),
					'2columns' => __( '2 Columns', 'wp-easycart' ),
					'3columns' => __( '3 Columns', 'wp-easycart' ),
					'4columns' => __( '4 Columns', 'wp-easycart' ),
					'5columns' => __( '5 Columns', 'wp-easycart' ),
					'6columns' => __( '6 Columns', 'wp-easycart' ),
					'products-to-display' => __( 'Products to Display', 'wp-easycart' ),
					'enter-product-ids' => __( 'Enter Product IDs (Comma Separated)', 'wp-easycart' ),
					'menus-to-display' => __( 'Menus to Display', 'wp-easycart' ),
					'enter-menu-level1-ids' => __( 'Enter Menu Level 1 IDs (Comma Separated)', 'wp-easycart' ),
					'submenus-to-display' => __( 'Sub-Menus to Display', 'wp-easycart' ),
					'enter-menu-level2-ids' => __( 'Enter Menu Level 2 IDs (Comma Separated)', 'wp-easycart' ),
					'subsubmenus-to-display' => __( 'Sub-Sub-Menus to Display', 'wp-easycart' ),
					'enter-menu-level3-ids' => __( 'Enter Menu Level 3 IDs (Comma Separated)', 'wp-easycart' ),
					'enter-category-ids' => __( 'Enter Category IDs (Comma Separated)', 'wp-easycart' ),
					'column1-label' => __( 'Column 1 Label', 'wp-easycart' ),
					'column1-data' => __( 'Column 1 Data', 'wp-easycart' ),
					'column2-label' => __( 'Column 2 Label', 'wp-easycart' ),
					'column2-data' => __( 'Column 2 Data', 'wp-easycart' ),
					'column3-label' => __( 'Column 3 Label', 'wp-easycart' ),
					'column3-data' => __( 'Column 3 Data', 'wp-easycart' ),
					'column4-label' => __( 'Column 4 Label', 'wp-easycart' ),
					'column4-data' => __( 'Column 4 Data', 'wp-easycart' ),
					'column5-label' => __( 'Column 5 Label', 'wp-easycart' ),
					'column5-data' => __( 'Column 5 Data', 'wp-easycart' ),
					'link-label' => __( 'Link Label (Optional)', 'wp-easycart' ),
					'product-to-display' => __( 'Product to Display', 'wp-easycart' ),
					'enter-product-id' => __( 'Enter Product ID', 'wp-easycart' ),
					'display-type' => __( 'Display Type', 'wp-easycart' ),
					'display-type1' => __( 'Default Product Display Type', 'wp-easycart' ),
					'display-type2' => __( 'Same as Product Widget Display', 'wp-easycart' ),
					'display-type3' => __( 'Custom Display Type 1', 'wp-easycart' ),
					'background-type' => __( 'Background Add', 'wp-easycart' ),
					'background-type0' => __( 'No, Redirect to Cart', 'wp-easycart' ),
					'background-type1' => __( 'Yes, Add in Background', 'wp-easycart' ),
					'purchase-required-1' => __( 'Purchase Required for Access (only one neeeded for access if multiple selected)', 'wp-easycart' ),
					'purchase-required-2' => __( 'Purchase Required for Access - Enter Product IDs (Comma Separated)', 'wp-easycart' ),
					'add-to-cart' => __( 'Add to Cart', 'wp-easycart' ),
					'no-categories' => __( 'No Categories found.', 'wp-easycart' ),
					'categories-selected' => __( '%d categories selected', 'wp-easycart' ),
					'clear-all' => __( 'Clear all', 'wp-easycart' ),
					'search-placeholder' => __( 'Start typing to search', 'wp-easycart' ),
					'done' => __( 'Done', 'wp-easycart' ),
					'cancel' => __( 'Cancel', 'wp-easycart' ),
					'store-category' => __( 'Store by Category', 'wp-easycart' ),
					'edit' => __( 'Edit', 'wp-easycart' ),
					'edit-block' => __( 'Edit Block', 'wp-easycart' ),
					'store-category-desc' => __( 'Display a complete store with products filtered by category', 'wp-easycart' ),
					'store-categories-empty' => __( 'No items found for your selection. Edit this block to select more categories.', 'wp-easycart' ),
					'products' => __( 'products', 'wp-easycart' ),
					'search-title' => __( 'WP EasyCart Search', 'wp-easycart' ),
					'search-desc' => __( 'Add a store search bar shortcode anywhere on your site.', 'wp-easycart' ),
				);
				wp_localize_script( 'wp_easycart_block_js', 'wp_easycart_admin_block_language', $wp_easycart_admin_block_language );
				wp_localize_script( 'wp_easycart_block_js', 'wp_easycart_categories', $this->get_categories_cdata() );
				wp_localize_script( 'wp_easycart_block_js', 'wp_easycart_manufacturers', $this->get_manufacturers_cdata() );
				wp_localize_script( 'wp_easycart_block_js', 'wp_easycart_menulevel1', $this->get_menu_level1_cdata() );
				wp_localize_script( 'wp_easycart_block_js', 'wp_easycart_menulevel2', $this->get_menu_level2_cdata() );
				wp_localize_script( 'wp_easycart_block_js', 'wp_easycart_menulevel3', $this->get_menu_level3_cdata() );
				wp_localize_script( 'wp_easycart_block_js', 'wp_easycart_products', $this->get_products_cdata() );
				wp_localize_script( 'wp_easycart_block_js', 'wp_easycart_products_model', $this->get_products_model_cdata() );

				wp_register_style( 'wp_easycart_block_css', plugins_url( 'wp-easycart/admin/gutenberg/assets/css/block.css', EC_PLUGIN_DIRECTORY ), array(), EC_CURRENT_VERSION );
				wp_enqueue_style( 'wp_easycart_block_css' );
			}
		}

		/**
		 * Get category cdata for use in javascript.
		 */
		private function get_categories_cdata() {
			global $wpdb;
			return $wpdb->get_results( 'SELECT category_id AS value, category_name AS label FROM ec_category ORDER BY category_name ASC LIMIT 2000' );
		}

		/**
		 * Get manufacturer cdata for use in javascript.
		 */
		private function get_manufacturers_cdata() {
			global $wpdb;
			return $wpdb->get_results( 'SELECT manufacturer_id AS value, ec_manufacturer.`name` AS label FROM ec_manufacturer ORDER BY ec_manufacturer.`name` ASC LIMIT 2000' );
		}

		/**
		 * Get menu level 1 cdata for use in javascript.
		 */
		private function get_menu_level1_cdata() {
			global $wpdb;
			return $wpdb->get_results( 'SELECT menulevel1_id AS value, ec_menulevel1.`name` AS label FROM ec_menulevel1 ORDER BY ec_menulevel1.`name` ASC LIMIT 2000' );
		}

		/**
		 * Get menu level 2 cdata for use in javascript.
		 */
		private function get_menu_level2_cdata() {
			global $wpdb;
			return $wpdb->get_results( 'SELECT menulevel1_id, menulevel2_id AS value, ec_menulevel2.`name` AS label FROM ec_menulevel2 ORDER BY ec_menulevel2.`name` ASC LIMIT 2000' );
		}

		/**
		 * Get menu level 3 cdata for use in javascript.
		 */
		private function get_menu_level3_cdata() {
			global $wpdb;
			return $wpdb->get_results( 'SELECT menulevel2_id, menulevel3_id AS value, ec_menulevel3.`name` AS label FROM ec_menulevel3 ORDER BY ec_menulevel3.`name` ASC LIMIT 2000' );
		}

		/**
		 * Get products cdata for use in javascript.
		 */
		private function get_products_cdata() {
			global $wpdb;
			return $wpdb->get_results( 'SELECT product_id AS value, ec_product.`title` AS label FROM ec_product ORDER BY ec_product.`title` ASC LIMIT 2000' );
		}

		/**
		 * Get products model number cdata for use in javascript.
		 */
		private function get_products_model_cdata() {
			global $wpdb;
			return $wpdb->get_results( 'SELECT model_number AS value, ec_product.`title` AS label FROM ec_product ORDER BY ec_product.`title` ASC LIMIT 2000' );
		}
	}
endif;
