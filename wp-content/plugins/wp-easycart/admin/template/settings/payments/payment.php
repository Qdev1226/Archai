<div class="ec_admin_settings_panel">

	<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_payment_settings_nonce', 'wp-easycart-settings-payment' ); ?>

	<?php do_action( 'wp_easycart_admin_payment_options_top' ); ?>

	<div class="ec_admin_important_numbered_list_full_width">

		<?php do_action( 'wpeasycart_admin_payment_settings' ); ?>

	</div>

</div>

<?php
if ( ! get_option( 'ec_option_wpeasycart_terms_accepted' ) ) {
	if ( '' != apply_filters( 'wp_easycart_admin_lock_icon', 'true' ) ) {
		echo '<div id="wpeasycart_payment_onboarding_overlay" class="ec_admin_payment_onboarding_overlay" style="position: absolute; top: 0px; right: 0px; bottom: 0px; left: 160px; background-color: rgba(38,38,38,0.9); z-index: 9980; padding: 5rem 0; display: -webkit-flex; display: flex; -webkit-align-items: center; align-items: center; -webkit-justify-content: flex-start; justify-content: flex-start; -webkit-flex-direction: column; flex-direction: column;">
		</div>
		<div id="wpeasycart_payment_onboarding" class="ec_admin_payment_onboarding" style="position: absolute; top: 0px; right: 0px; bottom: 0px; left: 160px; z-index: 9980; padding: 5rem 0; display: -webkit-flex; display: flex; -webkit-align-items: center; align-items: center; -webkit-justify-content: flex-start; justify-content: flex-start; -webkit-flex-direction: column; flex-direction: column;">
			<div style="display:block; position:relative; width:800px; max-width:80%; margin:200px auto 0;">
				<div class="wpeasycart_payment_onboarding_modal" style="background: #fff; overflow: hidden; padding: 0; margin: 20px; -webkit-border-radius: 3px 3px 2px 2px; -moz-border-radius: 3px 3px 2px 2px; border-radius: 3px 3px 2px 2px; -webkit-box-shadow: 0 2px 4px rgba(0,0,0,0.4); -moz-box-shadow: 0 2px 4px rgba(0,0,0,0.4); box-shadow: 0 2px 4px rgba(0,0,0,0.4); -webkit-background-clip: padding-box;">
					<div class="wpeasycart_payment_onboarding_modal_content" style="padding: 1rem;">
						<div class="wpeasycart_payment_onboarding_modal_content_inner" style="display: -webkit-flex; display: flex; -webkit-align-items: stretch; align-items: stretch; -webkit-justify-content: flex-start; justify-content: flex-start; -webkit-flex-direction: column; flex-direction: column;">
							<h3>' . esc_attr__( 'WP EasyCart Terms &amp; Conditions', 'wp-easycart' ) . '</h3>
							<p>' . esc_attr__( 'The WP EasyCart Free Edition is available to use with unlimited orders, unlimited products, and unlimited user accounts as well as Manual/Direct Deposit payments. Square, Stripe, and PayPal are offered as an optional payment gateway through our EasyCart Connect system, with 2% EasyCart fees, should you choose to utilize one of them. Upgrading to our Professional or Premium edition will remove all EasyCart fees and unlock all features as well as 30+ payment gateways.', 'wp-easycart' ) . '</p>
						</div>
					</div>
					<div class="wpeasycart_payment_onboarding_modal_footer" style="min-height: 44px; padding: 1rem; width: 100%; box-sizing: border-box; display: -webkit-flex; display: flex; -webkit-align-items: center; align-items: center; -webkit-justify-content: space-between; justify-content: space-between; position: relative; background-color: #f1f1f1; border-top: 1px solid #d9d9d9;">
						<ul class="wpeasycart_payment_onboarding_modal_footer_content" style="padding: 0; margin: 0; -webkit-justify-content: flex-end !important; justify-content: flex-end !important; display: -webkit-flex !important; display: flex !important; -webkit-align-items: center !important; align-items: center !important; -webkit-justify-content: flex-start !important; justify-content: flex-start !important; -webkit-flex-direction: row !important; flex-direction: row !important;width: 100%; max-width: 100%; list-style: none;">
							<li class="wpeasycart_payment_onboarding_modal_footer_content_left" style="padding:0; margin:0; -webkit-flex-grow: 0 !important; flex-grow: 0 !important;padding-right: 1rem !important;">
								<input type="checkbox" class="wpeasycart_payment_onboarding_modal_footer_checkbox" id="wpeasycart_payment_agree" />
								<label for="wpeasycart_payment_agree">' . sprintf( esc_attr__( 'By checking this box, I agree to the WP EasyCart %1$sterms and privacy policy%2$s', 'wp-easycart' ), '<a href="https://www.wpeasycart.com/terms-and-conditions/" target="_blank" rel="noopener noreferrer">', '</a>' ) . '</label>
							</li>
							<li style="padding:0; margin:0; -webkit-flex-grow: 0 !important; flex-grow: 0 !important;padding-left: 1rem !important;">
								<a href="#" class="wpeasycart_payment_onboarding_modal_footer_button" id="wpeasycart_payment_continue" style="color: #fff; background-color: #00709e; border-color: #005e85; display: inline-block; margin-bottom: 0; text-align: center; vertical-align: middle; touch-action: manipulation; cursor: pointer; background-image: none; border: 1px solid transparent; white-space: nowrap; padding: .5rem 1.25rem; font-size: .875rem; line-height: 1.3125rem; border-radius: 4px; font-weight: 400; text-transform: uppercase; -moz-user-select: -moz-none; -ms-user-select: none; -webkit-user-select: none; user-select: none; text-decoration:none;">' . esc_attr__( 'Continue', 'wp-easycart' ) . '</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<script>
			jQuery( document.getElementById( \'wpeasycart_payment_onboarding_overlay\' ) ).appendTo( \'#wpcontent\' );
			jQuery( document.getElementById( \'wpeasycart_payment_onboarding\' ) ).appendTo( \'#wpcontent\' );
			jQuery( document.getElementById( \'wpeasycart_payment_continue\' ) ).on( \'click\', function( ){
				if( !jQuery( document.getElementById( \'wpeasycart_payment_agree\' ) ).is( \':checked\' ) ){
					jQuery( \'.wpeasycart_payment_onboarding_modal_footer\' ).css( \'background-color\', \'#e4d0d0\' );
				}else{
					jQuery( \'.wpeasycart_payment_onboarding_modal_footer\' ).css( \'background-color\', \'#f1f1f1\' );
					jQuery( document.getElementById( \'wpeasycart_payment_onboarding_overlay\' ) ).remove( );
					jQuery( document.getElementById( \'wpeasycart_payment_onboarding\' ) ).remove( );
					var data = {
						action: \'ec_admin_ajax_save_terms_accepted\',
						wp_easycart_nonce: \'' . esc_attr( wp_create_nonce( 'wp-easycart-terms-accept' ) ) . '\'
					}
					jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: \'post\', data: data} );
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
		</style>
		';
	}
}
?>
