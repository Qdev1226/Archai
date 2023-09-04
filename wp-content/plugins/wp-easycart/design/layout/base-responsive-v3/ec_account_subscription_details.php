<h3 class="ec_account_subscription_title"><?php $this->subscription->display_title( ); ?></h3>


<?php if( $this->subscription->has_membership_page( ) ){ ?><div class="ec_account_subscription_row"><?php $this->subscription->display_membership_page_link( wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_line_5" ) ); ?></div><?php } ?>


<?php if( !$this->subscription->is_canceled( ) ){ ?><div class="ec_account_subscription_row"><b><?php echo wp_easycart_language( )->get_text( 'account_subscriptions', 'subscription_details_next_billing' ); ?>:</b> <?php $this->subscription->display_next_bill_date( ); ?></div><?php }?>


<div class="ec_account_subscription_row"><b><?php echo wp_easycart_language( )->get_text( 'account_subscriptions', 'subscription_details_last_payment' ); ?>:</b> <?php $this->subscription->display_last_bill_date( ); ?></div>


<div class="ec_account_subscription_row"><?php $this->subscription->display_price( ); ?></div>


<?php if( !$this->subscription->is_canceled( ) ){ ?>


<div class="ec_account_subscription_row last_spacer"><?php $GLOBALS['ec_user']->display_card_type( ); ?> **** <?php $GLOBALS['ec_user']->display_last4( ); ?> | <a href="#" onclick="return show_billing_info( );" class="ec_account_subscription_link">change billing method</a></div>

<?php $this->display_subscription_update_form_start( ); ?>

<?php if( $this->subscription->has_upgrades( ) ){ ?>

<div class="ec_account_subscription_upgrade_row"><?php $this->subscription->display_upgrade_dropdown( ); ?></div>

<?php }?>

<div id="ec_account_subscription_billing_information" class="ec_account_subscription_billing">

    <div class="ec_cart_subscription_holder_left">

        <div class="ec_cart_header ec_top">
			<?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'account_billing_information_title' )?>
        </div>
        
        <?php if( get_option( 'ec_option_display_country_top' ) ){ ?>
        <div class="ec_cart_input_row">
            <label for="ec_account_billing_information_country"><?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'account_billing_information_country' )?>*</label>
            <?php $this->display_account_billing_information_country_input( ); ?>
            <div class="ec_cart_error_row" id="ec_account_billing_information_country_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_select_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'account_billing_information_country' ); ?>
            </div>
        </div>
        <?php } ?>
        
        <div class="ec_cart_input_row">
            <label for="ec_account_billing_information_first_name"><?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'account_billing_information_first_name' )?>*</label>
            <?php $this->display_account_billing_information_first_name_input(); ?>
            <div class="ec_cart_error_row" id="ec_account_billing_information_first_name_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'account_billing_information_first_name' ); ?>
            </div>
        </div>
        
        <div class="ec_cart_input_row">
            <label for="ec_account_billing_information_last_name"><?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'account_billing_information_last_name' )?>*</label>
            <?php $this->display_account_billing_information_last_name_input(); ?>
            <div class="ec_cart_error_row" id="ec_account_billing_information_last_name_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'account_billing_information_last_name' ); ?>
            </div>
        </div>
        
        <?php if( get_option( 'ec_option_enable_company_name' ) ){ ?>
        <div class="ec_cart_input_row">
            <label for="ec_account_billing_information_company_name"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_company_name' ); ?></label>
            <input type="text" name="ec_account_billing_information_company_name" id="ec_account_billing_information_company_name" class="ec_account_billing_information_input_field" value="<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->company_name, ENT_QUOTES ) ); ?>">
            <div class="ec_cart_error_row" id="ec_account_billing_information_company_name_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'cart_billing_information_company_name' ); ?>
            </div>
        </div>
        <?php } ?>
        
        
        <div class="ec_cart_input_row">
            <label for="ec_account_billing_information_address"><?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'account_billing_information_address' )?>*</label>
            <?php $this->display_account_billing_information_address_input(); ?>
            <div class="ec_cart_error_row" id="ec_account_billing_information_address_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'account_billing_information_address' ); ?>
            </div>
        </div>
        <?php if( get_option( 'ec_option_use_address2' ) ){ ?>
        <div class="ec_cart_input_row">
        	<?php $this->display_account_billing_information_address2_input(); ?>
        </div>
        <?php }?>
        
        <div class="ec_cart_input_row">
            <label for="ec_account_billing_information_city"><?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'account_billing_information_city' )?>*</label>
            <?php $this->display_account_billing_information_city_input(); ?>
            <div class="ec_cart_error_row" id="ec_account_billing_information_city_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'account_billing_information_city' ); ?>
        	</div>
        </div>
        
        <div class="ec_cart_input_row">
            <label for="ec_account_billing_information_state"><?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'account_billing_information_state' )?><span id="ec_billing_state_required">*</span></label>
            <?php $this->display_account_billing_information_state_input(); ?>
            <div class="ec_cart_error_row" id="ec_account_billing_information_state_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'account_billing_information_state' ); ?>
            </div>
        </div>
    	
        <div class="ec_cart_input_row">
            <label for="ec_account_billing_information_zip"><?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'account_billing_information_zip' )?>*</label>
            <?php $this->display_account_billing_information_zip_input(); ?>
            <div class="ec_cart_error_row" id="ec_account_billing_information_zip_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'account_billing_information_zip' ); ?>
            </div>
        </div>
		
        <?php if( !get_option( 'ec_option_display_country_top' ) ){ ?>
        <div class="ec_cart_input_row">
            <label for="ec_account_billing_information_country"><?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'account_billing_information_country' )?>*</label>
            <?php $this->display_account_billing_information_country_input( ); ?>
            <div class="ec_cart_error_row" id="ec_account_billing_information_country_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_select_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'account_billing_information_country' ); ?>
            </div>
        </div>
        <?php } ?>
        
        <?php if( get_option( 'ec_option_collect_user_phone' ) ){ ?>
		<div class="ec_cart_input_row">
            <label for="ec_account_billing_information_phone"><?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'account_billing_information_phone' )?>*</label>
            <?php $this->display_account_billing_information_phone_input(); ?>
            <div class="ec_cart_error_row" id="ec_account_billing_information_phone_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'account_billing_information_phone' ); ?>
            </div>
        </div>
        <?php }?>

    </div>

    <div class="ec_cart_subscription_holder_right" style="width:100%; float:left; border-top:1px solid #CCC; margin-top:10px; padding-top:10px;">

		<div style="float:none;">

            <div class="form-row" style="margin-top:12px;float:left;width:100%;">
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
                <div style='font-family: "HelveticaNeue", "HelveticaNeue-Light", "Helvetica Neue Light", helvetica, arial, sans-serif; font-size: 14px; text-align: center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; width: 350px; top: 50%; left: 50%; position: absolute; margin-left: -165px; margin-top: -80px; cursor: pointer; text-align: center;'>
                    <div class="paypal-checkout-loader">
                        <div style="height: 30px; width: 30px; display: inline-block; box-sizing: content-box; opacity: 1; filter: alpha(opacity=100); -webkit-animation: rotation .7s infinite linear; -moz-animation: rotation .7s infinite linear; -o-animation: rotation .7s infinite linear; animation: rotation .7s infinite linear; border-left: 8px solid rgba(0, 0, 0, .2); border-right: 8px solid rgba(0, 0, 0, .2); border-bottom: 8px solid rgba(0, 0, 0, .2); border-top: 8px solid #fff; border-radius: 100%;"></div>
                    </div>
                </div>
            </div>
            <script><?php
				if( get_option( 'ec_option_payment_process_method' ) == 'stripe' )
					$pkey = get_option( 'ec_option_stripe_public_api_key' );
				else if( get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' && get_option( 'ec_option_stripe_connect_use_sandbox' ) )
					$pkey = get_option( 'ec_option_stripe_connect_sandbox_publishable_key' );
				else
					$pkey = get_option( 'ec_option_stripe_connect_production_publishable_key' );	
				?>
				<?php 
					$stripe_payment_intent_client_secret = $this->get_stripe_intent_client_secret( );
				?>
				jQuery( document.getElementById( 'stripe-success-cover' ) ).appendTo( document.body );
				try {
					var clientSecret = '<?php echo esc_attr( $stripe_payment_intent_client_secret ); ?>';
					var stripe = Stripe( '<?php echo esc_attr( $pkey ); ?>' );
					var elements = stripe.elements( );
					var style = {
						base: {
							color: '#32325d',
							fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
							fontSmoothing: 'antialiased',
							fontSize: '16px',
							'::placeholder': {
							  color: '#aab7c4'
							}
						},
						invalid: {
							color: '#fa755a',
							iconColor: '#fa755a'
						}
					};
					var card = elements.create( 'card', {style: style, hidePostalCode: true} );
					card.mount( '#ec_stripe_card_row' );
					card.addEventListener( 'change', function( event ){
						var displayError = document.getElementById( 'ec_card_errors' );
						if( event.error ){
							displayError.textContent = event.error.message;
						}else{
							displayError.textContent = '';
						}
					} );
					var form = document.getElementById( 'ec_submit_update_form' );
					form.addEventListener( 'submit', function( event ){
						if( jQuery( document.getElementById( 'ec_account_subscription_billing_information' ) ).is(":visible") ){
                            var payment_method = "credit_card";
                            event.preventDefault( );
                            jQuery( document.getElementById( 'ec_cart_submit_order' ) ).hide( );
                            jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).show( );
                            jQuery( document.getElementById( 'stripe-success-cover' ) ).show( );
                            jQuery( document.getElementById( 'ec_stripe_dynamic_error' ) ).hide( );
                            jQuery( document.getElementById( 'ec_card_errors' ) ).hide( );
                            var name = jQuery( document.getElementById( 'ec_account_billing_information_first_name' ) ).val( ) + ' ' + jQuery( document.getElementById( 'ec_account_billing_information_last_name' ) ).val( );
                            var address1 = jQuery( document.getElementById( 'ec_account_billing_information_address' ) ).val( );
                            var address2 = jQuery( document.getElementById( 'ec_account_billing_information_address2' ) ).val( );
                            var city = jQuery( document.getElementById( 'ec_account_billing_information_city' ) ).val( );
                            var state = jQuery( document.getElementById( 'ec_account_billing_information_state' ) ).val( );
                            if( jQuery( document.getElementById( 'ec_account_billing_information_state_' + jQuery( document.getElementById( 'ec_account_billing_information_country' ) ).val( ) ) ).length ){
                                state = jQuery( document.getElementById( 'ec_account_billing_information_state_' + jQuery( document.getElementById( 'ec_account_billing_information_country' ) ).val( ) ) ).val( );
                            }
                            var zip = jQuery( document.getElementById( 'ec_account_billing_information_zip' ) ).val( );
                            var country = jQuery( document.getElementById( 'ec_account_billing_information_country' ) ).val( );
                            var billing_details = {
                                address: {
                                    city: city,
                                    country: country,
                                    line1: address1,
                                    line2: address2,
                                    postal_code: zip,
                                    state: state
                                },
                                name: name
                            };
                            var additionalData = {
                                billing_details: billing_details,
                            };
                            var quantity = 1;
                            if( jQuery( document.getElementById( 'ec_quantity_<?php echo esc_attr( $this->subscription->subscription_id ); ?>' ) ).length ){
                                quantity = jQuery( document.getElementById( 'ec_quantity_<?php echo esc_attr( $this->subscription->subscription_id ); ?>' ) ).val( );
                            }
                            stripe.handleCardSetup( clientSecret, card, {
                                payment_method_data: {
                                    billing_details: {
                                        address: {
                                            city: city,
                                            country: country,
                                            line1: address1,
                                            line2: address2,
                                            postal_code: zip,
                                            state: state
                                        },
                                        name: name
                                    }
                                }
                            } ).then( function( result ){
                                if( result.error ){
                                    var errorElement = document.getElementById( 'ec_card_errors' );
                                    errorElement.textContent = result.error.message;
                                    jQuery( document.getElementById( 'ec_submit_order_error' ) ).show( );
                                    jQuery( document.getElementById( 'ec_cart_submit_order' ) ).show( );
                                    jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).hide( );
                                    jQuery( document.getElementById( 'stripe-success-cover' ) ).hide( );
                                }else{
                                    var data = {
                                        action: 'ec_ajax_get_stripe_update_customer_card',
                                        language: wpeasycart_ajax_object.current_language,
                                        subscription_id: <?php echo (int) $this->subscription->subscription_id; ?>,
                                        quantity: quantity,
                                        payment_id: result.setupIntent.payment_method,
                                        setup_intent_id: result.setupIntent.id,
                                        stripe_subscription_id: jQuery( document.getElementById( 'stripe_subscription_id' ) ).val( ),
                                        billing_details: billing_details,
                                        ec_selected_plan: jQuery( document.getElementById( 'ec_selected_plan' ) ).val( ),
										nonce: '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-get-stripe-update-customer-card-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>'
                                    };
                                    jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( result ){
                                        var json = JSON.parse( result );
                                        jQuery( location ).attr( 'href', json.url );
                                    } } );
                                }
                            } );
                        }
					} );
				}catch( err ){
					alert( "Your WP EasyCart with Stripe has a problem: " + err.message + ". Contact WP EasyCart for assistance." );
				}
			</script>
            
        </div>

	</div>

	<div class="ec_account_subscription_details_notice"><?php echo wp_easycart_language( )->get_text( 'account_subscriptions', 'subscription_details_notice' ); ?></div>

    <div class="ec_cart_error_row" id="ec_terms_error">
		<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_payment_accept_terms' )?> 
    </div>
    
    <?php if( get_option( 'ec_option_require_terms_agreement' ) ){ ?>
    <div class="ec_cart_input_row ec_agreement_section">
        <input type="checkbox" name="ec_terms_agree" id="ec_terms_agree" value="1"  /> <?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_review_agree' )?>
    </div>
    <?php }else{ ?>
        <input type="hidden" name="ec_terms_agree" id="ec_terms_agree" value="2"  />
    <?php }?>

	<?php if( !get_option( 'ec_option_subscription_one_only' ) ){ ?>
	<table class="ec_cartitem_quantity_table ec_account_subscription_table">
        <tbody>
            <tr>
                <td class="ec_minus_column">
                    <input type="button" value="-" class="ec_minus" onclick="ec_minus_quantity( '<?php echo esc_attr( $this->subscription->subscription_id ); ?>', 1 );" /></td>
                <td class="ec_quantity_column"><input type="number" value="<?php echo esc_attr( $this->subscription->quantity ); ?>" id="ec_quantity_<?php echo esc_attr( $this->subscription->subscription_id ); ?>" name="ec_quantity" autocomplete="off" step="1" min="1" class="ec_quantity" /></td>
                <td class="ec_plus_column"><input type="button" value="+" class="ec_plus" onclick="ec_plus_quantity( '<?php echo esc_attr( $this->subscription->subscription_id ); ?>', 0, 1000000 );" /></td>
            </tr>
        </tbody>
    </table>
    <?php }?>

</div>

<div class="ec_account_subscription_button"><input type="submit" value="<?php echo wp_easycart_language( )->get_text( 'account_subscriptions', 'save_changes_button' ); ?>" onclick="return ec_check_update_subscription_info( );" /></div>

<?php $this->display_subscription_update_form_end( ); ?>

<?php }?>

<h3><?php echo wp_easycart_language( )->get_text( 'account_subscriptions', 'subscription_details_past_payments' ); ?></h3>

<div class="ec_account_subscriptions_past_payments"><?php $this->subscription->display_past_payments( ); ?></div>

<?php if( !$this->subscription->is_canceled( ) ){ ?>

<hr />

<div  class="ec_account_subscription_button"><?php $this->subscription->display_cancel_form( wp_easycart_language( )->get_text( 'account_subscriptions', 'cancel_subscription_button' ), wp_easycart_language( )->get_text( 'account_subscriptions', 'cancel_subscription_confirm_text' ) ); ?></div>

<?php } ?>

<?php if( get_option( 'ec_option_cache_prevent' ) ){ ?>
<script type="text/javascript">
	wpeasycart_account_billing_country_update( );
	wpeasycart_account_shipping_country_update( );
	jQuery( document.getElementById( 'ec_account_billing_information_country' ) ).change( function( ){ wpeasycart_account_billing_country_update( ); } );
	jQuery( document.getElementById( 'ec_account_shipping_information_country' ) ).change( function( ){ wpeasycart_account_shipping_country_update( ); } );
</script>
<?php }?>

<div style="clear:both;"></div>
<div id="ec_current_media_size"></div>