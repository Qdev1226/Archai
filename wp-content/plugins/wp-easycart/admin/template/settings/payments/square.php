<div class="ec_admin_square_row">
	<div class="ec_admin_slider_row">
		<?php wp_easycart_admin()->preloader->print_preloader( 'ec_admin_square_display_loader' ); ?>
		<h3>
			<?php esc_attr_e( 'Square', 'wp-easycart' ); ?>
			<a href="<?php echo esc_url_raw( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'payment', 'square' ) ); ?>" target="_blank" class="ec_help_icon_link" title="<?php esc_attr_e( 'View Help?', 'wp-easycart' ); ?>" style="float:left; margin-left:0px;">
				<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
			</a>
		</h3>
		<div class="ec_admin_slider_row_description">
			<div><?php esc_attr_e( 'Square offers the ability to pay with a credit card directly on your website. Adding Square gives your shopping cart a more professional look and increases conversions.', 'wp-easycart' ); ?></div>
			<?php if ( 'square' == get_option( 'ec_option_payment_process_method' ) ) { ?>
				<a href="admin.php?page=wp-easycart-settings&subpage=cart-importer" target="_blank"><?php esc_attr_e( 'Import Products From SquareUp', 'wp-easycart' ); ?></a>
				<a href="admin.php?page=wp-easycart-settings&subpage=payment&ec_admin_form_action=square-disconnect"><?php esc_attr_e( 'Disconnect', 'wp-easycart' ); ?></a>
				<a href="admin.php?page=wp-easycart-settings&subpage=payment&ec_admin_form_action=square-renew"><?php esc_attr_e( 'Renew Access', 'wp-easycart' ); ?></a>
				<a href="#" onclick="return square_show_advanced( );" id="square_advanced_link"><?php esc_attr_e( 'Advanced Options', 'wp-easycart' ); ?> &#9660;</a>
			<?php }?>
			<input type="hidden" name="use_square" id="use_square" value="<?php echo ( get_option( 'ec_option_payment_process_method' ) == 'square' ) ? 1 : 0; ?>" />
		</div>
		<div class="ec_admin_toggles_wrap">
			<div class="ec_admin_toggle">
				<span><?php esc_attr_e( 'Enable Live', 'wp-easycart' ); ?>:</span>
				<?php if ( 'square' != get_option( 'ec_option_payment_process_method' ) || get_option( 'ec_option_square_is_sandbox' ) ) { ?>
				<?php $app_redirect_state = wp_create_nonce( 'wp-easycart-square' ); ?>
				<a href="https://connect.wpeasycart.com/square-v2/?url=<?php echo urlencode( esc_url_raw( admin_url( ) ) . '?ec_admin_form_action=handle-square' ); ?>&state=<?php echo esc_attr( $app_redirect_state ); ?>">
				<span></span>
				<?php }?>
				<label class="ec_admin_switch">
					<input type="checkbox" onclick="return square_on_off( );" class="ec_admin_slider_checkbox" value="1" id="ec_option_square_enable"<?php if( 'square' == get_option( 'ec_option_payment_process_method' ) && ! get_option( 'ec_option_square_is_sandbox' ) ) { ?> checked="checked"<?php } ?>>
					<span class="ec_admin_slider round"></span>
				</label>
				<?php if ( 'square' != get_option( 'ec_option_payment_process_method' ) || get_option( 'ec_option_square_is_sandbox' ) ) { ?>
				</a> 
				<?php } ?>
			</div>
			<div class="ec_admin_toggle" style="display:none">
				<span><?php esc_attr_e( 'Enable Sandbox', 'wp-easycart' ); ?>:</span>
				<?php if ( 'square' != get_option( 'ec_option_payment_process_method' ) || get_option( 'ec_option_square_is_sandbox' ) ) { ?>
				<?php 
					$app_redirect_state = wp_create_nonce( 'wp-easycart-square' );
				?>
				<a href="https://connect.wpeasycart.com/square-sandbox/?url=<?php echo urlencode( esc_url_raw( admin_url( ) ) . '?ec_admin_form_action=handle-square' ); ?>&state=<?php echo esc_attr( $app_redirect_state ); ?>">
				<span></span>
				<?php } ?>
				<label class="ec_admin_switch">
					<input type="checkbox" onclick="return square_on_off( );" class="ec_admin_slider_checkbox" value="1" id="ec_option_square_enable_sandbox"<?php if ( 'square' == get_option( 'ec_option_payment_process_method' ) && get_option( 'ec_option_square_is_sandbox' ) ) { ?> checked="checked"<?php } ?>>
					<span class="ec_admin_slider round"></span>
				</label>
				<?php if ( 'square' != get_option( 'ec_option_payment_process_method' ) || get_option( 'ec_option_square_is_sandbox' ) ) { ?>
				</a> 
				<?php } ?>
			</div>
		</div>
		<div id="ec_square_options" class="ec_admin_initial_hide">
			<?php if ( '' != get_option( 'ec_option_square_access_token' ) || '' != get_option( 'ec_option_square_sandbox_access_token' ) ) { ?>
			<?php if ( class_exists( 'ec_square' ) ) {
				$square = new ec_square();
				$square_locations = $square->get_locations(); ?>
			<div class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show"><?php esc_attr_e( 'Store Location', 'wp-easycart' ); ?>
				<select name="ec_option_square_location_id" id="ec_option_square_location_id" onchange="ec_admin_save_square_options( );">
					<option value="0"><?php esc_attr_e( 'Use Default Location (or select one here)', 'wp-easycart' ); ?></option>
					<?php if ( is_array( $square_locations ) && isset( $square_locations[0] ) && isset( $square_locations[0]->id ) ) {
						foreach( $square_locations as $location ){ ?>
					<option value="<?php echo esc_attr( $location->id ); ?>"<?php if ( ( get_option( 'ec_option_square_is_sandbox' ) && $location->id == get_option( 'ec_option_square_sandbox_location_id' ) ) || ( ! get_option( 'ec_option_square_is_sandbox' ) && $location->id == get_option( 'ec_option_square_location_id' ) ) ) { ?> selected="selected"<?php } ?> data-country="<?php echo esc_attr( $location->country ); ?>"><?php echo esc_attr( $location->name ); ?></option>
					<?php }
					}else{ ?>
					<option value="0"><?php esc_attr_e( 'Could not get your Square Locations', 'wp-easycart' ); ?></option>
					<?php }?>
				</select>
			</div>
			<div class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:5px;"></span>' ) ); ?><?php esc_attr_e( 'Digital Wallets', 'wp-easycart' ); ?>
				<select name="ec_option_square_digital_wallet" id="ec_option_square_digital_wallet" onchange="ec_admin_save_square_options( );">
					<option value="0" <?php if( ! get_option('ec_option_stripe_enable_apple_pay' ) ) { echo ' selected'; } ?>><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
				</select>
			</div>
			<div class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show"><?php esc_attr_e( 'Merchant Name (Shown in Digital Wallet Payments)', 'wp-easycart' ); ?>
				<input type="text" name="ec_option_square_merchant_name" id="ec_option_square_merchant_name" value="<?php echo esc_attr( get_option( 'ec_option_square_merchant_name' ) ); ?>" placeholder="<?php esc_attr_e( 'Your Company Name', 'wp-easycart' ); ?>" /> 
				<input type="button" onclick="ec_admin_save_square_options( );" class="ec_admin_settings_simple_button" value="<?php esc_attr_e( 'Save Name', 'wp-easycart' ); ?>" style="float:left;" />
			</div>
			<?php } } ?>
		</div>
	</div>
</div>