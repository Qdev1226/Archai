<div class="ec_admin_list_line_item">
            
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_cartpage_loader" ); ?>
    
    <div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-cart"></div>
		<span><?php esc_attr_e( 'Cart Page', 'wp-easycart' ); ?></span>
    	<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'initial-setup', 'cart-page' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
    	<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'initial-setup', 'cart-page');?>
    </div>
    
	<div class="ec_admin_settings_input wp_easycart_admin_no_padding">
		
        <?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_cartpage_settings_nonce', 'wp-easycart-initial-setup-cartpage-setup' ); ?>
		
		<?php $pages = get_pages( ); ?>
		<?php $page_options = array( ); ?>
		<?php foreach( $pages as $page ){ 
			$page_options[] = (object) array(
				'value'	=> $page->ID,
				'label'	=> $page->post_title
			);
		} ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_cartpage', 'ec_admin_save_initial_setup_text_setting', get_option( 'ec_option_cartpage' ), __( 'Cart Page', 'wp-easycart' ), __( 'This is your cart & checkout page.  This page will contain [ec_cart] shortcode and is required. ', 'wp-easycart' ), $page_options, 'ec_admin_cart_page_row', true, true ); ?>
		
		<a href="admin.php?page=wp-easycart-settings&subpage=initial-setup&action=easycart-add-cartpage" onclick="return ec_admin_create_cartpage( );" class="wp-easycart-admin-small-button"><?php esc_attr_e( 'Create New Cart Page', 'wp-easycart' ); ?></a>
	
	</div>

    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_cartpage_setup( );" value="<?php esc_attr_e( 'Save Selection', 'wp-easycart' ); ?>" />
    </div>
    
</div>