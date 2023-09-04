<div class="ec_admin_list_line_item ec_admin_demo_data_line">
	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-admin-generic"></div>
		<span><?php esc_attr_e( 'Global Email Settings', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'email-setup', 'email-settings' ) );?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
    	<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'email-setup', 'email-settings'); ?>
	</div>
	<div class="ec_admin_settings_input wp_easycart_admin_no_padding">
        <?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_wp_mail', 'wpeasycart_admin_update_email_view( ); ec_admin_save_email_option', get_option( 'ec_option_use_wp_mail' ), __( 'Use WordPress Mail', 'wp-easycart' ), __( 'This option will send EasyCart emails using WordPress\'s default email delivery system. If you experience sending email issues, we highly recommend installing an SMTP plugin for best delivery results.', 'wp-easycart' ) ); ?>
       	<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_send_signup_email', 'ec_admin_save_email_option', get_option( 'ec_option_send_signup_email' ), __( 'New Account Notifications', 'wp-easycart' ), __( 'Enabling this feature will send the admin email addresses a notificaiton when a new user creates an account on EasyCart.', 'wp-easycart' ) ); ?>
    </div>
</div>