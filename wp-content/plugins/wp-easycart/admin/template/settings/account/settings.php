<div class="ec_admin_list_line_item ec_admin_demo_data_line">
            
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_account_settings_loader" ); ?>
    
    <div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-admin-users"></div>
		<span><?php esc_attr_e( 'Account Options', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'accounts', 'settings' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
    	<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'accounts', 'settings');?>
	</div>
    <div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
        <?php wp_easycart_admin( )->load_toggle_group( 'ec_option_require_account_terms', 'ec_admin_save_account_options', get_option( 'ec_option_require_account_terms' ), __( 'Require Terms Agreement', 'wp-easycart' ), __( 'This adds a terms agreement checkbox to the account creation and is required for GDPR and CCPA Compliance.', 'wp-easycart' ) ); ?>
		
       	<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_require_account_address', 'ec_admin_save_account_options', get_option( 'ec_option_require_account_address' ), __( 'Require Billing for Account', 'wp-easycart' ), __( 'This option requires the user to enter a billing address on account registration.', 'wp-easycart' ) ); ?>
		
       	<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_require_email_validation', 'ec_admin_save_account_options', get_option( 'ec_option_require_email_validation' ), __( 'Email Validation', 'wp-easycart' ), __( 'This requires a user to validate their email when creating an account.', 'wp-easycart' ) ); ?>
		
       	<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_enable_recaptcha', 'wpeasycart_admin_update_account_view( ); ec_admin_save_account_options', get_option( 'ec_option_enable_recaptcha' ), __( 'Google Recaptcha V2', 'wp-easycart' ), __( 'This requires a valid Google Site Key and Secret Key, but enables a Recaptcha on all forms of the cart.', 'wp-easycart' ) ); ?>
		
       	<div class="wpeasycart-admin-more-options-bar" id="ec_option_enable_recaptcha_help"<?php echo ( !get_option( 'ec_option_enable_recaptcha' ) ) ? 'style="display:none;"' : ''; ?>>
            <div class="wp_easycart_admin_no_padding">
                <div class="dashicons dashicons-arrow-down"></div>
            
                <?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_recaptcha_site_key', 'ec_admin_save_account_text_setting', get_option( 'ec_option_recaptcha_site_key' ), __( 'Google Recaptcha: Site Key', 'wp-easycart' ), __( 'This is setup in your google recaptcha account, one key per url.', 'wp-easycart' ), '', 'ec_admin_ec_option_recaptcha_site_key_row', get_option( 'ec_option_enable_recaptcha' ), false ); ?>  
        
		        <?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_recaptcha_secret_key', 'ec_admin_save_account_text_setting', get_option( 'ec_option_recaptcha_secret_key' ), __( 'Google Recaptcha: Secret Key', 'wp-easycart' ), __( 'This is setup in your google recaptcha account, one key per url.', 'wp-easycart' ), '', 'ec_admin_ec_option_recaptcha_secret_key_row', get_option( 'ec_option_enable_recaptcha' ), false ); ?>
                
                <?php wp_easycart_admin( )->load_toggle_group( 'ec_option_enable_recaptcha_cart', 'wpeasycart_admin_update_account_view( ); ec_admin_save_account_options', get_option( 'ec_option_enable_recaptcha_cart' ), __( 'Enable in Cart', 'wp-easycart' ), __( 'This enables a recaptcha on the login and submit checkout details on the shopping cart.', 'wp-easycart' ), 'ec_admin_ec_option_recaptcha_cart_row', get_option( 'ec_option_enable_recaptcha' ) ); ?>
                
                <div style="clear:both;"></div>
            </div>
        </div> 
        
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_show_account_subscriptions_link', 'ec_admin_save_account_options', get_option( 'ec_option_show_account_subscriptions_link' ), __( 'Manage Subscriptions', 'wp-easycart' ), __( 'Adds a menu item to the account to allow a user to manage their subscriptions.', 'wp-easycart' ) ); ?>  
        
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_enable_user_notes', 'ec_admin_save_account_options', get_option( 'ec_option_enable_user_notes' ), __( 'User Notes', 'wp-easycart' ), __( 'This enables user notes on the user registration.', 'wp-easycart' ) ); ?>
        
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_show_subscriber_feature', 'ec_admin_save_account_options', get_option( 'ec_option_show_subscriber_feature' ), __( 'Subscribe to Newsletter', 'wp-easycart' ), __( 'This enables a subscribe to newsletter checkbox on the account and checkout pages.', 'wp-easycart' ) ); ?>
        
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_subscriptions_use_first_order_details', 'ec_admin_save_account_options', get_option( 'ec_subscriptions_use_first_order_details' ), __( 'Subscription Renewal: Use First Order Details', 'wp-easycart' ), __( 'This forces the billing and shipping to match the first order, rather than what is on the user account.', 'wp-easycart' ) ); ?>
		
    </div>
</div>