<?php
if( trim( get_option( 'ec_option_fb_pixel' ) ) != '' ){
	echo "<script>
		fbq('track', 'AddPaymentInfo', {value: " . esc_js( number_format( $this->order_totals->grand_total, 2, '.', '' ) ) . ", currency: '" . esc_js( $GLOBALS['currency']->get_currency_code( ) ) . "', contents: [";
		for( $i=0; $i<count( $this->cart->cart ); $i++ ){
			if( $i > 0 )
				echo ", ";
			echo "{ id: '" . esc_js( $this->cart->cart[$i]->product_id ) . "', quantity: " . esc_js( $this->cart->cart[$i]->quantity ) . ", price: " . esc_js( $this->cart->cart[$i]->unit_price ) . " }";
		}		
		echo "]});
	</script>";
}
?>

<?php if ( 'square' == get_option( 'ec_option_payment_process_method' ) ) {
	$this->print_square_payment_script( true );
} ?>

<?php if( ( isset( $_GET['ideal'] ) && isset( $_GET['client_secret'] ) && isset( $_GET['source'] ) && $_GET['ideal'] == 'returning' ) || ( isset( $ideal_source ) && isset( $ideal_client_secret ) && $ideal_source != '' && $ideal_client_secret != '' ) ){ ?>
<style>
	.ec_third_party_loader{ display:block; position:absolute !important; top:50% !important; left:50% !important; z-index:999999; }
	.ec_third_party_loader_bg{ display:block; position:absolute !important; top:0 !important; left:0 !important; width:100%; height:100%; background:rgba(255, 255, 255, 0.85); z-index:999998; }
	@-webkit-keyframes ec_third_party_loader {
		0% {
			-webkit-transform: rotate(0deg);
			-moz-transform: rotate(0deg);

			-ms-transform: rotate(0deg);
			-o-transform: rotate(0deg);
			transform: rotate(0deg);
		}
		100% {
			-webkit-transform: rotate(360deg);
			-moz-transform: rotate(360deg);
			-ms-transform: rotate(360deg);
			-o-transform: rotate(360deg);
			transform: rotate(360deg);
		}
	}

	@-moz-keyframes ec_third_party_loader {
		0% {
			-webkit-transform: rotate(0deg);
			-moz-transform: rotate(0deg);
			-ms-transform: rotate(0deg);
			-o-transform: rotate(0deg);
			transform: rotate(0deg);
		  }
		100% {
			-webkit-transform: rotate(360deg);
			-moz-transform: rotate(360deg);
			-ms-transform: rotate(360deg);
			-o-transform: rotate(360deg);
			transform: rotate(360deg);
		}
	}

	@-o-keyframes ec_third_party_loader {
		0% {
			-webkit-transform: rotate(0deg);
			-moz-transform: rotate(0deg);
			-ms-transform: rotate(0deg);
			-o-transform: rotate(0deg);
			transform: rotate(0deg);
		}
		100% {
			-webkit-transform: rotate(360deg);
			-moz-transform: rotate(360deg);
			-ms-transform: rotate(360deg);
			-o-transform: rotate(360deg);
			transform: rotate(360deg);
		}
	}

	@keyframes ec_third_party_loader {
		0% {
			-webkit-transform: rotate(0deg);
			-moz-transform: rotate(0deg);
			-ms-transform: rotate(0deg);
			-o-transform: rotate(0deg);
			transform: rotate(0deg);
		}
		100% {
			-webkit-transform: rotate(360deg);
			-moz-transform: rotate(360deg);
			-ms-transform: rotate(360deg);
			-o-transform: rotate(360deg);
			transform: rotate(360deg);
		}
	}
	.ec_third_party_loader {
		font-family: sans-serif;
		font-weight: 100;
	}
	.ec_third_party_loader:not(:required) {
		-webkit-animation: ec_third_party_loader 1250ms infinite linear;
		-moz-animation: ec_third_party_loader 1250ms infinite linear;
		-ms-animation: ec_third_party_loader 1250ms infinite linear;
		-o-animation: ec_third_party_loader 1250ms infinite linear;
		animation: ec_third_party_loader 1250ms infinite linear;
		border: 8px solid #3388ee;
		border-right-color: transparent;
		border-radius: 16px;
		box-sizing: border-box;
		display: inline-block;
		position: relative;
		overflow: hidden;
		text-indent: -9999px;
		width: 32px;
		height: 32px;
	}
</style>

<div class="ec_third_party_loader" id="ideal_loader">Loading...</div>
<div class="ec_third_party_loader_bg" id="ideal_loader_bg"></div>
<script>
	jQuery( document.getElementById( 'ideal_loader' ) ).appendTo( document.body );
	jQuery( document.getElementById( 'ideal_loader_bg' ) ).appendTo( document.body );
	var ec_ideal_timer = window.setInterval( ec_ideal_waiting_for_response, 5000 );
	var ec_ideal_tries = 0;
	function ec_ideal_waiting_for_response( ){
		if( ec_ideal_tries >= 3 ){ // Skip checking for order update, probably bad webhook or network issues.
			clearInterval( ec_ideal_timer );
			var data = {
				action: 'ec_ajax_check_stripe_ideal_order_skip',
				source: '<?php echo esc_attr( htmlspecialchars( ( ( isset( $_GET['source'] ) ) ? sanitize_text_field( $_GET['source'] ) : $ideal_source ), ENT_QUOTES ) ); ?>',
				client_secret: '<?php echo esc_attr( htmlspecialchars( ( ( isset( $_GET['client_secret'] ) ) ? sanitize_text_field( $_GET['client_secret'] ) : $ideal_client_secret ), ENT_QUOTES ) ); ?>',
				nonce: '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-check-stripe-ideal-order-skip-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>'
			};
			jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
				json_response = JSON.parse( data );
				if( json_response.status == 'failed' ){
					jQuery( document.getElementById( 'ideal_loader' ) ).hide( );
					jQuery( document.getElementById( 'ideal_loader_bg' ) ).hide( );
					jQuery( document.getElementById( 'ec_ideal_error' ) ).show( );
				}else if( json_response.is_approved == '1' ){
					window.location.href = '<?php echo esc_url_raw( $this->cart_page . $this->permalink_divider ) . "ec_page=checkout_success&order_id=" ?>' + json_response.order_id;
				}else{
					window.location.href = '<?php echo esc_url_raw( $this->cart_page . $this->permalink_divider ) . "ec_page=checkout_success&ec_cart_error=ideal-pending&order_id=" ?>' + json_response.order_id;
				}
			} } );
		}else{
			var data = {
				action: 'ec_ajax_check_stripe_ideal_order',
				source: '<?php echo esc_attr( htmlspecialchars( ( ( isset( $_GET['source'] ) ) ? sanitize_text_field( $_GET['source'] ) : $ideal_source ), ENT_QUOTES ) ); ?>',
				client_secret: '<?php echo esc_attr( htmlspecialchars( ( ( isset( $_GET['client_secret'] ) ) ? sanitize_text_field( $_GET['client_secret'] ) : $ideal_client_secret ), ENT_QUOTES ) ); ?>',
				nonce: '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-check-stripe-ideal-order-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>'
			};
			jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
				ec_ideal_tries++;
				if( data == 'failed' ){
					clearInterval( ec_ideal_timer );
					jQuery( document.getElementById( 'ideal_loader' ) ).hide( );
					jQuery( document.getElementById( 'ideal_loader_bg' ) ).hide( );
					jQuery( document.getElementById( 'ec_ideal_error' ) ).show( );
				}else if( data != '0' ){
					clearInterval( ec_ideal_timer );
					window.location.href = '<?php echo esc_url_raw( $this->cart_page . $this->permalink_divider ) . "ec_page=checkout_success&order_id=" ?>' + data
				}
			} } );
		}
	}
</script>
<?php } ?>

<div class="ec_cart_left" id="ec_cart_payment_one_column" style="margin-bottom:25px;">
	<div class="ec_cart_header ec_top">
		<?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_title' ); ?>
	</div>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_first_name, ENT_QUOTES ) . ' ' . htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_last_name, ENT_QUOTES ) ); ?>
	</div>

	<?php if( strlen( $GLOBALS['ec_cart_data']->cart_data->billing_company_name ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_company_name, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1, ENT_QUOTES ) ); ?>
	</div>

	<?php if( strlen( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_city, ENT_QUOTES ) ); ?>, <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_state, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_zip, ENT_QUOTES ) ); ?>
	</div>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_country_name, ENT_QUOTES ) ); ?>
	</div>

	<?php if( strlen( $GLOBALS['ec_cart_data']->cart_data->billing_phone ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_phone, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>

	<?php if( strlen( $GLOBALS['ec_cart_data']->cart_data->vat_registration_number ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<strong><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_vat_registration_number' ); ?>:</strong> <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->vat_registration_number, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>

	<div class="ec_cart_input_row">
		<a href="<?php echo esc_attr( $this->cart_page . $this->permalink_divider ); ?>ec_page=checkout_info" class="wpeasycart_edit_billing_address_link_mobile"><?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_edit_billing_link' ); ?></a>
	</div>

	<?php if( get_option( 'ec_option_use_shipping' ) && ( $this->cart->shippable_total_items > 0 || $this->order_totals->handling_total > 0 || $this->cart->excluded_shippable_total_items > 0 ) ){ ?>

	<div class="ec_cart_header ec_top">
		<?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_title' ); ?>
	</div>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_first_name, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_last_name, ENT_QUOTES ) ); ?>
	</div>

	<?php if( strlen( $GLOBALS['ec_cart_data']->cart_data->shipping_company_name ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_company_name, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1, ENT_QUOTES ) ); ?>
	</div>

	<?php if( strlen( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_city, ENT_QUOTES ) ); ?>, <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_state, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_zip, ENT_QUOTES ) ); ?>
	</div>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_country_name, ENT_QUOTES ) ); ?>
	</div>

	<?php if( strlen( $GLOBALS['ec_cart_data']->cart_data->shipping_phone ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_phone, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>

	<div class="ec_cart_input_row">
		<a href="<?php echo esc_attr( $this->cart_page . $this->permalink_divider ); ?>ec_page=checkout_info" class="wpeasycart_edit_shipping_address_link_mobile"><?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_edit_shipping_link' ); ?></a>
	</div>
	
	<?php do_action( 'wp_easycart_cart_payment_after_edit_shipping_link', $this ); ?>

	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_shipping_method', 'cart_shipping_method_title' ); ?> 
	</div>
	<div class="ec_cart_input_row">
		<?php $this->display_selected_shipping_method( ); ?>
		<a href="<?php echo esc_attr( $this->cart_page . $this->permalink_divider ); ?>ec_page=checkout_shipping" class="wpeasycart_edit_shipping_method_link_mobile"><?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_edit_shipping_method_link' ); ?></a>
	</div>
	<?php }?>
</div>

<?php $this->display_page_three_form_start( ); ?>
<div class="ec_cart_left">

	<?php if( $this->order_totals->grand_total > 0 ){ ?>
	<div class="ec_cart_header ec_top">
		<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_payment_method' ); ?>
	</div>

	<div class="ec_cart_error_row" id="ec_payment_method_error">
		<?php echo wp_easycart_language( )->get_text( 'ec_errors', 'missing_payment_method' )?> 
	</div>

	<?php if( $this->use_manual_payment( ) ){?>
	<div class="ec_cart_option_row">
		<input type="radio" class="no_wrap" name="ec_cart_payment_selection" id="ec_payment_manual" value="manual_bill"<?php if( $this->get_selected_payment_method( ) == "manual_bill" ){ ?> checked="checked"<?php }?> onChange="ec_update_payment_display( '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-update-payment-method-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );" /> <?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_manual_payment' )?>
	</div>

	<div id="ec_manual_payment_form"<?php if( $this->get_selected_payment_method( ) == "manual_bill" ){ ?> style="display:block;"<?php }?>>
		<div class="ec_cart_box_section">
			<?php $this->display_manual_payment_text( ); ?>
		</div>
	</div>
	<?php } ?>

	<?php if( get_option( 'ec_option_use_affirm' ) ){ ?>
	<div class="ec_cart_option_row">
		<input type="radio" class="no_wrap" name="ec_cart_payment_selection" id="ec_payment_affirm" value="affirm"<?php if( $this->get_selected_payment_method( ) == "affirm" ){ ?> checked="checked"<?php }?> onChange="ec_update_payment_display( '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-update-payment-method-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );" /> <?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_affirm' ); ?>
	</div>

	<div id="ec_affirm_form"<?php if( $this->get_selected_payment_method( ) == "affirm" ){ ?> style="display:block;"<?php }?>>
		<div class="ec_cart_box_section ec_affirm_box">
			<script>
				function ec_checkout_with_affirm( ){
				affirm.checkout({
					config: {
						financial_product_key:		"<?php echo esc_attr( get_option( 'ec_option_affirm_financial_product' ) ); ?>"
					},
					merchant: {
						user_confirmation_url:		"<?php echo esc_attr( $this->cart_page . $this->permalink_divider ); ?>ec_page=process_affirm",
						user_cancel_url:			"<?php echo esc_attr( $this->cart_page . $this->permalink_divider ); ?>ec_page=checkout_payment"
					},
					billing: {
						name: {
							first:					"<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_first_name, ENT_QUOTES ) ); ?>",
							last:					"<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_last_name, ENT_QUOTES ) ); ?>"
						},
						address: {
							line1:					"<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1, ENT_QUOTES ) ); ?>",
							line2:					"<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2, ENT_QUOTES ) ); ?>",
							city:					"<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_city, ENT_QUOTES ) ); ?>",
							state:					"<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_state, ENT_QUOTES ) ); ?>",
							zipcode:				"<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_zip, ENT_QUOTES ) ); ?>",
							country:				"<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_country, ENT_QUOTES ) ); ?>"
						},
						phone_number:				"<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_phone, ENT_QUOTES ) ); ?>"<?php if( !class_exists( 'Email_Encoder' ) && !function_exists( 'eae_encode_emails' ) ){ ?>,
						email:						"<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->email, ENT_QUOTES ) ); ?>"<?php }?>
					},
					shipping: {
						name: {
							first:					"<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_first_name, ENT_QUOTES ) ); ?>",
							last:					"<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_last_name, ENT_QUOTES ) ); ?>"
						},
						address: {
							line1:					"<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1, ENT_QUOTES ) ); ?>",
							line2:					"<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2, ENT_QUOTES ) ); ?>",
							city:					"<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_city, ENT_QUOTES ) ); ?>",
							state:					"<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_state, ENT_QUOTES ) ); ?>",
							zipcode:				"<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_zip, ENT_QUOTES ) ); ?>",
							country:				"<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_country, ENT_QUOTES ) ); ?>"
						},
						phone_number:				"<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_phone, ENT_QUOTES ) ); ?>"
					},
					items: [<?php for( $i=0; $i<count( $this->cart->cart ); $i++ ){ ?>{
						display_name:         		"<?php echo esc_attr( $this->cart->cart[$i]->title ); ?>",
						sku:                  		"<?php echo esc_attr( $this->cart->cart[$i]->model_number ); ?>",
						unit_price:           		<?php echo esc_attr( number_format( ( 100 * $this->cart->cart[$i]->unit_price ), 0, '', '' ) ); ?>,
						qty:                  		<?php echo esc_attr( $this->cart->cart[$i]->quantity ); ?>,
						item_image_url:       		"<?php echo esc_attr( $this->cart->cart[$i]->get_image_url( ) ); ?>",
						item_url:             		"<?php echo esc_attr( $this->cart->cart[$i]->get_product_url( ) ); ?>"
					},<?php }?>],
					tax_amount:						<?php echo esc_attr( number_format( ( 100 * $this->order_totals->tax_total ), 0, '', '' ) ); ?>,
					shipping_amount:				<?php echo esc_attr( number_format( ( 100 * $this->order_totals->shipping_total ), 0, '', '' ) ); ?>
				});
				affirm.checkout.open( );
			}
			</script>

			<a href="https://www.affirm.com" target="_blank"><img src="<?php echo esc_attr( $this->get_payment_image_source( "affirm-banner-540x200.png" ) ); ?>" alt="Affirm Split Pay" /></a>
		</div>
	</div>
	<?php }?>

	<?php if( $this->use_third_party( ) ){?>
	<div class="ec_cart_option_row">
		<input type="radio" class="no_wrap" name="ec_cart_payment_selection" id="ec_payment_third_party" value="third_party"<?php if( $this->get_selected_payment_method( ) == "third_party" ){ ?> checked="checked"<?php }?> onChange="ec_update_payment_display( '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-update-payment-method-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );" /> <?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_third_party' )?> <?php $this->ec_cart_display_current_third_party_name( ); ?>
	</div>


	<div id="ec_third_party_form"<?php if( $this->get_selected_payment_method( ) == "third_party" ){ ?> style="display:block;"<?php }?>>
		<div class="ec_cart_box_section">
			<?php if( get_option( 'ec_option_payment_third_party' ) != "paypal" || get_option( 'ec_option_paypal_enable_pay_now' ) != '1' ){
				echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_third_party_first' )?> <?php $this->ec_cart_display_current_third_party_name( ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_third_party_second' ) . '<br />';
			}?>

			<?php if( get_option( 'ec_option_payment_third_party' ) == "paypal" ){ ?>
				<img src="<?php echo esc_attr( $this->get_payment_image_source( "paypal.jpg" ) ); ?>" alt="PayPal" />

			<?php }else if( get_option( 'ec_option_payment_third_party' ) == "skrill" ){ ?>
				<img src="<?php echo esc_attr( $this->get_payment_image_source( "skrill-logo.gif" ) ); ?>" alt="Skrill" />

			<?php }else if( get_option( 'ec_option_realex_thirdparty_type' ) == 'hpp' && get_option( 'ec_option_payment_third_party' ) == "realex_thirdparty" ){  ?>
				<script>
				jQuery( document ).ready( function( ){
					var data = {
						action: "ec_ajax_realex_hpp_init",
						total: "<?php echo esc_js( $this->order_totals->grand_total ); ?>"
					};
					jQuery.ajax( { url: wpeasycart_ajax_object.ajax_url, type: "post", data: data, success: function( data ){
						<?php if( get_option( 'ec_option_realex_thirdparty_test_mode' ) ){ ?>RealexHpp.setHppUrl('https://pay.sandbox.realexpayments.com/pay');
						<?php }?>RealexHpp.init( "ec_cart_submit_order", "<?php echo esc_attr( $this->cart_page . $this->permalink_divider ) . "ec_page=checkout_success&order_id="; ?>" + data.order_id, data.response );
					} } );
				} );
				</script>

			<?php }?>

			<?php do_action( 'wpeasycart_third_party_checkout_box' ); ?>

		</div>
	</div>
	<?php }?>

	<?php if( $this->use_payment_gateway( ) ){?>

	<div class="ec_cart_option_row">
		<input type="radio" class="no_wrap" name="ec_cart_payment_selection" id="ec_payment_credit_card" value="credit_card"<?php if( $this->get_selected_payment_method( ) == "credit_card" ){ ?> checked="checked"<?php }?> onChange="ec_update_payment_display( '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-update-payment-method-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );" /> <?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_credit_card' )?>
	</div>

	<div id="ec_credit_card_form"<?php if( $this->get_selected_payment_method( ) == "credit_card" ){ ?> style="display:block;"<?php }?>>
		<div class="ec_cart_box_section">
			<?php if( get_option( 'ec_option_payment_process_method' ) == "square"  && $this->order_totals->grand_total < 1 ){ ?>
			<p style="font-size:18px; color:red">Minimum Order Total of $1.00 is Required!</h1>
			<?php }else if( ( get_option( 'ec_option_payment_process_method' ) == "stripe" || get_option( 'ec_option_payment_process_method' ) == "stripe_connect" ) && $this->order_totals->grand_total < .5 ){ ?>
			<p style="font-size:18px; color:red">Minimum Order Total of $0.50 is Required!</h1>
			<?php }?>

			<?php if( get_option( 'ec_option_payment_process_method' ) == "square" ){
				$this->print_square_payment_button( true );

			} else if( ( get_option( 'ec_option_payment_process_method' ) == 'stripe' && get_option( 'ec_option_stripe_public_api_key' ) != "" ) || ( get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) ) {
			do_action( 'wp_easycart_display_stripe_payment_pre' );
			if ( $this->order_totals->grand_total >= .5 ) { ?>
			<div class="form-row" style="margin-top:12px;">
				<div id="ec_stripe_card_row">
				  <!-- a Stripe Element will be inserted here. -->
				</div>

				<!-- Used to display form errors -->
				<div id="ec_card_errors" role="alert" style="color:rgb(181, 41, 41); float:left; width:100%; margin-top:5px; text-align:center; background:rgb(241, 241, 241);"></div>
			</div>

			<div id="stripe-success-cover" style="display:none; cursor:default; position:fixed; top:0; left:0; width:100%; height:100%; z-index:999999; background-color: rgba(0, 0, 0, 0.8); color:#FFF;">
				<style>
				@keyframes rotation{
					0%  { transform:rotate(0deg); }
					100%{ transform:rotate(359deg); }
				}
				</style>
				<div style='font-family: "HelveticaNeue", "HelveticaNeue-Light", "Helvetica Neue Light", helvetica, arial, sans-serif; font-size: 14px; text-align: center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; width: 350px; top: 50%; left: 50%; position: absolute; margin-left: -165px; margin-top: -80px; cursor: pointer; text-align: center; background:#EFEFEF; border-radius:10px; padding:25px;'>
					<div class="paypal-checkout-loader">
						<div style="height: 30px; width: 30px; display: inline-block; box-sizing: content-box; opacity: 1; filter: alpha(opacity=100); -webkit-animation: rotation .7s infinite linear; -moz-animation: rotation .7s infinite linear; -o-animation: rotation .7s infinite linear; animation: rotation .7s infinite linear; border-left: 8px solid rgba(0, 0, 0, .2); border-right: 8px solid rgba(0, 0, 0, .2); border-bottom: 8px solid rgba(0, 0, 0, .2); border-top: 8px solid #fff; border-radius: 100%;"></div>
					</div>
					<div style="float:left; width:100%; font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Ubuntu,sans-serif; margin-top:10px; color:#222; font-size:18px;"><?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_please_wait' )?></div>
				</div>
			</div>
			<script><?php
				if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
					$pkey = get_option( 'ec_option_stripe_public_api_key' );
				} else if ( get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' && get_option( 'ec_option_stripe_connect_use_sandbox' ) ) {
					$pkey = get_option( 'ec_option_stripe_connect_sandbox_publishable_key' );
				} else {
					$pkey = get_option( 'ec_option_stripe_connect_production_publishable_key' );
				}
				?>
				jQuery( document.getElementById( 'stripe-success-cover' ) ).appendTo( document.body );
				try {
					var stripe = Stripe( '<?php echo esc_attr( $pkey ); ?>' );
					const options = {
						clientSecret: '<?php echo esc_attr( $this->get_stripe_intent_client_secret() ); ?>',
						appearance: {
							theme: '<?php echo esc_attr( get_option( 'ec_option_stripe_payment_theme' ) ); ?>',

						},
					};
					const elements = stripe.elements( options );
					const paymentElement = elements.create( 'payment', {
						<?php if ( 'accordion' == get_option( 'ec_option_stripe_payment_layout' ) ) { ?>layout: {
							type: 'accordion',
							defaultCollapsed: false,
							radios: false,
							spacedAccordionItems: false
						},<?php } else { ?>layout: {
							type: 'tabs',
							defaultCollapsed: false
						},<?php }?><?php if ( ! get_option( 'ec_option_stripe_enable_apple_pay' ) ) { ?>
						wallets: {
							applePay: 'never',
							googlePay: 'never'
						},<?php }?>
						defaultValues: {
							billingDetails: {
								name: '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_first_name, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_last_name, ENT_QUOTES ) ); ?>',<?php if( !class_exists( 'Email_Encoder' ) && !function_exists( 'eae_encode_emails' ) ){ ?>
								email: '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->email, ENT_QUOTES ) ); ?>',<?php }?><?php if( get_option( 'ec_option_collect_user_phone' ) ) {?>
								phone: '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_phone, ENT_QUOTES ) ); ?>',<?php }?>
								address: {
									line1: '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1, ENT_QUOTES ) ); ?>',
									line2: '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2, ENT_QUOTES ) ); ?>',
									city: '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_city, ENT_QUOTES ) ); ?>',
									state: '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_state, ENT_QUOTES ) ); ?>',
									country: '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_country, ENT_QUOTES ) ); ?>',
									postal_code: '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_zip, ENT_QUOTES ) ); ?>',
								},
							},
						},
						fields: {
							billingDetails: {
								name: 'never',
								address: 'never',
							},
						},
					} );
					paymentElement.mount( '#ec_stripe_card_row' );
					paymentElement.addEventListener( 'change', function( event ){
						var displayError = document.getElementById( 'ec_card_errors' );
						if( event.error ){
							displayError.textContent = event.error.message;
						}else{
							displayError.textContent = '';
						}
					} );
					var form = document.getElementById( 'ec_submit_order_form' );
					form.addEventListener( 'submit', function( event ){
						var payment_method = "credit_card";
						if( jQuery( 'input:radio[name=ec_cart_payment_selection]:checked' ).length )
							payment_method = jQuery( 'input:radio[name=ec_cart_payment_selection]:checked' ).val( );
						if( payment_method != 'credit_card' ){
							jQuery( document.getElementById( 'ec_submit_order_error' ) ).hide( );
						}else{
							event.preventDefault( );
							jQuery( document.getElementById( 'ec_cart_submit_order' ) ).hide( );
							jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).show( );
							jQuery( document.getElementById( 'stripe-success-cover' ) ).show( );
							jQuery( document.getElementById( 'ec_stripe_dynamic_error' ) ).hide( );
							jQuery( document.getElementById( 'ec_card_errors' ) ).hide( );
							var name = '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_first_name, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_last_name, ENT_QUOTES ) ); ?>';
							var address1 = '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1, ENT_QUOTES ) ); ?>';
							var address2 = '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2, ENT_QUOTES ) ); ?>';
							var city = '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_city, ENT_QUOTES ) ); ?>';
							var state = '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_state, ENT_QUOTES ) ); ?>';
							var zip = '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_zip, ENT_QUOTES ) ); ?>';
							var country = '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_country, ENT_QUOTES ) ); ?>';
							var phone = '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_phone, ENT_QUOTES ) ); ?>';
							var shipping_name = '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_first_name, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_last_name, ENT_QUOTES ) ); ?>';
							var shipping_address1 = '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1, ENT_QUOTES ) ); ?>';
							var shipping_address2 = '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2, ENT_QUOTES ) ); ?>';
							var shipping_city = '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_city, ENT_QUOTES ) ); ?>';
							var shipping_state = '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_state, ENT_QUOTES ) ); ?>';
							var shipping_zip = '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_zip, ENT_QUOTES ) ); ?>';
							var shipping_country = '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_country, ENT_QUOTES ) ); ?>';
							var shipping_phone = '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_phone, ENT_QUOTES ) ); ?>';<?php if( !class_exists( 'Email_Encoder' ) && !function_exists( 'eae_encode_emails' ) ){ ?>
							var email = '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->email, ENT_QUOTES ) ); ?>';<?php }?>
							var ec_terms_agree = 0;
							if( jQuery( document.getElementById( 'ec_terms_agree' ) ).length && jQuery( document.getElementById( 'ec_terms_agree' ) ).is( ':checked' ) ){
								ec_terms_agree = 1;
							}
							var ec_cart_is_subscriber = 0;
							if( jQuery( document.getElementById( 'ec_cart_is_subscriber' ) ).length && jQuery( document.getElementById( 'ec_cart_is_subscriber' ) ).is( ':checked' ) ){
								ec_cart_is_subscriber = 1;
							}
							var additionalData = {
								name: name,
								address_line1: address1,
								address_city: city,
								address_state: state,
								address_zip: zip
							};
							stripe.confirmPayment( {
								elements,
								confirmParams: {
									return_url: '<?php echo esc_url_raw( $this->cart_page . $this->permalink_divider . "ec_page=checkout_payment&stripe=returning&wpecnonce=" . wp_create_nonce( 'wp-easycart-stripe-pi-order-complete-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>',
									shipping: {
										address: {
											line1: shipping_address1,
											city: shipping_city,
											country: shipping_country,
											line2: shipping_address2,
											postal_code: shipping_zip,
											state: shipping_state
										},
										name: shipping_name,
										phone: shipping_phone
									},
									payment_method_data: {
										billing_details: {
											address: {
												city: city,
												country: country,
												line1: address1,
												line2: address2,
												postal_code: zip,
												state: state
											},<?php if( !class_exists( 'Email_Encoder' ) && !function_exists( 'eae_encode_emails' ) ){ ?>
											email: email,<?php }?>
											name: name<?php if( $GLOBALS['ec_cart_data']->cart_data->billing_phone != '' ){ ?>,
											phone: phone<?php }?>
										}
									}
								},
								redirect: 'if_required'
							} ).then( function( result ){
								if( result.error ){
									jQuery( document.getElementById( 'ec_cart_submit_order' ) ).show( );
									jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).hide( );
									jQuery( document.getElementById( 'stripe-success-cover' ) ).fadeOut( );
									jQuery( document.getElementById( 'ec_stripe_dynamic_error' ) ).fadeIn( ).find( 'div' ).html( result.error.message );
									jQuery( document.getElementById( 'ec_card_errors' ) ).fadeIn( ).html( result.error.message );
								}else{
									if ( 'processing' == result.paymentIntent.status || 'succeeded' == result.paymentIntent.status || 'requires_capture' == result.paymentIntent.status ) {
										var data = {
											action: 'ec_ajax_get_stripe_complete_payment_main',
											language: wpeasycart_ajax_object.current_language,
											ec_terms_agree: ec_terms_agree,
											ec_cart_is_subscriber: ec_cart_is_subscriber,
											payment_status: result.paymentIntent.status,
											nonce: '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-get-stripe-complete-payment-main-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>'
										};
										jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( result ){
											jQuery( location ).attr( 'href', result );
										} } );
									} else if ( 'requires_action' == result.paymentIntent.status || 'requires_source_action' == result.paymentIntent.status ) {
										ec_stripe_3ds_waiting_for_response( result.paymentIntent.id, result.paymentIntent.client_secret );
									} else if ( 'requires_source' == result.paymentIntent.status || 'requires_payment_method' == result.paymentIntent.status ) {
										jQuery( document.getElementById( 'ec_cart_submit_order' ) ).show( );
										jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).hide( );
										jQuery( document.getElementById( 'stripe-success-cover' ) ).fadeOut( );
										jQuery( document.getElementById( 'ec_card_errors' ) ).fadeIn( ).html( result.paymentIntent.last_payment_error.message );
									} else {
										ec_create_ideal_order_redirect( result.paymentIntent.id, '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-create-stripe-ideal-order-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );
										console.log( result );
									}
								}
							} );
						}
					} );
				} catch( err ) {
					alert( "Your WP EasyCart with Stripe has a problem: " + err.message + ". Contact WP EasyCart for assistance." );
				}
				var ec_stripe_3ds_tries = 0;
				function ec_stripe_3ds_waiting_for_response( payment_id, client_secret ){
					if ( ec_stripe_3ds_tries >= 20 ) { // Skip checking for order update, probably bad webhook or network issues.
						ec_stripe_3ds_tries = 0;
						jQuery( document.getElementById( 'ec_cart_submit_order' ) ).show( );
						jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).hide( );
						jQuery( document.getElementById( 'stripe-success-cover' ) ).fadeOut( );
						jQuery( document.getElementById( 'ec_stripe_error' ) ).show();
						jQuery( document.getElementById( 'ec_submit_order_error' ) ).show();
					}else{
						var data = {
							action: 'ec_ajax_check_stripe_3ds_order',
							source: payment_id,
							client_secret: client_secret,
							nonce: '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-check-stripe-3ds-order-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>'
						};
						jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( result ) { 
							ec_stripe_3ds_tries++;
							var json_result = JSON.parse( result );
							if( 'requires_payment_method' == json_result.status ){
								ec_stripe_3ds_tries = 0;
								jQuery( document.getElementById( 'ec_cart_submit_order' ) ).show( );
								jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).hide( );
								jQuery( document.getElementById( 'stripe-success-cover' ) ).fadeOut( );
								jQuery( document.getElementById( 'ec_stripe_error' ) ).show();
								jQuery( document.getElementById( 'ec_submit_order_error' ) ).show();
							} else if ( 'succeeded' == json_result.status || 'processing' == json_result.status || 'requires_capture' == json_result.status ) {
								ec_stripe_3ds_tries = 0;
								var ec_terms_agree = 0;
								var ec_cart_is_subscriber = 0;
								if( jQuery( document.getElementById( 'ec_terms_agree' ) ).length && jQuery( document.getElementById( 'ec_terms_agree' ) ).is( ':checked' ) ){
									ec_terms_agree = 1;
								}
								if( jQuery( document.getElementById( 'ec_cart_is_subscriber' ) ).length && jQuery( document.getElementById( 'ec_cart_is_subscriber' ) ).is( ':checked' ) ){
									ec_cart_is_subscriber = 1;
								}
								var data = {
									action: 'ec_ajax_get_stripe_complete_payment_main',
									language: wpeasycart_ajax_object.current_language,
									ec_terms_agree: ec_terms_agree,
									ec_cart_is_subscriber: ec_cart_is_subscriber,
									payment_status: json_result.status,
									nonce: '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-get-stripe-complete-payment-main-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>'
								};
								jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( result ){
									jQuery( location ).attr( 'href', result );
								} } );
							} else {
								ec_stripe_3ds_waiting_for_response( payment_id, client_secret );
							}
						} } );
					}
				}
			</script>
			<?php } /* Close Minimum Required check*/ ?>

			<?php }else if( get_option( 'ec_option_payment_process_method' ) == "braintree" ){ // Close if Stripe Only Form ?>
			<?php $braintree_gateway = new ec_braintree( ); ?>
			<div id="wpec_braintree_dropin"></div>
			<input type="hidden" id="braintree_nonce" name="braintree_nonce" value="" />
			<style>
			.braintree-large-button.braintree-toggle{ display:none !important; }
			</style>
			<script>
				var form = document.querySelector( '#ec_submit_order_form' );
				var client_token = "<?php echo esc_attr( $braintree_gateway->get_client_token( ) ); ?>";
				braintree.dropin.create(
					{
						authorization: client_token,
						selector: '#wpec_braintree_dropin'
					}, 
					function( createErr, instance ){
						if( createErr ){
							console.log( 'Create Error', createErr );
							return;
						}
						form.addEventListener(
							'submit', 
							function( event ){
								var payment_method = "credit_card";
								if( jQuery( 'input:radio[name=ec_cart_payment_selection]:checked' ).length )
									payment_method = jQuery( 'input:radio[name=ec_cart_payment_selection]:checked' ).val( );
								if( payment_method != 'credit_card' ){
									jQuery( document.getElementById( 'ec_submit_order_error' ) ).hide( );
								}else{
									event.preventDefault( );
									jQuery( document.getElementById( 'ec_cart_submit_order' ) ).hide( );
									jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).show( );
									jQuery( document.getElementById( 'ec_submit_order_error' ) ).hide( );
									instance.requestPaymentMethod(
										function( err, payload ){
											if (err) {
												jQuery( document.getElementById( 'ec_submit_order_error' ) ).show( );
												jQuery( document.getElementById( 'ec_cart_submit_order' ) ).show( );
												jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).hide( );
												console.log( 'Request Payment Method Error', err );
												return;
											}
											document.querySelector( '#braintree_nonce' ).value = payload.nonce;
											form.submit( );
										}
									);
								}
							}
						);
					}
				);
			</script>
			<?php }else if( get_option( 'ec_option_payment_process_method' ) == "cardpointe" ){ // Close if Braintree Only Form ?>
			<input type="hidden" name="cardpointe_token" id="cardpointe_token" />
			<iframe id="cardpointeTokenFrame" name="cardpointeTokenFrame" src="https://<?php echo esc_attr( get_option( 'ec_option_cardpointe_site' ) ); ?>.cardconnect.com/itoke/ajax-tokenizer.html?useexpiry=true&usecvv=true&orientation=horizontal&tokenizewheninactive=true&css=<?php echo urlencode( 'br{display:inline-block;width:100%;clear:both;}label{display:inline-block;font-family:Arial, sans-serif;font-size:12px;font-weight:bold;color:#222222;margin-top:10px; }input,select{border-color:#e1e1e1; background-color:#f8f8f8; color:#919191; -webkit-appearance:none; border:1px solid #e1e1e1; outline:none; font:13px "HelveticaNeue", "Helvetica Neue", Helvetica, Arial, sans-serif; color:#777; margin:0; display:inline-block; max-width:100%; background:#FFF; padding:8px 6px; line-height:1.1em; box-sizing:border-box; -moz-box-sizing:border-box; -webkit-box-sizing:border-box; height:39px;}.error{color:red;}input[id="ccnumfield"]{width:100%;}select{width:46px;}' ); ?>" frameborder="0" scrolling="no"></iframe>
			<script language="JavaScript">
				window.addEventListener( 'message', function(event) {
					var token = JSON.parse( event.data );
					var mytoken = document.getElementById( 'cardpointe_token' );
					if( token.message ){
						mytoken.value = token.message;
					}
				}, false );
			</script>

			<?php }else{ // Close if Card Pointe Only Form ?>

			<div class="ec_cart_input_row" style="margin-top:-10px;">
				<?php if( get_option('ec_option_use_visa') || get_option('ec_option_use_delta') || get_option('ec_option_use_uke') ){ ?>
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "visa.png" ) ); ?>" alt="Visa" class="ec_card_active" id="ec_card_visa" />
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "visa_inactive.png" ) ); ?>" alt="Visa" class="ec_card_inactive" id="ec_card_visa_inactive" />
				<?php }?>

				<?php if( get_option('ec_option_use_discover') ){ ?>
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "discover.png" ) ); ?>" alt="Discover" class="ec_card_active" id="ec_card_discover" />
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "discover_inactive.png" ) ); ?>" alt="Discover" class="ec_card_inactive" id="ec_card_discover_inactive" />
				<?php }?>

				<?php if( get_option('ec_option_use_mastercard') || get_option('ec_option_use_mcdebit') ){ ?>
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "mastercard.png") ); ?>" alt="Mastercard" class="ec_card_active" id="ec_card_mastercard" />
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "mastercard_inactive.png") ); ?>" alt="Mastercard" class="ec_card_inactive" id="ec_card_mastercard_inactive" />
				<?php }?>

				<?php if( get_option('ec_option_use_amex') ){ ?>
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "american_express.png") ); ?>" alt="AMEX" class="ec_card_active" id="ec_card_amex" />
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "american_express_inactive.png") ); ?>" alt="AMEX" class="ec_card_inactive" id="ec_card_amex_inactive" />
				<?php }?>

				<?php if( get_option('ec_option_use_jcb') ){ ?>
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "jcb.png") ); ?>" alt="JCB" class="ec_card_active" id="ec_card_jcb" />
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "jcb_inactive.png") ); ?>" alt="JCB" class="ec_card_inactive" id="ec_card_jcb_inactive" />
				<?php }?>

				<?php if( get_option('ec_option_use_diners') ){ ?>
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "diners.png") ); ?>" alt="Diners" class="ec_card_active" id="ec_card_diners" />
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "diners_inactive.png") ); ?>" alt="Diners" class="ec_card_inactive" id="ec_card_diners_inactive" />
				<?php }?>

				<?php if( get_option('ec_option_use_maestro') || get_option('ec_option_use_laser')){ ?>
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "maestro.png") ); ?>" alt="Maestro" class="ec_card_active" id="ec_card_maestro" />
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "maestro_inactive.png") ); ?>" alt="Maestro" class="ec_card_inactive" id="ec_card_maestro_inactive" />
				<?php }?>
			</div>

			<?php if( get_option( 'ec_option_show_card_holder_name' ) ){ ?>
			<div class="ec_cart_input_row">
				<input name="ec_card_holder_name" id="ec_card_holder_name" type="text" class="input-lg form-control" placeholder="<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_card_holder_name' )?>">
				<div class="ec_cart_error_row" id="ec_card_holder_name_error">
					<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_valid' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_card_holder_name' )?>
				</div>
			</div>
			<?php }else{ ?>
			<?php $this->ec_cart_display_card_holder_name_hidden_input(); ?>
			<?php } ?>
			<div class="ec_cart_input_row">
				<div>
					<input name="ec_card_number" id="ec_card_number"<?php if( get_option( 'ec_option_payment_process_method' ) == "eway" && get_option( 'ec_option_eway_use_rapid_pay' ) ){?> data-eway-encrypt-name="ec_card_number"<?php }?> type="tel" class="input-lg form-control cc-number" autocomplete="cc-number" placeholder="<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_card_number' )?>">
				</div>
				<div class="ec_cart_error_row" id="ec_card_number_error">
					<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_valid' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_card_number' )?>
				</div>
			</div>
			<div class="ec_cart_input_row">
				<div class="ec_cart_input_left_half">
					<div>
						<input name="ec_cc_expiration" id="ec_cc_expiration" type="tel" class="input-lg form-control cc-exp" autocomplete="cc-exp" placeholder="MM / YYYY">
					</div>
					<div class="ec_cart_error_row" id="ec_expiration_date_error" style="padding:8px;">
						<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_valid' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_expiration_date' )?>
					</div>
				</div>
				<div class="ec_cart_input_right_half">
					<div>
						<input name="ec_security_code" id="ec_security_code"<?php if( get_option( 'ec_option_payment_process_method' ) == "eway" && get_option( 'ec_option_eway_use_rapid_pay' ) ){?> data-eway-encrypt-name="ec_security_code"<?php }?> type="tel" class="input-lg form-control cc-cvc" autocomplete="off" placeholder="CVV">
					</div>
					<div class="ec_cart_error_row" id="ec_security_code_error" style="padding:8px;">
						<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_valid' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_security_code' )?>
					</div>
				</div>
			</div>
			<?php } //else from Stripe only check ?>
		</div>
		<?php do_action( 'wp_easycart_end_live_payment_box_inner', $this ); ?>
	</div>

	<?php } //close if/else check for live gateway ?>

	<?php do_action( 'wp_easycart_cart_payment_payment_methods_end', $this ); ?>

	<?php } //close if/else check for free order ?>

	<div class="ec_cart_header<?php if( $this->order_totals->grand_total <= 0 ){ ?> ec_top<?php }?>">
		<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_review_title' )?>
	</div>

	<?php for( $cartitem_index = 0; $cartitem_index<count( $this->cart->cart ); $cartitem_index++ ){ ?>

	<div class="ec_cart_price_row ec_cart_price_row_cartitem_<?php echo esc_attr( $cartitem_index ); ?>">
		<div class="ec_cart_price_row_label"><?php $this->cart->cart[$cartitem_index]->display_title( ); ?><?php if( $this->cart->cart[$cartitem_index]->grid_quantity > 1 ){ ?> x <?php echo esc_attr( $this->cart->cart[$cartitem_index]->grid_quantity ); ?><?php }else if( $this->cart->cart[$cartitem_index]->quantity > 1 ){ ?> x <?php echo esc_attr( $this->cart->cart[$cartitem_index]->quantity ); ?><?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->stock_quantity <= 0 && $this->cart->cart[$cartitem_index]->allow_backorders ){ ?>
		<div class="ec_cart_backorder_date"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_backordered' ); ?><?php if( $this->cart->cart[$cartitem_index]->backorder_fill_date != "" ){ ?> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_backorder_until' ); ?> <?php echo wp_easycart_escape_html( $this->cart->cart[$cartitem_index]->backorder_fill_date ); ?><?php }?></div>
		<?php }?>
		<?php if( $this->cart->cart[$cartitem_index]->optionitem1_name ){ ?>
		<dl>
			<dt><?php echo esc_attr( $this->cart->cart[$cartitem_index]->optionitem1_name ); ?><?php if( $this->cart->cart[$cartitem_index]->optionitem1_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem1_price ) ); ?> )<?php }else if( $this->cart->cart[$cartitem_index]->optionitem1_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem1_price ) ); ?> )<?php } ?></dt>

		<?php if( $this->cart->cart[$cartitem_index]->optionitem2_name ){ ?>
			<dt><?php echo esc_attr( $this->cart->cart[$cartitem_index]->optionitem2_name ); ?><?php if( $this->cart->cart[$cartitem_index]->optionitem2_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem2_price ) ); ?> )<?php }else if( $this->cart->cart[$cartitem_index]->optionitem2_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem2_price ) ); ?> )<?php } ?></dt>
		<?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->optionitem3_name ){ ?>
			<dt><?php echo esc_attr( $this->cart->cart[$cartitem_index]->optionitem3_name ); ?><?php if( $this->cart->cart[$cartitem_index]->optionitem3_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem3_price ) ); ?> )<?php }else if( $this->cart->cart[$cartitem_index]->optionitem3_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem3_price ) ); ?> )<?php } ?></dt>
		<?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->optionitem4_name ){ ?>
			<dt><?php echo esc_attr( $this->cart->cart[$cartitem_index]->optionitem4_name ); ?><?php if( $this->cart->cart[$cartitem_index]->optionitem4_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem4_price ) ); ?> )<?php }else if( $this->cart->cart[$cartitem_index]->optionitem4_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem4_price ) ); ?> )<?php } ?></dt>
		<?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->optionitem5_name ){ ?>
			<dt><?php echo esc_attr( $this->cart->cart[$cartitem_index]->optionitem5_name ); ?><?php if( $this->cart->cart[$cartitem_index]->optionitem5_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem5_price ) ); ?> )<?php }else if( $this->cart->cart[$cartitem_index]->optionitem5_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem5_price ) ); ?> )<?php } ?></dt>
		<?php }?>
		</dl>
		<?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->use_advanced_optionset || $this->cart->cart[$cartitem_index]->use_both_option_types ){ ?>
		<dl>
		<?php foreach( $this->cart->cart[$cartitem_index]->advanced_options as $advanced_option_set ){ ?>
			<?php if( $advanced_option_set->option_type == "grid" ){ ?>
				<dt><?php echo esc_attr( $advanced_option_set->optionitem_name ); ?>: <?php echo esc_attr( $advanced_option_set->optionitem_value ); ?><?php
					if ( $advanced_option_set->optionitem_enable_custom_price_label && ( $advanced_option_set->optionitem_price != 0 || ( isset( $advanced_option_set->optionitem_price ) && $advanced_option_set->optionitem_price != 0 ) || ( isset( $advanced_option_set->optionitem_price_onetime ) && $advanced_option_set->optionitem_price_onetime != 0 ) ) ) {
						echo '<span class="ec_cart_line_optionitem_pricing"> ' . esc_attr( wp_easycart_language( )->convert_text( $advanced_option_set->optionitem_custom_price_label ) ) . '</span>';
					} else if ( $advanced_option_set->optionitem_price > 0 ) {
						echo '<span class="ec_cart_line_optionitem_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
					} else if ( $advanced_option_set->optionitem_price < 0 ) {
						echo '<span class="ec_cart_line_optionitem_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
					} else if ( $advanced_option_set->optionitem_price_onetime > 0 ) {
						echo '<span class="ec_cart_line_optionitem_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
					} else if ( $advanced_option_set->optionitem_price_onetime < 0 ) {
						echo '<span class="ec_cart_line_optionitem_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
					} else if ( $advanced_option_set->optionitem_price_override > -1 ) {
						echo '<span class="ec_cart_line_optionitem_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_override ) ) . ')</span>';
					} ?></dt>
			<?php } else if ( $advanced_option_set->option_type == "dimensions1" || $advanced_option_set->option_type == "dimensions2" ) { ?>
			<strong><?php echo wp_easycart_escape_html( $advanced_option_set->option_label ); ?>:</strong><br /><?php $dimensions = json_decode( $advanced_option_set->optionitem_value ); if( count( $dimensions ) == 2 ){ echo esc_attr( $dimensions[0] ); if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ echo "\""; } echo " x " . esc_attr( $dimensions[1] ); if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ echo "\""; } }else if( count( $dimensions ) == 4 ){ echo esc_attr( $dimensions[0] . " " . $dimensions[1] . "\" x " . $dimensions[2] . " " . $dimensions[3] ) . "\""; } ?><br />

			<?php } else { ?>
				<dt><?php echo wp_easycart_escape_html( $advanced_option_set->option_label ); ?>: <?php echo esc_attr( $advanced_option_set->optionitem_value ); ?><?php 
					if ( $advanced_option_set->optionitem_enable_custom_price_label && ( $advanced_option_set->optionitem_price != 0 || ( isset( $advanced_option_set->optionitem_price ) && $advanced_option_set->optionitem_price != 0 ) || ( isset( $advanced_option_set->optionitem_price_onetime ) && $advanced_option_set->optionitem_price_onetime != 0 ) ) ) {
						echo '<span class="ec_cart_line_optionitem_pricing"> ' . esc_attr( wp_easycart_language( )->convert_text( $advanced_option_set->optionitem_custom_price_label ) ) . '</span>';
					} else if( $advanced_option_set->optionitem_price > 0 ) {
						echo '<span class="ec_cart_line_optionitem_pricing">' . esc_attr( ' (+' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
					} else if ( $advanced_option_set->optionitem_price < 0 ) {
						echo '<span class="ec_cart_line_optionitem_pricing">' . esc_attr( ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
					} else if ( $advanced_option_set->optionitem_price_onetime > 0 ) {
						echo '<span class="ec_cart_line_optionitem_pricing">' . esc_attr( ' (+' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
					} else if ( $advanced_option_set->optionitem_price_onetime < 0 ) {
						echo '<span class="ec_cart_line_optionitem_pricing">' . esc_attr( ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
					} else if ( $advanced_option_set->optionitem_price_override > -1 ) {
						echo '<span class="ec_cart_line_optionitem_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_override ) ) . ')</span>';
					} ?></dt>
			<?php } ?>
		<?php }?>
		</dl>
		<?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->is_giftcard ){ ?>
		<dl>
		<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_recipient_name' ); ?>: <?php echo esc_attr( htmlspecialchars( $this->cart->cart[$cartitem_index]->gift_card_to_name, ENT_QUOTES ) ); ?></dt>
		<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_recipient_email' ); ?>: <?php echo esc_attr( htmlspecialchars( $this->cart->cart[$cartitem_index]->gift_card_email, ENT_QUOTES ) ); ?></dt>
		<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_sender_name' ); ?>: <?php echo esc_attr( htmlspecialchars( $this->cart->cart[$cartitem_index]->gift_card_from_name, ENT_QUOTES ) ); ?></dt>
		<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_message' ); ?>: <?php echo esc_attr( htmlspecialchars( $this->cart->cart[$cartitem_index]->gift_card_message, ENT_QUOTES ) ); ?></dt>
		</dl>
		<?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->is_deconetwork ){ ?>
		<dl>
		<dt><?php echo esc_attr( $this->cart->cart[$cartitem_index]->deconetwork_options ); ?></dt>
		<dt><?php echo "<a href=\"https://" . esc_attr( get_option( 'ec_option_deconetwork_url' ) ) . esc_attr( $this->cart->cart[$cartitem_index]->deconetwork_edit_link ) . "\">" . wp_easycart_language( )->get_text( 'cart', 'deconetwork_edit' ) . "</a>"; ?></dt>
		</dl>
		<?php }?>

		<?php do_action( 'wp_easycart_cartitem_post_optionitems', $this->cart->cart[$cartitem_index] ); ?>

		</div>
		<div class="ec_cart_price_row_total" id="ec_cart_subtotal"><?php echo esc_attr( $this->cart->cart[$cartitem_index]->get_total( ) ); ?></div>
	</div>

	<?php }?>

	<div class="ec_cart_price_row ec_order_total">
		<div class="ec_cart_price_row_label"></div>
		<div class="ec_cart_price_row_total"><a href="<?php echo esc_attr( $this->cart_page ); ?>"><?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_edit_cart_link' ); ?></a></div>
	</div>

	<?php if( get_option( 'ec_option_user_order_notes' ) && $GLOBALS['ec_cart_data']->cart_data->order_notes != "" && strlen( $GLOBALS['ec_cart_data']->cart_data->order_notes ) > 0 ){ ?>
	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_order_notes_title' ); ?>
	</div>
	<div class="ec_cart_input_row">
		<?php echo nl2br( esc_textarea( $GLOBALS['ec_cart_data']->cart_data->order_notes ) ); ?>
	</div>
	<?php }?>

	<?php do_action( 'wpeasycart_payment_order_notes_after' ); ?>

	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_review_totals_title' ); ?>
	</div>
	<?php $this->load_cart_total_lines(); ?>

	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_submit_order_button' )?>
	</div>

	<div class="ec_cart_error" id="ec_stripe_dynamic_error" style="display:none;">
		<div>
			<?php echo wp_easycart_language( )->get_text( "ec_errors", "payment_failed" ); ?>
		</div>
	</div>

	<div class="ec_cart_error_row" id="ec_terms_error">
		<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_payment_accept_terms' )?> 
	</div>
	<div class="ec_cart_input_row" id="ec_terms_row"<?php if( get_option( 'ec_option_payment_third_party' ) == "paypal" && $this->get_selected_payment_method( ) == "third_party" && get_option( 'ec_option_paypal_enable_pay_now' ) == '1' && $this->order_totals->grand_total > 0 ){ ?> style="display:none;"<?php }?>>
		<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_checkout_text' )?>
	</div>

	<?php if( get_option( 'ec_option_require_terms_agreement' ) ){ ?>
	<div class="ec_cart_input_row ec_agreement_section" id="ec_terms_agreement_row"<?php if( get_option( 'ec_option_payment_third_party' ) == "paypal" && $this->get_selected_payment_method( ) == "third_party" && get_option( 'ec_option_paypal_enable_pay_now' ) == '1' && $this->order_totals->grand_total > 0 ){ ?> style="display:none;"<?php }?>>
		<input type="checkbox" name="ec_terms_agree" id="ec_terms_agree" value="1"  /> <?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_review_agree' )?>
	</div>
	<?php }else{ ?>
		<input type="hidden" name="ec_terms_agree" id="ec_terms_agree" value="2"  />
	<?php }?>

	<?php if( get_option( 'ec_option_show_subscriber_feature' ) && ( !$GLOBALS['ec_user']->user_id || !$GLOBALS['ec_user']->is_subscriber ) ){ ?>
	<div class="ec_cart_input_row ec_agreement_section"<?php if( get_option( 'ec_option_require_terms_agreement' ) ){ ?> style="margin-top:-10px;"<?php }?>>
		<input type="checkbox" name="ec_cart_is_subscriber" id="ec_cart_is_subscriber" class="ec_account_register_input_field" value="1" />
		<?php echo wp_easycart_language( )->get_text( 'account_register', 'account_register_subscribe' )?>
	</div>
	<?php }?>

	<div class="ec_cart_error_row" id="ec_submit_order_error">
		<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_payment_correct_errors' )?> 
	</div>

	<?php if( get_option( 'ec_option_payment_third_party' ) == "paypal" && get_option( 'ec_option_paypal_enable_pay_now' ) == '1' && $this->order_totals->grand_total > 0 ){ ?>
		<div style="float:left; width:100%; margin:10px 0 0;<?php if( $this->get_selected_payment_method( ) != "third_party" ){ ?> display:none;<?php }?>" id="wpeasycart_submit_paypal_order_row">
			<div id="paypal-button-container" style="width:100%; max-width:350px; margin:0;"></div>
		</div>
		<div id="paypal-success-cover" style="display:none; cursor:default; position:fixed; top:0; left:0; width:100%; height:100%; z-index:999999; background-color: rgba(0, 0, 0, 0.8); color:#FFF;">
			<style>
			@keyframes rotation{
				0%  { transform:rotate(0deg); }
				100%{ transform:rotate(359deg); }
			}
			</style>
			<div style='font-family: "HelveticaNeue", "HelveticaNeue-Light", "Helvetica Neue Light", helvetica, arial, sans-serif; font-size: 14px; text-align: center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; width: 350px; top: 50%; left: 50%; position: absolute; margin-left: -165px; margin-top: -80px; cursor: pointer; text-align: center;'>
				<div class="paypal-checkout-logo">
					<img class="paypal-checkout-logo-pp" alt="pp" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAyNCAzMiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBwcmVzZXJ2ZUFzcGVjdFJhdGlvPSJ4TWluWU1pbiBtZWV0Ij4KICAgIDxwYXRoIGZpbGw9IiNmZmZmZmYiIG9wYWNpdHk9IjAuNyIgZD0iTSAyMC43MDIgOS40NDYgQyAyMC45ODIgNy4zNDcgMjAuNzAyIDUuOTQ3IDE5LjU3OCA0LjU0OCBDIDE4LjM2MSAzLjE0OCAxNi4yMDggMi41NDggMTMuNDkzIDIuNTQ4IEwgNS41MzYgMi41NDggQyA0Ljk3NCAyLjU0OCA0LjUwNiAyLjk0OCA0LjQxMiAzLjU0OCBMIDEuMTM2IDI1Ljc0IEMgMS4wNDIgMjYuMjM5IDEuMzIzIDI2LjYzOSAxLjc5MSAyNi42MzkgTCA2Ljc1MyAyNi42MzkgTCA2LjM3OCAyOC45MzggQyA2LjI4NSAyOS4yMzggNi42NTkgMjkuNjM4IDYuOTQgMjkuNjM4IEwgMTEuMTUzIDI5LjYzOCBDIDExLjYyMSAyOS42MzggMTEuOTk1IDI5LjIzOCAxMi4wODkgMjguNzM5IEwgMTIuMTgyIDI4LjUzOSBMIDEyLjkzMSAyMy4zNDEgTCAxMy4wMjUgMjMuMDQxIEMgMTMuMTE5IDIyLjQ0MSAxMy40OTMgMjIuMTQxIDEzLjk2MSAyMi4xNDEgTCAxNC42MTYgMjIuMTQxIEMgMTguNjQyIDIyLjE0MSAyMS43MzEgMjAuMzQyIDIyLjY2OCAxNS40NDMgQyAyMy4wNDIgMTMuMzQ0IDIyLjg1NSAxMS41NDUgMjEuODI1IDEwLjM0NSBDIDIxLjQ1MSAxMC4wNDYgMjEuMDc2IDkuNjQ2IDIwLjcwMiA5LjQ0NiBMIDIwLjcwMiA5LjQ0NiI+PC9wYXRoPgogICAgPHBhdGggZmlsbD0iI2ZmZmZmZiIgb3BhY2l0eT0iMC43IiBkPSJNIDIwLjcwMiA5LjQ0NiBDIDIwLjk4MiA3LjM0NyAyMC43MDIgNS45NDcgMTkuNTc4IDQuNTQ4IEMgMTguMzYxIDMuMTQ4IDE2LjIwOCAyLjU0OCAxMy40OTMgMi41NDggTCA1LjUzNiAyLjU0OCBDIDQuOTc0IDIuNTQ4IDQuNTA2IDIuOTQ4IDQuNDEyIDMuNTQ4IEwgMS4xMzYgMjUuNzQgQyAxLjA0MiAyNi4yMzkgMS4zMjMgMjYuNjM5IDEuNzkxIDI2LjYzOSBMIDYuNzUzIDI2LjYzOSBMIDcuOTcgMTguMzQyIEwgNy44NzYgMTguNjQyIEMgOC4wNjMgMTguMDQzIDguNDM4IDE3LjY0MyA5LjA5MyAxNy42NDMgTCAxMS40MzMgMTcuNjQzIEMgMTYuMDIxIDE3LjY0MyAxOS41NzggMTUuNjQzIDIwLjYwOCA5Ljk0NiBDIDIwLjYwOCA5Ljc0NiAyMC42MDggOS41NDYgMjAuNzAyIDkuNDQ2Ij48L3BhdGg+CiAgICA8cGF0aCBmaWxsPSIjZmZmZmZmIiBkPSJNIDkuMjggOS40NDYgQyA5LjI4IDkuMTQ2IDkuNDY4IDguODQ2IDkuODQyIDguNjQ2IEMgOS45MzYgOC42NDYgMTAuMTIzIDguNTQ2IDEwLjIxNiA4LjU0NiBMIDE2LjQ4OSA4LjU0NiBDIDE3LjIzOCA4LjU0NiAxNy44OTMgOC42NDYgMTguNTQ4IDguNzQ2IEMgMTguNzM2IDguNzQ2IDE4LjgyOSA4Ljc0NiAxOS4xMSA4Ljg0NiBDIDE5LjIwNCA4Ljk0NiAxOS4zOTEgOC45NDYgMTkuNTc4IDkuMDQ2IEMgMTkuNjcyIDkuMDQ2IDE5LjY3MiA5LjA0NiAxOS44NTkgOS4xNDYgQyAyMC4xNCA5LjI0NiAyMC40MjEgOS4zNDYgMjAuNzAyIDkuNDQ2IEMgMjAuOTgyIDcuMzQ3IDIwLjcwMiA1Ljk0NyAxOS41NzggNC42NDggQyAxOC4zNjEgMy4yNDggMTYuMjA4IDIuNTQ4IDEzLjQ5MyAyLjU0OCBMIDUuNTM2IDIuNTQ4IEMgNC45NzQgMi41NDggNC41MDYgMy4wNDggNC40MTIgMy41NDggTCAxLjEzNiAyNS43NCBDIDEuMDQyIDI2LjIzOSAxLjMyMyAyNi42MzkgMS43OTEgMjYuNjM5IEwgNi43NTMgMjYuNjM5IEwgNy45NyAxOC4zNDIgTCA5LjI4IDkuNDQ2IFoiPjwvcGF0aD4KICAgIDxnIHRyYW5zZm9ybT0ibWF0cml4KDAuNDk3NzM3LCAwLCAwLCAwLjUyNjEyLCAxLjEwMTQ0LCAwLjYzODY1NCkiIG9wYWNpdHk9IjAuMiI+CiAgICAgICAgPHBhdGggZmlsbD0iIzIzMWYyMCIgZD0iTTM5LjMgMTYuN2MwLjkgMC41IDEuNyAxLjEgMi4zIDEuOCAxIDEuMSAxLjYgMi41IDEuOSA0LjEgMC4zLTMuMi0wLjItNS44LTEuOS03LjgtMC42LTAuNy0xLjMtMS4yLTIuMS0xLjdDMzkuNSAxNC4yIDM5LjUgMTUuNCAzOS4zIDE2Ljd6Ij48L3BhdGg+CiAgICAgICAgPHBhdGggZmlsbD0iIzIzMWYyMCIgZD0iTTAuNCA0NS4yTDYuNyA1LjZDNi44IDQuNSA3LjggMy43IDguOSAzLjdoMTZjNS41IDAgOS44IDEuMiAxMi4yIDMuOSAxLjIgMS40IDEuOSAzIDIuMiA0LjggMC40LTMuNi0wLjItNi4xLTIuMi04LjRDMzQuNyAxLjIgMzAuNCAwIDI0LjkgMEg4LjljLTEuMSAwLTIuMSAwLjgtMi4zIDEuOUwwIDQ0LjFDMCA0NC41IDAuMSA0NC45IDAuNCA0NS4yeiI+PC9wYXRoPgogICAgICAgIDxwYXRoIGZpbGw9IiMyMzFmMjAiIGQ9Ik0xMC43IDQ5LjRsLTAuMSAwLjZjLTAuMSAwLjQgMC4xIDAuOCAwLjQgMS4xbDAuMy0xLjdIMTAuN3oiPjwvcGF0aD4KICAgIDwvZz4KPC9zdmc+Cg=="><img class="paypal-checkout-logo-paypal" alt="paypal" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjMyIiB2aWV3Qm94PSIwIDAgMTAwIDMyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaW5ZTWluIG1lZXQiPgogICAgPHBhdGggZmlsbD0iI2ZmZmZmZiIgZD0iTSAxMiA1LjMxNSBMIDQuMiA1LjMxNSBDIDMuNyA1LjMxNSAzLjIgNS43MTUgMy4xIDYuMjE1IEwgMCAyNi4yMTUgQyAtMC4xIDI2LjYxNSAwLjIgMjYuOTE1IDAuNiAyNi45MTUgTCA0LjMgMjYuOTE1IEMgNC44IDI2LjkxNSA1LjMgMjYuNTE1IDUuNCAyNi4wMTUgTCA2LjIgMjAuNjE1IEMgNi4zIDIwLjExNSA2LjcgMTkuNzE1IDcuMyAxOS43MTUgTCA5LjggMTkuNzE1IEMgMTQuOSAxOS43MTUgMTcuOSAxNy4yMTUgMTguNyAxMi4zMTUgQyAxOSAxMC4yMTUgMTguNyA4LjUxNSAxNy43IDcuMzE1IEMgMTYuNiA2LjAxNSAxNC42IDUuMzE1IDEyIDUuMzE1IFogTSAxMi45IDEyLjYxNSBDIDEyLjUgMTUuNDE1IDEwLjMgMTUuNDE1IDguMyAxNS40MTUgTCA3LjEgMTUuNDE1IEwgNy45IDEwLjIxNSBDIDcuOSA5LjkxNSA4LjIgOS43MTUgOC41IDkuNzE1IEwgOSA5LjcxNSBDIDEwLjQgOS43MTUgMTEuNyA5LjcxNSAxMi40IDEwLjUxNSBDIDEyLjkgMTAuOTE1IDEzLjEgMTEuNjE1IDEyLjkgMTIuNjE1IFoiPjwvcGF0aD4KICAgIDxwYXRoIGZpbGw9IiNmZmZmZmYiIGQ9Ik0gMzUuMiAxMi41MTUgTCAzMS41IDEyLjUxNSBDIDMxLjIgMTIuNTE1IDMwLjkgMTIuNzE1IDMwLjkgMTMuMDE1IEwgMzAuNyAxNC4wMTUgTCAzMC40IDEzLjYxNSBDIDI5LjYgMTIuNDE1IDI3LjggMTIuMDE1IDI2IDEyLjAxNSBDIDIxLjkgMTIuMDE1IDE4LjQgMTUuMTE1IDE3LjcgMTkuNTE1IEMgMTcuMyAyMS43MTUgMTcuOCAyMy44MTUgMTkuMSAyNS4yMTUgQyAyMC4yIDI2LjUxNSAyMS45IDI3LjExNSAyMy44IDI3LjExNSBDIDI3LjEgMjcuMTE1IDI5IDI1LjAxNSAyOSAyNS4wMTUgTCAyOC44IDI2LjAxNSBDIDI4LjcgMjYuNDE1IDI5IDI2LjgxNSAyOS40IDI2LjgxNSBMIDMyLjggMjYuODE1IEMgMzMuMyAyNi44MTUgMzMuOCAyNi40MTUgMzMuOSAyNS45MTUgTCAzNS45IDEzLjExNSBDIDM2IDEyLjkxNSAzNS42IDEyLjUxNSAzNS4yIDEyLjUxNSBaIE0gMzAuMSAxOS44MTUgQyAyOS43IDIxLjkxNSAyOC4xIDIzLjQxNSAyNS45IDIzLjQxNSBDIDI0LjggMjMuNDE1IDI0IDIzLjExNSAyMy40IDIyLjQxNSBDIDIyLjggMjEuNzE1IDIyLjYgMjAuODE1IDIyLjggMTkuODE1IEMgMjMuMSAxNy43MTUgMjQuOSAxNi4yMTUgMjcgMTYuMjE1IEMgMjguMSAxNi4yMTUgMjguOSAxNi42MTUgMjkuNSAxNy4yMTUgQyAzMCAxNy44MTUgMzAuMiAxOC43MTUgMzAuMSAxOS44MTUgWiI+PC9wYXRoPgogICAgPHBhdGggZmlsbD0iI2ZmZmZmZiIgZD0iTSA1NS4xIDEyLjUxNSBMIDUxLjQgMTIuNTE1IEMgNTEgMTIuNTE1IDUwLjcgMTIuNzE1IDUwLjUgMTMuMDE1IEwgNDUuMyAyMC42MTUgTCA0My4xIDEzLjMxNSBDIDQzIDEyLjgxNSA0Mi41IDEyLjUxNSA0Mi4xIDEyLjUxNSBMIDM4LjQgMTIuNTE1IEMgMzggMTIuNTE1IDM3LjYgMTIuOTE1IDM3LjggMTMuNDE1IEwgNDEuOSAyNS41MTUgTCAzOCAzMC45MTUgQyAzNy43IDMxLjMxNSAzOCAzMS45MTUgMzguNSAzMS45MTUgTCA0Mi4yIDMxLjkxNSBDIDQyLjYgMzEuOTE1IDQyLjkgMzEuNzE1IDQzLjEgMzEuNDE1IEwgNTUuNiAxMy40MTUgQyA1NS45IDEzLjExNSA1NS42IDEyLjUxNSA1NS4xIDEyLjUxNSBaIj48L3BhdGg+CiAgICA8cGF0aCBmaWxsPSIjZmZmZmZmIiBkPSJNIDY3LjUgNS4zMTUgTCA1OS43IDUuMzE1IEMgNTkuMiA1LjMxNSA1OC43IDUuNzE1IDU4LjYgNi4yMTUgTCA1NS41IDI2LjExNSBDIDU1LjQgMjYuNTE1IDU1LjcgMjYuODE1IDU2LjEgMjYuODE1IEwgNjAuMSAyNi44MTUgQyA2MC41IDI2LjgxNSA2MC44IDI2LjUxNSA2MC44IDI2LjIxNSBMIDYxLjcgMjAuNTE1IEMgNjEuOCAyMC4wMTUgNjIuMiAxOS42MTUgNjIuOCAxOS42MTUgTCA2NS4zIDE5LjYxNSBDIDcwLjQgMTkuNjE1IDczLjQgMTcuMTE1IDc0LjIgMTIuMjE1IEMgNzQuNSAxMC4xMTUgNzQuMiA4LjQxNSA3My4yIDcuMjE1IEMgNzIgNi4wMTUgNzAuMSA1LjMxNSA2Ny41IDUuMzE1IFogTSA2OC40IDEyLjYxNSBDIDY4IDE1LjQxNSA2NS44IDE1LjQxNSA2My44IDE1LjQxNSBMIDYyLjYgMTUuNDE1IEwgNjMuNCAxMC4yMTUgQyA2My40IDkuOTE1IDYzLjcgOS43MTUgNjQgOS43MTUgTCA2NC41IDkuNzE1IEMgNjUuOSA5LjcxNSA2Ny4yIDkuNzE1IDY3LjkgMTAuNTE1IEMgNjguNCAxMC45MTUgNjguNSAxMS42MTUgNjguNCAxMi42MTUgWiI+PC9wYXRoPgogICAgPHBhdGggZmlsbD0iI2ZmZmZmZiIgZD0iTSA5MC43IDEyLjUxNSBMIDg3IDEyLjUxNSBDIDg2LjcgMTIuNTE1IDg2LjQgMTIuNzE1IDg2LjQgMTMuMDE1IEwgODYuMiAxNC4wMTUgTCA4NS45IDEzLjYxNSBDIDg1LjEgMTIuNDE1IDgzLjMgMTIuMDE1IDgxLjUgMTIuMDE1IEMgNzcuNCAxMi4wMTUgNzMuOSAxNS4xMTUgNzMuMiAxOS41MTUgQyA3Mi44IDIxLjcxNSA3My4zIDIzLjgxNSA3NC42IDI1LjIxNSBDIDc1LjcgMjYuNTE1IDc3LjQgMjcuMTE1IDc5LjMgMjcuMTE1IEMgODIuNiAyNy4xMTUgODQuNSAyNS4wMTUgODQuNSAyNS4wMTUgTCA4NC4zIDI2LjAxNSBDIDg0LjIgMjYuNDE1IDg0LjUgMjYuODE1IDg0LjkgMjYuODE1IEwgODguMyAyNi44MTUgQyA4OC44IDI2LjgxNSA4OS4zIDI2LjQxNSA4OS40IDI1LjkxNSBMIDkxLjQgMTMuMTE1IEMgOTEuNCAxMi45MTUgOTEuMSAxMi41MTUgOTAuNyAxMi41MTUgWiBNIDg1LjUgMTkuODE1IEMgODUuMSAyMS45MTUgODMuNSAyMy40MTUgODEuMyAyMy40MTUgQyA4MC4yIDIzLjQxNSA3OS40IDIzLjExNSA3OC44IDIyLjQxNSBDIDc4LjIgMjEuNzE1IDc4IDIwLjgxNSA3OC4yIDE5LjgxNSBDIDc4LjUgMTcuNzE1IDgwLjMgMTYuMjE1IDgyLjQgMTYuMjE1IEMgODMuNSAxNi4yMTUgODQuMyAxNi42MTUgODQuOSAxNy4yMTUgQyA4NS41IDE3LjgxNSA4NS43IDE4LjcxNSA4NS41IDE5LjgxNSBaIj48L3BhdGg+CiAgICA8cGF0aCBmaWxsPSIjZmZmZmZmIiBkPSJNIDk1LjEgNS45MTUgTCA5MS45IDI2LjIxNSBDIDkxLjggMjYuNjE1IDkyLjEgMjYuOTE1IDkyLjUgMjYuOTE1IEwgOTUuNyAyNi45MTUgQyA5Ni4yIDI2LjkxNSA5Ni43IDI2LjUxNSA5Ni44IDI2LjAxNSBMIDEwMCA2LjExNSBDIDEwMC4xIDUuNzE1IDk5LjggNS40MTUgOTkuNCA1LjQxNSBMIDk1LjggNS40MTUgQyA5NS40IDUuMzE1IDk1LjIgNS41MTUgOTUuMSA1LjkxNSBaIj48L3BhdGg+Cjwvc3ZnPgo=">
				</div>
				<div class="paypal-checkout-loader">
					<div style="height: 30px; width: 30px; display: inline-block; box-sizing: content-box; opacity: 1; filter: alpha(opacity=100); -webkit-animation: rotation .7s infinite linear; -moz-animation: rotation .7s infinite linear; -o-animation: rotation .7s infinite linear; animation: rotation .7s infinite linear; border-left: 8px solid rgba(0, 0, 0, .2); border-right: 8px solid rgba(0, 0, 0, .2); border-bottom: 8px solid rgba(0, 0, 0, .2); border-top: 8px solid #fff; border-radius: 100%;"></div>
				</div>
			</div>
		</div>
		<?php if ( apply_filters( 'wp_easycart_allow_paypal_express', false ) ) {
			$this->print_paypal_express_button_code( true );
		} else {
			$this->print_paypal_express_button_code_order( true );
		} ?>
	<?php }?>

	<div class="ec_cart_button_row" id="wpeasycart_submit_order_row"<?php if( get_option( 'ec_option_payment_third_party' ) == "paypal" && $this->get_selected_payment_method( ) == "third_party" && get_option( 'ec_option_paypal_enable_pay_now' ) == '1' && $this->order_totals->grand_total > 0 ){ ?> style="display:none;"<?php }?>>
		<input type="submit" value="<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_submit_order_button' )?>" class="ec_cart_button" id="ec_cart_submit_order" onclick="return ec_validate_submit_order( );" />
		<input type="submit" value="<?php echo esc_attr( strtoupper( wp_easycart_language( )->get_text( 'cart', 'cart_please_wait' ) ) ); ?>" class="ec_cart_button_working" id="ec_cart_submit_order_working" onclick="return false;" />
	</div>
</div>

<?php $this->display_page_three_form_end( ); ?>

<div class="ec_cart_right" id="ec_cart_payment_hide_column">

	<div class="ec_cart_header ec_top">
		<?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_title' ); ?>
	</div>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_first_name, ENT_QUOTES ) . ' ' . htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_last_name, ENT_QUOTES ) ); ?>
	</div>

	<?php if( strlen( $GLOBALS['ec_cart_data']->cart_data->billing_company_name ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_company_name, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1, ENT_QUOTES ) ); ?>
	</div>

	<?php if( strlen( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_city, ENT_QUOTES ) ); ?>, <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_state, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_zip, ENT_QUOTES ) ); ?>
	</div>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_country_name, ENT_QUOTES ) ); ?>
	</div>

	<?php if( strlen( $GLOBALS['ec_cart_data']->cart_data->billing_phone ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_phone, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>

	<?php if( strlen( $GLOBALS['ec_cart_data']->cart_data->vat_registration_number ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<strong><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_vat_registration_number' ); ?>:</strong> <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->vat_registration_number, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>

	<div class="ec_cart_input_row">
		<a href="<?php echo esc_attr( $this->cart_page . $this->permalink_divider ); ?>ec_page=checkout_info" class="wpeasycart_edit_billing_address_link"><?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_edit_billing_link' ); ?></a>
	</div>

	<?php if( get_option( 'ec_option_use_shipping' ) && ( $this->cart->shippable_total_items > 0 || $this->order_totals->handling_total > 0 || $this->cart->excluded_shippable_total_items > 0 ) ){ ?>
	<div class="ec_cart_header ec_top">
		<?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_title' ); ?>
	</div>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_first_name, ENT_QUOTES ) . ' ' . htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_last_name, ENT_QUOTES ) ); ?>
	</div>

	<?php if( strlen( $GLOBALS['ec_cart_data']->cart_data->shipping_company_name ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_company_name, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1, ENT_QUOTES ) ); ?>
	</div>

	<?php if( strlen( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_city, ENT_QUOTES ) ); ?>, <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_state, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_zip, ENT_QUOTES ) ); ?>
	</div>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_country_name, ENT_QUOTES ) ); ?>
	</div>

	<?php if( strlen( $GLOBALS['ec_cart_data']->cart_data->shipping_phone ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_phone, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>

	<?php $this->display_page_two_form_start( ); ?>
	<div class="ec_cart_input_row">
		<a href="<?php echo esc_attr( $this->cart_page . $this->permalink_divider ); ?>ec_page=checkout_info" class="wpeasycart_edit_shipping_address_link"><?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_edit_shipping_link' ); ?></a>
	</div>
	<?php }?>
	
	<?php do_action( 'wp_easycart_cart_payment_after_edit_shipping_link', $this ); ?>

	<?php if( get_option( 'ec_option_use_shipping' ) && ( $this->cart->shippable_total_items > 0 || $this->order_totals->handling_total > 0 ) ){ ?>
	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_shipping_method', 'cart_shipping_method_title' ); ?>
	</div>
	<div class="ec_cart_input_row">
		<?php $this->display_selected_shipping_method( ); ?>
		<a href="<?php echo esc_attr( $this->cart_page . $this->permalink_divider ); ?>ec_page=checkout_shipping" class="wpeasycart_edit_shipping_method_link"><?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_edit_shipping_method_link' ); ?></a>
	</div>
	<?php } // Close if for shipping ?>

</div>
<?php if( $this->use_payment_gateway() && 'square' != get_option( 'ec_option_payment_process_method' ) && get_option( 'ec_option_cache_prevent' ) ) { ?>
<script type="text/javascript">
if( jQuery( document.getElementById( 'ec_card_number' ) ).length ){
	jQuery( document.getElementById( 'ec_card_number' ) ).payment( 'formatCardNumber' );
}
if( jQuery( document.getElementById( 'ec_cc_expiration' ) ).length ){
	jQuery( document.getElementById( 'ec_cc_expiration' ) ).payment('formatCardExpiry');
}
if( jQuery( document.getElementById( 'ec_security_code' ) ).length ){
	jQuery( document.getElementById( 'ec_security_code' ) ).payment('formatCardCVC');
}
</script>
<?php }?>
<?php do_action( 'wp_easycart_cart_payment_end', $this ); ?>

<div style="clear:both;"></div>
<div id="ec_current_media_size"></div>