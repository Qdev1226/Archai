<?php if ( ! isset( $cartpage ) ) {
	$cartpage = $this;
} ?>

<?php if ( ! $cartpage->page_allowed( 'shipping' ) ) { ?>
	<div class="ec_cart_header ec_cart_header_no_border">
		<?php echo wp_easycart_language( )->get_text( 'cart_shipping_method', 'cart_shipping_method_title' ); ?>
	</div>

	<div class="ec_cart_locked_panel">
		We will display shipping methods as soon as your shipping address is complete.
	</div>

<?php } else { ?>
	<?php if ( ! $cartpage->validate_cart_shipping() ) { ?>
	<div class="ec_cart_header ec_cart_header_no_border">
		<?php echo wp_easycart_language( )->get_text( 'cart_shipping_method', 'cart_shipping_method_title' ); ?>
	</div>

	<div class="ec_cart_locked_panel ec_cart_location_error">
		<?php echo wp_easycart_language()->get_text( "ec_errors", "cart_location_error" ); ?>
	</div>

	<?php } else { ?>
	<?php if ( '' != get_option( 'ec_option_google_ga4_property_id' ) ) { ?>
	<script>
		jQuery( document ).ready( function() {
			<?php if ( get_option( 'ec_option_google_ga4_tag_manager' ) ) { ?>
			dataLayer.push( { ecommerce: null } );
			dataLayer.push( {
				event: "add_shipping_info",
				ecommerce: {
			<?php } else { ?>
			gtag( "event", "add_shipping_info", {
			<?php }?>
				currency: "<?php echo esc_attr( $GLOBALS['currency']->get_currency_code( ) ); ?>",
				value: <?php echo esc_attr( number_format( $cartpage->order_totals->grand_total, 2, '.', '' ) ); ?>,
				coupon_code: "<?php echo esc_attr( $cartpage->coupon_code ); ?>",
				shipping_tier: "<?php echo esc_attr( trim( strip_tags( $cartpage->shipping->get_selected_shipping_method() ) ) ); ?>",
				items: [
				<?php for( $i=0; $i < count( $cartpage->cart->cart ); $i++ ) { ?>
					{
						item_id: "<?php echo esc_attr( $cartpage->cart->cart[$i]->model_number ); ?>",
						item_name: "<?php echo esc_attr( $cartpage->cart->cart[$i]->title ); ?>",
						index: <?php echo esc_attr( $i ); ?>,
						price: <?php echo esc_attr( number_format( $cartpage->cart->cart[$i]->unit_price, 2, '.', '' ) ); ?>,
						item_brand: "<?php echo esc_attr( $cartpage->cart->cart[$i]->manufacturer_name ) ; ?>",
						quantity: <?php echo esc_attr( number_format( $cartpage->cart->cart[$i]->quantity, 2, '.', '' ) ); ?>
					},
				<?php } ?>
				]
			<?php if ( ! get_option( 'ec_option_google_ga4_tag_manager' ) ) { ?>} );<?php } else { ?>} } );<?php }?>
		} );
	</script>
	<?php }?>

	<?php if( get_option( 'ec_option_onepage_checkout_tabbed' ) ) { ?>
	<div class="ec_cart_review_box">
		<div class="ec_cart_review_row">
			<div class="ec_cart_review_label">Contact</div>
			<div class="ec_cart_review_info"><?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->email, ENT_QUOTES ) ); ?></div>
			<div class="ec_cart_review_button"><a href="#" onclick="return wp_easycart_goto_page_v2( 'information', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-goto-cart-page-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );">Change</a></div>
		</div>
		<div class="ec_cart_review_row">
			<div class="ec_cart_review_label">Ship to</div>
			<div class="ec_cart_review_info"><?php
				echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1, ENT_QUOTES ) );
				if ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 ) {
					echo ', ' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2, ENT_QUOTES ) );
				}
				echo ', ' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_city, ENT_QUOTES ) );
				if ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_state ) {
					echo ' ' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_state, ENT_QUOTES ) );
				}
				if ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_zip ) {
					echo ', ' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_zip, ENT_QUOTES ) );
				}
				echo ' ' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_country, ENT_QUOTES ) );
			?></div>
			<div class="ec_cart_review_button"><a href="#" onclick="return wp_easycart_goto_page_v2( 'information', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-goto-cart-page-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );">Change</a></div>
		</div>
	</div>
	<?php }?>

	<?php if ( get_option( 'ec_option_onepage_checkout_tabbed' ) ) {
		$cartpage->display_page_two_form_start(); ?>
		<input type="hidden" name="wpeasycart_checkout_nonce" id="wpeasycart_checkout_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-save-shipping-method-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>" />
	<?php }?>

	<div class="ec_cart_header ec_cart_header_no_border">
		<?php echo wp_easycart_language( )->get_text( 'cart_shipping_method', 'cart_shipping_method_title' ); ?>
	</div>

	<div class="ec_cart_error_row" id="ec_cart_shipping_method_error">
		<?php echo wp_easycart_language( )->get_text( 'cart_shipping_method', 'cart_shipping_method_please_select_one' ); ?>
	</div>

	<div class="ec_cart_shipping_table">
		<?php $cartpage->ec_cart_display_shipping_methods( wp_easycart_language( )->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ), wp_easycart_language( )->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ), "RADIO" ); ?>
	</div>

	<?php if( get_option( 'ec_option_onepage_checkout_tabbed' ) ) { ?>
	<div class="ec_cart_bottom_nav_v2 ec_cart_bottom_nav_tabbed">
		<div class="ec_cart_bottom_nav_left">
			<a href="#" class="ec_cart_bottom_nav_back" onclick="return wp_easycart_goto_page_v2( 'information', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-goto-cart-page-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );">Return to Information</a>
		</div>
		<div class="ec_cart_bottom_nav_right ec_cart_button_column">
			<input type="button" value="Continue to Payment" onclick="return wp_easycart_goto_payment_v2();" class="ec_cart_button" />
		</div>
	</div>
	<?php }?>

	<?php if ( get_option( 'ec_option_onepage_checkout_tabbed' ) ) { $cartpage->display_page_two_form_end(); } ?>

	<?php }?>
<?php }?>
