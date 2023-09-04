<?php global $wpdb; ?>
<div class="ec_admin_list_line_item">

	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_store_defaults_display_loader" ); ?>

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-screenoptions"></div>
		<span><?php esc_attr_e( 'Store Default Settings', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'product-settings', 'product-store-defaults' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'product-settings', 'product-store-defaults');?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section">

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_enable_product_paging', 'ec_admin_save_product_options', get_option( 'ec_option_enable_product_paging' ), __( 'Store Defaults: Enable Product Paging', 'wp-easycart' ), __( 'Enabling this adds paging to the store page. Disable to show all products instead.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_enable_product_paging_per_page', 'ec_admin_save_product_text_setting', get_option( 'ec_option_enable_product_paging_per_page' ), __( 'Store Defaults: Products Per Page', 'wp-easycart' ), __( 'Enter a whole number here if you wish to override the products per page options under the customize settings section.', 'wp-easycart' ), '', 'ec_admin_enable_product_paging_per_page_row', true, false, false ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_show_store_sidebar', 'wpeasycart_admin_update_sidebar_view(); ec_admin_save_product_options', get_option( 'ec_option_show_store_sidebar' ), __( 'Store Defaults: Sidebar', 'wp-easycart' ), __( 'This allows you to enable a sidebar within your store.', 'wp-easycart' ) ); ?>

		<?php 
		$sidebar_options = array(
			(object) array(
				'value'	=> 'left',
				'label'	=> __( 'Sidebar Left', 'wp-easycart' )
			),
			(object) array(
				'value'	=> 'right',
				'label'	=> __( 'Sidebar Right', 'wp-easycart' )
			),
			(object) array(
				'value'	=> 'slide-left',
				'label'	=> __( 'Slideout from Left', 'wp-easycart' )
			),
			(object) array(
				'value'	=> 'slide-right',
				'label'	=> __( 'Slideout from Right', 'wp-easycart' )
			),
		);
		$sidebar_filter_method = array(
			(object) array(
				'value'	=> 'AND',
				'label'	=> __( 'Method: AND', 'wp-easycart' )
			),
			(object) array(
				'value'	=> 'OR',
				'label'	=> __( 'Method: OR', 'wp-easycart' )
			),
		);
		$sidebar_open_method = array(
			(object) array(
				'value'	=> '0',
				'label'	=> __( 'All Closed', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '1',
				'label'	=> __( 'All Open', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '2',
				'label'	=> __( 'First Open', 'wp-easycart' )
			),
		);
		$sidebar_categories = $wpdb->get_results( 'SELECT ec_category.category_id AS value, ec_category.category_name AS label FROM ec_category ORDER BY priority DESC' );
		?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_store_sidebar_position', 'ec_admin_save_product_text_setting', get_option( 'ec_option_store_sidebar_position' ), __( 'Store Defaults: Sidebar Position', 'wp-easycart' ), __( 'This allows you to choose the best position for the sidebar on your store.', 'wp-easycart' ), $sidebar_options, 'ec_admin_store_sidebar_position_row', ( ( get_option( 'ec_option_show_store_sidebar' ) == '1' ) ? true : false ), false ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_store_sidebar_filter_clear', 'ec_admin_save_product_options', get_option( 'ec_option_store_sidebar_filter_clear' ), __( 'Store Defaults: Enable Clear Filter Feature', 'wp-easycart' ), __( 'Enable this to show a filter clear feature in your sidebar.', 'wp-easycart' ), 'ec_admin_store_sidebar_filter_clear_row', ( ( get_option( 'ec_option_show_store_sidebar' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_store_sidebar_include_search', 'wpeasycart_admin_update_sidebar_view(); ec_admin_save_product_options', get_option( 'ec_option_store_sidebar_include_search' ), __( 'Store Defaults: Enable Sidebar Search', 'wp-easycart' ), __( 'Enable this to show a search box in your sidebar.', 'wp-easycart' ), 'ec_admin_store_sidebar_include_search_row', ( ( get_option( 'ec_option_show_store_sidebar' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_store_sidebar_include_categories', 'wpeasycart_admin_update_sidebar_view(); ec_admin_save_product_options', get_option( 'ec_option_store_sidebar_include_categories' ), __( 'Store Defaults: Enable Sidebar Category Links', 'wp-easycart' ), __( 'Enable this to show simple category links that you can customize in your sidebar.', 'wp-easycart' ), 'ec_admin_store_sidebar_include_categories_row', ( ( get_option( 'ec_option_show_store_sidebar' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_store_sidebar_categories', 'ec_admin_save_product_text_setting', ( ( get_option( 'ec_option_store_sidebar_categories' ) ) ? explode( ',', get_option( 'ec_option_store_sidebar_categories' ) ) : array() ), __( 'Store Defaults: Sidebar Categories', 'wp-easycart' ), __( 'Select the categories to add to your sidebar filter.', 'wp-easycart' ), ( ( $sidebar_categories ) ? $sidebar_categories : array() ), 'ec_admin_store_sidebar_categories_row', ( ( get_option( 'ec_option_show_store_sidebar' ) == '1' && get_option( 'ec_option_store_sidebar_include_categories' ) == '1' ) ? true : false ), false, true ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_sidebar_include_categories_first', 'ec_admin_save_product_options', get_option( 'ec_option_sidebar_include_categories_first' ), __( 'Store Defaults: Show Category Filters First', 'wp-easycart' ), __( 'Enable this to show categories before options in your sidebar.', 'wp-easycart' ), 'ec_admin_include_categories_first_row', ( ( get_option( 'ec_option_show_store_sidebar' ) == '1' && get_option( 'ec_option_store_sidebar_include_categories' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_sidebar_include_category_filters', 'wpeasycart_admin_update_sidebar_view(); ec_admin_save_product_options', get_option( 'ec_option_sidebar_include_category_filters' ), __( 'Store Defaults: Complex Category Filters', 'wp-easycart' ), __( 'Enable this to show category filters in your sidebar.', 'wp-easycart' ), 'ec_admin_sidebar_include_category_filters_row', ( ( get_option( 'ec_option_show_store_sidebar' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_sidebar_category_filter_id', 'ec_admin_save_product_text_setting', get_option( 'ec_option_sidebar_category_filter_id' ), __( 'Store Defaults: Category Top Level', 'wp-easycart' ), __( 'Select the top level to server as a category filter. Sub-categories will become groups that may be selected and sub-sub-categories become the selectable items.', 'wp-easycart' ), ( ( $sidebar_categories ) ? $sidebar_categories : array() ), 'ec_admin_sidebar_category_filter_id_row', ( ( get_option( 'ec_option_show_store_sidebar' ) == '1' && get_option( 'ec_option_sidebar_include_category_filters' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_sidebar_category_filter_method', 'ec_admin_save_product_text_setting', get_option( 'ec_option_sidebar_category_filter_method' ), __( 'Store Defaults: Filter Method', 'wp-easycart' ), __( 'This allows you to combine choices with an AND between groups or use an OR to allow any items in any selection.', 'wp-easycart' ), $sidebar_filter_method, 'ec_option_sidebar_category_filter_method_row', ( ( get_option( 'ec_option_show_store_sidebar' ) == '1' && get_option( 'ec_option_sidebar_include_category_filters' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_sidebar_category_filter_open', 'ec_admin_save_product_text_setting', get_option( 'ec_option_sidebar_category_filter_open' ), __( 'Store Defaults: Filters Open by Default', 'wp-easycart' ), __( 'Enable this to show filters expanded by default.', 'wp-easycart' ), $sidebar_open_method, 'ec_option_sidebar_category_filter_open_row', ( ( get_option( 'ec_option_show_store_sidebar' ) == '1' && get_option( 'ec_option_sidebar_include_category_filters' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_sidebar_include_option_filters', 'wpeasycart_admin_update_sidebar_view(); ec_admin_save_product_options', get_option( 'ec_option_sidebar_include_option_filters' ), __( 'Store Defaults: Show Option Filters', 'wp-easycart' ), __( 'Enable this to show option filters in your sidebar.', 'wp-easycart' ), 'ec_admin_sidebar_include_option_filters_row', ( ( get_option( 'ec_option_show_store_sidebar' ) == '1' ) ? true : false ) ); ?>

		<?php $sidebar_options = $wpdb->get_results( 'SELECT option_id AS value, option_name AS label FROM ec_option ORDER BY option_name ASC' ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_store_sidebar_option_filters', 'ec_admin_save_product_text_setting', ( ( get_option( 'ec_option_store_sidebar_option_filters' ) ) ? explode( ',', get_option( 'ec_option_store_sidebar_option_filters' ) ) : array() ), __( 'Store Defaults: Sidebar Options', 'wp-easycart' ), __( 'Select the option sets to add to your sidebar filter.', 'wp-easycart' ), ( ( $sidebar_options ) ? $sidebar_options : array() ), 'ec_admin_store_sidebar_option_filters_row', ( ( get_option( 'ec_option_show_store_sidebar' ) == '1' && get_option( 'ec_option_sidebar_include_option_filters' ) == '1' ) ? true : false ), false, true ); ?>

	</div>

</div>