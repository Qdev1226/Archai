<div class="ec_admin_list_line_item ec_admin_demo_data_line">
            
	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-admin-generic"></div>
		<span><?php esc_attr_e( 'Checkout Stock Control', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'checkout', 'stock-control' ) );?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div>  <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
    	<?php wp_easycart_admin( )->helpsystem->print_vids_url( 'settings', 'checkout', 'stock-control' ); ?>
	</div>
    <div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
        <?php wp_easycart_admin( )->load_toggle_group( 'ec_option_send_low_stock_emails', 'wpeasycart_admin_update_low_stock_trigger_view( ); ec_admin_save_cart_settings_options', get_option( 'ec_option_send_low_stock_emails' ), __( 'Low Stock: Notify Admin', 'wp-easycart' ), __( 'This allows an admin to be automatically notified of low stock on products in the store.', 'wp-easycart' ) ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_low_stock_trigger_total', 'ec_admin_save_checkout_text_setting', get_option( 'ec_option_low_stock_trigger_total' ), __( 'Low Stock: Trigger Quantity', 'wp-easycart' ), __( 'Enter the stock amount that triggers the low stock email.', 'wp-easycart' ), '', 'ec_admin_low_stock_trigger_total_row', ( ( get_option( 'ec_option_send_low_stock_emails' ) == "1" ) ? true : false ), true ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_send_out_of_stock_emails', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_send_out_of_stock_emails' ), __( 'Out of Stock: Notify Admin', 'wp-easycart' ), __( 'This allows an admin to be automatically notified when stock runs out in the store.', 'wp-easycart' ) ); ?>
		
    </div>
</div>