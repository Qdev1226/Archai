<div class="ec_admin_list_line_item">

	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_list_display_loader" ); ?>

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-screenoptions"></div>
		<span><?php esc_attr_e( 'Product List Display', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'product-settings', 'product-list' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'product-settings', 'product-list');?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section">

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_show_sort_box', 'wpeasycart_admin_update_sort_box_view( ); ec_admin_save_product_options', get_option( 'ec_option_show_sort_box' ), __( 'Product Sorting', 'wp-easycart' ), __( 'Enabling this allows your customers to sort by things like price, title, and rating.', 'wp-easycart' ) ); ?>

		<?php 
		$sort_options = array(
			(object) array(
				'value'	=> '0',
				'label'	=> __( 'Default Sorting (admin determined sort order)', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '1',
				'label'	=> __( 'Price Low-High', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '2',
				'label'	=> __( 'Price High-Low', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '3',
				'label'	=> __( 'Title A-Z', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '4',
				'label'	=> __( 'Title Z-A', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '5',
				'label'	=> __( 'Newest First', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '8',
				'label'	=> __( 'Oldest First', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '6',
				'label'	=> __( 'Best Rating First', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '7',
				'label'	=> __( 'Most Viewed', 'wp-easycart' )
			)
		);
		?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_default_store_filter', 'ec_admin_save_product_text_setting', get_option( 'ec_option_default_store_filter' ), __( 'Product Sorting: Default Selection', 'wp-easycart' ), __( 'This is the default selected option when a customer first visits your store.', 'wp-easycart' ), $sort_options, 'ec_admin_store_filter_default_row', ( ( get_option( 'ec_option_show_sort_box' ) == '1' ) ? true : false ), false ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_product_filter_0', 'ec_admin_save_product_options', get_option( 'ec_option_product_filter_0' ), __( 'Product Sorting: Default Sorting', 'wp-easycart' ), __( 'Enabling this to show default sorting in the sort box.', 'wp-easycart' ), 'ec_admin_store_filter_0_row', ( ( get_option( 'ec_option_show_sort_box' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_product_filter_1', 'ec_admin_save_product_options', get_option( 'ec_option_product_filter_1' ), __( 'Product Sorting: Price Low-High', 'wp-easycart' ), __( 'Enabling this to show pricing low-high in the sort box.', 'wp-easycart' ), 'ec_admin_store_filter_1_row', ( ( get_option( 'ec_option_show_sort_box' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_product_filter_2', 'ec_admin_save_product_options', get_option( 'ec_option_product_filter_2' ), __( 'Product Sorting: Price High-Low', 'wp-easycart' ), __( 'Enabling this to show pricing high-low in the sort box.', 'wp-easycart' ), 'ec_admin_store_filter_2_row', ( ( get_option( 'ec_option_show_sort_box' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_product_filter_3', 'ec_admin_save_product_options', get_option( 'ec_option_product_filter_3' ), __( 'Product Sorting: Title A-Z', 'wp-easycart' ), __( 'Enabling this to show title a-z in the sort box.', 'wp-easycart' ), 'ec_admin_store_filter_3_row', ( ( get_option( 'ec_option_show_sort_box' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_product_filter_4', 'ec_admin_save_product_options', get_option( 'ec_option_product_filter_4' ), __( 'Product Sorting: Title Z-A', 'wp-easycart' ), __( 'Enabling this to show title z-a in the sort box.', 'wp-easycart' ), 'ec_admin_store_filter_4_row', ( ( get_option( 'ec_option_show_sort_box' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_product_filter_5', 'ec_admin_save_product_options', get_option( 'ec_option_product_filter_5' ), __( 'Product Sorting: Newest', 'wp-easycart' ), __( 'Enabling this to show sort by newest in the sort box.', 'wp-easycart' ), 'ec_admin_store_filter_5_row', ( ( get_option( 'ec_option_show_sort_box' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_product_filter_8', 'ec_admin_save_product_options', get_option( 'ec_option_product_filter_8' ), __( 'Product Sorting: Oldest', 'wp-easycart' ), __( 'Enabling this to show sort by oldest in the sort box.', 'wp-easycart' ), 'ec_admin_store_filter_8_row', ( ( get_option( 'ec_option_show_sort_box' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_product_filter_6', 'ec_admin_save_product_options', get_option( 'ec_option_product_filter_6' ), __( 'Product Sorting: Best Rating', 'wp-easycart' ), __( 'Enabling this to show sort by rating in the sort box.', 'wp-easycart' ), 'ec_admin_store_filter_6_row', ( ( get_option( 'ec_option_show_sort_box' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_product_filter_7', 'ec_admin_save_product_options', get_option( 'ec_option_product_filter_7' ), __( 'Product Sorting: Most Viewed', 'wp-easycart' ), __( 'Enabling this to show sort by most viewed in the sort box.', 'wp-easycart' ), 'ec_admin_store_filter_7_row', ( ( get_option( 'ec_option_show_sort_box' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_short_description_on_product', 'ec_admin_save_product_options', get_option( 'ec_option_short_description_on_product' ), __( 'Product Grid Type: Display Short Description', 'wp-easycart' ), __( 'Enabling this will show the short description on the grid layout type.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_show_featured_categories', 'ec_admin_save_product_options', get_option( 'ec_option_show_featured_categories' ), __( 'Product List: Show Featured Categories First', 'wp-easycart' ), __( 'Enabling this will show the featured categories first on the store landing page.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_hide_out_of_stock', 'ec_admin_save_product_options', get_option( 'ec_option_hide_out_of_stock' ), __( 'Product List: Hide out of Stock', 'wp-easycart' ), __( 'Enabling this will hide items that are out of stock from the store, disabling shows products with out of stock notice.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_product_no_checkout_button', 'ec_admin_save_product_options', get_option( 'ec_option_product_no_checkout_button' ), __( 'Product List: Keep Add to Cart Button', 'wp-easycart' ), __( 'Enabling this will keep the add to cart button on a product, rather than switching to checkout. The view cart bar will still appear.', 'wp-easycart' ) ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_redirect_add_to_cart', 'ec_admin_save_product_options', get_option( 'ec_option_redirect_add_to_cart' ), __( 'Product List: Redirect Add to Cart', 'wp-easycart' ), __( 'Enabling this will send a customer to checkout as soon as the product is added to cart from the list.', 'wp-easycart' ) ); ?>
		
		<?php do_action( 'wpeasycart_admin_products_list_options' ); ?>

	</div>

</div>