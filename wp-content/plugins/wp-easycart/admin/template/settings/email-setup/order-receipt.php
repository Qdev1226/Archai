<div class="ec_admin_list_line_item ec_admin_demo_data_line">
            
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_order_receipt_loader" ); ?>
            
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
		}else if( isset( $_GET['subpage'] ) && $_GET['subpage'] == "email-setup" && isset( $_GET['ec_action'] ) && $_GET['ec_action'] == "send_test_email" ){
			$send_emails = new wp_easycart_admin_email_settings( );
			$result = $send_emails->ec_send_test_email( );
            if( $result )
                $isupdate = "5";
            else
                $isupdate = "6";
		}
		?>
    
    <div class="ec_admin_settings_label">
    	<div class="dashicons-before dashicons-email"></div>
        <span><?php esc_attr_e( 'Order Receipt Email Setup', 'wp-easycart' ); ?></span>
        <a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'email-setup', 'order-receipt' ) );?>" target="_blank" class="ec_help_icon_link">
            <div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
        </a>
    	<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'email-setup', 'order-receipt');?>
    </div>
	
    <div class="ec_admin_settings_input wp_easycart_admin_no_padding">
        
		<?php if( $isupdate && $isupdate == "1" ) { ?>
           <div  class="ec_save_success"><span><?php esc_attr_e( 'Email Test Sent Successfully!', 'wp-easycart' ); ?></span></div>
        <?php }else if( $isupdate && $isupdate == "2" ) { ?>
            <div  class="ec_save_failure"><span><?php esc_attr_e( 'Email Test Failed! Errors:', 'wp-easycart' ); ?> <?php echo esc_attr( $smtp_errors ); ?></span></div>
        <?php } ?>
        
        <?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_order_from_email', 'ec_admin_save_email_text_setting', get_option( 'ec_option_order_from_email' ), __( 'Order Receipt From Email Address', 'wp-easycart' ), __( 'This is the email address that your customer will see when receiving order based emails from the cart.', 'wp-easycart' ), '', 'ec_admin_ec_option_order_from_email_row', true, false ); ?>
        
        <div style="float:left; width:100%; font-size:11px; margin-top:-25px; margin-bottom:20px;">
            <?php esc_attr_e( 'Example: YOUR NAME&#60;email@email.com&#62;', 'wp-easycart' ); ?>
            <div style="float:right">
                <a href="admin.php?page=wp-easycart-settings&subpage=email-setup&ec_action=smtp-test1"><?php esc_attr_e( 'Send Test Email', 'wp-easycart' ); ?></a>
                | 
                <a href="https://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=email-setup" target="_blank"><?php esc_attr_e( 'Having Trouble Receiving Email?', 'wp-easycart' ); ?></a>
            </div>
        </div>
		
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_order_use_smtp', 'wpeasycart_admin_update_email_view( ); ec_admin_save_email_option', get_option( 'ec_option_order_use_smtp' ), __( 'Use SMTP', 'wp-easycart' ), __( 'Enabling this allows you to setup SMTP and send directly through your email server. This is an advanced feature, be sure to verify your setup is correct.', 'wp-easycart' ), 'ec_admin_ec_option_order_use_smtp_row', !get_option( 'ec_option_use_wp_mail' ) ); ?>
		
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
        
        <?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_order_from_smtp_encryption_type', 'ec_admin_save_email_text_setting', get_option( 'ec_option_order_from_smtp_encryption_type' ), __( 'Use SMTP', 'wp-easycart' ), __( 'This requires correct setup or emails will not send.', 'wp-easycart' ), $smtp_security_options, 'ec_admin_ec_option_order_from_smtp_encryption_type_row', ( !get_option( 'ec_option_use_wp_mail' ) && get_option( 'ec_option_order_use_smtp' ) ), false ); ?>
        
        <?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_order_from_smtp_host', 'ec_admin_save_email_text_setting', get_option( 'ec_option_order_from_smtp_host' ), __( 'SMTP Host', 'wp-easycart' ), __( 'This is the port for your SMTP setup.', 'wp-easycart' ), '', 'ec_admin_ec_option_order_from_smtp_host_row', ( !get_option( 'ec_option_use_wp_mail' ) && get_option( 'ec_option_order_use_smtp' ) ), false ); ?>
        
        <?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_order_from_smtp_port', 'ec_admin_save_email_text_setting', get_option( 'ec_option_order_from_smtp_port' ), __( 'SMTP Port Number', 'wp-easycart' ), __( 'This is the port for your SMTP setup.', 'wp-easycart' ), '', 'ec_admin_ec_option_order_from_smtp_port_row', ( !get_option( 'ec_option_use_wp_mail' ) && get_option( 'ec_option_order_use_smtp' ) ), false ); ?>
        
        <?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_order_from_smtp_username', 'ec_admin_save_email_text_setting', get_option( 'ec_option_order_from_smtp_username' ), __( 'SMTP User Name', 'wp-easycart' ), __( 'This is the user name for your SMTP setup.', 'wp-easycart' ), '', 'ec_admin_ec_option_order_from_smtp_username_display_row', ( !get_option( 'ec_option_use_wp_mail' ) && get_option( 'ec_option_order_use_smtp' ) ), false ); ?>
        
        <?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_order_from_smtp_password', 'ec_admin_save_email_text_setting', get_option( 'ec_option_order_from_smtp_password' ), __( 'SMTP Password', 'wp-easycart' ), __( 'This is the password for your SMTP setup.', 'wp-easycart' ), '', 'ec_admin_ec_option_order_from_smtp_password_display_row', ( !get_option( 'ec_option_use_wp_mail' ) && get_option( 'ec_option_order_use_smtp' ) ), false ); ?>
        
        <?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_bcc_email_addresses', 'ec_admin_save_email_text_setting', get_option( 'ec_option_bcc_email_addresses' ), __( 'Admin Email Address', 'wp-easycart' ), __( 'Enter email addresses that will receive copies of all EasyCart emails. Separate email addresses by a comma (,).', 'wp-easycart' ), '', 'ec_admin_ec_option_bcc_email_addresses_row', true, false ); ?>
		
       	<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_show_email_on_receipt', 'ec_admin_save_email_option', get_option( 'ec_option_show_email_on_receipt' ), __( 'Email on Receipt', 'wp-easycart' ), __( 'Enabling this feature will include the customer email address on the email receipt.', 'wp-easycart' ) ); ?>
        
        <?php wp_easycart_admin( )->load_toggle_group( 'ec_option_show_image_on_receipt', 'ec_admin_save_email_option', get_option( 'ec_option_show_image_on_receipt' ), __( 'Product Images on Receipt', 'wp-easycart' ), __( 'Enabling this feature will include the product images within the email receipt.', 'wp-easycart' ) ); ?>
		
		<?php 
       		global $wpdb;
        	$min_order_id = $wpdb->get_var( $wpdb->prepare( "SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = %s AND TABLE_NAME = 'ec_order'", $wpdb->dbname ) );
		?>
        
		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_current_order_id', 'ec_admin_save_email_text_setting', $min_order_id, __( 'Current Order ID', 'wp-easycart' ), __( 'Set the value for your next order number (you can only increase this value, lower values are not permitted).', 'wp-easycart' ), '', 'ec_admin_ec_option_current_order_id_row', true, false ); ?>
		
       	<?php if( $isupdate && $isupdate == "5" ) { ?>
        <div id='setting-error-settings_updated' class='updated settings-success'><p><strong><?php esc_attr_e( 'Order Emails have been dispatched.', 'wp-easycart' ); ?></strong></p></div>
        <?php }else if( $isupdate && $isupdate == "6" ){ ?>
        <div id='setting-error-settings_updated' class='updated settings-success'><p><strong><?php esc_attr_e( 'Order ID was not found.', 'wp-easycart' ); ?></strong></p></div> 
        <?php }?>
        
		<div>
        	<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_order_id', ' false; wp_easycart_none', '', __( 'Resend Order ID', 'wp-easycart' ), __( 'Enter an Order ID you wish to resend a confirmation emails (this is a good testing tool).', 'wp-easycart' ), '', 'ec_admin_ec_order_id_row', true, false, false ); ?>
				
			<input type="submit" value="<?php esc_attr_e( 'Resend Order Email', 'wp-easycart' ); ?>" class="ec_admin_settings_simple_button" style="float: right; margin-top:-20px; margin-bottom:20px;" onclick="ec_admin_resend_email_order( );" />
		</div>
		
		<div>
            <?php wp_easycart_admin( )->load_toggle_group_image( 'ec_option_email_logo', 'ec_admin_save_email_text_setting', get_option( 'ec_option_email_logo' ), __( 'Email Logo', 'wp-easycart' ), __( 'This is the logo shown on your EasyCart email templates..', 'wp-easycart' ), '', 'ec_admin_ec_option_email_logo_row', true, false ); ?>
        </div>
    </div>
</div>