<?php 
$paypal_currency = get_option( 'ec_option_paypal_currency_code' );
if( get_option( 'ec_option_paypal_use_selected_currency' ) ){
	if( isset( $_COOKIE['ec_convert_to'] ) ){
		$paypal_currency = substr( preg_replace( '/[^A-Z]/', '', strtoupper( sanitize_text_field( $_COOKIE['ec_convert_to'] ) ) ), 0, 3 );
	}
}
$tax_total = number_format( $this->order_totals->tax_total + $this->order_totals->duty_total + $this->order_totals->gst_total + $this->order_totals->pst_total + $this->order_totals->hst_total, 2 );
if( !$this->tax->vat_included )
	$tax_total = number_format( $tax_total + $this->order_totals->vat_total, 2 );
	
$fee_rate = apply_filters( 'wp_easycart_stripe_connect_fee_rate', 2 );

$extra_funding_options = array( );
$disallowed_funding_options = array( );
if( get_option( 'ec_option_paypal_use_venmo' ) == '1' ){
    $extra_funding_options[] = 'paypal.FUNDING.VENMO';
}
if( get_option( 'ec_option_paypal_enable_credit' ) == '1' ){ 
    $extra_funding_options[] = 'paypal.FUNDING.CREDIT'; 
}
if( $is_payment_page && get_option( 'ec_option_paypal_enable_credit' ) == '0' ){ 
    $disallowed_funding_options[] = 'paypal.FUNDING.CARD'; 
    $disallowed_funding_options[] = 'paypal.FUNDING.CREDIT'; 
}else if( get_option( 'ec_option_paypal_enable_credit' ) == '0' ){
    $disallowed_funding_options[] = 'paypal.FUNDING.CREDIT'; 
}
?>
<script>
	jQuery( document.getElementById( 'paypal-success-cover' ) ).appendTo( document.body );
	function wpeasycart_paypal_render_button( ){
		paypal.Button.render({
			env: '<?php if( get_option( 'ec_option_paypal_use_sandbox' ) == '1' ){ echo "sandbox"; }else{ echo "production"; } ?>',
			commit: true,
			style: {
				size:  'responsive', // small | medium | large | responsive
				color: '<?php echo esc_attr( get_option( 'ec_option_paypal_button_color' ) ); ?>', // gold | blue | silver | black
				shape: '<?php echo esc_attr( get_option( 'ec_option_paypal_button_shape' ) ); ?>',  // pill | rect
				tagline: false,
				layout: <?php if( $is_payment_page || $is_horizontal ){ echo "'horizontal'"; }else{ ?>'vertical'<?php }?>
			},
			funding: {
                allowed: [ <?php echo esc_attr( implode( ',', $extra_funding_options ) ); ?> ],
				disallowed: [ <?php echo esc_attr( implode( ',', $disallowed_funding_options ) ); ?> ]
			},
			client: {
				<?php if( get_option( 'ec_option_paypal_use_sandbox' ) == '1' ){ ?>sandbox: '<?php if( get_option( 'ec_option_paypal_sandbox_merchant_id' ) != '' ){ 
					// APP ID NOT PUBLIC OR SECRET KEY! THIS TELLS PAYPAL THE PARTER THE MERCHANT IS PROCESSING WITH. MERCHANT DESCRIBED BELOW, WHICH IS SPECIFIC TO THE MERCHANT. THEY HAVE CONNECTED WITH THE WP EasyCart PAYPAL APP. CANNOT USE ONE WITHOUT THE OTHER. THIS WAS CREATED WITH PAYPAL IN ORDER TO ALLOW FOR QUICK ONBOARDING, WITHOUT PROGRAMMING EXPERIENCE AND PAYPAL
                    // For more information: https://developer.paypal.com/docs/platforms/seller-onboarding/
                    echo 'Acet2ZT0h9IALSY-n76aGnnjCYp3E3myqcmrJ7tfqJiLUvLzXKQMabHN9uLr2W_N03txVHuvkpsQDwhw';
				}else{
					// THIS IS FOR THOSE THAT TAKE THE TIME TO CREATE THEIR OWN PAYPAL APP, NOT THE PUBLIC WP EASYCART APP
					echo esc_attr( get_option( 'ec_option_paypal_sandbox_app_id' ) ); 
				} ?>'<?php }?>
				<?php if( get_option( 'ec_option_paypal_use_sandbox' ) == '0' ){ ?>production: '<?php if( get_option( 'ec_option_paypal_production_merchant_id' ) != '' ){ 
					// APP ID NOT PUBLIC OR SECRET KEY! THIS TELLS PAYPAL THE PARTER THE MERCHANT IS PROCESSING WITH. MERCHANT DESCRIBED BELOW, WHICH IS SPECIFIC TO THE MERCHANT. THEY HAVE CONNECTED WITH THE WP EasyCart PAYPAL APP. CANNOT USE ONE WITHOUT THE OTHER. THIS WAS CREATED WITH PAYPAL IN ORDER TO ALLOW FOR QUICK ONBOARDING, WITHOUT PROGRAMMING EXPERIENCE AND PAYPAL
                    // For more information: https://developer.paypal.com/docs/platforms/seller-onboarding/
                    echo 'AXLwqGbEI4j2xLhSOPgUhJYNQkkooPmPUWH9NDIVUZ7PxY6yKPYGrBCELYlSdTSepUaVb_r_M0IdPSJa';
				}else{
					// THIS IS FOR THOSE THAT TAKE THE TIME TO CREATE THEIR OWN PAYPAL APP, NOT THE PUBLIC WP EASYCART APP
					echo esc_attr( get_option( 'ec_option_paypal_production_app_id' ) ); 
				} ?>'<?php }?>
			},
			payment: function(data, actions) {
				var CREATE_URL = wpeasycart_ajax_object.ajax_url;
				var data = {
					action: 'wp_easycart_ajax_init_paypal_express'
				};
				return paypal.request.post( CREATE_URL, data ).then( function(res){
					return res;
				} );
				var paymentPromise = new paypal.Promise( function( resolve, reject ){
					var data = {
						action: 'wp_easycart_ajax_init_paypal_express'
					};
					jQuery.ajax( { 
						url: wpeasycart_ajax_object.ajax_url, 
						type: 'post', 
						data: data, 
						success: function( data ){ 
							resolve( data );
						}
					} );
				} );
				paymentPromise.catch( function( err ){
					alert( '<?php echo str_replace( "'", "\'", wp_easycart_language( )->get_text( "ec_errors", "payment_failed" ) ); ?>' );
					console.log( err );
				});
				return paymentPromise;
			},
			onAuthorize: function( data, actions ){
				jQuery( document.getElementById( 'paypal-success-cover' ) ).delay( 600 ).fadeIn( 'slow' );
				window.location = '<?php echo esc_url_raw( $this->cart_page . $this->permalink_divider . "ec_page=checkout_paypal_authorized" ); ?>' + '&orderID=' + data.orderID + '&payerID=' + data.payerID + '&paymentID=' + data.paymentID + '&paymentToken=' + data.paymentToken;
			},
			onError: function(data, actions) {
				console.debug(data);
			},
		}, '#paypal-button-container');
	}
	jQuery(document).ready(function( $ ){
		setTimeout( wpeasycart_paypal_render_button, 1 ); // Delay load for mmenu sites
    });
</script>