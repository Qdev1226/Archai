<div class="ec_admin_list_line_item ec_admin_demo_data_line">
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_checkout_email_settings_loader" ); ?>
    <div class="ec_admin_settings_label"><div class="dashicons-before dashicons-undo"></div><span><?php esc_attr_e( 'Abandoned Cart Settings', 'wp-easycart' ); ?></span></div>
    <div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
        <div class="ec_admin_settings_input">
            <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_abandoned_cart_options( );" value="<?php esc_attr_e( 'Save Options', 'wp-easycart' ); ?>" />
        </div>
    </div>
</div>
