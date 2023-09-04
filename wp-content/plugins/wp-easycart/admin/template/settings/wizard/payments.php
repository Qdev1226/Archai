<form action="" method="POST" name="wpeasycart_admin_setup_wizard_form" id="wpeasycart_admin_setup_wizard_form" novalidate="novalidate">
	<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_nonce', 'wp-easycart-process-wizard-payments' ); ?>
	<input type="hidden" name="ec_admin_form_action" id="ec_admin_form_action" value="process-wizard-payments">
	<h3><?php esc_attr_e( 'Payments', 'wp-easycart' ); ?></h3>
	<p><?php echo sprintf( esc_attr__( 'WP EasyCart offers both online and offline payments. %s Additional payment methods %s can be installed later.', 'wp-easycart' ), '<a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=payment" target="_blank">', '</a>' ); ?></p>
	<div class="ec_admin_wizard_page_row" style="padding:30px 0;">
		<div class="ec_admin_wizard_page_row_title"><strong><?php esc_attr_e( 'PayPal', 'wp-easycart' ); ?></strong></div>
		<div class="ec_admin_wizard_page_row_content" style="padding-right:100px;"><?php esc_attr_e( 'Accept payments with PayPal without an SSL certificate.', 'wp-easycart' ); ?></div>
		<a target="_self" href="<?php echo esc_url_raw( wp_easycart_admin( )->available_url ); ?>/paypal-v2/production_onboard.php?redirect=<?php echo urlencode( esc_url_raw( admin_url( ) ) . '?wpeasycart_paypal_onboard=production&is_wizard=true' ); ?>" onclick="return wp_easycart_wizard_use_paypal( );">
			<span></span>
			<label class="ec_admin_wizard_input_row_toggle">
				<input type="checkbox" name="paypal_standard" id="wp_easycart_paypal_standard" onchange="wp_easycart_wizard_use_paypal( );"<?php if( get_option( 'ec_option_payment_third_party' ) == 'paypal' ){ ?> checked="checked"<?php }?> />
				<span class="ec_admin_wizard_slider round" style="top:-7px;"></span>
			</label>
		</a>
		<div style="clear:both;"></div>
	</div>
	<div class="ec_admin_wizard_page_row" style="padding:30px 0;">
		<div class="ec_admin_wizard_page_row_title"><strong><?php esc_attr_e( 'Stripe', 'wp-easycart' ); ?></strong></div>
		<div class="ec_admin_wizard_page_row_content" style="padding-right:100px;"><?php esc_attr_e( 'Accept payments with Stripe (SSL certificate required).', 'wp-easycart' ); ?></div>
		<a target="_self" href="<?php echo esc_url_raw( wp_easycart_admin( )->available_url ); ?>/connect/?step=start&redirect=<?php echo urlencode( esc_url_raw( admin_url( ) ) . '?ec_admin_form_action=stripe_onboard&env=production&goto=wizard' ); ?>&env=production" onclick="return wp_easycart_wizard_use_stripe( );">
			<span></span>
			<label class="ec_admin_wizard_input_row_toggle">
				<input type="checkbox" name="use_stripe" id="wp_easycart_use_stripe" onchange="wp_easycart_wizard_use_stripe( );"<?php if( get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ){ ?> checked="checked"<?php }?> />
				<span class="ec_admin_wizard_slider round" style="top:-7px;"></span>
			</label>
		</a>
		<div style="clear:both;"></div>
	</div>
	<div id="use_stripe_content" style="display:none;">
		<div class="ec_admin_wizard_input_row" style="text-align:center; padding:0 0 15px; margin-top:-15px;">   
			<span id="stripe_connected" style="font-weight:bold;"><br /><?php esc_attr_e( 'You should now be connected. Visit your Settings -> Payment page for more information.', 'wp-easycart' ); ?></span>
		</div>
		<div style="clear:both;"></div>
	</div>
	<?php 
		$app_redirect_state = rand( 1000000, 9999999 );
	?>
	<div class="ec_admin_wizard_page_row" style="padding:30px 0;">
		<div class="ec_admin_wizard_page_row_title"><strong><?php esc_attr_e( 'Square', 'wp-easycart' ); ?></strong></div>
		<div class="ec_admin_wizard_page_row_content" style="padding-right:100px;"><?php esc_attr_e( 'Accept payments with Square (SSL certificate required).', 'wp-easycart' ); ?></div>
		<a target="_self" href="https://connect.wpeasycart.com/square-v2/?url=<?php echo urlencode( esc_url_raw( admin_url( ) ) . '?ec_admin_form_action=handle-square&goto=wizard' ); ?>&state=<?php echo esc_attr( $app_redirect_state ); ?>" onclick="return wp_easycart_wizard_use_square( );">
			<span></span>
			<label class="ec_admin_wizard_input_row_toggle">
				<input type="checkbox" name="use_square" id="wp_easycart_use_square" onchange="wp_easycart_wizard_use_square( );"<?php if( get_option( 'ec_option_payment_process_method' ) == 'square' ){ ?> checked="checked"<?php }?> />
				<span class="ec_admin_wizard_slider round" style="top:-7px;"></span>
			</label>
		</a>
		<div style="clear:both;"></div>
	</div>
	<div id="use_square_content" style="display:none;">
		<div class="ec_admin_wizard_input_row" style="text-align:center; padding:0 0 15px; margin-top:-15px;">   
			<span id="square_connected" style="font-weight:bold;"><br /><?php esc_attr_e( 'You should now be connected. Visit your Settings -> Payment page for more information.', 'wp-easycart' ); ?></span>
		</div>
		<div style="clear:both;"></div>
	</div>
	<div class="ec_admin_wizard_page_row" style="padding:30px 0;">
		<div class="ec_admin_wizard_page_row_title"><strong><?php esc_attr_e( 'Manual Payments', 'wp-easycart' ); ?></strong></div>
		<div class="ec_admin_wizard_page_row_content" style="padding-right:100px;"><?php esc_attr_e( 'Allow users to complete an order without paying immediately. You can provide instructions on checkout for direct deposit or payment by check.', 'wp-easycart' ); ?></div>
		<label class="ec_admin_wizard_input_row_toggle">
			<input type="checkbox" name="manual_billing" id="wp_easycart_manual_billing"<?php if( get_option( 'ec_option_use_direct_deposit' ) ){ ?> checked="checked"<?php }?> />
			<span class="ec_admin_wizard_slider round"></span>
		</label>
		<div style="clear:both;"></div>
	</div>
	<?php if( '' != apply_filters( 'wp_easycart_trial_start_content', 'true' ) ) { ?>
	<div class="ec_admin_wizard_page_row" style="padding:30px 0;">
		<div class="ec_admin_wizard_page_row_title"><strong><?php esc_attr_e( 'Other Payments', 'wp-easycart' ); ?></strong></div>
		<div class="ec_admin_wizard_page_row_content" style="padding-right:100px;"><?php esc_attr_e( 'Did you know we offer 30+ other payment methods in Professional or Premium?', 'wp-easycart' ); ?><br /><a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=payment" target="_blank"><?php esc_attr_e( 'View Payment Gateway List', 'wp-easycart' ); ?></a> | <a href="admin.php?page=wp-easycart-registration&ec_trial=start" target="_blank"><?php esc_attr_e( 'TRY WITH 14 DAY FREE TRIAL', 'wp-easycart' ); ?></a></div>
		<div style="clear:both;"></div>
	</div>
	<?php }?>
	<div class="ec_admin_wizard_page_row" style="padding:30px 0;">
		<div class="ec_admin_wizard_page_row_title"><strong><?php esc_attr_e( 'Your Email Address', 'wp-easycart' ); ?></strong></div>
		<div class="ec_admin_wizard_page_row_content" style="padding-right:100px;"><?php esc_attr_e( 'Please enter your admin email address here. This allows you to recieve all notifications automatically from your store.', 'wp-easycart' ); ?></div>
		<div style="clear:both;"></div>
	</div>
	<div class="ec_admin_wizard_input_row" style="padding-top:0px; margin-top:-20px;">
		<div class="ec_admin_wizard_input_row_title">&nbsp;&nbsp;&nbsp;</div>
		<div class="ec_admin_wizard_input_row_input" style="padding-left:0 !important;">
			<input type="text" name="bcc_email" id="wp_easycart_bcc_email" value="" placeholder="youremail@email.com" style="margin-bottom:10px;" />
			<label style="font-size:11px;"><input type="checkbox" checked="checked" name="subscribe_me" id="wp_easycart_subscribe_me" /> <?php esc_attr_e( 'Send me security updates and news from WP EasyCart.', 'wp-easycart' ); ?></label>
		</div>
	</div>
	<div class="ec_admin_wizard_button_bar">
		<a href="admin.php?page=wp-easycart-settings&ec_admin_form_action=skip-wizard&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-skip-wizard' ) ); ?>" class="ec_admin_wizard_quit_button"><?php esc_attr_e( 'Skip Setup Wizard', 'wp-easycart' ); ?></a>
		<a href="admin.php?page=wp-easycart-products&subpage=products"><?php esc_attr_e( 'Setup Later', 'wp-easycart' ); ?></a>
		<input type="submit" class="ec_admin_wizard_next_button" value="<?php esc_attr_e( 'Save &amp; Continue', 'wp-easycart' ); ?>" />
	</div>
</form>
<?php
if ( ! get_option( 'ec_option_wpeasycart_terms_accepted' ) ) {
	if ( '' != apply_filters( 'wp_easycart_admin_lock_icon', 'true' ) ) { ?>
	<div id="wpeasycart_payment_onboarding_overlay" class="ec_admin_payment_onboarding_overlay" style="position: absolute; top: 0px; right: 0px; bottom: 0px; left: 160px; background-color: rgba(38,38,38,0.9); z-index: 9980; padding: 5rem 0; display: -webkit-flex; display: flex; -webkit-align-items: center; align-items: center; -webkit-justify-content: flex-start; justify-content: flex-start; -webkit-flex-direction: column; flex-direction: column;">
	</div>
	<div id="wpeasycart_payment_onboarding" class="ec_admin_payment_onboarding" style="position: absolute; top: 0px; right: 0px; bottom: 0px; left: 160px; z-index: 9980; padding: 5rem 0; display: -webkit-flex; display: flex; -webkit-align-items: center; align-items: center; -webkit-justify-content: flex-start; justify-content: flex-start; -webkit-flex-direction: column; flex-direction: column;">
		<div style="display:block; position:relative; width:800px; max-width:80%; margin:200px auto 0;">
			<div class="wpeasycart_payment_onboarding_modal" style="background: #fff; overflow: hidden; padding: 0; margin: 20px; -webkit-border-radius: 3px 3px 2px 2px; -moz-border-radius: 3px 3px 2px 2px; border-radius: 3px 3px 2px 2px; -webkit-box-shadow: 0 2px 4px rgba(0,0,0,0.4); -moz-box-shadow: 0 2px 4px rgba(0,0,0,0.4); box-shadow: 0 2px 4px rgba(0,0,0,0.4); -webkit-background-clip: padding-box;">
				<div class="wpeasycart_payment_onboarding_modal_content" style="padding: 1rem;">
					<div class="wpeasycart_payment_onboarding_modal_content_inner" style="display: -webkit-flex; display: flex; -webkit-align-items: stretch; align-items: stretch; -webkit-justify-content: flex-start; justify-content: flex-start; -webkit-flex-direction: column; flex-direction: column;">
						<h3><?php esc_attr_e( 'WP EasyCart Terms &amp; Conditions', 'wp-easycart' ); ?></h3>
						<p><?php esc_attr_e( 'The WP EasyCart Free Edition is available to use with unlimited orders, unlimited products, and unlimited user accounts as well as Manual/Direct Deposit payments. Square, Stripe, and PayPal are offered as an optional payment gateway through our EasyCart Connect system, with 2% EasyCart fees, should you choose to utilize one of them. Upgrading to our Professional or Premium edition will remove all EasyCart fees and unlock all features as well as 30+ payment gateways.', 'wp-easycart' ); ?></p>
					</div>
				</div>
				<div class="wpeasycart_payment_onboarding_modal_footer" style="min-height: 44px; padding: 1rem; width: 100%; box-sizing: border-box; display: -webkit-flex; display: flex; -webkit-align-items: center; align-items: center; -webkit-justify-content: space-between; justify-content: space-between; position: relative; background-color: #f1f1f1; border-top: 1px solid #d9d9d9;">
					<ul class="wpeasycart_payment_onboarding_modal_footer_content" style="padding: 0; margin: 0; -webkit-justify-content: flex-end !important; justify-content: flex-end !important; display: -webkit-flex !important; display: flex !important; -webkit-align-items: center !important; align-items: center !important; -webkit-justify-content: flex-start !important; justify-content: flex-start !important; -webkit-flex-direction: row !important; flex-direction: row !important;width: 100%; max-width: 100%; list-style: none;">
						<li class="wpeasycart_payment_onboarding_modal_footer_content_left" style="padding:0; margin:0; -webkit-flex-grow: 0 !important; flex-grow: 0 !important;padding-right: 1rem !important;">
							<input type="checkbox" class="wpeasycart_payment_onboarding_modal_footer_checkbox" id="wpeasycart_payment_agree" />
							<label for="wpeasycart_payment_agree"><?php echo sprintf( esc_attr__( 'By checking this box, I agree to the WP EasyCart %sterms and privacy policy%s', 'wp-easycart' ), '<a href="https://www.wpeasycart.com/terms-and-conditions/" target="_blank" rel="noopener noreferrer">', '</a>' ); ?></label>
						</li>
						<li style="padding:0; margin:0; -webkit-flex-grow: 0 !important; flex-grow: 0 !important;padding-left: 1rem !important;">
							<a href="#" class="wpeasycart_payment_onboarding_modal_footer_button" id="wpeasycart_payment_continue" style="color: #fff; background-color: #00709e; border-color: #005e85; display: inline-block; margin-bottom: 0; text-align: center; vertical-align: middle; touch-action: manipulation; cursor: pointer; background-image: none; border: 1px solid transparent; white-space: nowrap; padding: .5rem 1.25rem; font-size: .875rem; line-height: 1.3125rem; border-radius: 4px; font-weight: 400; text-transform: uppercase; -moz-user-select: -moz-none; -ms-user-select: none; -webkit-user-select: none; user-select: none; text-decoration:none;"><?php esc_attr_e( 'Continue', 'wp-easycart' ); ?></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<script>
		jQuery( document.getElementById( 'wpeasycart_payment_onboarding_overlay' ) ).appendTo( '#wpcontent' );
		jQuery( document.getElementById( 'wpeasycart_payment_onboarding' ) ).appendTo( '#wpcontent' );
		jQuery( document.getElementById( 'wpeasycart_payment_continue' ) ).on( 'click', function( ){
			if( !jQuery( document.getElementById( 'wpeasycart_payment_agree' ) ).is( ':checked' ) ){
				jQuery( '.wpeasycart_payment_onboarding_modal_footer' ).css( 'background-color', '#e4d0d0' );
			}else{
				jQuery( '.wpeasycart_payment_onboarding_modal_footer' ).css( 'background-color', '#f1f1f1' );
				jQuery( document.getElementById( 'wpeasycart_payment_onboarding_overlay' ) ).remove( );
				jQuery( document.getElementById( 'wpeasycart_payment_onboarding' ) ).remove( );
				var data = {
					action: 'ec_admin_ajax_save_terms_accepted',
					wp_easycart_nonce: '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-terms-accept' ) ); ?>'
				}
				jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data} );
			}
		} );
	</script>
	<style>
	@media screen and (max-width: 960px) {
		#wpeasycart_payment_onboarding_overlay, #wpeasycart_payment_onboarding{ left:36px !important; }
	}
	@media screen and (max-width: 782px) {
		#wpeasycart_payment_onboarding_overlay, #wpeasycart_payment_onboarding{ left:0px !important; }
	}
	</style><?php
	}
}
?>