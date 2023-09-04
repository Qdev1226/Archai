<div class="ec_admin_list_line_item">

	<?php wp_easycart_admin()->preloader->print_preloader( "ec_admin_live_gateway_display_loader" ); ?>

	<div class="ec_admin_settings_label"><div class="dashicons-before dashicons-lock"></div><span><?php esc_attr_e( 'Live Payment', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin()->helpsystem->print_docs_url( 'settings', 'payment', 'live-gateway' ) );?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin()->helpsystem->print_vids_url('settings', 'payment', 'live-gateway');?>
	</div>

	<?php if ( get_option( 'ec_option_stripe_connect_use_sandbox' ) && get_option( 'ec_option_stripe_connect_sandbox_access_token' ) != '' ) { ?>
	<div class="ec_admin_stripe_holder">
		<h1 class="ec_admin_stripe_title"><?php esc_attr_e( 'Connected with Stripe Sandbox', 'wp-easycart' ); ?></h1>
		<h3 class="ec_admin_stripe_subtitle"><?php esc_attr_e( 'You are ready to process payments in test mode', 'wp-easycart' ); ?></h3>
		<div class="ec_admin_stripe_button_row">
			<div style="width:100%;"><img style="max-width:100%;" src="<?php echo esc_url( plugins_url( 'wp-easycart/admin/images/Stripe Logo (blue).png', EC_PLUGIN_DIRECTORY ) ); ?>" alt="Stripe" /></div>
			<?php if ( get_option( 'ec_option_stripe_connect_production_access_token' ) != '' ) { ?>
			<a href="admin.php?page=wp-easycart-settings&subpage=payment&ec_admin_form_action=stripe-connect-use-production&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-stripe' ) ); ?>"><?php esc_attr_e( 'Switch to Live Mode', 'wp-easycart' ); ?></a>
			<?php } else { ?>
			<a href="https://connect.wpeasycart.com/connect/?step=start&redirect=<?php echo urlencode( esc_url_raw( admin_url() ) . '?ec_admin_form_action=stripe_onboard&env=production&wp_easycart_nonce=' . wp_create_nonce( 'wp-easycart-stripe' ) ); ?>&env=production"><?php esc_attr_e( 'Switch to Live Mode', 'wp-easycart' ); ?></a><?php }?> | <a href="admin.php?page=wp-easycart-settings&subpage=payment&ec_admin_form_action=stripe-connect-sandbox-disconnect&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-stripe' ) ); ?>"><?php esc_attr_e( 'Change Payment Method', 'wp-easycart' ); ?></a>
		</div>
		<div class="ec_admin_stripe_legal">*<?php esc_attr_e( 'WP EasyCart charges a 2% application fee on all sales with Stripe in the free edition.', 'wp-easycart' ); ?></div>
		<div class="ec_admin_settings_notice" style="text-align:center; padding:0 10px 20px;"><strong><?php esc_attr_e( 'Webhook URL', 'wp-easycart' ); ?>:</strong> <?php echo esc_url( get_site_url() . '?wpeasycarthook=stripe-webhook' ); ?></div>
		<div class="ec_admin_settings_notice" style="text-align:center; padding:0 10px 20px;"><strong><?php esc_attr_e( 'To Do', 'wp-easycart' ); ?>:</strong> <?php esc_attr_e( 'You must add the Webhook URL to your Stripe account for best results.', 'wp-easycart' ); ?> <a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=stripe" target="_blank"><?php esc_attr_e( 'Click Here to Learn More', 'wp-easycart' ); ?></a></div>
	</div>

	<?php } else if ( !get_option( 'ec_option_stripe_connect_use_sandbox' ) && get_option( 'ec_option_stripe_connect_production_access_token' ) != '' ) { ?>
	<div class="ec_admin_stripe_holder">
		<h1 class="ec_admin_stripe_title"><?php esc_attr_e( 'Connected with Stripe', 'wp-easycart' ); ?></h1>
		<h3 class="ec_admin_stripe_subtitle"><?php esc_attr_e( 'You are ready to process payments in live mode', 'wp-easycart' ); ?></h3>
		<div class="ec_admin_stripe_button_row">
			<div style="width:100%;"><img style="max-width:100%;" src="<?php echo esc_url( plugins_url( 'wp-easycart/admin/images/Stripe Logo (blue).png', EC_PLUGIN_DIRECTORY ) ); ?>" alt="Stripe" /></div>
			<?php if ( get_option( 'ec_option_stripe_connect_sandbox_access_token' ) != '' ) { ?>
			<a href="admin.php?page=wp-easycart-settings&subpage=payment&ec_admin_form_action=stripe-connect-use-sandbox&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-stripe' ) ); ?>"><?php esc_attr_e( 'Switch to Sandbox Mode', 'wp-easycart' ); ?></a>
			<?php } else { ?>
			<a href="https://connect.wpeasycart.com/connect/?step=start&redirect=<?php echo urlencode( esc_url_raw( admin_url() ) . '?ec_admin_form_action=stripe_onboard&env=sandbox&wp_easycart_nonce=' . wp_create_nonce( 'wp-easycart-stripe' ) ); ?>&env=sandbox"><?php esc_attr_e( 'Switch to Sandbox Mode', 'wp-easycart' ); ?></a><?php }?> | <a href="admin.php?page=wp-easycart-settings&subpage=payment&ec_admin_form_action=stripe-connect-production-disconnect&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-stripe' ) ); ?>"><?php esc_attr_e( 'Change Payment Method', 'wp-easycart' ); ?></a>
		</div>
		<div class="ec_admin_stripe_legal">*<?php esc_attr_e( 'WP EasyCart charges a 2% application fee on all sales with Stripe in the free edition.', 'wp-easycart' ); ?></div>
		<div class="ec_admin_settings_notice" style="text-align:center; padding:0 10px 20px;"><strong><?php esc_attr_e( 'Webhook URL', 'wp-easycart' ); ?>:</strong> <?php echo esc_url_raw( get_site_url() . '?wpeasycarthook=stripe-webhook' ); ?></div>
	</div>

	<?php } else { ?>
	<div class="ec_admin_stripe_holder">
		<h1 class="ec_admin_stripe_title"><?php esc_attr_e( 'WP EasyCart + Stripe', 'wp-easycart' ); ?></h1>
		<h3 class="ec_admin_stripe_subtitle"><?php esc_attr_e( 'Included FREE in WP EasyCart!', 'wp-easycart' ); ?>*</h3>
		<div class="ec_admin_stripe_button_row">
			<a href="https://connect.wpeasycart.com/connect/?step=start&redirect=<?php echo urlencode( esc_url_raw( admin_url() ) . '?ec_admin_form_action=stripe_onboard&env=production&wp_easycart_nonce=' . wp_create_nonce( 'wp-easycart-stripe' ) ); ?>&env=production" target="_self">
				<img src="<?php echo esc_url( plugins_url( 'wp-easycart/admin/images/blue-on-light.png', EC_PLUGIN_DIRECTORY ) ); ?>" alt="<?php esc_attr_e( 'Connect with Stripe', 'wp-easycart' ); ?>" />
			</a>
			<br />
			<a href="https://connect.wpeasycart.com/connect/?step=start&redirect=<?php echo urlencode( esc_url_raw( admin_url() ) . '?ec_admin_form_action=stripe_onboard&env=sandbox&wp_easycart_nonce=' . wp_create_nonce( 'wp-easycart-stripe' ) ); ?>&env=sandbox"><?php esc_attr_e( 'Try Sandbox First?', 'wp-easycart' ); ?></a>
		</div>
		<div class="ec_admin_stripe_legal">*<?php esc_attr_e( 'WP EasyCart charges a 2% application fee on all sales with Stripe in the free edition.', 'wp-easycart' ); ?></div>

		<div class="ec_admin_paypal_or">-- <?php esc_attr_e( 'OR', 'wp-easycart' ); ?> --</div>
	</div>

	<?php do_action( 'wp_easycart_admin_live_gateway_post_stripe' ); ?>

	<h1 class="ec_admin_stripe_title" style="color:#333; margin-top:10px;"><?php esc_attr_e( 'Use one of our PRO gateways', 'wp-easycart' ); ?></h1>
	<h3 class="ec_admin_stripe_subtitle"><?php esc_attr_e( 'No Application Fees, Sell Like a Pro!', 'wp-easycart' ); ?></h3>

	<div class="ec_admin_live_gateway_select">
		<select id="ec_option_payment_process_method" name="ec_option_payment_process_method" onchange="toggle_live_gateways();<?php do_action( 'wp_easycart_pro_add_live_save' ); ?>" value="<?php echo esc_attr( get_option( 'ec_option_payment_process_method' ) ); ?>" style="width:250px;">
			<option value="0" <?php if ( get_option( 'ec_option_payment_process_method') == "0" ) { echo " selected"; } ?>><?php esc_attr_e( 'No Live Payment Processor', 'wp-easycart' ); ?></option>
			<?php do_action( 'wpeasycart_admin_load_live_gateway_select_options' ); ?>
		</select>
	</div>

	<?php do_action( 'wpeasycart_admin_load_live_gateway_settings' ); ?>

	<div class="ec_admin_settings_input<?php if ( get_option( 'ec_option_payment_process_method' ) != '0' && get_option( 'ec_option_payment_process_method' ) != 'custom' ) { ?> ec_admin_initial_hide<?php }?>" id="ec_admin_live_gateway_none">
		<?php /*<input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_live_gateway_selection();" value="<?php esc_attr_e( 'Save Options" />*/ ?>
	</div>
	<?php }?>

</div>