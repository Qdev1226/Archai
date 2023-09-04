<div class="ec_admin_list_line_item">
            
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_storepage_loader" ); ?>
    
    <div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-products"></div>
		<span><?php esc_attr_e( 'Store Landing Page', 'wp-easycart' ); ?></span>
    	<a href="<?php echo esc_attr( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'initial-setup', 'product-page' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
    	<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'initial-setup', 'product-page');?>
    </div>
    
	<div class="ec_admin_settings_input wp_easycart_admin_no_padding">
		
        <?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_storepage_settings_nonce', 'wp-easycart-initial-setup-storepage-setup' ); ?>
		
		<?php $pages = get_pages( ); ?>
		<?php $page_options = array( ); ?>
		<?php foreach( $pages as $page ){ 
			$page_options[] = (object) array(
				'value'	=> $page->ID,
				'label'	=> $page->post_title
			);
		} ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_storepage', 'ec_admin_save_initial_setup_text_setting', get_option( 'ec_option_storepage' ), __( 'Store Landing Page', 'wp-easycart' ), __( 'This is your main store landing page.  This page will contain [ec_store] shortcode and is required, but does not necessarily need to be on your menu system.', 'wp-easycart' ), $page_options, 'ec_admin_store_page_row', true, true ); ?>
		
		<a href="admin.php?page=wp-easycart-settings&subpage=initial-setup&action=easycart-add-storepage" onclick="return ec_admin_create_storepage( );" class="wp-easycart-admin-small-button"><?php esc_attr_e( 'Create New Store Page', 'wp-easycart' ); ?></a>
		
    </div>

    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_storepage_setup( );" value="<?php esc_attr_e( 'Save Selection', 'wp-easycart' ); ?>" />
    </div>
    
</div>