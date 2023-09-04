<?php
if( trim( get_option( 'ec_option_fb_pixel' ) ) != '' ){
	if( !isset( $_COOKIE['ec_cart_facebook_order_id_tracked_' . $order->order_id] ) ){
		echo "<script>
			fbq('track', 'Purchase', {
				content_type: 'product',
				value: " . esc_attr( number_format( $order->grand_total, 2, '.', '' ) ) . ",
				currency: '" . esc_attr( $GLOBALS['currency']->get_currency_code( ) ) . "',
				contents: [";
		for( $i=0; $i<count( $order->orderdetails ); $i++ ){
			if( $i > 0 )
				echo ", ";
			echo "{
				id: '" . esc_attr( $order->orderdetails[$i]->product_id ) . "',
				quantity: " . esc_attr( $order->orderdetails[$i]->quantity ) . ",
				price: " . esc_attr( $order->orderdetails[$i]->unit_price ) . "
			}";
		}		
		echo "]
			});
		</script>";
		setcookie( 'ec_cart_facebook_order_id_tracked_' . $order->order_id, 1, time( ) + ( 3600 * 24 * 30 ), "/" );
	}
}
?>

<?php if ( get_option( 'ec_option_googleanalyticsid' ) != "UA-XXXXXXX-X" && get_option( 'ec_option_googleanalyticsid' ) != "" ) { ?>
<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	ga('create', '<?php echo esc_attr( $google_urchin_code ); ?>', '<?php echo esc_attr( $google_wp_url ); ?>');
	ga('send', 'pageview');
	ga('require', 'ecommerce', 'ecommerce.js');
	<?php $this->print_google_transaction( ); ?>
	ga('ecommerce:send');
</script>
<?php } ?>

<?php if ( '' != get_option( 'ec_option_google_ga4_property_id' ) ) { ?>
<?php if ( get_option( 'ec_option_google_ga4_tag_manager' ) ) { ?>
<script>
	jQuery( document ).ready( function() {
		dataLayer.push( { ecommerce: null } );
		dataLayer.push( {
			event: "purchase",
			ecommerce: {
				transaction_id: "<?php echo esc_attr( $order->order_id ); ?>",
				value: <?php echo esc_attr( number_format( $order->grand_total, 2, '.', '' ) ); ?>,
				tax: <?php echo esc_attr( number_format( $order->tax_total + $order->vat_total + $order->hst_total + $order->gst_total + $order->pst_total + $order->duty_total, 2, '.', '' ) ); ?>,
				shipping: <?php echo esc_attr( number_format( $order->shipping_total, 2, '.', '' ) ); ?>,
				currency: "<?php echo esc_attr( $GLOBALS['currency']->get_currency_code( ) ); ?>",
				coupon: "<?php echo esc_attr( $order->promo_code ); ?>",
				items: [
				<?php for( $i=0; $i<count( $order->orderdetails ); $i++ ){ ?>
					{
						item_id: "<?php echo esc_attr( $order->orderdetails[$i]->model_number ); ?>",
						item_name: "<?php echo esc_attr( $order->orderdetails[$i]->title ); ?>",
						index: <?php echo esc_attr( (int) $i ); ?>,
						price: <?php echo esc_attr( number_format( $order->orderdetails[$i]->unit_price, 2, '.', '' ) ); ?>,
						item_brand: "<?php echo esc_attr( $order->orderdetails[$i]->manufacturer_name ); ?>",
						quantity: <?php echo esc_attr( number_format( $order->orderdetails[$i]->quantity, 2, '.', '' ) ); ?>
					},
				<?php } ?>
				]
			}
		} );
	} );
</script>
<?php } else { ?>
<script>
	jQuery( document ).ready( function() {
		gtag( "event", "purchase", {
			transaction_id: "<?php echo esc_attr( $order->order_id ); ?>",
			value: <?php echo esc_attr( number_format( $order->grand_total, 2, '.', '' ) ); ?>,
			tax: <?php echo esc_attr( number_format( $order->tax_total + $order->vat_total + $order->hst_total + $order->gst_total + $order->pst_total + $order->duty_total, 2, '.', '' ) ); ?>,
			shipping: <?php echo esc_attr( number_format( $order->shipping_total, 2, '.', '' ) ); ?>,
			currency: "<?php echo esc_attr( $GLOBALS['currency']->get_currency_code( ) ); ?>",
			coupon: "<?php echo esc_attr( $order->promo_code ); ?>",
			items: [
			<?php for( $i=0; $i<count( $order->orderdetails ); $i++ ){ ?>
				{
					item_id: "<?php echo esc_attr( $order->orderdetails[$i]->model_number ); ?>",
					item_name: "<?php echo esc_attr( $order->orderdetails[$i]->title ); ?>",
					index: <?php echo esc_attr( (int) $i ); ?>,
					price: <?php echo esc_attr( number_format( $order->orderdetails[$i]->unit_price, 2, '.', '' ) ); ?>,
					item_brand: "<?php echo esc_attr( $order->orderdetails[$i]->manufacturer_name ); ?>",
					quantity: <?php echo esc_attr( number_format( $order->orderdetails[$i]->quantity, 2, '.', '' ) ); ?>
				},
			<?php } ?>
			]
		} );
	} );
</script>
<?php } ?>
<?php } ?>

<?php if( get_option( 'ec_option_google_adwords_conversion_id' ) != "" ){ ?>
<!-- Google Code for WP EasyCart Sale Conversion Page -->
<script type="text/javascript">
	/* <![CDATA[ */
	var google_conversion_id = <?php echo esc_attr( get_option( 'ec_option_google_adwords_conversion_id' ) ); ?>;
	var google_transaction_id = "<?php echo esc_attr( $order->order_id ); ?>";
	var google_conversion_language = "<?php echo esc_attr( get_option( 'ec_option_google_adwords_language' ) ); ?>";
	var google_conversion_format = "<?php echo esc_attr( get_option( 'ec_option_google_adwords_format' ) ); ?>";
	var google_conversion_color = "<?php echo esc_attr( get_option( 'ec_option_google_adwords_color' ) ); ?>";
	var google_conversion_label = "<?php echo esc_attr( get_option( 'ec_option_google_adwords_label' ) ); ?>";
	var google_conversion_value = <?php echo esc_attr( number_format( $order->grand_total, 2, '.', '' ) ); ?>;
	var google_conversion_currency = "<?php echo esc_attr( get_option( 'ec_option_google_adwords_currency' ) ); ?>";
	var google_remarketing_only = <?php echo esc_attr( get_option( 'ec_option_google_adwords_remarketing_only' ) ); ?>;
	/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
<noscript>
	<div style="display:inline;">
	<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/<?php echo esc_attr( get_option( 'ec_option_google_adwords_conversion_id' ) ); ?>/?value=<?php echo esc_attr( number_format( $order->grand_total, 2, '.', '' ) ); ?>&amp;currency_code=<?php echo esc_attr( get_option( 'ec_option_google_adwords_currency' ) ); ?>&amp;label=<?php echo esc_attr( get_option( 'ec_option_google_adwords_label' ) ); ?>&amp;guid=ON&amp;script=0"/>
	</div>
</noscript>
<?php } ?>

<?php do_action( 'wpeasycart_success_page_content_top', $order_id, $order ); ?>

<?php if( isset( $error_code ) && $error_code == 'ideal-pending' ){ ?>
<div class="ec_cart_error_row2" style="margin-bottom:20px;">
    <?php echo wp_easycart_language( )->get_text( 'ec_errors', 'ideal_processing' )?> 
</div>
<?php } else if ( ! $order->is_approved && ( 7 == $order->orderstatus_id || 9 == $order->orderstatus_id || 19 == $order->orderstatus_id )  ) { ?>
<div class="ec_cart_error_row2" style="margin-bottom:20px;">
    <?php echo wp_easycart_language( )->get_text( 'ec_errors', 'delayed_payment_failed' )?> 
</div>
<?php } else if ( ! $order->is_approved && 16 == $order->orderstatus_id ) { ?>
<div class="ec_cart_error_row2" style="margin-bottom:20px;">
    <?php echo wp_easycart_language( )->get_text( 'ec_errors', 'order_refunded' )?> 
</div>
<?php } else if ( ! $order->is_approved ) { ?>
<div class="ec_cart_notice_row" style="margin-bottom:20px;">
    <?php echo wp_easycart_language( )->get_text( 'ec_errors', 'payment_processing' )?> 
</div>
<?php }?>

<div class="ec_cart_success_print_button_v2">
	<?php $this->display_print_receipt_link( '<span class="dashicons dashicons-printer"></span>' . wp_easycart_language( )->get_text( 'cart_success', 'cart_success_print_receipt_text' ), $order_id ); ?>
</div>

<div class="ec_order_success_row">
	<div class="ec_order_success_loader ec_order_success_loader_v2">
		<div class="ec_order_success_loader_loaded">
			<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 161.2 161.2" enable-background="new 0 0 161.2 161.2" xml:space="preserve">
				<path class="ec_order_success_loader_loaded_path" fill="none" stroke="<?php echo esc_attr( get_option( 'ec_option_details_main_color' ) ); ?>" stroke-miterlimit="10" d="M425.9,52.1L425.9,52.1c-2.2-2.6-6-2.6-8.3-0.1l-42.7,46.2l-14.3-16.4c-2.3-2.7-6.2-2.7-8.6-0.1c-1.9,2.1-2,5.6-0.1,7.7l17.6,20.3c0.2,0.3,0.4,0.6,0.6,0.9c1.8,2,4.4,2.5,6.6,1.4c0.7-0.3,1.4-0.8,2-1.5c0.3-0.3,0.5-0.6,0.7-0.9l46.3-50.1C427.7,57.5,427.7,54.2,425.9,52.1z"/>
				<circle class="ec_order_success_loader_loaded_path" fill="none" stroke="<?php echo esc_attr( get_option( 'ec_option_details_main_color' ) ); ?>" stroke-width="4" stroke-miterlimit="10" cx="80.6" cy="80.6" r="62.1"/>
				<polyline class="ec_order_success_loader_loaded_path" fill="none" stroke="<?php echo esc_attr( get_option( 'ec_option_details_main_color' ) ); ?>" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="113,52.8 74.1,108.4 48.2,86.4 "/>
			</svg>
		</div>
	</div>
	<div class="ec_order_success_column2">
		<p class="ec_cart_success_order_number ec_cart_success_order_number_v2"><?php echo wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_order_number' )?> #<?php echo esc_attr( $order_id ); ?></p>
		<h2 class="ec_cart_success_title ec_cart_success_title_v2"><?php echo wp_easycart_language( )->get_text( 'cart_success', 'cart_success_thank_you_title' ); ?></h2>
		<p class="ec_cart_success_subtitle ec_cart_success_subtitle_v2"><?php echo wp_easycart_language( )->get_text( 'cart_success', 'cart_success_will_receive_email' ); ?> <?php echo esc_attr( htmlspecialchars( $order->user_email, ENT_QUOTES ) ); ?><?php echo ( ( isset( $order->email_other ) && '' != $order->email_other ) ? ', ' . esc_attr( htmlspecialchars( $order->email_other, ENT_QUOTES ) ) : '' ); ?></p>

		<p class="ec_cart_success_continue_shopping_button ec_cart_success_continue_shopping_button_v2">
			<?php if( $order->has_downloads( ) && $order->is_approved ){ ?>
			<?php $order->display_order_link( wp_easycart_language( )->get_text( 'cart_success', 'cart_success_view_downloads' ) ); ?>

			<?php }else if( $order->has_downloads( ) ){ ?>
			<?php $order->display_order_link( wp_easycart_language( )->get_text( 'cart_success', 'cart_success_view_downloads' ) ); ?>

			<?php }?>

			<?php if( $order->has_membership_page( ) ){ ?>
				<a href="<?php echo esc_attr( $order->get_membership_page_link( ) ); ?>"><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_line_5" ); ?></a>
			<?php }?>

			<?php if( $GLOBALS['ec_cart_data']->cart_data->is_guest == "" ){
				echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider . "ec_page=order_details&order_id=" . $order_id ) . "\"> " . wp_easycart_language( )->get_text( 'cart_success', 'cart_payment_receipt_order_details_link' ) . "</a>";
			}else{
				echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider . "ec_page=order_details&order_id=" . $order_id . "&ec_guest_key=" . $GLOBALS['ec_cart_data']->cart_data->guest_key ) . "\">" . wp_easycart_language( )->get_text( 'cart_success', 'cart_payment_receipt_order_details_link' ) . "</a>";
			}
			?>

			<a href="<?php echo esc_attr( $this->return_to_store_page( $this->store_page ) ); ?>"><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_continue_shopping' ); ?></a>
		</p>
	</div>
</div>

<?php do_action( 'wpeasycart_success_page_content_middle', $order_id, $order ); ?>

<?php $order->display_order_customer_notes( ); ?>

<?php do_action( 'wpeasycart_success_page_content_bottom', $order_id, $order ); ?>

<div style="clear:both;"></div>
<div id="ec_current_media_size"></div>