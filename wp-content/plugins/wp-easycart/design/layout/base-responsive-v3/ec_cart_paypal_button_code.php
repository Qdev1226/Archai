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
			commit: false,
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
				return actions.payment.create({
					payment: {
						transactions: [
							{<?php if( get_option( 'ec_option_paypal_use_sandbox' ) == '1' && get_option( 'ec_option_paypal_sandbox_merchant_id' ) != '' ){?>
								payee: { 
									merchant_id: '<?php echo esc_attr( get_option( 'ec_option_paypal_sandbox_merchant_id' ) ); ?>'
								},<?php }else if( get_option( 'ec_option_paypal_use_sandbox' ) == '0' && get_option( 'ec_option_paypal_production_merchant_id' ) != '' ){?>
								payee: { 
									merchant_id: '<?php echo esc_attr( get_option( 'ec_option_paypal_production_merchant_id' ) ); ?>'
								},<?php }?>
								amount: { 
									total: '<?php echo esc_attr( number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $this->order_totals->get_converted_grand_total( ) : $this->order_totals->grand_total, 2, '.', '' ) ); ?>', 
									currency: '<?php echo esc_attr( strtoupper( $paypal_currency ) ); ?>',
									details: {
										subtotal: '<?php echo esc_attr( number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $this->order_totals->get_converted_sub_total( ) - $GLOBALS['currency']->convert_price( $this->order_totals->discount_total ) + $GLOBALS['currency']->convert_price( $this->order_totals->fee_total ) + $GLOBALS['currency']->convert_price( $this->order_totals->tip_total ) : $this->order_totals->sub_total - $this->order_totals->discount_total + $this->order_totals->fee_total + $this->order_totals->tip_total, 2, '.', '' ) ); ?>',
										tax: '<?php echo esc_attr( number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $GLOBALS['currency']->convert_price( $tax_total ) : $tax_total, 2, '.', '' ) ); ?>',
										shipping: '<?php echo esc_attr( number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $GLOBALS['currency']->convert_price( $this->order_totals->shipping_total ) : $this->order_totals->shipping_total, 2, '.', '' ) ); ?>',
									}
								},
								item_list: {
									items: [<?php foreach( $this->cart->cart as $cart_item ){ ?>
										{
											name: '<?php echo esc_attr( htmlspecialchars( preg_replace( "/[^A-Za-z0-9 \,\:]/", '', str_replace( "\r\n", ", ", $cart_item->title ) ), ENT_QUOTES ) ); ?>',
											quantity: '<?php echo esc_attr( $cart_item->quantity ); ?>',
											price: '<?php echo esc_attr( number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $GLOBALS['currency']->convert_price( $cart_item->unit_price ) : $cart_item->unit_price, 2, '.', '' ) ); ?>',
											sku: '<?php echo esc_attr( htmlspecialchars( $cart_item->model_number, ENT_QUOTES ) ); ?>',<?php
											$description = '';
											if( $cart_item->optionitem1_name ){ 
												$description .= $cart_item->optionitem1_name;
												if( $cart_item->optionitem1_price > 0 ){ 
													$description .= '( +' . $GLOBALS['currency']->get_currency_display( $cart_item->optionitem1_price ) . ' )';
												}else if( $cart_item->optionitem1_price < 0 ){
													$description .= '( ' . $GLOBALS['currency']->get_currency_display( $cart_item->optionitem1_price ) . ' )';
												}
											}
											
											if( $cart_item->optionitem2_name ){ 
												$description .= ', ' . $cart_item->optionitem2_name;
												if( $cart_item->optionitem2_price > 0 ){ 
													$description .= '( +' . $GLOBALS['currency']->get_currency_display( $cart_item->optionitem2_price ) . ' )';
												}else if( $cart_item->optionitem2_price < 0 ){
													$description .= '( ' . $GLOBALS['currency']->get_currency_display( $cart_item->optionitem2_price ) . ' )';
												}
											}
											
											if( $cart_item->optionitem3_name ){ 
												$description .= ', ' . $cart_item->optionitem3_name;
												if( $cart_item->optionitem3_price > 0 ){ 
													$description .= '( +' . $GLOBALS['currency']->get_currency_display( $cart_item->optionitem3_price ) . ' )';
												}else if( $cart_item->optionitem3_price < 0 ){
													$description .= '( ' . $GLOBALS['currency']->get_currency_display( $cart_item->optionitem3_price ) . ' )';
												}
											}
											
											if( $cart_item->optionitem4_name ){ 
												$description .= ', ' . $cart_item->optionitem4_name;
												if( $cart_item->optionitem4_price > 0 ){ 
													$description .= '( +' . $GLOBALS['currency']->get_currency_display( $cart_item->optionitem4_price ) . ' )';
												}else if( $cart_item->optionitem4_price < 0 ){
													$description .= '( ' . $GLOBALS['currency']->get_currency_display( $cart_item->optionitem4_price ) . ' )';
												}
											}
											
											if( $cart_item->optionitem5_name ){ 
												$description .= ', ' . $cart_item->optionitem5_name;
												if( $cart_item->optionitem5_price > 0 ){ 
													$description .= '( +' . $GLOBALS['currency']->get_currency_display( $cart_item->optionitem5_price ) . ' )';
												}else if( $cart_item->optionitem5_price < 0 ){
													$description .= '( ' . $GLOBALS['currency']->get_currency_display( $cart_item->optionitem5_price ) . ' )';
												}
											}
											
											$has_onetime_price_adjustment = false;
											$onetime_price_adjustments = array( );
											
											if( $cart_item->use_advanced_optionset ){
												
												$first = true;
												foreach( $cart_item->advanced_options as $advanced_option_set ){
													
													if( !$first )
														$description .= ', ';
													
													if( $advanced_option_set->option_type == "grid" ){ 
														
														$description .= $advanced_option_set->optionitem_name . ': ' . $advanced_option_set->optionitem_value;
														if ( $advanced_option_set->optionitem_enable_custom_price_label && ( $advanced_option_set->optionitem_price != 0 || ( isset( $advanced_option_set->optionitem_price ) && $advanced_option_set->optionitem_price != 0 ) || ( isset( $advanced_option_set->optionitem_price_onetime ) && $advanced_option_set->optionitem_price_onetime != 0 ) ) ) {
															echo ' ' . esc_attr( wp_easycart_language( )->convert_text( $advanced_option_set->optionitem_custom_price_label ) );
														} else if( $advanced_option_set->optionitem_price > 0 ){ 
															$description .= ' (+' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
														
														}else if( $advanced_option_set->optionitem_price < 0 ){ 
															$description .= ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
														
														}else if( $advanced_option_set->optionitem_price_onetime > 0 ){ 
															$description .= ' (+' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
															$onetime_price_adjustments[] = array(
																'name'		=> $advanced_option_set->optionitem_name . ': ' . $advanced_option_set->optionitem_value . ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')',
																'price' 	=> $advanced_option_set->optionitem_price_onetime
															);
															$has_onetime_price_adjustment = true;
															
														}else if( $advanced_option_set->optionitem_price_onetime < 0 ){ 
															$description .= ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')'; 
															$onetime_price_adjustments[] = array(
																'name'		=> $advanced_option_set->optionitem_name . ': ' . $advanced_option_set->optionitem_value . ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')',
																'price' 	=> $advanced_option_set->optionitem_price_onetime
															);
															$has_onetime_price_adjustment = true;
														
														}else if( $advanced_option_set->optionitem_price_override > -1 ){ 
															$description .= ' (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_override ) . ')'; 
														
														}
												
													}else if( $advanced_option_set->option_type == "dimensions1" || $advanced_option_set->option_type == "dimensions2" ){
														echo esc_attr( $advanced_option_set->option_label ) . ': ';
														$dimensions = json_decode( $advanced_option_set->optionitem_value );
														if( count( $dimensions ) == 2 ){ 
															$description .= $dimensions[0]; 
															if( !get_option( 'ec_option_enable_metric_unit_display' ) ){
																$description .= "\"";
															}
															$description .= " x " . $dimensions[1];
															if( !get_option( 'ec_option_enable_metric_unit_display' ) ){
																$description .= "\"";
															}
														}else if( count( $dimensions ) == 4 ){
															$description .= $dimensions[0] . " " . $dimensions[1] . "\" x " . $dimensions[2] . " " . $dimensions[3] . "\"";
														}
													
													}else{
														$description .= $advanced_option_set->option_label . ': ' . $advanced_option_set->optionitem_value;
														if ( $advanced_option_set->optionitem_enable_custom_price_label && ( $advanced_option_set->optionitem_price != 0 || ( isset( $advanced_option_set->optionitem_price ) && $advanced_option_set->optionitem_price != 0 ) || ( isset( $advanced_option_set->optionitem_price_onetime ) && $advanced_option_set->optionitem_price_onetime != 0 ) ) ) {
															echo ' ' . esc_attr( wp_easycart_language( )->convert_text( $advanced_option_set->optionitem_custom_price_label ) );
														} else if( $advanced_option_set->optionitem_price > 0 ){
															$description .= ' (+' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
														}else if( $advanced_option_set->optionitem_price < 0 ){
															$description .= ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
														}else if( $advanced_option_set->optionitem_price_onetime > 0 ){
															$description .= ' (+' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
															$onetime_price_adjustments[] = array(
																'name'		=> $advanced_option_set->option_label . ': ' . htmlspecialchars( $advanced_option_set->optionitem_value, ENT_QUOTES ) . ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')',
																'price' 	=> $advanced_option_set->optionitem_price_onetime
															);
															$has_onetime_price_adjustment = true;
														}else if( $advanced_option_set->optionitem_price_onetime < 0 ){
															$description .= ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
															$onetime_price_adjustments[] = array(
																'name'		=> $advanced_option_set->option_label . ': ' . htmlspecialchars( $advanced_option_set->optionitem_value, ENT_QUOTES ) . ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')',
																'price' 	=> $advanced_option_set->optionitem_price_onetime
															);
															$has_onetime_price_adjustment = true;
														}else if( $advanced_option_set->optionitem_price_override > -1 ){
															$description .= ' (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_override ) . ')';
														}
													}
													$first = false;
												}
											} ?>
											description: "<?php echo esc_attr( htmlspecialchars( preg_replace( "/[^A-Za-z0-9 \,\:]/", '', str_replace( "\r\n", ", ", $description ) ), ENT_QUOTES ) ); ?>",
											currency: '<?php echo esc_attr( strtoupper( $paypal_currency ) ); ?>',
										},<?php 
											if( $has_onetime_price_adjustment ){
												foreach( $onetime_price_adjustments as $adjustment ){	
										?>
										{
											name: '<?php echo esc_attr( htmlspecialchars( preg_replace( "/[^A-Za-z0-9 \,\:]/", '', str_replace( "\r\n", ", ", $adjustment['name'] ) ), ENT_QUOTES ) ); ?>',
											quantity: '1',
											price: '<?php echo esc_attr( number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $GLOBALS['currency']->convert_price( $adjustment['price'] ) : $adjustment['price'], 2, '.', '' ) ); ?>',
											currency: '<?php echo esc_attr( strtoupper( $paypal_currency ) ); ?>',
										},
										<?php 
												} // close price adjustment loop
											} // close price adjustment if
										} // close cart loop
										?><?php 
										if( $this->order_totals->discount_total > 0 ){ ?>
										{
											name: '<?php echo esc_attr( htmlspecialchars( wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_discounts' ), ENT_QUOTES ) ); ?>',
											quantity: '1',
											price: '-<?php echo esc_attr( number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $GLOBALS['currency']->convert_price( $this->order_totals->discount_total ) : $this->order_totals->discount_total, 2, '.', '' ) ); ?>',
											currency: '<?php echo esc_attr( strtoupper( $paypal_currency ) ); ?>',
										},
										<?php }?><?php 
										if( $this->order_totals->tip_total > 0 ){ ?>
										{
											name: '<?php echo esc_attr( htmlspecialchars( wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_tip' ), ENT_QUOTES ) ); ?>',
											quantity: '1',
											price: '-<?php echo esc_attr( number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $GLOBALS['currency']->convert_price( $this->order_totals->tip_total ) : $this->order_totals->tip_total, 2, '.', '' ) ); ?>',
											currency: '<?php echo esc_attr( strtoupper( $paypal_currency ) ); ?>',
										},
										<?php }?><?php 
										if ( count( $this->tax->fees ) > 0 ) { foreach ( $this->tax->fees as $fee ) {?>
										{
											name: '<?php echo esc_attr( htmlspecialchars( $fee->label ) ); ?>',
											quantity: '1',
											price: '<?php echo esc_attr( number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $GLOBALS['currency']->convert_price( $fee->amount ) : $fee->amount, 2, '.', '' ) ); ?>',
											currency: '<?php echo esc_attr( strtoupper( $paypal_currency ) ); ?>',
										},
										<?php } } ?>
									]<?php if( $is_payment_page && strlen( $GLOBALS['ec_user']->shipping->country ) == 2 ){ ?>,
									shipping_address: {
										recipient_name:	'<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->first_name, ENT_QUOTES ) . " " . htmlspecialchars( $GLOBALS['ec_user']->shipping->last_name, ENT_QUOTES ) ); ?>',
										line1: '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->address_line_1, ENT_QUOTES ) ); ?>'<?php if( $GLOBALS['ec_user']->shipping->address_line_2 != "" ){ ?>,
										line2: '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->address_line_2, ENT_QUOTES ) ); ?>'<?php }?>,
										city: '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->city, ENT_QUOTES ) ); ?>'<?php if( ( strtoupper( $GLOBALS['ec_user']->shipping->country ) == "AU" || strtoupper( $GLOBALS['ec_user']->shipping->country ) == "BR" || strtoupper( $GLOBALS['ec_user']->shipping->country ) == "CA" || strtoupper( $GLOBALS['ec_user']->shipping->country ) == "IN" || strtoupper( $GLOBALS['ec_user']->shipping->country ) == "IT" || strtoupper( $GLOBALS['ec_user']->shipping->country ) == "JP" || strtoupper( $GLOBALS['ec_user']->shipping->country ) == "MX" || strtoupper( $GLOBALS['ec_user']->shipping->country ) == "TH" || strtoupper( $GLOBALS['ec_user']->shipping->country ) == "US" ) && strlen( $GLOBALS['ec_cart_data']->cart_data->shipping_state ) == 2 ){ ?>,
										state: '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->state, ENT_QUOTES ) ); ?>'<?php }?>,
										postal_code: '<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->zip, ENT_QUOTES ) ); ?>',
										country_code: '<?php echo esc_attr( htmlspecialchars( strtoupper( $GLOBALS['ec_user']->shipping->country ), ENT_QUOTES ) ); ?>'
									}
									<?php }?>
								}
							}
						]
					}<?php if( $is_payment_page ){ ?>,
					experience: {
						input_fields: {
							no_shipping: 2,
							address_override: 1
						}
					}<?php }?>,
					meta: {
						partner_attribution_id: 'LevelFourDevelopmentLLC_Cart'
					}
				} ).catch( function( err ){
					alert( '<?php echo str_replace( "'", "\'", wp_easycart_language( )->get_text( "ec_errors", "payment_failed" ) ); ?>' );
					console.log( err );
					reject( err );
				} );
			},
			onAuthorize: function( data, actions ){
				jQuery( document.getElementById( 'paypal-success-cover' ) ).delay( 600 ).fadeIn( 'slow' );
				window.location = '<?php echo esc_url_raw( $this->cart_page . $this->permalink_divider . "ec_page=checkout_paypal_authorized" ); ?><?php if( !$is_payment_page ){ echo "&ec_firstpage=1"; } ?>' + '&orderID=' + data.orderID + '&payerID=' + data.payerID + '&paymentID=' + data.paymentID + '&paymentToken=' + data.paymentToken;
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