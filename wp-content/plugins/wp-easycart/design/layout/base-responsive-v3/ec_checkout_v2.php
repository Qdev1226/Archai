<?php if ( ( get_option( 'ec_option_payment_process_method' ) == 'stripe' || get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) ) {
	if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
		$pkey = get_option( 'ec_option_stripe_public_api_key' );
	} else if ( get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' && get_option( 'ec_option_stripe_connect_use_sandbox' ) ) {
		$pkey = get_option( 'ec_option_stripe_connect_sandbox_publishable_key' );
	} else {
		$pkey = get_option( 'ec_option_stripe_connect_production_publishable_key' );
	} ?>
<script>
	const stripe = Stripe( '<?php echo esc_attr( $pkey ); ?>' );
</script>
<?php } else if ( 'square' == get_option( 'ec_option_payment_process_method' ) ) {
	$this->print_square_payment_script( true );
} ?>

<?php if ( $this->cart->total_items > 0 ) { ?>
<?php if( get_option( 'ec_option_enable_recaptcha' ) && get_option( 'ec_option_enable_recaptcha_cart' ) && get_option( 'ec_option_recaptcha_site_key' ) != '' ){ ?>
<input type="hidden" id="ec_grecaptcha_site_key" value="<?php echo esc_attr( get_option( 'ec_option_recaptcha_site_key' ) ); ?>" />
<?php } ?>

<?php if ( get_option( 'ec_option_onepage_checkout_tabbed' ) ) { ?>
<div class="ec_cart_breadcrumbs_v2">
	<a href="<?php echo esc_url_raw( $this->cart_page . $this->permalink_divider . 'eccheckout=cart' ); ?>" class="ec_cart_breadcrumb_item_v2" onclick="return wp_easycart_goto_page_v2( 'cart', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-goto-cart-page-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );" id="wpeasycart_cart_page_link">Cart</a>

	<span class="dashicons dashicons-arrow-right-alt2"></span>
	<a href="<?php echo esc_url_raw( $this->cart_page . $this->permalink_divider . 'eccheckout=information' ); ?>" class="ec_cart_breadcrumb_item_v2" onclick="return wp_easycart_goto_page_v2( 'information', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-goto-cart-page-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );" id="wpeasycart_information_page_link">Information</a>

	<?php if ( get_option( 'ec_option_use_shipping' ) && $this->shipping_address_allowed && ( $this->cart->shippable_total_items > 0 || $this->order_totals->handling_total > 0 || $this->cart->excluded_shippable_total_items > 0 ) ) { ?>
	<span class="dashicons dashicons-arrow-right-alt2"></span>
	<a href="<?php echo esc_url_raw( $this->cart_page . $this->permalink_divider . 'eccheckout=shipping' ); ?>" class="ec_cart_breadcrumb_item_v2" onclick="return wp_easycart_goto_page_v2( 'shipping', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-goto-cart-page-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );" id="wpeasycart_shipping_page_link"<?php echo ( ! $this->page_allowed( 'shipping' ) ) ? ' class="wpeasycart-deactivated-link"' : ''; ?>>Shipping</a>
	<?php }?>

	<span class="dashicons dashicons-arrow-right-alt2"></span>
	<a href="<?php echo esc_url_raw( $this->cart_page . $this->permalink_divider . 'eccheckout=payment' ); ?>" class="ec_cart_breadcrumb_item_v2" onclick="return wp_easycart_goto_page_v2( 'payment', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-goto-cart-page-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );" id="wpeasycart_payment_page_link"<?php echo ( ! $this->page_allowed( 'payment' ) ) ? ' class="wpeasycart-deactivated-link"' : ''; ?>>Payment</a>
</div>
<?php }?>

<div class="ec_cart_mobile_summary">
	<div class="ec_cart_mobile_summary_header" onclick="wpeasycart_mobile_summary();">
		<div class="ec_cart_mobile_summary_header_label">Show order summary <span class="dashicons dashicons-arrow-down-alt2"></span><span class="dashicons dashicons-arrow-up-alt2"></span></div>
		<div class="ec_cart_mobile_summary_header_total" id="ec_cart_mobile_total"><?php echo esc_attr( $this->get_grand_total() ); ?></div>
	</div>
	<div class="ec_cart_mobile_summary_content">
		<?php for( $cartitem_index = 0; $cartitem_index<count( $this->cart->cart ); $cartitem_index++ ){ ?>
		<div class="ec_cart_price_row_v2 ec_cartitem_<?php echo esc_attr( $this->cart->cart[ $cartitem_index ]->cartitem_id ); ?> ec_cart_price_row_cartitem_<?php echo esc_attr( $cartitem_index ); ?>">
			<div class="ec_cart_image_row_v2">
				<img src="<?php echo esc_attr( $this->cart->cart[$cartitem_index]->get_image_url() ); ?>" alt="<?php echo esc_attr( str_replace( '"', '&quot;', $this->cart->cart[$cartitem_index]->title ) ); ?>" />
			</div>

			<div class="ec_cart_price_row_label_v2"><?php $this->cart->cart[$cartitem_index]->display_title( ); ?><?php if( $this->cart->cart[$cartitem_index]->grid_quantity > 1 ){ ?> x <?php echo esc_attr( $this->cart->cart[$cartitem_index]->grid_quantity ); ?><?php }else if( $this->cart->cart[$cartitem_index]->quantity > 1 ){ ?> x <?php echo esc_attr( $this->cart->cart[$cartitem_index]->quantity ); ?><?php }?>

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
				<dt><?php echo wp_easycart_escape_html( $advanced_option_set->optionitem_name ); ?>: <?php echo esc_attr( $advanced_option_set->optionitem_value ); ?><?php
					if ( $advanced_option_set->optionitem_enable_custom_price_label && ( $advanced_option_set->optionitem_price != 0 || ( isset( $advanced_option_set->optionitem_price ) && $advanced_option_set->optionitem_price != 0 ) || ( isset( $advanced_option_set->optionitem_price_onetime ) && $advanced_option_set->optionitem_price_onetime != 0 ) ) ) {
						echo '<span class="ec_cart_line_optionitem_pricing"> ' . esc_attr( wp_easycart_language( )->convert_text( $advanced_option_set->optionitem_custom_price_label ) ) . '</span>';
					} else if ( $advanced_option_set->optionitem_price > 0 ) {
						echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
					} else if ( $advanced_option_set->optionitem_price < 0 ) {
						echo ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
					} else if ( $advanced_option_set->optionitem_price_onetime > 0 ) {
						echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
					} else if ( $advanced_option_set->optionitem_price_onetime < 0 ) {
						echo ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
					} else if ( $advanced_option_set->optionitem_price_override > -1 ) {
						echo ' (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_override ) ) . ')';
					} ?></dt>
				<?php }else if( $advanced_option_set->option_type == "dimensions1" || $advanced_option_set->option_type == "dimensions2" ){ ?>
				<strong><?php echo wp_easycart_escape_html( $advanced_option_set->option_label ); ?>:</strong><br /><?php $dimensions = json_decode( $advanced_option_set->optionitem_value ); if( count( $dimensions ) == 2 ){ echo esc_attr( $dimensions[0] ); if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ echo "\""; } echo " x " . esc_attr( $dimensions[1] ); if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ echo "\""; } }else if( count( $dimensions ) == 4 ){ echo esc_attr( $dimensions[0] . " " . $dimensions[1] . "\" x " . $dimensions[2] . " " . $dimensions[3] ) . "\""; } ?><br />

				<?php }else{ ?>
				<dt><?php echo wp_easycart_escape_html( $advanced_option_set->option_label ); ?>: <?php echo esc_attr( $advanced_option_set->optionitem_value ); ?><?php
					if ( $advanced_option_set->optionitem_enable_custom_price_label && ( $advanced_option_set->optionitem_price != 0 || ( isset( $advanced_option_set->optionitem_price ) && $advanced_option_set->optionitem_price != 0 ) || ( isset( $advanced_option_set->optionitem_price_onetime ) && $advanced_option_set->optionitem_price_onetime != 0 ) ) ) {
						echo '<span class="ec_cart_line_optionitem_pricing"> ' . esc_attr( wp_easycart_language( )->convert_text( $advanced_option_set->optionitem_custom_price_label ) ) . '</span>';
					} else if( $advanced_option_set->optionitem_price > 0 ){
						echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
					} else if ( $advanced_option_set->optionitem_price < 0 ) {
						echo ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
					} else if ( $advanced_option_set->optionitem_price_onetime > 0 ) {
						echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
					} else if ( $advanced_option_set->optionitem_price_onetime < 0 ) {
						echo ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
					} else if ( $advanced_option_set->optionitem_price_override > -1 ) {
						echo ' (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_override ) ) . ')';
					} ?></dt>
				<?php } ?>
			<?php }?>
			</dl>
			<?php }?>

			<?php if( $this->cart->cart[$cartitem_index]->is_giftcard ){ ?>
			<dl>
			<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_recipient_name' ); ?>: <?php echo esc_attr( $this->cart->cart[$cartitem_index]->gift_card_to_name ); ?></dt>
			<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_recipient_email' ); ?>: <?php echo esc_attr( $this->cart->cart[$cartitem_index]->gift_card_email ); ?></dt>
			<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_sender_name' ); ?>: <?php echo esc_attr( $this->cart->cart[$cartitem_index]->gift_card_from_name ); ?></dt>
			<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_message' ); ?>: <?php echo esc_attr( $this->cart->cart[$cartitem_index]->gift_card_message ); ?></dt>
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
			<div class="ec_cart_price_row_total_v2"><?php echo esc_attr( $this->cart->cart[$cartitem_index]->get_total( ) ); ?></div>
		</div>
		<?php }?>

		<?php if ( ! get_option( 'ec_option_onepage_checkout_tabbed' ) ) { ?>
			<div class="ec_cart_show_cart">
				<a href="<?php echo esc_url_raw( $this->cart_page . $this->permalink_divider . 'eccheckout=cart' ); ?>" class="ec_cart_show_link" onclick="jQuery( '.ec_cart_mobile_summary_content' ).hide(); return wp_easycart_goto_page_v2( 'cart', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-goto-cart-page-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );" id="wpeasycart_cart_page_link">Edit Cart</a>
			</div>
		<?php } ?>

		<?php if( get_option( 'ec_option_show_coupons' ) ){ ?>
		<div class="ec_cart_error_message" id="ec_coupon_error_mobile"<?php if( $this->is_coupon_expired( ) ){ ?> style="display:block;"<?php }?>><?php echo esc_attr( $this->get_coupon_expiration_note( ) ); ?></div>
		<div class="ec_cart_success_message" id="ec_coupon_success_mobile"<?php if( isset( $this->coupon ) && !$this->is_coupon_expired( ) ){?> style="display:block;"<?php }?>><?php if( isset( $this->coupon ) ){ if( $this->discount->coupon_matches <= 0 ){ echo wp_easycart_language( )->get_text( 'cart_coupons', 'coupon_not_applicable' ); }else{ echo wp_easycart_language( )->convert_text( $this->coupon->message ); } } ?></div>
		<div class="ec_cart_input_row ec_cart_input_button_row">
			<div class="ec_cart_input_column">
				<input type="text" name="ec_coupon_code_mobile" id="ec_coupon_code_mobile" value="<?php if( isset( $this->coupon ) ){ echo esc_attr( $this->coupon_code ); } ?>" placeholder="<?php echo wp_easycart_language( )->get_text( 'cart_coupons', 'cart_enter_coupon' )?>" />
			</div>
			<div class="ec_cart_button_column">
				<div class="ec_cart_button" id="ec_apply_coupon_mobile" onclick="ec_apply_coupon( '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-redeem-coupon-code-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>', true );"><?php echo wp_easycart_language( )->get_text( 'cart_coupons', 'cart_simple_apply' ); ?></div>
				<div class="ec_cart_button_working" id="ec_applying_coupon_v2_mobile"><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_please_wait' )?></div>
			</div>
		</div>
		<?php }?>
		<?php if( get_option( 'ec_option_show_giftcards' ) ){ ?>
		<div class="ec_cart_error_message" id="ec_gift_card_error_mobile"></div>
		<div class="ec_cart_success_message" id="ec_gift_card_success_mobile"<?php if( $this->gift_card != "" ){?> style="display:block;"<?php }?>><?php if( $this->gift_card != "" ){ echo esc_attr( $this->giftcard->message ); } ?></div>
		<div class="ec_cart_input_row ec_cart_input_button_row">
			<div class="ec_cart_input_column">
				<input type="text" name="ec_gift_card_mobile" id="ec_gift_card_mobile" value="<?php echo esc_attr( $this->gift_card ); ?>" placeholder="<?php echo wp_easycart_language( )->get_text( 'cart_coupons', 'cart_enter_gift_code' ); ?>" />
			</div>
			<div class="ec_cart_button_column">
				<div class="ec_cart_button" id="ec_apply_gift_card_mobile" onclick="ec_apply_gift_card( '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-redeem-gift-card-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>', true );"><?php echo wp_easycart_language( )->get_text( 'cart_coupons', 'cart_simple_apply' ); ?></div>
				<div class="ec_cart_button_working" id="ec_applying_gift_card_v2_mobile"><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_please_wait' )?></div>
			</div>
		</div>
		<?php }?>
	</div>
</div>

<div class="ec_cart_left<?php echo ( isset( $current_screen ) && 'cart' == $current_screen ) ? ' ec_cart_full' : ''; ?>">
	
	<?php
	if ( get_option( 'ec_option_onepage_checkout_tabbed' ) ) {
		if ( isset( $current_screen ) && 'cart' == $current_screen ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_v2.php' ) ) {
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_v2.php' );
			} else {
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_v2.php' );
			}
		} else if ( isset( $current_screen ) && 'information' == $current_screen ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_information_v2.php' ) ) {
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_information_v2.php' );
			} else {
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_information_v2.php' );
			}
		} else if ( isset( $current_screen ) && 'shipping' == $current_screen ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_shipping_v2.php' ) ) {
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_shipping_v2.php' );
			} else {
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_shipping_v2.php' );
			}
		} else if ( isset( $current_screen ) && 'payment' == $current_screen ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_payment_v2.php' ) ) {
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_payment_v2.php' );
			} else {
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_payment_v2.php' );
			}
		}
	} else {
		$this->display_page_one_form_start( );
		?>
		<div class="ec_cart_onepage" id="ec_cart_onepage_cart">
		<?php if ( isset( $current_screen ) && 'cart' == $current_screen ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_v2.php' ) ) {
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_v2.php' );
			} else {
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_v2.php' );
			}
		} ?>
		</div>
		<div class="ec_cart_information" id="ec_cart_onepage_info"<?php echo ( isset( $current_screen ) && 'cart' == $current_screen ) ? ' style="display:none;"' : ''; ?>>
			<?php
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_information_v2.php' ) ) {
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_information_v2.php' );
			} else {
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_information_v2.php' );
			}
			?>
		</div>
		<?php if( get_option( 'ec_option_use_shipping' ) && $this->shipping_address_allowed && ( $this->cart->shippable_total_items > 0 || $this->order_totals->handling_total > 0 || $this->cart->excluded_shippable_total_items > 0 ) ) { ?>
			<div class="ec_cart_shipping" id="ec_cart_onepage_shipping"<?php echo ( isset( $current_screen ) && 'cart' == $current_screen ) ? ' style="display:none;"' : ''; ?>>
				<?php
				if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_shipping_v2.php' ) ) {
					include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_shipping_v2.php' );
				} else {
					include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_shipping_v2.php' );
				}
				?>
			</div>
		<?php } ?>
		<div class="ec_cart_payment" id="ec_cart_onepage_payment"<?php echo ( isset( $current_screen ) && 'cart' == $current_screen ) ? ' style="display:none;"' : ''; ?>>
			<?php
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_payment_v2.php' ) ) {
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_payment_v2.php' );
			} else {
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_payment_v2.php' );
			}
			?>
		</div>
		<?php
		$this->display_page_three_form_end( );
	}
	?>

</div>

<div class="ec_cart_right ec_cart_right_v2"<?php echo ( isset( $current_screen ) && 'cart' == $current_screen ) ? ' style="display:none;"' : ''; ?>>
	<div class="ec_cart_header ec_cart_header_no_border">
		Order Summary
	</div>
	<?php for( $cartitem_index = 0; $cartitem_index<count( $this->cart->cart ); $cartitem_index++ ){ ?>
	<div class="ec_cart_price_row_v2 ec_cartitem_<?php echo esc_attr( $this->cart->cart[ $cartitem_index ]->cartitem_id ); ?> ec_cart_price_row_cartitem_<?php echo esc_attr( $cartitem_index ); ?>">
		<div class="ec_cart_image_row_v2">
			<img src="<?php echo esc_attr( $this->cart->cart[$cartitem_index]->get_image_url() ); ?>" alt="<?php echo esc_attr( str_replace( '"', '&quot;', $this->cart->cart[$cartitem_index]->title ) ); ?>" />
		</div>
		
		<div class="ec_cart_price_row_label_v2"><?php $this->cart->cart[$cartitem_index]->display_title( ); ?><?php if( $this->cart->cart[$cartitem_index]->grid_quantity > 1 ){ ?> x <?php echo esc_attr( $this->cart->cart[$cartitem_index]->grid_quantity ); ?><?php }else if( $this->cart->cart[$cartitem_index]->quantity > 1 ){ ?> x <?php echo esc_attr( $this->cart->cart[$cartitem_index]->quantity ); ?><?php }?>

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
			<dt><?php echo wp_easycart_escape_html( $advanced_option_set->optionitem_name ); ?>: <?php echo esc_attr( $advanced_option_set->optionitem_value ); ?><?php
				if ( $advanced_option_set->optionitem_enable_custom_price_label && ( $advanced_option_set->optionitem_price != 0 || ( isset( $advanced_option_set->optionitem_price ) && $advanced_option_set->optionitem_price != 0 ) || ( isset( $advanced_option_set->optionitem_price_onetime ) && $advanced_option_set->optionitem_price_onetime != 0 ) ) ) {
					echo '<span class="ec_cart_line_optionitem_pricing"> ' . esc_attr( wp_easycart_language( )->convert_text( $advanced_option_set->optionitem_custom_price_label ) ) . '</span>';
				} else if ( $advanced_option_set->optionitem_price > 0 ) {
					echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price < 0 ) {
					echo ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price_onetime > 0 ) {
					echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price_onetime < 0 ) {
					echo ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price_override > -1 ) {
					echo ' (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_override ) ) . ')';
				} ?></dt>
			<?php }else if( $advanced_option_set->option_type == "dimensions1" || $advanced_option_set->option_type == "dimensions2" ){ ?>
			<strong><?php echo wp_easycart_escape_html( $advanced_option_set->option_label ); ?>:</strong><br /><?php $dimensions = json_decode( $advanced_option_set->optionitem_value ); if( count( $dimensions ) == 2 ){ echo esc_attr( $dimensions[0] ); if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ echo "\""; } echo " x " . esc_attr( $dimensions[1] ); if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ echo "\""; } }else if( count( $dimensions ) == 4 ){ echo esc_attr( $dimensions[0] . " " . $dimensions[1] . "\" x " . $dimensions[2] . " " . $dimensions[3] ) . "\""; } ?><br />

			<?php }else{ ?>
			<dt><?php echo wp_easycart_escape_html( $advanced_option_set->option_label ); ?>: <?php echo esc_attr( $advanced_option_set->optionitem_value ); ?><?php
				if ( $advanced_option_set->optionitem_enable_custom_price_label && ( $advanced_option_set->optionitem_price != 0 || ( isset( $advanced_option_set->optionitem_price ) && $advanced_option_set->optionitem_price != 0 ) || ( isset( $advanced_option_set->optionitem_price_onetime ) && $advanced_option_set->optionitem_price_onetime != 0 ) ) ) {
					echo '<span class="ec_cart_line_optionitem_pricing"> ' . esc_attr( wp_easycart_language( )->convert_text( $advanced_option_set->optionitem_custom_price_label ) ) . '</span>';
				} else if( $advanced_option_set->optionitem_price > 0 ){
					echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price < 0 ) {
					echo ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price_onetime > 0 ) {
					echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price_onetime < 0 ) {
					echo ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price_override > -1 ) {
					echo ' (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_override ) ) . ')';
				} ?></dt>
			<?php } ?>
		<?php }?>
		</dl>
		<?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->is_giftcard ){ ?>
		<dl>
		<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_recipient_name' ); ?>: <?php echo esc_attr( $this->cart->cart[$cartitem_index]->gift_card_to_name ); ?></dt>
		<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_recipient_email' ); ?>: <?php echo esc_attr( $this->cart->cart[$cartitem_index]->gift_card_email ); ?></dt>
		<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_sender_name' ); ?>: <?php echo esc_attr( $this->cart->cart[$cartitem_index]->gift_card_from_name ); ?></dt>
		<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_message' ); ?>: <?php echo esc_attr( $this->cart->cart[$cartitem_index]->gift_card_message ); ?></dt>
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
		<div class="ec_cart_price_row_total_v2"><?php echo esc_attr( $this->cart->cart[$cartitem_index]->get_total( ) ); ?></div>
	</div>
	<?php }?>
	
	<?php if ( ! get_option( 'ec_option_onepage_checkout_tabbed' ) ) { ?>
		<div class="ec_cart_show_cart">
			<a href="<?php echo esc_url_raw( $this->cart_page . $this->permalink_divider . 'eccheckout=cart' ); ?>" class="ec_cart_show_link" onclick="return wp_easycart_goto_page_v2( 'cart', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-goto-cart-page-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );" id="wpeasycart_cart_page_link">Edit Cart</a>
		</div>
	<?php } ?>

	<?php if( get_option( 'ec_option_show_coupons' ) ){ ?>
	<div class="ec_cart_error_message" id="ec_coupon_error"<?php if( $this->is_coupon_expired( ) ){ ?> style="display:block;"<?php }?>><?php echo esc_attr( $this->get_coupon_expiration_note( ) ); ?></div>
	<div class="ec_cart_success_message" id="ec_coupon_success"<?php if( isset( $this->coupon ) && !$this->is_coupon_expired( ) ){?> style="display:block;"<?php }?>><?php if( isset( $this->coupon ) ){ if( $this->discount->coupon_matches <= 0 ){ echo wp_easycart_language( )->get_text( 'cart_coupons', 'coupon_not_applicable' ); }else{ echo wp_easycart_language( )->convert_text( $this->coupon->message ); } } ?></div>
	<div class="ec_cart_input_row ec_cart_input_button_row">
		<div class="ec_cart_input_column">
			<input type="text" name="ec_coupon_code" id="ec_coupon_code" value="<?php if( isset( $this->coupon ) ){ echo esc_attr( $this->coupon_code ); } ?>" placeholder="<?php echo wp_easycart_language( )->get_text( 'cart_coupons', 'cart_enter_coupon' )?>" />
		</div>
		<div class="ec_cart_button_column">
			<div class="ec_cart_button" id="ec_apply_coupon" onclick="ec_apply_coupon( '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-redeem-coupon-code-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );"><?php echo wp_easycart_language( )->get_text( 'cart_coupons', 'cart_simple_apply' ); ?></div>
			<div class="ec_cart_button_working" id="ec_applying_coupon_v2"><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_please_wait' )?></div>
		</div>
	</div>
	<?php }?>
	<?php if( get_option( 'ec_option_show_giftcards' ) ){ ?>
	<div class="ec_cart_error_message" id="ec_gift_card_error"></div>
	<div class="ec_cart_success_message" id="ec_gift_card_success"<?php if( $this->gift_card != "" ){?> style="display:block;"<?php }?>><?php if( $this->gift_card != "" ){ echo esc_attr( $this->giftcard->message ); } ?></div>
	<div class="ec_cart_input_row ec_cart_input_button_row">
		<div class="ec_cart_input_column">
			<input type="text" name="ec_gift_card" id="ec_gift_card" value="<?php echo esc_attr( $this->gift_card ); ?>" placeholder="<?php echo wp_easycart_language( )->get_text( 'cart_coupons', 'cart_enter_gift_code' ); ?>" />
		</div>
		<div class="ec_cart_button_column">
			<div class="ec_cart_button" id="ec_apply_gift_card" onclick="ec_apply_gift_card( '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-redeem-gift-card-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );"><?php echo wp_easycart_language( )->get_text( 'cart_coupons', 'cart_simple_apply' ); ?></div>
			<div class="ec_cart_button_working" id="ec_applying_gift_card_v2"><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_please_wait' )?></div>
		</div>
	</div>
	<?php }?>
	
	<div style="width:100%; float:left; height:20px;" class="ec_cart_v2_spacer"></div>

	<?php $this->load_cart_total_lines(); ?>

	<?php do_action( 'wp_easycart_checkout_details_right_end', $this ); ?>

</div>

<?php do_action( 'wpeasycart_checkout_details_after' ); ?>

<?php if( get_option( 'ec_option_cache_prevent' ) ){ ?>
<script type="text/javascript">
	wpeasycart_cart_billing_country_update( );
	wpeasycart_cart_shipping_country_update( );
	jQuery( document.getElementById( 'ec_cart_billing_country' ) ).change( wpeasycart_cart_billing_country_update );
	jQuery( document.getElementById( 'ec_cart_shipping_country' ) ).change( wpeasycart_cart_shipping_country_update );
</script>
<?php }?>
<script>
	window.onpopstate = function( event ){
		var valid_pages = [ 'cart', 'information', 'shipping', 'payment' ];
		if ( null == event.state || undefined == event.state || null == event.state.eccheckout || undefined == event.state.eccheckout ) {
			wp_easycart_goto_page_v2( '<?php echo ( get_option( 'ec_option_onepage_checkout_cart_first' ) ) ? 'cart' : 'information'; ?>', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-goto-cart-page-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>', false );
		} else if ( valid_pages.includes( event.state.eccheckout ) ) {
			wp_easycart_goto_page_v2( event.state.eccheckout, '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-goto-cart-page-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>', false );
		}
	};
</script>
<?php if ( ! get_option( 'ec_option_onepage_checkout_tabbed' ) ) { ?>
<script type="text/javascript">
	if ( jQuery( document.getElementById( 'ec_cart_onepage_cart' ) ).length ) {
		jQuery( document ).on( 'scroll', function() {
			if ( '100%' != jQuery( '.ec_cart_left' ).css( 'width' ) ) {
				var cart_right_offset = jQuery( '.ec_cart_right' ).offset().top;
				if ( jQuery( document ).scrollTop() > cart_right_offset - 80 ) {
					jQuery( '.ec_cart_right' ).css( 'padding-top', ( jQuery( document ).scrollTop() - cart_right_offset + 115 ) + 'px' );
				} else {
					jQuery( '.ec_cart_right' ).css( 'padding-top', '0px' );
				}
			}
		} );
	}
</script>
<?php } ?>

<div style="clear:both;"></div>
<div id="ec_current_media_size"></div>
<?php }?>
