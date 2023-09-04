<?php $this->display_page_two_form_start( ); ?>
<div class="ec_cart_left">
	<div class="ec_cart_header ec_top">
		<?php echo wp_easycart_language( )->get_text( 'cart_shipping_method', 'cart_shipping_method_title' ); ?>
	</div>
	<div class="ec_cart_error_row" id="ec_cart_billing_country_error">
		<?php echo wp_easycart_language( )->get_text( 'cart_shipping_method', 'cart_shipping_method_please_select_one' ); ?>
	</div>
	<div class="ec_cart_input_row">
		<label for="ec_cart_billing_country"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_title' ); ?></label>
		<?php $this->ec_cart_display_shipping_methods( wp_easycart_language( )->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ),wp_easycart_language( )->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ), "RADIO" ); ?>
	</div>
	<?php do_action( 'wp_easycart_cart_shipping_post_methods' ); ?>
</div>

<div class="ec_cart_right">

	<div class="ec_cart_header ec_top">
		<?php echo wp_easycart_language( )->get_text( 'cart', 'your_cart_title' ); ?>
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
			<strong><?php echo esc_attr( $advanced_option_set->option_label ); ?>:</strong><br /><?php $dimensions = json_decode( $advanced_option_set->optionitem_value ); if( count( $dimensions ) == 2 ){ echo esc_attr( $dimensions[0] ); if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ echo "\""; } echo " x " . esc_attr( $dimensions[1] ); if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ echo "\""; } }else if( count( $dimensions ) == 4 ){ echo esc_attr( $dimensions[0] . " " . $dimensions[1] . "\" x " . $dimensions[2] . " " . $dimensions[3] ) . "\""; } ?><br />

			<?php }else{ ?>
			<dt><?php echo esc_attr( $advanced_option_set->option_label ); ?>: <?php echo esc_attr( $advanced_option_set->optionitem_value ); ?><?php
				if ( $advanced_option_set->optionitem_enable_custom_price_label && ( $advanced_option_set->optionitem_price != 0 || ( isset( $advanced_option_set->optionitem_price ) && $advanced_option_set->optionitem_price != 0 ) || ( isset( $advanced_option_set->optionitem_price_onetime ) && $advanced_option_set->optionitem_price_onetime != 0 ) ) ) {
					echo '<span class="ec_cart_line_optionitem_pricing"> ' . esc_attr( wp_easycart_language( )->convert_text( $advanced_option_set->optionitem_custom_price_label ) ) . '</span>';
				} else if ( $advanced_option_set->optionitem_price > 0 ) {
					echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price < 0 ){
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
		<div class="ec_cart_price_row_total" id="ec_cart_subtotal"><?php echo esc_attr( $this->cart->cart[$cartitem_index]->get_total( ) ); ?></div>
	</div>

	<?php }?>

	<?php if( get_option( 'ec_option_show_coupons' ) ){ ?>
	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_coupons', 'cart_coupon_title' )?>
	</div>
	<div class="ec_cart_error_message" id="ec_coupon_error"<?php if( $this->is_coupon_expired( ) ){ ?> style="display:block;"<?php }?>><?php echo esc_attr( $this->get_coupon_expiration_note( ) ); ?></div>
	<div class="ec_cart_success_message" id="ec_coupon_success"<?php if( isset( $this->coupon ) && !$this->is_coupon_expired( ) ){?> style="display:block;"<?php }?>><?php if( isset( $this->coupon ) ){ if( $this->discount->coupon_matches <= 0 ){ echo wp_easycart_language( )->get_text( 'cart_coupons', 'coupon_not_applicable' ); }else{ echo wp_easycart_language( )->convert_text( $this->coupon->message ); } } ?></div>
	<div class="ec_cart_input_row">
		<input type="text" name="ec_coupon_code" id="ec_coupon_code" value="<?php if( isset( $this->coupon ) ){ echo esc_attr( $this->coupon_code ); } ?>" placeholder="<?php echo wp_easycart_language( )->get_text( 'cart_coupons', 'cart_enter_coupon' )?>" />
	</div>
	<div class="ec_cart_button_row">
		<div class="ec_cart_button" id="ec_apply_coupon" onclick="ec_apply_coupon( '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-redeem-coupon-code-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );"><?php echo wp_easycart_language( )->get_text( 'cart_coupons', 'cart_apply_coupon' ); ?></div>
		<div class="ec_cart_button_working" id="ec_applying_coupon"><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_please_wait' )?></div>
	</div>
	<?php }?>
	<?php if( get_option( 'ec_option_show_giftcards' ) ){ ?>
	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_coupons', 'cart_gift_card_title' )?>
	</div>
	<div class="ec_cart_error_message" id="ec_gift_card_error"></div>
	<div class="ec_cart_success_message" id="ec_gift_card_success"<?php if( $this->gift_card != "" ){?> style="display:block;"<?php }?>><?php if( $this->gift_card != "" ){ echo esc_attr( $this->giftcard->message ); } ?></div>
	<div class="ec_cart_input_row">
		<input type="text" name="ec_gift_card" id="ec_gift_card" value="<?php echo esc_attr( $this->gift_card ); ?>" placeholder="<?php echo wp_easycart_language( )->get_text( 'cart_coupons', 'cart_enter_gift_code' )?>" />
	</div>
	<div class="ec_cart_button_row">
		<div class="ec_cart_button" id="ec_apply_gift_card" onclick="ec_apply_gift_card( '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-redeem-gift-card-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );"><?php echo wp_easycart_language( )->get_text( 'cart_coupons', 'cart_redeem_gift_card' ); ?></div>
		<div class="ec_cart_button_working" id="ec_applying_gift_card"><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_please_wait' )?></div>
	</div>
	<?php }?>

	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_title' ); ?>
	</div>
	<?php $this->load_cart_total_lines(); ?>

	<div class="ec_cart_error_row" id="ec_checkout_error">
		<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_checkout_details_errors' )?>
	</div>

	<?php if( $this->shipping->shipping_method != "live" || $this->shipping->has_live_rates ){ ?>
	<div class="ec_cart_button_row">
		<input type="submit" value="<?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_continue_payment' ); ?>" class="ec_cart_button ec_cart_button_shipping_next" onclick="wp_easycart_cart_shipping_next();" />
		<div class="wp-easycart-ld-ring wp-easycart-ld-spin" style="color:#fff"></div>
	</div>
	<?php } ?>

</div>
<?php $this->display_page_two_form_end( ); ?>

<div style="clear:both;"></div>
<div id="ec_current_media_size"></div>