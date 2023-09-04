<div class="ec_admin_list_line_item ec_admin_demo_data_line">
            
	<?php
		$isupdate = false;
		if( isset( $_GET['subpage'] ) && $_GET['subpage'] == "email-setup" && isset( $_GET['ec_action'] ) && $_GET['ec_action'] == "smtp-test1" ){
			$send_emails = new wp_easycart_admin_email_settings( );
			$smtp_errors = $send_emails->wpeasycart_smtp_test1( );
			if( !$smtp_errors )
				$isupdate = "1";
			else
				$isupdate = "2";
		}else if( isset( $_GET['subpage'] ) && $_GET['subpage'] == "email-setup" && isset( $_GET['ec_action'] ) && $_GET['ec_action'] == "smtp-test2" ){
			$send_emails = new wp_easycart_admin_email_settings( );
			$smtp_errors = $send_emails->wpeasycart_smtp_test2( );
			if( !$smtp_errors )
				$isupdate = "3";
			else
				$isupdate = "4";
		}
	?>
    
    <div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-email"></div>
		<span><?php esc_attr_e( 'Customer Account Email Setup', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'email-setup', 'customer-email' ) );?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
    	<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'email-setup', 'customer-email');?>
	</div>
    
	<div class="ec_admin_settings_input wp_easycart_admin_no_padding">
        
        <?php if( $isupdate && $isupdate == "3" ) { ?>
           <div  class="ec_save_success"> <span><?php esc_attr_e( 'Email Test Sent Successfully!', 'wp-easycart' ); ?></span></div>
        <?php }else if( $isupdate && $isupdate == "4" ) { ?>
            <div  class="ec_save_failure"><span><?php esc_attr_e( 'Email Test Failed! Errors:', 'wp-easycart' ); ?> <?php echo esc_attr( $smtp_errors ); ?></span></div>
        <?php } ?> 
        
        <div>
			<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_password_from_email', 'ec_admin_save_email_text_setting', get_option( 'ec_option_password_from_email' ), __( 'Customer Account From Email Address', 'wp-easycart' ), __( 'This is the email address that customers will receive emails from their account based activities such as \'Forgot my Password\' tasks.', 'wp-easycart' ), '', 'ec_admin_ec_option_password_from_email_row', true, false ); ?>
			
            <div style="float:left; width:100%; font-size:11px; margin-top:-25px; margin-bottom:20px;">
				<?php esc_attr_e( 'Example: YOUR NAME&#60;email@email.com&#62;', 'wp-easycart' ); ?>
				<a href="admin.php?page=wp-easycart-settings&subpage=email-setup&ec_action=smtp-test2" style="float:right">
					(<?php esc_attr_e( 'Send Test Email', 'wp-easycart' ); ?>)
				</a>
			</div>
        </div>
        
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_password_use_smtp', 'wpeasycart_admin_update_email_view( ); ec_admin_save_email_option', get_option( 'ec_option_password_use_smtp' ), __( 'Use SMTP', 'wp-easycart' ), __( 'Enabling this allows you to setup SMTP and send directly through your email server. This is an advanced feature, be sure to verify your setup is correct.', 'wp-easycart' ), 'ec_admin_ec_option_password_use_smtp_row', !get_option( 'ec_option_use_wp_mail' ) ); ?>
		
		<?php
        $smtp_security_options = array(
            (object) array(
                'value'	=> 'none',
                'label'	=> __( 'None', 'wp-easycart' )
            ),
            (object) array(
                'value'	=> 'ssl',
                'label'	=> 'SSL'
            ),
            (object) array(
                'value'	=> 'tls',
                'label'	=> 'TLS'
            )
        );
        ?>
        
        <?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_password_from_smtp_encryption_type', 'ec_admin_save_email_text_setting', get_option( 'ec_option_password_from_smtp_encryption_type' ), __( 'Use SMTP', 'wp-easycart' ), __( 'This requires correct setup or emails will not send.', 'wp-easycart' ), $smtp_security_options, 'ec_admin_ec_option_password_from_smtp_encryption_type_row', ( !get_option( 'ec_option_use_wp_mail' ) && get_option( 'ec_option_password_use_smtp' ) ), false ); ?>
        
        <?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_password_from_smtp_host', 'ec_admin_save_email_text_setting', get_option( 'ec_option_password_from_smtp_host' ), __( 'SMTP Host', 'wp-easycart' ), __( 'This is the port for your SMTP setup.', 'wp-easycart' ), '', 'ec_admin_ec_option_password_from_smtp_host_row', ( !get_option( 'ec_option_use_wp_mail' ) && get_option( 'ec_option_password_use_smtp' ) ), false ); ?>
        
        <?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_password_from_smtp_port', 'ec_admin_save_email_text_setting', get_option( 'ec_option_password_from_smtp_port' ), __( 'SMTP Port Number', 'wp-easycart' ), __( 'This is the port for your SMTP setup.', 'wp-easycart' ), '', 'ec_admin_ec_option_password_from_smtp_port_row', ( !get_option( 'ec_option_use_wp_mail' ) && get_option( 'ec_option_password_use_smtp' ) ), false ); ?>
        
        <?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_password_from_smtp_username', 'ec_admin_save_email_text_setting', get_option( 'ec_option_password_from_smtp_username' ), __( 'SMTP User Name', 'wp-easycart' ), __( 'This is the user name for your SMTP setup.', 'wp-easycart' ), '', 'ec_admin_ec_option_password_from_smtp_username_row', ( !get_option( 'ec_option_use_wp_mail' ) && get_option( 'ec_option_password_use_smtp' ) ), false ); ?>
        
        <?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_password_from_smtp_password', 'ec_admin_save_email_text_setting', get_option( 'ec_option_password_from_smtp_password' ), __( 'SMTP Password', 'wp-easycart' ), __( 'This is the password for your SMTP setup.', 'wp-easycart' ), '', 'ec_admin_ec_option_password_from_smtp_password_row', ( !get_option( 'ec_option_use_wp_mail' ) && get_option( 'ec_option_password_use_smtp' ) ), false ); ?>
        
    </div>
</div>