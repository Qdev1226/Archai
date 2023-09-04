<div class="ec_admin_list_line_item ec_admin_demo_data_line">
            
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_google_analytics_loader" ); ?>
    
    <div class="ec_admin_settings_label">
        <div class="dashicons-before dashicons-admin-generic"></div>
        <span><?php esc_attr_e( 'Google Analytics Setup', 'wp-easycart' ); ?></span>
        <a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'third-party', 'google-analytics' ) );?>" target="_blank" class="ec_help_icon_link">
            <div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
        </a>
        <?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'third-party', 'google-analytics');?>
    </div>
    
    <div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
        <div><?php esc_attr_e( 'Google Analytics ID', 'wp-easycart' ); ?><input type="text" name="ec_option_googleanalyticsid" id="ec_option_googleanalyticsid" value="<?php echo esc_attr( get_option( 'ec_option_googleanalyticsid' ) ); ?>" /></div><br />
       	
        <div class="ec_admin_settings_input">
            <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_google_analytics( );" value="<?php esc_attr_e( 'Save Setup', 'wp-easycart' ); ?>" />
        </div>
    </div>
</div>

