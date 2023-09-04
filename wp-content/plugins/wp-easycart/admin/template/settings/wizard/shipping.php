<form action="" method="POST" name="wpeasycart_admin_setup_wizard_form" id="wpeasycart_admin_setup_wizard_form" novalidate="novalidate">
	<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_nonce', 'wp-easycart-process-wizard-shipping' ); ?>
	<input type="hidden" name="ec_admin_form_action" id="ec_admin_form_action" value="process-wizard-shipping">
	<h3><?php esc_attr_e( 'Shipping', 'wp-easycart' ); ?></h3>
	<p><?php esc_attr_e( 'WP EasyCart offers static shipping rates, weight based rates, cart total based rates, and a few more by default. You can upgrade to PRO and activate live shipping rates with UPS, USPS, FedEx, DHL, CanadaPost, or Australia Post later. For now, please choose a preferred method below and let EasyCart install some common shipping rates for you and your store\'s location.', 'wp-easycart' ); ?></p>
	<div class="ec_admin_wizard_input_row">
		<div class="ec_admin_wizard_input_row_title"><?php esc_attr_e( 'Shipping Method', 'wp-easycart' ); ?></div>
		<div class="ec_admin_wizard_input_row_input"><select name="shipping_method" id="wp_easycart_shipping_method" class="select2-basic">
			<option value="static"><?php esc_attr_e( 'Static Rates', 'wp-easycart' ); ?></option>
			<option value="price"><?php esc_attr_e( 'Cart Total Based Rates', 'wp-easycart' ); ?></option>
			<option value="weight"><?php esc_attr_e( 'Weight Based Rates', 'wp-easycart' ); ?></option>
		</select></div>
	</div>
	<?php if ( '' != apply_filters( 'wp_easycart_trial_start_content', 'true' ) ) { ?>
	<div class="ec_admin_wizard_input_row">
		<div class="ec_admin_wizard_input_row_title"><?php esc_attr_e( 'Live Shipping Rates', 'wp-easycart' ); ?></div>
		<div class="ec_admin_wizard_input_row_input" style="padding-right:100px;"><?php esc_attr_e( 'UPS, FedEx, USPS, DHL, CanadaPost, and Australia Post are all available in Professional or Premium', 'wp-easycart' ); ?><br /><a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=shipping-rates" target="_blank"><?php esc_attr_e( 'VIEW DETAILS', 'wp-easycart' ); ?></a> | <a href="admin.php?page=wp-easycart-registration&ec_trial=start" target="_blank"><?php esc_attr_e( 'TRY WITH 14 DAY FREE TRIAL', 'wp-easycart' ); ?></a></div>
		<div style="clear:both;"></div>
	</div>
	<?php } ?>
	<div class="ec_admin_wizard_button_bar">
		<a href="admin.php?page=wp-easycart-settings&ec_admin_form_action=skip-wizard&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-skip-wizard' ) ); ?>" class="ec_admin_wizard_quit_button"><?php esc_attr_e( 'Skip Setup Wizard', 'wp-easycart' ); ?></a>
		<a href="admin.php?page=wp-easycart-products&subpage=products"><?php esc_attr_e( 'Setup Later', 'wp-easycart' ); ?></a>
		<input type="submit" class="ec_admin_wizard_next_button" value="<?php esc_attr_e( 'Save &amp; Continue', 'wp-easycart' ); ?>" />
	</div>
</form>
