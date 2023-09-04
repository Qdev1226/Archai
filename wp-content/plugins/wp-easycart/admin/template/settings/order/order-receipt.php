<div class="ec_admin_list_line_item ec_admin_demo_data_line">
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_checkout_email_settings_loader" ); ?>
    <div class="ec_admin_settings_label"><div class="dashicons-before dashicons-tag"></div><span><?php esc_attr_e( 'Order Receipt', 'wp-easycart' ); ?></span></div>
    <div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
        <div><input type="checkbox" name="ec_option_send_low_stock_emails" id="ec_option_send_low_stock_emails" value="1"<?php if( get_option('ec_option_send_low_stock_emails') == "1" ){ echo " checked=\"checked\""; }?> /> <?php esc_attr_e( 'Send Low Stock Admin Emails', 'wp-easycart' ); ?></div>
       	<div><input type="checkbox" name="ec_option_send_out_of_stock_emails" id="ec_option_send_out_of_stock_emails" value="1"<?php if( get_option('ec_option_send_out_of_stock_emails') == "1" ){ echo " checked=\"checked\""; }?> /> <?php esc_attr_e( 'Send Out of Stock Admin Emails', 'wp-easycart' ); ?></div>
       	<div><?php esc_attr_e( 'Low Stock Trigger', 'wp-easycart' ); ?><input name="ec_option_low_stock_trigger_total" id="ec_option_low_stock_trigger_total" type="number" step="1" min="1" value="<?php echo esc_attr( get_option( 'ec_option_low_stock_trigger_total' ) ); ?>" /></div>
        <div class="ec_admin_settings_input">
            <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_checkout_options( );" value="<?php esc_attr_e( 'Save Options', 'wp-easycart' ); ?>" />
        </div>
    </div>
</div>