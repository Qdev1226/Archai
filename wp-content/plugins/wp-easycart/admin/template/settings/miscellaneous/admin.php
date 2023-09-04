<div class="ec_admin_list_line_item ec_admin_demo_data_line">
            
	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-admin-tools"></div>
		<span><?php esc_attr_e( 'Product Quick Add Options', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url_raw( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'additional-settings', 'admin-options' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
    	<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'additional-settings', 'admin-options');?>
	</div>
    
    <div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
    	<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_admin_product_show_stock_option', 'ec_admin_save_additional_options', get_option( 'ec_option_admin_product_show_stock_option' ), __( 'Show Stock Options', 'wp-easycart' ), __( 'Enable to show stock options on the product quick creation panel in the admin.', 'wp-easycart' ) ); ?>
		
       	<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_admin_product_show_shipping_option', 'ec_admin_save_additional_options', get_option( 'ec_option_admin_product_show_shipping_option' ), __( 'Show Shipping Options', 'wp-easycart' ), __( 'Enable to show shipping options on the product quick creation panel in the admin.', 'wp-easycart' ) ); ?>
		
       	<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_admin_product_show_tax_option', 'ec_admin_save_additional_options', get_option( 'ec_option_admin_product_show_tax_option' ), __( 'Show Tax Options', 'wp-easycart' ), __( 'Enable to show tax options on the product quick creation panel in the admin.', 'wp-easycart' ) ); ?>
		
       	<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_admin_product_show_variant_option', 'ec_admin_save_additional_options', get_option( 'ec_option_admin_product_show_variant_option' ), __( 'Show Variants', 'wp-easycart' ), __( 'Enable to show variant options on the product quick creation panel in the admin.', 'wp-easycart' ) ); ?>
    </div>

</div>

<div class="ec_admin_list_line_item ec_admin_demo_data_line">
    
	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-rss"></div>
		<span><?php esc_attr_e( 'Admin Apps Options (Premium Only)', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url_raw( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'additional-settings', 'admin-options' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
    	<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'additional-settings', 'admin-options'); ?>
	</div>
    
	<div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
		
       	<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_enable_push_notifications', 'ec_admin_save_additional_options', get_option( 'ec_option_enable_push_notifications' ), __( 'App Notifications', 'wp-easycart' ), __( 'Enable to receive push notifications on your WP EasyCart apps (must have push notifications enabled!).', 'wp-easycart' ) ); ?>
		
    </div>
	
</div>