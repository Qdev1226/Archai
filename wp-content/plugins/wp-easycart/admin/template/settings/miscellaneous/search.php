<div class="ec_admin_list_line_item ec_admin_demo_data_line">
            
	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-search"></div>
		<span><?php esc_attr_e( 'Search Options', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url_raw( wp_easycart_admin( )->helpsystem->print_docs_url('settings', 'additional-settings', 'search-options' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
    	<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'additional-settings', 'search-options');?>
	</div>
    <div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
		
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_live_search', 'ec_admin_search_options_update( ); ec_admin_save_additional_options', get_option( 'ec_option_use_live_search' ), __( 'Use Live Search', 'wp-easycart' ), __( 'Enable to show search results as you type in the search widget.', 'wp-easycart' ) ); ?>
		
       	<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_search_title', 'ec_admin_save_additional_options', get_option( 'ec_option_search_title' ), __( 'Search Includes Title', 'wp-easycart' ), __( 'Enable to search product titles in the search widget.', 'wp-easycart' ), 'ec_option_search_title_row', get_option( 'ec_option_use_live_search' ) ); ?>
		
       	<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_search_model_number', 'ec_admin_save_additional_options', get_option( 'ec_option_search_model_number' ), __( 'Search Includes Model Number', 'wp-easycart' ), __( 'Enable to search product model numbers in the search widget.', 'wp-easycart' ), 'ec_option_search_model_number_row', get_option( 'ec_option_use_live_search' ) ); ?>
		
        <?php wp_easycart_admin( )->load_toggle_group( 'ec_option_search_manufacturer', 'ec_admin_save_additional_options', get_option( 'ec_option_search_manufacturer' ), __( 'Search Includes Manufacturer', 'wp-easycart' ), __( 'Enable to search manufacturers in the search widget.', 'wp-easycart' ), 'ec_option_search_manufacturer_row', get_option( 'ec_option_use_live_search' ) ); ?>
		
        <?php wp_easycart_admin( )->load_toggle_group( 'ec_option_search_description', 'ec_admin_save_additional_options', get_option( 'ec_option_search_description' ), __( 'Search Includes Description', 'wp-easycart' ), __( 'Enable to search product descriptions in the search widget.', 'wp-easycart' ), 'ec_option_search_description_row', get_option( 'ec_option_use_live_search' ) ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_search_short_description', 'ec_admin_save_additional_options', get_option( 'ec_option_search_short_description' ), __( 'Search Includes Short Description', 'wp-easycart' ), __( 'Enable to search product short descriptions in the search widget.', 'wp-easycart' ), 'ec_option_search_short_description_row', get_option( 'ec_option_use_live_search' ) ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_search_menu', 'ec_admin_save_additional_options', get_option( 'ec_option_search_menu' ), __( 'Search Includes Menu Items', 'wp-easycart' ), __( 'Enable to search menu item names in the search widget.', 'wp-easycart' ), 'ec_option_search_menu_row', get_option( 'ec_option_use_live_search' ) ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_search_by_or', 'ec_admin_save_additional_options', get_option( 'ec_option_search_by_or' ), __( 'Search Expands Terms', 'wp-easycart' ), __( 'Enable to return more results, matching any words in your search phrase instead of all words.', 'wp-easycart' ), 'ec_option_search_by_or_row', get_option( 'ec_option_use_live_search' ) ); ?>
		
    </div>
</div>